<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Get option data from cache or database.
 *
 * Fetch the option data from the cache first, if it is not in the cache,
 * data will be taken from the database, then stored in the cache.
 *
 * @param string $slug The slug key for the option group (e.g., 'settings', 'engine', 'lang').
 * @param string $key The specific option key to retrieve. If empty, the default value is returned.
 * @param mixed  $default The default value to return if the option is not found.
 * @return mixed The value of the option if it exists, or the default value if not found.
 */
function wds_get_cached_option( $slug, $key = '', $default = false ) {
	if ( empty( $key ) ) {
		return $default;
	}

	$cache_key = WDS_SLUG . '_' . $slug;

	$options = wp_cache_get( $cache_key, 'wds_data' );
	if ( false === $options ) {
		$options = get_option( $cache_key, array() );
		wp_cache_set( $cache_key, $options, 'wds_data', WEEK_IN_SECONDS );
	}

	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/**
 * Retrieve a general setting option from cache or database.
 *
 * @param string $key The key of the option to retrieve.
 * @param mixed  $default The default value to return if the option is not found.
 * @return mixed The value of the option, or the default value if not found.
 */
function wds_option( $key = '', $default = false ) {
	return wds_get_cached_option( 'settings', $key, $default );
}

/**
 * Retrieve an engine-specific setting from cache or database.
 *
 * @param string $key The key of the engine option to retrieve.
 * @param mixed  $default The default value to return if the option is not found.
 * @return mixed The value of the engine option, or the default value if not found.
 */
function wds_engine( $key = '', $default = false ) {
	return wds_get_cached_option( 'engine', $key, $default );
}

/**
 * Retrieve a language setting from cache or database.
 *
 * @param string $key The key of the language option to retrieve.
 * @param mixed  $default The default value to return if the language option is not found.
 * @return mixed The value of the language option, or the default value if not found.
 */
function wds_lang( $key = '', $default = false ) {
	$default = ! $default ? $key : $default;

	return wds_get_cached_option( 'lang', $key, $default );
}

/**
 * Get post meta with cache support.
 *
 * @param int    $post_id The ID of the post.
 * @param string $key     The meta key to retrieve.
 * @param bool   $single  Whether to return a single value.
 * @return mixed The post meta value, or false if not found.
 */
function wds_post_meta( $post_id, $key = '', $single = true ) {
	$post_id   = intval( $post_id );
	$cache_key = 'post_meta_' . $post_id;
	$post_meta = wp_cache_get( $cache_key, 'wds_data' );
	if ( false === $post_meta ) {
		$post_meta = get_post_meta( $post_id );
		wp_cache_set( $cache_key, $post_meta, 'wds_data', DAY_IN_SECONDS );
	}

	return isset( $post_meta[ $key ] ) ? ( $single ? maybe_unserialize( $post_meta[ $key ][0] ) : $post_meta[ $key ] ) : false;
}

/**
 * Get term meta with cache support.
 *
 * @param int    $term_id The ID of the term.
 * @param string $key     The meta key to retrieve.
 * @param bool   $single  Whether to return a single value.
 * @return mixed The term meta value, or false if not found.
 */
function wds_term_meta( $term_id, $key = '', $single = true ) {
	$term_id   = intval( $term_id );
	$cache_key = 'term_meta_' . $term_id;
	$term_meta = wp_cache_get( $cache_key, 'wds_data' );
	if ( false === $term_meta ) {
		$term_meta = get_term_meta( $term_id );
		wp_cache_set( $cache_key, $term_meta, 'wds_data', DAY_IN_SECONDS );
	}

	return isset( $term_meta[ $key ] ) ? ( $single ? maybe_unserialize( $term_meta[ $key ][0] ) : $term_meta[ $key ] ) : false;
}

/**
 * Get user meta with cache support.
 *
 * @param int    $user_id The ID of the user.
 * @param string $key     The meta key to retrieve.
 * @param bool   $single  Whether to return a single value.
 * @return mixed The user meta value, or false if not found.
 */
function wds_user_meta( $user_id, $key = '', $single = true ) {
	$user_id   = intval( $user_id );
	$cache_key = 'user_meta_' . $user_id;
	$user_meta = wp_cache_get( $cache_key, 'wds_data' );
	if ( false === $user_meta ) {
		$user_meta = get_user_meta( $user_id );
		wp_cache_set( $cache_key, $user_meta, 'wds_data', DAY_IN_SECONDS );
	}

	return isset( $user_meta[ $key ] ) ? ( $single ? maybe_unserialize( $user_meta[ $key ][0] ) : $user_meta[ $key ] ) : false;
}

/**
 * Delete cache.
 *
 * @param string $key The key to deleted.
 */
function wds_delete_cache( $key = '' ) {
	wp_cache_delete( $key, 'wds_data' );
}

/**
 * Delete user meta cache.
 *
 * @since 2.0.6
 * @param int $user_id The ID of the user.
 */
function wds_delete_cache_user( $user_id ) {
	wds_delete_cache( 'user_meta_' . intval( $user_id ) );
}

/**
 * Delete post meta cache.
 *
 * @since 2.0.7
 * @param int $post_id The ID of the post.
 */
function wds_delete_cache_post( $post_id ) {
	wds_delete_cache( 'post_meta_' . intval( $post_id ) );
}

/**
 * Check if the addon is enabled.
 *
 * @deprecated version 2.0.4
 * @return bool True if addon is enabled, false otherwise.
 */
function wds_addon_enable() {
	$options = get_option( WDS_SLUG . '_addons', array() );

	return wds_sanitize_data_field( $options, 'enable', false );
}

/**
 * Check if the addon price is fixed.
 *
 * @return bool True if addon is fixed, false otherwise.
 */
function wds_addon_fixed() {
	$options = get_option( WDS_SLUG . '_addons', array() );

	return wds_sanitize_data_field( $options, 'fixed', false );
}

/**
 * Retrieve addon data.
 *
 * @return mixed The data if exists, or array if not.
 */
function wds_addon_data() {
	$options = get_option( WDS_SLUG . '_addons', array() );
	$data    = wds_sanitize_data_field( $options, 'data', array() );

	if ( wds_check_array( $data, true ) ) {
		foreach ( $data as $key => $item ) {
			if ( empty( $item['title'] ) || empty( $item['id'] ) || empty( $item['price'] ) ) {
				unset( $data[ $key ] );
			}
		}
	}

	return $data;
}
