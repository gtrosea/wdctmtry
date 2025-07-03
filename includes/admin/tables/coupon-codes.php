<?php

namespace WDS\Admin\Table;

use WDS\Models\Coupon_Code;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Coupon_Codes Class.
 *
 * A custom WordPress List Table to display and manage coupon codes in the admin dashboard.
 */
class Coupon_Codes extends \WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'code',
				'plural'   => 'codes',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get the columns to be displayed in the table.
	 *
	 * @return array Array of columns.
	 */
	public function get_columns() {
		$columns = array(
			'code'   => __( 'Code', 'wds-notrans' ),
			'user'   => __( 'User', 'wds-notrans' ),
			'usage'  => __( 'Usage', 'wds-notrans' ),
			'action' => ' ',
		);

		return $columns;
	}

	/**
	 * Code column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the code.
	 */
	protected function column_code( $item ) {
		return $item->code;
	}

	/**
	 * User column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the code.
	 */
	protected function column_user( $item ) {
		$output = __( 'General', 'wds-notrans' );

		if ( null != $item->user_id ) {
			$output = wds_user_name( $item->user_id );
		}

		return $output;
	}

	/**
	 * Usage column for the coupon.
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the code.
	 */
	protected function column_usage( $item ) {
		return wds_get_coupon_usage( $item->code_id );
	}

	/**
	 * Action column for the coupon (Delete link).
	 *
	 * @param object $item The coupon item.
	 * @return string The HTML for the action links.
	 */
	protected function column_action( $item ) {
		$item_id = intval( $item->code_id );
		$user_id = intval( $item->user_id );
		$link    = menu_page_url( 'weddingsaas-coupon', false );

		if ( ! empty( $user_id ) || 0 != $user_id ) {
			return '<a href="' . esc_url( $link . '&coupon_id=' . $item->coupon_id . '&delete_code=' . $item_id ) . '" class="button button-small">Delete</a>';
		}
	}

	/**
	 * Prepare the items (coupons) for the table.
	 */
	public function prepare_items() {
		$per_page  = 20;
		$columns   = $this->get_columns();
		$hidden    = array();
		$sortable  = $this->get_sortable_columns();
		$coupon_id = isset( $_GET['coupon_id'] ) ? intval( $_GET['coupon_id'] ) : 0;

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$coupon_query = Coupon_Code::paginate( $per_page, $this->get_pagenum() );

		$sql = sprintf( ' coupon_id = %d', $coupon_id );

		$search = wds_sanitize_data_field( $_REQUEST, 's' );
		if ( $search ) {
			$sql .= sprintf( ' code LIKE %s', "'%$search%'" );
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
