<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$data = get_option( 'wds_taxonomy_data', array() );

$prefix1 = WDS_SLUG . '_tax1';

CSF::createTaxonomyOptions(
	$prefix1,
	array(
		'taxonomy'  => 'category',
		'data_type' => 'unserialize',
		'class'     => 'wds-tax',
	)
);

CSF::createSection(
	$prefix1,
	array(
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Taxonomy', 'wds-notrans' ),
			),
			array(
				'id'          => '_template',
				'type'        => 'select',
				'title'       => __( 'Pilih Taksonomi', 'weddingsaas' ),
				'placeholder' => __( 'Pilih taksonomi', 'weddingsaas' ),
				'chosen'      => true,
				'options'     => wds_sanitize_data_field( $data, 'template', array() ),
			),
			array(
				'id'      => '_icon_option',
				'type'    => 'radio',
				'title'   => __( 'Opsi Ikon', 'weddingsaas' ),
				'options' => array(
					'media' => __( 'Media Library', 'wds-notrans' ),
					'url'   => __( 'URL', 'wds-notrans' ),
				),
				'default' => 'media',
			),
			array(
				'id'         => '_icon',
				'type'       => 'upload',
				'library'    => 'image',
				'preview'    => true,
				'title'      => __( 'Ikon', 'weddingsaas' ),
				'desc'       => __( 'Ukuran yang disarankan adalah 64 X 64 piksel.', 'weddingsaas' ),
				'dependency' => array( '_icon_option', '==', 'media' ),
			),
			array(
				'id'         => '_custom_icon',
				'type'       => 'text',
				'title'      => __( 'Kustom Ikon', 'weddingsaas' ),
				'desc'       => __( 'Ukuran yang disarankan adalah 64 X 64 piksel.', 'weddingsaas' ),
				'dependency' => array( '_icon_option', '==', 'url' ),
			),
		),
	)
);

$prefix2 = WDS_SLUG . '_tax2';

CSF::createTaxonomyOptions(
	$prefix2,
	array(
		'taxonomy'  => wds_sanitize_data_field( $data, 'membership' ),
		'data_type' => 'unserialize',
		'class'     => 'wds-tax',
	)
);

CSF::createSection(
	$prefix2,
	array(
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Membership', 'wds-notrans' ),
			),
			array(
				'id'          => '_membership',
				'type'        => 'select',
				'title'       => __( 'Pilih Produk', 'weddingsaas' ),
				'desc'        => __( 'Jika tidak dipilih, maka akan ditampilkan ke semua pengguna.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_product_restrict(),
			),
		),
	)
);

$prefix3 = WDS_SLUG . '_tax3';

CSF::createTaxonomyOptions(
	$prefix3,
	array(
		'taxonomy'  => wds_sanitize_data_field( $data, 'config' ),
		'data_type' => 'unserialize',
		'class'     => 'wds-tax',
	)
);

CSF::createSection(
	$prefix3,
	array(
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Configuration', 'wds-notrans' ),
			),
			array(
				'id'      => '_thumbnail_option',
				'type'    => 'radio',
				'title'   => __( 'Opsi Thumbnail', 'weddingsaas' ),
				'options' => array(
					'media' => __( 'Media Library', 'wds-notrans' ),
					'url'   => __( 'URL', 'wds-notrans' ),
				),
				'default' => 'media',
			),
			array(
				'id'         => '_thumbnail',
				'type'       => 'upload',
				'library'    => 'image',
				'preview'    => true,
				'title'      => __( 'Thumbnail', 'weddingsaas' ),
				'dependency' => array( '_thumbnail_option', '==', 'media' ),
			),
			array(
				'id'          => '_custom_thumbnail',
				'type'        => 'text',
				'title'       => __( 'Thumbnail URL', 'weddingsaas' ),
				'placeholder' => 'Ex. https://file.domain.com/img/theme1.jpg',
				'dependency'  => array( '_thumbnail_option', '==', 'url' ),
			),
			array(
				'id'    => '_preview',
				'type'  => 'text',
				'title' => __( 'URL Preview', 'wds-notrans' ),
				'desc'  => __( 'Jika Anda menggunakan Tema WDS, biarkan kosong, karena ini otomatis.', 'weddingsaas' ),
			),
		),
	)
);

if ( wds_is_theme() ) {
	$prefix4 = WDS_SLUG . '_tax4';

	CSF::createTaxonomyOptions(
		$prefix4,
		array(
			'taxonomy'  => wds_theme_default_taxonomy(),
			'data_type' => 'unserialize',
			'class'     => 'wds-tax',
		)
	);

	CSF::createSection(
		$prefix4,
		array(
			'fields' => array(
				array(
					'type'  => 'heading',
					'title' => __( 'Configuration', 'wds-notrans' ),
				),
				array(
					'id'          => '_theme',
					'type'        => 'select',
					'title'       => __( 'Pilih Tema', 'weddingsaas' ),
					'placeholder' => __( 'Pilih tema', 'weddingsaas' ),
					'chosen'      => true,
					'options'     => 'page',
					'query_args'  => array(
						'post_type'      => 'wds_template',
						'posts_per_page' => -1,
					),
				),
			),
		)
	);
}
