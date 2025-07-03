<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Returns a list of all available gateways.
 *
 * @since 2.0.0
 * @return array $gateways All the available gateways.
 */
function wds_get_gateways() {
	$gateways = array(
		'banktransfer' => 'Bank Transfer',
		'qris'         => 'QRIS',
		'xendit'       => 'Xendit',
	);

	if ( wds_is_midtrans() ) {
		$gateways['midtrans'] = 'Midtrans';
	}

	if ( wds_is_flip() ) {
		$gateways['flip'] = 'Flip';
	}

	$duitku = wds_get_duitku_channel( 'list', array() );
	$tripay = wds_get_tripay_channel( 'list', array() );

	$gateways = array_merge( $gateways, $duitku, $tripay );

	return apply_filters( 'wds_payment_gateways', $gateways );
}

/**
 * Get default gateway selected.
 *
 * @since 2.0.0
 * @return string The default gateway.
 */
function wds_get_default_gateway() {
	$default_gateway = wds_option( 'gateway_default' );

	return apply_filters( 'wds_default_gateway', $default_gateway );
}

/**
 * Returns a list of all active gateways.
 *
 * @since 2.0.0
 * @return array $gateways All the active gateways.
 */
function wds_get_active_gateways() {
	$gateways = wds_get_gateways();
	$default  = wds_get_default_gateway();
	$active   = wds_option( 'gateway_active' );

	$gateway_list = array();
	if ( wds_check_array( $active, true ) ) {
		foreach ( $active as $gateway ) {
			if ( isset( $gateways[ $gateway ] ) ) {
				$gateway_list[ $gateway ] = $gateways[ $gateway ];
			}
		}
	}

	if ( isset( $gateway_list[ $default ] ) ) {
		$gateway_list = array( $default => $gateways[ $default ] ) + $gateway_list;
	}

	return apply_filters( 'wds_active_gateways', $gateway_list );
}

/**
 * Checks whether a specified gateway is activated.
 *
 * @since 2.0.0
 * @param string $gateway Name of the gateway to check for.
 * @return boolean true if enabled, false otherwise.
 */
function wds_is_gateway_active( $gateway ) {
	$gateways = wds_get_active_gateways();
	$retval   = false;

	foreach ( array_keys( $gateways ) as $active_gateway ) {
		if ( $gateway === $active_gateway || strpos( $active_gateway, $gateway ) === 0 ) {
			$retval = true;
			break;
		}
	}

	return apply_filters( 'wds_is_gateway_active', $retval, $gateway, $gateways );
}

/**
 * Returns the label for the specified gateway.
 *
 * @since 2.0.0
 * @param string $gateway Name of the gateway to retrieve a label for.
 * @return string label for the gateway.
 */
function wds_get_gateway_label( $gateway ) {
	$gateways = wds_get_gateways();
	$label    = isset( $gateways[ $gateway ] ) ? $gateways[ $gateway ] : ucfirst( $gateway );

	if ( strpos( $label, 'Tripay_' ) === 0 ) {
		$label = 'Tripay';
	} elseif ( strpos( $label, 'Duitku_' ) === 0 ) {
		$label = 'Duitku';
	}

	return apply_filters( 'wds_gateway_label', $label, $gateway );
}

/**
 * Retrieve specific gateway data based on key.
 *
 * @param string $_id The unique ID of the gateway.
 * @param string $key The specific key to retrieve.
 * @return mixed Returns the requested gateway data based on the key, or false if parameters are invalid.
 */
function wds_gateway( $_id, $key = false ) {
	if ( empty( $_id ) ) {
		return false;
	}

	$gateway = new WDS\Gateway\Main( $_id );

	$ret = $gateway;
	switch ( $key ) {
		case 'icon_enable':
			$ret = $gateway->get_icon_enable();
			break;

		case 'icon':
			$ret = $gateway->get_icon();
			break;

		case 'title':
			$ret = $gateway->get_title();
			break;

		case 'instruction':
			$ret = $gateway->get_instruction();
			break;

		case 'currency':
			$ret = $gateway->get_currency();
			break;

		case 'unique_number':
			$ret = $gateway->use_unique_number();
			break;

		case 'action':
			$ret = $gateway->print_action();
			break;
	}

	return $ret;
}

/**
 * Get duitku channel enable.
 *
 * @since 2.0.0
 * @param string $key The key to get data.
 * @param string $default The default if failed get data.
 * @return mixed The list payment channel.
 */
function wds_get_duitku_channel( $key, $default = false ) {
	$channels = get_transient( WDS_SLUG . '_duitku' );
	$channels = get_option( WDS_SLUG . '_duitku' ) ? get_option( WDS_SLUG . '_duitku' ) : $channels;
	if ( ! $channels || empty( $channels ) || ! is_array( $channels ) || empty( $key ) ) {
		return $default;
	}

	$ret = $default;
	switch ( $key ) {
		case 'list':
			foreach ( $channels as $channel ) {
				$ret[ 'duitku_' . strtolower( $channel['paymentMethod'] ) ] = 'Duitku - ' . $channel['paymentName'];
			}
			break;

		case 'name':
			foreach ( $channels as $channel ) {
				$ret[] = 'duitku_' . strtolower( $channel['paymentMethod'] );
			}
			break;

		case 'all':
			$ret = $channels;
			break;
	}

	return $ret;
}

/**
 * Get tripay channel enable.
 *
 * @since 2.0.0
 * @param string $key The key to get data.
 * @param string $default The default if failed get data.
 * @return mixed The list payment channel.
 */
function wds_get_tripay_channel( $key, $default = false ) {
	$channels = get_transient( WDS_SLUG . '_tripay' );
	$channels = get_option( WDS_SLUG . '_tripay' ) ? get_option( WDS_SLUG . '_tripay' ) : $channels;
	if ( ! $channels || empty( $channels ) || ! is_array( $channels ) || empty( $key ) ) {
		return $default;
	}

	$ret = $default;
	switch ( $key ) {
		case 'list':
			foreach ( $channels as $channel ) {
				$ret[ 'tripay_' . strtolower( $channel['code'] ) ] = 'Tripay - ' . $channel['name'];
			}
			break;

		case 'name':
			foreach ( $channels as $channel ) {
				$ret[] = 'tripay_' . strtolower( $channel['code'] );
			}
			break;

		case 'all':
			$ret = $channels;
			break;
	}

	return $ret;
}

/**
 * Get gateway reference.
 *
 * @since 2.0.0
 * @param int    $user_id The user ID.
 * @param int    $invoice_id The invoice ID.
 * @param string $invoice_number The invoice number.
 * @param string $gateway The gateway key.
 * @param array  $summary The data summary.
 * @return mixed The data gateway reference.
 */
function wds_get_gateway_reference( $user_id = 0, $invoice_id = 0, $invoice_number = false, $gateway = false, $summary = array() ) {
	$ret = null;

	$total    = wds_sanitize_data_field( $summary, 'total' );
	$title    = wds_sanitize_data_field( $summary, 'product_title' );
	$price    = wds_sanitize_data_field( $summary, 'product_price' );
	$discount = wds_sanitize_data_field( $summary, 'discount', false );

	if ( 0 == $total ) {
		return $ret;
	}

	$addon_fixed = wds_sanitize_data_field( $summary, 'addon_fixed', false );
	$addon_price = wds_sanitize_data_field( $summary, 'addon_price', false );
	$addon_title = wds_sanitize_data_field( $summary, 'addon_title', false );
	$is_addons   = ! empty( $addon_price ) && ! empty( $addon_title );

	if ( $is_addons && ! $addon_fixed ) {
		$title = $title . ' + Addons';
		$price = $price + $addon_price;
	} elseif ( $is_addons && $addon_fixed ) {
		$addon_title = 'Addon (' . $addon_title . ')';
	}

	if ( strpos( $gateway, 'tripay' ) === 0 ) {
		$channel = strtoupper( str_replace( 'tripay_', '', $gateway ) );

		$item[] = array(
			'name'     => $title,
			'price'    => $price,
			'quantity' => 1,
		);

		if ( $is_addons && $addon_fixed ) {
			$item[] = array(
				'name'     => $addon_title,
				'price'    => $addon_price,
				'quantity' => 1,
			);
		}

		if ( $discount ) {
			$item[] = array(
				'name'     => __( 'Discount', 'wds-notrans' ),
				'price'    => -$discount,
				'quantity' => 1,
			);
		}

		$args = array(
			'method'         => $channel,
			'merchant_ref'   => $invoice_number,
			'amount'         => $total,
			'customer_name'  => wds_user_name( $user_id ),
			'customer_email' => wds_user_email( $user_id ),
			'customer_phone' => wds_user_phone( $user_id ),
			'order_items'    => $item,
		);

		$calback_url = get_rest_url( null, 'weddingsaas-tripay/v1/webhook' );
		$return_url  = wds_url( 'pay', wds_encrypt_decrypt( $invoice_id ) );

		$ret = \WDS\Gateway\Tripay::request_payment( $args, $calback_url, $return_url );
	} elseif ( strpos( $gateway, 'duitku' ) === 0 ) {
		$channel = strtoupper( str_replace( 'duitku_', '', $gateway ) );

		$item[] = array(
			'name'     => $title,
			'price'    => $price,
			'quantity' => 1,
		);

		if ( $is_addons && $addon_fixed ) {
			$item[] = array(
				'name'     => $addon_title,
				'price'    => $addon_price,
				'quantity' => 1,
			);
		}

		if ( $discount ) {
			$item[] = array(
				'name'     => __( 'Discount', 'wds-notrans' ),
				'price'    => -$discount,
				'quantity' => 1,
			);
		}

		$data = array(
			'amount'      => $total,
			'product'     => $title,
			'method'      => $channel,
			'orderId'     => $invoice_id,
			'userInfo'    => wds_user_name( $user_id ),
			'userEmail'   => wds_user_email( $user_id ),
			'phoneNumber' => wds_user_phone( $user_id ),
			'itemDetails' => $item,
		);

		$ret = \WDS\Gateway\Duitku::request_payment( $data );
	} elseif ( 'xendit' == $gateway ) {
		$user_data = array(
			'given_names'   => wds_user_name( $user_id ),
			'email'         => wds_user_email( $user_id ),
			'mobile_number' => wds_user_phone( $user_id ),
		);

		$item[] = array(
			'name'     => $title,
			'price'    => $price,
			'quantity' => 1,
		);

		if ( $is_addons && $addon_fixed ) {
			$item[] = array(
				'name'     => $addon_title,
				'price'    => $addon_price,
				'quantity' => 1,
			);
		}

		$data = array(
			'external_id'           => 'INV_' . $invoice_id,
			'amount'                => $total,
			'description'           => 'Pembayaran untuk invoice #' . $invoice_number,
			'customer'              => $user_data,
			'invoice_duration'      => wds_option( 'invoice_due_date' ) * 86400,
			'success_redirect_url'  => wds_url( 'thanks', wds_encrypt_decrypt( $invoice_id ) ),
			'failure_redirect_url'  => wds_url( 'overview' ),
			'currency'              => 'IDR',
			'locale'                => 'id',
			'items'                 => $item,
			'client_type'           => 'INTEGRATION',
			'platform_callback_url' => home_url( '?wds_xendit=xendit_invoice_callback' ),
		);

		if ( $discount ) {
			$data['fees'] = array(
				array(
					'type'  => __( 'Discount', 'wds-notrans' ),
					'value' => -$discount,
				),
			);
		}

		if ( wds_option( 'xendit_notification' ) ) {
			$data['customer_notification_preference'] = array(
				'invoice_created'  => array(
					'whatsapp',
					'email',
				),
				'invoice_reminder' => array(
					'whatsapp',
					'email',
				),
				'invoice_paid'     => array(
					'whatsapp',
					'email',
				),
			);
		}

		$ret = \WDS\Gateway\Xendit::create_invoice( $data );
	} elseif ( 'midtrans' == $gateway ) {
		$user_data = array(
			'id'    => $user_id,
			'name'  => wds_user_name( $user_id ),
			'email' => wds_user_email( $user_id ),
			'phone' => ltrim( wds_user_phone( $user_id ), '0' ),
		);

		$item[] = array(
			'description' => $title,
			'price'       => $price,
			'quantity'    => 1,
		);

		if ( $is_addons && $addon_fixed ) {
			$item[] = array(
				'description' => $addon_title,
				'price'       => $addon_price,
				'quantity'    => 1,
			);
		}

		// Set timezone to GMT+7
		$timezone = new \DateTimeZone( 'Asia/Jakarta' );

		// Create the current date and time for invoice_date
		$invoice_date           = new \DateTime( 'now', $timezone );
		$invoice_date_formatted = $invoice_date->format( 'Y-m-d H:i:s O' );

		// Calculate due_date by adding a specific duration
		$due_date = clone $invoice_date;
		$add_day  = wds_option( 'invoice_due_date' );
		$due_date->modify( "+$add_day days" );
		$due_date_formatted = $due_date->format( 'Y-m-d H:i:s O' );

		$data = array(
			'order_id'         => 'INV_' . $invoice_id,
			'invoice_number'   => $invoice_number,
			'due_date'         => $due_date_formatted,
			'invoice_date'     => $invoice_date_formatted,
			'customer_details' => $user_data,
			'payment_type'     => 'payment_link',
			'item_details'     => $item,
		);

		if ( $discount ) {
			$data['amount'] = array( 'discount' => $discount );
		}

		$ret = \WDS\Gateway\Midtrans::create_invoice( $data );
	}

	return apply_filters( 'wds_get_gateway_reference', $ret, $user_id, $invoice_id, $invoice_number, $gateway, $summary );
}
