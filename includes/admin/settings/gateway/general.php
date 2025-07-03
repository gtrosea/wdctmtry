<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'gateway',
		'title'  => __( 'General', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'notice',
				'style'   => 'info',
				'content' => __( 'Setelah memilih metode pembayaran, silahkan simpan lalu reload terlebih dahulu.', 'weddingsaas' ),
			),
			array(
				'id'          => 'gateway_active',
				'type'        => 'select',
				'title'       => __( 'Metode Pembayaran Aktif', 'weddingsaas' ),
				'desc'        => __( 'Pilih bagaimana Anda ingin pelanggan membayar Anda.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih...', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_gateways(),
				'default'     => array( 'banktransfer' ),
			),
			array(
				'id'          => 'gateway_default',
				'type'        => 'select',
				'title'       => __( 'Metode Pembayaran Default', 'weddingsaas' ),
				'desc'        => __( 'Pilih metode pembayaran yang akan digunakan secara default.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih...', 'weddingsaas' ),
				'chosen'      => true,
				'options'     => wds_get_active_gateways(),
				'default'     => 'banktransfer',
			),
			array(
				'id'    => 'gateway_hide',
				'type'  => 'switcher',
				'title' => __( 'Sembunyikan Metode Pembayaran', 'weddingsaas' ),
				'desc'  => __( 'Cocok jika gateway yang aktif hanya satu, jadi tidak perlu memilih opsi pembayaran.', 'weddingsaas' ),
			),
		),
	)
);
