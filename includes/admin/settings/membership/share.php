<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$muslim = '_Assalamualaikum Warahmatullahi Wabarakatuh_

Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i *[nama]* untuk menghadiri acara kami.

*Berikut link undangan kami*, untuk info lengkap dari acara bisa kunjungi:

[link-undangan]

Merupakan suatu kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu.

*Mohon maaf perihal undangan hanya di bagikan melalui pesan ini.*

Terima kasih banyak atas perhatiannya.

Salam Hormat
[judul-undangan]';

$formal = 'Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i, *[nama]* untuk menghadiri acara pernikahan kami.

*Berikut link undangan kami*, untuk info lengkap dari acara, bisa kunjungi:

[link-undangan]

Merupakan suatu kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu.

Terima Kasih

Hormat kami,
[judul-undangan]';

$reseller = 'Halo kak,

Ini hasil undangannya ya kak

Link Undangan:

- [invitation-link]

*Berikut merupakan link untuk menambahkan nama tamu:*

Link Input Nama Tamu:

- [share-link]

Cara Input nama tamu:

1. Klik Link Input Nama Tamu
2. Masukkan nama tamu
3. Klik Buat Link Undangan
4. Klik Bagikan Via WA / Sosmed

Pantau Ucapan & Kehadiran tamu:

- [rsvp-link]
- Password: [rsvp-password]

Mohon buka LINK melalui Google Chrome.';

$default = wds_v1_share();
if ( ! wds_check_array( $default, true ) ) {
	$default = array(
		array(
			'title' => 'Muslim',
			'text'  => $muslim,
		),
		array(
			'title' => 'Formal',
			'text'  => $formal,
		),
	);
}

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Share', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'share_type',
				'type'    => 'select',
				'title'   => __( 'Share Type', 'wds-notrans' ),
				'options' => array(
					''    => __( 'Default', 'wds-notrans' ),
					'url' => __( 'Custome', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'invitation_share' ),
			),
			array(
				'id'          => 'share_link',
				'type'        => 'text',
				'title'       => __( 'Share Custom', 'wds-notrans' ),
				'placeholder' => __( 'kirim-undangan/', 'wds-notrans' ),
				'default'     => wds_v1_option( 'invitation_share_link', 'kirim-undangan' ),
				'dependency'  => array( 'share_type', '==', 'url' ),
			),
			array(
				'id'         => 'restrict_invitation',
				'type'       => 'switcher',
				'title'      => __( 'Aktifkan Pembatasan Undangan', 'weddingsaas' ),
				'default'    => wds_v1_option( 'restrict_invitation' ),
				'dependency' => array( 'share_type', '==', '' ),
			),
			array(
				'id'          => 'restrict_invitation_redirect',
				'type'        => 'text',
				'title'       => __( 'Redirect Link jika undangan dibuka tanpa nama tamu', 'weddingsaas' ),
				'placeholder' => 'https://domain.com/restrict/',
				'default'     => wds_v1_option( 'restrict_invitation_redirect' ),
				'dependency'  => array( 'share_type|restrict_invitation', '==|==', '|true' ),
			),
			array(
				'id'         => 'share_data',
				'type'       => 'group',
				'title'      => __( 'Teks Pengantar', 'weddingsaas' ),
				'fields'     => array(
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => __( 'Judul', 'wds-notrans' ),
					),
					array(
						'id'         => 'text',
						'type'       => 'textarea',
						'title'      => __( 'Isi', 'wds-notrans' ),
						'desc'       => 'Shortcode [nama], [judul-undangan], [link-undangan], [mempelai-wanita], [mempelai-pria], [yang-mengundang].',
						'attributes' => array( 'rows' => 20 ),
						'sanitize'   => false,
					),
				),
				'default'    => $default,
				'dependency' => array( 'share_type', '==', '' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Share Undangan ke Client Reseller', 'weddingsaas' ),
			),
			array(
				'id'         => 'share_client_reseller',
				'type'       => 'textarea',
				'title'      => __( 'Pesan', 'weddingsaas' ),
				'desc'       => 'Shortcode [invitation-link], [share-link], [rsvp-link], [rsvp-password].',
				'attributes' => array( 'rows' => 20 ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'invitation_share_client_reseller', $reseller ),
			),
		),
	)
);
