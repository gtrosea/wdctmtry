<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$prefix = WDS_SLUG . '_user';

$primary = array(
	array(
		'type'  => 'heading',
		'title' => __( 'WeddingSaas', 'wds-notrans' ),
	),
	array(
		'id'      => '_wds_user_status',
		'type'    => 'select',
		'title'   => __( 'Status', 'weddingsaas' ),
		'options' => array(
			'inactive' => __( 'Tidak Aktif', 'weddingsaas' ),
			'active'   => __( 'Aktif', 'weddingsaas' ),
		),
	),
	array(
		'id'      => '_wds_user_group',
		'type'    => 'select',
		'title'   => __( 'User Grup', 'weddingsaas' ),
		'options' => wds_list_user_group(),
	),
	array(
		'id'       => '_wds_user_active_period',
		'type'     => 'date',
		'title'    => __( 'Masa Aktif', 'weddingsaas' ),
		'desc'     => __( 'Biarkan kosong jika pengguna merupakan pengguna yang aktif selamanya.', 'weddingsaas' ),
		'settings' => array(
			'dateFormat'      => 'dd M yy',
			'changeMonth'     => true,
			'changeYear'      => true,
			'monthNamesShort' => array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ),
		),
	),
	array(
		'id'      => '_wds_user_membership',
		'type'    => 'select',
		'title'   => __( 'Membership', 'weddingsaas' ),
		'options' => wds_get_product_restrict(),
	),
	array(
		'id'    => '_phone',
		'type'  => 'number',
		'title' => __( 'No HP/WhatsApp', 'weddingsaas' ),
	),
	array(
		'id'         => '_wds_order_id',
		'type'       => 'number',
		'title'      => __( 'Order ID', 'weddingsaas' ),
		'attributes' => array( 'readonly' => 'readonly' ),
	),
);

if ( wds_is_digital() ) {
	$primary = array_merge(
		$primary,
		array(
			array(
				'id'          => '_wds_access_product',
				'type'        => 'select',
				'title'       => __( 'Akses Produk', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
				'options'     => wds_get_product_digital(),
			),
		)
	);
}

$invitation = array(
	array(
		'type'  => 'heading',
		'title' => __( 'Invitation', 'wds-notrans' ),
	),
	array(
		'id'    => '_wds_invitation_quota',
		'type'  => 'number',
		'title' => __( 'Kuota Undangan', 'weddingsaas' ),
	),
	array(
		'id'         => '_wds_client_quota',
		'type'       => 'number',
		'title'      => __( 'Kuota Client', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'    => '_wds_invitation_duration',
		'type'  => 'number',
		'title' => __( 'Durasi', 'weddingsaas' ),
		'desc'  => __( 'Biarkan kosong jika undangan merupakan undangan yang aktif selamanya.', 'weddingsaas' ),
	),
	array(
		'id'      => '_wds_invitation_period',
		'type'    => 'select',
		'title'   => __( 'Periode', 'weddingsaas' ),
		'options' => array(
			'day'   => __( 'Hari', 'weddingsaas' ),
			'month' => __( 'Bulan', 'weddingsaas' ),
			'year'  => __( 'Tahun', 'weddingsaas' ),
		),
		'default' => 'year',
	),
	array(
		'id'      => '_wds_invitation_action',
		'type'    => 'select',
		'title'   => __( 'Status Undangan', 'weddingsaas' ),
		'desc'    => __( 'Status undangan saat kadaluwarsa.', 'weddingsaas' ),
		'options' => array(
			'draft' => 'Draft',
			'trash' => 'Trash',
		),
	),
);

$affiliate = array(
	array(
		'type'  => 'heading',
		'title' => __( 'Affiliate', 'wds-notrans' ),
	),
	array(
		'id'      => '_affiliate_status',
		'type'    => 'select',
		'title'   => __( 'Status Affiliasi', 'weddingsaas' ),
		'options' => array(
			''         => __( 'Aktif', 'weddingsaas' ),
			'inactive' => __( 'Tidak Aktif', 'weddingsaas' ),
		),
	),
	array(
		'id'    => '_affiliate_commission',
		'type'  => 'number',
		'title' => __( 'Komisi Afiliasi Kustom (%)', 'weddingsaas' ),
	),
	array(
		'id'         => '_affiliate_payment_method',
		'type'       => 'text',
		'title'      => __( 'Metode Pembayaran', 'weddingsaas' ),
		'attributes' => array( 'readonly' => 'readonly' ),
	),
	array(
		'id'         => '_affiliate_payment_method_account',
		'type'       => 'textarea',
		'title'      => __( 'Metode Pembayaran Akun', 'weddingsaas' ),
		'attributes' => array( 'readonly' => 'readonly' ),
	),
);

$reseller = array(
	array(
		'type'       => 'heading',
		'title'      => __( 'Branding Reseller', 'wds-notrans' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_branding_name',
		'type'       => 'text',
		'title'      => __( 'Nama Brand', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_branding_favicon',
		'type'       => 'upload',
		'library'    => 'image',
		'preview'    => true,
		'title'      => __( 'Favicon', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_branding_logo',
		'type'       => 'upload',
		'library'    => 'image',
		'preview'    => true,
		'title'      => __( 'Logo Brand', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_branding_link',
		'type'       => 'text',
		'title'      => __( 'Logo Link', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_branding_description',
		'type'       => 'text',
		'title'      => __( 'Deskripsi', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_invitation_price',
		'type'       => 'number',
		'title'      => __( 'Harga Undangan', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_instagram',
		'type'       => 'text',
		'title'      => __( 'Link Instagram', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_facebook',
		'type'       => 'text',
		'title'      => __( 'Link Facebook', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_tiktok',
		'type'       => 'text',
		'title'      => __( 'Link Tiktok', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_twitter',
		'type'       => 'text',
		'title'      => __( 'Link Twitter (X)', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
	array(
		'id'         => '_youtube',
		'type'       => 'text',
		'title'      => __( 'Link Youtube', 'weddingsaas' ),
		'dependency' => array( '_wds_user_group', '==', 'reseller' ),
	),
);

$fields = array_merge( $primary, $invitation, $affiliate, $reseller );

CSF::createProfileOptions(
	$prefix,
	array(
		'data_type' => 'unserialize',
		'class'     => ' wds-profile',
	)
);

CSF::createSection(
	$prefix,
	array(
		'fields' => $fields,
	)
);
