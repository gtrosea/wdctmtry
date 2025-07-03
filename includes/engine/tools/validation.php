<?php

namespace WDS\Engine\Tools;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Validation Class.
 *
 * @since 2.0.0
 */
class Validation {

	/**
	 * Validation email.
	 *
	 * @param string $email The email address.
	 */
	public static function email( $email ) {
		$key = false;
		$url = false;

		$provider = wds_engine( 'email_validation' );
		if ( empty( $provider ) ) {
			return null;
		}

		if ( 'emaillistverify' == $provider ) {
			$key = wds_engine( 'api_emaillistverify' );
			$url = 'https://apps.emaillistverify.com/api/verifyEmail?secret=' . rawurlencode( $key ) . '&email=' . rawurlencode( $email );
		}

		if ( ! $url && ! $key ) {
			return null;
		}

		$response = wp_safe_remote_get(
			$url,
			array(
				'timeout'   => 10,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			wds_log( 'WordPress HTTP API Error: ' . $response->get_error_message() );
			return null;
		} else {
			return wp_remote_retrieve_body( $response );
		}
	}

	/**
	 * Validation whatsapp.
	 *
	 * @param string $phone The phone number.
	 */
	public static function whatsapp( $phone ) {
		$provider = wds_engine( 'whatsapp_validation' );
		if ( empty( $provider ) ) {
			return null;
		}

		if ( 'starsender' == $provider ) {
			return self::whatsapp_starsender( $phone );
		} elseif ( 'fonnte' == $provider ) {
			return self::whatsapp_fonnte( $phone );
		} elseif ( 'onesender' == $provider ) {
			return self::whatsapp_onesender( $phone );
		} elseif ( 'rapidapi' == $provider ) {
			return self::whatsapp_rapidapi( $phone );
		}

		return null;
	}

	/**
	 * Validation whatsapp Starsender.
	 *
	 * @since 2.2.2
	 * @param string $phone The phone number.
	 */
	public static function whatsapp_starsender( $phone ) {
		$url = 'https://api.starsender.online/api/check-number';

		$pesan = array(
			'number' => $phone,
		);

		$apikey = wds_engine( 'api_starsender' );

		$response = wp_safe_remote_post(
			$url,
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => $apikey,
				),
				'body'    => wp_json_encode( $pesan ),
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			wds_log( 'WordPress HTTP API Error: ' . $response->get_error_message() );
			return null;
		} else {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			// wds_log( $data, true );
			if ( isset( $data['success'], $data['data']['status'] ) && 1 == $data['success'] && 1 == $data['data']['status'] ) {
				return null;
			} else {
				return 'not_registered';
			}
		}
	}

	/**
	 * Validation whatsapp fonnte.
	 *
	 * @param string $phone The phone number.
	 */
	public static function whatsapp_fonnte( $phone ) {
		$url = 'https://api.fonnte.com/validate';

		$body = array(
			'target'      => $phone,
			'countryCode' => 62,
		);

		$headers = array( 'Authorization' => wds_engine( 'api_fonnte' ) );

		$response = wp_safe_remote_post(
			$url,
			array(
				'body'        => $body,
				'headers'     => $headers,
				'timeout'     => 10,
				'redirection' => 10,
				'httpversion' => '1.1',
			)
		);

		if ( is_wp_error( $response ) ) {
			wds_log( 'WordPress HTTP API Error: ' . $response->get_error_message() );
			return null;
		} else {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $data['status'] ) && true === $data['status'] && ! empty( $data['not_registered'] ) ) {
				return 'not_registered';
			} else {
				return null;
			}
		}
	}

	/**
	 * Validation whatsapp onesender.
	 *
	 * @param string $phone The phone number.
	 */
	public static function whatsapp_onesender( $phone ) {
		$api_key = wds_engine( 'api_onesender' );
		$url     = wds_engine( 'api_url_onesender' ) . '/api/v1/check-number?phone_number=' . $phone;

		$args = array(
			'method'      => 'GET',
			'headers'     => array( 'Authorization' => 'Bearer ' . $api_key ),
			'timeout'     => 0,
			'redirection' => 10,
			'httpversion' => '1.1',
			'blocking'    => true,
			'sslverify'   => true,
		);

		$response = wp_remote_request( $url, $args );
		if ( is_wp_error( $response ) ) {
			wds_log( 'WordPress HTTP API Error: ' . $response->get_error_message() );
			return null;
		} else {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			// wds_log( $data, true );
			if ( isset( $data['code'] ) && 200 === $data['code'] && isset( $data['success'] ) && true === $data['success'] && isset( $data['data']['has_account'] ) && true === $data['data']['has_account'] ) {
				return null;
			} else {
				return 'not_registered';
			}
		}
	}

	/**
	 * Validation whatsapp rapidapi.
	 *
	 * @param string $phone The phone number.
	 */
	public static function whatsapp_rapidapi( $phone ) {
		$url = 'https://whatsapp-data1.p.rapidapi.com/number/' . wds_phone_country_code( $phone );

		$headers = array(
			'x-rapidapi-host' => 'whatsapp-data1.p.rapidapi.com',
			'x-rapidapi-key'  => wds_engine( 'api_whatsapp-data1' ),
		);

		$response = wp_safe_remote_get(
			$url,
			array(
				'headers' => $headers,
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) ) {
			wds_log( 'WordPress HTTP API Error: ' . $response->get_error_message() );
			return null;
		} else {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $data['error'] ) && "Whatsapp number doesn't exist" == $data['error'] ) {
				return 'not_registered';
			} else {
				return null;
			}
		}
	}
}
