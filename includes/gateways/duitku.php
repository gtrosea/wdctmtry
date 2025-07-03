<?php
/**
 * WeddingSaas Duitku Gateway.
 *
 * Provides methods for handling Duitku payment gateway functionality.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Gateways
 */

namespace WDS\Gateway;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Duitku Class.
 */
class Duitku {

	const QUERY_VAR  = 'duitku_return';
	const PASSPHRASE = 'duitku_listener_passphrase';

	/**
	 * Check if Duitku merchant credentials are available.
	 *
	 * @return bool True if merchant credentials are available, false otherwise.
	 */
	public static function check() {
		return ! empty( wds_option( 'duitku_merchant_code' ) ) && ! empty( wds_option( 'duitku_api_key' ) );
	}

	/**
	 * Get the base URL for Duitku API based on environment.
	 *
	 * @return string Duitku API base URL.
	 */
	public static function base() {
		return wds_option( 'duitku_sandbox' ) ? 'https://sandbox.duitku.com/webapi/' : 'https://passport.duitku.com/webapi/';
	}

	/**
	 * Retrieve payment channels from Duitku.
	 *
	 * @return array|bool List of payment channels, or false if not available.
	 */
	public static function channel() {
		$check = self::check();
		if ( ! $check ) {
			return __( 'Pengaturan gateway belum lengkap.', 'weddingsaas' );
		}

		$merchant  = wds_option( 'duitku_merchant_code' );
		$api_key   = wds_option( 'duitku_api_key' );
		$datetime  = gmdate( 'Y-m-d H:i:s' );
		$amount    = 10000;
		$signature = hash( 'sha256', $merchant . $amount . $datetime . $api_key );

		$params = array(
			'merchantcode' => $merchant,
			'amount'       => $amount,
			'datetime'     => $datetime,
			'signature'    => $signature,
		);

		$response = wp_remote_post(
			self::base() . 'api/merchant/paymentmethod/getpaymentmethod',
			array(
				'body'      => wp_json_encode( $params ),
				'headers'   => array( 'Content-Type' => 'application/json' ),
				'timeout'   => 20,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response->get_error_message();
		}

		$http_code = wp_remote_retrieve_response_code( $response );
		$body      = wp_remote_retrieve_body( $response );
		$data      = json_decode( $body, true );

		if ( 200 !== $http_code || empty( $data['responseMessage'] ) ) {
			return 'Server Error ' . $http_code . ' Unknown error.';
		}

		foreach ( $data['paymentFee'] as $key => $fee ) {
			if ( in_array( $fee['paymentMethod'], array( 'OV', 'DN', 'LA', 'SA', 'DA', 'SL', 'OL', 'JP', 'IQ', 'GQ' ) ) ) {
				unset( $data['paymentFee'][ $key ] );
			}
		}

		// set_transient( WDS_SLUG . '_duitku', $data['paymentFee'] );
		update_option( WDS_SLUG . '_duitku', $data['paymentFee'] );

		return 'success';
	}

	/**
	 * Listen for Duitku return requests.
	 */
	public static function return_listener() {
		if ( ! isset( $_GET[ self::QUERY_VAR ], $_GET['form_id'] ) ) {
			return;
		}

		$passphrase = get_option( self::PASSPHRASE, false );
		if ( ! $passphrase || $_GET[ self::QUERY_VAR ] !== $passphrase ) {
			return;
		}

		$order_id  = wds_sanitize_data_field( $_REQUEST, 'merchantOrderId', null );
		$status    = wds_sanitize_data_field( $_REQUEST, 'resultCode', null );
		$reference = wds_sanitize_data_field( $_REQUEST, 'reference', null );

		if ( '00' == $status && self::validate_transaction( $order_id, $reference ) ) {
			wds_update_invoice_status( $order_id, 'completed' );
			return new \WP_REST_Response(
				array(
					'success' => true,
					'invoice' => $order_id,
				),
				200
			);
		} else {
			return new \WP_REST_Response(
				array(
					'success' => false,
					'error'   => 'Duitku log failed.',
				),
				400
			);
		}
	}

	/**
	 * Validate a transaction with Duitku.
	 *
	 * @param string $order_id Order ID to validate.
	 * @param string $reference Reference ID from Duitku.
	 * @return bool True if transaction is valid, false otherwise.
	 */
	public static function validate_transaction( $order_id, $reference ) {
		$params = array(
			'merchantCode'    => wds_option( 'duitku_merchant_code' ),
			'merchantOrderId' => $order_id,
			'signature'       => md5( wds_option( 'duitku_merchant_code' ) . $order_id . wds_option( 'duitku_api_key' ) ),
			'reference'       => $reference,
		);

		$response = wp_remote_post(
			self::base() . 'api/merchant/transactionStatus',
			array(
				'method'    => 'POST',
				'body'      => wp_json_encode( $params ),
				'headers'   => array( 'Content-Type' => 'application/json' ),
				'timeout'   => 90,
				'sslverify' => false,
			)
		);

		$response_body = wp_remote_retrieve_body( $response );

		if ( '200' == wp_remote_retrieve_response_code( $response ) ) {
			$resp = json_decode( $response_body );
			if ( '00' == $resp->statusCode ) { // phpcs:ignore
				return true;
			}
		}

		return false;
	}

	/**
	 * Generate Duitku listener URL.
	 *
	 * @param int $form_id Form ID for the listener URL.
	 * @return string Listener URL.
	 */
	public static function get_listener_url( $form_id ) {
		$passphrase = get_option( self::PASSPHRASE, false );
		if ( ! $passphrase ) {
			$passphrase = md5( site_url() . time() );
			update_option( self::PASSPHRASE, $passphrase );
		}

		return add_query_arg(
			array(
				self::QUERY_VAR => $passphrase,
				'form_id'       => $form_id,
			),
			site_url( '/' )
		);
	}

	/**
	 * Request payment URL from Duitku.
	 *
	 * @param array $data Payment data for the request.
	 * @return string|bool Payment URL or error message if failed.
	 */
	public static function request_payment( $data ) {
		$params = array(
			'merchantCode'     => wds_option( 'duitku_merchant_code' ),
			'paymentAmount'    => intval( $data['amount'] ),
			'paymentMethod'    => $data['method'],
			'merchantOrderId'  => $data['orderId'],
			'productDetails'   => __( 'Order', 'wds-notrans' ) . ' ' . $data['product'],
			'additionalParam'  => '',
			'merchantUserInfo' => $data['userInfo'],
			'customerVaName '  => $data['userInfo'],
			'email'            => $data['userEmail'],
			'phoneNumber'      => $data['phoneNumber'],
			'itemDetails'      => $data['itemDetails'],
			'signature'        => md5( wds_option( 'duitku_merchant_code' ) . $data['orderId'] . intval( $data['amount'] ) . wds_option( 'duitku_api_key' ) ),
			'expiryPeriod'     => 1440,
			'returnUrl'        => wds_url( 'thanks', wds_encrypt_decrypt( $data['orderId'] ) ),
			'callbackUrl'      => esc_url_raw( self::get_listener_url( $data['orderId'] ) ),
		);

		$response = wp_remote_post(
			self::base() . 'api/merchant/v2/inquiry',
			array(
				'method'    => 'POST',
				'body'      => wp_json_encode( $params ),
				'headers'   => array( 'Content-Type' => 'application/json' ),
				'timeout'   => 90,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) || empty( wp_remote_retrieve_body( $response ) ) ) {
			wds_log( 'Error connecting to Duitku.' );
			return false;
		}

		$response_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			wds_log( $response_data->Message ); // phpcs:ignore
			return false;
		}

		return $response_data->paymentUrl; // phpcs:ignore
	}
}
