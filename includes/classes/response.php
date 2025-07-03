<?php
/**
 * WeddingSaas Response.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

namespace WDS;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Response Class.
 */
class Response extends \WP_HTTP_Response {

	/**
	 * Response json from header.
	 */
	public function json() {
		status_header( $this->status );

		foreach ( $this->headers as $key => $value ) {
			$value = preg_replace( '/\s+/', ' ', $value );
			header( sprintf( '%s: %s', $key, $value ) );
		}

		echo wp_json_encode( $this->data );
		exit;
	}
}
