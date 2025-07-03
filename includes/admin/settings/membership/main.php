<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Main', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'login_redirect',
				'type'    => 'text',
				'title'   => __( 'Link Halaman Setelah Login', 'weddingsaas' ),
				'default' => wds_v1_option( 'login_redirect', wds_url( 'overview' ) ),
			),
			array(
				'id'      => 'logout_redirect',
				'type'    => 'text',
				'title'   => __( 'Link Halaman Setelah Logout', 'weddingsaas' ),
				'default' => wds_v1_option( 'logout_redirect', home_url() ),
			),
			array(
				'id'      => 'signup_link',
				'type'    => 'text',
				'title'   => __( 'Link Pendaftaran', 'weddingsaas' ),
				'default' => wds_v1_option( 'signup_link', home_url( 'checkout/trial/' ) ),
			),
			array(
				'id'      => 'dashboard_link',
				'type'    => 'text',
				'title'   => __( 'Link Dasbor Undangan', 'weddingsaas' ),
				'default' => wds_v1_option( 'dashboard_link', wds_url( 'invitation' ) ),
			),
			array(
				'id'      => 'support_link',
				'type'    => 'text',
				'title'   => __( 'Link Dukungan', 'weddingsaas' ),
				'default' => wds_v1_option( 'support_link', home_url() ),
			),
			array(
				'id'      => 'account_activation',
				'type'    => 'switcher',
				'title'   => __( 'Aktivasi Akun', 'weddingsaas' ),
				'desc'    => __( 'Jika diaktifkan, pengguna harus aktivasi akun terlebih dahulu melalui email / whatsapp saat mendaftar.', 'weddingsaas' ),
				'default' => 'active' == wds_v1_option( 'account_activation' ),
			),
			array(
				'id'      => 'user_expiration',
				'type'    => 'switcher',
				'title'   => __( 'Akumulasi Kuota Undangan', 'weddingsaas' ),
				'desc'    => __( 'Jika diaktifkan, ketika pengguna kadaluarsa kuota undangan akan diakumulasikan, jika tidak undangan tidak diakumulasi / hangus.', 'weddingsaas' ),
				'default' => 'accumulation' == wds_v1_option( 'user_expiration_conditional' ),
			),
			array(
				'id'       => 'restrict',
				'type'     => 'select',
				'title'    => __( 'Restrict Content', 'weddingsaas' ),
				'desc'     => __( 'Pilih Post Type yang akan dibatasi.', 'weddingsaas' ),
				'chosen'   => true,
				'multiple' => true,
				'options'  => 'post_types',
				'default'  => wds_v1_restrict(),
			),
			array(
				'id'       => 'iframe',
				'type'     => 'select',
				'title'    => __( 'Iframe', 'weddingsaas' ),
				'desc'     => __( 'Pilih Post Type yang akan diaktifkan iframe.', 'weddingsaas' ),
				'chosen'   => true,
				'multiple' => true,
				'options'  => 'post_types',
				'default'  => wds_v1_iframe(),
			),
			array(
				'id'       => 'access_admin',
				'type'     => 'select',
				'title'    => __( 'Akses Admin', 'weddingsaas' ),
				'desc'     => __( 'Pilih role yang diizinkan untuk akses admin. Default role yang diizinkan yaitu administrator.', 'weddingsaas' ),
				'chosen'   => true,
				'multiple' => true,
				'options'  => 'roles',
			),
			array(
				'id'    => 'hide_password',
				'type'  => 'switcher',
				'title' => __( 'Sembunyikan Password di Checkout', 'weddingsaas' ),
				'desc'  => __( 'Jika diaktifkan, field password di halaman checkout dihilangkan dan password dikirim ke email / whatsapp.', 'weddingsaas' ),
			),
			array(
				'id'          => 'default_password',
				'type'        => 'text',
				'title'       => __( 'Default Password', 'weddingsaas' ),
				'dependency'  => array( 'hide_password', '==', 'true' ),
				'placeholder' => '@1234567@',
				'default'     => '@1234567@',
			),
		),
	)
);
