<?php
/**
 * Affiliate Table.
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
 * Affiliate Class.
 */
class Affiliate extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_affiliate';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'           => 'integer',
		'affiliate_id' => 'intger',
		'visited_at'   => 'string',
		'product_id'   => 'integer',
		'uri'          => 'string',
		'referer'      => 'string',
		'device'       => 'string',
		'ip'           => 'string',
		'browser'      => 'string',
		'platform'     => 'string',
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
