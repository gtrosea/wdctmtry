<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'Client', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'heading',
				'content' => __( 'Registration', 'wds-notrans' ),
			),
			array(
				'id'      => $client_register_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_client_registration_enable' ),
			),
			array(
				'type'       => 'content',
				'content'    => $client_register_sc,
				'dependency' => array( $client_register_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $client_register_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_client_register_subject', $client_register_subject ),
				'dependency' => array( $client_register_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $client_register_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_client_register_body', $client_register_content ),
				'dependency' => array( $client_register_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $client_register_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_client_register_whatsapp', $client_register_content ),
				'dependency' => array( $client_register_prefix . 'enable', '==', 'true' ),
			),
		),
	)
);
