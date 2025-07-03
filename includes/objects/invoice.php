<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a invoice.
 *
 * @param int|object $invoice The invoice ID or object.
 * @return mixed Invoices object if found, false otherwise.
 */
function wds_get_invoice( $invoice = false ) {
	return WDS()->database->get( 'invoice', $invoice );
}

/**
 * Insert a new invoice.
 *
 * @param array $data Invoice data to insert.
 * @return mixed The ID of the new invoice, or false on failure.
 */
function wds_insert_invoice( $data = array() ) {
	$invoice_id = WDS()->database->add( 'invoice', $data );

	if ( ! is_wp_error( $invoice_id ) && isset( $data['order_id'] ) ) {
		$inv_order = array(
			'invoice_id' => $invoice_id,
			'order_id'   => intval( $data['order_id'] ),
		);
		wds_insert_invoice_order( $inv_order );
	}

	return $invoice_id;
}

/**
 * Update existing invoice.
 *
 * @param array $data Invoice data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_invoice( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_invoice( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_invoice( $data );
	}

	$updated = WDS()->database->update( 'invoice', $data );

	if ( ! is_wp_error( $updated ) && isset( $data['order_id'] ) ) {
		$inv_order = array(
			'invoice_id' => $obj->ID,
			'order_id'   => $data['order_id'],
		);
		wds_update_invoice_order( $inv_order );
	}

	return $updated;
}

/**
 * Delete a invoice.
 *
 * @param int|object $invoice The invoice ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_invoice( $invoice = false ) {
	$obj = wds_get_invoice( $invoice );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'invoice', $obj->ID );
}

/**
 * Update the status of a invoice.
 *
 * @param int|object $invoice The invoice ID or object.
 * @param string     $new_status The new status of the invoice.
 * @return bool True on success, false on failure.
 */
function wds_update_invoice_status( $invoice = false, $new_status = '' ) {
	global $wpdb;

	$obj = wds_get_invoice( $invoice );
	if ( ! $obj ) {
		return false;
	}

	$invoice_id = $obj->ID;
	$old_status = $obj->status;
	$new_status = wds_sanitize_text_field( $new_status );
	$is_exists  = wds_get_invoice_statuses( $new_status );
	if ( ! $is_exists ) {
		return false;
	}

	if ( $old_status == $new_status ) {
		return false;
	}

	$data = array(
		'ID'         => $invoice_id,
		'status'     => $new_status,
		'updated_at' => gmdate( 'Y-m-d H:i:s' ),
	);

	do_action( 'wds_update_invoice_status_before', $invoice_id, $new_status, $old_status );

	$updated = WDS()->database->update( 'invoice', $data );

	do_action( 'wds_update_invoice_status', $invoice_id );

	if ( ! is_wp_error( $updated ) ) {
		$orders    = $obj->orders();
		$order_ids = array();
		foreach ( $orders as $order ) {
			$order_ids[] = intval( $order->ID );
			wds_invoice_update_order_status( $invoice_id, $order->ID );
		}

		// update commission status from pending to unpaid if completed invoice status
		if ( ! empty( $order_ids ) ) {
			$order_ids = implode( ',', $order_ids );
			$table     = $wpdb->prefix . WDS_MODEL . '_commission';
			$query     = "UPDATE $table SET status = %s WHERE order_id IN ($order_ids) AND status != %s";

			if ( 'completed' == $new_status ) {
				$wpdb->query( $wpdb->prepare( $query, 'unpaid', 'paid' ) ); // phpcs:ignore
			} elseif ( 'unpaid' == $new_status || 'checking_payment' == $new_status ) {
				$wpdb->query( $wpdb->prepare( $query, 'pending', 'paid' ) ); // phpcs:ignore
			} elseif ( 'refunded' == $new_status || 'cancelled' == $new_status ) {
				$wpdb->query( $wpdb->prepare( $query, 'cancelled', 'paid' ) ); // phpcs:ignore
			}
		}
	}

	return $updated;
}

/**
 * Update the status of a order.
 *
 * @param int|object $invoice The invoice ID or object.
 * @param int|object $order The order ID or object.
 * @return bool True on success, false on failure.
 */
function wds_invoice_update_order_status( $invoice, $order ) {
	$invoice = wds_get_invoice( $invoice );
	$order   = wds_get_order( $order );
	if ( ! $invoice || ! $order ) {
		return false;
	}

	$order_id = $order->ID;
	$status   = 'inactive';
	$log      = __( 'Payment', 'wds-notrans' ) . ' ' . wds_get_invoice_statuses( $invoice->status );

	if ( 'completed' == $invoice->status ) {
		$status = 'active';
		$log    = __( 'Order Activated', 'wds-notrans' );
		if ( 'renew_order' == $invoice->type ) {
			$log = __( 'Order Renewed', 'wds-notrans' );
		}
	}

	$updated = wds_update_order_status( $order_id, $status );

	if ( ! is_wp_error( $updated ) ) {
		wds_insert_order_log( $order_id, $log );
		do_action( 'wds_update_order_status', $order_id, $status );
	}

	return $updated;
}

/**
 * Retrieve a invoice order.
 *
 * @param int|object $invoice The invoice ID or object.
 * @return mixed Invoice order object if found, false otherwise.
 */
function wds_get_invoice_order( $invoice = false ) {
	$obj = wds_get_invoice( $invoice );
	if ( ! $obj ) {
		return false;
	}

	$invoice = WDS\Models\Invoice_Order::query( 'WHERE invoice_id = %d', $obj->ID )->first();

	return $invoice->invoice_id > 0 ? $invoice : false;
}

/**
 * Insert a new invoice order.
 *
 * @param array $data Invoice order data to insert.
 * @return mixed The ID of the new invoice order, or false on failure.
 */
function wds_insert_invoice_order( $data = array() ) {
	if ( empty( $data['invoice_id'] ) || empty( $data['order_id'] ) ) {
		return false;
	}

	$invoice = wds_get_invoice( $data['invoice_id'] );
	$order   = wds_get_order( $data['order_id'] );
	if ( ! $invoice || ! $order ) {
		return false;
	}

	return WDS\Models\Invoice_Order::data( $data )->create();
}

/**
 * Update an existing invoice order.
 *
 * @param array $data Invoice order data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_invoice_order( $data = array() ) {
	if ( empty( $data['invoice_id'] ) || empty( $data['order_id'] ) ) {
		return false;
	}

	$invoice = wds_get_invoice( $data['invoice_id'] );
	$order   = wds_get_order( $data['order_id'] );
	if ( ! $invoice || ! $order ) {
		return false;
	}

	$obj = wds_get_invoice_order( $data['invoice_id'] );
	if ( ! $obj ) {
		return wds_insert_invoice_order( $data );
	}

	return WDS\Models\Invoice_Order::data( $data )->update( array( 'invoice_order_id' => $obj->invoice_order_id ) );
}

/**
 * Generate a formatted invoice number based on the given invoice ID and predefined format.
 *
 * @param int $invoice_id The ID of the invoice.
 * @return string The invoice format.
 */
function wds_generate_invoice_format( $invoice_id ) {
	if ( empty( $invoice_id ) ) {
		return;
	}

	$format = wds_option( 'invoice_format' );

	list($y, $m, $d) = explode( '-', gmdate( 'Y-m-d', strtotime( 'now' ) ) );

	$number = str_pad( $invoice_id, 5, '0', STR_PAD_LEFT );

	$format = str_replace( '{year}', $y, $format );
	$format = str_replace( '{month}', $m, $format );
	$format = str_replace( '{date}', $d, $format );
	$format = str_replace( '{number}', $number, $format );

	return $format;
}

/**
 * Set the due date for an invoice.
 *
 * @return datetime The invoice due date.
 */
function wds_set_invoice_due_date() {
	$duration = wds_option( 'invoice_due_date' );

	if ( intval( $duration ) <= 0 ) {
		$duration = 3;
	}

	return gmdate( 'Y-m-d H:i:s', strtotime( '+' . $duration . 'days' ) );
}

/**
 * Retrieve the current invoice from WDS variables.
 *
 * @return string The current invoice.
 */
function wds_get_current_invoice_slug() {
	return wds_get_vars( '__invoice' );
}

/**
 * Get the unpaid invoice data.
 *
 * @param int $user_id The user ID.
 * @return array The unpaid invoice data.
 */
function wds_get_invoice_unpaid( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user_id  = intval( $user_id );
	$invoices = array();

	$query = WDS\Models\Invoice::query( 'WHERE user_id = %d AND status = %s', $user_id, 'unpaid' )->get();
	if ( $query->found() > 0 ) {
		foreach ( $query as $unpaid ) {
			if ( $unpaid->due_date_at && strtotime( $unpaid->due_date_at ) <= strtotime( 'now' ) ) {
				wds_update_invoice_status( $unpaid->ID, 'cancelled' );
				continue;
			}

			$invoices[ $unpaid->ID ] = (object) array(
				'number'      => $unpaid->number,
				'created_at'  => $unpaid->created_at,
				'due_date_at' => $unpaid->due_date_at,
				'total'       => wds_invoice_summary( $unpaid, 'total' ),
				'product'     => wds_invoice_summary( $unpaid, 'product_title' ),
				'link'        => wds_url( 'pay', wds_encrypt_decrypt( $unpaid->ID ) ),
			);
		}
	}

	return $invoices;
}

/**
 * Get the invoice transactions data.
 *
 * @param int $user_id The user ID.
 * @return array The invoice transaction data.
 */
function wds_get_invoice_transactions( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user_id  = intval( $user_id );
	$invoices = array();

	$query = WDS\Models\Invoice::query( 'WHERE user_id = %d', $user_id )->order( 'ID', 'DESC' )->get();
	if ( $query->found() > 0 ) {
		foreach ( $query as $inv ) {
			$product = '';
			foreach ( $inv->orders() as $order ) {
				$product = wds_get_product( $order->product_id );
				if ( $product ) {
					$product = $product->title;
				}
			}

			$invorder = wds_get_invoice_order( $inv->ID );

			switch ( $inv->status ) {
				case 'completed':
					$color = 'primary';
					break;
				case 'refunded':
					$color = 'info';
					break;
				case 'cancelled':
					$color = 'danger';
					break;
				default:
					$color = 'warning';
			}

			$link = '#!';
			if ( 'completed' !== $inv->status ) {
				$link = wds_url( 'pay', wds_encrypt_decrypt( $inv->ID ) );
			}

			$invoices[] = (object) array(
				'order_id' => $invorder->order_id,
				'number'   => $inv->number,
				'created'  => wds_date_format( strtotime( $inv->created_at ) ),
				'duedate'  => wds_date_format( strtotime( $inv->due_date_at ) ),
				'product'  => $product,
				'price'    => 0 == floatval( $inv->total ) ? wds_lang( 'free' ) : wds_convert_money( wds_invoice_summary( $inv, 'total' ) ),
				'gateway'  => $inv->gateway,
				'status'   => $inv->status,
				'color'    => $color,
				'link'     => $link,
			);
		}
	}

	return $invoices;
}

/**
 * Get invoices start date.
 *
 * @return datetime The start date.
 */
function wds_get_invoices_start_date() {
	global $wpdb;

	$data = $wpdb->get_row( "SELECT ID,created_at FROM {$wpdb->prefix}wds_invoice ORDER BY created_at ASC" );

	return $data && $data->created_at ? gmdate( 'Y-m-d', strtotime( $data->created_at ) ) : '2000-01-01';
}

/**
 * Get invoices summary date.
 *
 * @param int|object $invoice The invoice ID or object.
 * @param string     $key The get data key.
 * @return string The summary date.
 */
function wds_invoice_summary( $invoice = false, $key = 'all' ) {
	$obj = wds_get_invoice( $invoice );
	if ( ! $obj ) {
		return false;
	}

	$summary = $obj->summary;

	$v2  = wds_sanitize_data_field( $summary, 'wds_v2' );
	$ret = false;
	switch ( $key ) {
		case 'all':
			$ret = $summary;
			break;
		case 'product_id':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'product_id' ) : $summary['product']['id'];
			break;
		case 'product_title':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'product_title' ) : $summary['product']['label'];
			break;
		case 'product_price':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'product_price' ) : $summary['product']['price']['value'];
			break;
		case 'addon_title':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'addon_title' ) : '';
			break;
		case 'addon_price':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'addon_price' ) : '';
			break;
		case 'subtotal':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'subtotal' ) : $summary['subtotal']['value'];
			break;
		case 'total':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'total' ) : $summary['total']['value'];
			break;
		case 'discount':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'discount' ) : $summary['rebate']['value'];
			break;
		case 'commission':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'commission' ) : $summary['commission']['value'];
			break;
		case 'unique_number':
			$ret = $v2 ? wds_sanitize_data_field( $summary, 'unique_number' ) : $summary['unique']['value'];
			break;
	}

	return $ret;
}
