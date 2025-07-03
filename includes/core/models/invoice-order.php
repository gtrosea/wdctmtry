<?php
/**
 * Invoice Order Table.
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
 * Invoice_Order Class.
 */
class Invoice_Order extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_invoice_order';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'invoice_order_id' => 'integer',
		'invoice_id'       => 'integer',
		'order_id'         => 'integer',
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
