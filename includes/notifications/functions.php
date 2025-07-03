<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Replaces shortcodes in email content with provided arguments.
 *
 * @param string $content The email content containing shortcodes to replace.
 * @param array  $args    Optional. An array of replacements for the shortcodes. Default empty.
 * @return string The email content with shortcodes replaced by their corresponding values.
 */
function wds_email_replace_shortcode( $content, $args = array() ) {
	$default = array(
		'site-name' => get_bloginfo( 'name' ),
		'site-url'  => get_site_url(),
		'login-url' => wds_url( 'login' ),
	);

	$args = wp_parse_args( $args, $default );

	preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );

	foreach ( $matches[1] as $key => $tag ) {
		if ( isset( $args[ $tag ] ) ) {
			$content = str_replace( '[' . $tag . ']', $args[ $tag ], $content );
		}
	}

	return $content;
}

/**
 * Initialize the email notification system.
 *
 * This function creates a new instance of the Main notification class with the specified target for email notifications.
 *
 * @param string|bool $target The target identifier for the email notification. Default is false.
 * @return WDS\Notifications\Main|null Returns an instance of the Main class or null if the target is not provided.
 */
function wds_email( $target = false ) {
	if ( empty( $target ) ) {
		return null;
	}

	return new WDS\Notifications\Main( $target );
}

/**
 * Initialize the WhatsApp notification system.
 *
 * This function creates a new instance of the Main notification class with the specified target for WhatsApp notifications.
 *
 * @param string|bool $target The target identifier for the WhatsApp notification. Default is false.
 * @return WDS\Notifications\Main|null Returns an instance of the Main class or null if the target is not provided.
 */
function wds_whatsapp( $target = false ) {
	if ( empty( $target ) ) {
		return null;
	}

	return new WDS\Notifications\Main( $target );
}

/**
 * Generates the email layout and applies the given content within it.
 *
 * @param string $content The email content to be included in the layout.
 * @return string The complete email content with the layout applied.
 */
function wds_email_layout( $content ) {
	ob_start();

	$temp = wds_option( 'email_template' );
	$temp = ! empty( $temp ) ? $temp : 'default';

	include wds_get_template( 'email/' . $temp . '.php' );

	return apply_filters( 'wds_email_layout', ob_get_clean(), $content );
}

/**
 * Generate payment link.
 *
 * @param int $invoice_id The invoice ID.
 * @return string The payment link.
 */
function wds_email_payment_link( $invoice_id ) {
	return wds_lang( 'trx_payment_link_payment' ) . wds_url( 'pay', wds_encrypt_decrypt( $invoice_id ) );
}

/**
 * Format gateway bank transfer data.
 *
 * @return string The payment bank.
 */
function wds_banktransfer_email_format() {
	$text  = '';
	$banks = wds_option( 'banktransfer_bank' );
	if ( ! empty( $banks ) ) {
		foreach ( $banks as $data ) {
			$bank_name = $data['name'];
			if ( 'other' == $bank_name ) {
				$bank_name = $data['name_input'];
			}

			if ( in_array( $bank_name, array( 'DANA', 'OVO', 'GOPAY', 'GoPay', 'SHOPEEPAY', 'ShopeePay', 'LINKAJA', 'LinkAja' ) ) ) {
				$text .= '- ' . $bank_name . ', ' . $data['account_number'] . ', a.n. ' . $data['account_name'] . "\n";
			} else {
				$text .= '- Bank ' . $bank_name . ', ' . $data['account_number'] . ', a.n. ' . $data['account_name'] . "\n";
			}
		}
	}

	return $text;
}
