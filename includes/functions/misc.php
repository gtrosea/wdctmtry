<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Encrypt and decrypt.
 *
 * @param string  $string String that will be processed.
 * @param string  $action Action between encrypt or decrypt.
 * @param integer $length The length string.
 */
function wds_encrypt_decrypt( $string, $action = 'encrypt', $length = 16 ) {
	$output         = false;
	$secret_key     = AUTH_KEY;
	$secret_iv      = AUTH_SALT;
	$encrypt_method = 'AES-256-CBC';

	$key = hash( 'sha256', $secret_key );

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr( hash( 'sha256', $secret_iv ), 0, $length );

	if ( 'encrypt' == $action ) {
		$output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
		$output = base64_encode( $output ); // phpcs:ignore
	} elseif ( 'decrypt' == $action ) {
		$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv ); // phpcs:ignore
	}

	return $output;
}

/**
 * Get global data.
 *
 * @param string $key The data key.
 */
function wds_data( $key = false ) {
	global $wds_data;

	if ( 'all' == $key ) {
		return $wds_data;
	}

	if ( isset( $wds_data[ $key ] ) ) {
		return $wds_data[ $key ];
	}

	return false;
}

/**
 * Checks if the current page is a WDS page.
 *
 * @global WP $wp WordPress query object.
 * @return string|false Returns the sanitized '__wds_page' query var if set, false otherwise.
 */
function wds_is_page() {
	global $wp;

	return wds_sanitize_data_field( $wp->query_vars, '__wds_page', false );
}

/**
 * Retrieves WDS query variables.
 *
 * @global WP $wp WordPress query object.
 * @param string|false $key Optional. The key of the specific query variable to retrieve. Default is false.
 * @return array|string|false Returns all query variables if no key is provided,
 *                            the sanitized query variable if the key is found, or false if not.
 */
function wds_vars( $key = false ) {
	global $wp;

	if ( ! $key ) {
		return $wp->query_vars;
	}

	return wds_sanitize_data_field( $wp->query_vars, $key, false );
}

/**
 * Retrieves the current WDS query variables by key.
 *
 * @param string $key The key of the specific query variable to retrieve.
 * @return string|false The current data vars, false otherwise.
 */
function wds_get_vars( $key = false ) {
	if ( ! $key ) {
		return false;
	}

	$vars = wds_vars();

	if ( 'order_id' == $key ) {
		if ( ! isset( $vars['__order'] ) ) {
			wds_log( 'Data vars not found. Key = order_id' );
		}
		$encoded  = wds_sanitize_text_field( $vars['__order'] );
		$order_id = wds_encrypt_decrypt( $encoded, 'decrypt' );
		if ( empty( $order_id ) ) {
			$order_id = $encoded;
		}
		return $order_id;
	} elseif ( ! isset( $vars[ $key ] ) ) {
		wds_log( 'Data vars not found. Key = ' . $key );
		return false;
	}

	return wds_sanitize_text_field( $vars[ $key ] );
}

/**
 * Creates a nonce for WDS operations.
 *
 * @return string The sanitized encrypted nonce.
 */
function wds_create_nonce() {
	$nonce = wds_encrypt_decrypt( strtotime( 'now' ) );

	return wds_sanitize_text_field( $nonce );
}

/**
 * Outputs or returns a hidden input field with a WDS nonce.
 *
 * @param string $name The name attribute of the hidden input field.
 * @param bool   $echo Optional. Whether to echo the field or return it. Default is true.
 * @return string|null The HTML input field if echo is false, otherwise null.
 */
function wds_nonce_field( $name, $echo = true ) {
	$field = '<input type="hidden" name="' . $name . '" value="' . wds_create_nonce() . '">';

	if ( false === $echo ) {
		return $field;
	}

	echo wp_kses_post( $field );
}

/**
 * Verifies the WDS nonce.
 *
 * @param string $nonce The encrypted nonce to verify.
 * @return bool True if the nonce is valid, false otherwise.
 */
function wds_verify_nonce( $nonce ) {
	if ( ! isset( $_COOKIE['wp_wds'] ) ) {
		return false;
	}

	$cookie = wds_encrypt_decrypt( wds_sanitize_text_field( $_COOKIE['wp_wds'] ), 'decrypt' );
	$nonce  = wds_encrypt_decrypt( wds_sanitize_text_field( $nonce ), 'decrypt' );
	$args   = json_decode( $cookie, true );

	if ( WDS_User_Info::get_ip() != $args['ip'] ) {
		return false;
	}

	if ( strtotime( '- 6 hours' ) > $nonce ) {
		return false;
	}

	return true;
}

/**
 * Sanitizes input data to prevent XSS attacks and unwanted HTML tags.
 *
 * @param string $data    The input data to be sanitized.
 * @param mixed  $default The default value to return if the input contains unsafe content. Default is false.
 * @return string|mixed   The sanitized input, or the default value if unsafe content is detected.
 */
function wds_sanitize_input( $data, $default = false ) {
	$pattern = '/<[^>]*script|<[^>]*>|<\/[^>]*>/i';

	if ( preg_match( $pattern, $data ) ) {
		return $default;
	}

	return htmlspecialchars( $data, ENT_QUOTES, 'UTF-8' );
}

/**
 * Sanitize a specific field value.
 *
 * @param string $value   The value to be sanitized.
 * @param string $default The default value to return if the value is not set or is empty. Default is an empty string.
 * @return string The sanitized value or the default value if the field is not set or empty.
 */
function wds_sanitize_text_field( $value, $default = '' ) {
	return isset( $value ) && ! empty( $value ) ? sanitize_text_field( $value ) : $default;
}

/**
 * Cleans a query parameter value by sanitizing it and replacing escaped characters for Guest Name.
 *
 * @since 2.3.2
 * @param string $value   The value to be cleaned.
 * @param string $default The default value to return if the value is not set or is empty. Default is an empty string.
 * @return string The cleaned value or the default value if the field is not set or empty.
 */
function wds_sanitize_text_guest_name( $value, $default = '' ) {
	$value = isset( $value ) && ! empty( $value ) ? sanitize_text_field( $value ) : $default;
	$value = str_replace( array( "\\'", '\\"', '\\\\' ), array( "'", '"', '\\' ), $value );
	return $value;
}

/**
 * Sanitize a specific key from the array data.
 *
 * @param array  $data    The array to fetch the value.
 * @param string $key     The key to look for in the array.
 * @param string $default The default value to return if the key is not set. Default is an empty string.
 * @param bool   $sanitize The data sanitize.
 * @return mixed The sanitized value of the key, or the default value if the key is not set.
 */
function wds_sanitize_data_field( $data, $key, $default = '', $sanitize = true ) {
	if ( wds_check_array( $data, true ) && isset( $data[ $key ] ) && ! empty( $data[ $key ] ) ) {
		if ( is_array( $data[ $key ] ) ) {
			$ret = $data[ $key ];
		} elseif ( $sanitize ) {
			$ret = sanitize_text_field( $data[ $key ] );
		} else {
			$ret = $data[ $key ];
		}
	} else {
		$ret = $default;
	}

	return $ret;
}

/**
 * Get number cookie in setting.
 */
function wds_cookie() {
	return wds_option( 'affiliate_cookie', 14 );
}

/**
 * Sets a cookie with a specified name and value for the duration defined in the options.
 *
 * @param string $cookie_name  The name of the cookie.
 * @param string $cookie_value The value to be stored in the cookie.
 */
function wds_set_cookie( $cookie_name, $cookie_value ) {
	$cookie_day  = wds_cookie();
	$cookie_time = intval( $cookie_day ) * DAY_IN_SECONDS;

	setcookie( $cookie_name, $cookie_value, time() + $cookie_time, COOKIEPATH, COOKIE_DOMAIN );
}

/**
 * Deletes a specified cookie by setting its expiration time in the past.
 *
 * @param string $cookie_name The name of the cookie to delete.
 */
function wds_delete_cookie( $cookie_name ) {
	$cookie_day  = wds_cookie();
	$cookie_time = intval( $cookie_day ) * DAY_IN_SECONDS;

	setcookie( $cookie_name, '', time() - $cookie_time, COOKIEPATH, COOKIE_DOMAIN );
}

/**
 * Formats a timestamp according to the site's date format settings.
 *
 * @param int $timestamp The Unix timestamp to format.
 * @return string The formatted date.
 */
function wds_date_format( $timestamp ) {
	$date_format = date_i18n( get_option( 'date_format' ), $timestamp );

	return $date_format;
}

/**
 * Get assets source.
 *
 * @param string $file The file name.
 * @return string The path file source.
 */
function wds_assets( $file = false ) {
	$file_src = ABSPATH . 'wp-content/plugins/weddingsaas-pro/src/' . $file;
	if ( wds_doing_script_debug() && file_exists( $file_src ) ) {
		$src = WDS_URL . 'src/' . $file;
	} else {
		$src = WDS_URL . 'assets/' . $file;
	}

	return $src;
}

/**
 * is Administrator?
 */
function wds_is_admin() {
	if ( current_user_can( 'manage_options' ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if the current user is a editor.
 *
 * @since 2.3.0
 * @return bool True if the user is a editor, false otherwise.
 */
function wds_is_editor() {
	$user = wp_get_current_user();
	if ( in_array( 'editor', (array) $user->roles ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if the current user has admin-level access.
 *
 * @since 2.1.0
 * @return bool True if the user has admin access, false otherwise.
 */
function wds_is_admin_access() {
	$user            = wp_get_current_user();
	$allowed_roles   = wds_check_array( wds_option( 'access_admin' ) );
	$allowed_roles[] = 'administrator';

	if ( array_intersect( $user->roles, $allowed_roles ) ) {
		return true;
	}

	return false;
}

/**
 * Abstraction for WordPress cron checking, to avoid code duplication.
 *
 * @return boolean
 */
function wds_doing_cron() {
	return wp_doing_cron() ? true : false;
}

/**
 * Abstraction for WordPress AJAX checking, to avoid code duplication.
 *
 * @return boolean
 */
function wds_doing_ajax() {
	return wp_doing_ajax() ? true : false;
}

/**
 * Abstraction for WordPress autosave checking, to avoid code duplication.
 *
 * @return boolean
 */
function wds_doing_autosave() {
	return defined( 'DOING_AUTOSAVE' ) && ( true === DOING_AUTOSAVE ) ? true : false;
}

/**
 * Abstraction for WordPress Script-Debug checking to avoid code duplication.
 *
 * @return boolean
 */
function wds_doing_script_debug() {
	return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? true : false;
}

/**
 * Check for page builder query args.
 */
function wds_is_page_builder() {
	$page_builders = apply_filters(
		'wds_is_page_builders',
		array(
			'customizer',
			'elementor-preview', //elementor
			'fl_builder', //beaver builder
			'et_fb', //divi
			'ct_builder', //oxygen
			'tve', //thrive
			'app', //flatsome
			'uxb_iframe',
			'fb-edit', //fusion builder
			'builder',
			'bricks', //bricks
			'vc_editable', //wp bakery
			'op3editor', //optimizepress
			'cs_preview_state', //cornerstone
			'breakdance', //breakdance
			'breakdance_iframe',
			'givewp-route', //givewp
		)
	);

	if ( ! empty( $page_builders ) ) {
		foreach ( $page_builders as $page_builder ) {
			if ( isset( $_REQUEST[ $page_builder ] ) ) {
				return true;
			}
		}
	}

	return false;
}
