<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$categories = get_categories( array( 'hide_empty' => false ) );

$category_v1        = array();
$category_v1['all'] = __( 'Semua Kategori', 'weddingsaas' );
foreach ( $categories as $category ) {
	$category_v1[ $category->term_id ] = $category->name;
}

$category_options = array();
foreach ( $categories as $category ) {
	$parent_category = get_term( $category->parent, 'category' );
	$parent_name     = ( $parent_category && ! is_wp_error( $parent_category ) ) ? $parent_category->name . ' | ' : '';

	$category_options[ $category->term_id ] = $parent_name . $category->name;
}

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Invitation Edit', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'invitation_edit_type',
				'type'    => 'select',
				'title'   => __( 'Menu Type', 'wds-notrans' ),
				'options' => array(
					''    => __( 'Default', 'wds-notrans' ),
					'url' => __( 'Custome', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'invitation_edit_conditional' ),
			),
			array(
				'id'          => 'invitation_edit_link',
				'type'        => 'text',
				'title'       => __( 'Menu Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'invitation_edit_link', wds_url( 'edit' ) . '?id=' ),
				'dependency'  => array( 'invitation_edit_type', '==', 'url' ),
			),
			array(
				'id'         => 'invitation_edit_layout',
				'type'       => 'select',
				'title'      => __( 'Layout Form', 'weddingsaas' ),
				'desc'       => __( 'Pilih versi layout untuk halaman edit undangan.', 'weddingsaas' ),
				'options'    => array(
					'1' => __( 'Versi 1', 'weddingsaas' ),
					'2' => __( 'Versi 2', 'weddingsaas' ),
				),
				'default'    => wds_v1_option( 'invitation_edit_version', '2' ),
				'dependency' => array( 'invitation_edit_type', '==', '' ),
			),
			array(
				'id'         => 'invitation_edit_v1',
				'type'       => 'repeater',
				'title'      => __( 'Shortcode V1', 'wds-notrans' ),
				'fields'     => array(
					array(
						'id'      => 'category',
						'type'    => 'select',
						'title'   => __( 'Kategori', 'weddingsaas' ),
						'options' => $category_v1,
					),
					array(
						'id'          => 'shortcode',
						'type'        => 'text',
						'title'       => __( 'Shortcode', 'wds-notrans' ),
						'desc'        => __( 'Support: JetFormBuilder.', 'wds-notrans' ),
						'placeholder' => '[jet_fb_form form_id="1" submit_type="reload" required_mark="*" fields_layout="column" enable_progress="" fields_label_tag="div" load_nonce="render" use_csrf=""]',
					),
				),
				'default'    => wds_v1_editv1(),
				'dependency' => array( 'invitation_edit_type|invitation_edit_layout', '==|==', '|1' ),
			),
			array(
				'id'         => 'invitation_edit_v2',
				'type'       => 'group',
				'title'      => __( 'Shortcode V2', 'wds-notrans' ),
				'fields'     => array(
					array(
						'id'    => 'note',
						'type'  => 'text',
						'title' => __( 'Catatan', 'weddingsaas' ),
						'desc'  => __( 'Kolom ini hanya tampil di dashboard admin.', 'weddingsaas' ),
					),
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => __( 'Judul', 'weddingsaas' ),
					),
					array(
						'id'      => 'icon',
						'type'    => 'upload',
						'library' => 'image',
						'preview' => true,
						'title'   => __( 'Ikon', 'weddingsaas' ),
						'desc'    => __( 'Ukuran yang disarankan adalah 64 X 64 piksel.', 'weddingsaas' ),
					),
					array(
						'id'          => 'shortcode',
						'type'        => 'text',
						'title'       => __( 'Shortcode', 'wds-notrans' ),
						'desc'        => __( 'Support: [wds_audio], [wds_tema] & JetFormBuilder.', 'wds-notrans' ),
						'placeholder' => '[jet_fb_form form_id="1" submit_type="reload" required_mark="*" fields_layout="column" enable_progress="" fields_label_tag="div" load_nonce="render" use_csrf=""]',
					),
					array(
						'type'  => 'subheading',
						'title' => __( 'Batasi Popup<br><span style="font-weight:400">Anda bisa membatasi popup ini berdasarkan kategori, sub tema dan membership.</span>', 'weddingsaas' ),
					),
					array(
						'id'          => 'category',
						'type'        => 'select',
						'title'       => __( 'Kategori', 'weddingsaas' ),
						'desc'        => __( 'Jika tidak dipilih, semua kategori akan ditampilkan kepada user.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih kategori', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => $category_options,
					),
					array(
						'id'          => 'subtheme',
						'type'        => 'select',
						'title'       => __( 'Sub Tema', 'weddingsaas' ),
						'desc'        => __( 'Jika tidak dipilih, semua tema akan ditampilkan kepada user.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih subtema', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => 'wds_get_subthemes',
					),
					array(
						'id'          => 'product',
						'type'        => 'select',
						'title'       => __( 'Produk', 'weddingsaas' ),
						'desc'        => __( 'Jika tidak dipilih, semua produk akan ditampilkan kepada user.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => wds_get_product_restrict(),
					),
				),
				'default'    => wds_v1_editv2(),
				'dependency' => array( 'invitation_edit_type|invitation_edit_layout', '==|==', '|2' ),
			),
			array(
				'id'         => 'invitation_edit_popup',
				'type'       => 'number',
				'title'      => __( 'Lebar Maksimal Untuk Popup (px)', 'weddingsaas' ),
				'default'    => wds_v1_option( 'invitation_edit_popup', '650' ),
				'dependency' => array( 'invitation_edit_type|invitation_edit_layout', '==|==', '|2' ),
			),
		),
	)
);
