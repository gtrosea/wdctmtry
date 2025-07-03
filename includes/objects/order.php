<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a order.
 *
 * @param int|object $order The order ID or object.
 * @return mixed Orders object if found, false otherwise.
 */
function wds_get_order( $order = false ) {
	return WDS()->database->get( 'order', $order );
}

/**
 * Insert a new order.
 *
 * @param array $data Order data to insert.
 * @return mixed The ID of the new order, or false on failure.
 */
function wds_insert_order( $data = array() ) {
	$order_id = WDS()->database->add( 'order', $data );

	if ( ! is_wp_error( $order_id ) ) {
		wds_insert_order_log( $order_id, __( 'Order Created', 'wds-notrans' ) );
	}

	return $order_id;
}

/**
 * Update an existing order.
 *
 * @param array $data Order data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_order( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_order( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_order( $data );
	}

	return WDS()->database->update( 'order', $data );
}

/**
 * Delete a order.
 *
 * @param int|object $order The order ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_order( $order = false ) {
	$obj = wds_get_order( $order );
	if ( ! $obj ) {
		return false;
	}

	$order_id = $obj->ID;
	$invoices = $obj->invoices();
	$deleted  = WDS()->database->delete( 'order', $order_id );

	if ( $deleted ) {
		foreach ( $invoices as $invoice ) {
			wds_delete_invoice( $invoice->ID );
		}
		WDS\Models\Invoice_Order::delete( array( 'order_id' => $order_id ) );
		WDS\Models\Order_Log::delete( array( 'order_id' => $order_id ) );
		WDS\Models\Order_Meta::delete( array( 'order_id' => $order_id ) );
		wds_delete_commission_by_order( $order_id );
	}

	return $deleted;
}

/**
 * Update the status of a order.
 *
 * @param int|object $order The order ID or object.
 * @param string     $new_status New status for the order.
 * @return mixed True on success, false on failure.
 */
function wds_update_order_status( $order = false, $new_status = '' ) {
	$obj = wds_get_order( $order );
	if ( ! $obj ) {
		return false;
	}

	$old_status = $obj->status;
	$new_status = wds_sanitize_text_field( $new_status );
	$is_exists  = wds_get_order_statuses( $new_status );
	if ( ! $is_exists ) {
		return false;
	}

	if ( $old_status == $new_status ) {
		return false;
	}

	$data = array(
		'ID'         => $obj->ID,
		'status'     => $new_status,
		'updated_at' => gmdate( 'Y-m-d H:i:s' ),
	);

	return WDS()->database->update( 'order', $data );
}

/**
 * Retrieve metadata for a order.
 *
 * @param int|object $order order ID to retrieve metadata for.
 * @param string     $meta_key The metadata key to retrieve.
 * @param bool       $single Whether to return a single value (true) or an array of values (false).
 * @return mixed The retrieved data if found, or false if not found or invalid input.
 */
function wds_get_order_meta( $order, $meta_key, $single = true ) {
	$obj = wds_get_order( $order );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->meta->get_meta( 'order', $obj->ID, $meta_key, $single );
}

/**
 * Add metadata for a order.
 *
 * @param int|object $order order ID to add metadata to.
 * @param string     $meta_key The metadata key to add.
 * @param mixed      $meta_value The value to set for the metadata key.
 * @param bool       $unique Whether the metadata key should be unique (true) or not (false).
 * @return mixed The added meta if successful, or false if invalid input or failure.
 */
function wds_add_order_meta( $order, $meta_key, $meta_value, $unique = false ) {
	$obj = wds_get_order( $order );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->meta->add_meta( 'order', $obj->ID, $meta_key, $meta_value, $unique );
}

/**
 * Update metadata for a order.
 *
 * @param int|object $order order ID to update metadata for.
 * @param string     $meta_key The metadata key to update.
 * @param mixed      $meta_value The new value for the metadata key.
 * @return mixed The updated meta if successful, or false if invalid input or failure.
 */
function wds_update_order_meta( $order, $meta_key, $meta_value ) {
	$obj = wds_get_order( $order );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->meta->update_meta( 'order', $obj->ID, $meta_key, $meta_value );
}

/**
 * Delete metadata for a order.
 *
 * @param int|object $order order ID to delete metadata from.
 * @param string     $meta_key The metadata key to delete.
 * @param mixed      $meta_value Optional. The value of the metadata to delete. If not provided, all values for the key will be deleted.
 * @return bool The deleted meta if successful, or false if invalid input or failure.
 */
function wds_delete_order_meta( $order, $meta_key, $meta_value = false ) {
	$obj = wds_get_order( $order );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->meta->delete_meta( 'order', $obj->ID, $meta_key, $meta_value );
}

/**
 * Add order log.
 *
 * @param int|object $order order ID to delete metadata from.
 * @param string     $note The note order.
 * @return mixed The added log if successful, or false if invalid input or failure.
 */
function wds_insert_order_log( $order = false, $note = '' ) {
	$obj = wds_get_order( $order );
	if ( ! $obj || empty( $note ) ) {
		return false;
	}

	$args = array(
		'order_id' => $obj->ID,
		'note'     => wp_kses_post( $note ),
	);

	return WDS\Models\Order_Log::data( $args )->create();
}

/**
 * Generates an order code.
 *
 * @return string The generated order code.
 */
function wds_generate_order_code() {
	return strtoupper( WDS()->database->generate( 30 ) );
}

/**
 * Retrieves the current order ID.
 *
 * @return string The original / decrypted order ID.
 */
function wds_get_current_order_id() {
	return wds_get_vars( 'order_id' );
}

/**
 * Checks if an order is renewed.
 *
 * @param int|object $order The order ID or object.
 * @return string|bool The renewal URL if the order is renewed, false otherwise.
 */
function wds_order_is_renewed( $order = false ) {
	$obj = wds_get_order( $order );
	if ( ! $obj ) {
		return false;
	}

	$renew   = false;
	$expired = $obj->expired_at;

	if ( empty( $expired ) || null == $expired ) {
		return false;
	}

	$gmexpired  = gmdate( 'Y-m-d', strtotime( $expired ) );
	$expired_at = empty( $expired ) || '1970-01-01' == $gmexpired ? '~' : $gmexpired;

	if ( 'expired' == $obj->status ) {
		$encoded = wds_encrypt_decrypt( $obj->ID );
		$renew   = wds_url( 'renew', $encoded );
	}

	if ( '~' != $expired_at && 'active' == $obj->status ) {
		$expired   = strtotime( $expired );
		$last_date = strtotime( '+ 7 days' );
		if ( $expired < $last_date ) {
			$encoded = wds_encrypt_decrypt( $obj->ID );
			$renew   = wds_url( 'renew', $encoded );
		}
	}

	return $renew;
}

/**
 * Get the expired order data.
 *
 * @param int $user_id The user ID.
 * @return array The order data.
 */
function wds_get_order_expired( $user_id = 0 ) {
	$user_id = intval( $user_id );
	$orders  = array();

	$query = WDS\Models\Order::query( 'WHERE user_id = %s AND status = %s', $user_id, 'expired' )->get();
	if ( $query->found() > 0 ) {
		foreach ( $query as $expired ) {
			$product = wds_get_product( $expired->product_id );
			if ( ! $product ) {
				continue;
			}

			$orders[] = (object) array(
				'product' => $product->title,
				'at'      => gmdate( 'Y-m-d', strtotime( $expired->expired_at ) ),
				'renew'   => wds_order_is_renewed( $expired->ID ),
			);
		}
	}

	return $orders;
}
