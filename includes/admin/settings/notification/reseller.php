<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$_client = array(
	array(
		'type'    => 'heading',
		'content' => __( 'Client Registration', 'wds-notrans' ),
	),
	array(
		'id'      => $reseller_client_register_prefix . 'enable',
		'type'    => 'switcher',
		'title'   => __( 'Enable Notifications', 'wds-notrans' ),
		'default' => wds_v1_option( 'notification_reseller_client_registration_enable' ),
	),
	array(
		'type'       => 'content',
		'content'    => $reseller_client_register_sc,
		'dependency' => array( $reseller_client_register_prefix . 'enable', '==', 'true' ),
	),
	array(
		'id'         => $reseller_client_register_prefix . 'subject',
		'type'       => 'text',
		'title'      => __( 'Email Subject', 'wds-notrans' ),
		'sanitize'   => false,
		'default'    => wds_v1_option( 'notification_email_reseller_client_register_subject', $reseller_client_register_subject ),
		'dependency' => array( $reseller_client_register_prefix . 'enable', '==', 'true' ),
	),
	array(
		'id'         => $reseller_client_register_prefix . 'body',
		'type'       => 'wp_editor',
		'title'      => __( 'Email Content', 'wds-notrans' ),
		'sanitize'   => false,
		'default'    => wds_v1_option( 'notification_email_reseller_client_register_body', $reseller_client_register_content ),
		'dependency' => array( $reseller_client_register_prefix . 'enable', '==', 'true' ),
	),
	array(
		'id'         => $reseller_client_register_prefix . 'whatsapp',
		'type'       => 'textarea',
		'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
		'sanitize'   => false,
		'attributes' => array( 'rows' => 10 ),
		'default'    => wds_v1_option( 'notification_email_reseller_client_register_whatsapp', $reseller_client_register_content ),
		'dependency' => array( $reseller_client_register_prefix . 'enable', '==', 'true' ),
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
			'type'    => 'content',
			'content' => $reseller_domain_sc,
		),
		array(
			'id'       => $reseller_domain_pending_prefix . 'subject',
			'type'     => 'text',
			'title'    => __( 'Email Subject', 'wds-notrans' ),
			'sanitize' => false,
			'default'  => wds_v1_option( 'notification_email_reseller_domain_pending_subject', $reseller_domain_pending_subject ),
		),
		array(
			'id'       => $reseller_domain_pending_prefix . 'body',
			'type'     => 'wp_editor',
			'title'    => __( 'Email Content', 'wds-notrans' ),
			'sanitize' => false,
			'default'  => wds_v1_option( 'notification_email_reseller_domain_pending_body', $reseller_domain_pending_content ),
		),
		array(
			'id'         => $reseller_domain_pending_prefix . 'whatsapp',
			'type'       => 'textarea',
			'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
			'sanitize'   => false,
			'attributes' => array( 'rows' => 10 ),
			'default'    => wds_v1_option( 'notification_email_reseller_domain_pending_whatsapp', $reseller_domain_pending_content ),
		),

		array(
			'type'    => 'heading',
			'content' => __( 'Custome Domain Active', 'wds-notrans' ),
		),
		array(
			'type'    => 'content',
			'content' => $reseller_domain_sc,
		),
		array(
			'id'       => $reseller_domain_active_prefix . 'subject',
			'type'     => 'text',
			'title'    => __( 'Email Subject', 'wds-notrans' ),
			'sanitize' => false,
			'default'  => wds_v1_option( 'notification_email_reseller_domain_active_subject', $reseller_domain_active_subject ),
		),
		array(
			'id'       => $reseller_domain_active_prefix . 'body',
			'type'     => 'wp_editor',
			'title'    => __( 'Email Content', 'wds-notrans' ),
			'sanitize' => false,
			'default'  => wds_v1_option( 'notification_email_reseller_domain_active_body', $reseller_domain_active_content ),
		),
		array(
			'id'         => $reseller_domain_active_prefix . 'whatsapp',
			'type'       => 'textarea',
			'title'      => __( 'WhatsApp Content', 'wds-notrans' ),
			'sanitize'   => false,
			'attributes' => array( 'rows' => 10 ),
			'default'    => wds_v1_option( 'notification_email_reseller_domain_active_whatsapp', $reseller_domain_active_content ),
		),
	);
}

$reseller_field = array_merge( $_client, $_domain );

CSF::createSection(
	$prefix,
	array(
		'parent' => 'notification',
		'title'  => __( 'Reseller', 'wds-notrans' ),
		'fields' => $reseller_field,
	)
);
