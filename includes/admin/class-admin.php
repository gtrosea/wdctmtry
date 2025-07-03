<?php
/**
 * WeddingSaas Admin.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Admin
 */

namespace WDS\Admin;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Admin Class.
 */
class Admin {

	/**
	 * @var Menu|null
	 */
	public $menu = null;

	/**
	 * @var Pages|null
	 */
	public $pages = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->menu  = new Menu();
		$this->pages = new Pages();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'parent_file', array( $this, 'modify_seperator_menu' ) );
		add_action( 'in_admin_header', array( $this, 'hide_all_admin_notices' ) );

		if ( ! wds_is_active() ) {
			add_action( 'admin_menu', array( $this->menu, 'register_license_menu' ) );
		} else {
			add_action( 'admin_menu', array( $this->menu, 'register_primary_menu' ) );
			add_filter( 'admin_head', array( $this->menu, 'modify_menu_highlight' ), 9999 );
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_item' ), 500 );

			add_action( 'after_setup_theme', array( $this, 'register_fields' ), 999 );
			add_action( 'csf_' . WDS_SLUG . '_settings_save_after', array( $this, 'save_settings' ) );
			add_action( 'csf_' . WDS_SLUG . '_engine_save_after', array( $this, 'save_engine' ) );
			add_action( 'csf_' . WDS_SLUG . '_lang_save_after', array( $this, 'save_language' ) );

			add_filter( 'csf_' . WDS_SLUG . '_user_save', array( $this, 'modify_user_meta' ), 10, 2 );
			add_filter( 'csf_' . WDS_SLUG . '_post_save', array( $this, 'modify_post_meta' ), 10, 2 );

			add_action( 'edited_term', array( $this, 'update_term_meta' ), 10, 2 );
			add_action( 'profile_update', array( $this, 'update_user_meta' ), 10, 2 );
		}
	}

	/**
	 * Add enqueue scripts.
	 */
	public function enqueue_scripts() {
		$localize = array(
			'ajax_url'     => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'   => wp_create_nonce( 'wds_admin_nonce' ),
			'domain_nonce' => wp_create_nonce( 'custome_domain_nonce' ),
			'admin_url'    => admin_url(),
			'invoice_text' => __( 'Apakah Anda yakin ingin konfirmasi invoice', 'weddingsaas' ),
		);

		wp_enqueue_style( 'wds-admin', wds_assets( 'css/wds-admin.css' ), array(), WDS_VERSION );
		wp_enqueue_script( 'wds-admin', wds_assets( 'js/admin.js' ), array( 'jquery' ), WDS_VERSION, true );
		wp_localize_script( 'wds-admin', 'WdsAdmin', $localize );
	}

	/**
	 * Modify seperator menu.
	 *
	 * @param array $parent_file The parent file.
	 */
	public function modify_seperator_menu( $parent_file ) {
		$menu = &$GLOBALS['menu'];

		foreach ( $menu as $key => $item ) {
			if ( in_array( 'separator-wds-before', $item ) && 5 < count( $item ) ) {
				$menu[ $key ][2] = 'separator-wds-before';
				$menu[ $key ][4] = 'wp-menu-separator separator-wds separator-wds--before';
				unset( $menu[ $key ][5], $menu[ $key ][6] );
			}

			if ( in_array( 'separator-wds-after', $item ) && 5 < count( $item ) ) {
				$menu[ $key ][2] = 'separator-wds-after';
				$menu[ $key ][4] = 'wp-menu-separator separator-wds separator-wds--after';
				unset( $menu[ $key ][5], $menu[ $key ][6] );
			}

			if ( in_array( 'separator-wds-cpt', $item ) && 5 < count( $item ) ) {
				$menu[ $key ][2] = 'separator-wds-cpt';
				$menu[ $key ][4] = 'wp-menu-separator separator-wds separator-wds--cpt';
				unset( $menu[ $key ][5], $menu[ $key ][6] );
			}
		}

		return $parent_file;
	}

	/**
	 * Hide all notices from the page.
	 */
	public function hide_all_admin_notices() {
		if ( $this->pages->check() ) {
			global $wp_filter;

			$exclude_callbacks = array(
				array( 'WDS_Handler', 'activation_message' ),
				array( 'WDS_Handler', 'inactive_licenses_message' ),
			);

			$exclude_callbacks = apply_filters( 'wds_admin_hide_notices', $exclude_callbacks );

			if ( isset( $wp_filter['admin_notices'] ) ) {
				foreach ( $wp_filter['admin_notices']->callbacks as $priority => $callbacks ) {
					foreach ( $callbacks as $callback_id => $callback ) {
						if ( ! in_array( $callback['function'], $exclude_callbacks, true ) ) {
							remove_action( 'admin_notices', $callback['function'], $priority );
						}
					}
				}
			}

			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				foreach ( $wp_filter['all_admin_notices']->callbacks as $priority => $callbacks ) {
					foreach ( $callbacks as $callback_id => $callback ) {
						if ( ! in_array( $callback['function'], $exclude_callbacks, true ) ) {
							remove_action( 'all_admin_notices', $callback['function'], $priority );
						}
					}
				}
			}

			if ( wds_is_active() ) {
				add_action( 'admin_notices', array( $this->menu, 'add_header_menu' ), 1 );
			}
		}
	}

	/**
	 * Add admin bar menu.
	 *
	 * @param object $admin_bar The admin bar item.
	 */
	public function admin_bar_item( $admin_bar ) {
		if ( ! wds_is_admin() ) {
			return;
		}

		$admin_bar->add_menu(
			array(
				'id'     => 'wds-admin-bar',
				'parent' => null,
				'group'  => null,
				'title'  => 'WDS Dashboard',
				'href'   => wds_url( 'overview' ),
				'meta'   => array(
					'title'  => 'WDS Dashboard',
					'target' => '_blank',
				),
			)
		);

		$admin_bar->add_menu(
			array(
				'id'     => 'wds-admin-bar-affiliate',
				'parent' => 'wds-admin-bar',
				'title'  => __( 'Affiliate', 'wds-notrans' ),
				'href'   => wds_url( 'affiliate' ),
				'meta'   => array(
					'title'  => __( 'Affiliate', 'wds-notrans' ),
					'target' => '_blank',
				),
			)
		);

		$admin_bar->add_menu(
			array(
				'id'     => 'wds-admin-bar-payouts',
				'parent' => 'wds-admin-bar',
				'title'  => __( 'Affiliate Payouts', 'wds-notrans' ),
				'href'   => wds_url( 'payouts' ),
				'meta'   => array(
					'title'  => __( 'Affiliate Payouts', 'wds-notrans' ),
					'target' => '_blank',
				),
			)
		);

		$admin_bar->add_menu(
			array(
				'id'     => 'wds-admin-bar-statistics',
				'parent' => 'wds-admin-bar',
				'title'  => __( 'Statistics', 'wds-notrans' ),
				'href'   => wds_url( 'statistics' ),
				'meta'   => array(
					'title'  => __( 'Statistics', 'wds-notrans' ),
					'target' => '_blank',
				),
			)
		);

		$admin_bar->add_menu(
			array(
				'id'     => 'wds-admin-bar-users',
				'parent' => 'wds-admin-bar',
				'title'  => __( 'Users', 'wds-notrans' ),
				'href'   => wds_url( 'users' ),
				'meta'   => array(
					'title'  => __( 'Users', 'wds-notrans' ),
					'target' => '_blank',
				),
			)
		);
	}

	/**
	 * Register all fields setting for the plugin.
	 */
	public function register_fields() {
		if ( class_exists( 'CSF' ) ) {
			require_once WDS_INCLUDES . 'admin/option-v1.php';
			require_once WDS_INCLUDES . 'admin/addons.php';
			require_once WDS_INCLUDES . 'admin/settings/fields.php';
			require_once WDS_INCLUDES . 'admin/languages/fields.php';
			require_once WDS_INCLUDES . 'admin/engine/fields.php';

			require_once WDS_INCLUDES . 'admin/metabox/post.php';
			require_once WDS_INCLUDES . 'admin/metabox/taxonomy.php';
			require_once WDS_INCLUDES . 'admin/metabox/user.php';
		}
	}

	/**
	 * Run when save framework setting.
	 */
	public function save_settings() {
		wds_delete_cache( WDS_SLUG . '_settings' );

		if ( wds_option( 'account_activation' ) ) {
			$activation_time = get_option( 'account_activation_time' );
			if ( ! $activation_time ) {
				update_option( 'account_activation_time', current_time( 'mysql', true ) );
			}
		} else {
			$activation_time = get_option( 'account_activation_time' );
			if ( $activation_time ) {
				delete_option( 'account_activation_time' );
			}
		}

		$prefix  = WDS_SLUG . '_settings';
		$options = get_option( $prefix );

		$active_gateways = wds_get_active_gateways();
		if ( empty( $active_gateways ) ) {
			$options['gateway_active'] = array( 'banktransfer' );
			update_option( $prefix, $options );
			$active_gateways = wds_get_active_gateways();
		}

		$default_gateway = wds_get_default_gateway();
		if ( empty( $default_gateway ) || ! array_key_exists( $default_gateway, $active_gateways ) ) {
			$options['gateway_default'] = array_key_first( $active_gateways );
		}

		if ( ! \WDS\Gateway\Duitku::check() ) {
			// delete_transient( WDS_SLUG . '_duitku' );
			delete_option( WDS_SLUG . '_duitku' );
		}

		if ( ! \WDS\Gateway\Tripay::check() ) {
			// delete_transient( WDS_SLUG . '_tripay' );
			delete_option( WDS_SLUG . '_tripay' );
		}

		update_option( $prefix, $options );
	}

	/**
	 * Run when save framework engine.
	 */
	public function save_engine() {
		wds_delete_cache( WDS_SLUG . '_engine' );
		wp_cache_delete( 'wds_metaboxes_post', 'wds_metaboxes' );
		wp_cache_delete( 'wds_metaboxes_user', 'wds_metaboxes' );
		wp_cache_delete( 'wds_metaboxes_taxonomy', 'wds_metaboxes' );

		$api_mailketing = wds_engine( 'mailketing_api' );
		if ( $api_mailketing ) {
			\WDS\Engine\Tools\Contact::mailketing_fetch_list( $api_mailketing );
		}

		$api_starsender = wds_engine( 'starsender_api' );
		if ( $api_starsender ) {
			\WDS\Engine\Tools\Contact::starsender_fetch_group( $api_starsender );
		}

		$sendy_url   = wds_engine( 'sendy_url' );
		$sendy_api   = wds_engine( 'sendy_api' );
		$sendy_brand = wds_engine( 'sendy_brand_id' );
		if ( $sendy_url && $sendy_api && $sendy_brand ) {
			\WDS\Engine\Tools\Contact::sendy_fetch_list( $sendy_url, $sendy_api, $sendy_brand );
		}
	}

	/**
	 * Run when save framework language.
	 */
	public function save_language() {
		update_option( 'wds_language_selected', wds_lang( '_default' ) );

		wds_delete_cache( WDS_SLUG . '_lang' );
	}

	/**
	 * Modify user meta data before saving.
	 *
	 * @param mixed $data    The original data to be saved.
	 * @param int   $user_id The user ID for whom data is being saved.
	 * @return mixed         The modified data.
	 */
	public function modify_user_meta( $data, $user_id ) {
		if ( wds_check_array( $data, true ) && isset( $data['_wds_user_active_period'] ) ) {
			$active_period = $data['_wds_user_active_period'];
			if ( ! empty( $active_period ) ) {
				$data['_wds_user_active_period'] = strtotime( $active_period );
			}
		}

		return $data;
	}

	/**
	 * Modify post meta data before saving.
	 *
	 * @param mixed $data    The original data to be saved.
	 * @param int   $post_id The post ID for whom data is being saved.
	 * @return mixed         The modified data.
	 */
	public function modify_post_meta( $data, $post_id ) {
		if ( wds_check_array( $data, true ) ) {
			if ( isset( $data['_wds_pep_period'] ) ) {
				$pep_period = $data['_wds_pep_period'];
				if ( ! empty( $pep_period ) ) {
					$data['_wds_pep_period'] = strtotime( $pep_period );
				}
			}
			if ( isset( $data['_wds_del_period'] ) ) {
				$del_period = $data['_wds_del_period'];
				if ( ! empty( $del_period ) ) {
					$data['_wds_del_period'] = strtotime( $del_period );
				}
			}
		}

		return $data;
	}

	/**
	 * Update for term meta.
	 *
	 * @param int $term_id The ID of the term.
	 */
	public function update_term_meta( $term_id ) {
		wds_delete_cache( 'term_meta_' . intval( $term_id ) );
	}

	/**
	 * Update for user meta.
	 *
	 * @param int $user_id The ID of the user.
	 */
	public function update_user_meta( $user_id ) {
		wds_delete_cache_user( $user_id );

		$date       = wds_user_active_period( $user_id, true );
		$order_id   = wds_user_order_id( $user_id );
		$get_status = wds_user_status( $user_id );

		if ( 'active' == $get_status ) {
			$status = 'active';
		} else {
			$status = 'inactive';
		}

		if ( ! empty( $order_id ) ) {
			if ( $date ) {
				$data = array(
					'ID'         => $order_id,
					'expired_at' => gmdate( 'Y-m-d H:i:s', $date ),
					'status'     => $status,
				);
			} else {
				$data = array(
					'ID'         => $order_id,
					'expired_at' => 'NULL',
					'status'     => $status,
				);
			}

			wds_update_order( $data );
		}
	}
}

new Admin();
