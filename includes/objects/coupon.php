<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a coupon.
 *
 * @param int|object $coupon The coupon ID or object.
 * @return mixed Coupons object if found, false otherwise.
 */
function wds_get_coupon( $coupon = false ) {
	return WDS()->database->get( 'coupon', $coupon );
}

/**
 * Insert a new coupon.
 *
 * @param array $data Coupon data to insert.
 * @return mixed The ID of the new coupon, or false on failure.
 */
function wds_insert_coupon( $data = array() ) {
	$coupon_id = WDS()->database->add( 'coupon', $data );

	if ( ! is_wp_error( $coupon_id ) ) {
		$code = wds_sanitize_data_field( $data, 'code', WDS()->database->generate( 10 ) );
		wds_insert_coupon_code( $coupon_id, strtoupper( $code ) );
	}

	return $coupon_id;
}

/**
 * Update an existing coupon.
 *
 * @param array $data Coupon data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_coupon( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_coupon( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_coupon( $data );
	}

	$updated = WDS()->database->update( 'coupon', $data );

	if ( ! is_wp_error( $updated ) ) {
		$code    = wds_sanitize_data_field( $data, 'code', WDS()->database->generate( 10 ) );
		$code_id = wds_get_coupon_code( $obj->ID, null, true );
		if ( $code_id && $code_id > 0 ) {
			wds_update_coupon_code( $obj->ID, $code_id, strtoupper( $code ) );
		} else {
			wds_insert_coupon_code( $obj->ID, strtoupper( $code ) );
		}
	}

	return $updated;
}

/**
 * Delete a coupon.
 *
 * @param int|object $coupon The coupon ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_coupon( $coupon = false ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $obj ) {
		return false;
	}

	$deleted = WDS()->database->delete( 'coupon', $obj->ID );

	if ( $deleted ) {
		WDS\Models\Coupon_Code::delete( array( 'coupon_id' => $obj->ID ) );
		WDS\Models\Coupon_Usage::delete( array( 'coupon_id' => $obj->ID ) );
	}

	return $deleted;
}

/**
 * Update the status of a coupon.
 *
 * @param int|object $coupon The coupon ID or object.
 * @param string     $new_status New status for the coupon.
 * @return mixed True on success, false on failure.
 */
function wds_update_coupon_status( $coupon = false, $new_status = '' ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $obj ) {
		return false;
	}

	$new_status = wds_sanitize_text_field( $new_status );
	$is_exists  = wds_get_coupon_statuses( $new_status );
	if ( ! $is_exists ) {
		return false;
	}

	$data = array(
		'ID'         => $obj->ID,
		'status'     => $new_status,
		'updated_at' => gmdate( 'Y-m-d H:i:s' ),
	);

	return WDS()->database->update( 'coupon', $data );
}

/**
 * Retrieve a code.
 *
 * @param int $code_id The code ID.
 * @return mixed Code if found, false otherwise.
 */
function wds_get_code( $code_id = 0 ) {
	return WDS()->database->get( 'code', intval( $code_id ) );
}

/**
 * Retrieve a coupon code.
 *
 * @param int|object $coupon The coupon ID or object.
 * @param int        $user_id User ID.
 * @param bool       $_ids The get code id.
 * @param bool       $flip The flip user id.
 * @return mixed Coupon code if found, false otherwise.
 */
function wds_get_coupon_code( $coupon = false, $user_id = false, $_ids = false, $flip = false ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $obj ) {
		return false;
	}

	$coupon_id = $obj->ID;

	if ( ! $user_id ) {
		$query = "WHERE coupon_id = '$coupon_id' AND user_id IS NULL";
	} else {
		$query = "WHERE coupon_id = '$coupon_id' AND user_id = '$user_id'";
		if ( $flip ) {
			$query = "WHERE coupon_id = '$coupon_id' AND user_id != '$user_id'";
		}
	}

	$check_code = WDS()->database->get_by_query( 'code', $query );

	if ( $check_code && $check_code->code_id > 0 ) {
		if ( $_ids ) {
			return $check_code->code_id;
		} else {
			return $check_code->code;
		}
	}

	return false;
}

/**
 * Retrieve a coupon code by code.
 *
 * @param string $code The coupon code.
 * @param int    $user_id User ID.
 * @param bool   $_ids The get code id.
 * @param bool   $flip The flip user id.
 * @return mixed Coupon code if found, false otherwise.
 */
function wds_get_coupon_code_by_code( $code = false, $user_id = false, $_ids = false, $flip = false ) {
	if ( ! $code ) {
		return false;
	}

	if ( ! $user_id ) {
		$query = "WHERE code = '$code' AND user_id IS NULL";
	} else {
		$query = "WHERE code = '$code' AND user_id = '$user_id'";
		if ( $flip ) {
			$query = "WHERE code = '$code' AND user_id != '$user_id'";
		}
	}

	$check_code = WDS()->database->get_by_query( 'code', $query );

	if ( $check_code && $check_code->code_id > 0 ) {
		if ( $_ids ) {
			return $check_code->code_id;
		} else {
			return $check_code->code;
		}
	}

	return false;
}

/**
 * Add coupon code.
 *
 * @param int|object $coupon The coupon ID or object.
 * @param string     $code Coupon code.
 * @param int        $user_id User ID.
 * @return mixed The added code if successful, or false if invalid input or failure.
 */
function wds_insert_coupon_code( $coupon, $code, $user_id = null ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $obj || empty( $code ) ) {
		return false;
	}

	$data = array(
		'coupon_id' => $obj->ID,
		'user_id'   => $user_id,
		'code'      => strtoupper( wp_unslash( $code ) ),
	);

	if ( null !== $user_id || is_numeric( $user_id ) ) {
		$data['user_id'] = intval( $user_id );
	}

	return WDS()->database->add( 'code', $data );
}

/**
 * Update coupon code.
 *
 * @param int|object $coupon The coupon ID or object.
 * @param int        $code_id Code ID.
 * @param string     $code Coupon code.
 * @param int        $user_id User ID.
 * @return mixed The updated code if successful, or false if invalid input or failure.
 */
function wds_update_coupon_code( $coupon, $code_id, $code, $user_id = null ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $obj || ! is_numeric( $code_id ) || empty( $code ) ) {
		return false;
	}

	$coupon_id = $obj->ID;

	$code = strtoupper( wp_unslash( $code ) );
	$data = array(
		'code_id'   => $code_id,
		'coupon_id' => $coupon_id,
		'code'      => $code,
	);

	if ( null === $user_id || ! is_numeric( $user_id ) ) {
		$existing_code_id = wds_get_coupon_code( $coupon_id, null, true );
	} else {
		$existing_code_id = wds_get_coupon_code( $coupon_id, $user_id, true );

		$data['user_id'] = intval( $user_id );
	}

	if ( $existing_code_id && $existing_code_id == $code_id ) {
		return WDS()->database->update( 'code', $data );
	} else {
		return wds_insert_coupon_code( $coupon_id, $code, $user_id );
	}
}

/**
 * Delete coupon code.
 *
 * @param int $code_id Code ID.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_coupon_code( $code_id ) {
	$obj = wds_get_code( $code_id );
	if ( ! $obj ) {
		return false;
	}

	$deleted = WDS()->database->delete( 'code', $obj->code_id );

	if ( $deleted ) {
		WDS\Models\Coupon_Usage::delete( array( 'code_id' => $obj->code_id ) );
	}

	return $deleted;
}

/**
 * Get coupon usage.
 *
 * @param int $code_id Code ID.
 * @return int The total usage coupon.
 */
function wds_get_coupon_usage( $code_id ) {
	$obj = wds_get_code( $code_id );
	if ( ! $obj ) {
		return false;
	}

	$usage = WDS\Models\Coupon_Usage::select( 'COUNT(usage_id) as count' )->query( "WHERE code_id = '$code_id'" )->result();

	return $usage[0]->count;
}

/**
 * Add coupon usage.
 *
 * @param int|object $coupon The coupon ID or object.
 * @param int        $code_id Code ID.
 * @param int        $invoice_id Invoice ID.
 * @return bool True if added was successful, false otherwise.
 */
function wds_insert_coupon_usage( $coupon, $code_id, $invoice_id ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $obj || ! is_numeric( $code_id ) || ! is_numeric( $invoice_id ) ) {
		return false;
	}

	$args = array(
		'coupon_id'  => $obj->ID,
		'code_id'    => intval( $code_id ),
		'invoice_id' => intval( $invoice_id ),
	);

	return WDS\Models\Coupon_Usage::data( $args )->create();
}

/**
 * Retrieve a coupon by a specific key and value.
 *
 * @param string $key   The key to search.
 * @param string $value The value to search for.
 * @return mixed Products object if found, false otherwise.
 */
function wds_get_coupon_by( $key, $value ) {
	$query = "WHERE $key = '$value'";

	return WDS()->database->get_by_query( 'coupon', $query );
}

/**
 * Get all active coupons sorted by the specified order.
 *
 * @param string $sort Sorting order for active coupons (optional). Default is 'DESC'.
 * @return object|array The data product with status active.
 */
function wds_get_coupon_active( $sort = 'DESC' ) {
	return WDS()->database->get_data_active( 'coupon', $sort );
}

/**
 * Check if a coupon is valid for a specific product.
 *
 * @param string $coupon_code The coupon code to check.
 * @param int    $product_id  The ID of the product to check against the coupon.
 * @return mixed Coupon object if found, false otherwise.
 */
function wds_check_coupon_product( $coupon_code, $product_id ) {
	if ( empty( $coupon_code ) || empty( $product_id ) ) {
		return false;
	}

	$coupon_code = strtoupper( wds_sanitize_text_field( $coupon_code ) );
	$product_id  = intval( $product_id );

	$query  = "WHERE status = 'active' AND code = '$coupon_code' AND products IS NOT NULL AND products LIKE '%\"$product_id\"%'";
	$query .= " OR status = 'active' AND code = '$coupon_code' AND products IS NULL";

	$coupon = WDS\Models\Coupon::join( 'right', WDS_MODEL . '_coupon_code', array( 'ID', 'coupon_id', '=' ) )->query( $query )->first();

	return $coupon->ID > 0 ? $coupon : false;
}

/**
 * Check if the coupon has reached its usage limit.
 *
 * @param int|object $coupon The coupon ID or object.
 * @param string     $coupon_code The coupon code.
 * @return bool True if limit, false otherwise.
 */
function wds_check_coupon_limit( $coupon, $coupon_code ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $coupon ) {
		return false;
	}

	$coupon_id   = $obj->ID;
	$coupon_code = strtoupper( wds_sanitize_text_field( $coupon_code ) );

	$max_usage = $obj->max_usage;
	if ( empty( $max_usage ) || '0' == $max_usage ) {
		return false;
	}

	$query = "WHERE coupon_id = '$coupon_id' AND code = '$coupon_code'";
	$check = WDS()->database->get_by_query( 'code', $query );
	if ( ! $check ) {
		return false;
	}

	$usage = WDS\Models\Coupon_Usage::query( 'WHERE coupon_id = %d AND code_id = %d', $coupon_id, $check->code_id )->count();
	if ( $usage >= $max_usage ) {
		return true;
	}

	return false;
}

/**
 * Get a list of available coupons for upgrade.
 *
 * @return array The data coupon for upgrade.
 */
function wds_coupon_list_for_upgrade() {
	$query = WDS\Models\Coupon_Code::query( 'WHERE user_id IS NULL' )->order( 'code_id', 'DESC' )->get();
	$code  = array();

	$code[''] = __( 'Tanpa kupon', 'weddingsaas' );
	if ( $query->found() > 0 ) {
		foreach ( $query as $coupon ) {
			$code[ $coupon->code ] = $coupon->code;
		}
	}

	return $code;
}

/**
 * Get a list of affiliate coupons.
 *
 * @param int $user_id The user ID.
 * @return array The data coupon.
 */
function wds_get_affiliate_coupon( $user_id = false ) {
	global $wpdb;

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$coupons = array();
	$query   = WDS\Models\Coupon::right_join( WDS_MODEL . '_coupon_code', array( WDS_MODEL . '_coupon.ID', WDS_MODEL . '_coupon_code.coupon_id', '=' ) )
	->select(
		$wpdb->prefix . WDS_MODEL . '_coupon.*',
		$wpdb->prefix . WDS_MODEL . '_coupon_code.user_id',
		$wpdb->prefix . WDS_MODEL . '_coupon_code.code'
	)
	->query( "WHERE status = 'active' AND user_id IS NULL AND is_private != 1" )->get();

	foreach ( $query as $coupon ) {
		$user_code = wds_get_coupon_code( $coupon->ID, $user_id );
		if ( empty( $user_code ) ) {
			$user_code = '';
		}

		$coupons[] = array(
			'id'        => $coupon->ID,
			'rebate'    => strpos( $coupon->rebate, '%' ) !== false ? $coupon->rebate : wds_convert_money( $coupon->rebate ),
			'code'      => $coupon->code,
			'code_user' => $user_code,
		);
	}

	return $coupons;
}

/**
 * Get the raw rebate information for the coupon.
 *
 * @param int|object $coupon The coupon ID or object.
 * @return array An array containing the type ('percen' or 'fixed') and value of the rebate.
 */
function wds_get_coupon_raw_rebate( $coupon = false ) {
	$obj = wds_get_coupon( $coupon );
	if ( ! $obj ) {
		return false;
	}

	$rebate_array = array();

	$rebate = $obj->rebate;
	if ( ! empty( $rebate ) ) {
		if ( strpos( $rebate, '%' ) ) {
			$rebate = str_replace( '%', '', $rebate );

			$rebate_array = array(
				'type'  => 'percen',
				'value' => intval( $rebate ),
			);
		} else {
			$rebate_array = array(
				'type'  => 'fixed',
				'value' => intval( $rebate ),
			);
		}
	}

	return $rebate_array;
}
