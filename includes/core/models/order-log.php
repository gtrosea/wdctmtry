<?php
/**
 * Order Log Table.
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
 * Order_Log Class.
 */
class Order_Log extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_order_log';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'log_id'     => 'integer',
		'order_id'   => 'integer',
		'created_at' => 'string',
		'note'       => 'content',
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
