<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a income.
 *
 * @param string $type The income type.
 * @param int    $data_id The data ID.
 * @return mixed Income object if found, false otherwise.
 */
function wds_get_income( $type = 'invitation', $data_id = 0 ) {
	$query = "WHERE type = '$type' AND data_id = '$data_id'";

	return WDS()->database->get_by_query( 'income', $query );
}

/**
 * Insert a new income.
 *
 * @param array $data Income data to insert.
 * @return mixed The ID of the new income, or false on failure.
 */
function wds_insert_income( $data = array() ) {
	return WDS()->database->add( 'income', $data );
}

/**
 * Update existing income.
 *
 * @param string $type The income type.
 * @param int    $data_id The data ID.
 * @param int    $price The income price.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_income( $type = 'invitation', $data_id = 0, $price = 0 ) {
	$obj = wds_get_income( $type, $data_id );
	if ( ! $obj ) {
		return false;
	}

	$data = array(
		'ID'    => intval( $obj->ID ),
		'price' => intval( $price ),
	);

	return WDS()->database->update( 'income', $data );
}

/**
 * Delete a income.
 *
 * @param string $type The income type.
 * @param int    $data_id The data ID.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_income( $type = 'invitation', $data_id = 0 ) {
	$obj = wds_get_income( $type, $data_id );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'income', $obj->ID );
}
