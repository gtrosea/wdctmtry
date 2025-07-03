<?php
/**
 * Product Meta Table.
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
 * Product_Meta Class.
 */
class Product_Meta extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_productmeta';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'meta_id'    => 'integer',
		'product_id' => 'integer',
		'meta_key'   => 'string',
		'meta_value' => 'string',
	);
}
