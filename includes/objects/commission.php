<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a commission.
 *
 * @param int|object $commission The commission ID or object.
 * @return mixed Commission object if found, false otherwise.
 */
function wds_get_commission( $commission = false ) {
	return WDS()->database->get( 'commission', $commission );
}

/**
 * Retrieve a commission by a query.
 *
 * @param string $query The data query.
 * @return mixed Commission object if found, false otherwise.
 */
function wds_get_commission_by( $query ) {
	return WDS()->database->get_by_query( 'commission', $query );
}

/**
 * Insert a new commission.
 *
 * @param array $data Commission data to insert.
 * @return mixed The ID of the new commission, or false on failure.
 */
function wds_insert_commission( $data = array() ) {
	return WDS()->database->add( 'commission', $data );
}

/**
 * Update existing commission.
 *
 * @param array $data Commission data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_commission( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_commission( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_commission( $data );
	}

	return WDS()->database->update( 'commission', $data );
}

/**
 * Delete a commission.
 *
 * @param int|object $commission The commission ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_commission( $commission = false ) {
	$obj = wds_get_commission( $commission );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'commission', $obj->ID );
}

/**
 * Delete a commission by order id.
 *
 * @param int $order_id The order ID.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_commission_by_order( $order_id = false ) {
	$obj = wds_get_commission_by( "WHERE order_id = '$order_id'" );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'commission', $obj->ID );
}

/**
 * Update the status of a commission.
 *
 * @param int|object $commission The commission ID or object.
 * @param string     $new_status The new status of the commission.
 * @return mixed True on success, false on failure.
 */
function wds_update_commission_status( $commission = false, $new_status = '' ) {
	$obj = wds_get_commission( $commission );
	if ( ! $obj ) {
		return false;
	}

	$new_status = wds_sanitize_text_field( $new_status );
	$is_exists  = wds_get_commission_statuses( $new_status );
	if ( ! $is_exists ) {
		return false;
	}

	if ( $new_status == $obj->status ) {
		return false;
	}

	$data = array(
		'ID'         => $obj->ID,
		'status'     => $new_status,
		'updated_at' => gmdate( 'Y-m-d H:i:s' ),
	);

	return WDS()->database->update( 'commission', $data );
}

/**
 * Get commission statistics including totals for each status.
 *
 * @return array Commission statistics for paid, unpaid, pending, and cancelled commissions.
 */
function wds_get_commissions_statistics() {
	return WDS_Statistics::commissions_stats();
}
