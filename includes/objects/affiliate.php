<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a affiliate.
 *
 * @param int|object $affiliate The affiliate ID or object.
 * @return mixed Affiliate object if found, false otherwise.
 */
function wds_get_affiliate( $affiliate = false ) {
	return WDS()->database->get( 'affiliate', $affiliate );
}

/**
 * Insert a new affiliate.
 *
 * @param array $data Affiliate data to insert.
 * @return mixed The ID of the new affiliate, or false on failure.
 */
function wds_insert_affiliate( $data = array() ) {
	return WDS()->database->add( 'affiliate', $data );
}

/**
 * Update existing affiliate.
 *
 * @param array $data Affiliate data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_affiliate( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_affiliate( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_affiliate( $data );
	}

	return WDS()->database->update( 'affiliate', $data );
}

/**
 * Delete a affiliate.
 *
 * @param int|object $affiliate The affiliate ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_affiliate( $affiliate = false ) {
	$obj = wds_get_affiliate( $affiliate );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->database->delete( 'affiliate', $obj->ID );
}

/**
 * Generate an affiliate link for a specific product and user.
 *
 * @param mixed $user User object, ID, or login.
 * @param mixed $product Product ID, object, or slug.
 * @return string The affiliate link URL.
 */
function wds_affiliate_link( $user = null, $product = false ) {
	$user_login   = false;
	$product_slug = '';

	if ( is_object( $user ) && isset( $user->ID ) ) {
		$user_login = get_userdata( $user->ID )->user_login;
	} elseif ( is_numeric( $user ) ) {
		$user_id   = intval( $user );
		$user_data = get_userdata( $user_id );
		if ( $user_data ) {
			$user_login = $user_data->user_login;
		}
	}

	if ( false === $user_login ) {
		$current_user = wp_get_current_user();
		$user_login   = $current_user->user_login;
	}

	// Replace username with user ID in the referral URL
	$user_id = get_user_by( 'login', $user_login )->ID;
	$link    = site_url() . '/reff/' . $user_id . '/';

	if ( $product ) {
		if ( is_object( $product ) && $product->slug ) {
			$product_slug = wds_sanitize_text_field( $product->slug );
		} elseif ( is_int( $product ) || is_string( $product ) ) {
			$product = wds_get_product( intval( $product ) );
			if ( $product->slug ) {
				$product_slug = $product->slug;
			}
		}

		$link = $link . $product_slug . '/';
	}

	return $link;
}

/**
 * Format and print the affiliate commission.
 *
 * @param array|false $raw_commission Commission data, including type and value.
 * @return string Formatted commission, either in percentage or currency.
 */
function wds_print_affiliate_commission( $raw_commission = false ) {
	if ( false == $raw_commission ) {
		return 0;
	}

	if ( 'percen' == $raw_commission['type'] ) {
		return $raw_commission['value'] . '%';
	}

	return wds_convert_money( $raw_commission['value'] );
}

/**
 * Retrieve the affiliate cookie name.
 *
 * @return string The affiliate cookie name.
 */
function wds_affiliate_cookie() {
	return 'wds_affiliate';
}

/**
 * Set the affiliate cookie with a specified username.
 *
 * @param string $username Affiliate username to store in the cookie.
 * @return bool True on success, false on failure.
 */
function wds_set_affiliate_cookie( $username = false ) {
	if ( ! $username ) {
		return false;
	}

	$cookie_time = intval( wds_cookie() ) * DAY_IN_SECONDS;
	setcookie( wds_affiliate_cookie(), $username, time() + $cookie_time, COOKIEPATH, COOKIE_DOMAIN );

	return true;
}

/**
 * Retrieve the current affiliate cookie value.
 *
 * @return mixed The affiliate cookie value, or false if not set.
 */
function wds_get_current_affiliate_cookie() {
	$name = wds_affiliate_cookie();

	return wds_sanitize_data_field( $_COOKIE, $name, false );
}

/**
 * Delete the current affiliate cookie.
 *
 * @return void
 */
function wds_delete_current_affiliate_cookie() {
	$cookie_time = intval( wds_cookie() ) * DAY_IN_SECONDS;
	setcookie( wds_affiliate_cookie(), '', time() - $cookie_time, COOKIEPATH, COOKIE_DOMAIN );
}

/**
 * Retrieve a summary of affiliate statistics, including sales, leads, and commissions.
 *
 * @param int|false $affiliate_id Optional. Affiliate ID. Default is the current user.
 * @return object An object containing sales, leads, commissions, clicks, and unique clicks.
 */
function wds_get_affiliate_summary( $affiliate_id = false ) {
	return WDS_Statistics::affiliate_summary( $affiliate_id );
}
