<?php
/**
 * WeddingSaas Xendit Gateway.
 *
 * Provides methods for handling Xendit payment gateway functionality.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Gateways
 */

namespace WDS\Gateway;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Xendit Class.
 */
class Xendit {

	/**
	 * Default timeout for API requests.
	 */
	const DEFAULT_TIME_OUT = 70;

	/**
	 * Get the base URL for Xendit API based on environment.
	 *
	 * @return string Xendit API base URL.
	 */
	public static function base() {
		return 'https://api.xendit.co';
	}

	/**
	 * Checks and handles Xendit response callback for invoice updates.
	 *
	 * @return void
	 */
	public static function check_response() {
		$wds_xendit = wds_sanitize_data_field( $_REQUEST, 'wds_xendit', false );
		if ( ! $wds_xendit || 'xendit_invoice_callback' !== $wds_xendit ) {
			header( 'HTTP/1.1 404 Not Found' );
			echo 'Callback URL not found.';
			exit;
		}

		$request_method = wds_sanitize_data_field( $_SERVER, 'REQUEST_METHOD', false );
		if ( 'POST' !== $request_method ) {
			header( 'HTTP/1.1 404 Not Found' );
			echo 'HTTP method not supported.';
			exit;
		}

		$data     = file_get_contents( 'php://input' );
		$response = json_decode( $data );

		if ( ! isset( $response->id ) || ! isset( $response->external_id ) || ! isset( $response->status ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			echo 'Callback URL not found.';
			exit;
		}

		$get_invoice = self::get_invoice( $response->id );

		if ( ! empty( $get_invoice ) ) {
			$order_id = str_replace( 'INV_', '', $get_invoice['external_id'] );

			if ( 'PAID' === $get_invoice['status'] || 'SETTLED' === $get_invoice['status'] ) {
				wds_update_invoice_status( $order_id, 'completed' );
			} elseif ( 'EXPIRED' === $get_invoice['status'] ) {
				wds_update_invoice_status( $order_id, 'cancelled' );
			}
		}

		echo 'Success';
		die;
	}

	/**
	 * Generates headers for Xendit API requests.
	 *
	 * @return array An associative array of request headers.
	 */
	public static function default_header() {
		$api_key = wds_option( 'xendit_dev' ) ? wds_option( 'xendit_api_key_dev' ) : wds_option( 'xendit_api_key' );

		$header = array(
			'content-type'     => 'application/json',
			'Authorization'    => 'Basic ' . base64_encode( $api_key . ':' ), // phpcs:ignore
			'x-plugin-name'    => WDS_NAME,
			'x-plugin-version' => WDS_VERSION,
		);

		return $header;
	}

	/**
	 * Handles and parses the response from the Xendit API.
	 *
	 * @param array|WP_Error $response Response from the wp_remote_* function.
	 * @return array Parsed JSON response as an associative array.
	 */
	public static function response_handler( $response ) {
		if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
			return array();
		}

		return json_decode( $response['body'], true );
	}

	/**
	 * Creates an invoice via Xendit API.
	 *
	 * @param array $body Associative array containing invoice data.
	 * @return string URL of the invoice if created successfully, otherwise the error message.
	 */
	public static function create_invoice( $body ) {
		$payload = wp_json_encode( $body );

		$args = array(
			'headers' => self::default_header(),
			'body'    => $payload,
			'timeout' => self::DEFAULT_TIME_OUT,
		);

		$end_point = self::base() . '/v2/invoices';
		$response  = wp_remote_post( $end_point, $args );

		$response_final = self::response_handler( $response );

		if ( isset( $response_final['invoice_url'] ) ) {
			return $response_final['invoice_url'];
		} else {
			return $response_final['message'];
		}
	}

	/**
	 * Retrieves an invoice by its ID from the Xendit API.
	 *
	 * @param string $invoice_id ID of the invoice to retrieve.
	 * @return array Associative array containing invoice data.
	 */
	public static function get_invoice( $invoice_id ) {
		$end_point = self::base() . '/v2/invoices/' . $invoice_id;

		$args = array(
			'headers' => self::default_header(),
			'timeout' => self::DEFAULT_TIME_OUT,
		);

		$response = wp_remote_get( $end_point, $args );

		return self::response_handler( $response );
	}
}
