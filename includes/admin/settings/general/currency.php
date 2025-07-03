<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'general',
		'title'  => __( 'Currency', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'currency',
				'type'    => 'select',
				'title'   => __( 'Mata Uang Default', 'weddingsaas' ),
				'options' => wds_currencies(),
				'default' => wds_v1_option( 'currency', 'IDR' ),
			),
			array(
				'id'         => 'currency_position',
				'type'       => 'select',
				'title'      => __( 'Posisi Mata Uang', 'weddingsaas' ),
				'options'    => array(
					'left'  => __( 'Kiri', 'weddingsaas' ),
					'right' => __( 'Kanan', 'weddingsaas' ),
				),
				'attributes' => array( 'disabled' => 'disabled' ),
				'default'    => wds_v1_option( 'currency_position', 'left' ),
			),
			array(
				'id'      => 'thousand_separator',
				'type'    => 'text',
				'title'   => __( 'Pemisah Ribuan', 'weddingsaas' ),
				'default' => wds_v1_option( 'thousand_separator', '.' ),
			),
			array(
				'id'      => 'decimal_separator',
				'type'    => 'text',
				'title'   => __( 'Pemisah Desimal', 'weddingsaas' ),
				'default' => wds_v1_option( 'decimal_separator', ',' ),
			),
			array(
				'id'      => 'number_of_decimal',
				'type'    => 'number',
				'title'   => __( 'Jumlah Angka Desimal', 'weddingsaas' ),
				'default' => wds_v1_option( 'number_of_decimal', '0' ),
			),
		),
	)
);
