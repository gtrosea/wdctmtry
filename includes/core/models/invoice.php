<?php
/**
 * Invoice Table.
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
 * Invoice Class.
 */
class Invoice extends Database {

	/**
	 * The name of the table in the database.
	 *
	 * @var string
	 */
	protected $table = WDS_MODEL . '_invoice';

	/**
	 * The columns of the table and their types.
	 *
	 * @var array
	 */
	protected $columns = array(
		'ID'          => 'integer',
		'number'      => 'string',
		'user_id'     => 'integer',
		'summary'     => 'array',
		'total'       => 'price',
		'gateway'     => 'string',
		'reference'   => 'array',
		'type'        => 'string',
		'status'      => 'string',
		'due_date_at' => 'string',
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

	/**
	 * The orders associated with the invoice.
	 *
	 * This property holds the orders linked to a specific invoice.
	 *
	 * @var array
	 */
	protected $orders = array();

	/**
	 * Retrieve the orders associated with the invoice.
	 *
	 * This method fetches the orders linked to a particular invoice by joining
	 * the order and invoice_order tables.
	 *
	 * @return array The orders associated with the invoice.
	 */
	public function orders() {
		global $wpdb;

		if ( empty( $this->orders ) ) {
			$this->orders = Order::join( 'left', WDS_MODEL . '_invoice_order', array( 'ID', 'order_id', '=' ) )
			->select(
				array( $wpdb->prefix . WDS_MODEL . '_order.*' ),
				array( $wpdb->prefix . WDS_MODEL . '_invoice_order.invoice_id' )
			)->query( 'WHERE invoice_id = %d', $this->ID )->get();
		}

		return $this->orders;
	}
}
