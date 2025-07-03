<?php

namespace WDS\Engine\Tools;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Contact Class.
 *
 * @since 2.0.0
 */
class Contact {

	/**
	 * Fetch and save mailketing list.
	 *
	 * @param string $api_token The mailketing token.
	 */
	public static function mailketing_fetch_list( $api_token ) {
		$url = 'https://api.mailketing.co.id/api/v1/viewlist';

		$response = wp_remote_post(
			$url,
			array(
				'body' => array(
					'api_token' => $api_token,
				),
			)
		);

		if ( ! is_wp_error( $response ) && 200 == $response['response']['code'] ) {
			$body = wp_remote_retrieve_body( $response );

			$decoded_response = json_decode( $body, true );
			if ( isset( $decoded_response['status'] ) && 'success' === $decoded_response['status'] ) {
				update_option( '_mailketing_list', $decoded_response['lists'] );
			}
		}
	}

	/**
	 * Get all mailketing list.
	 */
	public static function mailketing_list() {
		$data     = get_option( '_mailketing_list' );
		$list     = array();
		$list[''] = __( '-- Pilih list ID --', 'weddingsaas' );
		if ( wds_check_array( $data, true ) ) {
			foreach ( $data as $item ) {
				$list[ $item['list_id'] ] = $item['list_name'];
			}
		}

		return $list;
	}

	/**
	 * Add subscriber to mailketing list.
	 *
	 * @param string $name The user name.
	 * @param string $email The user email.
	 * @param string $phone The user phone.
	 * @param int    $list_id The list ID.
	 */
	public static function mailketing_add_subscriber( $name, $email, $phone, $list_id ) {
		$api_token = wds_engine( 'mailketing_api' );

		$url = 'https://api.mailketing.co.id/api/v1/addsubtolist';

		$params = array(
			'first_name' => $name,
			'email'      => $email,
			'mobile'     => $phone,
			'api_token'  => $api_token,
			'list_id'    => $list_id,
		);

		$response = wp_remote_post(
			$url,
			array(
				'body' => $params,
			)
		);
	}

	/**
	 * Fetch and save starsender group.
	 *
	 * @param string $api_key The starsender api key.
	 */
	public static function starsender_fetch_group( $api_key ) {
		$url = 'https://api.starsender.online/api/groups';

		$response = wp_remote_get(
			$url,
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => $api_key,
				),
			)
		);

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) == 200 ) {
			$body = wp_remote_retrieve_body( $response );

			$decoded_response = json_decode( $body, true );
			if ( isset( $decoded_response['success'] ) && true === $decoded_response['success'] ) {
				update_option( '_starsender_group', $decoded_response['data']['groups'] );
			}
		}
	}

	/**
	 * Get all starsender group.
	 */
	public static function starsender_group() {
		$data     = get_option( '_starsender_group' );
		$list     = array();
		$list[''] = __( '-- Pilih group --', 'weddingsaas' );
		if ( wds_check_array( $data, true ) ) {
			foreach ( $data as $item ) {
				$list[ $item['id'] ] = $item['name'];
			}
		}

		return $list;
	}

	/**
	 * Add contact to starsender group.
	 *
	 * @param string $name The user name.
	 * @param string $phone The user phone.
	 * @param int    $group_id The group ID.
	 */
	public static function starsender_add_group( $name, $phone, $group_id ) {
		$api_key = wds_engine( 'starsender_api' );

		$data = array(
			'name'     => "$name",
			'number'   => "$phone",
			'group_id' => intval( $group_id ),
		);

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => $api_key,
		);

		$response = wp_remote_post(
			'https://api.starsender.online/api/contacts',
			array(
				'body'    => wp_json_encode( $data ),
				'headers' => $headers,
			)
		);
		// $data     = json_decode( wp_remote_retrieve_body( $response ), true );
		// wds_log( $data, true );
	}

	/**
	 * Delete contact starsender group.
	 *
	 * @param string $phone The user phone.
	 * @param int    $group_id The group ID.
	 */
	public static function starsender_delete_group( $phone, $group_id ) {
		$api_key = wds_engine( 'starsender_api' );

		$data = array(
			'number'   => $phone,
			'group_id' => $group_id,
		);

		$args = array(
			'body'    => wp_json_encode( $data ),
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => $api_key,
			),
		);

		$response = wp_remote_post( 'https://api.starsender.online/api/groups/contacts/delete', $args );
	}

	/**
	 * Fetch and save sendy.
	 *
	 * @param string $sendy_url The sendy url.
	 * @param string $sendy_api The sendy api.
	 * @param string $sendy_brand The sendy brand.
	 */
	public static function sendy_fetch_list( $sendy_url, $sendy_api, $sendy_brand ) {
		$url = $sendy_url . '/api/lists/get-lists.php';

		$params = array(
			'api_key'        => $sendy_api,
			'brand_id'       => $sendy_brand,
			'include_hidden' => 'no',
		);

		$response = wp_remote_post(
			$url,
			array(
				'body' => $params,
			)
		);

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) == 200 ) {
			$body = wp_remote_retrieve_body( $response );

			$decoded_response = json_decode( $body, true );
			if ( null !== $decoded_response ) {
				update_option( '_sendy_list', $decoded_response );
			}
		}
	}

	/**
	 * Get all sendy list.
	 */
	public static function sendy_list() {
		$data     = get_option( '_sendy_list' );
		$list     = array();
		$list[''] = __( '-- Pilih list ID --', 'weddingsaas' );
		if ( wds_check_array( $data, true ) ) {
			foreach ( $data as $item ) {
				$list[ $item['id'] ] = $item['name'];
			}
		}

		return $list;
	}

	/**
	 * Add subscriber to sendy list.
	 *
	 * @param string $name The user name.
	 * @param string $email The user email.
	 * @param int    $list_id The list ID.
	 */
	public static function sendy_add_subscriber( $name, $email, $list_id ) {
		$sendy_url   = wds_engine( 'sendy_url' );
		$sendy_api   = wds_engine( 'sendy_api' );
		$sendy_brand = wds_engine( 'sendy_brand_id' );

		$url = $sendy_url . '/subscribe';

		$params = array(
			'name'    => $name,
			'email'   => $email,
			'list'    => $list_id,
			'api_key' => $sendy_api,
			'boolean' => 'true',
		);

		$response = wp_remote_post(
			$url,
			array(
				'body' => $params,
			)
		);
	}
}
