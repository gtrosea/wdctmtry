<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve product statuses or a specific status label.
 *
 * @param string|false $status Optional. The specific status to retrieve the label for. Default is false.
 * @return array|string|false Array of all statuses, a specific status label, or false if not found.
 */
function wds_get_product_statuses( $status = false ) {
	$statuses = array(
		'draft'    => __( 'Draft', 'wds-notrans' ),
		'active'   => __( 'Active', 'wds-notrans' ),
		'inactive' => __( 'Inactive', 'wds-notrans' ),
	);

	$statuses = apply_filters( 'wds_product_statuses', $statuses, $status );

	if ( $status ) {
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : false;
	}

	return $statuses;
}

/**
 * Retrieve coupon statuses or a specific status label.
 *
 * @param string|false $status Optional. The specific status to retrieve the label for. Default is false.
 * @return array|string|false Array of all statuses, a specific status label, or false if not found.
 */
function wds_get_coupon_statuses( $status = false ) {
	$statuses = array(
		'draft'    => __( 'Draft', 'wds-notrans' ),
		'active'   => __( 'Active', 'wds-notrans' ),
		'inactive' => __( 'Inactive', 'wds-notrans' ),
	);

	$statuses = apply_filters( 'wds_coupon_statuses', $statuses, $status );

	if ( $status ) {
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : false;
	}

	return $statuses;
}

/**
 * Retrieve invoice statuses or a specific status label.
 *
 * @param string|false $status Optional. The specific status to retrieve the label for. Default is false.
 * @return array|string|false Array of all statuses, a specific status label, or false if not found.
 */
function wds_get_invoice_statuses( $status = false ) {
	$statuses = array(
		'unpaid'    => __( 'Unpaid', 'wds-notrans' ),
		'completed' => __( 'Completed', 'wds-notrans' ),
		'cancelled' => __( 'Cancelled', 'wds-notrans' ),
		// 'refunded'         => __( 'Refunded', 'wds-notrans' ),
		// 'checking_payment' => __( 'Checking Payment', 'wds-notrans' ),
	);

	$statuses = apply_filters( 'wds_invoice_statuses', $statuses, $status );

	if ( $status ) {
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : false;
	}

	return $statuses;
}

/**
 * Retrieve order statuses or a specific status label.
 *
 * @param string|false $status Optional. The specific status to retrieve the label for. Default is false.
 * @return array|string|false Array of all statuses, a specific status label, or false if not found.
 */
function wds_get_order_statuses( $status = false ) {
	$statuses = array(
		'active'   => __( 'Active', 'wds-notrans' ),
		'inactive' => __( 'Inactive', 'wds-notrans' ),
		'expired'  => __( 'Expired', 'wds-notrans' ),
	);

	$statuses = apply_filters( 'wds_order_statuses', $statuses, $status );

	if ( $status ) {
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : false;
	}

	return $statuses;
}

/**
 * Retrieve commission statuses or a specific status label.
 *
 * @param string|false $status Optional. The specific status to retrieve the label for. Default is false.
 * @return array|string|false Array of all statuses, a specific status label, or false if not found.
 */
function wds_get_commission_statuses( $status = false ) {
	$statuses = array(
		'paid'      => __( 'Paid', 'wds-notrans' ),
		'unpaid'    => __( 'Unpaid', 'wds-notrans' ),
		'pending'   => __( 'Pending', 'wds-notrans' ),
		'cancelled' => __( 'Cancelled', 'wds-notrans' ),
	);

	$statuses = apply_filters( 'wds_commissions_statuses', $statuses, $status );

	if ( $status ) {
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : false;
	}

	return $statuses;
}

/**
 * Retrieve replica statuses or a specific status label.
 *
 * @param string|false $status Optional. The specific status to retrieve the label for. Default is false.
 * @return array|string|false Array of all statuses, a specific status label, or false if not found.
 */
function wds_get_replica_statuses( $status = false ) {
	$statuses = array(
		'active'      => __( 'Active', 'wds-notrans' ),
		'blocked'     => __( 'Blocked', 'wds-notrans' ),
		'connected'   => __( 'Connected', 'wds-notrans' ),
		'unconnected' => __( 'Unconnected', 'wds-notrans' ),
	);

	$statuses = apply_filters( 'wdr_statuses', $statuses, $status );

	if ( $status ) {
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : false;
	}

	return $statuses;
}
