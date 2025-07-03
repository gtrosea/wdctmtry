<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'shortcode',
		'title'  => __( 'Audio', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'notice',
				'style'   => 'info',
				'content' => 'Shortcode: <code>[wds_audio]</code>',
			),
			array(
				'type'    => 'heading',
				'content' => 'Default',
			),
			array(
				'id'      => 'audio',
				'type'    => 'radio',
				'title'   => __( 'Type Audio', 'wds-notrans' ),
				'options' => array(
					'default' => 'Default',
					'cct'     => 'CCT JetEngine',
				),
				'default' => 'default',
			),
			array(
				'id'    => 'audio_time_default',
				'type'  => 'switcher',
				'title' => __( 'Aktifkan custom waktu', 'wds-notrans' ),
			),
			// array(
			// 'id'    => 'audio_autoplay',
			// 'type'  => 'switcher',
			// 'title' => __( 'Auto Play', 'wds-notrans' ),
			// ),
			array(
				'id'         => 'audio_cct',
				'type'       => 'fieldset',
				'fields'     => array(
					array(
						'id'      => 'slug',
						'type'    => 'text',
						'title'   => __( 'CCT Slug', 'wds-notrans' ),
						'default' => 'audio',
					),
					array(
						'id'      => 'name',
						'type'    => 'text',
						'title'   => __( 'Field Name', 'wds-notrans' ),
						'default' => 'nama',
					),
					array(
						'id'      => 'url',
						'type'    => 'text',
						'title'   => __( 'Field URL', 'wds-notrans' ),
						'default' => 'link',
					),
				),
				'dependency' => array( 'audio', '==', 'cct' ),
			),
			array(
				'id'     => 'audio_note',
				'type'   => 'wp_editor',
				'title'  => __( 'Note', 'wds-notrans' ),
				'height' => '100px',
			),
			array(
				'type'    => 'heading',
				'content' => 'Youtube',
			),
			array(
				'id'    => 'audio_youtube',
				'type'  => 'switcher',
				'title' => __( 'Audio Youtube', 'wds-notrans' ),
			),
			array(
				'id'         => 'audio_youtube_note',
				'type'       => 'wp_editor',
				'title'      => __( 'Audio Youtube Note', 'wds-notrans' ),
				'default'    => __( '<b>Note:</b> Audio Youtube tidak berjalan di browser media sosial seperti instagram, facebook dan telegram.', 'weddingsaas' ),
				'height'     => '100px',
				'dependency' => array( 'audio_youtube', '==', 'true' ),
			),
			array(
				'type'    => 'heading',
				'content' => 'Custom',
			),
			array(
				'id'    => 'audio_custom',
				'type'  => 'switcher',
				'title' => __( 'Custom Audio', 'wds-notrans' ),
			),
			array(
				'id'    => 'audio_time_custom',
				'type'  => 'switcher',
				'title' => __( 'Aktifkan custom waktu', 'wds-notrans' ),
			),
			array(
				'id'         => 'audio_custom_max',
				'type'       => 'number',
				'title'      => __( 'Max Upload (KB)', 'wds-notrans' ),
				'default'    => '5000',
				'dependency' => array( 'audio_custom', '==', 'true' ),
			),
			array(
				'id'         => 'audio_custom_note',
				'type'       => 'wp_editor',
				'title'      => __( 'Custom Audio Note', 'wds-notrans' ),
				'default'    => __( 'Upload file audio mp3 sesuai yang Anda inginkan (Maksimal 5 MB).', 'weddingsaas' ),
				'height'     => '100px',
				'dependency' => array( 'audio_custom', '==', 'true' ),
			),
		),
	)
);
