<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Runs on plugin install.
 *
 * @since 2.0.0
 */
function wds_install() {
	// Setup custom post type
	// wds_setup_post_types();

	// Install the database tabel
	wds_setup_tables();

	// Clear the permalinks
	flush_rewrite_rules();

	// Create new roles
	WDS()->roles->add_roles();

	// Check for PHP Session support, and enable if available
	WDS()->session->use_php_sessions();

	update_option( WDS_SLUG . '_installed', true );
	update_option( WDS_SLUG . '_version', WDS_VERSION );

	$license_old = get_option( 'weddingsaas_pro_license' );
	$engine_old  = get_option( 'wdss_settings' );

	$license = get_option( WDS_SLUG . '_license' );
	$engine  = get_option( WDS_SLUG . '_engine' );

	if ( ! empty( $license_old ) && empty( $license ) ) {
		update_option( WDS_SLUG . '_license', $license_old );
	}

	if ( ! empty( $engine_old ) && empty( $engine ) ) {
		update_option( WDS_SLUG . '_engine', $engine_old );
	}
}

// /**
//  * Post-installation.
//  *
//  * Runs just after plugin installation and exposes the wds_after_install hook.
//  *
//  * @since 2.0.0
//  */
// function wds_after_install() {
//  if ( ! is_admin() ) {
//      return;
//  }

//  $wds_installed = get_transient( '_wds_installed' );

//  do_action( 'wds_after_install', $wds_installed );

//  if ( false !== $wds_installed ) {
//      delete_transient( '_wds_installed' );
//  }
// }
// add_action( 'admin_init', 'wds_after_install' );
