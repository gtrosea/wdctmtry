<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'Affiliate', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'heading',
				'content' => __( 'New Sales', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => $affiliate_sc,
			),
			array(
				'id'       => $affiliate_new_sales_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_affiliate_new_sales_subject', $affiliate_new_sales_subject ),
			),
			array(
				'id'       => $affiliate_new_sales_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_affiliate_new_sales_body', $affiliate_new_sales_content ),
			),
			array(
				'id'         => $affiliate_new_sales_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_affiliate_new_sales_whatsapp', $affiliate_new_sales_content ),
			),

			array(
				'type'    => 'heading',
				'content' => __( 'Commission Paid', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => $affiliate_sc,
			),
			array(
				'id'       => $affiliate_commission_paid_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_affiliate_commission_paid_subject', $affiliate_commission_paid_subject ),
			),
			array(
				'id'       => $affiliate_commission_paid_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_affiliate_commission_paid_body', $affiliate_commission_paid_content ),
			),
			array(
				'id'         => $affiliate_commission_paid_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_affiliate_commission_paid_whatsapp', $affiliate_commission_paid_content ),
			),
		),
	)
);
