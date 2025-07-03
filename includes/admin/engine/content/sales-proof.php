<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'content',
		'title'  => __( 'Sales Proof', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'sp',
				'type'    => 'switcher',
				'title'   => __( 'Activate', 'wds-notrans' ),
				'desc'    => __( 'Sales proof ditampilkan di halaman checkout dan halaman yang Anda pilih.', 'weddingsaas' ),
				'default' => wds_v1_option( 'sales_proof_enable' ),
			),
			array(
				'id'         => 'sp_image',
				'type'       => 'upload',
				'library'    => 'image',
				'preview'    => true,
				'title'      => __( 'Image', 'wds-notran' ),
				'desc'       => __( 'Ukuran yang disarankan adalah 250 X 250 piksel.', 'weddingsaas' ),
				'default'    => wds_v1_option( 'sales_proof_image' ),
				'dependency' => array( 'sp', '==', 'true' ),
			),
			array(
				'id'          => 'sp_text1',
				'type'        => 'text',
				'title'       => __( 'Text 1', 'wds-notrans' ),
				'placeholder' => 'Just purchased',
				'default'     => wds_v1_option( 'sales_proof_text1', 'Just purchased' ),
				'dependency'  => array( 'sp', '==', 'true' ),
			),
			array(
				'id'          => 'sp_text2',
				'type'        => 'text',
				'title'       => __( 'Text 2', 'wds-notrans' ),
				'placeholder' => 'Verified by',
				'default'     => wds_v1_option( 'sales_proof_text2', 'Verified by' ),
				'dependency'  => array( 'sp', '==', 'true' ),
			),
			array(
				'id'         => 'sp_position',
				'type'       => 'select',
				'title'      => __( 'Position', 'wds-notrans' ),
				'options'    => array(
					'tl' => __( 'Top Left', 'wds-notrans' ),
					'tr' => __( 'Top Right', 'wds-notrans' ),
					'bl' => __( 'Bottom Left', 'wds-notrans' ),
					'br' => __( 'Bottom Right', 'wds-notrans' ),
				),
				'default'    => wds_v1_option( 'sales_proof_position', 'bl' ),
				'dependency' => array( 'sp', '==', 'true' ),
			),
			array(
				'id'         => 'sp_delay',
				'type'       => 'number',
				'title'      => __( 'Delay', 'wds-notrans' ),
				'desc'       => __( 'Muncul penundaan antar notifikasi (detik).', 'weddingsaas' ),
				'default'    => wds_v1_option( 'sales_proof_delay', 5 ),
				'dependency' => array( 'sp', '==', 'true' ),
			),
			array(
				'id'         => 'sp_time',
				'type'       => 'number',
				'title'      => __( 'Time', 'wds-notrans' ),
				'desc'       => __( 'Berapa lama notifikasi muncul (detik).', 'weddingsaas' ),
				'default'    => wds_v1_option( 'sales_proof_time', 2 ),
				'dependency' => array( 'sp', '==', 'true' ),
			),
			array(
				'id'          => 'sp_page',
				'type'        => 'select',
				'title'       => __( 'Pages', 'wds-notrans' ),
				'desc'        => __( 'Pilih halaman yang ingin Anda tampilkan sales proof.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih halaman', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => 'all_page',
				'default'     => wds_v1_option( 'sales_proof_page' ),
				'dependency'  => array( 'sp', '==', 'true' ),
			),
			array(
				'id'         => 'sp_cache',
				'type'       => 'switcher',
				'title'      => __( 'Query Cache', 'wds-notrans' ),
				'desc'       => __( 'Data akan diperbarui setiap jam.', 'weddingsaas' ),
				'dependency' => array( 'sp', '==', 'true' ),
			),
		),
	)
);
