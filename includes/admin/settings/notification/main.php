<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'Main', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Email Settings', 'wds-notrans' ),
			),
			array(
				'id'      => 'email_from_name',
				'type'    => 'text',
				'title'   => __( 'From name', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_from_name', $site_name ),
			),
			array(
				'id'      => 'email_from_email',
				'type'    => 'text',
				'title'   => __( 'From email address', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_from_email', $admin_email ),
			),
			array(
				'id'      => 'email_reply_to_name',
				'type'    => 'text',
				'title'   => __( 'Reply name', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_reply_to_name', $site_name ),
			),
			array(
				'id'      => 'email_reply_to_email',
				'type'    => 'text',
				'title'   => __( 'Reply email address', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_reply_to_email', $admin_email ),
			),
			array(
				'id'      => 'email_logo',
				'type'    => 'upload',
				'library' => 'image',
				'preview' => true,
				'title'   => __( 'Email Logo', 'wds-notrans' ),
				'default' => wds_v1_option( 'email_logo' ),
			),
			array(
				'id'       => 'email_footer',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Footer', 'wds-notrans' ),
				'height'   => '20px',
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_footer', '<center>www.weddingsaas.id</center>' ),
			),
			array(
				'id'      => 'email_template',
				'type'    => 'select',
				'title'   => __( 'Email Template', 'wds-notrans' ),
				'desc'    => __( 'Choose a template. Once you\'ve saved your changes, preview an email to see the new template.', 'wds-notrans' ),
				'options' => array(
					'default' => __( 'Default', 'wds-notrans' ),
				),
				'default' => 'default',
			),

			array(
				'type'  => 'heading',
				'title' => __( 'WhatsApp Settings', 'wds-notrans' ),
			),
			array(
				'id'      => 'whatsapp_gateway',
				'type'    => 'select',
				'title'   => __( 'WhatsApp Gateway Service', 'wds-notrans' ),
				'options' => array(
					''             => __( 'No Whatsapp Notifications' ),
					'starsender'   => 'Starsender',
					'starsenderv3' => 'Starsender V3',
					'onesender'    => 'OneSender',
					'responic'     => 'Responic',
					'fonnte'       => 'Fonnte',
					'dripsender'   => 'Dripsender',
					'autowa'       => 'AutoWA',
				),
				'default' => wds_v1_option( 'whatsapp_gateway' ),
			),
			array(
				'id'         => 'api_starsender',
				'type'       => 'text',
				'title'      => 'Starsender API Key',
				'default'    => wds_v1_option( 'api_starsender' ),
				'dependency' => array( 'whatsapp_gateway', 'any', 'starsender,starsenderv3' ),
			),
			array(
				'id'         => 'api_onesender',
				'type'       => 'text',
				'title'      => 'OneSender API Key',
				'default'    => wds_v1_option( 'api_onesender' ),
				'dependency' => array( 'whatsapp_gateway', '==', 'onesender' ),
			),
			array(
				'id'          => 'api_url_onesender',
				'type'        => 'text',
				'title'       => 'OneSender API URL',
				'desc'        => 'Enter the main domain OneSender Installation URL.',
				'placeholder' => 'Ex. https://wa111.api-wa.my.id',
				'default'     => wds_v1_option( 'api_url_onesender' ),
				'dependency'  => array( 'whatsapp_gateway', '==', 'onesender' ),
			),
			array(
				'id'         => 'api_responic',
				'type'       => 'text',
				'title'      => 'Responic API Token',
				'default'    => wds_v1_option( 'api_responic' ),
				'dependency' => array( 'whatsapp_gateway', '==', 'responic' ),
			),
			array(
				'id'         => 'fonnte_token',
				'type'       => 'text',
				'title'      => 'Fonnte Token',
				'default'    => wds_v1_option( 'fonnte_token' ),
				'dependency' => array( 'whatsapp_gateway', '==', 'fonnte' ),
			),
			array(
				'id'         => 'api_dripsender',
				'type'       => 'text',
				'title'      => 'Dripsender API Key',
				'default'    => wds_v1_option( 'dripsender_api_key' ),
				'dependency' => array( 'whatsapp_gateway', '==', 'dripsender' ),
			),
			array(
				'id'         => 'api_autowa',
				'type'       => 'text',
				'title'      => 'AutoWA API Key',
				'default'    => wds_v1_option( 'api_autowa' ),
				'dependency' => array( 'whatsapp_gateway', '==', 'autowa' ),
			),
			array(
				'id'         => 'api_clientid_autowa',
				'type'       => 'text',
				'title'      => 'AutoWA Client ID',
				'default'    => wds_v1_option( 'api_clientid_autowa' ),
				'dependency' => array( 'whatsapp_gateway', '==', 'autowa' ),
			),
		),
	)
);
