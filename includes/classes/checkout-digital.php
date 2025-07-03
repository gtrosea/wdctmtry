<?php
/**
 * WeddingSaas Checkout Digital Product.
 *
 * Handles checkout session management.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Checkout_Digital Class.
 */
class WDS_Checkout_Digital {

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
		return 'checkout_data_digital_' . session_id();
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
	 * Prepares checkout session data.
	 *
	 * @param int $product_id The product ID.
	 */
	public function prepare( $product_id ) {
		$data = $this->get_session( 'all' );
		if ( wds_check_array( $data, true ) ) {
			$number      = intval( get_option( 'wds_user_number', 0 ) ) + 1;
			$checkout_id = 'checkout_id_' . $number;
			update_option( 'wds_user_number', $number );
		} else {
			$checkout_id = $this->get_session( 'checkout_id' );
		}

		$this->update_session( 'checkout_id', $checkout_id );
		$this->update_session( 'product_id', $product_id );

		$user = false;
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user()->user_login;
		}

		// check if ready affiliate cookie
		$affiliate = wds_get_current_affiliate_cookie();
		if ( $affiliate ) {
			if ( $affiliate == $user ) {
				wds_delete_current_affiliate_cookie();
			} else {
				$affiliate_user = get_user_by( 'login', $affiliate );
				if ( $affiliate_user ) {
					$this->update_session( 'affiliate_id', $affiliate_user->ID );
				}
			}
		}

		$price = wds_get_product_price( $product_id );
		if ( empty( $price ) || 0 == $price ) {
			$this->update_session( 'free', true );
			$this->remove_session( 'unique' );
			$this->remove_session( 'unique_number' );
			$this->remove_session( 'gateway' );
			$this->remove_session( 'coupon_code' );
		} else {
			$this->remove_session( 'free' );

			// check if unique number active
			$gateway = wds_sanitize_data_field( $data, 'gateway', wds_get_default_gateway() );
			if ( ( 'banktransfer' == $gateway || 'qris' == $gateway ) && wds_gateway( $gateway, 'unique_number' ) ) {
				$unique = $this->get_session( 'unique_number' );
				if ( ! $unique || empty( $unique ) ) {
					$unique = wds_generate_unique_number();
				}
				$this->update_session( 'unique', true );
				$this->update_session( 'unique_number', $unique );
				$this->update_session( 'gateway', $gateway );
			}

			// check if ready coupon parameter
			$coupon_code = wds_sanitize_data_field( $_GET, 'coupon', false );
			if ( $coupon_code ) {
				$coupon = wds_check_coupon_product( $coupon_code, $product_id );
				if ( $coupon ) {
					$this->update_session( 'coupon_code', $coupon_code );
					if ( $coupon->user_id ) {
						$affiliate = get_userdata( $coupon->user_id );
						if ( $affiliate ) {
							if ( ! is_user_logged_in() || ( is_user_logged_in() && get_current_user_id() != $affiliate->ID ) ) {
								$this->update_session( 'affiliate_id', $affiliate->ID );
								wds_set_affiliate_cookie( $affiliate->user_login );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Load checkout data.
	 */
	public function load_data() {
		global $wds_data;

		$data = $this->summary();

		$wds_data['data'] = $data;

		$product_id = wds_sanitize_data_field( $data, 'product_id' );
		$price      = wds_get_product_price( $product_id );
		$free       = empty( $price ) || 0 == $price ? true : false;

		if ( is_user_logged_in() ) {
			$wds_data['auth_link']  = wp_logout_url( wds_url( 'checkout', wds_get_current_product_slug() ) );
			$wds_data['auth_title'] = wds_lang( 'logout' );
		} else {
			$wds_data['auth_link']  = wds_url( 'login', false, 'checkout/' . wds_get_current_product_slug() );
			$wds_data['auth_title'] = wds_lang( 'login' );
		}

		$wds_data['product_price']        = $free ? wds_lang( 'free' ) : wds_convert_money( $price );
		$wds_data['product_price_period'] = $free ? '' : '/' . wds_lang( 'onetime' );

		if ( ! $free ) {
			// gateway
			$wds_data['gateway']  = wds_sanitize_data_field( $data, 'gateway', wds_get_default_gateway() );
			$wds_data['gateways'] = array_filter(
				wds_get_active_gateways(),
				function ( $key ) {
					return wds_gateway( $key, 'currency' ) === wds_option( 'currency' );
				},
				ARRAY_FILTER_USE_KEY
			);

			// coupon
			$coupon_code = wds_sanitize_data_field( $data, 'coupon_code', false );
			if ( isset( $_GET['coupon'] ) ) {
				$coupon_code = wds_sanitize_data_field( $_GET, 'coupon', false );
			}

			$wds_data['coupon_code'] = strtoupper( $coupon_code );
		}
	}

	/**
	 * Get checkout summary.
	 */
	public function summary() {
		$product_id = $this->get_session( 'product_id' );
		$product    = wds_get_product( $product_id );
		$summary    = array();

		$price = wds_get_product_price( $product_id );
		$free  = $this->get_session( 'free' );

		$summary['product_id']    = $product_id;
		$summary['product_title'] = $product->title;
		$summary['product_price'] = wds_convert_price( $price );

		if ( $free ) {
			$summary['subtotal'] = 0;
			$summary['total']    = 0;

			$this->update_session( 'summary', $summary );
			return $this->get_session( 'all' );
		}

		// coupons
		$discount    = 0;
		$coupon_code = $this->get_session( 'coupon_code' );
		if ( $coupon_code ) {
			$coupon = wds_check_coupon_product( $coupon_code, $product->ID );
			if ( $coupon && ! $free ) {
				$raw_rebate = wds_get_coupon_raw_rebate( $coupon );
				if ( wds_check_array( $raw_rebate, true ) ) {
					if ( 'percen' == $raw_rebate['type'] ) {
						$discount = floatval( $raw_rebate['value'] ) * floatval( $price );
						$discount = $discount / 100;
					} else {
						$discount = $raw_rebate['value'];
					}
					$discount = wds_convert_price( $discount );

					$summary['discount'] = $discount;
				}
			}
		}

		// commissions
		if ( $product->affiliate ) {
			$price_commission = floatval( $price ) - floatval( $discount );
			$raw_commission   = wds_get_product_raw_affiliate_commission( $product->ID );
			if ( wds_check_array( $raw_commission, true ) ) {
				if ( 'percen' == $raw_commission['type'] ) {
					$commission = floatval( $raw_commission['value'] ) * $price_commission;
					$commission = $commission / 100;
				} else {
					$commission = $raw_commission['value'];
				}
				$summary['commission'] = $commission;
			}
		}

		$subtotal = wds_convert_price( floatval( $price ) );
		$total    = $subtotal;

		$summary['subtotal'] = $subtotal;

		if ( 0 != $discount ) {
			$total = $total - $discount;
		}

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
}
