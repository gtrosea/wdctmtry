<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$_invoice_complete = array(
	array(
		'type'    => 'heading',
		'content' => __( 'Settings', 'wds-notrans' ),
	),
	array(
		'id'      => $admin_prefix . 'email',
		'type'    => 'text',
		'title'   => __( 'Admin Email Address', 'wds-notrans' ),
		'default' => wds_v1_option( 'notification_email_admin_invoice_completed_address', $admin_email ),
	),
	array(
		'id'      => $admin_prefix . 'whatsapp',
		'type'    => 'text',
		'title'   => __( 'Admin WhatsApp Number', 'wds-notrans' ),
		'default' => wds_v1_option( 'notification_email_admin_invoice_completed_whatsapp_number' ),
	),

	array(
		'type'    => 'heading',
		'content' => __( 'Invoice Completed', 'wds-notrans' ),
	),
	array(
		'id'      => $admin_invoice_completed_prefix . 'enable',
		'type'    => 'switcher',
		'title'   => __( 'Enable Notifications', 'wds-notrans' ),
		'default' => wds_v1_option( 'notification_email_admin_invoice_completed_conditional' ),
	),
	array(
		'type'       => 'content',
		'content'    => $shortcode,
		'dependency' => array( $admin_invoice_completed_prefix . 'enable', '==', 'true' ),
	),
	array(
		'id'         => $admin_invoice_completed_prefix . 'subject',
		'type'       => 'text',
		'title'      => __( 'Email Subject', 'wds-notrans' ),
		'sanitize'   => false,
		'default'    => wds_v1_option( 'notification_email_admin_invoice_completed_subject', $admin_invoice_completed_subject ),
		'dependency' => array( $admin_invoice_completed_prefix . 'enable', '==', 'true' ),
	),
	array(
		'id'         => $admin_invoice_completed_prefix . 'body',
		'type'       => 'wp_editor',
		'title'      => __( 'Email Content', 'wds-notrans' ),
		'sanitize'   => false,
		'default'    => wds_v1_option( 'notification_email_admin_invoice_completed_body', $admin_invoice_completed_content ),
		'dependency' => array( $admin_invoice_completed_prefix . 'enable', '==', 'true' ),
	),
	array(
		'id'         => $admin_invoice_completed_prefix . 'whatsapp',
		'type'       => 'textarea',
		'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
		'sanitize'   => false,
		'attributes' => array( 'rows' => 10 ),
		'default'    => wds_v1_option( 'notification_email_admin_invoice_completed_whatsapp', $admin_invoice_completed_content ),
		'dependency' => array( $admin_invoice_completed_prefix . 'enable', '==', 'true' ),
	),
);

$_domain = array();
if ( wds_is_replica() ) {
	$_domain = array(
		array(
			'type'    => 'heading',
			'content' => __( 'Custome Domain Pending', 'wds-notrans' ),
		),
		array(
			'id'    => $admin_domain_pending_prefix . 'enable',
			'type'  => 'switcher',
			'title' => __( 'Enable Notifications', 'wds-notrans' ),
		),
		array(
			'type'       => 'content',
			'content'    => $reseller_domain_sc,
			'dependency' => array( $admin_domain_pending_prefix . 'enable', '==', 'true' ),
		),
		array(
			'id'         => $admin_domain_pending_prefix . 'subject',
			'type'       => 'text',
			'title'      => __( 'Email Subject', 'wds-notrans' ),
			'sanitize'   => false,
			'default'    => $admin_domain_pending_subject,
			'dependency' => array( $admin_domain_pending_prefix . 'enable', '==', 'true' ),
		),
		array(
			'id'         => $admin_domain_pending_prefix . 'body',
			'type'       => 'wp_editor',
			'title'      => __( 'Email Content', 'wds-notrans' ),
			'sanitize'   => false,
			'default'    => $admin_domain_pending_content,
			'dependency' => array( $admin_domain_pending_prefix . 'enable', '==', 'true' ),
		),
		array(
			'id'         => $admin_domain_pending_prefix . 'whatsapp',
			'type'       => 'textarea',
			'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
			'sanitize'   => false,
			'attributes' => array( 'rows' => 10 ),
			'default'    => $admin_domain_pending_content,
			'dependency' => array( $admin_domain_pending_prefix . 'enable', '==', 'true' ),
		),
	);
}

$admin_field = array_merge( $_invoice_complete, $_domain );

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'Admin', 'wds-notrans' ),
		'fields' => $admin_field,
	)
);
