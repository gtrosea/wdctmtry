<?php
/**
 * Product Table.
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
 * Product Class.
 */
class Product extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_product';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'          => 'integer',
		'slug'        => 'string',
		'title'       => 'string',
		'description' => 'content',
		'affiliate'   => 'integer',
		'status'      => 'string',
		'created_at'  => 'string',
		'updated_at'  => 'string',
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
	//  * Meta information associated with the product.
	//  *
	//  * @var array
	//  */
	// protected $meta = array();

	// /**
	//  * The price of the product.
	//  *
	//  * @var float
	//  */
	// protected $price = 0;

	// /**
	//  * Set the price of the product.
	//  *
	//  * @param float $price The price to set.
	//  * @return self
	//  */
	// public function set_price( $price ) {
	//  $this->price = wds_convert_price( $price );

	//  return $this;
	// }

	// /**
	//  * Get the product price.
	//  *
	//  * @param string $type The type of price to get (e.g., 'regular', 'renew').
	//  * @return float The product price.
	//  */
	// public function get_price( $type = 'regular' ) {
	//  $price = $this->price;

	//  if ( 'regular' != $type ) {
	//      $price = $this->meta( $type . '_price' );
	//  }

	//  if ( false === $price || empty( $price ) ) {
	//      $price = $this->meta( 'regular_price' );
	//  }

	//  return floatval( $price );
	// }

	// /**
	//  * Get the commission for an affiliate based on the product price.
	//  *
	//  * @param float $price Optional. The price to use for calculating the commission.
	//  * @return float The calculated commission.
	//  */
	// public function get_commission( $price = 0 ) {
	//  if ( ! $price ) {
	//      $price = $this->get_price();
	//  }

	//  $raw_commission = $this->get_raw_affiliate_commission();
	//  if ( 'percen' == $raw_commission['type'] ) {
	//      $commission = floatval( $raw_commission['value'] ) * $price;
	//      $commission = $commission / 100;
	//  } else {
	//      $commission = $raw_commission['value'];
	//  }

	//  return floatval( $commission );
	// }

	// /**
	//  * Get the raw affiliate commission information.
	//  *
	//  * This method retrieves the affiliate commission in either percentage or fixed value.
	//  *
	//  * @return array The commission type and value.
	//  */
	// public function get_raw_affiliate_commission() {
	//  $commission_array = array();
	//  $commission       = $this->meta( 'affiliate_commission' );

	//  if ( strpos( $commission, '%' ) ) {
	//      $commission = str_replace( '%', '', $commission );

	//      $commission_array = array(
	//          'type'  => 'percen',
	//          'value' => floatval( $commission ),
	//      );
	//  } else {
	//      $commission_array = array(
	//          'type'  => 'fixed',
	//          'value' => floatval( $commission ),
	//      );
	//  }

	//  return $commission_array;
	// }

	// /**
	//  * Retrieve meta data for the product.
	//  *
	//  * If no key is provided, all meta data is returned.
	//  *
	//  * @param string|false $meta_key Optional. The meta key to retrieve.
	//  * @return mixed The meta value if a key is provided, otherwise all meta data.
	//  */
	// public function meta( $meta_key = false ) {
	//  if ( empty( $this->meta ) ) {
	//      $get_all_meta = Product_Meta::query( 'WHERE product_id = %d', $this->ID )->get();

	//      $all_meta = array();

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
