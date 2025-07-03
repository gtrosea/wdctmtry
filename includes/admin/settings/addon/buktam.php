<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'addon',
		'title'  => __( 'Guest Book', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'General', 'wds-notrans' ),
			),
			array(
				'id'      => 'gb_welcome_bg',
				'type'    => 'upload',
				'library' => 'image',
				'preview' => true,
				'title'   => __( 'Default Welcome Background', 'wds-notrans' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Pusher', 'wds-notrans' ),
			),
			array(
				'id'    => 'pusher_app_id',
				'type'  => 'text',
				'title' => __( 'Pusher App ID', 'wds-notrans' ),
			),
			array(
				'id'    => 'pusher_key',
				'type'  => 'text',
				'title' => __( 'Pusher Key', 'wds-notrans' ),
			),
			array(
				'id'    => 'pusher_secret',
				'type'  => 'text',
				'title' => __( 'Pusher Secret', 'wds-notrans' ),
			),
			array(
				'id'    => 'pusher_cluster',
				'type'  => 'text',
				'title' => __( 'Pusher Cluster', 'wds-notrans' ),
			),
		),
	)
);
