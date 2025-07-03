<?php
/**
 * Admin Pages.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Admin
 */

namespace WDS\Admin;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Pages Class.
 */
class Pages {

	/**
	 * Get class menu.
	 */
	private function menu() {
		return new Menu();
	}

	/**
	 * Check if a page is WDS page.
	 *
	 * @param bool $session The page session.
	 */
	public function check( $session = true ) {
		$page   = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		$menus  = $this->menu()->define_all_menu();
		$datas  = '';

		if ( strpos( $page, 'weddingsaas' ) === 0 || strpos( $page, 'wds' ) === 0 ) {
			$group = isset( $menus[ $page ]['group'] ) ? $menus[ $page ]['group'] : '';
			$datas = array(
				'type'  => 'page',
				'id'    => $page,
				'group' => $group,
			);
		} elseif ( $screen && ! $screen->is_block_editor() && isset( $screen->post_type ) && strpos( $screen->post_type, 'wds' ) === 0 ) {
			$group = isset( $menus[ $screen->post_type ]['group'] ) ? $menus[ $screen->post_type ]['group'] : '';
			$datas = array(
				'type'  => 'cpt',
				'id'    => $screen->post_type,
				'group' => $group,
			);
		}

		if ( ! empty( $datas ) ) {
			if ( $session ) {
				WDS()->session->set( 'admin_data', $datas );
			}

			return true;
		}

		if ( $session ) {
			WDS()->session->set( 'admin_data', '' );
		}

		return false;
	}

	/**
	 * Display welcome page.
	 */
	public function welcome_page() {
		global $current_user;

		$user = $current_user->display_name;

		include_once 'templates/welcome.php';
	}

	/**
	 * Display license page.
	 */
	public function license_page() {
		$products = wds_get_products();

		include_once 'templates/license.php';
	}

	/**
	 * Display system page.
	 */
	public function system_page() {
		$systemservers  = wds_system_server();
		$systemwps      = wds_system_wp();
		$systemthemes   = wds_system_theme();
		$plugins_counts = (array) get_option( 'active_plugins', array() );

		include_once 'templates/system.php';
	}

	/**
	 * Display product page.
	 */
	public function product_page() {
		if ( isset( $_GET['product_id'] ) ) {
			$this->product_new_page();
		} else {
			$list_table = new \WDS\Admin\Table\Products();
			$list_table->prepare_items();

			include_once 'templates/products-list.php';
		}
	}

	/**
	 * Display product new page.
	 */
	public function product_new_page() {
		if ( ! class_exists( 'CSF' ) ) {
			wds_log( 'Framework notfound.' );
			return;
		}

		$product    = false;
		$product_id = 0;

		if ( isset( $_GET['product_id'] ) ) {
			$product = wds_get_product( $_GET['product_id'] );
			if ( ! $product ) {
				return wds_add_notice( __( 'Product ID not found.', 'wds-notrans' ) );
			}

			$product_id = $product->ID;
		}

		\CSF::$enqueue = true;
		\CSF::add_admin_enqueue_scripts();

		if ( ! $product ) {
			$slug      = '';
			$title     = '';
			$status    = 'active';
			$affiliate = 0;
		} else {
			$slug      = $product->slug;
			$title     = $product->title;
			$status    = $product->status;
			$affiliate = $product->affiliate;
		}

		$site_url_checkout = get_site_url() . '/checkout/';
		$checkout_link     = wds_url( 'checkout', $slug );
		$permalink         = $checkout_link;

		include_once 'templates/product.php';
	}

	/**
	 * Display coupon page.
	 */
	public function coupon_page() {
		if ( isset( $_GET['action'] ) && ( 'new' == $_GET['action'] || 'edit' == $_GET['action'] ) ) {
			if ( ! class_exists( 'CSF' ) ) {
				wds_log( 'Framework notfound.' );
				return;
			}

			$coupon    = false;
			$coupon_id = 0;

			if ( isset( $_GET['coupon_id'] ) && 'edit' == $_GET['action'] ) {
				$coupon = wds_get_coupon( $_GET['coupon_id'] );
				if ( ! $coupon ) {
					return wds_add_notice( __( 'Coupon ID not found.', 'wds-notrans' ) );
				}

				$coupon_id = $coupon->ID;
			}

			\CSF::$enqueue = true;
			\CSF::add_admin_enqueue_scripts();

			if ( ! $coupon ) {
				$title       = '';
				$rebate      = '';
				$is_private  = '';
				$max_usage   = '';
				$is_products = array();
				$users       = '';
				$status      = 'active';
			} else {
				$title       = $coupon->title;
				$rebate      = $coupon->rebate;
				$is_private  = $coupon->is_private;
				$max_usage   = $coupon->max_usage;
				$is_products = $coupon->products;
				$users       = $coupon->users;
				$status      = $coupon->status;
			}

			$products = wds_get_product_active();

			$product_data = array();
			if ( ! empty( $products ) ) {
				foreach ( $products as $product ) {
					$product_data[ $product->ID ] = $product->title;
				}
			}

			$list_table = new \WDS\Admin\Table\Coupon_Codes();

			include_once 'templates/coupon.php';
		} else {
			$list_table = new \WDS\Admin\Table\Coupons();
			$list_table->prepare_items();

			include_once 'templates/coupons-list.php';
		}
	}

	/**
	 * Display invoice page.
	 */
	public function invoice_page() {
		$list_table = new \WDS\Admin\Table\Invoices();
		$list_table->prepare_items();

		include_once 'templates/invoices-list.php';
	}

	/**
	 * Display order page.
	 */
	public function order_page() {
		if ( isset( $_GET['order_id'] ) ) {
			$order = wds_get_order( $_GET['order_id'] );
			if ( ! $order ) {
				return wds_add_notice( __( 'Order ID not found.', 'wds-notrans' ) );
			}

			$invoices = $order->invoices();
			$order_id = intval( $order->ID );
			$user_id  = intval( $order->user_id );
			$product  = wds_get_product( intval( $order->product_id ) );

			$expired = $order->expired_at ? $order->expired_at : __( 'Lifetime', 'wds-notrans' );
			if ( 'inactive' == $order->status ) {
				$expired = '-';
			}

			$renew = $order->get_renew_price();
			$renew = $renew ? wds_convert_money( $renew ) : '-';

			$affiliate = get_userdata( $order->get_affiliate_id() );
			$affiliate = $affiliate ? $affiliate->user_login . ' (' . $affiliate->user_email . ')' : '-';

			$addon = wds_get_order_meta( $order_id, 'addons' );
			$addon = ! empty( $addon ) && is_array( $addon ) ? implode( ', ', $addon ) : $addon;

			$addon_link = wds_get_order_meta( $order_id, 'addon_link' );

			include_once 'templates/order-details.php';
		} else {
			$list_table = new \WDS\Admin\Table\Orders();
			$list_table->prepare_items();

			include_once 'templates/orders-list.php';
		}
	}

	/**
	 * Display statistic page.
	 */
	public function statistic_page() {
		global $wpdb;

		$prefix = $wpdb->prefix . WDS_MODEL;

		$invoice_status = array_keys( wds_get_invoice_statuses() );
		$invoice_status = implode( '\',\'', $invoice_status );

		$products = "SELECT ID FROM {$prefix}_product";

		$args = array(
			'products'       => $products,
			'invoice_status' => $invoice_status,
			'invoice_order'  => $prefix . '_invoice_order',
			'invoice'        => $prefix . '_invoice',
			'order'          => $prefix . '_order',
		);

		$results    = \WDS_Statistics::stats_query( $args );
		$get_profit = \WDS_Statistics::get_profit();

		$leads      = $results->leads[0];
		$sales      = $results->sales[0];
		$conver     = $results->conversions[0];
		$revenue    = wds_convert_money( $get_profit->revenue );
		$profit     = wds_convert_money( $get_profit->profit );
		$commission = wds_convert_money( $get_profit->commission );

		include_once 'templates/statistics.php';
	}

	/**
	 * Display domain page.
	 */
	public function replica_domain_page() {
		$list_table = new \WDS\Admin\Table\Domain();
		$list_table->prepare_items();

		include_once 'templates/domain-list.php';
	}

	/**
	 * Display subdomain page.
	 */
	public function replica_subdomain_page() {
		$list_table = new \WDS\Admin\Table\Subdomain();
		$list_table->prepare_items();

		include_once 'templates/subdomain-list.php';
	}
}
