<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Marketing Kit', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'marketing_type',
				'type'    => 'select',
				'title'   => __( 'Menu Type', 'wds-notrans' ),
				'options' => array(
					''     => __( 'Default', 'wds-notrans' ),
					'url'  => __( 'Custome', 'wds-notrans' ),
					'hide' => __( 'Hide', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'marketing_menu_conditional' ),
			),
			array(
				'id'          => 'marketing_link',
				'type'        => 'text',
				'title'       => __( 'Menu Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'marketing_menu_link', wds_url( 'marketing' ) ),
				'dependency'  => array( 'marketing_type', '==', 'url' ),
			),
			array(
				'id'         => 'marketing_data',
				'type'       => 'group',
				'title'      => __( 'Data', 'wds-notrans' ),
				'fields'     => array(
					array(
						'id'       => 'title',
						'type'     => 'text',
						'title'    => __( 'Title', 'wds-notrans' ),
						'sanitize' => false,
					),
					array(
						'id'      => 'icon',
						'type'    => 'upload',
						'library' => 'image',
						'preview' => true,
						'title'   => __( 'Icon', 'wds-notrans' ),
						'desc'    => __( 'Ukuran yang disarankan adalah 64 X 64 piksel.', 'weddingsaas' ),
					),
					array(
						'id'       => 'desc',
						'type'     => 'textarea',
						'title'    => __( 'Description', 'wds-notrans' ),
						'sanitize' => false,
					),
					array(
						'id'         => 'url',
						'type'       => 'text',
						'title'      => __( 'Link Access', 'wds-notrans' ),
						'attributes' => array( 'placeholder' => 'Ex. https://drive.google.com/xxxxx' ),
					),
				),
				'default'    => wds_v1_marketingkit(),
				'dependency' => array( 'marketing_type', '==', '' ),
			),
		),
	)
);
