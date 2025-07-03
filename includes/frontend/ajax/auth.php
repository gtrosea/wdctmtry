<?php

namespace WDS\Frontend\Ajax;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Auth Class.
 */
class Auth {

	/**
	 * Login action.
	 */
	public static function login() {
		$post = $_POST;

		$user_login    = wds_sanitize_data_field( $post, 'email' );
		$user_password = wds_sanitize_data_field( $post, 'password' );
		$rememberme    = wds_sanitize_data_field( $post, 'rememberme' );

		$credentials = array(
			'user_login'    => wp_unslash( $user_login ),
			'user_password' => $user_password,
			'rememberme'    => $rememberme,
		);

		$user = wp_signon( $credentials );

		if ( is_wp_error( $user ) ) {
			wp_send_json_error( $user->get_error_message() );
		}

		wp_set_auth_cookie( $user->ID, true );
		wp_send_json_success( wds_lang( 'auth_login_success' ) );
	}

	/**
	 * Lost password action.
	 */
	public static function lost_password() {
		$post = $_POST;

		$reset = retrieve_password( wds_sanitize_data_field( $post, 'email' ) );
		if ( is_wp_error( $reset ) ) {
			wp_send_json_error( $reset->get_error_message() );
		}

		wp_send_json_success( wds_lang( 'auth_lp_success' ) );
	}

	/**
	 * Reset password action.
	 */
	public static function reset_password() {
		$post = $_POST;

		$user_login    = wds_sanitize_data_field( $post, 'rp_login' );
		$user_password = wds_sanitize_data_field( $post, 'new_password' );
		$reset_key     = wds_sanitize_data_field( $post, 'rp_key' );

		$user = check_password_reset_key( $reset_key, wp_unslash( $user_login ) );

		if ( is_wp_error( $user ) ) {
			wp_send_json_error( $user->get_error_message() );
		}

		wp_set_password( $user_password, $user->ID );
		wp_send_json_success( wds_lang( 'auth_rp_success' ) );
	}
}
