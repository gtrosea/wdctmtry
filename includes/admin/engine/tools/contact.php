<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'tools',
		'title'  => __( 'Contact', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Mailketing', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => __( 'To save Complete Order data based on user group to <a href="https://weddingsaas.id/go/mailketing" target="_blank">Mailketing List</a>.', 'wds-notrans' ),
			),
			array(
				'id'      => 'mailketing',
				'type'    => 'switcher',
				'title'   => __( 'Activate', 'wds-notrans' ),
				'default' => wds_v1_option( 'wds_mailketing' ),
			),
			array(
				'id'         => 'mailketing_api',
				'type'       => 'text',
				'title'      => __( 'API Token', 'wds-notrans' ),
				'desc'       => 'Menu : Integrations => Email Application API',
				'default'    => wds_v1_option( 'wds_mailketing_api' ),
				'dependency' => array( 'mailketing', '==', 'true' ),
			),
			array(
				'id'         => 'mailketing_trial',
				'type'       => 'select',
				'title'      => __( 'Trial', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::mailketing_list(),
				'default'    => wds_v1_option( 'wds_mailketing_trial' ),
				'dependency' => array( 'mailketing', '==', 'true' ),
			),
			array(
				'id'         => 'mailketing_member',
				'type'       => 'select',
				'title'      => __( 'Member', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::mailketing_list(),
				'default'    => wds_v1_option( 'wds_mailketing_member' ),
				'dependency' => array( 'mailketing', '==', 'true' ),
			),
			array(
				'id'         => 'mailketing_reseller',
				'type'       => 'select',
				'title'      => __( 'Reseller', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::mailketing_list(),
				'default'    => wds_v1_option( 'wds_mailketing_reseller' ),
				'dependency' => array( 'mailketing', '==', 'true' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Starsender', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => __( 'To save Complete Order data based on user group to <a href="https://weddingsaas.id/go/strsndr" target="_blank">Starsender Group</a>.', 'wds-notrans' ),
			),
			array(
				'id'      => 'starsender',
				'type'    => 'switcher',
				'title'   => __( 'Activate', 'wds-notrans' ),
				'default' => wds_v1_option( 'wds_starsender' ),
			),
			array(
				'id'         => 'starsender_api',
				'type'       => 'text',
				'title'      => __( 'Account API Key', 'wds-notrans' ),
				'desc'       => __( 'Anda bisa dapatkan api key di menu profile.', 'weddingsaas' ),
				'default'    => wds_v1_option( 'wds_starsender_api' ),
				'dependency' => array( 'starsender', '==', 'true' ),
			),
			array(
				'id'         => 'starsender_trial',
				'type'       => 'select',
				'title'      => __( 'Trial', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::starsender_group(),
				'default'    => wds_v1_option( 'wds_starsender_trial' ),
				'dependency' => array( 'starsender', '==', 'true' ),
			),
			array(
				'id'         => 'starsender_member',
				'type'       => 'select',
				'title'      => __( 'Member', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::starsender_group(),
				'default'    => wds_v1_option( 'wds_starsender_member' ),
				'dependency' => array( 'starsender', '==', 'true' ),
			),
			array(
				'id'         => 'starsender_reseller',
				'type'       => 'select',
				'title'      => __( 'Reseller', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::starsender_group(),
				'default'    => wds_v1_option( 'wds_starsender_reseller' ),
				'dependency' => array( 'starsender', '==', 'true' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Sendy', 'wds-notrans' ),
			),
			array(
				'type'    => 'content',
				'content' => __( 'To save Complete Order data based on user group to <a href="https://sendy.co" target="_blank">Sendy</a>.', 'wds-notrans' ),
			),
			array(
				'id'      => 'sendy',
				'type'    => 'switcher',
				'title'   => __( 'Activate', 'wds-notrans' ),
				'default' => wds_v1_option( 'wds_sendy' ),
			),
			array(
				'id'          => 'sendy_url',
				'type'        => 'text',
				'title'       => __( 'Installation URL', 'wds-notrans' ),
				'placeholder' => 'Ex. https://domain.com',
				'default'     => wds_v1_option( 'wds_sendy_url' ),
				'dependency'  => array( 'sendy', '==', 'true' ),
			),
			array(
				'id'         => 'sendy_api',
				'type'       => 'text',
				'title'      => __( 'API Key', 'wds-notrans' ),
				'desc'       => __( 'Kunci API Anda, tersedia di pengaturan.', 'weddingsaas' ),
				'default'    => wds_v1_option( 'wds_sendy_api' ),
				'dependency' => array( 'sendy', '==', 'true' ),
			),
			array(
				'id'         => 'sendy_brand_id',
				'type'       => 'text',
				'title'      => __( 'Brand ID', 'wds-notrans' ),
				'desc'       => __( 'Brand ID yang ingin Anda dapatkan daftarnya. Brand ID dapat ditemukan di bawah halaman "Brand" yang bernama ID.', 'weddingsaas' ),
				'default'    => wds_v1_option( 'wds_sendy_brand_id' ),
				'dependency' => array( 'sendy', '==', 'true' ),
			),
			array(
				'id'         => 'sendy_trial',
				'type'       => 'select',
				'title'      => __( 'Trial', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::sendy_list(),
				'default'    => wds_v1_option( 'wds_sendy_trial' ),
				'dependency' => array( 'sendy', '==', 'true' ),
			),
			array(
				'id'         => 'sendy_member',
				'type'       => 'select',
				'title'      => __( 'Member', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::sendy_list(),
				'default'    => wds_v1_option( 'wds_sendy_member' ),
				'dependency' => array( 'sendy', '==', 'true' ),
			),
			array(
				'id'         => 'sendy_reseller',
				'type'       => 'select',
				'title'      => __( 'Reseller', 'wds-notrans' ),
				'options'    => WDS\Engine\Tools\Contact::sendy_list(),
				'default'    => wds_v1_option( 'wds_sendy_reseller' ),
				'dependency' => array( 'sendy', '==', 'true' ),
			),
		),
	)
);
