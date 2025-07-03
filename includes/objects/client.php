<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a client.
 *
 * @param int|object $client The client ID or object.
 * @return mixed Client object if found, false otherwise.
 */
function wds_get_client( $client = false ) {
	return WDS()->database->get( 'client', $client );
}

/**
 * Insert a new client.
 *
 * @param array $data Client data to insert.
 * @return mixed The ID of the new client, or false on failure.
 */
function wds_insert_client( $data = array() ) {
	return WDS()->database->add( 'client', $data );
}

/**
 * Update existing client.
 *
 * @param array $data Client data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_client( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_client( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_client( $data );
	}

	return WDS()->database->update( 'client', $data );
}

/**
 * Delete a client.
 *
 * @param int|object $client The client ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_client( $client = false ) {
	$obj = wds_get_client( $client );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'client', $obj->ID );
}

/**
 * Get all client.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return array The data client.
 */
function wds_get_all_client( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$client = array();
	$query  = \WDS\Models\Client::query( 'WHERE reseller_id = "' . $user_id . '"' )->order( 'ID', 'DESC' )->get();
	if ( $query->found() > 0 ) {
		foreach ( $query as $result ) {
			$user_id = $result->client_id;
			$user    = get_userdata( $user_id );
			if ( $user ) {
				$client[] = (object) array(
					'ID'    => $user_id,
					'name'  => wds_user_name( $user_id ),
					'email' => wds_user_email( $user_id ),
					'phone' => wds_user_phone( $user_id ),
					'join'  => $user->user_registered,

				);
			}
		}
	}

	return $client;
}
