<?php
/**
 * WeddingSaas Payment Gateway.
 *
 * Abstract base class for payment gateways.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Core/Abstracts
 */

namespace WDS\Abstracts;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Payment_Gateway Class.
 */
abstract class Payment_Gateway {

	/**
	 * The unique identifier for the gateway.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The currency of the gateway.
	 *
	 * @var string
	 */
	protected $currency = 'IDR';

	/**
	 * Whether the gateway uses a unique number.
	 *
	 * @var bool
	 */
	protected $unique = false;

	/**
	 * Get the gateway ID.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Check if the gateway icon is enabled.
	 *
	 * @return bool
	 */
	public function get_icon_enable() {
		return wds_option( $this->get_id() . '_icon_enable' );
	}

	/**
	 * Get the gateway icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		$icon = wds_option( $this->get_id() . '_icon' );
		if ( empty( $icon ) ) {
			$icon = wds_logo_payment( $this->get_id() );
		}

		return $icon;
	}

	/**
	 * Get the gateway title.
	 *
	 * @return string
	 */
	public function get_title() {
		return wds_option( $this->get_id() . '_title' );
	}

	/**
	 * Get the payment gateway instructions.
	 *
	 * @return string
	 */
	public function get_instruction() {
		return wds_option( $this->get_id() . '_instruction' );
	}

	/**
	 * Get the gateway currency.
	 *
	 * @return string
	 */
	public function get_currency() {
		return $this->currency;
	}

	// /**
	//  * Get the symbol for the gateway currency.
	//  *
	//  * @return string
	//  */
	// public function get_currency_symbol() {
	//  return wds_option( $this->id . '_currency_symbol' );
	// }

	// /**
	//  * Get the exchange rate for the gateway currency.
	//  *
	//  * @return float
	//  */
	// public function get_currency_rate() {
	//  return wds_option( $this->id . '_currency_rate' );
	// }

	/**
	 * Check if the gateway uses a unique number.
	 *
	 * @return bool
	 */
	public function use_unique_number() {
		if ( wds_option( 'unique_number' ) ) {
			return $this->unique;
		}

		return false;
	}

	/**
	 * Print the gateway action.
	 *
	 * @return mixed
	 */
	public function print_action() {
		ob_start();

		// if ( 'banktransfer' == $this->get_id() ) {
		//  wds_template_section( 'partials/payment/banktransfer.php' );
		// } elseif ( 'qris' == $this->get_id() ) {
		//  wds_template_section( 'partials/payment/qris.php' );
		// } elseif ( 'midtrans' == $this->get_id() ) {
		//  wds_template_section( 'partials/payment/midtrans.php' );
		// } elseif ( 'flip' == $this->get_id() ) {
		//  wds_template_section( 'partials/payment/flip.php' );
		// } elseif ( 'xendit' == $this->get_id() ) {
		//  wds_template_section( 'partials/payment/xendit.php' );
		// } elseif ( strpos( $this->get_id(), 'duitku' ) === 0 ) {
		//  wds_template_section( 'partials/payment/duitku.php' );
		// } elseif ( strpos( $this->get_id(), 'tripay' ) === 0 ) {
		//  wds_template_section( 'partials/payment/tripay.php' );
		// }
		if ( 'banktransfer' == $this->get_id() ) {
			wds_template_section( 'partials/payment/banktransfer.php' );
		} elseif ( 'qris' == $this->get_id() ) {
			wds_template_section( 'partials/payment/qris.php' );
		} else {
			wds_template_section( 'partials/payment/gateway.php' );
		}

		return ob_get_clean();
	}
}
