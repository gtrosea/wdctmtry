<?php
/**
 * Coupon Table.
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
 * Coupon Class.
 */
class Coupon extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_coupon';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'          => 'integer',
		'title'       => 'string',
		'description' => 'content',
		'rebate'      => 'string',
		'is_private'  => 'integer',
		'max_usage'   => 'integer',
		'products'    => 'array',
		'users'       => 'array',
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
	//  * The coupon codes associated with the coupon.
	//  *
	//  * This property stores the coupon codes related to the current coupon.
	//  *
	//  * @var array
	//  */
	// protected $codes;

	// /**
	//  * Get the raw rebate information for the coupon.
	//  *
	//  * This method parses the rebate value, determining if it is a percentage-based
	//  * or a fixed amount. It returns an array with the type and value of the rebate.
	//  *
	//  * @return array An array containing the type ('percen' or 'fixed') and value of the rebate.
	//  */
	// public function get_raw_rebate() {
	//  $rebate_array = array();

	//  if ( strpos( $this->rebate, '%' ) ) {
	//      $rebate = str_replace( '%', '', $this->rebate );

	//      $rebate_array = array(
	//          'type'  => 'percen',
	//          'value' => intval( $rebate ),
	//      );
	//  } else {
	//      $rebate_array = array(
	//          'type'  => 'fixed',
	//          'value' => intval( $this->rebate ),
	//      );
	//  }

	//  return $rebate_array;
	// }

	// /**
	//  * Retrieve the coupon codes associated with the coupon.
	//  *
	//  * This method fetches the coupon codes linked to a particular coupon from the database.
	//  *
	//  * @return array The coupon codes associated with the coupon.
	//  */
	// public function codes() {
	//  if ( empty( $this->codes ) ) {
	//      $this->codes = Coupon_Code::query( 'WHERE coupon_id = %d', $this->ID )->get();
	//  }

	//  return $this->codes;
	// }
}
