<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @since 2.0.0
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_plugin_active( 'weddingsaas-pro/weddingsaas.php' ) ) {
	return;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/v1.php';

$prefix = 'wds_pro';

// Installed
delete_transient( '_wds_installed' );

// Session
delete_option( 'wds_use_php_sessions' );

// License
delete_option( $prefix . '_license' );
delete_transient( $prefix . '_license_check' );

// Cronjob
wp_clear_scheduled_hook( 'wds/cron/15minutes' );
wp_clear_scheduled_hook( 'wds/cron/hourly' );
wp_clear_scheduled_hook( 'wds/cron/daily' );

// Role
remove_role( 'wds-member' );

// Settings
delete_option( $prefix . '_settings' );

// Engine
delete_option( $prefix . '_engine' );

// Language
delete_option( $prefix . '_lang' );

// Membership
delete_option( $prefix . '_membership' );

// Addons
delete_option( $prefix . '_addons' );
