<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Registers a custom Rank Math variable for displaying the invitation title.
 *
 * @return void
 */
add_action(
	'rank_math/vars/register_extra_replacements',
	function () {
		rank_math_register_var_replacement(
			'wds_title',
			array(
				'name'        => __( 'Judul Undangan', 'weddingsaas' ),
				'description' => __( 'Untuk menampilkan judul undangan.', 'weddingsaas' ),
				'variable'    => 'wds_title',
				'example'     => __( 'Putri & Putra, December 31, 2023', 'weddingsaas' ),
			),
			'wds_rankmath_title'
		);
	}
);

/**
 * Retrieves the title of the invitation with a guest name placeholder if provided.
 *
 * @return string The invitation title with guest name replaced if available.
 */
function wds_rankmath_title() {
	$guest   = wds_sanitize_data_field( $_GET, 'to' );
	$content = wds_post_meta( get_the_ID(), '_meta_judul' );
	if ( $content ) {
		$args = array(
			'[guest-name]' => $guest,
			'[nama-tamu]'  => $guest,
		);

		$ret = strtr( $content, $args );
	} else {
		$ret = html_entity_decode( get_the_title( get_the_ID() ) );
	}

	return $ret;
}

/**
 * Registers a custom Rank Math variable for displaying the reseller page title.
 *
 * @return void
 */
add_action(
	'rank_math/vars/register_extra_replacements',
	function () {
		rank_math_register_var_replacement(
			'wdr_title',
			array(
				'name'        => __( 'Judul Halaman', 'weddingsaas' ),
				'description' => __( 'Untuk menampilkan judul halaman.', 'weddingsaas' ),
				'variable'    => 'wdr_title',
				'example'     => __( 'Jasa Undangan Digital', 'weddingsaas' ),
			),
			'wdr_rankmath_title'
		);
	}
);

/**
 * Retrieves the title of the reseller page based on the current domain.
 *
 * @return string The reseller page title.
 */
function wdr_rankmath_title() {
	$domain_custom = $_SERVER['HTTP_HOST'];
	$domain_host   = wds_option( 'wdr_domain_host' );

	if ( $domain_custom != $domain_host ) {
		$title = wds_option( 'wdr_seo_res_title' );
		$meta  = wds_user_meta( wds_data( 'reseller_id' ), '_meta_title' );
		if ( $meta ) {
			$title = $meta;
		}
	} else {
		$title = wds_option( 'wdr_seo_home_title' );
	}

	return $title;
}

/**
 * Registers a custom Rank Math variable for displaying the invitation description.
 *
 * @return void
 */
add_action(
	'rank_math/vars/register_extra_replacements',
	function () {
		rank_math_register_var_replacement(
			'wds_description',
			array(
				'name'        => __( 'Deskripsi Undangan', 'weddingsaas' ),
				'description' => __( 'Untuk menampilkan deskripsi undangan.', 'weddingsaas' ),
				'variable'    => 'wds_description',
				'example'     => __( 'Nama Tamu, Hadiri pernikahan Putri dan Putra pada hari Minggu, 31 Desember 2023!', 'weddingsaas' ),
			),
			'wds_rankmath_description'
		);
	}
);

/**
 * Retrieves the description of the invitation with a guest name placeholder if provided.
 *
 * @return string The invitation description with guest name replaced if available.
 */
function wds_rankmath_description() {
	$guest   = wds_sanitize_data_field( $_GET, 'to' );
	$content = wds_post_meta( get_the_ID(), '_meta_deskripsi' );
	$args    = array(
		'[guest]'     => $guest,
		'[nama-tamu]' => $guest,
	);

	return strtr( $content, $args );
}

/**
 * Registers a custom Rank Math variable for displaying the reseller page description.
 *
 * @return void
 */
add_action(
	'rank_math/vars/register_extra_replacements',
	function () {
		rank_math_register_var_replacement(
			'wdr_description',
			array(
				'name'        => __( 'Judul Halaman', 'weddingsaas' ),
				'description' => __( 'Untuk menampilkan deskripsi halaman.', 'weddingsaas' ),
				'variable'    => 'wdr_description',
				'example'     => __( 'Buat undangan digital kamu semakin berkesan dengan sentuhan digital', 'weddingsaas' ),
			),
			'wdr_rankmath_description'
		);
	}
);

/**
 * Retrieves the description of the reseller page based on the current domain.
 *
 * @return string The reseller page description.
 */
function wdr_rankmath_description() {
	$domain_custom = $_SERVER['HTTP_HOST'];
	$domain_host   = wds_option( 'wdr_domain_host' );

	if ( $domain_custom != $domain_host ) {
		$description = wds_option( 'wdr_seo_res_description' );
		$meta        = wds_user_meta( wds_data( 'reseller_id' ), '_meta_description' );
		if ( $meta ) {
			$description = $meta;
		}
	} else {
		$description = wds_option( 'wdr_seo_home_description' );
	}

	return $description;
}

/**
 * Filters the Open Graph image URL for Replica.
 *
 * @since 2.4.0
 * @param string $url The original image URL.
 * @return string The modified image URL if conditions are met, otherwise the original URL.
 */
add_filter(
	'rank_math/opengraph/facebook/image',
	function ( $url ) {
		if ( wds_is_replica() && wds_data( 'reseller_id' ) && ( wds_option( 'wdr_set_homepage' ) == get_the_ID() ) ) {
			$meta = wds_user_meta( wds_data( 'reseller_id' ), '_meta_thumbnail' );
			if ( $meta ) {
				return $meta;
			}
		}
		return $url;
	}
);
