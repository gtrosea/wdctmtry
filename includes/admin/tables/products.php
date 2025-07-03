<?php

namespace WDS\Admin\Table;

use WDS\Models\Product;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Products Class.
 *
 * A custom WordPress List Table to display and manage products in the admin dashboard.
 */
class Products extends \WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'product',
				'plural'   => 'products',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get available views (filters) for products.
	 *
	 * @return array Array of views with filter links.
	 */
	protected function get_views() {
		$statuses = wds_get_product_statuses();

		$current_status = wds_sanitize_data_field( $_REQUEST, 'status' );

		$link = menu_page_url( 'weddingsaas-product', false );
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
			'cb'      => '<input type="checkbox" />',
			'title'   => __( 'Title', 'wds-notrans' ),
			'type'    => __( 'Type', 'wds-notrans' ),
			'price'   => __( 'Price', 'wds-notrans' ),
			'payment' => __( 'Payment', 'wds-notrans' ),
			'status'  => __( 'Status', 'wds-notrans' ),
			'action'  => ' ',
		);

		return $columns;
	}

	/**
	 * Checkbox column for selecting products.
	 *
	 * @param object $item The product item.
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
	 * Title column for the product.
	 *
	 * @param object $item The product item.
	 * @return string The HTML for the title and checkout link.
	 */
	protected function column_title( $item ) {
		$checkout_link = wds_url( 'checkout', $item->slug );

		return sprintf(
			'<span style="font-weight:bold;">%1$s</span></br> (<a href="%2$s" target="_blank">Checkout Link</a>)',
			$item->title,
			$checkout_link
		);
	}

	/**
	 * Type column for the product.
	 *
	 * @param object $item The product item.
	 * @return string The HTML for the product type and membership type.
	 */
	protected function column_type( $item ) {
		$product_type = wds_get_product_meta( $item->ID, 'product_type' );
		if ( 'digital' == $product_type ) {
			if ( ! wds_is_digital() ) {
				return '-';
			}

			return __( 'Digital Product', 'wds-notrans' );
		}

		$product    = __( 'Membership', 'wds-notrans' );
		$type       = 'addon' == wds_get_product_meta( $item->ID, 'membership_type' ) ? __( 'Top Up', 'wds-notrans' ) : ucfirst( wds_get_product_meta( $item->ID, 'membership_type' ) );
		$membership = ' (' . $type . ')';

		return $product . $membership;
	}

	/**
	 * Price column for the product.
	 *
	 * @param object $item The product item.
	 * @return string The HTML for the price and renewal price.
	 */
	protected function column_price( $item ) {
		$price       = wds_get_product_price( $item->ID );
		$renew_price = wds_get_product_renew_price( $item->ID );
		$payment     = wds_get_product_meta( $item->ID, 'payment_type' );

		if ( empty( $price ) || 0 == $price ) {
			$ret = __( 'Free', 'wds-notrans' );
		} elseif ( 'onetime' == $payment ) {
			$ret = wds_convert_money( $price );
		} elseif ( 'subscription' == $payment ) {
			$ret  = wds_convert_money( $price );
			$ret .= '<br>';
			$ret .= '(' . __( 'Renew', 'wds-notrans' ) . ' ' . wds_convert_money( $renew_price ) . ')';
		}

		return $ret;
	}

	/**
	 * Payment type column for the product.
	 *
	 * @param object $item The product item.
	 * @return string The payment type (One Time or Subscription).
	 */
	protected function column_payment( $item ) {
		$payment = wds_get_product_meta( $item->ID, 'payment_type' );
		if ( 'onetime' == $payment ) {
			$payment = __( 'One Time', 'wds-notrans' );
		} elseif ( 'subscription' == $payment ) {
			$payment = __( 'Subscription', 'wds-notrans' );
		}

		return $payment;
	}

	/**
	 * Status column for the product.
	 *
	 * @param object $item The product item.
	 * @return string The formatted HTML status with color.
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
	 * Action column for the product (Edit link).
	 *
	 * @param object $item The product item.
	 * @return string The HTML for the action links (Edit).
	 */
	protected function column_action( $item ) {
		$link = menu_page_url( 'weddingsaas-product', false );

		return '<a href="' . esc_url( $link . '&product_id=' . $item->ID ) . '" class="button button-small">Edit</a>';
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
	 * Process the bulk actions for the products.
	 */
	protected function process_bulk_action() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! check_admin_referer( 'bulk-' . $this->_args['plural'] ) ) {
			return;
		}

		$products = isset( $_GET['product'] ) ? array_map( 'sanitize_text_field', (array) $_GET['product'] ) : array();

		foreach ( $products as $id ) {
			switch ( $this->current_action() ) {
				case 'deleted':
					wds_delete_product( $id );
					break;
				case 'activated':
					wds_update_product_status( $id, 'active' );
					break;
				case 'inactivated':
					wds_update_product_status( $id, 'inactive' );
					break;
				case 'drafted':
					wds_update_product_status( $id, 'draft' );
					break;
			}
		}
	}

	/**
	 * Get sortable columns for the table.
	 *
	 * @return array Array of sortable columns.
	 */
	// protected function get_sortable_columns() {
	//  $sortable_columns = array(
	//      'title'  => array( 'title', false ),
	//      'price'  => array( 'price', false ),
	//      'status' => array( 'status', true ),
	//  );

	//  return $sortable_columns;
	// }

	/**
	 * Prepare the items (products) for the table.
	 */
	public function prepare_items() {
		$per_page = 20;
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$product_query = Product::paginate( $per_page, $this->get_pagenum() )->order( 'ID', 'DESC' );

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
			$product_query->query( "WHERE$sql" );
		}

		$products = $product_query->get();

		$this->items = $products->found > 0 ? $products : 0;
		$this->set_pagination_args(
			array(
				'total_items' => $products->found(),
				'total_pages' => $products->max_num_pages(),
				'per_page'    => $products->results_per_page(),
			)
		);
	}
}
