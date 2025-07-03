<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$events = array(
	'AddPaymentInfo'       => 'Add Payment Info',
	'AddToCart'            => 'Add To Cart',
	'AddToWishlist'        => 'Add To Wishlist',
	'CompleteRegistration' => 'Complete Registration',
	'Contact'              => 'Contact',
	'CustomizeProduct'     => 'Customize Product',
	'Donate'               => 'Donate',
	'Find Location'        => 'Find Location',
	'InitiateCheckout'     => 'Initiate Checkout',
	'Lead'                 => 'Lead',
	'Purchase'             => 'Purchase',
	'Schedule'             => 'Schedule',
	'Search'               => 'Search',
	'StartTrial'           => 'Start Trial',
	'SubmitApplication'    => 'Submit Application',
	'Subscribe'            => 'Subscribe',
	'Conversion'           => 'Conversion',
	'ViewContent'          => 'View Content',
);

$fields = array(
	array(
		'type'  => 'heading',
		'title' => __( 'Meta Ads', 'wds-notrans' ),
	),
	array(
		'id'      => 'fbpixel',
		'type'    => 'switcher',
		'title'   => __( 'Facebook Pixel', 'wds-notrans' ),
		'desc'    => __( 'Aktifkan fitur ini jika Anda ingin mengaktifkan piksel Facebook.', 'weddingsaas' ),
		'default' => wds_v1_option( 'fbpixel_enable' ),
	),
	array(
		'id'          => 'fbpixel_ids',
		'type'        => 'text',
		'title'       => __( 'Pixel ids', 'wds-notrans' ),
		'desc'        => __( 'Pisahkan id piksel dengan koma.', 'weddingsaas' ),
		'placeholder' => '123xxxxx,456xxxxxx,678xxxxxxx',
		'default'     => wds_v1_option( 'fbpixel_ids' ),
		'dependency'  => array( 'fbpixel', '==', 'true' ),
	),
	array(
		'id'          => 'fbpixel_checkout',
		'type'        => 'select',
		'title'       => __( 'Checkout Page', 'wds-notrans' ),
		'desc'        => __( 'Pilih peristiwa pemuatan halaman.', 'weddingsaas' ),
		'placeholder' => __( 'Pilih peristiwa', 'weddingsaas' ),
		'options'     => $events,
		'default'     => wds_v1_option( 'fbpixel_checkout' ),
		'dependency'  => array( 'fbpixel', '==', 'true' ),
	),
	array(
		'id'          => 'fbpixel_payment',
		'type'        => 'select',
		'title'       => __( 'Payment Page', 'wds-notrans' ),
		'desc'        => __( 'Pilih peristiwa pemuatan halaman.', 'weddingsaas' ),
		'placeholder' => __( 'Pilih peristiwa', 'weddingsaas' ),
		'options'     => $events,
		'default'     => wds_v1_option( 'fbpixel_payment' ),
		'dependency'  => array( 'fbpixel', '==', 'true' ),
	),
	array(
		'id'          => 'fbpixel_thanks',
		'type'        => 'select',
		'title'       => __( 'Thank You Page', 'wds-notrans' ),
		'desc'        => __( 'Pilih peristiwa pemuatan halaman.', 'weddingsaas' ),
		'placeholder' => __( 'Pilih peristiwa', 'weddingsaas' ),
		'options'     => $events,
		'default'     => wds_v1_option( 'fbpixel_thank' ),
		'dependency'  => array( 'fbpixel', '==', 'true' ),
	),
);

if ( wds_is_meta_capi() ) {
	$meta = array(
		array(
			'type'       => 'heading',
			'title'      => __( 'Meta Conversion API', 'wds-notrans' ),
			'dependency' => array( 'fbpixel', '==', 'true' ),
		),
		array(
			'id'         => 'fbpixel_capi',
			'type'       => 'switcher',
			'title'      => __( 'Meta Conversion API', 'wds-notrans' ),
			'desc'       => __( 'Aktifkan fitur ini jika Anda ingin mengaktifkan konversi meta.', 'weddingsaas' ),
			'default'    => wds_v1_option( 'fbpixel_capi_enable' ),
			'dependency' => array( 'fbpixel', '==', 'true' ),
		),
		array(
			'id'          => 'fbpixel_capi_id',
			'type'        => 'text',
			'title'       => __( 'Meta Conversion Pixel id', 'wds-notrans' ),
			'desc'        => __( 'Masukan hanya satu pixel saja.', 'weddingsaas' ),
			'placeholder' => '123xxxxx',
			'default'     => wds_v1_option( 'fbpixel_capi_id' ),
			'dependency'  => array( 'fbpixel|fbpixel_capi', '==|==', 'true|true' ),
		),
		array(
			'id'         => 'fbpixel_capi_token',
			'type'       => 'textarea',
			'title'      => __( 'Meta Conversion API Token', 'wds-notrans' ),
			'default'    => wds_v1_option( 'fbpixel_capi_token' ),
			'dependency' => array( 'fbpixel|fbpixel_capi', '==|==', 'true|true' ),
		),
		array(
			'id'         => 'fbpixel_capi_test_code',
			'type'       => 'text',
			'title'      => __( 'Test Event Code', 'wds-notrans' ),
			'default'    => wds_v1_option( 'fbpixel_capi_test_code' ),
			'dependency' => array( 'fbpixel|fbpixel_capi', '==|==', 'true|true' ),
		),
	);

	$fields = array_merge( $fields, $meta );
}

CSF::createSection(
	$prefix,
	array(
		'parent' => 'content',
		'title'  => __( 'Tracking', 'wds-notran' ),
		'fields' => $fields,
	)
);
