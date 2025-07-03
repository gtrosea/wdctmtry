<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Upgrade', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'upgrade_coupon',
				'type'    => 'select',
				'title'   => __( 'Kupon Default', 'weddingsaas' ),
				'desc'    => __( 'Pilih kupon default untuk checkout.', 'weddingsaas' ),
				'options' => wds_coupon_list_for_upgrade(),
				'default' => wds_v1_option( 'membership_upgrade_coupon' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Tombol Upgrade Ketika Kadaluarsa Untuk Trial & Member', 'weddingsaas' ),
			),
			array(
				'id'      => 'upgrade_type',
				'type'    => 'select',
				'title'   => __( 'Type', 'wds-notrans' ),
				'options' => array(
					''    => __( 'Default', 'wds-notrans' ),
					'url' => __( 'Custome', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'membership_upgrade_conditional' ),
			),
			array(
				'id'          => 'upgrade_link',
				'type'        => 'text',
				'title'       => __( 'Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'membership_upgrade_url', home_url() ),
				'dependency'  => array( 'upgrade_type', '==', 'url' ),
			),
			array(
				'id'          => 'upgrade',
				'type'        => 'select',
				'title'       => __( 'Products', 'wds-notrans' ),
				'desc'        => __( 'Jika tidak dipilih, semua produk ditampilkan pada halaman upgrade.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_product_membership( 'upgrade', true ),
				'default'     => wds_v1_upgrade_member(),
				'dependency'  => array( 'upgrade_type', '==', '' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Tombol Upgrade Ketika Kadaluarsa Untuk Reseller', 'weddingsaas' ),
			),
			array(
				'id'      => 'upgrade_reseller_type',
				'type'    => 'select',
				'title'   => __( 'Type', 'wds-notrans' ),
				'options' => array(
					''    => __( 'Default', 'wds-notrans' ),
					'url' => __( 'Custome', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'membership_upgrade_reseller_conditional' ),
			),
			array(
				'id'          => 'upgrade_reseller_link',
				'type'        => 'text',
				'title'       => __( 'Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'membership_upgrade_reseller_url', home_url() ),
				'dependency'  => array( 'upgrade_reseller_type', '==', 'url' ),
			),
			array(
				'id'          => 'upgrade_reseller',
				'type'        => 'select',
				'title'       => __( 'Products', 'wds-notrans' ),
				'desc'        => __( 'Jika tidak dipilih, semua produk ditampilkan pada halaman upgrade.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_product_membership( 'upgrade_reseller', true ),
				'default'     => wds_v1_upgrade_reseller(),
				'dependency'  => array( 'upgrade_reseller_type', '==', '' ),
			),

			array(
				'type'  => 'subheading',
				'title' => __( 'Tombol Upgrade Ketika Kuota Habis Untuk Reseller', 'weddingsaas' ),
			),
			array(
				'id'      => 'upgrade_quota_type',
				'type'    => 'select',
				'title'   => __( 'Type', 'wds-notrans' ),
				'options' => array(
					''    => __( 'Default', 'wds-notrans' ),
					'url' => __( 'Custome', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'membership_upgrade_quota_conditional' ),
			),
			array(
				'id'          => 'upgrade_quota_link',
				'type'        => 'text',
				'title'       => __( 'Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'membership_upgrade_quota_url', home_url() ),
				'dependency'  => array( 'upgrade_quota_type', '==', 'url' ),
			),
			array(
				'id'          => 'upgrade_quota',
				'type'        => 'select',
				'title'       => __( 'Products', 'wds-notrans' ),
				'desc'        => __( 'Jika tidak dipilih, semua produk ditampilkan pada halaman upgrade.', 'weddingsaas' ),
				'placeholder' => __( 'Pilih produk', 'weddingsaas' ),
				'chosen'      => true,
				'multiple'    => true,
				'options'     => wds_get_product_membership( 'upgrade_quota', true ),
				'default'     => wds_v1_upgrade_quota(),
				'dependency'  => array( 'upgrade_quota_type', '==', '' ),
			),
		),
	)
);
