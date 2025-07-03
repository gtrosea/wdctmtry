<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Modules', 'wds-notrans' ),
		'icon'   => 'fa fa-list-ul',
		'fields' => array(
			array(
				'type'    => 'notice',
				'style'   => 'info',
				'content' => __( 'Setelah aktif/nonaktif module, silahkan simpan dan refresh.', 'weddingsaas' ),
			),
			array(
				'id'       => 'module_audio',
				'type'     => 'switcher',
				'title'    => __( 'Audio', 'wds-notrans' ),
				'subtitle' => __( 'Shortcode Form & CPT Audio', 'wds-notrans' ),
				'default'  => true,
			),
			array(
				'id'       => 'module_tema',
				'type'     => 'switcher',
				'title'    => __( 'Tema Undangan', 'wds-notrans' ),
				'subtitle' => __( 'Shortcode Form Invitation Theme', 'wds-notrans' ),
				'default'  => true,
			),
			array(
				'id'       => 'module_bank',
				'type'     => 'switcher',
				'title'    => __( 'Bank', 'wds-notrans' ),
				'subtitle' => __( 'CPT Bank', 'wds-notrans' ),
				'default'  => false,
			),
			array(
				'id'      => 'module_dynamic_visibility',
				'type'    => 'switcher',
				'title'   => __( 'Dynamic Visibility', 'wds-notrans' ),
				'default' => false,
			),
		),
	)
);
