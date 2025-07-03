<?php
/**
 * WeddingSaas Midtrans Gateway.
 *
 * Provides methods for handling Midtrans payment gateway functionality.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Gateways
 */

namespace WDS\Gateway;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Midtrans Class.
 */
class Midtrans {

	/**
	 * Get the base URL for Midtrans API based on environment.
	 *
	 * @return string Midtrans API base URL.
	 */
	public static function base() {
		return wds_option( 'midtrans_sandbox' ) ? 'https://api.sandbox.midtrans.com/v1/invoices' : 'https://api.midtrans.com/v1/invoices';
	}

	/**
	 * Get the default headers for Midtrans API requests.
	 *
	 * @return array Default headers for Midtrans API requests.
	 */
	public static function default_header() {
		$key = wds_option( 'midtrans_sandbox' ) ? wds_option( 'midtrans_sand_server_key' ) : wds_option( 'midtrans_prod_server_key' );

		$header = array(
			'content-type'  => 'application/json',
			'Authorization' => 'Basic ' . base64_encode( $key . ':' ), // phpcs:ignore
		);

		return $header;
	}

	/**
	 * Handle the response from Midtrans API.
	 *
	 * @param array|WP_Error $response The API response or error object.
	 * @return array Decoded response data or an empty array on failure.
	 */
	public static function response_handler( $response ) {
		if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
			return array();
		}

		return json_decode( $response['body'], true );
	}

	/**
	 * Create an invoice with Midtrans.
	 *
	 * @param array $body The invoice data to send to Midtrans.
	 * @return string The payment link URL on success, or an error message on failure.
	 */
	public static function create_invoice( $body ) {
		$payload = wp_json_encode( $body );

		$args = array(
			'headers' => self::default_header(),
			'body'    => $payload,
		);

		$response = wp_remote_post( self::base(), $args );

		$response_final = self::response_handler( $response );

		if ( isset( $response_final['payment_link_url'] ) ) {
			return $response_final['payment_link_url'];
		} else {
			return $response_final['error_messages'][0];
		}
	}

	/**
	 * Retrieve an invoice from Midtrans.
	 *
	 * @param string $invoice_id The ID of the invoice to retrieve.
	 * @return array The decoded invoice data or an empty array on failure.
	 */
	public static function get_invoice( $invoice_id ) {
		$end_point = self::base() . '/' . $invoice_id;

		$args = array( 'headers' => self::default_header() );

		$response = wp_remote_get( $end_point, $args );

		return self::response_handler( $response );
	}
}
