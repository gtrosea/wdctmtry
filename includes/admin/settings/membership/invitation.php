<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$list = wds_v1_option( 'invitation_list_layout' );

CSF::createSection(
	$prefix,
	array(
		'parent' => 'membership',
		'title'  => __( 'Invitation', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'invitation_type',
				'type'    => 'select',
				'title'   => __( 'Menu Type', 'wds-notrans' ),
				'options' => array(
					''    => __( 'Default', 'wds-notrans' ),
					'url' => __( 'Custome', 'wds-notrans' ),
				),
				'default' => wds_v1_option( 'invitation_menu_conditional' ),
			),
			array(
				'id'          => 'invitation_link',
				'type'        => 'text',
				'title'       => __( 'Menu Link', 'wds-notrans' ),
				'placeholder' => home_url(),
				'default'     => wds_v1_option( 'invitation_menu_link', wds_url( 'invitation' ) ),
				'dependency'  => array( 'invitation_type', '==', 'url' ),
			),
			array(
				'id'         => 'invitation_status',
				'type'       => 'select',
				'title'      => __( 'Status Undangan', 'weddingsaas' ),
				'desc'       => __( 'Pilih status undangan setelah undangan dibuat.', 'weddingsaas' ),
				'options'    => array(
					'publish' => __( 'Publish', 'wds-notrans' ),
					'pending' => __( 'Pending', 'wds-notrans' ),
				),
				'default'    => wds_v1_option( 'invitation_submit_status', 'publish' ),
				'dependency' => array( 'invitation_type', '==', '' ),
			),
			array(
				'id'         => 'invitation_form_layout',
				'type'       => 'select',
				'title'      => __( 'Layout Form Undangan', 'weddingsaas' ),
				'desc'       => __( 'Pilih layout untuk formulir undangan.', 'weddingsaas' ),
				'options'    => array(
					'1' => __( 'Versi 1', 'weddingsaas' ),
					'2' => __( 'Versi 2', 'weddingsaas' ),
				),
				'default'    => wds_v1_option( 'invitation_form_layout', '2' ),
				'dependency' => array( 'invitation_type', '==', '' ),
			),
			array(
				'id'         => 'invitation_list_layout',
				'type'       => 'select',
				'title'      => __( 'Layout List Undangan', 'weddingsaas' ),
				'desc'       => __( 'Pilih layout untuk list undangan.', 'weddingsaas' ),
				'options'    => array(
					'1' => __( 'Versi 1 (Table)', 'weddingsaas' ),
					'2' => __( 'Versi 2 (Grid)', 'weddingsaas' ),
				),
				'default'    => empty( $list ) ? '2' : ( 'grid' == $list ? '2' : '1' ),
				'dependency' => array( 'invitation_type', '==', '' ),
			),
			array(
				'id'         => 'invitation_action_layout',
				'type'       => 'select',
				'title'      => __( 'Layout Tombol Action', 'weddingsaas' ),
				'desc'       => __( 'Pilih layout untuk tombol action.', 'weddingsaas' ),
				'options'    => array(
					'1' => __( 'Versi 1', 'weddingsaas' ),
					'2' => __( 'Versi 2', 'weddingsaas' ),
				),
				'default'    => wds_v1_option( 'invitation_action_layout', '2' ),
				'dependency' => array( 'invitation_type|invitation_list_layout', '==|==', '|1' ),
			),
			array(
				'id'         => 'invitation_query',
				'type'       => 'text',
				'title'      => __( 'Query Undangan', 'weddingsaas' ),
				'desc'       => __( 'Query tambahan pada tombol buka undangan.', 'weddingsaas' ),
				'default'    => wds_v1_option( 'invitation_open_query', '?to=Nama+Tamu' ),
				'dependency' => array( 'invitation_type', '==', '' ),
			),
			array(
				'id'      => 'invitation_copy_link',
				'type'    => 'switcher',
				'title'   => __( 'Nonaktifkan Fitur Copy Link', 'weddingsaas' ),
				'default' => wds_v1_option( 'invitation_edit_copy_link' ),
			),
		),
	)
);
