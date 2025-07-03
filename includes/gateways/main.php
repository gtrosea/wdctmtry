<?php
/**
 * WeddingSaas Main Gateway.
 *
 * Handles the main gateway for transactions.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Gateway
 */

namespace WDS\Gateway;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class Main extends \WDS\Abstracts\Payment_Gateway {

	/**
	 * Constructor to initialize register gateway properties.
	 *
	 * @param string $_id Gateway unique identifier.
	 */
	public function __construct( $_id = false ) {
		if ( ! $_id || ! empty( $_id ) ) {
			$this->id = $_id;

			if ( 'banktransfer' == $_id || 'qris' == $_id ) {
				$this->unique = true;
			}
		}
	}
}
