<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'tools',
		'title'  => __( 'Components', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'auto_delete_draft',
				'type'    => 'number',
				'title'   => __( 'Auto Delete Drafts Post', 'wds-notrans' ),
				'desc'    => __( 'Masukkan jumlah hari, jika Anda tidak ingin menggunakan fitur ini, biarkan kosong.', 'weddingsaas' ),
				'default' => get_option( '_wds_autodel_draft', 30 ),
			),
			array(
				'id'          => 'allowed_domain_email',
				'type'        => 'text',
				'title'       => __( 'Allowed Domain Email', 'wds-notrans' ),
				'desc'        => __( 'Masukkan email domain yang diizinkan, pisahkan dengan koma dan tanpa spasi.', 'weddingsaas' ),
				'placeholder' => 'Ex. gmail.com,yahoo.com,yahoo.co.id,hotmail.com,icloud.com',
				'default'     => get_option( '_allowed_domain_email', 'gmail.com,yahoo.com,yahoo.co.id,hotmail.com,icloud.com' ),
			),
			array(
				'id'      => 'limit_post_revision',
				'type'    => 'select',
				'title'   => __( 'Limit Post Revisions', 'wds-notrans' ),
				'desc'    => __( 'Membatasi jumlah maksimum revisi yang diizinkan untuk postingan dan halaman.', 'weddingsaas' ),
				'options' => array(
					''      => __( 'Default', 'wds-notrans' ),
					'false' => __( 'Disable Post Revisions', 'wds-notrans' ),
					'2'     => '2',
					'3'     => '3',
					'4'     => '4',
					'5'     => '5',
					'10'    => '10',
					'15'    => '15',
					'20'    => '20',
					'25'    => '25',
					'30'    => '30',
				),
				'default' => get_option( '_limit_post_revisions' ),
			),
			array(
				'id'      => 'autosave_interval',
				'type'    => 'select',
				'title'   => __( 'Auto Save Interval', 'wds-notrans' ),
				'desc'    => __( 'Mengontrol seberapa sering WordPress akan menyimpan postingan dan halaman secara otomatis saat mengedit.', 'weddingsaas' ),
				'options' => array(
					''      => '1 ' . __( 'Menit', 'weddingsaas' ) . ' (' . __( 'Default', 'wds-notrans' ) . ')',
					'86400' => __( 'Disable Autosave Interval', 'wds-notrans' ),
					'120'   => '2 ' . __( 'Menit', 'weddingsaas' ),
					'180'   => '3 ' . __( 'Menit', 'weddingsaas' ),
					'240'   => '4 ' . __( 'Menit', 'weddingsaas' ),
					'300'   => '5 ' . __( 'Menit', 'weddingsaas' ),
					'600'   => '10 ' . __( 'Menit', 'weddingsaas' ),
					'900'   => '15 ' . __( 'Menit', 'weddingsaas' ),
					'1200'  => '20 ' . __( 'Menit', 'weddingsaas' ),
					'1500'  => '25 ' . __( 'Menit', 'weddingsaas' ),
					'1800'  => '30 ' . __( 'Menit', 'weddingsaas' ),
				),
				'default' => get_option( '_autosave_interval' ),
			),
			array(
				'id'      => 'disable_xmlrpc',
				'type'    => 'switcher',
				'title'   => __( 'Disable XML-RPC', 'wds-notrans' ),
				'desc'    => __( 'Menonaktifkan fungsi XML-RPC WordPress.', 'weddingsaas' ),
				'default' => get_option( '_disable_xmlrpc' ),
			),
			array(
				'id'      => 'disable_rss_feeds',
				'type'    => 'switcher',
				'title'   => __( 'Disable RSS Feeds', 'wds-notrans' ),
				'desc'    => __( 'Nonaktifkan umpan RSS yang dihasilkan WordPress dan URL pengalihan 301 ke homepage.', 'weddingsaas' ),
				'default' => get_option( '_disable_rss_feeds' ),
			),
			array(
				'id'      => 'disable_search',
				'type'    => 'switcher',
				'title'   => __( 'Disable Search', 'wds-notrans' ),
				'desc'    => __( 'Nonaktifkan sepenuhnya pencarian di situs web WordPress Anda.', 'weddingsaas' ),
				'default' => get_option( '_disable_search' ),
			),
			array(
				'id'      => 'restrict_media_library',
				'type'    => 'switcher',
				'title'   => __( 'Restrict Media Library', 'wds-notrans' ),
				'desc'    => __( 'Pada Media Library hanya menampilkan yang diunggah oleh pengguna yang sedang login.', 'weddingsaas' ),
				'default' => get_option( '_restrict_media_library' ),
			),
			array(
				'id'      => 'disable_author',
				'type'    => 'switcher',
				'title'   => __( 'Disable Author', 'wds-notrans' ),
				'desc'    => __( 'Nonaktifkan penulis dan alihkan halaman ke beranda.', 'weddingsaas' ),
				'default' => get_option( '_wds_disable_author' ),
			),
		),
	)
);
