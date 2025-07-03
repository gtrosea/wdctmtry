<?php
/**
 * WeddingSaas Checkout.
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
 * WDS_Checkout Class.
 */
class WDS_Checkout {

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
		$type = wds_sanitize_data_field( $_POST, 'context' );
		if ( 'digital' == $type ) {
			return 'checkout_data_digital_' . session_id();
		}

		return 'checkout_data_' . session_id();
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
	 * Retrieves the product ID for the current product based on the product slug.
	 *
	 * @return int The product ID.
	 */
	public function get_product() {
		$slug = wds_get_current_product_slug();
		if ( ! $slug ) {
			return false;
		}

		$product = wds_get_product_by( 'slug', $slug );
		if ( $product ) {
			return $product->ID;
		}
	}

	/**
	 * Prepares checkout session data.
	 *
	 * @param int $product_id The product ID.
	 */
	public function prepare( $product_id ) {
		$data = $this->get_session( 'all' );
		if ( ! wds_check_array( $data, true ) ) {
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

		$membership = wds_get_product_meta( $product_id, 'membership_type' );

		$price = wds_get_product_price( $product_id );
		if ( empty( $price ) || 0 == $price || 'trial' == $membership ) {
			$this->update_session( 'free', true );
			$this->remove_session( 'unique' );
			$this->remove_session( 'unique_number' );
			$this->remove_session( 'gateway' );
			$this->remove_session( 'coupon_code' );
			$this->remove_session( 'addons' );
		} else {
			$this->remove_session( 'free' );
			$this->remove_session( 'addons' );

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

		if ( ! is_user_logged_in() && 'addon' == $membership ) {
			wds_redirect( wds_url( 'login', false, 'checkout/' . wds_get_current_product_slug() ) );
		}
	}

	/**
	 * Load checkout data.
	 */
	public function load_data() {
		global $wds_data;

		$data = $this->summary();

		$product_id = wds_sanitize_data_field( $data, 'product_id' );
		$price      = wds_get_product_price( $product_id );
		$renew      = wds_get_product_renew_price( $product_id );
		$free       = $this->get_session( 'free' );

		$wds_data['data'] = $data;

		// auth button
		if ( is_user_logged_in() ) {
			$wds_data['auth_link']  = wp_logout_url( wds_url( 'checkout', wds_get_current_product_slug() ) );
			$wds_data['auth_title'] = wds_lang( 'logout' );
		} else {
			$wds_data['auth_link']  = wds_url( 'login', false, 'checkout/' . wds_get_current_product_slug() );
			$wds_data['auth_title'] = wds_lang( 'login' );
		}

		// product data
		$membership_type = wds_get_product_meta( $product_id, 'membership_type' );

		$wds_data['membership_type']             = $membership_type;
		$wds_data['product_price']               = wds_get_product_data( $product_id, 'price' );
		$wds_data['product_payment_type']        = wds_get_product_data( $product_id, 'payment_type' );
		$wds_data['renew_price']                 = wds_get_product_data( $product_id, 'renew_price' );
		$wds_data['renew_price_data']            = ! $free && floatval( $price ) != floatval( $renew ) ? true : false;
		$wds_data['product_membership_type']     = wds_get_product_data( $product_id, 'membership_type' );
		$wds_data['product_membership_lifetime'] = wds_get_product_data( $product_id, 'membership_lifetime' );
		$wds_data['product_invitation_lifetime'] = wds_get_product_data( $product_id, 'invitation_lifetime' );
		$wds_data['product_membership_quota']    = wds_get_product_data( $product_id, 'membership_quota' );
		$wds_data['product_client_quota']        = wds_get_product_meta( $product_id, 'reseller_client_quota' );

		if ( ! $free || 'trial' != $membership_type ) {
			$wds_data['addon'] = wds_get_product_meta( $product_id, 'addon' );

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

		$membership = wds_get_product_meta( $product_id, 'membership_type' );

		if ( $free || 'trial' == $membership ) {
			$summary['subtotal'] = 0;
			$summary['total']    = 0;

			$this->update_session( 'summary', $summary );
			return $this->get_session( 'all' );
		}

		// addons
		$addon_price = 0;
		$addons      = $this->get_session( 'addons', array() );
		if ( ! empty( $addons ) ) {
			$addon_data = wds_addon_data();
			if ( wds_check_array( $addon_data, true ) ) {
				$addon_title = array();
				foreach ( $addon_data as $addon ) {
					if ( in_array( $addon['id'], array_keys( $addons ) ) ) {
						$addon_price   = floatval( $addon_price ) + floatval( $addon['price'] );
						$addon_title[] = $addon['title'];
					}
				}
				$summary['addon_fixed'] = wds_addon_fixed();
				$summary['addon_price'] = $addon_price;
				$summary['addon_title'] = ! empty( $addon_title ) ? implode( ', ', $addon_title ) : '';
			}
		}

		$addon_nonfixed = ! wds_addon_fixed() && 0 != $addon_price;
		$addon_isfixed  = wds_addon_fixed() && 0 != $addon_price;

		if ( $addon_nonfixed ) {
			$price = floatval( $price ) + floatval( $addon_price );
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
			if ( $addon_isfixed ) {
				$price_commission = floatval( $price_commission ) + floatval( $addon_price );
			}
			$raw_commission = wds_get_product_raw_affiliate_commission( $product->ID );
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

		if ( $addon_isfixed ) {
			$total = $total + $addon_price;
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

	/**
	 * Ajax check existing email action.
	 */
	public function ajax_check_email() {
		$data = array();

		if ( ! is_user_logged_in() ) {
			$email  = sanitize_email( $_POST['email'] );
			$user   = get_user_by( 'email', $email );
			$domain = '';

			if ( $user ) {
				$data = array(
					'valid'   => false,
					'message' => wds_lang( 'email_exist' ),
				);
			} else {
				if ( strpos( $email, '@' ) ) {
					$email_parts = explode( '@', $email );
					$domain      = $email_parts[1];
				}

				$allowed_domains_string = wds_engine( 'allowed_domain_email' );
				$allowed_domains_array  = $allowed_domains_string ? explode( ',', $allowed_domains_string ) : array( 'gmail.com' );

				if ( in_array( $domain, $allowed_domains_array ) ) {
					$data = array( 'valid' => true );
				} elseif ( $domain ) {
					$data = array(
						'valid'   => false,
						'message' => wds_lang( 'email_allowed' ),
					);
				}
			}
		} else {
			$data = array( 'valid' => true );
		}

		echo wp_json_encode( $data );
		wp_die();
	}

	/**
	 * Ajax check existing phone action.
	 */
	public function ajax_check_phone() {
		if ( ! is_user_logged_in() ) {
			$phone = wds_sanitize_data_field( $_POST, 'phone' );
			$check = wds_check_existing_phone( $phone );
			if ( $check ) {
				$data = array(
					'valid'   => false,
					'message' => wds_lang( 'phone_exist' ),
				);
			} else {
				$data = array( 'valid' => true );
			}
		} else {
			$data = array( 'valid' => true );
		}

		echo wp_json_encode( $data );
		wp_die();
	}

	/**
	 * Ajax update summary action.
	 */
	public function ajax_update_summary() {
		$text_addon = false;
		$subtotal   = wds_lang( 'free' );
		$total      = wds_lang( 'free' );
		$discount   = false;
		$addon      = false;

		if ( 'digital' == wds_sanitize_data_field( $_POST, 'context' ) ) {
			$data = \WDS_Checkout_Digital::instance()->summary( true );
		} else {
			$data = $this->summary( true );
		}

		$is_free = wds_sanitize_data_field( $data, 'free' );
		$summary = wds_sanitize_data_field( $data, 'summary', array() );

		$_subtotal = wds_sanitize_data_field( $summary, 'subtotal' );
		$_total    = wds_sanitize_data_field( $summary, 'total' );
		if ( ! $is_free && 0 != $_total ) {
			$subtotal = wds_convert_money( $_subtotal );
			$total    = wds_convert_money( $_total );
		}

		$_coupon   = wds_sanitize_data_field( $data, 'coupon_code', false );
		$_discount = wds_sanitize_data_field( $summary, 'discount' );
		if ( $_coupon && ! empty( $_discount ) ) {
			$discount = '-' . wds_convert_money( $_discount );
		}

		$unique        = wds_sanitize_data_field( $data, 'unique', false );
		$unique_number = wds_sanitize_data_field( $data, 'unique_number' );
		if ( $unique && ! empty( $unique_number ) ) {
			$unique = wds_option( 'unique_number_type' ) . wds_convert_money( $unique_number );
		}

		$addons      = wds_sanitize_data_field( $data, 'addons' );
		$addon_price = wds_sanitize_data_field( $summary, 'addon_price' );
		$addon_fixed = wds_sanitize_data_field( $summary, 'addon_fixed' );
		if ( wds_check_array( $addons, true ) && ! empty( $addon_price ) ) {
			if ( ! $addon_fixed ) {
				$text_addon = true;
			} else {
				$addon = '+' . wds_convert_money( $addon_price );
			}
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
	 * Ajax change addon action.
	 */
	public function ajax_change_addon() {
		$post = $_POST;

		$data     = $this->get_session( 'addons', array() );
		$addon    = wds_sanitize_data_field( $post, 'addon' );
		$addon_id = wds_sanitize_data_field( $post, 'addon_id' );
		$title    = wds_sanitize_data_field( $post, 'addon_title' );
		$price    = wds_sanitize_data_field( $post, 'addon_price' );

		if ( 'inactive' === $addon && isset( $data[ $addon_id ] ) ) {
			unset( $data[ $addon_id ] );
			if ( empty( $data ) ) {
				$this->remove_session( 'addons' );
			} else {
				$this->update_session( 'addons', $data );
			}
			wp_send_json_success( wds_lang( 'trx_checkout_alert_addon_remove' ) );
		} elseif ( 'active' === $addon && ! in_array( $addon_id, array_keys( $data ) ) ) {
			$data[ $addon_id ] = array(
				'title' => $title,
				'price' => $price,
			);
			$this->update_session( 'addons', $data );
			wp_send_json_success( wds_lang( 'trx_checkout_alert_addon_add' ) );
		}
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
	 * Ajax apply coupon action.
	 */
	public function ajax_apply_coupon() {
		$code = wds_sanitize_data_field( $_POST, 'coupon' );
		if ( ! $code || empty( $code ) ) {
			$this->remove_session( 'coupon_code' );
			wp_send_json_error( wds_lang( 'trx_checkout_alert_coupon_invalid' ) );
		}

		$coupon = wds_check_coupon_product( $code, $this->get_session( 'product_id' ) );
		if ( $coupon ) {
			$this->update_session( 'coupon_code', $code );

			$affiliate = get_userdata( $coupon->user_id );
			if ( $affiliate ) {
				if ( is_user_logged_in() && get_current_user_id() == $affiliate->ID ) {
					$this->remove_session( 'coupon_code' );
					$this->remove_session( 'affiliate_id' );
					wds_delete_current_affiliate_cookie();
					wp_send_json_error( wds_lang( 'trx_checkout_alert_coupon_forbidden' ) );
				} else {
					$this->update_session( 'affiliate_id', $affiliate->ID );
					wds_set_affiliate_cookie( $affiliate->user_login );
				}
			}

			$check_limit = wds_check_coupon_limit( $coupon->ID, $code );
			if ( $check_limit ) {
				$this->remove_session( 'coupon_code' );
				$this->remove_session( 'affiliate_id' );
				wds_delete_current_affiliate_cookie();
				wp_send_json_error( wds_lang( 'trx_checkout_alert_coupon_limit' ) );
			}

			wp_send_json_success( wds_lang( 'trx_checkout_alert_coupon_success' ) );
		} else {
			$this->remove_session( 'coupon_code' );
			wp_send_json_error( wds_lang( 'trx_checkout_alert_coupon_notfound' ) );
		}
	}

	/**
	 * Ajax process data action.
	 */
	public function ajax_process_data() {
		$post = $_POST;

		$data          = $this->get_session( 'all' );
		$type          = wds_sanitize_data_field( $post, 'context' );
		$is_digital    = wds_is_digital() && 'digital' == $type ? true : false;
		$checkout_id   = wds_sanitize_data_field( $data, 'checkout_id' );
		$product_id    = wds_sanitize_data_field( $data, 'product_id' );
		$affiliate_id  = wds_sanitize_data_field( $data, 'affiliate_id' );
		$is_free       = wds_sanitize_data_field( $data, 'free', false );
		$unique        = wds_sanitize_data_field( $data, 'unique' );
		$unique_number = wds_sanitize_data_field( $data, 'unique_number' );
		$gateway       = wds_sanitize_data_field( $data, 'gateway' );
		$gateway       = ! empty( $gateway ) ? $gateway : wds_sanitize_data_field( $post, 'gateway' );
		$coupon_code   = wds_sanitize_data_field( $data, 'coupon_code' );
		$coupon_code   = ! empty( $coupon_code ) ? $coupon_code : wds_sanitize_data_field( $post, 'coupon' );
		$summary       = wds_sanitize_data_field( $data, 'summary', array() );

		// wds_log( $post, true );
		// wds_log( $data, true );

		$summary['wds_v2'] = true;

		if ( $unique && ! empty( $unique_number ) ) {
			$summary['unique_number'] = $unique_number;
		}

		if ( empty( $checkout_id ) || empty( $product_id ) ) {
			wp_send_json_error( wds_lang( 'trx_checkout_alert_empty_id' ) );
		}

		$product         = wds_get_product( $product_id );
		$membership_type = wds_get_product_meta( $product_id, 'membership_type' );

		if ( is_user_logged_in() ) {
			$user_id      = get_current_user_id();
			$status_user  = wds_user_status( $user_id );
			$user_group   = wds_user_group( $user_id );
			$wds_order_id = wds_user_order_id( $user_id );

			// check if affiliate_id same and user_id
			if ( $user_id == $affiliate_id ) {
				$this->remove_session( 'affiliate_id' );
				wds_delete_current_affiliate_cookie();
			}

			if ( ! $is_digital ) {
				if ( 'trial' == $user_group && 'trial' == $membership_type && $wds_order_id ) {
					wp_send_json_error( wds_lang( 'trx_checkout_alert_trial1' ) );
				} elseif ( 'active' == $status_user && 'trial' == $membership_type ) {
					wp_send_json_error( wds_lang( 'trx_checkout_alert_trial2' ) );
				} elseif ( 'inactive' == $status_user || 'trial' == $user_group || 'member' == $user_group ) {
					if ( 'addon' == $membership_type && $wds_order_id ) {
						wp_send_json_error( wds_lang( 'trx_checkout_alert_addon' ) );
					}
				}
			}
		} else {
			$fname    = wds_sanitize_data_field( $post, 'fullname' );
			$email    = wds_sanitize_data_field( $post, 'email' );
			$phone    = wds_sanitize_data_field( $post, 'phone' );
			$password = wds_sanitize_data_field( $post, 'password' );
			$password = wds_option( 'hide_password' ) ? wds_option( 'default_password' ) : $password;

			// Email validation
			$email  = isset( $post['email'] ) ? sanitize_email( $post['email'] ) : '';
			$echeck = \WDS\Engine\Tools\Validation::email( $email );
			if ( 'email_disabled' == $echeck ) {
				wp_send_json_error( wds_lang( 'email_disabled' ) );
			}

			// WhatsApp validation
			$phone  = isset( $post['phone'] ) ? $post['phone'] : '';
			$wcheck = \WDS\Engine\Tools\Validation::whatsapp( $phone );
			if ( 'not_registered' == $wcheck ) {
				wp_send_json_error( wds_lang( 'phone_invalid' ) );
			}

			$user_data = array(
				'user_email'      => $email,
				'user_login'      => $email,
				'user_pass'       => $password,
				'first_name'      => $fname,
				'display_name'    => $fname,
				'user_registered' => current_time( 'mysql' ),
				'role'            => 'wds-member',
			);

			$user_id = wp_insert_user( $user_data );

			if ( is_wp_error( $user_id ) ) {
				wp_send_json_error( wds_lang( 'trx_checkout_alert_account' ) );
			}

			update_user_meta( $user_id, '_phone', wds_phone_country_code( $phone ) );
			update_user_meta( $user_id, '_password', $password );

			wp_set_auth_cookie( $user_id );
			wp_set_current_user( $user_id );

			if ( wds_option( 'account_activation' ) ) {
				do_action( 'wds_user_activation', $user_id );
			} else {
				do_action( 'wds_user_register', $user_id );
			}
		}

		do_action( 'wds_checkout_before', $user_id );

		if ( ! $is_free && empty( $gateway ) ) {
			wp_send_json_error( wds_lang( 'trx_checkout_alert_empty_gateway' ) );
		}

		$invoice_args = array(
			'number'      => '',
			'user_id'     => $user_id,
			'summary'     => $summary,
			'total'       => floatval( wds_sanitize_data_field( $summary, 'total' ) ),
			'gateway'     => $is_free ? 'system' : $gateway,
			'reference'   => '',
			'status'      => 'unpaid',
			'due_date_at' => wds_set_invoice_due_date(),
		);

		$invoice_id = wds_insert_invoice( $invoice_args );

		if ( is_wp_error( $invoice_id ) ) {
			wp_send_json_error( wds_lang( 'trx_checkout_alert_invoice' ) );
		}

		$invoice_number = wds_generate_invoice_format( $invoice_id );

		$reference = wds_get_gateway_reference( $user_id, $invoice_id, $invoice_number, $gateway, $summary );

		$order_args = array(
			'code'       => wds_generate_order_code(),
			'user_id'    => $user_id,
			'product_id' => $product->ID,
		);

		do_action( 'wds_insert_order_before', $order_args );

		$order_id = wds_insert_order( $order_args );

		if ( ! is_wp_error( $order_id ) ) {
			wds_add_order_meta( $order_id, 'product_type', $type );
			if ( $is_digital ) {
				wds_add_order_meta( $order_id, 'duration', 'onetime' );
			} else {
				$membership_lifetime       = wds_get_product_meta( $product->ID, 'membership_lifetime' );
				$membership_duration       = wds_get_product_meta( $product->ID, 'membership_duration' );
				$membership_period         = wds_get_product_meta( $product->ID, 'membership_period' );
				$invitation_lifetime       = wds_get_product_meta( $product->ID, 'invitation_lifetime' );
				$invitation_duration       = wds_get_product_meta( $product->ID, 'invitation_duration' );
				$invitation_period         = wds_get_product_meta( $product->ID, 'invitation_period' );
				$invitation_quota          = wds_get_product_meta( $product->ID, 'invitation_quota' );
				$invitation_status         = wds_get_product_meta( $product->ID, 'invitation_status' );
				$reseller_client_quota     = wds_get_product_meta( $product->ID, 'reseller_client_quota' );
				$reseller_invitation_quota = wds_get_product_meta( $product->ID, 'reseller_invitation_quota' );
				$payment_type              = wds_get_product_meta( $product->ID, 'payment_type' );
				$renew_duration            = wds_get_product_meta( $product->ID, 'renew_duration' );
				$renew_period              = wds_get_product_meta( $product->ID, 'renew_period' );
				$renew_price               = wds_get_product_renew_price( $product->ID );

				wds_add_order_meta( $order_id, 'membership_type', $membership_type );

				if ( 'addon' != $membership_type ) {
					if ( 'yes' == $membership_lifetime ) {
						wds_add_order_meta( $order_id, 'membership_duration', 'lifetime' );
					} else {
						wds_add_order_meta( $order_id, 'membership_duration', $membership_duration . ' ' . $membership_period );
					}

					if ( 'yes' == $invitation_lifetime ) {
						wds_add_order_meta( $order_id, 'invitation_duration', 'lifetime' );
					} else {
						wds_add_order_meta( $order_id, 'invitation_duration', $invitation_duration . ' ' . $invitation_period );
					}

					if ( 'reseller' != $membership_type ) {
						wds_add_order_meta( $order_id, 'invitation_quota', $invitation_quota );
					} else {
						wds_add_order_meta( $order_id, 'reseller_client_quota', $reseller_client_quota );
						wds_add_order_meta( $order_id, 'reseller_invitation_quota', $reseller_invitation_quota );
					}

					wds_add_order_meta( $order_id, 'invitation_status', $invitation_status );
				} else {
					wds_add_order_meta( $order_id, 'reseller_client_quota', $reseller_client_quota );
					wds_add_order_meta( $order_id, 'reseller_invitation_quota', $reseller_invitation_quota );
				}

				if ( 'onetime' == $payment_type ) {
					wds_add_order_meta( $order_id, 'duration', 'onetime' );
				} else {
					wds_add_order_meta( $order_id, 'duration', $renew_duration . ' ' . $renew_period );
					wds_add_order_meta( $order_id, 'renew_price', $renew_price );
				}

				$addon_title = wds_sanitize_data_field( $summary, 'addon_title' );
				if ( $addon_title ) {
					wds_add_order_meta( $order_id, 'addons', $addon_title );
				}
			}

			do_action( 'wds_insert_order_after', $order_args, $order_id );

			if ( $affiliate_id && $product->affiliate ) {
				wds_add_order_meta( $order_id, 'affiliate_id', $affiliate_id );

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
							__( 'Commission on product sales %s', 'wds-notrans' ),
							$product->title
						),
					);

					wds_insert_commission( $commission_args );
				}
			}

			if ( $coupon_code ) {
				$coupon = wds_check_coupon_product( $coupon_code, $product->ID );
				if ( $coupon->ID ) {
					if ( $affiliate_id ) {
						$check = wds_get_coupon_code( $coupon->ID, $affiliate_id, true );
					} else {
						$check = wds_get_coupon_code_by_code( $coupon_code, false, true );
					}

					if ( $check && $check > 0 ) {
						wds_insert_coupon_usage( $coupon->ID, $check, $invoice_id );
					}
				}
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
