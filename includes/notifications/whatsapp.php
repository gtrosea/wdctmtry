<?php
/**
 * WeddingSaas WhatsApp Notifications.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Notifications
 */

namespace WDS\Notifications;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WhatsApp Class.
 *
 * This class provides methods to send WhatsApp messages through various gateways.
 */
class WhatsApp {

	/**
	 * Send a WhatsApp message to a specified phone number using the configured gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 */
	public static function send( $phone, $message ) {
		if ( empty( $phone ) || empty( $message ) ) {
			return;
		}

		$gateway = wds_option( 'whatsapp_gateway' );
		switch ( $gateway ) {
			case 'starsender':
				self::starsender( $phone, $message );
				break;

			case 'starsenderv3':
				self::starsenderv3( $phone, $message );
				break;

			case 'onesender':
				self::onesender( $phone, $message );
				break;

			case 'responic':
				self::responic( $phone, $message );
				break;

			case 'fonnte':
				self::fonnte( $phone, $message );
				break;

			case 'dripsender':
				self::dripsender( $phone, $message );
				break;

			case 'autowa':
				self::autowa( $phone, $message );
				break;
		}
	}

	/**
	 * Send a message using the StarSender gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 * @return bool|void False if API key is missing, otherwise sends the message.
	 */
	public static function starsender( $phone, $message ) {
		$apikey = wds_option( 'api_starsender' );
		if ( empty( $apikey ) ) {
			return false;
		}

		$url = 'https://starsender.online/api/sendText?message=' . rawurlencode( $message ) . '&tujuan=' . rawurlencode( $phone . '@s.whatsapp.net' );

		$args = array(
			'headers' => array(
				'apikey' => $apikey,
			),
			'timeout' => 30,
		);

		$response = wp_remote_post( $url, $args );
	}

	/**
	 * Send a message using the StarSender v3 gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 * @return bool|void False if API key is missing, otherwise sends the message.
	 */
	public static function starsenderv3( $phone, $message ) {
		$apikey = wds_option( 'api_starsender' );
		if ( empty( $apikey ) ) {
			return false;
		}

		$pesan = array(
			'messageType' => 'text',
			'to'          => $phone,
			'body'        => $message,
		);

		$response = wp_remote_post(
			'https://api.starsender.online/api/send',
			array(
				'body'    => wp_json_encode( $pesan ),
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => $apikey,
				),
			)
		);
	}

	/**
	 * Send a message using the OneSender gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 * @return bool|void False if API key is missing, otherwise sends the message.
	 */
	public static function onesender( $phone, $message ) {
		// 1. Persiapan Data
		$api_key = wds_option( 'api_onesender' );
		$api_url = wds_option( 'api_url_onesender' );

		// Format nomor tujuan
		$phone = preg_replace( '/[^0-9]/', '', $phone );
		if ( strlen( $phone ) >= 8 && substr( $phone, 0, 2 ) === '08' ) {
			$phone = '628' . substr( $phone, 2 );
		}

		// 2. Format URL API (antisipasi salah ketik)
		$url_parts = wp_parse_url( $api_url );

		$url_parts = wp_parse_args(
			$url_parts,
			array(
				'scheme' => $url_parts['scheme'] ?? 'http',
				'host'   => $url_parts['host'] ?? 'localhost',
				'port'   => false,
				'path'   => $url_parts['path'] ?? '',
			)
		);

		$api_url = sprintf(
			'%s://%s',
			$url_parts['scheme'],
			$url_parts['host']
		);

		if ( ! empty( $url_parts['port'] ) ) {
			$api_url = sprintf(
				'%s://%s:%s',
				$url_parts['scheme'] ?? 'http',
				$url_parts['host'] ?? 'localhost',
				$url_parts['port'] ?? '3001'
			);
		}

		// Tambahkan path dengan aman
		$api_url = trailingslashit( $api_url ) . 'api/v1/messages';

		// 3. Data yang akan dikirim
		$data = array(
			'recipient_type' => 'individual',
			'to'             => $phone,
			'type'           => 'text',
			'text'           => array(
				'body' => $message,
			),
		);

		// 4. Kirim request
		$response = wp_remote_post(
			$api_url,
			array(
				'headers'   => array(
					'Authorization' => 'Bearer ' . $api_key,
					'Content-Type'  => 'application/json',
				),
				'body'      => wp_json_encode( $data ),
				'sslverify' => false,
			)
		);

		return $response;
	}

	/**
	 * Send a message using the Responic gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 * @return bool|void False if API key is missing, otherwise sends the message.
	 */
	public static function responic( $phone, $message ) {
		$apikey = wds_option( 'api_responic' );
		if ( empty( $apikey ) ) {
			return false;
		}

		$response = wp_remote_post(
			'https://panel.responic.com/api/message',
			array(
				'body'    => wp_json_encode(
					array(
						'receiver' => $phone,
						'message'  => array(
							'text' => $message,
						),
					)
				),
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $apikey,
				),
			)
		);
	}

	/**
	 * Send a message using the Fonnte gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 * @return bool|void False if API key is missing, otherwise sends the message.
	 */
	public static function fonnte( $phone, $message ) {
		$apikey = wds_option( 'fonnte_token' );
		if ( empty( $apikey ) ) {
			return false;
		}

		$response = wp_remote_post(
			'https://api.fonnte.com/send',
			array(
				'body'    => array(
					'target'      => $phone,
					'message'     => $message,
					'countryCode' => '62',
				),
				'headers' => array(
					'Authorization' => $apikey,
				),
			)
		);
	}

	/**
	 * Send a message using the Dripsender gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 * @return bool|void False if API key is missing, otherwise sends the message.
	 */
	public static function dripsender( $phone, $message ) {
		$apikey = wds_option( 'api_dripsender' );
		if ( empty( $apikey ) ) {
			return false;
		}

		$response = wp_remote_post(
			'https://api.dripsender.id/send',
			array(
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body'    => wp_json_encode(
					array(
						'api_key' => $apikey,
						'phone'   => $phone,
						'text'    => $message,
					)
				),
			)
		);
	}

	/**
	 * Send a message using the Autowa gateway.
	 *
	 * @param string $phone   The recipient's phone number.
	 * @param string $message The message to send.
	 * @return bool|void False if API key is missing, otherwise sends the message.
	 */
	public static function autowa( $phone, $message ) {
		$apikey = wds_option( 'api_autowa' );
		$client = wds_option( 'api_clientid_autowa' );
		if ( empty( $apikey ) || empty( $client ) ) {
			return false;
		}

		$url    = 'https://app.autowa.site/api/user/v2/send_message_url';
		$mobile = $phone;
		$text   = $message;

		$query_params = http_build_query(
			array(
				'client_id' => $client,
				'mobile'    => $mobile,
				'text'      => $text,
				'token'     => $apikey,
			)
		);

		$api_url = $url . '?' . $query_params;

		$response = file_get_contents( $api_url ); // phpcs:ignore
	}
}
