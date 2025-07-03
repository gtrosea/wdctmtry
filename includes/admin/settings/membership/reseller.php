<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Reseller', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'reseller_branding',
				'type'    => 'switcher',
				'title'   => __( 'Fitur Branding', 'weddingsaas' ),
				'desc'    => __( 'Jika diaktifkan, akan menampilkan formulir branding untuk reseller di menu pengaturan akun.', 'weddingsaas' ),
				'default' => wds_v1_option( 'reseller_branding' ),
			),
			array(
				'id'          => 'reseller_hide',
				'type'        => 'select',
				'title'       => __( 'Sembunyikan Kolom', 'weddingsaas' ),
				'desc'        => __( 'Pilih kolom yang akan di sembunyikan.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih kolom', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => array(
					'name'        => 'Nama Brand',
					'logo'        => 'Logo',
					'link'        => 'Link Logo',
					'description' => 'Deskripsi',
					'instagram'   => 'Link Instagram',
					'facebook'    => 'Link Facebook',
					'tiktok'      => 'Link Tiktok',
					'twitter'     => 'Link Twitter (X)',
					'youtube'     => 'Link Youtube',
				),
				'default'     => wds_v1_reseller_hide(),
				'dependency'  => array( 'reseller_branding', '==', 'true' ),
			),
			array(
				'id'         => 'reseller_name_fallback',
				'type'       => 'text',
				'title'      => __( 'Nama Brand Default', 'weddingsaas' ),
				'default'    => wds_v1_option( 'reseller_branding_name_fallback', get_bloginfo( 'name' ) ),
				'dependency' => array( 'reseller_branding', '==', 'true' ),
			),
			array(
				'id'         => 'reseller_logo_fallback',
				'type'       => 'upload',
				'library'    => 'image',
				'preview'    => true,
				'title'      => __( 'Logo Brand Default', 'weddingsaas' ),
				'default'    => wds_v1_option( 'reseller_logo_fallback' ),
				'dependency' => array( 'reseller_branding', '==', 'true' ),
			),
			array(
				'id'         => 'reseller_link_fallback',
				'type'       => 'text',
				'title'      => __( 'Link Brand Default', 'weddingsaas' ),
				'default'    => wds_v1_option( 'reseller_link_fallback', home_url() ),
				'dependency' => array( 'reseller_branding', '==', 'true' ),
			),
			array(
				'id'         => 'reseller_desc_fallback',
				'type'       => 'text',
				'title'      => __( 'Deskripsi Brand Default', 'weddingsaas' ),
				'default'    => wds_v1_option( 'reseller_description_fallback', 'Made with ❤️ by ' . get_bloginfo( 'name' ) ),
				'sanitize'   => false,
				'dependency' => array( 'reseller_branding', '==', 'true' ),
			),
		),
	)
);
