<?php
/**
 * Checkout Meta Table.
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
 * Checkout_Meta Class.
 */
class Checkout_Meta extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_checkoutmeta';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'meta_id'     => 'integer',
		'checkout_id' => 'integer',
		'meta_key'    => 'string',
		'meta_value'  => 'string',
	);
}
