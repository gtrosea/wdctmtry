<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Client', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'client_type',
				'type'    => 'select',
				'title'   => __( 'Menu Type', 'wds-notrans' ),
				'options' => array(
					''     => __( 'Default', 'wds-notrans' ),
					'url'  => __( 'Custome', 'wds-notrans' ),
					'hide' => __( 'Hide', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'client_menu_conditional' ),
			),
			array(
				'id'          => 'client_link',
				'type'        => 'text',
				'title'       => __( 'Menu Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'client_menu_link', wds_url( 'client' ) ),
				'dependency'  => array( 'client_type', '==', 'url' ),
			),
			array(
				'id'          => 'client_product',
				'type'        => 'select',
				'title'       => __( 'Default Products', 'wds-notrans' ),
				'desc'        => __( 'Pilih produk untuk mengatur membership klien yang didaftarkan oleh reseller. Jika tidak dipilih, semua produk akan ditampilkan ke daftar produk.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_product_restrict( 'client' ),
				'default'     => wds_v1_client_product(),
				'dependency'  => array( 'client_type', '!=', 'hide' ),
			),
			array(
				'id'         => 'client_affiliate_status',
				'type'       => 'switcher',
				'title'      => __( 'Status Afiliasi Klien', 'weddingsaas' ),
				'default'    => 'inactive' == wds_v1_option( 'client_affiliate_status' ) ? false : true,
				'dependency' => array( 'client_type', '!=', 'hide' ),
			),
		),
	)
);
