<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$pref = 'qris_';

CSF::createSection(
	$prefix,
	array(
		'parent' => 'gateway',
		'title'  => __( 'QRIS', 'wds-notrans' ),
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
				'default'  => wds_v1_option( 'payment_gateway_' . $pref . 'title', 'QRIS' ),
			),
			array(
				'id'       => $pref . 'instruction',
				'type'     => 'textarea',
				'title'    => __( 'Intruksi', 'weddingsaas' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'payment_gateway_' . $pref . 'instruction', 'Transfer pembayaran Anda ke Kode QR di bawah ini.' ),
			),
			array(
				'id'      => $pref . 'code',
				'type'    => 'upload',
				'library' => 'image',
				'preview' => true,
				'title'   => __( 'QR Code', 'weddingsaas' ),
				'default' => wds_v1_option( 'payment_gateway_' . $pref . 'bank' ),
			),
		),
	)
);
