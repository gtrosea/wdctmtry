<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'Expired', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'content',
				'content' => $expired_sc,
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Membership Expired', 'wds-notrans' ),
			),
			array(
				'id'       => $expired_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_user_expired_subject', $expired_subject ),
			),
			array(
				'id'       => $expired_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_user_expired_body', $expired_content ),
			),
			array(
				'id'         => $expired_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_whatsapp', $expired_content ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Reminder 1 Day Before Expired', 'wds-notrans' ),
			),
			array(
				'id'      => $expired_reminder1_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_user_expired_reminder_1_before' ),
			),
			array(
				'id'         => $expired_reminder1_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_1_before_subject', $expired_reminder1_subject ),
				'dependency' => array( $expired_reminder1_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $expired_reminder1_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_1_before_body', $expired_reminder1_content ),
				'dependency' => array( $expired_reminder1_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $expired_reminder1_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_1_before_whatsapp', $expired_reminder1_content ),
				'dependency' => array( $expired_reminder1_prefix . 'enable', '==', 'true' ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Reminder 2 Day Before Expired', 'wds-notrans' ),
			),
			array(
				'id'      => $expired_reminder2_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_user_expired_reminder_2_before' ),
			),
			array(
				'id'         => $expired_reminder2_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_2_before_subject', $expired_reminder2_subject ),
				'dependency' => array( $expired_reminder2_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $expired_reminder2_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_2_before_body', $expired_reminder2_content ),
				'dependency' => array( $expired_reminder2_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $expired_reminder2_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_2_before_whatsapp', $expired_reminder2_content ),
				'dependency' => array( $expired_reminder2_prefix . 'enable', '==', 'true' ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Reminder 3 Day Before Expired', 'wds-notrans' ),
			),
			array(
				'id'      => $expired_reminder3_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_user_expired_reminder_3_before' ),
			),
			array(
				'id'         => $expired_reminder3_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_3_before_subject', $expired_reminder3_subject ),
				'dependency' => array( $expired_reminder3_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $expired_reminder3_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_3_before_body', $expired_reminder3_content ),
				'dependency' => array( $expired_reminder3_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $expired_reminder3_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_user_expired_reminder_3_before_whatsapp', $expired_reminder3_content ),
				'dependency' => array( $expired_reminder3_prefix . 'enable', '==', 'true' ),
			),
		),
	)
);
