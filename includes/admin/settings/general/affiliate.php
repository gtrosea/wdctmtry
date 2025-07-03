<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'general',
		'title'  => __( 'Affiliate', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'affiliate_redirect',
				'type'    => 'text',
				'title'   => __( 'Tautan Afiliasi Umum', 'weddingsaas' ),
				'desc'    => __( 'Target url pengalihan untuk tautan afiliasi umum.', 'weddingsaas' ),
				'default' => wds_v1_option( 'affiliate_redirect', home_url( '/' ) ),
			),
			array(
				'id'      => 'affiliate_cookie',
				'type'    => 'number',
				'title'   => __( 'Cookie Afiliasi', 'weddingsaas' ),
				'desc'    => __( 'Masukkan dalam jumlah hari.', 'weddingsaas' ),
				'default' => wds_v1_option( 'affiliate_cookie', 14 ),
			),
			array(
				'id'      => 'affiliate_recurring',
				'type'    => 'switcher',
				'title'   => __( 'Komisi Berulang', 'weddingsaas' ),
				'desc'    => __( 'Jika diaktifkan, afiliasi akan mendapat komisi lagi pada saat pengguna yang daftar melalui afiliasi tersebut memperpanjang membership.', 'weddingsaas' ),
				'default' => wds_v1_option( 'affiliate_recurring' ),
			),
			array(
				'id'      => 'affiliate_hide',
				'type'    => 'checkbox',
				'title'   => __( 'Nonaktifkan untuk user group', 'weddingsaas' ),
				'options' => array(
					'trial'    => 'Trial',
					'member'   => 'Member',
					'reseller' => 'Reseller',
				),
				'default' => wds_v1_affiliate_hide(),
			),
		),
	)
);
