<?php
/**
 * WeddingSaas Handler.
 *
 * This class handles the license of the plugin.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Handler Class.
 */
class WDS_Handler {

	/**
	 * Init.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'activate_licenses' ) );
		add_action( 'admin_init', array( __CLASS__, 'deactivate_licenses' ) );
		add_action( 'admin_init', array( __CLASS__, 'update_plugins' ) );
		add_action( 'admin_init', array( __CLASS__, 'check_system' ) );
		add_action( 'wds/cron/weekly', array( __CLASS__, 'license_check' ) );

		add_action( 'admin_notices', array( __CLASS__, 'activation_message' ) );
		add_action( 'admin_notices', array( __CLASS__, 'inactive_licenses_message' ) );

		add_filter( 'wds_pro_products', array( __CLASS__, 'register_weddingsaas' ) );
	}

	/**
	 * Process POST activation request from license page.
	 */
	public static function activate_licenses() {
		if ( ! isset( $_POST['weddingsaas_action'] ) || 'license_activation' != $_POST['weddingsaas_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['weddingsaas_nonce'], 'license_activation' ) ) {
			return;
		}

		if ( ! wds_is_admin() ) {
			return;
		}

		$products = wds_get_products();
		foreach ( $products as $id => $args ) {
			if ( ! isset( $_POST[ $id . '_activation' ] ) ) {
				continue;
			}

			$license_key = trim( $_POST[ $id . '_license_key' ] );
			$api_params  = array(
				'edd_action' => 'activate_license',
				'item_name'  => rawurlencode( $args['name'] ),
				'item_id'    => rawurlencode( $args['item_id'] ),
				'license'    => $license_key,
				'url'        => home_url(),
			);

			$response = wp_remote_get(
				add_query_arg( $api_params, WDS_STORE ),
				array(
					'timeout'   => 15,
					'sslverify' => false,
				)
			);

			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ), true );

			$license_data['key'] = $license_key;

			update_option( $id . '_license', $license_data );

			if ( 'valid' == $license_data['license'] ) {
				$redirect_url = add_query_arg(
					array(
						'product'        => rawurlencode( $args['name'] ),
						'license_status' => 'activated',
					),
					menu_page_url( 'weddingsaas-license', false )
				);
				if ( WDS_SLUG == $id ) {
					update_option( WDS_SLUG . '_is_valid', true );
				}
			} else {
				$redirect_url = add_query_arg(
					array(
						'product'        => rawurlencode( $args['name'] ),
						'license_status' => 'invalid',
					),
					menu_page_url( 'weddingsaas-license', false )
				);
			}

			wds_redirect( $redirect_url );
		}
	}

	/**
	 * Process POST deactivation request from license page.
	 */
	public static function deactivate_licenses() {
		if ( ! isset( $_POST['weddingsaas_action'] ) || 'license_activation' != $_POST['weddingsaas_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['weddingsaas_nonce'], 'license_activation' ) ) {
			return;
		}

		if ( ! wds_is_admin() ) {
			return;
		}

		$products = wds_get_products();
		foreach ( $products as $id => $args ) {
			if ( ! isset( $_POST[ $id . '_deactivation' ] ) ) {
				continue;
			}

			$license    = get_option( $id . '_license', array() );
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'item_name'  => rawurlencode( $args['name'] ),
				'item_id'    => rawurlencode( $args['item_id'] ),
				'license'    => $license['key'],
				'url'        => home_url(),
			);

			$response = wp_remote_get(
				add_query_arg( $api_params, WDS_STORE ),
				array(
					'timeout'   => 15,
					'sslverify' => false,
				)
			);

			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( true === $license_data['success'] ) {
				$redirect_url = add_query_arg(
					array(
						'product'        => rawurlencode( $args['name'] ),
						'license_status' => 'deactivated',
					),
					menu_page_url( 'weddingsaas-license', false )
				);

				// Update product license data
				update_option( $id . '_license', $license_data );
				if ( WDS_SLUG == $id ) {
					delete_option( WDS_SLUG . '_is_valid' );
				}
			} else {
				$redirect_url = add_query_arg(
					array(
						'product'        => rawurlencode( $args['name'] ),
						'license_status' => 'deactivated',
					),
					menu_page_url( 'weddingsaas-license', false )
				);
				update_option( $id . '_license', $license_data );
			}

			wds_redirect( $redirect_url );
		}
	}

	/**
	 * Check system
	 */
	public static function check_system() {
		if ( ! wds_is_admin() ) {
			return;
		}

		$products = wds_get_products();
		foreach ( $products as $id => $args ) {
			$check = get_transient( $id . '_license_check' );
			if ( false !== $check ) {
				continue;
			}

			$license_data = get_option( $id . '_license', array() );
			$license_key  = wds_sanitize_data_field( $license_data, 'key' );
			$api_params   = array(
				'edd_action' => 'check_license',
				'item_name'  => rawurlencode( $args['name'] ),
				'item_id'    => rawurlencode( $args['item_id'] ),
				'license'    => $license_key,
				'url'        => wds_home_url(),
			);

			$response = wp_remote_get(
				add_query_arg( $api_params, WDS_STORE ),
				array(
					'timeout'   => 15,
					'sslverify' => false,
				)
			);

			if ( is_wp_error( $response ) ) {
				wds_log( 'Request failed: ' . $response->get_error_message() );
				return;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ), true );
			// wds_log( $license_data, true );

			$license_data['key'] = $license_key;

			update_option( $id . '_license', $license_data );

			set_transient( $id . '_license_check', 1, 3 * DAY_IN_SECONDS );
		}

		if ( wds_is_active( WDS_SLUG ) && ! get_option( WDS_SLUG . '_is_valid' ) ) {
			update_option( WDS_SLUG . '_is_valid', true );
		}
	}

	/**
	 * License check.
	 *
	 * @since 1.0.4
	 */
	public static function license_check() {
		if ( ! wds_is_admin() && ! wds_doing_cron() ) {
			return;
		}

		$products = wds_get_products();
		foreach ( $products as $id => $args ) {
			$license_data = get_option( $id . '_license', array() );
			$license_key  = isset( $license_data['key'] ) ? trim( $license_data['key'] ) : '';
			if ( empty( $license_key ) ) {
				continue;
			}

			$api_params = array(
				'edd_action' => 'check_license',
				'item_name'  => rawurlencode( $args['name'] ?? null ),
				'item_id'    => rawurlencode( $args['item_id'] ?? null ),
				'license'    => $license_key,
				'url'        => wds_home_url(),
			);

			$response = wp_remote_get(
				add_query_arg( $api_params, WDS_STORE ),
				array(
					'timeout'   => 15,
					'sslverify' => false,
				)
			);

			if ( is_wp_error( $response ) ) {
				wds_log( 'Request failed: ' . $response->get_error_message() );
				continue;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ), true );
			// wds_log( $license_data, true );
			if ( isset( $license_data['license'] ) && 'valid' !== $license_data['license'] ) {
				delete_option( $id . '_license' );
				delete_transient( $id . '_license_check' );
				if ( WDS_SLUG == $id ) {
					delete_option( WDS_SLUG . '_is_valid' );
				}
			} else {
				set_transient( $id . '_license_check', 1, 3 * DAY_IN_SECONDS );
			}
		}
	}

	/**
	 * Update all valid WeddingSaas products.
	 */
	public static function update_plugins() {
		// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
		if ( ! wds_is_admin() && ! wds_doing_cron() ) {
			return;
		}

		include WDS_INCLUDES . 'classes/updater.php';

		$products = wds_get_products();
		foreach ( $products as $id => $args ) {
			$license = get_option( $id . '_license' );
			if ( ! isset( $license['license'] ) || 'valid' != $license['license'] ) {
				continue;
			}

			$license_key = isset( $license['key'] ) ? trim( $license['key'] ) : null;

			$updater = new WDS_Updater(
				WDS_STORE,
				$args['file'],
				array(
					'version'   => $args['version'],
					'license'   => $license_key,
					'item_name' => $args['name'],
					'item_id'   => $args['item_id'],
					'author'    => 'WeddingSaas Team',
					'url'       => home_url(),
					'beta'      => false,
				)
			);
		}
	}

	/**
	 * Output license activation message.
	 */
	public static function activation_message() {
		if ( ! isset( $_GET['page'] ) || 'weddingsaas-license' != $_GET['page'] ) {
			return;
		}

		if ( ! isset( $_GET['product'] ) || ! isset( $_GET['license_status'] ) ) {
			return;
		}

		$support_url = WDS_STORE . 'support/';

		if ( 'activated' == $_GET['license_status'] ) {
			$message = sprintf(
				/* translators: %s: The product name */
				__( 'Lisensi %s telah berhasil diaktifkan.', 'weddingsaas' ),
				$_GET['product']
			);
			$class = 'success';
		} elseif ( 'deactivated' == $_GET['license_status'] ) {
			$message = sprintf(
				/* translators: %s: The product name */
				__( 'Lisensi %s telah berhasil dinonaktifkan.', 'weddingsaas' ),
				$_GET['product']
			);
			$class = 'success';
		} elseif ( 'deactivation_failed' == $_GET['license_status'] ) {
			$message = sprintf(
				/* translators: %1s: The product name, %2s: The support url */
				__( 'Lisensi %1$s belum dinonaktifkan. Silakan coba lagi. Jika tidak, silakan hubungi dukungan kami <a href="%2$s" target="_blank">di sini</a>.', 'weddingsaas' ),
				$_GET['product'],
				$support_url
			);
			$class = 'error';
		} else {
			$message = sprintf(
				/* translators: %1s: The product name, %2s: The support url */
				__( 'Lisensi %1$s tidak valid. Lisensi tersebut telah kedaluwarsa atau ada yang salah. Harap periksa kunci lisensi Anda dan coba lagi. Jika tidak, harap hubungi dukungan kami <a href="%2$s" target="_blank">di sini</a>.', 'weddingsaas' ),
				$_GET['product'],
				$support_url
			);
			$class = 'error';
		}

		echo "<script>
            jQuery(document).ready(function($) {
                var current_url = window.location.href;
                var modified_url = current_url.replace(/\&?(product|license_status).*/, '');
                window.history.pushState('weddingsaas', '', modified_url);
			});
        </script>";

		wds_add_notice( $message, $class . ' is-dismissible' );
	}

	/**
	 * Display notice inactive license.
	 */
	public static function inactive_licenses_message() {
		$products = wds_get_products();

		foreach ( $products as $id => $args ) {
			if ( ! wds_is_product_active( $id ) ) {
				$message = sprintf(
					/* translators: %1$s: Url license page, %2$s: Product name, %3$s: Member area url  */
					__( '<a href="%1$s">Aktifkan</a> %2$s untuk mendapatkan pembaruan dan dukungan otomatis. Anda bisa mendapatkan kunci lisensi Anda <a href="%3$s" target="_blank">di sini</a>.', 'weddingsaas' ),
					menu_page_url( 'weddingsaas-license', false ),
					$args['name'],
					WDS_STORE . 'dashboard/'
				);

				wds_add_notice( $message, 'warning' );
			}
		}
	}

	/**
	 * Register WDS Pro product.
	 *
	 * @param array $products The list product.
	 */
	public static function register_weddingsaas( $products ) {
		$products[ WDS_SLUG ] = array(
			'name'    => WDS_NAME,
			'item_id' => WDS_ITEM_ID,
			'file'    => WDS_FILE,
			'version' => WDS_VERSION,
		);

		return $products;
	}
}

WDS_Handler::init();
