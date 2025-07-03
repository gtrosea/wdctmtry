<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$pref = 'flip_';

CSF::createSection(
	$prefix,
	array(
		'parent' => 'gateway',
		'title'  => __( 'Flip', 'wds-notrans' ),
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
				'content' => add_query_arg( 'wds-flip', 'FlipForBusiness_Gateway', home_url( '/' ) ) . '<br>' . __( 'Masukan url pada kolom Accept Payment.', 'weddingsaas' ),
			),
			array(
				'id'      => $pref . 'sandbox',
				'type'    => 'switcher',
				'title'   => __( 'Test Environment', 'wds-notrans' ),
				'desc'    => __( 'Enable Test Environment - Please uncheck for processing real transaction.', 'wds-notrans' ),
				'default' => wds_v1_option( $pref . 'developmentmode' ),
			),
			array(
				'id'         => $pref . 'api_key',
				'type'       => 'text',
				'title'      => __( 'API Secret Key', 'wds-notrans' ),
				'subtitle'   => __( 'Live Mode/Production', 'wds-notrans' ),
				'sanitize'   => false,
				'dependency' => array( $pref . 'sandbox', '==', 'false' ),
			),
			array(
				'id'         => $pref . 'api_key_sandbox',
				'type'       => 'text',
				'title'      => __( 'API Secret Key', 'wds-notrans' ),
				'subtitle'   => __( 'Test Mode/Sandbox', 'wds-notrans' ),
				'sanitize'   => false,
				'dependency' => array( $pref . 'sandbox', '==', 'true' ),
			),
			array(
				'id'         => $pref . 'token',
				'type'       => 'text',
				'title'      => __( 'Token Validation', 'wds-notrans' ),
				'subtitle'   => __( 'Live Mode/Production', 'wds-notrans' ),
				'sanitize'   => false,
				'dependency' => array( $pref . 'sandbox', '==', 'false' ),
			),
			array(
				'id'         => $pref . 'token_sandbox',
				'type'       => 'text',
				'title'      => __( 'Token Validation', 'wds-notrans' ),
				'subtitle'   => __( 'Test Mode/Sandbox', 'wds-notrans' ),
				'sanitize'   => false,
				'dependency' => array( $pref . 'sandbox', '==', 'true' ),
			),
			array(
				'id'      => $pref . 'payment_link',
				'type'    => 'switcher',
				'title'   => __( 'Link Pembayaran Langsung', 'weddingsaas' ),
				'desc'    => __( 'Jika diaktifkan, link pembayaran pada notifikasi email dan whatsapp diarahkan ke website Flip.', 'weddingsaas' ),
				'default' => wds_v1_option( $pref . 'payment_link' ),
			),
		),
	)
);
