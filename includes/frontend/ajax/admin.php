<?php

namespace WDS\Frontend\Ajax;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Admin Class.
 */
class Admin {

	/**
	 * Admin affiliate payouts action.
	 */
	public static function payouts() {
		global $wpdb;

		$post = $_POST;

		$user_id = wds_sanitize_data_field( $post, 'user_id' );
		$amount  = wds_sanitize_data_field( $post, 'amount' );
		$method  = wds_sanitize_data_field( $post, 'method' );
		$account = wds_sanitize_data_field( $post, 'account' );

		$user = get_userdata( $user_id );
		if ( empty( $user ) ) {
			wp_send_json_error( __( 'User ID notfound.', 'wds-notrans' ) );
		}

		$args = array(
			'user_id' => $user_id,
			'amount'  => floatval( $amount ),
			'method'  => $method . ': ' . $account,
		);

		$insert = wds_insert_withdraw( $args );
		if ( is_wp_error( $insert ) ) {
			wp_send_json_error( __( 'Failed to pay the affiliate commission!', 'wds-notrans' ) );
		}

		$table = $wpdb->prefix . WDS_MODEL . '_commission';
		$wpdb->query( $wpdb->prepare( 'UPDATE %i SET `status` = %s WHERE `user_id` = %d AND `status` = %s', array( $table, 'paid', $user_id, 'unpaid' ) ) );

		do_action( 'wds_success_payout', $args );

		wp_send_json_success(
			array(
				'message'  => __( 'Successfully payout affiliate commissions!', 'wds-notrans' ),
				'redirect' => wds_url( 'payouts' ),
			)
		);
	}

	/**
	 * Admin get statistics action.
	 */
	public static function get_stats() {
		global $wpdb;

		$post   = $_POST;
		$prefix = $wpdb->prefix . WDS_MODEL;

		$date_start = wds_get_invoices_start_date();
		$date_end   = gmdate( 'Y-m-d' );

		$start_date = wds_sanitize_data_field( $post, 'start_date', $date_start );
		$end_date   = wds_sanitize_data_field( $post, 'end_date', $date_end );
		$products   = 'all';

		$invoice_status = array_keys( wds_get_invoice_statuses() );
		$invoice_status = implode( '\',\'', $invoice_status );

		$invoice       = $prefix . '_invoice';
		$invoice_order = $prefix . '_invoice_order';
		$order         = $prefix . '_order';

		if ( 'all' == $products || empty( $products ) ) {
			$products = "SELECT ID FROM {$prefix}_product";
		} else {
			$products = is_array( $products ) ? implode( '\',\'', array_map( 'sanitize_text_field', $products ) ) : wds_sanitize_text_field( $products );
			$products = "'$products'";
		}

		$query =
			"SELECT
                COUNT(
                    DISTINCT CASE 
                    WHEN {$invoice}.status IN ('$invoice_status')
                    AND {$order}.product_id IN ($products)
                    AND DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                    THEN {$invoice}.ID ELSE NULL END
                ) AS leads,
                COUNT(
                    DISTINCT CASE 
                    WHEN {$invoice}.status IN ('completed')
                    AND {$order}.product_id IN ($products)
                    AND DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                    THEN {$invoice}.ID ELSE NULL END
                ) AS sales
            FROM $invoice 
            LEFT JOIN $invoice_order
                ON {$invoice}.ID = {$invoice_order}.invoice_id
            LEFT JOIN $order
                ON {$order}.ID = {$invoice_order}.order_id
            WHERE DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'";

		$result = $wpdb->get_row( $query ); // phpcs:ignore

		$leads = intval( $result->leads );
		$sales = intval( $result->sales );

		if ( 0 === $leads ) {
			$conversion = 0;
		} else {
			$conversion = ( $sales / $leads ) * 100;
		}

		// $result->conversion = $sales == 0 ? '0%' : round( $conversion, 2 ) . '%';

		// $conversion         = intval( $result->sales ) / intval( $result->leads ) * 100;
		$result->conversion = intval( $result->sales ) == 0 ? '0%' : round( $conversion, 2 ) . '%';
		$result->query      = $query;

		wp_send_json( $result );
	}

	/**
	 * Admin get statistics chart action.
	 */
	public static function get_stats_chart() {
		global $wpdb;

		$post   = $_POST;
		$prefix = $wpdb->prefix . WDS_MODEL;

		$date_start = wds_get_invoices_start_date();
		$date_end   = gmdate( 'Y-m-d' );

		$start_date = wds_sanitize_data_field( $post, 'start_date', $date_start );
		$end_date   = wds_sanitize_data_field( $post, 'end_date', $date_end );
		$products   = 'all';

		$invoice_status = array_keys( wds_get_invoice_statuses() );
		$invoice_status = implode( '\',\'', $invoice_status );

		if ( 'all' == $products || empty( $products ) ) {
			$products = "SELECT ID FROM {$prefix}_product";
		} else {
			$products = is_array( $products ) ? implode( '\',\'', array_map( 'sanitize_text_field', $products ) ) : wds_sanitize_text_field( $products );
			$products = "'$products'";
		}

		$args = array(
			'start_date'     => $start_date,
			'end_date'       => $end_date,
			'products'       => $products,
			'invoice_status' => $invoice_status,
			'invoice_order'  => $prefix . '_invoice_order',
			'invoice'        => $prefix . '_invoice',
			'order'          => $prefix . '_order',
		);

		$date1 = date_create( $start_date );
		$date2 = date_create( $end_date );
		$diff  = date_diff( $date1, $date2 );
		$month = intval( $diff->format( '%m' ) );

		if ( $month > 3 ) {
			$results = \WDS_Statistics::monthly_query( $args );
		} else {
			$day = intval( $diff->format( '%a' ) );
			if ( $day > 3 ) {
				$results = \WDS_Statistics::daily_query( $args );
			} else {
				$results = \WDS_Statistics::hourly_query( $args );
			}
		}

		$number        = wp_parse_args( $results->leads, $results->sales );
		$higher_number = max( $number );

		$res = array(
			'series'    => array(
				array(
					'name' => __( 'Leads', 'wds-notrans' ),
					'type' => 'area',
					'data' => $results->leads,
				),
				array(
					'name' => __( 'Sales', 'wds-notrans' ),
					'type' => 'area',
					'data' => $results->sales,
				),
				array(
					'name' => __( 'Conversion', 'wds-notrans' ),
					'type' => 'line',
					'data' => $results->conversions,
				),
			),
			'xaxis'     => array(
				'categories' => $results->categories,
				'title'      => array(
					'text' => '',
				),
			),
			'maxnumber' => $higher_number,
		);

		wp_send_json( $res );
	}

	/**
	 * Admin get income action.
	 */
	public static function get_income() {
		global $wpdb;

		$post   = $_POST;
		$prefix = $wpdb->prefix . WDS_MODEL;

		$date_start = wds_get_invoices_start_date();
		$date_end   = gmdate( 'Y-m-d' );

		$start_date = wds_sanitize_data_field( $post, 'start_date', $date_start );
		$end_date   = wds_sanitize_data_field( $post, 'end_date', $date_end );
		$products   = 'all';

		$invoice       = $prefix . '_invoice';
		$invoice_order = $prefix . '_invoice_order';
		$order         = $prefix . '_order';
		$commission    = $prefix . '_commission';

		if ( 'all' == $products || empty( $products ) ) {
			$products = "SELECT ID FROM {$prefix}_product";
		} else {
			$products = is_array( $products ) ? implode( '\',\'', array_map( 'sanitize_text_field', $products ) ) : wds_sanitize_text_field( $products );
			$products = "'$products'";
		}

		$query =
			"SELECT
                SUM(
                    CASE 
                    WHEN {$invoice}.status IN ('completed')
                    AND {$order}.product_id IN ($products)
                    AND DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                    THEN {$invoice}.total ELSE 0 END
                ) AS revenue
            FROM $invoice
            LEFT JOIN $invoice_order
                ON {$invoice}.ID = {$invoice_order}.invoice_id
            LEFT JOIN $order
                ON {$order}.ID = {$invoice_order}.order_id
            WHERE DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'";

		$result_income = $wpdb->get_row( $query ); // phpcs:ignore

		$query =
			"SELECT
                SUM(
                    CASE 
                    WHEN {$commission}.status IN ('paid', 'unpaid')
                    AND {$commission}.product_id IN ($products)
                    AND DATE({$commission}.created_at) BETWEEN '$start_date' AND '$end_date'
                    THEN {$commission}.amount ELSE 0 END
                ) AS total
            FROM $commission
            WHERE DATE({$commission}.created_at) BETWEEN '$start_date' AND '$end_date'";

		$result_commission = $wpdb->get_row( $query ); // phpcs:ignore

		$revenue   = $result_income->revenue;
		$commision = $result_commission->total;
		$profit    = intval( $result_income->revenue ) - intval( $result_commission->total );

		$result = array(
			'revenue'    => wds_convert_money( $revenue ),
			'commission' => wds_convert_money( $commision ),
			'profit'     => wds_convert_money( $profit ),
		);

		wp_send_json( $result );
	}

	/**
	 * Admin get top product action.
	 */
	public static function get_top_product() {
		global $wpdb;

		$post   = $_POST;
		$prefix = $wpdb->prefix . WDS_MODEL;

		$date_start = wds_get_invoices_start_date();
		$date_end   = gmdate( 'Y-m-d' );

		$start_date = wds_sanitize_data_field( $post, 'start_date', $date_start );
		$end_date   = wds_sanitize_data_field( $post, 'end_date', $date_end );
		$products   = 'all';

		$invoice       = $prefix . '_invoice';
		$invoice_order = $prefix . '_invoice_order';
		$order         = $prefix . '_order';

		if ( 'all' == $products || empty( $products ) ) {
			$products = "SELECT ID FROM {$prefix}_product";
		} else {
			$products = is_array( $products ) ? implode( '\',\'', array_map( 'sanitize_text_field', $products ) ) : wds_sanitize_text_field( $products );
			$products = "'$products'";
		}

		$query =
			"SELECT 
                {$order}.product_id AS product_id,
                COUNT(DISTINCT {$invoice}.ID) AS sales,
                SUM({$invoice}.total) AS revenue
            FROM $order
            LEFT JOIN $invoice_order
                ON {$order}.ID = {$invoice_order}.order_id
            LEFT JOIN $invoice
                ON {$invoice}.ID = {$invoice_order}.invoice_id
            WHERE DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                AND {$invoice}.status = 'completed'
                AND {$order}.product_id IN ($products)
            GROUP BY {$order}.product_id
            ORDER BY sales DESC";

		$results = $wpdb->get_results( $query ); // phpcs:ignore

		$response = array();
		foreach ( $results as $result ) {
			$response[] = array(
				'product_id'   => $result->product_id,
				'product_name' => wds_get_product_title( $result->product_id ),
				'sales'        => $result->sales,
				'revenue'      => wds_convert_money( $result->revenue ),
				'edit'         => admin_url( 'admin.php?page=weddingsaas-product&product_id=' . $result->product_id ),
			);
		}

		wp_send_json( $response );
	}

	/**
	 * Get user statistics action.
	 * @since 2.3.1
	 */
	public static function get_user_statistic() {
		$users = count_users();
		$stats = array(
			'active'   => array(
				'key'   => '_wds_user_status',
				'value' => 'active',
			),
			'trial'    => array(
				'key'   => '_wds_user_group',
				'value' => 'trial',
			),
			'member'   => array(
				'key'   => '_wds_user_group',
				'value' => 'member',
			),
			'reseller' => array(
				'key'   => '_wds_user_group',
				'value' => 'reseller',
			),
		);

		$result = array( 'user' => $users['total_users'] );

		foreach ( $stats as $type => $meta ) {
			$result[ $type ] = count(
				get_users(
					array(
						'meta_key'   => $meta['key'],
						'meta_value' => $meta['value'],
						'fields'     => 'ID', // Only get IDs for better performance
						'number'     => -1,
					)
				)
			);
		}

		wp_send_json( $result );
	}

	/**
	 * Get users data for admin table display.
	 *
	 * @since 2.3.1
	 * @return void
	 */
	public static function get_users() {
		$post = $_POST;
		// wds_log( $post, true );

		$page   = isset( $post['start'] ) ? intval( $post['start'] ) : 0;
		$length = isset( $post['length'] ) ? intval( $post['length'] ) : 10;
		$search = isset( $post['search']['value'] ) ? sanitize_text_field( $post['search']['value'] ) : '';

		// Filter
		$status           = isset( $post['status'] ) ? sanitize_text_field( $post['status'] ) : '';
		$group            = isset( $post['group'] ) ? sanitize_text_field( $post['group'] ) : '';
		$expired          = isset( $post['expired'] ) ? sanitize_text_field( $post['expired'] ) : '';
		$invitation_quota = isset( $post['invitation_quota'] ) ? sanitize_text_field( $post['invitation_quota'] ) : '';
		$client_quota     = isset( $post['client_quota'] ) ? sanitize_text_field( $post['client_quota'] ) : '';
		$created          = isset( $post['created'] ) ? sanitize_text_field( $post['created'] ) : '';
		$storage          = isset( $post['storage'] ) ? sanitize_text_field( $post['storage'] ) : '';

		$args = array(
			'number'         => $length,
			'offset'         => $page,
			'search'         => "*{$search}*",
			'search_columns' => array( 'user_login', 'user_email', 'display_name' ),
		);

		// Filter by user status
		if ( $status ) {
			$args['meta_query'][] = array(
				'key'     => '_wds_user_status',
				'value'   => $status,
				'compare' => '=',
			);
		}

		// Filter by user group
		if ( $group ) {
			$args['meta_query'][] = array(
				'key'     => '_wds_user_group',
				'value'   => $group,
				'compare' => '=',
			);
		}

		// Filter by user created
		if ( $expired ) {
			$args['meta_query'][] = array(
				'key'     => '_wds_user_active_period',
				'type'    => 'NUMERIC',
				'compare' => 'EXISTS',
			);

			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_wds_user_active_period';
			$args['order']    = 'min' == $expired ? 'ASC' : 'DESC';
		}

		// Filter by user invitation quota
		if ( $invitation_quota ) {
			$args['meta_query'][] = array(
				'key'     => '_wds_invitation_quota',
				'type'    => 'NUMERIC',
				'compare' => 'EXISTS',
			);

			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_wds_invitation_quota';
			$args['order']    = 'min' == $invitation_quota ? 'ASC' : 'DESC';
		}

		// Filter by user client quota
		if ( $client_quota ) {
			$args['meta_query'][] = array(
				'key'     => '_wds_client_quota',
				'type'    => 'NUMERIC',
				'compare' => 'EXISTS',
			);

			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_wds_client_quota';
			$args['order']    = 'min' == $client_quota ? 'ASC' : 'DESC';
		}

		// Filter by user created & storage
		if ( $created || $storage ) {
			$args['number'] = -1;
			$args['offset'] = 0;
		}

		$query = new \WP_User_Query( $args );
		$users = $query->get_results();
		$total = $query->get_total();

		// Filter by post count
		if ( $created ) {
			$users_with_created = array();
			foreach ( $users as $user ) {
				$user_id                        = $user->ID;
				$created_size                   = wds_user_posts_count( $user_id );
				$users_with_created[ $user_id ] = $created_size;
			}

			// Sort users by post count
			if ( 'min' == $created ) {
				asort( $users_with_created );
			} else {
				arsort( $users_with_created );
			}

			// Reorder users array
			$sorted_users = array();
			foreach ( $users_with_created as $user_id => $size ) {
				foreach ( $users as $user ) {
					if ( $user->ID === $user_id ) {
						$sorted_users[] = $user;
						break;
					}
				}
			}
			$users = $sorted_users;
		}

		// Filter by storage
		if ( $storage ) {
			$users_with_storage = array();
			foreach ( $users as $user ) {
				$user_id                        = $user->ID;
				$storage_size                   = wds_user_storage( $user_id, false ); // Get numeric value
				$users_with_storage[ $user_id ] = $storage_size;
			}

			// Sort users by storage
			if ( 'min' == $storage ) {
				asort( $users_with_storage );
			} else {
				arsort( $users_with_storage );
			}

			// Reorder users array
			$sorted_users = array();
			foreach ( $users_with_storage as $user_id => $size ) {
				foreach ( $users as $user ) {
					if ( $user->ID === $user_id ) {
						$sorted_users[] = $user;
						break;
					}
				}
			}
			$users = $sorted_users;
		}

		$data = array();

		foreach ( $users as $user ) {
			$user_id = $user->ID;

			$data[] = array(
				'ID'                 => $user_id,
				'name'               => $user->display_name,
				'email'              => $user->user_email,
				'phone'              => wds_user_phone( $user_id ),
				'status'             => wds_user_status( $user_id ) == 'active' ? wds_lang( 'active' ) : wds_lang( 'inactive' ),
				'user_group'         => ucwords( wds_user_group( $user_id ) ),
				'user_expired'       => wds_user_active_period( $user_id ),
				'invitation_quota'   => wds_user_invitation_quota( $user_id ),
				'client_quota'       => wds_user_client_quota( $user_id ),
				'invitation_created' => wds_user_posts_count( $user_id ),
				'storage'            => wds_user_storage( $user_id ) . ' MB',
				'avatar'             => wds_user_avatar( $user_id ),
				'spinner'            => esc_url( WDS_URL . 'assets/img/spinner.gif' ),
				'edit_link'          => get_edit_user_link( $user_id ),
			);
		}

		wp_send_json(
			array(
				'draw'            => intval( $_POST['draw'] ?? 1 ),
				'recordsTotal'    => $total,
				'recordsFiltered' => $total,
				'data'            => $data,
			)
		);
	}
}
