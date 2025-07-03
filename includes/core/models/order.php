<?php
/**
 * Order Table.
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
 * Order Class.
 */
class Order extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_order';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'         => 'integer',
		'code'       => 'string',
		'user_id'    => 'integer',
		'product_id' => 'integer',
		'status'     => 'string',
		'expired_at' => 'string',
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
	//  * The order log data.
	//  *
	//  * @var array
	//  */
	// protected $log = array();

	/**
	 * The invoices associated with the order.
	 *
	 * @var array
	 */
	protected $invoices = array();

	// /**
	//  * The product associated with the order.
	//  *
	//  * @var Product|null
	//  */
	// protected $product = null;

	// /**
	//  * Retrieve the order log data.
	//  *
	//  * @return array The log data for the order.
	//  */
	// public function log() {
	//  if ( empty( $this->log ) ) {
	//      $this->log = Order_Log::query( 'WHERE order_id = %d', $this->ID )->order( 'log_id', 'DESC' )->get();
	//  }

	//  return $this->log;
	// }

	/**
	 * Retrieve the invoices associated with the order.
	 *
	 * @global wpdb $wpdb WordPress database access object.
	 * @return array The invoices associated with the order.
	 */
	public function invoices() {
		global $wpdb;

		if ( empty( $this->invoices ) ) {
			$this->invoices = Invoice::join( 'right', WDS_MODEL . '_invoice_order', array( 'ID', 'invoice_id', '=' ) )
				->select(
					array( $wpdb->prefix . WDS_MODEL . '_invoice.*' ),
					array( $wpdb->prefix . WDS_MODEL . '_invoice_order.order_id' )
				)->query( 'WHERE order_id = %d', $this->ID )->get();
		}

		return $this->invoices;
	}

	// /**
	//  * Retrieve meta data for the order.
	//  *
	//  * If no key is provided, all meta data is returned.
	//  *
	//  * @param string|false $meta_key Optional. The meta key to retrieve.
	//  * @return mixed The meta value if a key is provided, otherwise all meta data.
	//  */
	// public function meta( $meta_key = false ) {
	//  if ( empty( $this->meta ) ) {
	//      $get_all_meta = Order_Meta::query( 'WHERE order_id = %d', $this->ID )->get();

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

	// /**
	//  * Get the product associated with the order.
	//  *
	//  * @return Product|null The product associated with the order.
	//  */
	// public function get_product() {
	//  if ( is_null( $this->product ) ) {
	//      $this->product = Product::query( 'WHERE ID = %d', $this->product_id )->first();
	//  }

	//  return $this->product;
	// }

	/**
	 * Get the renew price for the order.
	 *
	 * If no renew price is set, the total order amount is returned.
	 *
	 * @return float The renew price.
	 */
	public function get_renew_price() {
		$renew_price = wds_get_order_meta( $this->ID, 'renew_price', true );

		if ( empty( $renew_price ) ) {
			$renew_price = $this->total;
		}

		return $renew_price;
	}

	/**
	 * Get the affiliate ID for the order.
	 *
	 * If no affiliate ID is set, the default affiliate ID is used.
	 *
	 * @return int The affiliate ID.
	 */
	public function get_affiliate_id() {
		$affiliate_id = wds_get_order_meta( $this->ID, 'affiliate_id', true );

		if ( empty( $affiliate_id ) ) {
			$affiliate_id = $this->affiliate_id;
		}

		return intval( $affiliate_id );
	}
}
