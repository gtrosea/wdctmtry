<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'User', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'heading',
				'content' => __( 'Account Activation', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => $activation_sc,
			),
			array(
				'id'       => $activation_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_activation_subject', $activation_subject ),
			),
			array(
				'id'       => $activation_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_activation_body', $activation_content ),
			),
			array(
				'id'      => $activation_prefix . 'whatsapp_enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable WhatsApp Notification', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_activation_whatsapp_enable' ),
			),
			array(
				'id'         => $activation_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_activation_whatsapp', $activation_content ),
				'dependency' => array( $activation_prefix . 'whatsapp_enable', '==', 'true' ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Account Registration', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => $register_sc,
			),
			array(
				'id'       => $register_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_register_subject', $register_subject ),
			),
			array(
				'id'       => $register_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_register_body', $register_content ),
			),
			array(
				'id'         => $register_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_register_whatsapp', $register_content ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Account Upgrade', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => $shortcode,
			),
			array(
				'id'       => $upgrade_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_user_upgrade_subject', $upgrade_subject ),
			),
			array(
				'id'       => $upgrade_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_user_upgrade_body', $upgrade_content ),
			),
			array(
				'id'         => $upgrade_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_user_upgrade_whatsapp', $upgrade_content ),
			),
		),
	)
);
