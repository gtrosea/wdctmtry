<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Removes specific components from the WordPress <head> for certain pages.
 */
function remove_component_wp_head() {
	if ( 'dashboard/invitation/edit' == wds_is_page() || 'dashboard/landingpage/edit' == wds_is_page() ) {
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );

		// rank math
		remove_action( 'rank_math/head', '_wp_render_title_tag', 1 );
		add_action(
			'rank_math/head',
			function () {
				global $wp_filter;
				if ( isset( $wp_filter['rank_math/json_ld'] ) ) {
					unset( $wp_filter['rank_math/json_ld'] );
				}
				remove_all_actions( 'rank_math/opengraph/facebook' );
				remove_all_actions( 'rank_math/opengraph/twitter' );
			}
		);
	}
}
add_action( 'wp', 'remove_component_wp_head', 20 );

// add_action( 'template_redirect', 'exclude_wp_rocket_cache' );

// function exclude_wp_rocket_cache() {
//  if ( is_page( 'nama-halaman' ) ) { // Ganti 'nama-halaman' dengan slug halaman yang ingin dikecualikan
//      define( 'DONOTCACHEPAGE', true );
//  }

//  // Untuk mengecualikan berdasarkan kondisi lain, seperti post type tertentu
//  if ( is_singular( 'post_type' ) ) { // Ganti 'post_type' dengan jenis postingan yang ingin dikecualikan
//      define( 'DONOTCACHEPAGE', true );
//  }
// }

// if ( strpos( $_SERVER['REQUEST_URI'], 'nama-halaman' ) !== false ) {
//  define( 'DONOTCACHEPAGE', true );
// }


// add_filter( 'litespeed_cache_control', 'exclude_litespeed_cache' );

// function exclude_litespeed_cache( $cache ) {
//  if ( is_page( 'nama-halaman' ) ) { // Ganti 'nama-halaman' dengan slug halaman yang ingin dikecualikan
//      return false; // Nonaktifkan cache untuk halaman ini
//  }

//  // Contoh pengecualian lainnya
//  if ( is_singular( 'post_type' ) ) { // Ganti 'post_type' dengan jenis post yang dikecualikan
//      return false; // Nonaktifkan cache untuk post type ini
//  }

//  return $cache; // Tetap aktifkan cache untuk halaman lain
// }
