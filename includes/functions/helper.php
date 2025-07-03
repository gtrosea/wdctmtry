<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves the appropriate home URL based on replica status.
 *
 * @since 2.1.2
 * @return string The determined home URL.
 */
function wds_home_url() {
	$url = wds_is_replica() ? 'https://' . wds_option( 'wdr_domain_host' ) : home_url();
	return $url;
}

/**
 * Check if a license is active.
 *
 * @return bool True if active, false otherwise.
 */
function wds_is_active() {
	$license = get_option( WDS_SLUG . '_license', array() );

	if ( isset( $license['license'] ) && 'valid' === $license['license'] ) {
		return true;
	}

	return false;
}

/**
 * Get all products used for license check.
 *
 * Available args for returned products:
 * 'name', 'id', 'file', 'version'
 *
 * @return array List of products with their details.
 */
function wds_get_products() {
	$products = apply_filters( 'wds_pro_products', array() );

	ksort( $products );

	return $products;
}

/**
 * Check if a product license is active.
 *
 * @param  string $id ID of a product.
 * @return bool True if active|false otherwise
 */
function wds_is_product_active( $id ) {
	$license = get_option( $id . '_license', array() );

	if ( isset( $license['license'] ) && 'valid' == $license['license'] ) {
		return true;
	}

	return false;
}

/**
 * Check if a wds theme installed & license is active.
 *
 * @return bool True if active|false otherwise
 */
function wds_is_theme() {
	if ( class_exists( 'WDS_Theme' ) && 'valid' == get_option( 'wds_theme_license_key_status', false ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a wds replica installed & license is active.
 *
 * @return bool True if active|false otherwise
 */
function wds_is_replica() {
	if ( class_exists( 'WDS_Replica' ) && wds_is_product_active( 'wds_replica' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a wds midtrans installed & license is active.
 *
 * @return bool True if active|false otherwise
 */
function wds_is_midtrans() {
	if ( class_exists( 'WDS_Midtrans' ) && wds_is_product_active( 'wds_midtrans' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a wds digital product installed & license is active.
 *
 * @return bool True if active|false otherwise
 */
function wds_is_digital() {
	if ( class_exists( 'WDS_Digital' ) && wds_is_product_active( 'wds_digital' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a wds buku tamu installed & license is active.
 *
 * @return bool True if active|false otherwise
 */
function wds_is_buktam() {
	if ( class_exists( 'WDS_BukuTamu' ) && wds_is_product_active( 'wds_buktam' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a wds flip installed & license is active.
 *
 * @return bool True if active|false otherwise
 */
function wds_is_flip() {
	if ( class_exists( 'WDS_Flip' ) && wds_is_product_active( 'wds_flip' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a wds meta capi installed.
 *
 * @return bool True if active|false otherwise
 */
function wds_is_meta_capi() {
	if ( class_exists( 'WDS_MetaCapi' ) ) {
		return true;
	}

	return false;
}

/**
 * Generates and returns a styled admin notice.
 *
 * @param string $message Optional. The message to display in the notice. Default is an empty string.
 * @param string $color   Optional. The color of the notice. Can be 'success', 'error', 'warning', or 'info'. Default is 'warning'.
 * @return string|void The generated HTML string for the notice, or void if no message is provided.
 */
function wds_add_notice( $message = '', $color = 'warning' ) {
	if ( empty( $message ) ) {
		return;
	}

	$html_message = sprintf( '<div class="notice notice-%s wds-notice">%s</div>', esc_attr( $color ), wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Perform a safe, local redirect somewhere inside the current site.
 *
 * @param string $location The URL to redirect the user to.
 * @param int    $status   Optional. The numeric code to give in the redirect headers. Default: 302.
 * @param bool   $save     Optional. The save redirect header. Default: true.
 */
function wds_redirect( $location = '', $status = 302, $save = true ) {
	if ( empty( $location ) ) {
		$location = is_admin() ? admin_url() : home_url();
	}

	if ( $save ) {
		wp_safe_redirect( esc_url_raw( $location ), $status );
	} else {
		wp_redirect( esc_url_raw( $location ), $status ); // phpcs:ignore
	}

	exit;
}

/**
 * Debug function.
 *
 * This function wds_is used for debugging purposes.
 * It should be removed or commented out in production environments.
 *
 * @param mixed $args The variable to be printed for debugging.
 * @param bool  $title Optional. Title to be displayed before the debug output.
 */
function wds_debug( $args, $title = false ) {
	if ( $title ) {
		echo '<h3>' . esc_html( $title ) . '</h3>';
	}

	echo '<pre>';
	print_r( $args ); // phpcs:ignore
	echo '</pre>';
}

/**
 * Debug log function.
 *
 * @param string|array $data The data for debugging.
 * @param bool         $array The data is array.
 */
function wds_log( $data, $array = false ) {
	if ( ! defined( 'WP_DEBUG' ) && ! WP_DEBUG ) {
		return;
	}

	if ( empty( $data ) ) {
		return;
	}

	if ( $array ) {
		error_log( print_r( $data, true ) ); // phpcs:ignore
	} else {
		error_log( $data ); // phpcs:ignore
	}
}
