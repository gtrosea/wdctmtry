<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$pref = 'banktransfer_';

CSF::createSection(
	$prefix,
	array(
		'parent' => 'gateway',
		'title'  => __( 'Bank Transfer', 'wds-notrans' ),
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
				'default'  => wds_v1_option( 'payment_gateway_' . $pref . 'title', 'Bank Transfer' ),
			),
			array(
				'id'       => $pref . 'instruction',
				'type'     => 'textarea',
				'title'    => __( 'Intruksi', 'weddingsaas' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'payment_gateway_' . $pref . 'instruction', 'Transfer pembayaran Anda ke salah satu rekening bank di bawah ini.' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Data Akun Bank', 'weddingsaas' ),
			),
			array(
				'id'      => $pref . 'bank',
				'type'    => 'repeater',
				'title'   => __( 'Data', 'weddingsaas' ),
				'fields'  => array(
					array(
						'id'      => 'name',
						'type'    => 'select',
						'title'   => __( 'Nama Bank', 'weddingsaas' ),
						'options' => array(
							'BCA'     => 'Bank BCA',
							'MANDIRI' => 'Bank Mandiri',
							'BRI'     => 'Bank BRI',
							'BNI'     => 'Bank BNI',
							'other'   => 'Other Bank',
						),
					),
					array(
						'id'         => 'name_input',
						'type'       => 'text',
						'title'      => __( 'Nama Bank Manual', 'weddingsaas' ),
						'dependency' => array( 'name', '==', 'other' ),
					),
					array(
						'id'    => 'account_name',
						'type'  => 'text',
						'title' => __( 'Nama Pemilik Rekening', 'weddingsaas' ),
					),
					array(
						'id'    => 'account_number',
						'type'  => 'text',
						'title' => __( 'No Rekening', 'weddingsaas' ),
					),
				),
				'default' => wds_v1_banktransfer(),
			),
		),
	)
);
