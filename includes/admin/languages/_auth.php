<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Auth Pages', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'subheading',
				'title' => __( 'Title', 'wds-notrans' ),
			),
			array(
				'id'          => 'auth_login_title',
				'type'        => 'text',
				'title'       => __( 'Login Title', 'wds-notrans' ),
				'placeholder' => 'Masuk',
				'default'     => wds_v1_lang( 'auth_login_title', 'Masuk' ),
			),
			array(
				'id'          => 'auth_lp_title',
				'type'        => 'text',
				'title'       => __( 'Lost Password Title', 'wds-notrans' ),
				'placeholder' => 'Lupa Kata Sandi',
				'default'     => wds_v1_lang( 'auth_lp_title', 'Lupa Kata Sandi' ),
			),
			array(
				'id'          => 'auth_rp_title',
				'type'        => 'text',
				'title'       => __( 'Reset Password Title', 'wds-notrans' ),
				'placeholder' => 'Reset Kata Sandi',
				'default'     => wds_v1_lang( 'auth_rp_title', 'Reset Kata Sandi' ),
			),
			array(
				'id'          => 'auth_verify_title',
				'type'        => 'text',
				'title'       => __( 'Verify Title', 'wds-notrans' ),
				'placeholder' => 'Aktivasi Akun',
				'default'     => 'Aktivasi Akun',
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Login Page', 'wds-notrans' ),
			),
			array(
				'id'          => 'auth_login_header',
				'type'        => 'text',
				'title'       => __( 'Login Header', 'wds-notrans' ),
				'placeholder' => 'Masuk ke akun Anda!',
				'default'     => wds_v1_lang( 'auth_login_header', 'Masuk ke akun Anda!' ),
			),
			array(
				'id'          => 'auth_login_remember',
				'type'        => 'text',
				'title'       => __( 'Remember', 'wds-notrans' ),
				'placeholder' => 'Ingat saya',
				'default'     => wds_v1_lang( 'auth_login_remember', 'Ingat saya' ),
			),
			array(
				'id'          => 'auth_login_lost',
				'type'        => 'text',
				'title'       => __( 'Lost Password', 'wds-notrans' ),
				'placeholder' => 'Lupa Kata Sandi?',
				'default'     => wds_v1_lang( 'auth_login_lost', 'Lupa Kata Sandi?' ),
			),
			array(
				'id'          => 'auth_login_signup_text',
				'type'        => 'text',
				'title'       => __( 'Not a Member yet?', 'wds-notrans' ),
				'placeholder' => 'Belum punya akun?',
				'default'     => wds_v1_lang( 'auth_login_signup_text', 'Belum punya akun?' ),
			),
			array(
				'id'          => 'auth_login_signup',
				'type'        => 'text',
				'title'       => __( 'Sign Up', 'wds-notrans' ),
				'placeholder' => 'Daftar Sekarang',
				'default'     => wds_v1_lang( 'auth_login_signup', 'Daftar Sekarang' ),
			),
			array(
				'id'          => 'auth_login_success',
				'type'        => 'text',
				'title'       => __( 'Login Success', 'wds-notrans' ),
				'placeholder' => 'Anda telah berhasil login!',
				'default'     => wds_v1_lang( 'auth_login_success', 'Anda telah berhasil login!' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Lost Password Page', 'wds-notrans' ),
			),
			array(
				'id'          => 'auth_lp_header',
				'type'        => 'text',
				'title'       => __( 'Lost Password Header', 'wds-notrans' ),
				'placeholder' => 'Lupa Kata Sandi ?',
				'default'     => wds_v1_lang( 'auth_lp_header', 'Lupa Kata Sandi ?' ),
			),
			array(
				'id'          => 'auth_lp_subheader',
				'type'        => 'text',
				'title'       => __( 'Lost Password Subheader', 'wds-notrans' ),
				'placeholder' => 'Masukkan email Anda untuk mengatur ulang password Anda.',
				'default'     => wds_v1_lang( 'auth_lp_subheader', 'Masukkan email Anda untuk mengatur ulang password Anda.' ),
			),
			array(
				'id'          => 'auth_lp_success',
				'type'        => 'text',
				'title'       => __( 'Lost Password Success', 'wds-notrans' ),
				'placeholder' => 'Email Reset Password telah dikirim. Silahkan periksa email Anda dan ikuti instruksinya.',
				'default'     => wds_v1_lang( 'auth_lp_success', 'Email Reset Password telah dikirim. Silahkan periksa email Anda dan ikuti instruksinya.' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Reset Password Page', 'wds-notrans' ),
			),
			array(
				'id'          => 'auth_rp_header',
				'type'        => 'text',
				'title'       => __( 'Reset Password Header', 'wds-notrans' ),
				'placeholder' => 'Siapkan Kata Sandi Baru',
				'default'     => wds_v1_lang( 'auth_rp_header', 'Siapkan Kata Sandi Baru' ),
			),
			array(
				'id'          => 'auth_rp_subheader',
				'type'        => 'text',
				'title'       => __( 'Reset Password Subheader', 'wds-notrans' ),
				'placeholder' => 'Apakah Anda sudah mengatur ulang kata sandi?',
				'default'     => wds_v1_lang( 'auth_rp_subheader', 'Apakah Anda sudah mengatur ulang kata sandi?' ),
			),
			array(
				'id'          => 'auth_rp_instruction',
				'type'        => 'text',
				'title'       => __( 'Reset Password Instruction', 'wds-notrans' ),
				'placeholder' => 'Gunakan 8 karakter atau lebih dengan campuran huruf, angka, dan simbol.',
				'default'     => wds_v1_lang( 'auth_rp_instruction', 'Gunakan 8 karakter atau lebih dengan campuran huruf, angka, dan simbol.' ),
			),
			array(
				'id'          => 'auth_rp_success',
				'type'        => 'text',
				'title'       => __( 'Reset Password Success', 'wds-notrans' ),
				'placeholder' => 'Kata sandi Anda telah berhasil diubah!',
				'default'     => wds_v1_lang( 'auth_rp_success', 'Kata sandi Anda telah berhasil diubah!' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Reset Password Email', 'wds-notrans' ),
			),
			array(
				'id'          => 'auth_rp_email_1',
				'type'        => 'text',
				'title'       => __( 'Line 1', 'wds-notrans' ),
				'placeholder' => 'Halo!',
				'default'     => wds_v1_lang( 'auth_rp_email_1', 'Halo!' ),
			),
			array(
				'id'          => 'auth_rp_email_2',
				'type'        => 'text',
				'title'       => __( 'Line 2', 'wds-notrans' ),
				'placeholder' => 'Anda meminta kami untuk mengatur ulang kata sandi untuk akun Anda menggunakan nama pengguna / email',
				'default'     => wds_v1_lang( 'auth_rp_email_2', 'Anda meminta kami untuk mengatur ulang kata sandi untuk akun Anda menggunakan nama pengguna / email' ),
			),
			array(
				'id'          => 'auth_rp_email_3',
				'type'        => 'text',
				'title'       => __( 'Line 3', 'wds-notrans' ),
				'placeholder' => 'Jika ini adalah kesalahan, atau Anda tidak meminta pengaturan ulang kata sandi, abaikan saja email ini dan tidak akan terjadi apa-apa.',
				'default'     => wds_v1_lang( 'auth_rp_email_3', 'Jika ini adalah kesalahan, atau Anda tidak meminta pengaturan ulang kata sandi, abaikan saja email ini dan tidak akan terjadi apa-apa.' ),
			),
			array(
				'id'          => 'auth_rp_email_4',
				'type'        => 'text',
				'title'       => __( 'Line 4', 'wds-notrans' ),
				'placeholder' => 'Untuk mengatur ulang kata sandi Anda, kunjungi tautan berikut:',
				'default'     => wds_v1_lang( 'auth_rp_email_4', 'Untuk mengatur ulang kata sandi Anda, kunjungi tautan berikut:' ),
			),
			array(
				'id'          => 'auth_rp_email_5',
				'type'        => 'text',
				'title'       => __( 'Line 5', 'wds-notrans' ),
				'placeholder' => 'Terima kasih!',
				'default'     => wds_v1_lang( 'auth_rp_email_5', 'Terima kasih!' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Verify Page', 'wds-notrans' ),
			),
			array(
				'id'          => 'auth_verify_header',
				'type'        => 'text',
				'title'       => __( 'Verify Header', 'wds-notrans' ),
				'placeholder' => 'Aktifkan Akun Anda',
				'default'     => 'Aktifkan Akun Anda',
			),
			array(
				'id'          => 'auth_verify_subheader',
				'type'        => 'text',
				'title'       => __( 'Verify Subheader', 'wds-notrans' ),
				'placeholder' => 'Periksa email Anda dan cari email dengan judul Aktivasi Akun.',
				'default'     => 'Periksa email Anda dan cari email dengan judul Aktivasi Akun.',
			),
			array(
				'id'          => 'auth_verify_resend',
				'type'        => 'text',
				'title'       => __( 'Tombol Kirim Ulang Aktivasi', 'wds-notrans' ),
				'placeholder' => 'Kirim Ulang Link aktivasi',
				'default'     => 'Kirim Ulang Link aktivasi',
			),
			array(
				'id'          => 'auth_verify_resend_success',
				'type'        => 'text',
				'title'       => __( 'Kirim Ulang Aktivasi Berhasil', 'wds-notrans' ),
				'placeholder' => 'Link aktivasi berhasil dikirim.',
				'default'     => 'Link aktivasi berhasil dikirim.',
			),
		),
	)
);
