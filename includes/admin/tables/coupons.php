<?php

namespace WDS\Admin\Table;

use WDS\Models\Coupon;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Coupons Class.
 *
 * A custom WordPress List Table to display and manage coupons in the admin dashboard.
 */
class Coupons extends \WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'coupon',
				'plural'   => 'coupons',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get available views (filters) for coupons.
	 *
	 * @return array Array of views with filter links.
	 */
	protected function get_views() {
		$statuses = wds_get_coupon_statuses();

		$current_status = wds_sanitize_data_field( $_REQUEST, 'status' );

		$link = menu_page_url( 'weddingsaas-coupon', false );
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
			'title'  => __( 'Title', 'wds-notrans' ),
			'code'   => __( 'General Code', 'wds-notrans' ),
			'rebate' => __( 'Rebate', 'wds-notrans' ),
			'limit'  => __( 'Max Used', 'wds-notrans' ),
			'status' => __( 'Status', 'wds-notrans' ),
			'action' => ' ',
		);

		return $columns;
	}

	/**
	 * Checkbox column for selecting coupons.
	 *
	 * @param object $item The coupon item.
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
	 * Title column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the title.
	 */
	protected function column_title( $item ) {
		return $item->title;
	}

	/**
	 * Title column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the coupon code.
	 */
	protected function column_code( $item ) {
		return wds_get_coupon_code( $item->ID );
	}

	/**
	 * Title column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the rebate.
	 */
	protected function column_rebate( $item ) {
		return $item->rebate;
	}

	/**
	 * Title column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the limit.
	 */
	protected function column_limit( $item ) {
		return empty( $item->max_usage ) || 0 == $item->max_usage ? __( 'Unlimited', 'wds-notrans' ) : $item->max_usage;
	}

	/**
	 * Title column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the status.
	 */
	protected function column_status( $item ) {
		switch ( $item->status ) {
			case 'active':
				$color = '#46B450';
				break;

			case 'inactive':
				$color = '#DC3232';
				break;

			default:
				$color = '#82878C';
		}

		return sprintf(
			'<span style="color:%1$s">%2$s</span>',
			$color,
			ucfirst( $item->status ),
		);
	}

	/**
	 * Action column for the coupon (Edit link).
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the action links (Edit).
	 */
	protected function column_action( $item ) {
		$link = menu_page_url( 'weddingsaas-coupon', false );

		return '<a href="' . esc_url( $link . '&action=edit&coupon_id=' . $item->ID ) . '" class="button button-small">Edit</a>';
	}

	/**
	 * Get bulk actions available in the table.
	 *
	 * @return array Array of bulk actions.
	 */
	protected function get_bulk_actions() {
		$actions = array(
			'deleted'     => __( 'Deleted', 'wds-notrans' ),
			'activated'   => __( 'Activated', 'wds-notrans' ),
			'inactivated' => __( 'Inactivated', 'wds-notrans' ),
			'drafted'     => __( 'Drafted', 'wds-notrans' ),
		);

		return $actions;
	}

	/**
	 * Process the bulk actions for the coupons.
	 */
	protected function process_bulk_action() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! check_admin_referer( 'bulk-' . $this->_args['plural'] ) ) {
			return;
		}

		$coupons = isset( $_GET['coupon'] ) ? array_map( 'sanitize_text_field', (array) $_GET['coupon'] ) : array();

		foreach ( $coupons as $id ) {
			switch ( $this->current_action() ) {
				case 'deleted':
					wds_delete_coupon( $id );
					break;
				case 'activated':
					wds_update_coupon_status( $id, 'active' );
					break;
				case 'inactivated':
					wds_update_coupon_status( $id, 'inactive' );
					break;
				case 'drafted':
					wds_update_coupon_status( $id, 'draft' );
					break;
			}
		}
	}

	/**
	 * Prepare the items (coupons) for the table.
	 */
	public function prepare_items() {
		$per_page = 20;
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$coupon_query = Coupon::paginate( $per_page, $this->get_pagenum() )->order( 'ID', 'DESC' );

		$sql = '';

		$search = wds_sanitize_data_field( $_REQUEST, 's' );
		if ( $search ) {
			$sql .= sprintf( ' title LIKE %s', "'%$search%'" );
		}

		$status = wds_sanitize_data_field( $_REQUEST, 'status' );
		if ( $status ) {
			$sql .= sprintf( ' status = %s', "'$status'" );
		}

		if ( $sql ) {
			$coupon_query->query( "WHERE$sql" );
		}

		$coupons = $coupon_query->get();

		$this->items = $coupons->found > 0 ? $coupons : 0;
		$this->set_pagination_args(
			array(
				'total_items' => $coupons->found(),
				'total_pages' => $coupons->max_num_pages(),
				'per_page'    => $coupons->results_per_page(),
			)
		);
	}
}
