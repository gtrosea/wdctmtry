<?php

namespace WDS\Admin\Table;

use WDS\Models\Invoice;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Invoices Class.
 *
 * A custom WordPress List Table to display and manage invoices in the admin dashboard.
 */
class Invoices extends \WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'invoice',
				'plural'   => 'invoices',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get available views (filters) for invoices.
	 *
	 * @return array Array of views with filter links.
	 */
	protected function get_views() {
		$statuses = wds_get_invoice_statuses();

		$current_status = wds_sanitize_data_field( $_REQUEST, 'status' );

		$link = menu_page_url( 'weddingsaas-invoice', false );
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

		return array();
	}

	/**
	 * Get the columns to be displayed in the table.
	 *
	 * @return array Array of columns.
	 */
	public function get_columns() {
		$columns = array(
			'cb'       => '<input type="checkbox" />',
			'invoice'  => __( 'Invoice', 'wds-notrans' ),
			'customer' => __( 'Customer', 'wds-notrans' ),
			'product'  => __( 'Product', 'wds-notrans' ),
			'total'    => __( 'Total', 'wds-notrans' ),
			'type'     => __( 'Type', 'wds-notrans' ),
			'status'   => __( 'Status', 'wds-notrans' ),
			'action'   => ' ',
		);

		return $columns;
	}

	/**
	 * Checkbox column for selecting invoices.
	 *
	 * @param object $item The invoice item.
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
	 * Number column for the invoice.
	 *
	 * @param object $item The invoice item.
	 * @return string The HTML for the invoice number.
	 */
	protected function column_invoice( $item ) {
		return '<span class="fbold">' . $item->number . '</span>';
	}

	/**
	 * Customer column for the invoice.
	 *
	 * @param object $item The invoice item.
	 * @return string The HTML for the invoice customer.
	 */
	protected function column_customer( $item ) {
		return wds_user_name( $item->user_id );
	}

	/**
	 * Product column for the invoice.
	 *
	 * @param object $item The invoice item.
	 * @return string The HTML for the invoice product.
	 */
	protected function column_product( $item ) {
		$output  = '';
		$invoice = wds_get_invoice( $item->ID );
		foreach ( $invoice->orders() as $order ) {
			$output = wds_get_product_title( $order->product_id );
			$addon  = wds_get_order_meta( $order->ID, 'addons' );
			if ( ! empty( $addon ) ) {
				if ( is_array( $addon ) ) {
					$addon = implode( ', ', $addon );
				}
				$output .= ' + Addon (' . $addon . ')';
			}
		}

		return $output;
	}

	/**
	 * Total price column for the invoice.
	 *
	 * @param object $item The invoice item.
	 * @return string The HTML for the invoice total price.
	 */
	protected function column_total( $item ) {
		$price = wds_convert_money( $item->total );
		if ( 0 == $item->total ) {
			$price = __( 'Free', 'wds-notrans' );
		}

		return $price;
	}

	/**
	 * Type column for the invoice.
	 *
	 * @param object $item The invoice item.
	 * @return string The HTML for the invoice type.
	 */
	protected function column_type( $item ) {
		$type = __( 'New Order', 'wds-notrans' );
		if ( 'renew_order' == $item->type ) {
			$type = __( 'Renew Order', 'wds-notrans' );
		}

		return $type;
	}

	/**
	 * Status column for the invoice.
	 *
	 * @param object $item The invoice item.
	 * @return string The formatted HTML status with color.
	 */
	protected function column_status( $item ) {
		switch ( $item->status ) {
			case 'completed':
				$color = '#46B450';
				break;

			case 'cancelled':
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
		$html   = '';
		$orders = $item->orders();
		$status = $item->status;

		foreach ( $orders as $order ) {
			$link  = menu_page_url( 'weddingsaas-order', false ) . '&order_id=' . $order->ID;
			$html .= '&nbsp; <a href="' . $link . '" class="button button-small">View Details</a>';
		}

		if ( 'unpaid' == $status ) {
			$html .= '&nbsp; <button type="button" class="button button-primary button-small invoice-confirm-action wrap-spin inline" data-id="' . esc_attr( $item->ID ) . '">Confirm<i class="dashicons dashicons-update-alt small"></i></button>';
		}

		return $html;
	}

	/**
	 * Get bulk actions available in the table.
	 *
	 * @return array Array of bulk actions.
	 */
	protected function get_bulk_actions() {
		$actions = array(
			'completed' => __( 'Completed', 'wds-notrans' ),
			'cancelled' => __( 'Cancelled', 'wds-notrans' ),
			'deleted'   => __( 'Deleted', 'wds-notrans' ),
		);

		return $actions;
	}

	/**
	 * Process the bulk actions for the invoices.
	 */
	protected function process_bulk_action() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! check_admin_referer( 'bulk-' . $this->_args['plural'] ) ) {
			return;
		}

		$invoices = isset( $_GET['invoice'] ) ? array_map( 'sanitize_text_field', (array) $_GET['invoice'] ) : array();

		foreach ( $invoices as $id ) {
			switch ( $this->current_action() ) {
				case 'completed':
					wds_update_invoice_status( $id, 'completed' );
					break;
				case 'cancelled':
					wds_update_invoice_status( $id, 'cancelled' );
					break;
				case 'deleted':
					wds_delete_invoice( $id );
					break;
			}
		}
	}

	/**
	 * Prepare the items (invoices) for the table.
	 */
	public function prepare_items() {
		global $wpdb;

		$per_page = 20;
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$invoice_query = Invoice::paginate( $per_page, $this->get_pagenum() )->order( 'ID', 'DESC' );

		$sql = '';

		$search = wds_sanitize_data_field( $_REQUEST, 's' );
		if ( $search ) {
			$sql .= sprintf(
				" number LIKE %s OR user_id IN (SELECT ID FROM {$wpdb->users} WHERE display_name LIKE %s)",
				"'%$search%'",
				"'%$search%'"
			);
		}

		$status = wds_sanitize_data_field( $_REQUEST, 'status' );
		if ( $status ) {
			$sql .= sprintf( ' status = %s', "'$status'" );
		}

		if ( $sql ) {
			$invoice_query->query( "WHERE$sql" );
		}

		$invoices = $invoice_query->get();

		$this->items = $invoices->found > 0 ? $invoices : 0;
		$this->set_pagination_args(
			array(
				'total_items' => $invoices->found(),
				'total_pages' => $invoices->max_num_pages(),
				'per_page'    => $invoices->results_per_page(),
			)
		);
	}
}
