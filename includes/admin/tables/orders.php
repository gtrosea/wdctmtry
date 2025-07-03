<?php

namespace WDS\Admin\Table;

use WDS\Models\Order;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Orders Class.
 *
 * A custom WordPress List Table to display and manage orders in the admin dashboard.
 */
class Orders extends \WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'wdsorder',
				'plural'   => 'orders',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get available views (filters) for orders.
	 *
	 * @return array Array of views with filter links.
	 */
	protected function get_views() {
		$statuses = wds_get_order_statuses();

		$current_status = wds_sanitize_data_field( $_REQUEST, 'status' );

		$link = menu_page_url( 'weddingsaas-order', false );
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
			'cb'       => '<input type="checkbox" />',
			'order'    => __( 'Code', 'wds-notrans' ),
			'customer' => __( 'Customer', 'wds-notrans' ),
			'product'  => __( 'Product', 'wds-notrans' ),
			'expired'  => __( 'Expired', 'wds-notrans' ),
			'status'   => __( 'Status', 'wds-notrans' ),
			'action'   => ' ',
		);

		return $columns;
	}

	/**
	 * Checkbox column for selecting orders.
	 *
	 * @param object $item The order item.
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
	 * Code column for the order.
	 *
	 * @param object $item The order item.
	 * @return string The HTML for the order code.
	 */
	protected function column_order( $item ) {
		return '<span class="fbold">' . $item->code . '</span>';
	}

	/**
	 * Customer column for the order.
	 *
	 * @param object $item The order item.
	 * @return string The HTML for the order customer.
	 */
	protected function column_customer( $item ) {
		return wds_user_name( $item->user_id );
	}

	/**
	 * Product column for the order.
	 *
	 * @param object $item The order item.
	 * @return string The HTML for the order product.
	 */
	protected function column_product( $item ) {
		return wds_get_product_title( $item->product_id );
	}

	/**
	 * Expired column for the order.
	 *
	 * @param object $item The order item.
	 * @return string The HTML for the order expired.
	 */
	protected function column_expired( $item ) {
		$expired_at = $item->expired_at ? wds_date_format( strtotime( $item->expired_at ) ) : __( 'Lifetime', 'wds-notrans' );
		if ( 'inactive' == $item->status ) {
			$expired_at = '-';
		}

		return $expired_at;
	}

	/**
	 * Status column for the order.
	 *
	 * @param object $item The order item.
	 * @return string The formatted HTML status with color.
	 */
	protected function column_status( $item ) {
		switch ( $item->status ) {
			case 'active':
				$color = '#46B450';
				break;
			case 'expired':
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
	 * Action column for the order details.
	 *
	 * @param object $item The product item.
	 * @return string The HTML for the view detail.
	 */
	protected function column_action( $item ) {
		$link = menu_page_url( 'weddingsaas-order', false ) . '&order_id=' . $item->ID;

		return '<a href="' . esc_url( $link ) . '" class="button button-small">View Details</a>';
	}

	/**
	 * Get bulk actions available in the table.
	 *
	 * @return array Array of bulk actions.
	 */
	protected function get_bulk_actions() {
		$actions = array( 'deleted' => __( 'Deleted', 'wds-notrans' ) );

		return $actions;
	}

	/**
	 * Process the bulk actions for the orders.
	 */
	protected function process_bulk_action() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! check_admin_referer( 'bulk-' . $this->_args['plural'] ) ) {
			return;
		}

		$orders = isset( $_GET['wdsorder'] ) ? array_map( 'sanitize_text_field', (array) $_GET['wdsorder'] ) : array();

		foreach ( $orders as $id ) {
			switch ( $this->current_action() ) {
				case 'deleted':
					wds_delete_order( $id );
					break;
			}
		}
	}

	/**
	 * Prepare the items (orders) for the table.
	 */
	public function prepare_items() {
		global $wpdb;

		$per_page = 20;
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$order_query = Order::paginate( $per_page, $this->get_pagenum() )->order( 'ID', 'DESC' );

		$sql = '';

		$search = wds_sanitize_data_field( $_REQUEST, 's' );
		if ( $search ) {
			$sql .= sprintf(
				" code LIKE %s OR user_id IN (SELECT ID FROM {$wpdb->users} WHERE display_name LIKE %s)",
				"'%$search%'",
				"'%$search%'"
			);
		}

		$status = wds_sanitize_data_field( $_REQUEST, 'status' );
		if ( $status ) {
			$sql .= sprintf( ' status = %s', "'$status'" );
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
