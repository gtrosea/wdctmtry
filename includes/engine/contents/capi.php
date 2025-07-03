<?php

namespace WDS\Engine\Contents;

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Capi Class.
 *
 * @since 2.0.0
 */
class Capi {

	/**
	 * Sends a "PageView" event to the Facebook Conversion API.
	 *
	 * This method sends a "PageView" event to Facebook's Conversion API (CAPI) with
	 * user-specific hashed data, such as email, phone number, first name, and other data.
	 * The event is triggered based on the provided page title and unique event ID.
	 *
	 * @param string $title    The title or name of the page where the event is being fired.
	 * @param string $event_id The unique event ID for tracking purposes.
	 * @return void
	 */
	public static function send_event_capi_page_view( $title, $event_id ) {
		$capi_enable    = wds_engine( 'fbpixel_capi' );
		$capi_id        = wds_engine( 'fbpixel_capi_id' );
		$capi_token     = wds_engine( 'fbpixel_capi_token' );
		$capi_test_code = wds_engine( 'fbpixel_capi_test_code' );

		if ( ! $capi_enable || empty( $capi_id ) || empty( $capi_token ) ) {
			return;
		}

		$login = is_user_logged_in() ? true : false;

		$email = hash( 'sha256', strtolower( trim( wp_get_current_user()->user_email ) ) );
		$email = ! $login ? null : array( $email );

		$phone = hash( 'sha256', preg_replace( '/[^0-9]/', '', wds_user_phone( get_current_user_id() ) ) );
		$phone = ! $login ? null : array( $phone );

		$fname = hash( 'sha256', strtolower( trim( wp_get_current_user()->first_name ) ) );
		$fname = ! $login ? null : $fname;

		$fbc        = wds_sanitize_data_field( $_COOKIE, '_fbc' );
		$fbp        = wds_sanitize_data_field( $_COOKIE, '_fbp' );
		$ip_address = wds_sanitize_data_field( $_SERVER, 'REMOTE_ADDR' );
		$user_agent = wds_sanitize_data_field( $_SERVER, 'HTTP_USER_AGENT' );

		$data_view = array(
			'page'   => $title,
			'plugin' => WDS_NAME,
		);

		Api::init( null, null, $capi_token );
		$api = Api::instance();
		$api->setLogger( new CurlLogger() );

		$user_data = ( new UserData() )
			->setEmails( $email )
			->setPhones( $phone )
			->setFirstName( $fname )
			->setClientIpAddress( $ip_address )
			->setClientUserAgent( $user_agent )
			->setFbc( $fbc )
			->setFbp( $fbp );

		$custom_data = ( new CustomData() )
			->setCustomProperties( $data_view );

		$event = ( new Event() )
			->setEventName( 'PageView' )
			->setEventTime( time() )
			->setEventId( $event_id )
			->setEventSourceUrl( esc_url_raw( home_url( $_SERVER['REQUEST_URI'] ) ) )
			->setUserData( $user_data )
			->setCustomData( $custom_data )
			->setActionSource( ActionSource::WEBSITE );

		$events = array( $event );

		$request = ( new EventRequest( $capi_id ) )
			->setEvents( $events );

		if ( ! empty( $capi_test_code ) ) {
			$request->setTestEventCode( $capi_test_code );
		}

		$response = $request->execute();

		// try {
		//  $response = $request->execute();
		// } catch ( \Exception $e ) {
		//  error_log( 'Request failed: ' . $e->getMessage() );
		//  error_log( 'Request Data: ' . print_r( $request, true ) );
		// }
	}

	/**
	 * Sends a custom event to the Facebook Conversion API.
	 *
	 * This method sends a specified event to Facebook's Conversion API (CAPI),
	 * with custom data such as product or invoice information, as well as user-specific
	 * hashed data. The event name, page title, and event ID are customizable based on the context.
	 *
	 * @param string $title      The title or context of the page/event.
	 * @param string $event_name The specific event name to be sent to Facebook.
	 * @param string $event_id   The unique event ID for tracking purposes.
	 * @return void
	 */
	public static function send_event_capi( $title, $event_name, $event_id ) {
		$capi_enable    = wds_engine( 'fbpixel_capi' );
		$capi_id        = wds_engine( 'fbpixel_capi_id' );
		$capi_token     = wds_engine( 'fbpixel_capi_token' );
		$capi_test_code = wds_engine( 'fbpixel_capi_test_code' );

		if ( ! $capi_enable || empty( $capi_id ) || empty( $capi_token ) ) {
			return;
		}

		$login = is_user_logged_in() ? true : false;

		$email = hash( 'sha256', strtolower( trim( wp_get_current_user()->user_email ) ) );
		$email = ! $login ? null : array( $email );

		$phone = hash( 'sha256', preg_replace( '/[^0-9]/', '', wds_user_phone( get_current_user_id() ) ) );
		$phone = ! $login ? null : array( $phone );

		$fname = hash( 'sha256', strtolower( trim( wp_get_current_user()->first_name ) ) );
		$fname = ! $login ? null : $fname;

		$fbc        = wds_sanitize_data_field( $_COOKIE, '_fbc' );
		$fbp        = wds_sanitize_data_field( $_COOKIE, '_fbp' );
		$ip_address = wds_sanitize_data_field( $_SERVER, 'REMOTE_ADDR' );
		$user_agent = wds_sanitize_data_field( $_SERVER, 'HTTP_USER_AGENT' );

		Api::init( null, null, $capi_token );
		$api = Api::instance();
		$api->setLogger( new CurlLogger() );

		$user_data = ( new UserData() )
			->setEmails( $email )
			->setPhones( $phone )
			->setFirstName( $fname )
			->setClientIpAddress( $ip_address )
			->setClientUserAgent( $user_agent )
			->setFbc( $fbc )
			->setFbp( $fbp );

		if ( 'Checkout' == $title ) {
			$data    = wds_data( 'data' );
			$summary = wds_sanitize_data_field( $data, 'summary' );

			$properties = array(
				'page'   => $title,
				'plugin' => WDS_NAME,
			);

			$content = ( new Content() )
				->setProductId( wds_sanitize_data_field( $summary, 'product_id' ) )
				->setQuantity( 1 )
				->setItemPrice( wds_sanitize_data_field( $summary, 'total' ) )
				->setTitle( wds_sanitize_data_field( $summary, 'product_title' ) );

			$custom_data = ( new CustomData() )
				->setContents( array( $content ) )
				->setCurrency( wds_option( 'currency' ) )
				->setValue( wds_sanitize_data_field( $summary, 'total' ) )
				->setCustomProperties( $properties );
		} else {
			$invoice = wds_data( 'invoice' );

			$properties = array(
				'page'           => $title,
				'plugin'         => WDS_NAME,
				'invoice_id'     => $invoice->ID,
				'invoice_number' => $invoice->number,
				'subtotal'       => wds_convert_money( wds_invoice_summary( $invoice, 'subtotal' ) ),
				'discount'       => wds_convert_money( wds_invoice_summary( $invoice, 'discount' ) ),
				'total'          => wds_convert_money( wds_invoice_summary( $invoice, 'total' ) ),
				'gateway'        => $invoice->gateway,
				'type'           => $invoice->type,
			);

			$product_id    = wds_invoice_summary( $invoice, 'product_id' );
			$product_name  = wds_invoice_summary( $invoice, 'product_title' );
			$product_price = wds_invoice_summary( $invoice, 'product_price' );
			$status        = $invoice->status;

			$content = ( new Content() )
				->setProductId( $product_id )
				->setQuantity( 1 )
				->setItemPrice( $product_price )
				->setTitle( $product_name );

			$custom_data = ( new CustomData() )
				->setContents( array( $content ) )
				->setCurrency( wds_option( 'currency' ) )
				->setValue( $product_price )
				->setStatus( $status )
				->setCustomProperties( $properties );
		}

		$event = ( new Event() )
			->setEventName( $event_name )
			->setEventTime( time() )
			->setEventId( $event_id )
			->setEventSourceUrl( esc_url_raw( home_url( $_SERVER['REQUEST_URI'] ) ) )
			->setUserData( $user_data )
			->setCustomData( $custom_data )
			->setActionSource( ActionSource::WEBSITE );

		$events = array( $event );

		$request = ( new EventRequest( $capi_id ) )
			->setEvents( $events );

		if ( ! empty( $capi_test_code ) ) {
			$request->setTestEventCode( $capi_test_code );
		}

		$response = $request->execute();

		// try {
		//  $response = $request->execute();
		// } catch ( \Exception $e ) {
		//  error_log( 'Request failed: ' . $e->getMessage() );
		//  error_log( 'Request Data: ' . print_r( $request, true ) );
		// }
	}
}
