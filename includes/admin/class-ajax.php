<?php
/**
 * WeddingSaas Admin Ajax.
 *
 * @since 2.0.3
 * @package WeddingSaas
 * @subpackage Admin
 */

namespace WDS\Admin;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Ajax Class.
 */
class Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_mark_invoice_completed', array( $this, 'mark_invoice_completed' ) );
		add_action( 'wp_ajax_get_payment_channels', array( $this, 'get_payment_channels' ) );
	}

	/**
	 * Handle check AJAX requests.
	 */
	public function check() {
		if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
			wp_send_json_error( __( 'Invalid request method.', 'wds-notrans' ) );
		}

		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( __( 'Missing security information.', 'wds-notrans' ) );
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'wds_admin_nonce' ) ) {
			wp_send_json_error( __( 'Nonce verification failed.', 'wds-notrans' ) );
		}
	}

	/**
	 * Marks an invoice as completed.
	 *
	 * @since 2.0.1
	 * @return void Redirects to the invoice page after updating the status.
	 */
	public function mark_invoice_completed() {
		$this->check();

		$updated = wds_update_invoice_status( wds_sanitize_data_field( $_POST, 'invoice_id' ), 'completed' );
		if ( is_wp_error( $updated ) ) {
			wp_send_json_error( __( 'Gagal mengupdate invoice.', 'weddingsaas' ) );
		} else {
			wp_send_json_success( 'success' );
		}
	}

	/**
	 * Get tripay payment list.
	 *
	 * @since 2.0.3
	 * @return void
	 */
	public function get_payment_channels() {
		$this->check();

		$gateway = wds_sanitize_data_field( $_POST, 'gateway' );
		if ( 'duitku' == $gateway ) {
			$data = \WDS\Gateway\Duitku::channel();
		} elseif ( 'tripay' == $gateway ) {
			$data = \WDS\Gateway\Tripay::channel();
		}

		if ( 'success' == $data ) {
			wp_send_json_success( 'success' );
		} else {
			wp_send_json_error( $data );
		}
	}
}

new Ajax();
