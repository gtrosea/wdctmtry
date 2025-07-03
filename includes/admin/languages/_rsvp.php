<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'RSVP', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'subheading',
				'title' => __( 'General', 'wds-notrans' ),
			),
			array(
				'id'          => 'rsvp_comment',
				'type'        => 'text',
				'title'       => __( 'Comments', 'wds-notrans' ),
				'placeholder' => 'Ucapan',
				'default'     => wds_v1_lang( 'commentpress_comment', 'Ucapan' ),
			),
			array(
				'id'          => 'rsvp_person',
				'type'        => 'text',
				'title'       => __( 'Person', 'wds-notrans' ),
				'placeholder' => 'orang',
				'default'     => wds_v1_lang( 'commentpress_person', 'orang' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Alert', 'wds-notrans' ),
			),
			array(
				'id'          => 'rsvp_thanks_comment',
				'type'        => 'text',
				'title'       => __( 'Thanks Comment', 'wds-notrans' ),
				'placeholder' => 'Terima kasih atas ucapan Anda!',
				'default'     => wds_v1_lang( 'commentpress_thanks_comment', 'Terima kasih atas ucapan Anda!' ),
			),
			array(
				'id'          => 'rsvp_thanks_reply_comment',
				'type'        => 'text',
				'title'       => __( 'Thanks Reply Comment', 'wds-notrans' ),
				'placeholder' => 'Terima kasih telah menjawab komentar!',
				'default'     => wds_v1_lang( 'commentpress_thanks_reply_comment', 'Terima kasih telah menjawab komentar!' ),
			),
			array(
				'id'          => 'rsvp_duplicate_comment',
				'type'        => 'text',
				'title'       => __( 'Duplicate Comment', 'wds-notrans' ),
				'placeholder' => 'Anda mungkin membiarkan salah satu kolom kosong, atau menggandakan komentar.',
				'default'     => wds_v1_lang( 'commentpress_duplicate_comment', 'Anda mungkin membiarkan salah satu kolom kosong, atau menggandakan komentar.' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Field', 'wds-notrans' ),
			),
			array(
				'id'          => 'rsvp_name',
				'type'        => 'text',
				'title'       => __( 'Name', 'wds-notrans' ),
				'placeholder' => 'Nama Anda',
				'default'     => wds_v1_lang( 'commentpress_name', 'Nama Anda' ),
			),
			array(
				'id'          => 'rsvp_req_name',
				'type'        => 'text',
				'title'       => __( 'Required Name', 'wds-notrans' ),
				'placeholder' => 'Masukan nama anda',
				'default'     => wds_v1_lang( 'commentpress_req_name', 'Masukan nama anda' ),
			),
			array(
				'id'          => 'rsvp_confirmation_attendance',
				'type'        => 'text',
				'title'       => __( 'Confirmation of Attendance', 'wds-notrans' ),
				'placeholder' => 'Konfirmasi Kehadiran',
				'default'     => wds_v1_lang( 'commentpress_confirmation_attendance', 'Konfirmasi Kehadiran' ),
			),
			array(
				'id'          => 'rsvp_attendance_present',
				'type'        => 'text',
				'title'       => __( 'Present', 'wds-notrans' ),
				'placeholder' => 'Hadir',
				'default'     => wds_v1_lang( 'commentpress_attendance_present', 'Hadir' ),
			),
			array(
				'id'          => 'rsvp_attendance_notpresent',
				'type'        => 'text',
				'title'       => __( 'Not Present', 'wds-notrans' ),
				'placeholder' => 'Tidak Hadir',
				'default'     => wds_v1_lang( 'commentpress_attendance_notpresent', 'Tidak Hadir' ),
			),
			array(
				'id'          => 'rsvp_attendance_notsure',
				'type'        => 'text',
				'title'       => __( 'Not Sure', 'wds-notrans' ),
				'placeholder' => 'Masih Ragu',
				'default'     => wds_v1_lang( 'commentpress_attendance_notsure', 'Masih Ragu' ),
			),
			array(
				'id'          => 'rsvp_req_attendance',
				'type'        => 'text',
				'title'       => __( 'Required Attendance', 'wds-notrans' ),
				'placeholder' => 'Pilih konfirmasi kehadiran',
				'default'     => wds_v1_lang( 'commentpress_req_attendance', 'Pilih konfirmasi kehadiran' ),
			),
			array(
				'id'          => 'rsvp_guest',
				'type'        => 'text',
				'title'       => __( 'Guest', 'wds-notrans' ),
				'placeholder' => 'Jumlah Tamu',
				'default'     => wds_v1_lang( 'commentpress_guest', 'Jumlah Tamu' ),
			),
			array(
				'id'          => 'rsvp_req_guest',
				'type'        => 'text',
				'title'       => __( 'Required Guest', 'wds-notrans' ),
				'placeholder' => 'Pilih jumlah tamu',
				'default'     => wds_v1_lang( 'commentpress_req_guest', 'Pilih jumlah tamu' ),
			),
			array(
				'id'          => 'rsvp_write_comment',
				'type'        => 'text',
				'title'       => __( 'Write comment', 'wds-notrans' ),
				'placeholder' => 'Tulis ucapan',
				'default'     => wds_v1_lang( 'commentpress_write_comment', 'Tulis ucapan' ),
			),
			array(
				'id'          => 'rsvp_req_comment',
				'type'        => 'text',
				'title'       => __( 'Required Comment', 'wds-notrans' ),
				'placeholder' => 'Ucapan minimal 2 karakter',
				'default'     => wds_v1_lang( 'commentpress_req_comment', 'Ucapan minimal 2 karakter' ),
			),
			array(
				'id'          => 'rsvp_send',
				'type'        => 'text',
				'title'       => __( 'Text Button', 'wds-notrans' ),
				'placeholder' => 'Kirim',
				'default'     => wds_v1_lang( 'commentpress_send', 'Kirim' ),
			),
			array(
				'id'          => 'rsvp_total',
				'type'        => 'text',
				'title'       => __( 'Total', 'wds-notrans' ),
				'placeholder' => 'Jumlah RSVP',
				'default'     => 'Jumlah RSVP',
			),
			array(
				'id'          => 'rsvp_closed',
				'type'        => 'text',
				'title'       => __( 'Closed', 'wds-notrans' ),
				'placeholder' => 'RSVP ditutup.',
				'default'     => 'RSVP ditutup.',
			),
			array(
				'id'          => 'rsvp_spam',
				'type'        => 'text',
				'title'       => __( 'Spam', 'wds-notrans' ),
				'placeholder' => 'Komentar terdeteksi sebagai spam.',
				'default'     => 'Komentar terdeteksi sebagai spam.',
			),
		),
	)
);
