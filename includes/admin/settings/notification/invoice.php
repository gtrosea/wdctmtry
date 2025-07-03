<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'Invoice', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'content',
				'content' => $shortcode,
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Invoice Place Order', 'wds-notrans' ),
			),
			array(
				'id'       => $invoice_place_order_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_place_order_subject', $invoice_place_order_subject ),
			),
			array(
				'id'       => $invoice_place_order_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_place_order_body', $invoice_place_order_content ),
			),
			array(
				'id'         => $invoice_place_order_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_place_order_whatsapp', $invoice_place_order_content ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Reminder 1 Day Unpaid Invoice', 'wds-notrans' ),
			),
			array(
				'id'      => $invoice_reminder1_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_1' ),
			),
			array(
				'id'         => $invoice_reminder1_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_1_subject', $invoice_reminder1_subject ),
				'dependency' => array( $invoice_reminder1_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_reminder1_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_1_body', $invoice_reminder1_content ),
				'dependency' => array( $invoice_reminder1_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_reminder1_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_1_whatsapp', $invoice_reminder1_content ),
				'dependency' => array( $invoice_reminder1_prefix . 'enable', '==', 'true' ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Reminder 2 Day Unpaid Invoice', 'wds-notrans' ),
			),
			array(
				'id'      => $invoice_reminder2_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_2' ),
			),
			array(
				'id'         => $invoice_reminder2_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_2_subject', $invoice_reminder2_subject ),
				'dependency' => array( $invoice_reminder2_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_reminder2_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_2_body', $invoice_reminder2_content ),
				'dependency' => array( $invoice_reminder2_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_reminder2_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_2_whatsapp', $invoice_reminder2_content ),
				'dependency' => array( $invoice_reminder2_prefix . 'enable', '==', 'true' ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Reminder 3 Day Unpaid Invoice', 'wds-notrans' ),
			),
			array(
				'id'      => $invoice_reminder3_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_3' ),
			),
			array(
				'id'         => $invoice_reminder3_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_3_subject', $invoice_reminder3_subject ),
				'dependency' => array( $invoice_reminder3_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_reminder3_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_3_body', $invoice_reminder3_content ),
				'dependency' => array( $invoice_reminder3_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_reminder3_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_invoice_unpaid_reminder_3_whatsapp', $invoice_reminder3_content ),
				'dependency' => array( $invoice_reminder3_prefix . 'enable', '==', 'true' ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Invoice Cancelled', 'wds-notrans' ),
			),
			array(
				'id'      => $invoice_cancelled_prefix . 'enable',
				'type'    => 'switcher',
				'title'   => __( 'Enable Notifications', 'wds-notrans' ),
				'default' => wds_v1_option( 'notification_email_customer_invoice_cancelled' ),
			),
			array(
				'id'         => $invoice_cancelled_prefix . 'subject',
				'type'       => 'text',
				'title'      => __( 'Email Subject', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_cancelled_subject', $invoice_cancelled_subject ),
				'dependency' => array( $invoice_cancelled_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_cancelled_prefix . 'body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Content', 'wds-notrans' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'notification_email_customer_invoice_cancelled_body', $invoice_cancelled_content ),
				'dependency' => array( $invoice_cancelled_prefix . 'enable', '==', 'true' ),
			),
			array(
				'id'         => $invoice_cancelled_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_invoice_cancelled_whatsapp', $invoice_cancelled_content ),
				'dependency' => array( $invoice_cancelled_prefix . 'enable', '==', 'true' ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Invoice Completed', 'wds-notrans' ),
			),
			array(
				'id'       => $invoice_completed_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_invoice_completed_subject', $invoice_completed_subject ),
			),
			array(
				'id'       => $invoice_completed_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_invoice_completed_body', $invoice_completed_content ),
			),
			array(
				'id'         => $invoice_completed_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_invoice_completed_whatsapp', $invoice_completed_content ),
			),
		),
	)
);
