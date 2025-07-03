<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'tools',
		'title'  => __( 'Register Validation', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Email', 'wds-notrans' ),
			),
			array(
				'id'      => 'email_validation',
				'type'    => 'select',
				'title'   => __( 'Email Validation Service', 'wds-notrans' ),
				'desc'    => '<a href="https://docs.weddingsaas.id/pro/tools/validasi-email-whatsapp#email" target="_blank">Tutorial</a>',
				'options' => array(
					''                => __( 'Tanpa Validasi Email', 'weddingsaas' ),
					'emaillistverify' => 'emaillistverify.com',
				),
				'default' => wds_v1_option( 'wds_email_validation' ),
			),
			array(
				'id'         => 'api_emaillistverify',
				'type'       => 'text',
				'title'      => __( 'emaillistverify.com API Key', 'wds-notrans' ),
				'default'    => wds_v1_option( 'wds_api_emaillistverify' ),
				'dependency' => array( 'email_validation', '==', 'emaillistverify' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'WhatsApp', 'wds-notrans' ),
			),
			array(
				'id'      => 'whatsapp_validation',
				'type'    => 'select',
				'title'   => __( 'WhatsApp Validation Service', 'wds-notrans' ),
				'desc'    => '<a href="https://docs.weddingsaas.id/pro/tools/validasi-email-whatsapp#whatsapp" target="_blank">Tutorial</a>',
				'options' => array(
					''               => __( 'Tanpa Validasi WhatsApp', 'weddingsaas' ),
					'starsender'     => 'Starsender',
					'fonnte'         => 'Fonnte',
					'onesender'      => 'OneSender',
					'whatsapp-data1' => 'whatsapp-data1.p.rapidapi.com',
				),
				'default' => wds_v1_option( 'wds_whatsapp_validation' ),
			),
			array(
				'id'         => 'api_starsender',
				'type'       => 'text',
				'title'      => __( 'Starsender API Key', 'wds-notrans' ),
				'dependency' => array( 'whatsapp_validation', '==', 'starsender' ),
			),
			array(
				'id'         => 'api_fonnte',
				'type'       => 'text',
				'title'      => __( 'Fonnte Token', 'wds-notrans' ),
				'default'    => wds_v1_option( 'wds_api_fonnte' ),
				'dependency' => array( 'whatsapp_validation', '==', 'fonnte' ),
			),
			array(
				'id'         => 'api_onesender',
				'type'       => 'text',
				'title'      => __( 'OneSender API Key', 'wds-notrans' ),
				'default'    => wds_v1_option( 'wds_api_onesender' ),
				'dependency' => array( 'whatsapp_validation', '==', 'onesender' ),
			),
			array(
				'id'          => 'api_url_onesender',
				'type'        => 'text',
				'title'       => __( 'OneSender API URL', 'wds-notrans' ),
				'desc'        => __( 'Masukkan URL Instalasi OneSender domain utama.', 'weddingsaas' ),
				'placeholder' => 'Ex: https://wa111.api-wa.my.id',
				'default'     => wds_v1_option( 'wds_api_url_onesender' ),
				'dependency'  => array( 'whatsapp_validation', '==', 'onesender' ),
			),
			array(
				'id'         => 'api_whatsapp-data1',
				'type'       => 'text',
				'title'      => __( 'whatsapp-data1 API Key', 'wds-notrans' ),
				'default'    => wds_v1_option( 'wds_api_whatsapp-data1' ),
				'dependency' => array( 'whatsapp_validation', '==', 'whatsapp-data1' ),
			),
		),
	)
);
