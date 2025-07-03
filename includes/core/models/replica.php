<?php
/**
 * Replica Table.
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
 * Replica Class.
 */
class Replica extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = 'wdr';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'         => 'integer',
		'user_id'    => 'integer',
		'domain'     => 'string',
		'subdomain'  => 'string',
		'status'     => 'string',
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
