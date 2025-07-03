<?php
/**
 * Admin Handle.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Admin
 */

namespace WDS\Admin;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Handle Class.
 */
class Handle {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'handle_product_action' ) );
		add_action( 'admin_init', array( $this, 'handle_coupon_action' ) );
		add_action( 'admin_init', array( $this, 'handle_order_action' ) );
	}

	/**
	 * Current request page.
	 */
	private function page() {
		return filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';
	}

	/**
	 * Handle product actions (create, update, redirect).
	 *
	 * This function processes the product form submission, including creation and update of products.
	 * It checks nonce validation, handles form data, and sets relevant success or error messages.
	 */
	public function handle_product_action() {
		$product_id = 0;

		$slug = 'weddingsaas-product';

		if ( strpos( $this->page(), $slug ) === 0 ) {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], $slug ) ) {
				$post = $_POST;

				unset( $post['_wpnonce'], $post['_wp_http_referer'] );

				if ( isset( $post['___access_data'] ) ) {
					unset( $post['___access_data'] );
				}

				if ( 'membership' == $post['product_type'] && 'trial' == $post['membership_type'] ) {
					$post['regular_price'] = 0;
					$post['renew_price']   = 0;
				}

				$insert     = false;
				$product_id = intval( $post['ID'] ?? 0 );

				if ( $product_id > 0 ) {
					$product = wds_update_product( $post );
				} else {
					$product = wds_insert_product( $post );
					$insert  = true;
				}

				if ( is_wp_error( $product ) ) {
					$message = rawurlencode( $product->get_error_message() );
					$page    = $insert ? $slug . '-new' : $slug . '&product_id=' . $product_id;
					$page   .= '&error=' . $message;
				} else {
					$product_id = $insert ? $product : $product_id;
					$message    = rawurlencode( __( 'Product successfully saved.', 'wds-notrans' ) );
					$page       = $slug . '&product_id=' . $product_id . '&success=' . $message;

					do_action( 'wds_product_saved', $product_id );
				}

				$redirect = admin_url( 'admin.php?page=' . $page );
				wds_redirect( $redirect );
			}
		}
	}

	/**
	 * Handle coupon actions (create, update, delete, redirect).
	 *
	 * This function processes the coupon form submission, including creation and update of coupons.
	 * It checks nonce validation, handles form data, and sets relevant success or error messages.
	 */
	public function handle_coupon_action() {
		$coupon_id = 0;

		$slug = 'weddingsaas-coupon';

		if ( $this->page() == $slug ) {
			if ( isset( $_POST['_wpnonce'] ) && isset( $_GET['action'] ) && ( 'new' == $_GET['action'] || 'edit' == $_GET['action'] ) ) {
				$post = $_POST;

				unset( $post['_wpnonce'], $post['_wp_http_referer'] );

				$insert    = false;
				$coupon_id = intval( $post['ID'] ?? 0 );

				if ( $coupon_id > 0 ) {
					$coupon = wds_update_coupon( $post );
				} else {
					$coupon = wds_insert_coupon( $post );
					$insert = true;
				}

				if ( is_wp_error( $coupon ) ) {
					$message = rawurlencode( $coupon->get_error_message() );
					$page    = $insert ? '&action=new' : '&action=edit&coupon_id=' . $coupon_id;
					$page   .= '&error=' . $message;
				} else {
					$coupon_id = $insert ? $coupon : $coupon_id;
					$message   = rawurlencode( __( 'Coupon successfully saved.', 'wds-notrans' ) );
					$page      = '&action=edit&coupon_id=' . $coupon_id . '&success=' . $message;

					do_action( 'wds_coupon_saved', $coupon_id );
				}

				wds_redirect( menu_page_url( $slug, false ) . $page );
			} elseif ( isset( $_GET['coupon_id'] ) && isset( $_GET['delete_code'] ) ) {
				$coupon_id = intval( $_GET['coupon_id'] );
				$redirect  = menu_page_url( $slug, false ) . '&action=edit&coupon_id=' . $coupon_id;
				$message   = rawurlencode( __( 'Coupon code successfully deleted.', 'wds-notrans' ) );
				$deleted   = wds_delete_coupon_code( intval( $_GET['delete_code'] ) );
				if ( $deleted ) {
					wds_redirect( $redirect . '&success=' . $message );
				}
				$message = rawurlencode( __( 'Coupon code failed deleted.', 'wds-notrans' ) );
				wds_redirect( $redirect . '&error=' . $message );
			}
		}
	}

	/**
	 * Handle order actions.
	 *
	 * This function processes the order form submission.
	 */
	public function handle_order_action() {
		$order_id = 0;

		$slug = 'weddingsaas-order';

		if ( $this->page() == $slug ) {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], $slug ) ) {
				$post = $_POST;

				unset( $post['_wpnonce'], $post['_wp_http_referer'] );

				$order_id   = intval( $post['ID'] );
				$addon_link = $post['link'];

				wds_update_order_meta( $order_id, 'addon_link', $addon_link );
				$message = rawurlencode( __( 'Addon successfully saved.', 'wds-notrans' ) );
				$page    = '&order_id=' . $order_id . '&success=' . $message;

				wds_redirect( menu_page_url( $slug, false ) . $page );
			}
		}
	}
}

new Handle();
