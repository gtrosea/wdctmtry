<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'addon',
		'title'  => __( 'Theme', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'General', 'wds-notrans' ),
			),
			array(
				'id'       => 'x_date',
				'type'     => 'datetime',
				'title'    => __( 'Default Date', 'wds-notrans' ),
				'settings' => array(
					'enableTime' => true,
					'dateFormat' => 'd-m-Y H:i',
				),
				'default'  => wds_v1_option( 'x_date', '01-01-2030 09:00' ),
			),
			array(
				'id'      => 'x_location',
				'type'    => 'text',
				'title'   => __( 'Default Location', 'wds-notrans' ),
				'default' => wds_v1_option( 'x_location', 'Rumah Utama Kami' ),
			),
			array(
				'id'       => 'x_address',
				'type'     => 'textarea',
				'title'    => __( 'Default Address', 'wds-notrans' ),
				'sanitize' => false,
				'default'  => wds_v1_option( 'x_address', 'Jl. Moch. Hatta No.39 Jakarta' ),
			),
			array(
				'id'      => 'x_google_maps_link',
				'type'    => 'text',
				'title'   => __( 'Default Google Maps Link', 'wds-notrans' ),
				'default' => wds_v1_option( 'x_google_maps_link', 'https://maps.app.goo.gl/BA59Hi4TnPipixeq7' ),
			),
			array(
				'id'      => 'x_google_maps_embed',
				'type'    => 'text',
				'title'   => __( 'Default Google Maps Location', 'wds-notrans' ),
				'default' => wds_v1_option( 'x_google_maps_embed', 'Monumen Nasional' ),
			),
			array(
				'id'      => 'x_timezone',
				'type'    => 'select',
				'title'   => __( 'Default Timezone', 'wds-notrans' ),
				'options' => array(
					'WIB'  => 'WIB',
					'WIT'  => 'WIT',
					'WITA' => 'WITA',
				),
				'default' => wds_v1_option( 'x_timezone', 'WIB' ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Media', 'wds-notrans' ),
			),
			array(
				'id'      => 'max_number_upload',
				'type'    => 'number',
				'title'   => __( 'Max Number Upload', 'wds-notrans' ),
				'default' => wds_v1_option( 'max_number_upload', 15 ),
			),
			array(
				'id'      => 'max_size_upload',
				'type'    => 'number',
				'title'   => __( 'Max Size Upload (kilo bytes - kb)', 'wds-notrans' ),
				'default' => wds_v1_option( 'max_size_upload', 1000 ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Other', 'wds-notrans' ),
			),
			array(
				'id'      => 'disable_right_click',
				'type'    => 'switcher',
				'title'   => __( 'Disable Right Click', 'wds-notrans' ),
				'desc'    => __( 'Nonaktifkan Klik Kanan pada halaman undangan.', 'weddingsaas' ),
				'default' => wds_v1_option( 'wds_disable_right_click' ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Custome Global Script', 'wds-notrans' ),
			),
			array(
				'id'       => 'general_template_header_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Template Header Scripts', 'wds-notrans' ),
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
				'default'  => wds_v1_option( 'x_general_scripts' ),
			),
			array(
				'id'       => 'general_template_footer_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Template Footer Scripts', 'wds-notrans' ),
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
			),
			array(
				'id'       => 'general_page_header_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Page Header Scripts', 'wds-notrans' ),
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
				'default'  => wds_v1_option( 'x_general_scripts' ),
			),
			array(
				'id'       => 'general_page_footer_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Page Footer Scripts', 'wds-notrans' ),
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
			),
			array(
				'id'       => 'general_blog_header_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Blog Header Scripts', 'wds-notrans' ),
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
				'default'  => wds_v1_option( 'x_general_scripts' ),
			),
			array(
				'id'       => 'general_blog_footer_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Blog Footer Scripts', 'wds-notrans' ),
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Pages', 'wds-notrans' ),
			),
			array(
				'id'      => 'fe_bg_color',
				'type'    => 'color',
				'title'   => __( 'Background Color', 'wds-notrans' ),
				'desc'    => __( 'Disarankan menggunakan warna gelap agar sesuai dengan text. (hanya untuk V1)', 'weddingsaas' ),
				'default' => wds_v1_option( 'fe_bg_color', '#13263c' ),
			),
			array(
				'id'      => 'fe_section_tentang',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Section Tentang', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_section_tentang', true ),
			),
			array(
				'id'      => 'fe_section_fitur',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Section Fitur', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_section_fitur', true ),
			),
			array(
				'id'      => 'fe_section_cara_kerja',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Section Cara Kerja', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_section_cara_kerja', true ),
			),
			array(
				'id'      => 'fe_section_pengguna',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Section Pengguna', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_section_pengguna', true ),
			),
			array(
				'id'      => 'fe_section_testimoni',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Section Testimoni', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_section_testimoni', true ),
			),
			array(
				'id'      => 'fe_section_tema',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Section Tema', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_section_tema', true ),
			),
			array(
				'id'      => 'fe_section_faq',
				'type'    => 'switcher',
				'title'   => __( 'Tampilkan Section FAQ', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_section_faq', true ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Homepage', 'wds-notrans' ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Hero', 'wds-notrans' ),
			),
			array(
				'id'      => 'fe_home_img',
				'type'    => 'upload',
				'library' => 'image',
				'preview' => true,
				'title'   => __( 'Image', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_home_img' ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Testimoni', 'wds-notrans' ),
			),
			array(
				'id'      => 'fe_testimoni',
				'type'    => 'group',
				'title'   => __( 'Data', 'wds-notrans' ),
				'fields'  => array(
					array(
						'id'       => 'name',
						'type'     => 'text',
						'title'    => __( 'Name', 'wds-notrans' ),
						'sanitize' => false,
					),
					array(
						'id'       => 'city',
						'type'     => 'text',
						'title'    => __( 'City', 'wds-notrans' ),
						'sanitize' => false,
					),
					array(
						'id'      => 'image',
						'type'    => 'upload',
						'library' => 'image',
						'preview' => true,
						'title'   => __( 'Image', 'wds-notrans' ),
					),
					array(
						'id'       => 'text',
						'type'     => 'textarea',
						'title'    => __( 'Description', 'wds-notrans' ),
						'sanitize' => false,
					),
				),
				'max'     => 3,
				'default' => wds_v1_theme_testimoni(),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Theme', 'wds-notrans' ),
			),
			array(
				'id'          => 'fe_htema_link',
				'type'        => 'text',
				'title'       => __( 'Button URL', 'wds-notrans' ),
				'placeholder' => home_url( 'tema/' ),
				'default'     => wds_v1_option( 'fe_htema_link', home_url( 'tema/' ) ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Price', 'wds-notrans' ),
			),
			array(
				'id'          => 'fe_harga',
				'type'        => 'select',
				'title'       => __( 'List', 'wds-notrans' ),
				'placeholder' => __( 'Select product', 'wds-notrans' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_product_membership( 'pricing', true ),
				'default'     => wds_v1_option( 'fe_harga' ),
			),
			array(
				'id'      => 'fe_harga_reseller',
				'type'    => 'switcher',
				'title'   => __( 'Reseller Box', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_harga_reseller', true ),
			),
			array(
				'id'          => 'fe_harga_reseller_link',
				'type'        => 'text',
				'title'       => __( 'Reseller URL', 'wds-notrans' ),
				'placeholder' => home_url( 'reseller/' ),
				'default'     => wds_v1_option( 'fe_harga_reseller_link', home_url( 'reseller/' ) ),
				'dependency'  => array( 'fe_harga_reseller', '==', 'true' ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Support', 'wds-notrans' ),
			),
			array(
				'id'          => 'fe_support_link',
				'type'        => 'text',
				'title'       => __( 'Support URL', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'fe_support_link', home_url() ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'FAQ', 'wds-notrans' ),
			),
			array(
				'id'      => 'fe_faq_list',
				'type'    => 'group',
				'title'   => __( 'Data', 'wds-notrans' ),
				'fields'  => array(
					array(
						'id'       => 'title',
						'type'     => 'text',
						'title'    => __( 'Title', 'wds-notrans' ),
						'sanitize' => false,
					),
					array(
						'id'       => 'desc',
						'type'     => 'textarea',
						'title'    => __( 'Description', 'wds-notrans' ),
						'sanitize' => false,
					),
				),
				'default' => wds_v1_theme_faq(),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Footer', 'wds-notrans' ),
			),
			array(
				'id'          => 'fe_footer_whatsapp',
				'type'        => 'text',
				'title'       => __( 'WhatsApp', 'wds-notrans' ),
				'placeholder' => '6285123456789',
				'default'     => wds_v1_option( 'fe_footer_whatsapp', '6285123456789' ),
			),
			array(
				'id'          => 'fe_footer_instagram',
				'type'        => 'text',
				'title'       => __( 'Instagram', 'wds-notrans' ),
				'placeholder' => 'Ex: instagram',
				'default'     => wds_v1_option( 'fe_footer_instagram', 'instagram' ),
			),
			array(
				'id'          => 'fe_footer_email',
				'type'        => 'text',
				'title'       => __( 'Email', 'wds-notrans' ),
				'placeholder' => 'cs@domain.com',
				'default'     => wds_v1_option( 'fe_footer_email', 'cs@domain.com' ),
			),
			array(
				'id'      => 'fe_footer_work',
				'type'    => 'group',
				'title'   => __( 'Work Hours', 'wds-notrans' ),
				'fields'  => array(
					array(
						'id'    => 'day',
						'type'  => 'text',
						'title' => __( 'Day', 'wds-notrans' ),
					),
					array(
						'id'    => 'time',
						'type'  => 'text',
						'title' => __( 'Time', 'wds-notrans' ),
					),
				),
				'default' => wds_v1_theme_work(),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Reseller Page', 'wds-notrans' ),
			),
			array(
				'id'      => 'fe_reseller_hero_img',
				'type'    => 'upload',
				'library' => 'image',
				'preview' => true,
				'title'   => __( 'Image', 'wds-notrans' ),
				'default' => wds_v1_option( 'fe_reseller_hero_img' ),
			),
			array(
				'id'          => 'fe_reseller',
				'type'        => 'select',
				'title'       => __( 'List', 'wds-notrans' ),
				'placeholder' => __( 'Select product', 'wds-notrans' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_product_membership( 'pricing_reseller', true ),
				'default'     => wds_v1_option( 'fe_reseller' ),
			),
		),
	)
);
