<?php
/**
 * Plugin Name:         WeddingSaas Membership Pro
 * Plugin URI:          https://weddingsaas.id/
 * Description:         All in one Wedding Service Management plugin.
 * Version:             2.4.0
 * Author:              WeddingSaas Team
 * Author URI:          https://weddingsaas.id/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         weddingsaas
 * Domain Path:         /languages
 * Requires at least:   6.3.0
 * Requires PHP:        7.4
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Plugin root file
if ( ! defined( 'WDS_FILE' ) ) {
	define( 'WDS_FILE', __FILE__ );
}

// Plugin base name
if ( ! defined( 'WDS_BASE' ) ) {
	define( 'WDS_BASE', plugin_basename( __FILE__ ) );
}

// Plugin folder path
if ( ! defined( 'WDS_PATH' ) ) {
	define( 'WDS_PATH', plugin_dir_path( __FILE__ ) );
}

// Plugin folder URL
if ( ! defined( 'WDS_URL' ) ) {
	define( 'WDS_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin folder includes
if ( ! defined( 'WDS_INCLUDES' ) ) {
	define( 'WDS_INCLUDES', WDS_PATH . 'includes/' );
}

// Plugin folder templates
if ( ! defined( 'WDS_TEMPLATES' ) ) {
	define( 'WDS_TEMPLATES', WDS_PATH . 'templates/' );
}

// Plugin name
if ( ! defined( 'WDS_NAME' ) ) {
	define( 'WDS_NAME', 'WeddingSaaS Pro' );
}

// Plugin version
if ( ! defined( 'WDS_VERSION' ) ) {
	define( 'WDS_VERSION', '2.4.0' );
}

// Plugin item ID
if ( ! defined( 'WDS_ITEM_ID' ) ) {
	define( 'WDS_ITEM_ID', 23 );
}

// Plugin store url
if ( ! defined( 'WDS_STORE' ) ) {
	define( 'WDS_STORE', 'https://weddingsaas.id/' );
}

// Plugin model
if ( ! defined( 'WDS_MODEL' ) ) {
	define( 'WDS_MODEL', 'wds' );
}

// Plugin slug
if ( ! defined( 'WDS_SLUG' ) ) {
	define( 'WDS_SLUG', 'wds_pro' );
}

// WDS Logo
if ( ! defined( 'WDS_LOGO' ) ) {
	define( 'WDS_LOGO', WDS_URL . 'assets/img/logo/icon.png' );
}

// WDS Icon
if ( ! defined( 'WDS_ICON' ) ) {
	define( 'WDS_ICON', WDS_URL . 'assets/img/logo/iconx20.png' );
}

// WDS Thumbnail
if ( ! defined( 'WDS_BLANK_IMAGE' ) ) {
	define( 'WDS_BLANK_IMAGE', WDS_URL . 'assets/img/blank-image.svg' );
}

// Require PHP Version
if ( ! defined( 'WDS_PHP_VERSION' ) ) {
	define( 'WDS_PHP_VERSION', '7.4' );
}

// Require WordPress Version
if ( ! defined( 'WDS_WP_VERSION' ) ) {
	define( 'WDS_WP_VERSION', '6.3.0' );
}

if ( class_exists( 'WeddingSaas' ) ) {

	if ( ! function_exists( 'weddingsaas_pro_just_activated' ) ) {
		/**
		 * When we activate a Pro version, we need to do additional operations:
		 * 1) deactivate a Lite version;
		 * 2) register an option so we know when Pro was activated.
		 */
		function weddingsaas_pro_just_activated() {
			if ( ! get_option( 'weddingsaas_pro_activation_date', false ) ) {
				update_option( 'weddingsaas_pro_activation_date', time() );
			}
			weddingsaas_deactivate();
		}
	}
	add_action( 'activate_weddingsaas-pro/weddingsaas.php', 'weddingsaas_pro_just_activated' );

	if ( ! function_exists( 'weddingsaas_lite_just_activated' ) ) {
		/**
		 * Store temporarily that the Lite version of the plugin was activated.
		 * This is needed because WP does a redirect after activation and
		 * we need to preserve this state to know whether user activated Lite or not.
		 */
		function weddingsaas_lite_just_activated() {
			set_transient( 'weddingsaas_lite_just_activated', true );
		}
	}
	add_action( 'activate_weddingsaas/weddingsaas.php', 'weddingsaas_lite_just_activated' );

	if ( ! function_exists( 'weddingsaas_lite_just_deactivated' ) ) {
		/**
		 * Store temporarily that Lite plugin was deactivated.
		 * Convert temporary "activated" value to a global variable,
		 * so it is available through the request. Remove from the storage.
		 */
		function weddingsaas_lite_just_deactivated() {
			global $weddingsaas_lite_just_activated, $weddingsaas_lite_just_deactivated;

			$weddingsaas_lite_just_activated   = (bool) get_transient( 'weddingsaas_lite_just_activated' );
			$weddingsaas_lite_just_deactivated = true;

			delete_transient( 'weddingsaas_lite_just_activated' );
		}
	}
	add_action( 'deactivate_weddingsaas/weddingsaas.php', 'weddingsaas_lite_just_deactivated' );

	if ( ! function_exists( 'weddingsaas_deactivate' ) ) {
		/**
		 * Deactivate Lite if WeddingSaas Pro already activated.
		 */
		function weddingsaas_deactivate() {
			$plugin = 'weddingsaas/weddingsaas.php';

			deactivate_plugins( $plugin );

			do_action( 'weddingsaas_plugin_deactivated', $plugin );
		}
	}
	add_action( 'admin_init', 'weddingsaas_deactivate' );

	if ( ! function_exists( 'weddingsaas_lite_notice' ) ) {
		/**
		 * Display the notice after deactivation when Pro is still active
		 * and user wanted to activate the Lite version of the plugin.
		 */
		function weddingsaas_lite_notice() {
			global $weddingsaas_lite_just_activated, $weddingsaas_lite_just_deactivated;

			if ( empty( $weddingsaas_lite_just_activated ) || empty( $weddingsaas_lite_just_deactivated ) ) {
				return;
			}

			// Currently tried to activate Lite with Pro still active, so display the message.
			printf(
				'<div class="notice notice-warning">
					<p>%1$s</p>
					<p>%2$s</p>
				</div>',
				esc_html__( 'Hati-hati!', 'weddingsaas' ),
				esc_html__( 'Situs Anda sudah mengaktifkan WeddingSaas (Pro). Jika Anda ingin beralih ke WeddingSaas (Lite), pertama-tama buka Plugins → Installed Plugins dan nonaktifkan WeddingSaas (Pro). Kemudian, Anda dapat mengaktifkan WeddingSaas (Lite).', 'weddingsaas' )
			);

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			unset( $weddingsaas_lite_just_activated, $weddingsaas_lite_just_deactivated );
		}
	}
	add_action( 'admin_notices', 'weddingsaas_lite_notice' );

	// Do not process the plugin code further.
	return;
}

require_once WDS_INCLUDES . 'class-requirements-check.php';
new WeddingSaas_Requirements_Check();
