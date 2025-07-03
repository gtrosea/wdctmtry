<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// /**
//  * Retrieves a checkout.
//  *
//  * @param int|object $checkout The checkout ID or object.
//  * @return mixed Checkout object if found, false otherwise.
//  */
// function wds_get_checkout( $checkout = false ) {
//  return WDS()->database->get( 'checkout', $checkout );
// }

// /**
//  * Insert a new checkout.
//  *
//  * @param array $data Checkout data to insert.
//  * @return mixed The ID of the new checkout, or false on failure.
//  */
// function wds_insert_checkout( $data = array() ) {
//  return WDS()->database->add( 'checkout', $data );
// }

// /**
//  * Update existing checkout.
//  *
//  * @param array $data Checkout data to update.
//  * @return mixed True if update was successful, false otherwise.
//  */
// function wds_update_checkout( $data = array() ) {
//  if ( empty( $data['ID'] ) ) {
//      return false;
//  }

//  $obj = wds_get_checkout( $data['ID'] );
//  if ( ! $obj ) {
//      return wds_insert_checkout( $data );
//  }

//  return WDS()->database->update( 'checkout', $data );
// }

// /**
//  * Delete a checkout.
//  *
//  * @param int|object $checkout The checkout ID or object.
//  * @return bool True if delete was successful, false otherwise.
//  */
// function wds_delete_checkout( $checkout = false ) {
//  $obj = wds_get_checkout( $checkout );
//  if ( ! $obj ) {
//      return false;
//  }

//  $deleted = WDS()->database->delete( 'checkout', $obj->ID );

//  if ( $deleted ) {
//      WDS\Models\Checkout_Meta::delete( array( 'checkout_id' => $obj->ID ) );
//  }

//  return $deleted;
// }

// /**
//  * Retrieves a checkout object by $username.
//  *
//  * @param string $username The checkout username.
//  * @return mixed Checkout object if found, null otherwise.
//  */
// function wds_get_checkout_by_user( $username = false ) {
//  if ( ! $username ) {
//      return false;
//  }

//  $query = "WHERE user = '$username'";

//  return WDS()->database->get_by_query( 'checkout', $query );
// }
