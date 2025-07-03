<?php
/**
 * WeddingSaas Gateway.
 *
 * Handles the gateway for the WeddingSaas plugin.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Gateways
 */

namespace WDS;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Gateway Class.
 */
class Gateway {

	/**
	 * Constructor.
	 *
	 * Initializes the gateway class by adding actions and filters,
	 */
	public function __construct() {
		add_action( 'init', array( '\WDS\Gateway\Duitku', 'return_listener' ) );
		add_action( 'rest_api_init', array( '\WDS\Gateway\Tripay', 'register_rest_api' ) );
		if ( isset( $_REQUEST['wds_xendit'] ) ) {
			add_action( 'init', array( '\WDS\Gateway\Xendit', 'check_response' ) );
		}
	}
}

new Gateway();
