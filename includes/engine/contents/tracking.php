<?php

namespace WDS\Engine\Contents;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Tracking Class.
 *
 * @since 2.0.0
 */
class Tracking {

	/**
	 * Singleton instance of Tracking class.
	 *
	 * @var Tracking|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the Tracking class.
	 *
	 * @return Tracking Singleton instance.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wds_head', array( $this, 'on_checkout' ), 999 );
		add_action( 'wds_head', array( $this, 'on_payment' ), 999 );
		add_action( 'wds_head', array( $this, 'on_thankyou' ), 999 );
	}

	/**
	 * Check pixel enable or not.
	 */
	private function check() {
		return wds_engine( 'fbpixel' ) ? true : false;
	}

	/**
	 * Generates the base Facebook Pixel script for initializing the pixel.
	 *
	 * This script initializes the Facebook Pixel base setup by loading the Facebook tracking library.
	 *
	 * @return string The JavaScript code to initialize the Facebook Pixel.
	 */
	private function fb_pixel_script_base() {
		$script = "!function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');";

		return $script;
	}

	/**
	 * Generates the Facebook Pixel initialization script with a specified Pixel ID.
	 *
	 * This script initializes the Facebook Pixel with the provided Pixel ID.
	 *
	 * @param string $pixel_id The unique ID of the Facebook Pixel to initialize.
	 * @return string The JavaScript code to initialize the Facebook Pixel with the specified ID.
	 */
	private function fb_pixel_script_init( $pixel_id ) {
		$script = "fbq('init', '$pixel_id');";

		return $script;
	}

	/**
	 * Generates the Facebook Pixel event tracking script.
	 *
	 * Tracks a specified event with optional arguments and a unique event ID for deduplication.
	 *
	 * @param string $event     The name of the event to track (e.g., 'Purchase', 'ViewContent').
	 * @param string $event_id  A unique identifier for the event, used for event deduplication.
	 * @param array  $args      Optional. Additional parameters to pass with the event.
	 * @return string The JavaScript code to track the event with the specified parameters.
	 */
	private function fb_pixel_script_event( $event, $event_id, $args = array() ) {
		$args   = (object) $args;
		$script = "fbq('track', '$event', " . wp_json_encode( $args ) . ", {eventID: '$event_id'});";

		return $script;
	}

	/**
	 * Generates the Facebook Pixel tracking image for non-JavaScript fallback.
	 *
	 * Provides a tracking image for users who have disabled JavaScript. The image sends a tracking
	 * request to Facebook for the specified event.
	 *
	 * @param string $pixel_id The unique ID of the Facebook Pixel.
	 * @param string $event    The name of the event to track (e.g., 'PageView').
	 * @return string The HTML code for an image tag that sends a tracking request to Facebook.
	 */
	private function fb_pixel_script_image( $pixel_id, $event ) {
		$script = "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=$pixel_id&ev=$event&noscript=1' />";

		return $script;
	}

	/**
	 * Outputs Facebook Pixel tracking scripts for the checkout page.
	 *
	 * This method generates the Facebook Pixel tracking code for the checkout page,
	 * including a 'PageView' event and an optional custom event.
	 * Also sends Conversions API (CAPI) events if enabled.
	 *
	 * @return void
	 */
	public function on_checkout() {
		$check = $this->check();
		if ( ! $check || 'checkout' != wds_is_page() ) {
			return;
		}

		$pixel_ids = wds_engine( 'fbpixel_ids' );
		if ( empty( $pixel_ids ) ) {
			return;
		}

		$pixel_ids = explode( ',', $pixel_ids );
		$event     = wds_engine( 'fbpixel_checkout' );
		$event_id1 = bin2hex( random_bytes( 12 ) );
		$event_id2 = bin2hex( random_bytes( 12 ) );

		$data    = wds_data( 'data' );
		$summary = wds_sanitize_data_field( $data, 'summary' );

		$data_view = (object) array(
			'page'   => 'Checkout',
			'plugin' => WDS_NAME,
		);

		$data = (object) array(
			'page'     => 'Checkout',
			'plugin'   => WDS_NAME,
			'currency' => wds_option( 'currency' ),
			'value'    => wds_sanitize_data_field( $summary, 'total' ),
		);

		echo '<!-- Meta Pixel Code -->';
		echo '<script>';
		echo wp_kses_post( $this->fb_pixel_script_base() );
		foreach ( $pixel_ids as $id ) {
			echo wp_kses_post( $this->fb_pixel_script_init( $id ) );
		}
		echo wp_kses_post( $this->fb_pixel_script_event( 'PageView', $event_id1, $data_view ) );
		if ( ! empty( $event ) ) {
			echo wp_kses_post( $this->fb_pixel_script_event( $event, $event_id2, $data ) );
		}
		echo '</script>';
		echo '<noscript>';
		foreach ( $pixel_ids as $id ) {
			echo wp_kses_post( $this->fb_pixel_script_image( $id, 'PageView' ) );
			if ( ! empty( $event ) ) {
				echo wp_kses_post( $this->fb_pixel_script_image( $id, $event ) );
			}
		}
		echo '</noscript>';
		echo '<!-- End Meta Pixel Code -->';

		if ( wds_is_meta_capi() ) {
			Capi::send_event_capi_page_view( 'Checkout', $event_id1 );
			if ( ! empty( $event ) ) {
				Capi::send_event_capi( 'Checkout', $event, $event_id2 );
			}
		}
	}

	/**
	 * Outputs Facebook Pixel tracking scripts for the payment page.
	 *
	 * This method generates the Facebook Pixel tracking code for the payment page,
	 * including a 'PageView' event and an optional custom event.
	 * Also sends Conversions API (CAPI) events if enabled.
	 *
	 * @return void
	 */
	public function on_payment() {
		$check = $this->check();
		if ( ! $check || 'pay' != wds_is_page() ) {
			return;
		}

		$pixel_ids = wds_engine( 'fbpixel_ids' );
		if ( empty( $pixel_ids ) ) {
			return;
		}

		$pixel_ids = explode( ',', $pixel_ids );
		$event     = wds_engine( 'fbpixel_payment' );
		$event_id1 = bin2hex( random_bytes( 12 ) );
		$event_id2 = bin2hex( random_bytes( 12 ) );

		$invoice = wds_data( 'invoice' );

		$data_view = (object) array(
			'page'   => 'Payment',
			'plugin' => WDS_NAME,
		);

		$data = (object) array(
			'page'     => 'Payment',
			'plugin'   => WDS_NAME,
			'currency' => wds_option( 'currency' ),
			'value'    => wds_invoice_summary( $invoice, 'product_price' ),
		);

		echo '<!-- Meta Pixel Code -->';
		echo '<script>';
		echo wp_kses_post( $this->fb_pixel_script_base() );
		foreach ( $pixel_ids as $id ) {
			echo wp_kses_post( $this->fb_pixel_script_init( $id ) );
		}
		echo wp_kses_post( $this->fb_pixel_script_event( 'PageView', $event_id1, $data_view ) );
		if ( ! empty( $event ) ) {
			echo wp_kses_post( $this->fb_pixel_script_event( $event, $event_id2, $data ) );
		}
		echo '</script>';
		echo '<noscript>';
		foreach ( $pixel_ids as $id ) {
			echo wp_kses_post( $this->fb_pixel_script_image( $id, 'PageView' ) );
			if ( ! empty( $event ) ) {
				echo wp_kses_post( $this->fb_pixel_script_image( $id, $event ) );
			}
		}
		echo '</noscript>';
		echo '<!-- End Meta Pixel Code -->';

		if ( wds_is_meta_capi() ) {
			Capi::send_event_capi_page_view( 'Payment', $event_id1 );
			if ( ! empty( $event ) ) {
				Capi::send_event_capi( 'Payment', $event, $event_id2 );
			}
		}
	}

	/**
	 * Outputs Facebook Pixel tracking scripts for the thank-you page.
	 *
	 * This method generates the Facebook Pixel tracking code for the thank-you page,
	 * including a 'PageView' event and an optional custom event.
	 * Also sends Conversions API (CAPI) events if enabled.
	 *
	 * @return void
	 */
	public function on_thankyou() {
		$check = $this->check();
		if ( ! $check || 'thanks' != wds_is_page() ) {
			return;
		}

		$pixel_ids = wds_engine( 'fbpixel_ids' );
		if ( empty( $pixel_ids ) ) {
			return;
		}

		$pixel_ids = explode( ',', $pixel_ids );
		$event     = wds_engine( 'fbpixel_thanks' );
		$event_id1 = bin2hex( random_bytes( 12 ) );
		$event_id2 = bin2hex( random_bytes( 12 ) );

		$invoice = wds_data( 'invoice' );

		$data_view = (object) array(
			'page'   => 'Thanks',
			'plugin' => WDS_NAME,
		);

		$data = (object) array(
			'page'     => 'Thanks',
			'plugin'   => WDS_NAME,
			'currency' => wds_option( 'currency' ),
			'value'    => wds_invoice_summary( $invoice, 'product_price' ),
		);

		echo '<!-- Meta Pixel Code -->';
		echo '<script>';
		echo wp_kses_post( $this->fb_pixel_script_base() );
		foreach ( $pixel_ids as $id ) {
			echo wp_kses_post( $this->fb_pixel_script_init( $id ) );
		}
		echo wp_kses_post( $this->fb_pixel_script_event( 'PageView', $event_id1, $data_view ) );
		if ( ! empty( $event ) ) {
			echo wp_kses_post( $this->fb_pixel_script_event( $event, $event_id2, $data ) );
		}
		echo '</script>';
		echo '<noscript>';
		foreach ( $pixel_ids as $id ) {
			echo wp_kses_post( $this->fb_pixel_script_image( $id, 'PageView' ) );
			if ( ! empty( $event ) ) {
				echo wp_kses_post( $this->fb_pixel_script_image( $id, $event ) );
			}
		}
		echo '</noscript>';
		echo '<!-- End Meta Pixel Code -->';

		if ( wds_is_meta_capi() ) {
			Capi::send_event_capi_page_view( 'Thanks', $event_id1 );
			if ( ! empty( $event ) ) {
				Capi::send_event_capi( 'Thanks', $event, $event_id2 );
			}
		}
	}
}

Tracking::instance();
