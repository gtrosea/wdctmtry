<?php

namespace WDS\Admin\Table;

use WDS\Models\Replica;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Domain Class.
 *
 * A custom WordPress List Table to display and manage domains in the admin dashboard.
 */
class Domain extends \WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'domain',
				'plural'   => 'domains',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get available views (filters) for domains.
	 *
	 * @return array Array of views with filter links.
	 */
	protected function get_views() {
		$statuses = array(
			'active'      => __( 'Active', 'wds-notrans' ),
			'block'       => __( 'Block', 'wds-notrans' ),
			'unconnected' => __( 'Unconnected', 'wds-notrans' ),
		);

		$current_status = wds_sanitize_data_field( $_REQUEST, 'status' );

		$link = menu_page_url( 'wds-replica-domain', false );
		if ( ! $current_status ) {
			$tag = __( 'All', 'wds-notrans' );
		} else {
			$tag = sprintf( '<a href="%s">%s</a>', $link, __( 'All', 'wds-notrans' ) );
		}

		$status_links = array( 'all' => $tag );

		foreach ( (array) $statuses as $status_key => $status_title ) {
			$new_link = $link . '&status=' . $status_key;
			if ( $current_status == $status_key ) {
				$status_links[ $status_key ] = $status_title;
			} else {
				$status_links[ $status_key ] = sprintf( '<a href="%s">%s</a>', $new_link, $status_title );
			}
		}

		return $status_links;
	}

	/**
	 * Get the columns to be displayed in the table.
	 *
	 * @return array Array of columns.
	 */
	public function get_columns() {
		$columns = array(
			'cb'     => '<input type="checkbox" />',
			'name'   => __( 'Domain', 'wds-notrans' ),
			'user'   => __( 'User', 'wds-notrans' ),
			'status' => __( 'Status', 'wds-notrans' ),
		);

		return $columns;
	}

	/**
	 * Checkbox column for selecting domains.
	 *
	 * @param object $item The domain item.
	 * @return string The HTML for the checkbox.
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item->ID
		);
	}

	/**
	 * Name column for the domain.
	 *
	 * @param object $item The domain item.
	 * @return string The HTML for the name.
	 */
	protected function column_name( $item ) {
		$url = $item->domain;

		return '<a href="https://' . $url . '" target="_blank">' . $url . '</a>';
	}

	/**
	 * User column for the domain.
	 *
	 * @param object $item The domain item.
	 * @return string The HTML for the user.
	 */
	protected function column_user( $item ) {
		$user_id = intval( $item->user_id );

		return sprintf(
			'<span style="font-weight: bold">%1$s</span> <span style="font-size: 12px">(%2$s)</span>',
			wds_user_name( $user_id ),
			wds_user_meta( $user_id, '_branding_name' )
		);
	}

	/**
	 * Status column for the domain.
	 *
	 * @param object $item The domain item.
	 * @return string The formatted HTML status with color.
	 */
	protected function column_status( $item ) {
		if ( 'active' == $item->status ) {
			$color = '#46B450';
		} else {
			$color = '#DC3232';
		}

		if ( 'unconnected' == $item->status ) {
			$text = '<span class="dashicons dashicons-update-alt" data="' . $item->domain . '"></span>';
		} else {
			$text = '';
		}

		return sprintf(
			'<div class="wdr--domain-update wrap-spin"><span style="color:' . $color . ';">%1$s</span>' . $text . '</div>',
			ucfirst( $item->status )
		);
	}

	/**
	 * Get bulk actions available in the table.
	 *
	 * @return array Array of bulk actions.
	 */
	protected function get_bulk_actions() {
		$actions = array(
			'deleted'     => __( 'Deleted', 'wds-notrans' ),
			'blocked'     => __( 'Blocked', 'wds-notrans' ),
			'activated'   => __( 'Activated', 'wds-notrans' ),
			'unconnected' => __( 'Unconnected', 'wds-notrans' ),
		);

		return $actions;
	}

	/**
	 * Process the bulk actions for the domains.
	 */
	protected function process_bulk_action() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
			return;
		}

		if ( check_admin_referer( 'bulk-' . $this->_args['plural'] ) ) {
			$domains = isset( $_GET['domain'] ) ? (array) $_GET['domain'] : array();

			if ( 'deleted' === $this->current_action() ) {
				foreach ( $domains as $key => $id ) {
					wdr_delete( $id );
				}
			}

			if ( 'blocked' === $this->current_action() ) {
				foreach ( $domains as $key => $id ) {
					wdr_update_status( $id, 'blocked' );
				}
			}

			if ( 'activated' === $this->current_action() ) {
				foreach ( $domains as $key => $id ) {
					wdr_update_status( $id, 'active' );
				}
			}

			if ( 'unconnected' === $this->current_action() ) {
				foreach ( $domains as $key => $id ) {
					wdr_update_status( $id, 'unconnected' );
				}
			}
		}
	}

	/**
	 * Prepare the items (domains) for the table.
	 */
	public function prepare_items() {
		global $wpdb;

		$per_page = 20;
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$order_query = Replica::paginate( $per_page, $this->get_pagenum() )->order( 'ID', 'DESC' );

		$sql = '';

		$search = wds_sanitize_data_field( $_REQUEST, 's' );
		if ( $search ) {
			$sql .= sprintf(
				" domain LIKE %s OR user_id IN (SELECT ID FROM {$wpdb->users} WHERE display_name LIKE %s)",
				"'%$search%'",
				"'%$search%'"
			);
		}

		$status = wds_sanitize_data_field( $_REQUEST, 'status' );
		if ( $status ) {
			$sql .= sprintf( ' status = %s', "'$status'" );
		}

		if ( $sql ) {
			$sql .= " AND domain != 'nothing'";
		} else {
			$sql .= " domain != 'nothing'";
		}

		if ( $sql ) {
			$order_query->query( "WHERE$sql" );
		}

		$orders = $order_query->get();

		$this->items = $orders->found > 0 ? $orders : 0;
		$this->set_pagination_args(
			array(
				'total_items' => $orders->found(),
				'total_pages' => $orders->max_num_pages(),
				'per_page'    => $orders->results_per_page(),
			)
		);
	}
}
