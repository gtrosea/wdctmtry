<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a replica.
 *
 * @param int|object $replica The replica ID or object.
 * @return mixed Replicas object if found, false otherwise.
 */
function wdr_get( $replica = false ) {
	return WDS()->database->get( 'replica', $replica );
}

/**
 * Retrieves a replica by query.
 *
 * @param string $query The query fo search.
 * @return mixed Replicas object if found, false otherwise.
 */
function wdr_get_by( $query = false ) {
	return WDS()->database->get_by_query( 'replica', $query );
}

/**
 * Insert a new replica.
 *
 * @param array $data Replica data to insert.
 * @return mixed The ID of the new replica, or false on failure.
 */
function wdr_insert( $data = array() ) {
	return WDS()->database->add( 'replica', $data );
}

/**
 * Delete a replica.
 *
 * @param int|object $replica The replica ID or object.
 * @return mixed True if delete was successful, false otherwise.
 */
function wdr_delete( $replica = false ) {
	$obj = wdr_get( $replica );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'replica', $obj->ID );
}

/**
 * Update an existing replica by domain.
 *
 * @param int    $user_id The User ID.
 * @param string $domain The old domain.
 * @param string $new_domain The new domain.
 * @return mixed True if update was successful, false otherwise.
 */
function wdr_update_domain( $user_id, $domain, $new_domain ) {
	if ( empty( $user_id ) || empty( $domain ) || empty( $new_domain ) ) {
		return false;
	}

	$obj = wdr_get_by( "WHERE domain = '$domain' AND user_id = '$user_id'" );
	if ( ! $obj ) {
		return false;
	}

	if ( $new_domain == $obj->domain ) {
		return false;
	}

	$data = array(
		'ID'     => $obj->ID,
		'domain' => $new_domain,
	);

	return WDS()->database->update( 'replica', $data );
}

/**
 * Update an existing replica by subdomain.
 *
 * @param int    $user_id The User ID.
 * @param string $subdomain The old subdomain.
 * @param string $new_subdomain The new subdomain.
 * @return mixed True if update was successful, false otherwise.
 */
function wdr_update_subdomain( $user_id, $subdomain, $new_subdomain ) {
	if ( empty( $user_id ) || empty( $subdomain ) || empty( $new_subdomain ) ) {
		return false;
	}

	$obj = wdr_get_by( "WHERE subdomain = '$subdomain' AND user_id = '$user_id'" );
	if ( ! $obj ) {
		return false;
	}

	if ( $new_subdomain == $obj->subdomain ) {
		return false;
	}

	$data = array(
		'ID'        => $obj->ID,
		'subdomain' => $new_subdomain,
	);

	return WDS()->database->update( 'replica', $data );
}

/**
 * Update the status of a replica.
 *
 * @param int|object $replica The replica ID or object.
 * @param string     $new_status New status for the replica.
 * @return mixed True on success, false on failure.
 */
function wdr_update_status( $replica = false, $new_status = '' ) {
	$obj = wdr_get( $replica );
	if ( ! $obj ) {
		return false;
	}

	$new_status = wds_sanitize_text_field( $new_status );
	$is_exists  = wds_get_replica_statuses( $new_status );
	if ( ! $is_exists ) {
		return false;
	}

	if ( $new_status == $obj->status ) {
		return false;
	}

	$data = array(
		'ID'     => $obj->ID,
		'status' => $new_status,
	);

	return WDS()->database->update( 'replica', $data );
}
