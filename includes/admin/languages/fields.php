<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$prefix = WDS_SLUG . '_lang';

CSF::createOptions(
	$prefix,
	array(
		'menu_title'      => 'Languages',
		'menu_slug'       => 'weddingsaas-languages',
		'menu_type'       => 'menu',
		'menu_capability' => 'manage_options',
		'theme'           => 'light',
		'menu_hidden'     => true,
		'show_bar_menu'   => false,
		'sticky_header'   => false,
		'class'           => 'wds-framework',
		'framework_title' => wp_kses_post( 'WDS Languages<br/><small>Version: ' . WDS_VERSION . '</small>' ),
		'footer_text'     => wp_kses_post( 'The Plugin will Created By <a href="https://pelatform.com" target="_blank">Pelatform Dev</a>' ),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Settings', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => '_default',
				'type'    => 'select',
				'title'   => __( 'Bahasa Default', 'weddingsaas' ),
				'desc'    => __( 'Silahkan simpan & reload setelah mengganti bahasa default.', 'weddingsaas' ),
				'chosen'  => true,
				'options' => array( 'ID' => 'Indonesia' ),
				'default' => 'ID',
			),
		),
	)
);

require_once '_general.php';
require_once '_auth.php';
require_once '_account.php';
require_once '_transaction.php';
require_once '_dashboard.php';
require_once '_public.php';
require_once '_rsvp.php';

if ( wds_is_theme() ) {
	require_once '_theme.php';
}

if ( wds_is_replica() ) {
	require_once '_replica.php';
}

do_action( 'wds_languages', $prefix );

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Misc', 'wds-notrans' ),
		'fields' => array(
			array(
				'title' => __( 'Ekspor Impor Data', 'weddingsaas' ),
				'type'  => 'backup',
			),
		),
	)
);
