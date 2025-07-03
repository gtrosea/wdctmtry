<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Replica', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'General', 'wds-notrans' ),
			),
			array(
				'id'      => 'wdr_title',
				'type'    => 'text',
				'title'   => __( 'Judul', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_title', 'Landing Page Generator' ),
			),
			array(
				'id'      => 'wdr_link_title',
				'type'    => 'text',
				'title'   => __( 'Judul Link', 'weddingsaas' ),
				'default' => __( 'Link Landing Page', 'weddingsaas' ),
			),
			array(
				'id'      => 'wdr_field_note',
				'type'    => 'text',
				'title'   => __( 'Catatan Kolom', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_field_note', 'Untuk mengubah kolom, silakan ubah di halaman pengaturan.' ),
			),
			array(
				'id'      => 'wdr_click',
				'type'    => 'text',
				'title'   => __( 'Klik disini', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_click', 'Klik disini' ),
			),
			array(
				'id'      => 'wdr_select_host',
				'type'    => 'text',
				'title'   => __( 'Pilih Host', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_select_host', 'Pilih Host' ),
			),
			array(
				'id'      => 'wdr_select_host_note_subdomain',
				'type'    => 'text',
				'title'   => __( 'Catatan Subdomain', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_select_host_note_subdomain', 'Gunakan nama yang unik, jangan gunakan spasi, karakter, dan angka. Gunakan hanya huruf.' ),
			),
			array(
				'id'      => 'wdr_select_host_note_domain',
				'type'    => 'text',
				'title'   => __( 'Catatan Domain', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_select_host_note_domain', 'Masukkan domain yang telah Anda beli, gunakan tanpa http:// atau https://.' ),
			),
			array(
				'id'      => 'wdr_visit',
				'type'    => 'text',
				'title'   => __( 'Kunjungi Halaman', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_visit', 'Kunjungi halaman di sini' ),
			),
			array(
				'id'      => 'wdr_create_landing_page',
				'type'    => 'text',
				'title'   => __( 'Buat Landing Page', 'weddingsaas' ),
				'default' => 'Buat Landing Page',
			),
			array(
				'id'      => 'wdr_open_landing_page',
				'type'    => 'text',
				'title'   => __( 'Buka Landing Page', 'weddingsaas' ),
				'default' => 'Buka Landing Page',
			),
			array(
				'id'      => 'wdr_landing_page_not_created',
				'type'    => 'text',
				'title'   => __( 'Landing page belum dibuat', 'weddingsaas' ),
				'default' => 'Landing page belum dibuat',
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Notification', 'wds-notrans' ),
			),
			array(
				'id'      => 'wdr_notif_empty_host',
				'type'    => 'text',
				'title'   => __( 'HOST kosong', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_empty_host', 'Tidak ada HOST yang dipilih.' ),
			),
			array(
				'id'      => 'wdr_notif_empty_domain',
				'type'    => 'text',
				'title'   => __( 'Domain Kustom Kosong', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_empty_domain', 'Domain kustom kosong, silakan isi kolom yang tersedia.' ),
			),
			array(
				'id'      => 'wdr_notif_used_domain',
				'type'    => 'text',
				'title'   => __( 'Domain Sudah Digunakan', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_used_domain', 'Domain tidak tersedia, silakan pilih domain lain.' ),
			),
			array(
				'id'      => 'wdr_notif_domain_added',
				'type'    => 'text',
				'title'   => __( 'Domain Ditambahkan', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_domain_added', 'Domain Kustom berhasil ditambahkan.' ),
			),
			array(
				'id'      => 'wdr_notif_domain_updated',
				'type'    => 'text',
				'title'   => __( 'Domain Diperbarui', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_domain_updated', 'Domain kustom berhasil diperbarui.' ),
			),
			array(
				'id'      => 'wdr_notif_domain_deleted',
				'type'    => 'text',
				'title'   => __( 'Domain Dihapus', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_domain_deleted', 'Domain kustom berhasil dihapus.' ),
			),
			array(
				'id'      => 'wdr_notif_empty_subdomain',
				'type'    => 'text',
				'title'   => __( 'Subdomain Kosong', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_empty_subdomain', 'Subdomain kosong, silakan isi kolom yang tersedia.' ),
			),
			array(
				'id'      => 'wdr_notif_blacklist_subdomain',
				'type'    => 'text',
				'title'   => __( 'Subdomain Tidak Tersedia', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_blacklist_subdomain', 'Subdomain ini tidak bisa digunakan, silakan gunakan yang lain.' ),
			),
			array(
				'id'      => 'wdr_notif_wrong_subdomain',
				'type'    => 'text',
				'title'   => __( 'Subdomain Salah', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_wrong_subdomain', 'Harap masukkan alfanumerik huruf kecil hanya untuk subdomain.' ),
			),
			array(
				'id'      => 'wdr_notif_length_subdomain',
				'type'    => 'text',
				'title'   => __( 'Panjang Subdomain', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_length_subdomain', 'Panjang subdomain salah, masukkan dengan' ),
			),
			array(
				'id'      => 'wdr_notif_used_subdomain',
				'type'    => 'text',
				'title'   => __( 'Subdomain Sudah Digunakan', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_used_subdomain', 'Subdomain tidak tersedia, silakan pilih subdomain lain.' ),
			),
			array(
				'id'      => 'wdr_notif_subdomain_added',
				'type'    => 'text',
				'title'   => __( 'Subdomain Ditambahkan', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_subdomain_added', 'Subdomain berhasil ditambahkan.' ),
			),
			array(
				'id'      => 'wdr_notif_subdomain_updated',
				'type'    => 'text',
				'title'   => __( 'Subdomain Diperbarui', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_subdomain_updated', 'Subdomain berhasil diperbarui.' ),
			),
			array(
				'id'      => 'wdr_notif_subdomain_deleted',
				'type'    => 'text',
				'title'   => __( 'Subdomain Dihapus', 'weddingsaas' ),
				'default' => wds_v1_lang( 'wdr_notif_subdomain_deleted', 'Subdomain berhasil dihapus.' ),
			),
		),
	)
);
