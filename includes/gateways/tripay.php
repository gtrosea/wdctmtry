<?php
/**
 * WeddingSaas Tripay Gateway.
 *
 * Provides methods for handling Tripay payment gateway functionality.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Gateways
 */

namespace WDS\Gateway;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Tripay Class.
 */
class Tripay {

	/**
	 * Check if Tripay merchant credentials are available.
	 *
	 * @return bool True if merchant credentials are available, false otherwise.
	 */
	public static function check() {
		return ! empty( wds_option( 'tripay_merchant_code' ) ) && ! empty( wds_option( 'tripay_api_key' ) ) && ! empty( wds_option( 'tripay_private_key' ) );
	}

	/**
	 * Get the base URL for Tripay API based on environment.
	 *
	 * @return string Tripay API base URL.
	 */
	public static function base() {
		return wds_option( 'tripay_sandbox' ) ? 'https://tripay.co.id/api-sandbox/' : 'https://tripay.co.id/api/';
	}

	/**
	 * Retrieve payment channels from Tripay.
	 *
	 * @return array|bool List of payment channels, or false if not available.
	 */
	public static function channel() {
		$check = self::check();
		if ( ! $check ) {
			return __( 'Pengaturan gateway belum lengkap.', 'weddingsaas' );
		}

		$args = array(
			'headers' => array( 'Authorization' => 'Bearer ' . wds_option( 'tripay_api_key' ) ),
			'timeout' => 20,
		);

		$response = wp_remote_get( self::base() . 'merchant/payment-channel', $args );
		if ( is_wp_error( $response ) ) {
			return $response->get_error_message();
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! empty( $data['success'] ) && ! empty( $data['data'] ) ) {
			// set_transient( WDS_SLUG . '_tripay', $data['data'] );
			update_option( WDS_SLUG . '_tripay', $data['data'] );
			return 'success';
		}

		return $data['message'];
	}

	/**
	 * Register the REST API endpoint for handling Tripay webhooks.
	 */
	public static function register_rest_api() {
		register_rest_route(
			'weddingsaas-tripay/v1',
			'/webhook',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'endpoint' ),
				'permission_callback' => array( __CLASS__, 'check_authorize' ),
			)
		);
	}

	/**
	 * REST API Endpoint for processing payment status updates.
	 *
	 * Handles webhook requests from Tripay, verifies the payment data,
	 * and updates the invoice status accordingly based on the received data.
	 *
	 * @param \WP_REST_Request $request The REST API request instance.
	 * @return \WP_REST_Response The response object indicating success or error.
	 * @throws \Exception If data conditions are invalid or do not match the required format.
	 */
	public static function endpoint( \WP_REST_Request $request ) {
		try {
			$push = (object) json_decode( file_get_contents( 'php://input' ) );
			$data = self::get_payment( $push->reference );
			$data = $data['checkout_url'];

			$reff          = \WDS\Models\Invoice::query( 'WHERE reference = %s', $data )->first();
			$invoice_query = "WHERE ID = %s AND gateway LIKE 'tripay_%' AND status IN ('unpaid', 'checking_payment')";
			$invoice       = \WDS\Models\Invoice::query( $invoice_query, $reff->ID )->first();

			if ( ! $invoice->ID || $invoice->total > $push->total_amount ) {
				throw new \Exception( 'Invalid data or conditions.' );
			}

			if ( 'EXPIRED' == $push->status ) {
				wds_update_invoice_status( $invoice->ID, 'cancelled' );
			} elseif ( 'PAID' == $push->status ) {
				wds_update_invoice_status( $invoice->ID, 'completed' );
			}

			return new \WP_REST_Response(
				array(
					'success' => true,
					'invoice' => $invoice->ID,
				),
				200
			);
		} catch ( \Exception $e ) {
			return new \WP_REST_Response(
				array(
					'success' => false,
					'error'   => $e->getMessage(),
				),
				400
			);
		}
	}

	/**
	 * Check authorization of the incoming webhook request.
	 *
	 * @return bool
	 */
	public static function check_authorize() {
		$callback_signature = isset( $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';

		$payload   = file_get_contents( 'php://input' );
		$signature = hash_hmac( 'sha256', $payload, wds_option( 'tripay_private_key' ) );

		$event = $_SERVER['HTTP_X_CALLBACK_EVENT'];

		return $callback_signature == $signature && 'payment_status' == $event;
	}

	/**
	 * Add necessary arguments for Tripay API request.
	 *
	 * @param array $args Request arguments.
	 * @return array Modified request arguments with authorization header.
	 */
	public static function args( $args = array() ) {
		$args['headers']['Authorization'] = 'Bearer ' . wds_option( 'tripay_api_key' );

		return $args;
	}

	/**
	 * Handle the response from Tripay API.
	 *
	 * @param mixed $response API response.
	 * @return mixed Response data or error message.
	 */
	public static function response( $response ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$code = wp_remote_retrieve_response_code( $response );
		$data = json_decode( $body, true );

		if ( intval( $code ) !== 200 ) {
			return $data['message'];
		}

		return $data['data'];
	}

	/**
	 * Generate a signature for Tripay payment request.
	 *
	 * @param string $ref     Transaction reference.
	 * @param float  $amount  Transaction amount.
	 * @return string Generated HMAC signature.
	 */
	public static function signature( $ref, $amount ) {
		$private_key   = wds_option( 'tripay_private_key' );
		$merchant_code = wds_option( 'tripay_merchant_code' );
		$merchant_ref  = $ref;

		return hash_hmac( 'sha256', $merchant_code . $merchant_ref . $amount, $private_key );
	}

	/**
	 * Create a new Tripay payment request.
	 *
	 * @param array  $args         Payment arguments.
	 * @param string $callback_url Callback URL for payment status.
	 * @param string $return_url   Return URL after payment completion.
	 * @return string|array Checkout URL or error message.
	 */
	public static function request_payment( $args, $callback_url, $return_url ) {
		$default_args = array(
			'method'         => '',
			'merchant_ref'   => '',
			'amount'         => '',
			'customer_name'  => '',
			'customer_email' => '',
			'customer_phone' => '',
			'order_items'    => array(),
		);

		$body                 = wp_parse_args( $args, $default_args );
		$body['callback_url'] = $callback_url;
		$body['return_url']   = $return_url;
		$body['expired_time'] = strtotime( '+' . wds_option( 'invoice_due_date' ) . ' day' );
		$body['signature']    = self::signature( $body['merchant_ref'], floatval( $body['amount'] ) );

		$url            = self::base() . 'transaction/create';
		$response       = wp_remote_post( $url, self::args( array( 'body' => $body ) ) );
		$response_final = self::response( $response );

		return isset( $response_final['checkout_url'] ) ? $response_final['checkout_url'] : $response_final;
	}

	/**
	 * Retrieve payment details from Tripay by reference.
	 *
	 * @param string $reference Payment reference.
	 * @return mixed Payment data or error message.
	 */
	public static function get_payment( $reference ) {
		$parameter = array( 'reference' => sanitize_text_field( $reference ) );
		$url       = self::base() . 'transaction/detail?' . http_build_query( $parameter );
		$response  = wp_remote_get( $url, self::args() );

		return self::response( $response );
	}
}
