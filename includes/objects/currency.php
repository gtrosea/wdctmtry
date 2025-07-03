<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves an array of available currencies with their respective labels.
 *
 * @return array An associative array of currency codes and their labels.
 */
function wds_currencies() {
	$currencies = array(
		'IDR' => 'Indonesian Rupiah (IDR)',
		'MYR' => 'Malaysian Ringgit (MYR)',
		// 'USD' => 'US Dollar (USD)',
		// 'GBP' => 'UK Pound (GBP)',
		// 'THB' => 'Thai Baht (THB)',
		// 'INR' => 'Indian Rupee (INR)',
	);

	/**
	 * Filter the list of available currencies.
	 *
	 * @param array $currencies An associative array of currency codes and their labels.
	 */
	return (array) apply_filters( 'wds_currencies', $currencies );
}

/**
 * Converts a number into a formatted monetary value.
 *
 * @param float  $number          The numeric value to convert.
 * @param string $currency_symbol Optional. The currency symbol to prepend or append. Default false.
 * @return string The formatted monetary value with currency symbol.
 */
function wds_convert_money( $number, $currency_symbol = false ) {
	$number = floatval( $number );

	$currency = wds_option( 'currency' );
	$symbol   = 'IDR' == $currency ? 'Rp ' : 'RM';

	$thousand_separator = wds_option( 'thousand_separator' );
	$decimal_separator  = wds_option( 'decimal_separator' );
	$number_of_decimal  = intval( wds_option( 'number_of_decimal' ) );

	// Format the number based on user-defined settings.
	$money = number_format( $number, $number_of_decimal, $decimal_separator, $thousand_separator );

	// if ( false === $currency_symbol ) {
	//  $currency_symbol = wds_option( 'currency_symbol' );
	// }

	$currency_position = wds_option( 'currency_position' );

	// Add currency symbol to the formatted value.
	if ( empty( $currency_position ) || 'left' === $currency_position ) {
		$money = $symbol . $money;
	} else {
		$money = $money . ' ' . $symbol;
	}

	/**
	 * Filter the converted monetary value.
	 *
	 * @param string $money  The formatted money string.
	 * @param float  $number The original numeric value.
	 */
	return apply_filters( 'wds_convert_money', $money, $number );
}

/**
 * Converts a price into a rounded or formatted value based on settings.
 *
 * @param float $price The price to convert.
 * @return float The converted price.
 */
function wds_convert_price( $price ) {
	$converted_price = floatval( $price );

	// Round the price if no decimals are allowed.
	if ( intval( wds_option( 'number_of_decimal' ) ) === 0 ) {
		$converted_price = round( $converted_price );
	}

	/**
	 * Filter the converted price.
	 *
	 * @param float $converted_price The converted price.
	 * @param float $price           The original price.
	 */
	return apply_filters( 'wds_convert_price', $converted_price, $price );
}
