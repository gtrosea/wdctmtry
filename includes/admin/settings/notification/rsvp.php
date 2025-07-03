<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'RSVP', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'heading',
				'content' => __( 'Comment', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => $rsvp_sc,
			),
			array(
				'id'       => $rsvp_prefix . 'subject',
				'type'     => 'text',
				'title'    => __( 'Email Subject', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_comment_subject', $rsvp_subject ),
			),
			array(
				'id'       => $rsvp_prefix . 'body',
				'type'     => 'wp_editor',
				'title'    => __( 'Email Content', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'notification_email_customer_comment_body', $rsvp_content ),
			),
			array(
				'id'         => $rsvp_prefix . 'whatsapp',
				'type'       => 'textarea',
				'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
				'sanitize'   => false,
				'attributes' => array( 'rows' => 10 ),
				'default'    => wds_v1_option( 'notification_email_customer_comment_whatsapp', $rsvp_content ),
			),
		),
	)
);
