<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$domain = str_replace( array( 'https://', 'http://', 'https://www.', 'http://www.' ), '', home_url() );

CSF::createSection(
	$prefix,
	array(
		'parent' => 'addon',
		'title'  => __( 'Replica', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'General', 'wds-notrans' ),
			),
			array(
				'id'      => 'wdr_select_host',
				'type'    => 'select',
				'title'   => __( 'Pilih Host', 'weddingsaas' ),
				'desc'    => __( 'Jika Anda menginstal WDS Pro pada subdomain, maka pilih Custom Domain Only.', 'weddingsaas' ),
				'options' => array(
					'1' => __( 'Custom Domain and Subdomain Vendor', 'wds-notrans' ),
					'2' => __( 'Custom Domain Only', 'wds-notrans' ),
					'3' => __( 'Custom Domain and Subdomain Custom', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'wdr_select_host', '1' ),
			),
			array(
				'id'          => 'wdr_domain_host',
				'type'        => 'text',
				'title'       => __( 'Domain Host', 'weddingsaas' ),
				'desc'        => __( 'Domain utama Anda, domain tempat Anda menginstal WDS Pro.', 'weddingsaas' ),
				'placeholder' => $domain,
				'default'     => wds_v1_option( 'wdr_domain_host', $domain ),
			),
			array(
				'id'          => 'wdr_domain_host_custom',
				'type'        => 'text',
				'title'       => __( 'Kustom Subdomain Host', 'weddingsaas' ),
				'desc'        => __( 'Masukkan domain utama yang ingin digunakan oleh reseller.', 'weddingsaas' ),
				'placeholder' => 'domain.com',
				'default'     => wds_v1_option( 'wdr_domain_host_custom' ),
				'dependency'  => array( 'wdr_select_host', '==', '3' ),
			),
			array(
				'id'      => 'wdr_integration',
				'type'    => 'radio',
				'title'   => __( 'Pilih Integrasi', 'weddingsaas' ),
				'options' => array(
					''       => __( 'Global (landing page, undangan & semua halaman WeddingSaas)', 'wds-notrans' ),
					'public' => __( 'Public (landing page, undangan, share & public RSVP)', 'wds-notrans' ),
					'lp'     => __( 'Landing Page', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'wdr_integration' ),
			),
			array(
				'id'      => 'wdr_disable_button',
				'type'    => 'checkbox',
				'title'   => __( 'Nonaktifkan Tombol', 'weddingsaas' ),
				'desc'    => __( 'Jika dicentang, tombol yang ada di halaman edit undangan tidak akan redirect langsung ke custom domain.', 'weddingsaas' ),
				'options' => array(
					'open' => __( 'Buka Undangan', 'weddingsaas' ),
				),
				'default' => wds_v1_option( 'wdr_disable_button' ),
			),
			array(
				'id'          => 'wdr_blacklist_subdomain',
				'type'        => 'textarea',
				'title'       => __( 'Blacklist Subdomain', 'wds-notrans' ),
				'desc'        => __( 'Separate subdomain by comma.', 'wds-notrans' ),
				'placeholder' => __( 'Example: demo,test,sample,admin', 'wds-notrans' ),
				'default'     => wds_v1_option( 'wdr_blacklist_subdomain' ),
			),
			array(
				'id'          => 'wdr_limit_min',
				'type'        => 'number',
				'title'       => __( 'Limit Character (min)', 'wds-notrans' ),
				'placeholder' => __( 'Example: 4', 'wds-notrans' ),
				'default'     => wds_v1_option( 'wdr_limit_min', 4 ),
			),
			array(
				'id'          => 'wdr_limit_max',
				'type'        => 'number',
				'title'       => __( 'Limit Character (max)', 'wds-notrans' ),
				'placeholder' => __( 'Example: 15', 'wds-notrans' ),
				'default'     => wds_v1_option( 'wdr_limit_max', 15 ),
			),
			array(
				'id'          => 'wdr_set_homepage',
				'type'        => 'select',
				'title'       => __( 'Pilih Homepage', 'weddingsaas' ),
				'placeholder' => __( 'Pilih halaman', 'weddingsaas' ),
				'options'     => 'all_page',
				'chosen'      => true,
				'default'     => wds_v1_option( 'wdr_set_homepage' ),
			),
			array(
				'id'          => 'wdr_set_page',
				'type'        => 'select',
				'title'       => __( 'Pilih Halaman', 'weddingsaas' ),
				'desc'        => __( 'Pilih halaman yang dapat diakses pada domain kustom.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih halaman', 'weddingsaas' ),
				'options'     => 'all_page',
				'chosen'      => true,
				'multiple'    => true,
				'default'     => wds_v1_option( 'wdr_set_page' ),
			),
			array(
				'id'          => 'wdr_set_cpt',
				'type'        => 'select',
				'title'       => __( 'Pilih CPT', 'weddingsaas' ),
				'desc'        => __( 'Pilih CPT yang dapat diakses pada domain kustom.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih post type', 'weddingsaas' ),
				'options'     => 'wds_get_post_type',
				'chosen'      => true,
				'multiple'    => true,
				'default'     => wds_v1_option( 'wdr_set_cpt' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Form', 'wds-notrans' ),
			),
			array(
				'id'     => 'wdr_form',
				'type'   => 'group',
				'title'  => __( 'Form', 'wds-notrans' ),
				'fields' => array(
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
						'desc'        => __( 'Support: JetFormBuilder.', 'wds-notrans' ),
						'placeholder' => '[jet_fb_form form_id="1" submit_type="reload" required_mark="*" fields_layout="column" enable_progress="" fields_label_tag="div" load_nonce="render" use_csrf=""]',
					),
					array(
						'type'  => 'subheading',
						'title' => __( 'Batasi Popup<br><span style="font-weight:400">Anda bisa membatasi popup ini berdasarkan membership.</span>', 'weddingsaas' ),
					),
					array(
						'id'          => 'product',
						'type'        => 'select',
						'title'       => __( 'Produk', 'weddingsaas' ),
						'desc'        => __( 'Jika tidak dipilih, semua produk akan ditampilkan kepada user.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => wds_get_product_restrict( 'reseller' ),
					),
				),
			),

			array(
				'id'       => 'wdr_settings_form',
				'type'     => 'switcher',
				'title'    => __( 'Settings Form', 'wds-notrans' ),
				'subtitle' => __( 'Alihkan link pengaturan akun ke edit landing page.', 'wds-notrans' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Link Landing Page', 'wds-notrans' ),
			),
			array(
				'id'     => 'wdr_pages',
				'type'   => 'group',
				'title'  => __( 'Pages', 'wds-notrans' ),
				'fields' => array(
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => __( 'Nama Halaman', 'weddingsaas' ),
					),
					array(
						'id'          => 'page',
						'type'        => 'text',
						'title'       => __( 'Slug Halaman', 'weddingsaas' ),
						'placeholder' => __( 'Contoh: katalog', 'weddingsaas' ),
					),
				),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Data Landing Page', 'wds-notrans' ),
			),
			array(
				'id'      => 'wdr_favicon',
				'type'    => 'upload',
				'library' => 'image',
				'preview' => true,
				'title'   => __( 'Favicon', 'wds-notrans' ),
				'desc'    => __( 'Ukuran yang disarankan adalah 256 X 256 piksel.', 'weddingsaas' ),
				'default' => wds_v1_option( 'lang_wdr_favicon' ),
			),
			array(
				'id'          => 'wdr_btn_header',
				'type'        => 'text',
				'title'       => __( 'Tombol Header', 'weddingsaas' ),
				'placeholder' => __( 'Hubungi Sekarang', 'weddingsaas' ),
				'default'     => wds_v1_option( 'lang_wdr_btn_header', 'Hubungi Sekarang' ),
			),
			array(
				'id'          => 'wdr_product_name',
				'type'        => 'text',
				'title'       => __( 'Nama Produk', 'weddingsaas' ),
				'placeholder' => 'PLATINUM',
				'default'     => wds_v1_option( 'lang_wdr_product_name', 'PLATINUM' ),
			),
			array(
				'id'          => 'wdr_product_membership',
				'type'        => 'text',
				'title'       => __( 'Masa Aktif Undangan', 'weddingsaas' ),
				'placeholder' => '1 ' . __( 'Tahun', 'weddingsaas' ),
				'default'     => wds_v1_option( 'lang_wdr_product_membership', '1 Tahun' ),
			),
			array(
				'id'      => 'wdr_product_quota',
				'type'    => 'number',
				'title'   => __( 'Kuota Undangan', 'weddingsaas' ),
				'default' => wds_v1_option( 'lang_wdr_product_quota', 1 ),
			),
			array(
				'id'          => 'wdr_btn_register',
				'type'        => 'text',
				'title'       => __( 'Tombol Daftar', 'weddingsaas' ),
				'placeholder' => __( 'Daftar Sekarang', 'weddingsaas' ),
				'default'     => wds_v1_option( 'lang_wdr_btn_register', 'Daftar Sekarang' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'SEO', 'wds-notrans' ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Homepage', 'wds-notrans' ),
			),
			array(
				'id'          => 'wdr_seo_home_title',
				'type'        => 'text',
				'title'       => __( 'Judul', 'weddingsaas' ),
				'placeholder' => __( 'Platform Undangan Digital Terlengkap', 'weddingsaas' ),
				'default'     => wds_v1_option( 'lang_wdr_seo_home_title', 'Platform Undangan Digital Terlengkap' ),
			),
			array(
				'id'          => 'wdr_seo_home_description',
				'type'        => 'text',
				'title'       => __( 'Deskripsi', 'weddingsaas' ),
				'placeholder' => __( 'Buat undangan digital kamu semakin berkesan dengan sentuhan digital', 'weddingsaas' ),
				'default'     => wds_v1_option( 'lang_wdr_seo_home_description', 'Buat undangan digital kamu semakin berkesan dengan sentuhan digital' ),
			),
			array(
				'type'  => 'subheading',
				'title' => __( 'Landing Page Reseller', 'wds-notrans' ),
			),
			array(
				'id'          => 'wdr_seo_res_title',
				'type'        => 'text',
				'title'       => __( 'Judul', 'weddingsaas' ),
				'placeholder' => __( 'Jasa Undangan Digital Terlengkap', 'weddingsaas' ),
				'default'     => wds_v1_option( 'lang_wdr_seo_res_title', 'Jasa Undangan Digital Terlengkap' ),
			),
			array(
				'id'          => 'wdr_seo_res_description',
				'type'        => 'text',
				'title'       => __( 'Deskripsi', 'weddingsaas' ),
				'placeholder' => __( 'Buat undangan digital kamu semakin berkesan dengan sentuhan digital', 'weddingsaas' ),
				'default'     => wds_v1_option( 'lang_wdr_seo_res_description', 'Buat undangan digital kamu semakin berkesan dengan sentuhan digital' ),
			),
		),
	)
);
