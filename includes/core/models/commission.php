<?php
/**
 * Commission Table.
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
 * Commission Class.
 */
class Commission extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_commission';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'         => 'integer',
		'user_id'    => 'integer',
		'invoice_id' => 'integer',
		'order_id'   => 'integer',
		'product_id' => 'integer',
		'amount'     => 'price',
		'status'     => 'string',
		'note'       => 'content',
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
}
