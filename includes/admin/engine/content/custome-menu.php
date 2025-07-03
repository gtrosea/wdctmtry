<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'content',
		'title'  => __( 'Custome Menu', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'           => 'menu',
				'type'         => 'group',
				'title'        => '',
				'button_title' => __( 'Add Menu', 'wds-notrans' ),
				'fields'       => array(
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => __( 'Title', 'wds-notrans' ),
					),
					array(
						'id'    => 'icon',
						'type'  => 'text',
						'title' => __( 'Icon', 'wds-notrans' ),
						'desc'  => __( 'Anda dapat menggunakan ikon <a href="https://icons.getbootstrap.com" target="_blank">DI SINI</a>, masukkan tanpa "bi bi-".', 'weddingsaas' ),
					),
					array(
						'id'    => 'url',
						'type'  => 'text',
						'title' => __( 'URL', 'wds-notrans' ),
					),
					array(
						'id'    => 'new_tab',
						'type'  => 'switcher',
						'title' => __( 'Open link in new tab', 'wds-notrans' ),
					),
					array(
						'id'          => 'group',
						'type'        => 'select',
						'title'       => __( 'Group', 'wds-notrans' ),
						'desc'        => __( 'Anda bisa membatasi berdasarkan user group. Kosongkan jika tidak ingin dibatasi.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih group', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => wds_list_user_group(),
					),
					array(
						'id'          => 'product',
						'type'        => 'select',
						'title'       => __( 'Products', 'wds-notrans' ),
						'desc'        => __( 'Anda bisa membatasi berdasarkan membership. Kosongkan jika tidak ingin dibatasi.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih product', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => wds_get_product_restrict(),
					),
				),
				'default'      => wds_v1_engine_menu(),
			),
		),
	)
);
