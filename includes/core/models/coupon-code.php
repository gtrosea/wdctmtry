<?php
/**
 * Coupon Code Table.
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
 * Coupon_Code Class.
 */
class Coupon_Code extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_coupon_code';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'code_id'   => 'integer',
		'coupon_id' => 'integer',
		'user_id'   => 'integer',
		'code'      => 'string',
	);

	/**
	 * The attributes of the model.
	 *
	 * This array can be used to store additional attributes or settings related to the model.
	 *
	 * @var array
	 */
	protected $attributes = array();
}
