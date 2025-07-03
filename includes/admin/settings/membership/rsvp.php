<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'RSVP', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'rsvp_type',
				'type'    => 'select',
				'title'   => __( 'Menu Type', 'wds-notrans' ),
				'options' => array(
					''    => __( 'Default', 'wds-notrans' ),
					'url' => __( 'Custome', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'invitation_rsvp_conditional' ),
			),
			array(
				'id'          => 'rsvp_link',
				'type'        => 'text',
				'title'       => __( 'Menu Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'rsvp_menu_link', wds_url( 'rsvp' ) ),
				'dependency'  => array( 'rsvp_type', '==', 'url' ),
			),
			array(
				'id'         => 'rsvp_password',
				'type'       => 'text',
				'title'      => __( 'Public Password', 'wds-notrans' ),
				'desc'       => __( 'Leave blank if you don\'t want to use a password.', 'wds-notrans' ),
				'default'    => wds_v1_option( 'rsvp_public_password', '123456' ),
				'dependency' => array( 'rsvp_type', '==', '' ),
			),
			array(
				'id'         => 'rsvp_integration',
				'type'       => 'select',
				'title'      => __( 'Integration', 'wds-notrans' ),
				'desc'       => __( 'Pilih jenis integrasi RSVP.', 'weddingsaas' ),
				'options'    => array(
					'default' => __( 'Default', 'wds-notrans' ),
					'other'   => __( 'WeddingPress / Templateku', 'wds-notrans' ),
				),
				'default'    => wds_v1_option( 'invitation_rsvp' ),
				'dependency' => array( 'rsvp_type', '==', '' ),
			),
			array(
				'id'         => 'rsvp_avatar_option',
				'type'       => 'select',
				'title'      => __( 'Avatar Type', 'wds-notrans' ),
				'options'    => array(
					'image'      => __( 'Image', 'wds-notrans' ),
					'ui-avatars' => __( 'UI Avatars', 'wds-notrans' ),
				),
				'default'    => wds_v1_option( 'commentpress_avatar_option' ),
				'dependency' => array( 'rsvp_type|rsvp_integration', '==|==', '|default' ),
			),
			array(
				'id'         => 'rsvp_avatar',
				'type'       => 'upload',
				'library'    => 'image',
				'preview'    => true,
				'title'      => __( 'Avatar Image', 'wds-notrans' ),
				'desc'       => __( 'Ukuran yang disarankan adalah 50 X 50 piksel.', 'weddingsaas' ),
				'default'    => wds_v1_option( 'commentpress_avatar' ),
				'dependency' => array( 'rsvp_type|rsvp_integration|rsvp_avatar_option', '==|==|==', '|default|image' ),
			),
			array(
				'id'         => 'rsvp_order',
				'type'       => 'select',
				'title'      => __( 'Order List', 'wds-notrans' ),
				'options'    => array(
					'DESC' => __( 'Terbaru', 'weddingsaas' ),
					'ASC'  => __( 'Terlama', 'weddingsaas' ),
				),
				'default'    => wds_v1_option( 'commentpress_order' ),
				'dependency' => array( 'rsvp_type|rsvp_integration', '==|==', '|default' ),
			),
			array(
				'id'         => 'rsvp_guest_max',
				'type'       => 'number',
				'title'      => __( 'Maksimal Jumlah Tamu', 'weddingsaas' ),
				'default'    => wds_v1_option( 'commentpress_guest_max', 5 ),
				'dependency' => array( 'rsvp_type|rsvp_integration', '==|==', '|default' ),
			),
			array(
				'id'         => 'rsvp_guest_page',
				'type'       => 'number',
				'title'      => __( 'Jumlah RSVP Perhalaman', 'weddingsaas' ),
				'desc'       => __( 'Catatan: Jika jumlah total RSVP kurang dari jumlah RSVP per halaman, pager tidak akan ditampilkan.', 'weddingsaas' ),
				'default'    => wds_v1_option( 'commentpress_rsvp_page', 10 ),
				'dependency' => array( 'rsvp_type|rsvp_integration', '==|==', '|default' ),
			),
			array(
				'id'         => 'rsvp_text_limit',
				'type'       => 'number',
				'title'      => __( 'Jumlah Karakter Maksimal', 'weddingsaas' ),
				'default'    => wds_v1_option( 'commentpress_text_limit', 300 ),
				'dependency' => array( 'rsvp_type|rsvp_integration', '==|==', '|default' ),
			),
			array(
				'id'         => 'rsvp_hide_attendance',
				'type'       => 'switcher',
				'title'      => __( 'Sembunyikan Opsi Kehadiran', 'weddingsaas' ),
				'default'    => wds_v1_option( 'commentpress_hide_attendance' ),
				'dependency' => array( 'rsvp_type|rsvp_integration', '==|==', '|default' ),
			),
			array(
				'id'         => 'rsvp_hide_notsure',
				'type'       => 'switcher',
				'title'      => __( 'Sembunyikan Opsi Masih Ragu', 'weddingsaas' ),
				'default'    => wds_v1_option( 'commentpress_hide_notsure' ),
				'dependency' => array( 'rsvp_type|rsvp_integration', '==|==', '|default' ),
			),
		),
	)
);
