<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$apikey = __( 'Untuk mengambil apikey silahkan buka <a href="https://dashboard.xendit.co/settings/developers#api-keys" target="_blank">link ini</a>. Lihat tutorial di dokumentasi untuk lebih lengkapnya.', 'weddingsaas' );

$pref = 'xendit_';

CSF::createSection(
	$prefix,
	array(
		'parent' => 'gateway',
		'title'  => __( 'Xendit', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => $pref . 'icon_enable',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Ikon', 'weddingsaas' ),
				'default' => wds_v1_option( 'payment_gateway_' . $pref . 'icon_enable', true ),
			),
			array(
				'id'         => $pref . 'icon',
				'type'       => 'upload',
				'library'    => 'image',
				'preview'    => true,
				'title'      => __( 'Kustom Ikon', 'weddingsaas' ),
				'default'    => wds_v1_option( 'payment_gateway_' . $pref . 'icon', wds_logo_payment( str_replace( '_', '', $pref ) ) ),
				'dependency' => array( $pref . 'icon_enable', '==', 'true' ),
			),
			array(
				'id'       => $pref . 'title',
				'type'     => 'text',
				'title'    => __( 'Judul', 'weddingsaas' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'payment_gateway_' . $pref . 'title', 'Pembayaran Otomatis' ),
			),
			array(
				'id'       => $pref . 'instruction',
				'type'     => 'textarea',
				'title'    => __( 'Intruksi', 'weddingsaas' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'payment_gateway_' . $pref . 'instruction', 'Pembayaran otomatis dengan virtual akun, qris, atau retail.' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Settings', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'title'   => __( 'Callback Url', 'wds-notrans' ),
				'content' => home_url( '?wds_xendit=xendit_invoice_callback' ),
			),
			array(
				'id'      => $pref . 'dev',
				'type'    => 'switcher',
				'title'   => __( 'Test Environment', 'wds-notrans' ),
				'desc'    => __( 'Enable Test Environment - Please uncheck for processing real transaction', 'wds-notrans' ),
				'default' => wds_v1_option( $pref . 'developmentmode' ),
			),
			array(
				'id'         => $pref . 'api_key',
				'type'       => 'text',
				'title'      => __( 'Secret API Key', 'wds-notrans' ),
				'subtitle'   => __( '(Live Mode)', 'wds-notrans' ),
				'desc'       => $apikey,
				'sanitize'   => false,
				'default'    => wds_v1_option( $pref . 'api_key' ),
				'dependency' => array( $pref . 'dev', '==', 'false' ),
			),
			array(
				'id'         => $pref . 'api_key_dev',
				'type'       => 'text',
				'title'      => __( 'Secret API Key', 'wds-notrans' ),
				'subtitle'   => __( '(Test Mode)', 'wds-notrans' ),
				'desc'       => $apikey,
				'sanitize'   => false,
				'default'    => wds_v1_option( $pref . 'api_key_dev' ),
				'dependency' => array( $pref . 'dev', '==', 'true' ),
			),
			array(
				'id'      => $pref . 'notification',
				'type'    => 'switcher',
				'title'   => __( 'Customer Notification', 'wds-notrans' ),
				'desc'    => __( 'Please check for enable notification from Xendit', 'wds-notrans' ),
				'default' => wds_v1_option( $pref . 'notification' ),
			),
			array(
				'id'      => $pref . 'payment_link',
				'type'    => 'switcher',
				'title'   => __( 'Link Pembayaran Langsung', 'weddingsaas' ),
				'desc'    => __( 'Jika diaktifkan, link pembayaran pada notifikasi email dan whatsapp diarahkan ke website Xendit.', 'weddingsaas' ),
				'default' => wds_v1_option( $pref . 'payment_link' ),
			),
		),
	)
);
