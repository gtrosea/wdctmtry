<?php
/**
 * Checkout Table.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Core/Models
 */

namespace WDS\Models;

use WDS\Abstracts\Database;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Checkout Class.
 */
class Checkout extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_checkout';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'         => 'integer',
		'user'       => 'string',
		'created_at' => 'string',
		'updated_at' => 'string',
	);

	/**
	 * The attributes of the model.
	 *
	 * This array can be used to store additional attributes or settings related to the model.
	 *
	 * @var array
	 */
	protected $attributes = array();

	// /**
	//  * The metadata associated with the checkout.
	//  *
	//  * This property holds all metadata for the checkout, which is retrieved from the database.
	//  *
	//  * @var array
	//  */
	// protected $meta = array();

	// /**
	//  * Retrieve the metadata for the checkout.
	//  *
	//  * This method fetches metadata associated with a specific checkout from the `Checkout_Meta` table.
	//  * If the metadata is already stored, it returns the cached version. If a specific meta key is provided,
	//  * it will return the value associated with that key, otherwise, it will return all metadata.
	//  *
	//  * @param string|bool $meta_key Optional. The meta key to retrieve. Default is false, which returns all metadata.
	//  *
	//  * @return mixed The metadata value for the specified key, or all metadata if no key is specified.
	//  */
	// public function meta( $meta_key = false ) {
	//  if ( empty( $this->meta ) ) {
	//      $get_all_meta = Checkout_Meta::query( 'WHERE checkout_id = %d', $this->ID )->get();
	//      $all_meta     = array();

	//      if ( $get_all_meta ) {
	//          foreach ( $get_all_meta as $key => $value ) {
	//              if ( isset( $all_meta[ $value->meta_key ] ) ) {

	//                  if ( is_array( $all_meta[ $value->meta_key ] ) ) {
	//                      $all_meta[ $value->meta_key ][] = \maybe_unserialize( $value->meta_value );
	//                  } else {
	//                      $temp = array(
	//                          $all_meta[ $value->meta_key ],
	//                          \maybe_unserialize( $value->meta_value ),
	//                      );

	//                      unset( $all_meta[ $value->meta_key ] );

	//                      $all_meta[ $value->meta_key ] = $temp;
	//                  }
	//              } else {
	//                  $all_meta[ $value->meta_key ] = \maybe_unserialize( $value->meta_value );
	//              }
	//          }
	//      }

	//      $this->meta = $all_meta;
	//  }

	//  if ( $meta_key ) {
	//      return isset( $this->meta[ $meta_key ] ) ? $this->meta[ $meta_key ] : false;
	//  }

	//  return $this->meta;
	// }
}
