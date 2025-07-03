<?php
/**
 * WeddingSaas Renew.
 *
 * Handles Renew session management.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Renew Class.
 */
class WDS_Renew {

	/**
	 * Holds the singleton instance of the class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Retrieve the singleton instance of the class.
	 *
	 * @return self The single instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Retrieves session key.
	 */
	public function session_key() {
		return 'renew_data_' . session_id();
	}

	/**
	 * Delete all session.
	 *
	 * @return bool True if the session was deleted successfully, false otherwise.
	 */
	public function delete_session() {
		return WDS()->session->delete( $this->session_key() );
	}

	/**
	 * Retrieves session data by key.
	 *
	 * @param string $key     The key to retrieve from the session data.
	 * @param mixed  $default The default value to return if the key does not exist.
	 * @return mixed          The value associated with the key, or the default value if key doesn't exist.
	 */
	public function get_session( $key = '', $default = false ) {
		$session = WDS()->session->get( $this->session_key() );

		if ( 'all' == $key ) {
			return $session;
		}

		return wds_check_array( $session, true ) && isset( $session[ $key ] ) ? $session[ $key ] : $default;
	}

	/**
	 * Updates session data with a given key-value pair.
	 *
	 * @param string $key   The key to update in the session data.
	 * @param mixed  $value The value to associate with the key.
	 * @return bool         True if the session was updated successfully, false otherwise.
	 */
	public function update_session( $key, $value ) {
		if ( empty( $key ) ) {
			return false;
		}

		$session = $this->get_session( 'all' );
		if ( ! wds_check_array( $session, true ) ) {
			$session = array();
		}

		$session[ $key ] = wds_check_array( $value, true ) ? $value : wds_sanitize_text_field( $value );

		return WDS()->session->set( $this->session_key(), $session );
	}

	/**
	 * Remove session data by key.
	 *
	 * @param string $key The key to remove from the session data.
	 * @return bool       True if the session was removed successfully, false otherwise.
	 */
	public function remove_session( $key = '' ) {
		if ( empty( $key ) ) {
			return false;
		}

		$session = $this->get_session( 'all' );
		if ( wds_check_array( $session, true ) && isset( $session[ $key ] ) ) {
			unset( $session[ $key ] );
		} else {
			return false;
		}

		return WDS()->session->set( $this->session_key(), $session );
	}

	/**
	 * Prepares renew session data.
	 *
	 * @param object $order The order object.
	 */
	public function prepare( $order ) {
		$order_id = $order->ID;

		$this->update_session( 'order_id', $order_id );
		$this->update_session( 'product_id', $order->product_id );

		if ( wds_option( 'affiliate_recurring' ) ) {
			$affiliate_id = wds_get_order_meta( $order_id, 'affiliate_id', true );
			if ( ! empty( $affiliate_id ) ) {
				wds_set_affiliate_cookie( $affiliate_id );
				$this->update_session( 'affiliate_id', $affiliate_id );
			}
		} else {
			wds_delete_current_affiliate_cookie();
			$this->remove_session( 'affiliate_id' );
		}
	}

	/**
	 * Check if invoice exist.
	 */
	public function invoice_exists() {
		global $wpdb;

		$order_id = $this->get_session( 'order_id' );
		$invoice  = \WDS\Models\Invoice::select(
			array(
				$wpdb->prefix . WDS_MODEL . '_invoice.*',
				$wpdb->prefix . WDS_MODEL . '_invoice_order.order_id',
			)
		)
		->left_Join( WDS_MODEL . '_invoice_order', array( WDS_MODEL . '_invoice.ID', WDS_MODEL . '_invoice_order.invoice_id' ) )
		->query( 'WHERE order_id = %d AND status = %s', $order_id, 'unpaid' )
		->first();

		return $invoice && $invoice->ID > 0 ? $invoice->ID : false;
	}

	/**
	 * Load renew data.
	 */
	public function load_data() {
		global $wds_data;

		$data = $this->summary();

		$wds_data['data'] = $data;

		// product data
		$product_id = wds_sanitize_data_field( $data, 'product_id' );

		$membership_type = wds_get_product_meta( $product_id, 'membership_type' );

		$wds_data['membership_type']             = $membership_type;
		$wds_data['product_price']               = wds_get_product_data( $product_id, 'renew_price' );
		$wds_data['product_payment_type']        = wds_get_product_data( $product_id, 'payment_type' );
		$wds_data['product_membership_type']     = wds_get_product_data( $product_id, 'membership_type' );
		$wds_data['product_membership_lifetime'] = wds_get_product_data( $product_id, 'membership_lifetime' );
		$wds_data['product_invitation_lifetime'] = wds_get_product_data( $product_id, 'invitation_lifetime' );
		$wds_data['product_membership_quota']    = wds_get_product_data( $product_id, 'membership_quota' );
		$wds_data['product_client_quota']        = wds_get_product_meta( $product_id, 'reseller_client_quota' );

		// gateway
		$wds_data['gateway']  = wds_sanitize_data_field( $data, 'gateway', wds_get_default_gateway() );
		$wds_data['gateways'] = array_filter(
			wds_get_active_gateways(),
			function ( $key ) {
				return wds_gateway( $key, 'currency' ) === wds_option( 'currency' );
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	/**
	 * Get renew summary.
	 */
	public function summary() {
		$product_id = $this->get_session( 'product_id' );
		$product    = wds_get_product( $product_id );
		$price      = floatval( wds_get_product_renew_price( $product_id ) );
		$summary    = array();

		$summary['product_id']    = $product_id;
		$summary['product_title'] = $product->title;
		$summary['product_price'] = wds_convert_price( $price );

		// commissions
		if ( wds_option( 'affiliate_recurring' ) && $product->affiliate ) {
			$raw_commission = wds_get_product_raw_affiliate_commission( $product->ID );
			if ( wds_check_array( $raw_commission, true ) ) {
				if ( 'percen' == $raw_commission['type'] ) {
					$commission = floatval( $raw_commission['value'] ) * $price;
					$commission = $commission / 100;
				} else {
					$commission = $raw_commission['value'];
				}
				$summary['commission'] = $commission;
			}
		}

		$subtotal = wds_convert_price( $price );
		$total    = $subtotal;

		$summary['subtotal'] = $subtotal;

		// unique number
		$gateway = $this->get_session( 'gateway' );
		if ( $this->get_session( 'unique' ) && $gateway && wds_gateway( $gateway, 'unique_number' ) ) {
			$unique_type   = wds_option( 'unique_number_type' );
			$unique_number = wds_convert_price( $this->get_session( 'unique_number' ) );
			if ( '+' == $unique_type ) {
				$total = $total + $unique_number;
			} else {
				$total = $total - $unique_number;
			}
		}

		$summary['total'] = wds_convert_price( $total );

		$this->update_session( 'summary', $summary );

		return $this->get_session( 'all' );
	}

	/**
	 * Ajax update summary action.
	 */
	public function ajax_update_summary() {
		$text_addon = false;
		$discount   = false;
		$addon      = false;

		$data    = $this->summary();
		$summary = wds_sanitize_data_field( $data, 'summary', array() );

		$subtotal = wds_convert_money( wds_sanitize_data_field( $summary, 'subtotal' ) );
		$total    = wds_convert_money( wds_sanitize_data_field( $summary, 'total' ) );

		$unique        = wds_sanitize_data_field( $data, 'unique', false );
		$unique_number = wds_sanitize_data_field( $data, 'unique_number' );
		if ( $unique && ! empty( $unique_number ) ) {
			$unique = wds_option( 'unique_number_type' ) . wds_convert_money( $unique_number );
		}

		include_once wds_get_template( 'partials/summary.php' );

		wp_die();
	}

	/**
	 * Ajax update refered.
	 */
	public function ajax_update_refered() {
		$affiliate_id = $this->get_session( 'affiliate_id' );
		if ( ! $affiliate_id ) {
			echo 'noreff';
		} else {
			echo '<div class="text-muted text-center fs-6 mt-4">' . esc_html( wds_lang( 'trx_referred_by' ) ) . ' <span class="fw-bold text-primary">' . esc_html( wds_user_name( $affiliate_id ) ) . '</span></div>';
		}

		wp_die();
	}

	/**
	 * Ajax check gateway action.
	 */
	public function ajax_change_gateway() {
		$gateway = wds_sanitize_data_field( $_POST, 'gateway' );
		if ( $gateway && wds_gateway( $gateway, 'unique_number' ) ) {
			$unique = $this->get_session( 'unique_number' );
			if ( ! $unique || empty( $unique ) ) {
				$unique = wds_generate_unique_number();
			}
			$this->update_session( 'unique', true );
			$this->update_session( 'unique_number', $unique );
		} else {
			$this->remove_session( 'unique' );
		}

		$this->update_session( 'gateway', $gateway );

		wp_send_json_success( wds_lang( 'trx_checkout_alert_gateway_update' ) );
	}

	/**
	 * Ajax process data action.
	 */
	public function ajax_process_data() {
		$post = $_POST;

		$data          = $this->get_session( 'all' );
		$order_id      = wds_sanitize_data_field( $data, 'order_id' );
		$product_id    = wds_sanitize_data_field( $data, 'product_id' );
		$affiliate_id  = wds_sanitize_data_field( $data, 'affiliate_id' );
		$unique        = wds_sanitize_data_field( $data, 'unique' );
		$unique_number = wds_sanitize_data_field( $data, 'unique_number' );
		$gateway       = wds_sanitize_data_field( $data, 'gateway' );
		$gateway       = ! empty( $gateway ) ? $gateway : wds_sanitize_data_field( $post, 'gateway' );
		$summary       = wds_sanitize_data_field( $data, 'summary', array() );

		// wds_log( $post, true );
		// wds_log( $data, true );

		$summary['wds_v2'] = true;

		if ( $unique && ! empty( $unique_number ) ) {
			$summary['unique_number'] = $unique_number;
		}

		$user_id = get_current_user_id();
		$product = wds_get_product( $product_id );

		do_action( 'wds_renew_before', $order_id, $user_id );

		if ( empty( $gateway ) ) {
			wp_send_json_error( wds_lang( 'trx_checkout_alert_empty_gateway' ) );
		}

		$invoice_args = array(
			'number'      => '',
			'user_id'     => $user_id,
			'summary'     => $summary,
			'total'       => floatval( wds_sanitize_data_field( $summary, 'total' ) ),
			'gateway'     => $gateway,
			'reference'   => '',
			'type'        => 'renew_order',
			'status'      => 'unpaid',
			'due_date_at' => wds_set_invoice_due_date(),
		);

		$invoice_id = wds_insert_invoice( $invoice_args );

		if ( is_wp_error( $invoice_id ) ) {
			wp_send_json_error( wds_lang( 'trx_checkout_alert_invoice' ) );
		}

		$invoice_number = wds_generate_invoice_format( $invoice_id );

		$reference = wds_get_gateway_reference( $user_id, $invoice_id, $invoice_number, $gateway, $summary );

		if ( wds_option( 'affiliate_recurring' ) && $affiliate_id && $product->affiliate ) {
			$commission = wds_sanitize_data_field( $summary, 'commission' );
			if ( $commission ) {
				$commission_args = array(
					'user_id'    => $affiliate_id,
					'invoice_id' => $invoice_id,
					'order_id'   => $order_id,
					'product_id' => $product->ID,
					'amount'     => $commission,
					'status'     => 'pending',
					'note'       => sprintf(
						/* translators: %s: Product title */
						__( 'Commission on renew product %s', 'wds-notrans' ),
						$product->title
					),
				);

				wds_insert_commission( $commission_args );
			}
		}

		$update_invoice_args = array(
			'ID'        => $invoice_id,
			'number'    => $invoice_number,
			'reference' => $reference,
			'order_id'  => $order_id,
		);

		wds_update_invoice( $update_invoice_args );

		do_action( 'wds_insert_invoice_after', $invoice_id );

		$this->delete_session();

		wp_send_json_success( wds_url( 'pay', wds_encrypt_decrypt( $invoice_id ) ) );
	}
}
