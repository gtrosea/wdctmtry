<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a withdraw.
 *
 * @param int|object $withdraw The withdraw ID or object.
 * @return mixed Withdraw object if found, false otherwise.
 */
function wds_get_withdraw( $withdraw = false ) {
	return WDS()->database->get( 'withdraw', $withdraw );
}

/**
 * Insert a new withdraw.
 *
 * @param array $data Data array for the withdraw.
 * @return mixed The ID of the new withdraw, or false on failure.
 */
function wds_insert_withdraw( $data = array() ) {
	return WDS()->database->add( 'withdraw', $data );
}

/**
 * Update existing withdraw.
 *
 * @param array $data Withdraw data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_withdraw( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_withdraw( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_withdraw( $data );
	}

	return WDS()->database->update( 'withdraw', $data );
}

/**
 * Delete a withdraw.
 *
 * @param int|object $withdraw The withdraw ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_withdraw( $withdraw = false ) {
	$obj = wds_get_withdraw( $withdraw );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'withdraw', $obj->ID );
}
