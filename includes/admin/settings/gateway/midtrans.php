<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$pref = 'midtrans_';

CSF::createSection(
	$prefix,
	array(
		'parent' => 'gateway',
		'title'  => __( 'Midtrans', 'wds-notrans' ),
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
				'title'   => __( 'Notification URL endpoint', 'wds-notrans' ),
				'content' => wds_v1_option( $pref . 'callback_url', home_url( '?wds_midtrans=midtrans_notification_invoice' ) ) . '<br>Untuk production isi <a href="https://dashboard.midtrans.com/settings/payment/notification" target="_blank">disini</a>, untuk sandbox isi <a href="https://dashboard.sandbox.midtrans.com/settings/payment/notification" target="_blank">disini</a>.',
			),
			array(
				'id'      => $pref . 'sandbox',
				'type'    => 'switcher',
				'title'   => __( 'Enable Testing Sandbox', 'wds-notrans' ),
				'desc'    => __( 'Enable sandbox only to test payments with Midtrans sandbox.', 'wds-notrans' ),
				'default' => wds_v1_option( $pref . 'sanbox' ),
			),
			array(
				'id'       => $pref . 'merchant_id',
				'type'     => 'text',
				'title'    => __( 'Merchant ID', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( $pref . 'merchant_id' ),
			),
			array(
				'id'         => $pref . 'prod_server_key',
				'type'       => 'text',
				'title'      => __( 'Server Key', 'wds-notrans' ),
				'subtitle'   => __( '(Production Mode)', 'wds-notrans' ),
				'desc'       => 'Get the key <a href="https://dashboard.midtrans.com/settings/config_info" target="_blank">here</a>',
				'sanitize'   => false,
				'default'    => wds_v1_option( $pref . 'prod_server_key' ),
				'dependency' => array( $pref . 'sandbox', '==', 'false' ),
			),
			array(
				'id'         => $pref . 'sand_server_key',
				'type'       => 'text',
				'title'      => __( 'Server Key', 'wds-notrans' ),
				'subtitle'   => __( '(Sandbox Mode)', 'wds-notrans' ),
				'desc'       => 'Get the key <a href="https://dashboard.sandbox.midtrans.com/settings/config_info" target="_blank">here</a>',
				'sanitize'   => false,
				'default'    => wds_v1_option( $pref . 'sand_server_key' ),
				'dependency' => array( $pref . 'sandbox', '==', 'true' ),
			),
			array(
				'id'      => $pref . 'payment_link',
				'type'    => 'switcher',
				'title'   => __( 'Link Pembayaran Langsung', 'weddingsaas' ),
				'desc'    => __( 'Jika diaktifkan, link pembayaran pada notifikasi email dan whatsapp diarahkan ke website Midtrans.', 'weddingsaas' ),
				'default' => wds_v1_option( $pref . 'payment_link' ),
			),
		),
	)
);
