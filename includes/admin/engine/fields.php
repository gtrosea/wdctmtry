<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$prefix = WDS_SLUG . '_engine';

CSF::createOptions(
	$prefix,
	array(
		'menu_title'      => 'WDS Engine',
		'menu_slug'       => 'wds-engine',
		'menu_type'       => 'menu',
		'menu_capability' => 'manage_options',
		'menu_icon'       => WDS_ICON,
		'menu_position'   => 42.3322,
		'show_in_network' => false,
		'show_bar_menu'   => false,
		'show_sub_menu'   => false,
		'theme'           => 'light',
		'class'           => 'wds-framework',
		'framework_title' => wp_kses_post( 'WDS Engine<br/><small>Version: ' . WDS_VERSION . '</small>' ),
		'footer_text'     => wp_kses_post( 'The Plugin will Created By <a href="https://pelatform.com" target="_blank">Pelatform Dev</a>' ),
	)
);

require_once '_modules.php';

CSF::createSection(
	$prefix,
	array(
		'id'    => 'engine',
		'icon'  => 'fa fa-rocket',
		'title' => __( 'Engine', 'wds-notrans' ),
	)
);

require_once 'engine/metaboxes.php';
require_once 'engine/post-types.php';
require_once 'engine/taxonomies.php';
require_once 'engine/shortcode.php';

CSF::createSection(
	$prefix,
	array(
		'id'    => 'shortcode',
		'icon'  => 'fa fa-laptop-code',
		'title' => __( 'Shortcode', 'wds-notrans' ),
	)
);

if ( wds_engine( 'module_audio' ) ) {
	require_once 'shortcode/audio.php';
}

if ( wds_engine( 'module_tema' ) ) {
	require_once 'shortcode/theme.php';
}

CSF::createSection(
	$prefix,
	array(
		'id'    => 'content',
		'icon'  => 'fa fa-chalkboard',
		'title' => __( 'Contents', 'wds-notrans' ),
	)
);

require_once 'content/auto-insert.php';
require_once 'content/tracking.php';
require_once 'content/sales-proof.php';
require_once 'content/custome-menu.php';

CSF::createSection(
	$prefix,
	array(
		'id'    => 'tools',
		'icon'  => 'fa fa-tools',
		'title' => __( 'Tools', 'wds-notrans' ),
	)
);

require_once 'tools/component.php';
require_once 'tools/optimization.php';
require_once 'tools/notification.php';
require_once 'tools/contact.php';
require_once 'tools/validation.php';

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Misc', 'wds-notrans' ),
		'icon'   => 'fa fa-window-restore',
		'fields' => array(
			array(
				'title' => __( 'Ekspor Impor Data', 'weddingsaas' ),
				'type'  => 'backup',
			),
		),
	)
);
