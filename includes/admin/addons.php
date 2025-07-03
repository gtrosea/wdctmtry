<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$prefix = WDS_SLUG . '_addons';

CSF::createOptions(
	$prefix,
	array(
		'menu_title'         => 'Addons',
		'menu_slug'          => 'weddingsaas-addon',
		'menu_type'          => 'menu',
		'menu_capability'    => 'manage_options',
		'theme'              => 'light',
		'menu_hidden'        => true,
		'show_bar_menu'      => false,
		'show_search'        => false,
		'show_reset_section' => false,
		'sticky_header'      => false,
		'framework_title'    => wp_kses_post( 'WDS Addons' ),
		'footer_text'        => wp_kses_post( 'The Plugin will Created By <a href="https://pelatform.com" target="_blank">Pelatform Dev</a>' ),
	)
);

$placeholder_digital = wds_is_digital() ? __( 'Pilih produk', 'weddingsaas' ) : __( 'Addon Plugin Belum Aktif', 'weddingsaas' );

CSF::createSection(
	$prefix,
	array(
		'fields' => array(
			array(
				'type'    => 'content',
				'title'   => '',
				'content' => __( 'Untuk menampilkan addon di halaman checkout, pilih addon di halaman edit tiap produk.<br><a href="https://docs.pelatform.com/doc/5-addon-uCU3SQci2Y" target="_blank">Lihat tutorial disini.</a>', 'weddingsaas' ),
			),
			array(
				'id'    => 'fixed',
				'type'  => 'switcher',
				'title' => __( 'Fixed Price', 'wds-notrans' ),
				'desc'  => __( 'Jika di aktifkan, harga addon tidak akan dikurangi dengan diskon saat checkout.', 'weddingsaas' ),
			),
			array(
				'id'      => 'data',
				'type'    => 'group',
				'title'   => __( 'Data Addon', 'weddingsaas' ),
				'fields'  => array(
					array(
						'id'         => 'title',
						'type'       => 'text',
						'title'      => __( 'Judul', 'weddingsaas' ),
						'attributes' => array( 'placeholder' => 'Ex. Video Undangan' ),
					),
					array(
						'id'         => 'id',
						'type'       => 'text',
						'title'      => __( 'Kode', 'weddingsaas' ),
						'attributes' => array( 'placeholder' => 'Ex. VID' ),
					),
					array(
						'id'         => 'price',
						'type'       => 'text',
						'title'      => __( 'Harga', 'weddingsaas' ),
						'attributes' => array( 'placeholder' => 'Ex. 99999' ),
					),
					array(
						'id'         => 'link',
						'type'       => 'text',
						'title'      => __( 'Link Produk', 'weddingsaas' ),
						'attributes' => array( 'placeholder' => 'Ex. https://domain.com/video-undangan' ),
					),
					array(
						'id'          => 'product_id',
						'type'        => 'select',
						'title'       => __( 'Hubungkan Produk Digital', 'weddingsaas' ),
						'placeholder' => $placeholder_digital,
						'options'     => wds_get_product_digital(),
					),
				),
				'default' => wds_v1_addon(),
			),
			array(
				'title' => __( 'Ekspor Impor Data', 'weddingsaas' ),
				'type'  => 'backup',
			),
		),
	)
);
