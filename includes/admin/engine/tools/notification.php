<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$pages = array(
	'dashboard/invitation'        => wds_lang( 'invitation' ),
	'dashboard/invitation/create' => wds_lang( 'create_invitation' ),
	'dashboard/invitation/edit'   => wds_lang( 'edit_invitation' ),
	'dashboard/client'            => wds_lang( 'client' ),
	'dashboard/marketing'         => wds_lang( 'marketing' ),
	'dashboard/landingpage'       => wds_lang( 'landingpage' ),
	'dashboard/upgrade'           => wds_lang( 'upgrade' ),
	'dashboard/access'            => wds_lang( 'access' ),
	'account/overview'            => wds_lang( 'overview' ),
	'account/settings'            => wds_lang( 'settings' ),
	'account/transactions'        => wds_lang( 'transactions' ),
	'account/referrals'           => wds_lang( 'referrals' ),
	'checkout'                    => wds_lang( 'trx_checkout_title' ),
	'renew'                       => wds_lang( 'trx_renew_title' ),
	'share'                       => wds_lang( 'share' ),
);

CSF::createSection(
	$prefix,
	array(
		'parent' => 'tools',
		'title'  => __( 'Notifications', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Popup Manager', 'wds-notrans' ),
			),
			array(
				'id'           => 'popup',
				'type'         => 'group',
				'title'        => '',
				'button_title' => __( 'Add Popup', 'wds-notrans' ),
				'fields'       => array(
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => __( 'Title', 'wds-notrans' ),
					),
					array(
						'id'      => 'delay',
						'type'    => 'number',
						'title'   => __( 'Delay', 'wds-notrans' ),
						'desc'    => __( 'Delay pertama kali popup muncul dalam hitungan detik.', 'weddingsaas' ),
						'default' => '2',
					),
					array(
						'id'      => 'interval',
						'type'    => 'number',
						'title'   => __( 'Reopen Interval', 'wds-notrans' ),
						'desc'    => __( 'Interval waktu untuk memunculkan kembali popup setelah ditutup dalam hitungan hari.', 'weddingsaas' ),
						'default' => '7',
					),
					array(
						'id'       => 'content',
						'type'     => 'wp_editor',
						'title'    => __( 'Content', 'wds-notrans' ),
						'desc'     => __( 'Jika Anda menggunakan gambar, tambahkan class "w-100 h-100"', 'weddingsaas' ),
						'sanitize' => false,
					),
					array(
						'id'          => 'pages',
						'type'        => 'select',
						'title'       => __( 'Pages', 'wds-notrans' ),
						'desc'        => __( 'Pilih halaman yang ingin ditampilkan.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih halaman', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => $pages,
					),
					array(
						'id'          => 'restrict',
						'type'        => 'select',
						'title'       => __( 'Restrict', 'wds-notrans' ),
						'desc'        => __( 'Anda bisa membatasi berdasarkan user group. Kosongkan jika tidak ingin dibatasi.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih group', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => wds_list_user_group(),
					),
				),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Alert Manager', 'wds-notrans' ),
			),
			array(
				'id'           => 'alert',
				'type'         => 'group',
				'title'        => '',
				'button_title' => __( 'Add Alert', 'wds-notrans' ),
				'fields'       => array(
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => __( 'Judul', 'weddingsaas' ),
					),
					array(
						'id'      => 'style',
						'type'    => 'select',
						'title'   => __( 'Style', 'wds-notrans' ),
						'options' => array(
							'primary' => 'Primary',
							'success' => 'Success',
							'info'    => 'Info',
							'danger'  => 'Danger',
							'warning' => 'Warning',
						),
					),
					array(
						'id'       => 'message',
						'type'     => 'wp_editor',
						'title'    => __( 'Message', 'wds-notrans' ),
						'sanitize' => false,
					),
					array(
						'id'          => 'pages',
						'type'        => 'select',
						'title'       => __( 'Pages', 'wds-notrans' ),
						'desc'        => __( 'Pilih halaman yang ingin ditampilkan.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih halaman', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => $pages,
					),
					array(
						'id'          => 'group',
						'type'        => 'select',
						'title'       => __( 'Group', 'wds-notrans' ),
						'desc'        => __( 'Anda bisa membatasi berdasarkan user group. Kosongkan jika tidak ingin dibatasi.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih group', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => wds_list_user_group(),
					),
					array(
						'id'          => 'product',
						'type'        => 'select',
						'title'       => __( 'Products', 'wds-notrans' ),
						'desc'        => __( 'Anda bisa membatasi berdasarkan membership. Kosongkan jika tidak ingin dibatasi.', 'weddingsaas' ),
						'placeholder' => __( 'Pilih product', 'weddingsaas' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => wds_get_product_restrict(),
					),
				),
				'default'      => wds_v1_engine_alert(),
			),
		),
	)
);
