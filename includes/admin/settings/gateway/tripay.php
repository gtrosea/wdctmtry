<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$text = __( 'Sebelum klik refresh, isi terlebih dahulu merchant code, apikey, dll. Lalu save dan reload halaman.', 'weddingsaas' );

$pref = 'tripay_';

$settings = array(
	array(
		'type'  => 'heading',
		'title' => __( 'Settings', 'wds-notrans' ),
	),
	array(
		'type'    => 'content',
		'title'   => __( 'Payment Channel', 'wds-notrans' ),
		'content' => '<button type="button" class="button button-primary wrap-spin gateway-payment-list" data-gateway="tripay">Refresh Payment List<i class="dashicons dashicons-update-alt"></i></button><div style="margin-top:5px">' . $text . '</div>',
	),
	array(
		'type'    => 'content',
		'title'   => __( 'Callback Url', 'wds-notrans' ),
		'content' => get_rest_url( null, 'weddingsaas-tripay/v1/webhook' ),
	),
	array(
		'id'      => $pref . 'sandbox',
		'type'    => 'switcher',
		'title'   => __( 'Enable Testing Sandbox', 'wds-notrans' ),
		'desc'    => __( 'Enable sandbox only to test payments with tripay sandbox', 'wds-notrans' ),
		'default' => wds_v1_option( $pref . 'sanbox' ),
	),
	array(
		'id'       => $pref . 'merchant_code',
		'type'     => 'text',
		'title'    => __( 'Merchant Code', 'wds-notrans' ),
		'sanitize' => false,
		'default'  => wds_v1_option( $pref . 'merchant_code' ),
	),
	array(
		'id'       => $pref . 'api_key',
		'type'     => 'text',
		'title'    => __( 'Api Key', 'wds-notrans' ),
		'sanitize' => false,
	),
	array(
		'id'       => $pref . 'private_key',
		'type'     => 'text',
		'title'    => __( 'Private Key', 'wds-notrans' ),
		'sanitize' => false,
		'default'  => wds_v1_option( $pref . 'api_key' ),
	),
	array(
		'id'      => $pref . 'payment_link',
		'type'    => 'switcher',
		'title'   => __( 'Link Pembayaran Langsung', 'weddingsaas' ),
		'desc'    => __( 'Jika diaktifkan, link pembayaran pada notifikasi email dan whatsapp diarahkan ke website Tripay.', 'weddingsaas' ),
		'default' => wds_v1_option( $pref . 'payment_link' ),
	),
);

$channels = array();

$tripay = wds_get_tripay_channel( 'list', array() );
if ( wds_check_array( $tripay, true ) ) {
	foreach ( $tripay as $key => $title ) {
		$channels[] = array(
			'type'  => 'subheading',
			'title' => $title . ' (' . strtoupper( str_replace( 'tripay_', '', $key ) ) . ')',
		);
		$channels[] = array(
			'id'      => $key . '_icon_enable',
			'type'    => 'switcher',
			'title'   => __( 'Tampilkan Ikon', 'weddingsaas' ),
			'default' => true,
		);
		$channels[] = array(
			'id'         => $key . '_icon',
			'type'       => 'upload',
			'library'    => 'image',
			'preview'    => true,
			'title'      => __( 'Kustom Ikon', 'weddingsaas' ),
			'default'    => wds_logo_payment( $key ),
			'dependency' => array( $key . '_icon_enable', '==', 'true' ),
		);
		$channels[] = array(
			'id'       => $key . '_title',
			'type'     => 'text',
			'title'    => __( 'Judul', 'weddingsaas' ),
			'sanitize' => false,
			'default'  => 'Pembayaran Otomatis',
		);
		$channels[] = array(
			'id'       => $key . '_instruction',
			'type'     => 'textarea',
			'title'    => __( 'Petunjuk', 'weddingsaas' ),
			'sanitize' => false,
			'default'  => 'Pembayaran otomatis dengan virtual akun, qris, atau retail.',
		);
	}
}

$fields = array_merge( $settings, $channels );

CSF::createSection(
	$prefix,
	array(
		'parent' => 'gateway',
		'title'  => __( 'Tripay', 'wds-notrans' ),
		'fields' => $fields,
	)
);
