<?php
/**
 * WeddingSaas Statistics.
 *
 * This class provides various statistical data such as leads, sales, profits,
 * and conversion rates. It also provides hourly, daily, and monthly breakdowns.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Statistics Class.
 */
class WDS_Statistics {

	/**
	 * Calculates the total revenue, profit, and commission based on completed invoices.
	 *
	 * @return object Contains revenue, profit, and commission amounts.
	 */
	public static function get_profit() {
		global $wpdb;

		$prefix = $wpdb->prefix . WDS_MODEL;

		$invoice       = $prefix . '_invoice';
		$invoice_order = $prefix . '_invoice_order';
		$order         = $prefix . '_order';
		$commission    = $prefix . '_commission';

		$products = $wpdb->get_col( $wpdb->prepare( 'SELECT ID FROM %i', array( $prefix . '_product' ) ) );

		if ( empty( $products ) ) {
			return (object) array(
				'revenue'    => 0,
				'profit'     => 0,
				'commission' => 0,
			);
		}

		$products_list = implode( ',', $products );

		$query =
			"SELECT
                SUM(
                    CASE 
                    WHEN {$invoice}.status IN ('completed')
                    AND {$order}.product_id IN (" . $products_list . ")
                    THEN {$invoice}.total ELSE 0 END
                ) AS revenue
            FROM $invoice
            LEFT JOIN $invoice_order
                ON {$invoice}.ID = {$invoice_order}.invoice_id
            LEFT JOIN $order
                ON {$order}.ID = {$invoice_order}.order_id";

		$result_income = $wpdb->get_row( $query ); // phpcs:ignore

		$query =
			"SELECT
                SUM(
                    CASE 
                    WHEN {$commission}.status IN ('paid', 'unpaid')
                    AND {$commission}.product_id IN ($products_list)
                    THEN {$commission}.amount ELSE 0 END
                ) AS total
            FROM $commission";

		$result_commission = $wpdb->get_row( $query ); // phpcs:ignore

		$revenue    = $result_income ? $result_income->revenue : 0;
		$commission = $result_commission ? $result_commission->total : 0;
		$profit     = intval( $revenue ) - intval( $commission );

		return (object) array(
			'revenue'    => $revenue,
			'profit'     => $profit,
			'commission' => $commission,
		);
	}

	/**
	 * Executes a statistics query to retrieve leads and sales based on product IDs and invoice status.
	 *
	 * @param array $args { An associative array of parameters.
	 *     @type string $products       The query get product.
	 *     @type string $invoice_status The status of the invoice to filter results.
	 *     @type string $invoice_order  The name of the invoice order table.
	 *     @type string $invoice        The name of the invoice table.
	 *     @type string $order          The name of the order table.
	 * }
	 * @return object Contains arrays of leads, sales, and conversion rates.
	 */
	public static function stats_query( $args = array() ) {
		global $wpdb;

		$products       = $args['products'] ?? '';
		$invoice_status = $args['invoice_status'] ?? '';
		$invoice_order  = $args['invoice_order'] ?? '';
		$invoice        = $args['invoice'] ?? '';
		$order          = $args['order'] ?? '';

		$query =
		"SELECT
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('$invoice_status')
                AND {$order}.product_id IN ($products)
                THEN {$invoice}.ID ELSE NULL END
            ) AS leads,
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('completed')
                AND {$order}.product_id IN ($products)
                THEN {$invoice}.ID ELSE NULL END
            ) AS sales
        FROM $invoice 
        LEFT JOIN $invoice_order
            ON {$invoice}.ID = {$invoice_order}.invoice_id
        LEFT JOIN $order
            ON {$order}.ID = {$invoice_order}.order_id";

		$results = $wpdb->get_results( $query ); // phpcs:ignore

		$leads       = array();
		$sales       = array();
		$conversions = array();

		if ( $results ) {
			$data    = $results[0];
			$lead    = $data->leads ? intval( $data->leads ) : 0;
			$sale    = $data->sales ? intval( $data->sales ) : 0;
			$convert = 0 == $sale ? 0 : round( ( $sale / $lead ) * 100, 2 );

			$leads[]       = $lead;
			$sales[]       = $sale;
			$conversions[] = $convert;
		}

		return (object) array(
			'leads'       => $leads,
			'sales'       => $sales,
			'conversions' => $conversions,
		);
	}

	/**
	 * Retrieves monthly statistics for leads, sales, and conversions within a date range.
	 *
	 * @param array $args { An associative array of parameters.
	 *     @type string $start_date     The start date.
	 *     @type string $end_date       The end date.
	 *     @type string $products       The query get product.
	 *     @type string $invoice_status The status of the invoice to filter results.
	 *     @type string $invoice_order  The name of the invoice order table.
	 *     @type string $invoice        The name of the invoice table.
	 *     @type string $order          The name of the order table.
	 * }
	 * @return object Contains leads, sales, conversions, and categories (monthly periods).
	 */
	public static function monthly_query( $args = array() ) {
		global $wpdb;

		$start_date     = $args['start_date'] ?? '';
		$end_date       = $args['end_date'] ?? '';
		$products       = $args['products'] ?? '';
		$invoice_status = $args['invoice_status'] ?? '';
		$invoice_order  = $args['invoice_order'] ?? '';
		$invoice        = $args['invoice'] ?? '';
		$order          = $args['order'] ?? '';

		$periode = new \DatePeriod(
			new \DateTime( $start_date ),
			new \DateInterval( 'P1M' ),
			new \DateTime( $end_date )
		);

		$query =
		"SELECT
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('$invoice_status')
                AND {$order}.product_id IN ($products)
                AND DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                THEN {$invoice}.ID ELSE NULL END
            ) AS leads,
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('completed')
                AND {$order}.product_id IN ($products)
                AND DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                THEN {$invoice}.ID ELSE NULL END
            ) AS sales,
            DATE_FORMAT({$invoice}.created_at, '%Y-%m') as date
        FROM $invoice 
        LEFT JOIN $invoice_order
            ON {$invoice}.ID = {$invoice_order}.invoice_id
        LEFT JOIN $order
            ON {$order}.ID = {$invoice_order}.order_id
        WHERE DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
        GROUP BY DATE_FORMAT({$invoice}.created_at, '%Y-%m')";

		$results = $wpdb->get_results( $query ); // phpcs:ignore

		$data = array();
		foreach ( $results as $result ) {
			$data[ $result->date ] = $result;
		}

		$categories  = array();
		$leads       = array();
		$sales       = array();
		$conversions = array();

		foreach ( $periode as $value ) {
			$categories[] = $value->format( 'Y/m' );
			$date_key     = $value->format( 'Y-m' );
			$lead         = isset( $data[ $date_key ] ) ? intval( $data[ $date_key ]->leads ) : 0;
			$sale         = isset( $data[ $date_key ] ) ? intval( $data[ $date_key ]->sales ) : 0;
			$convert      = 0 == $sale ? 0 : round( ( $sale / $lead ) * 100, 2 );

			$leads[]       = $lead;
			$sales[]       = $sale;
			$conversions[] = $convert;
		}

		return (object) array(
			'leads'       => $leads,
			'sales'       => $sales,
			'conversions' => $conversions,
			'categories'  => $categories,
		);
	}

	/**
	 * Retrieves daily statistics for leads, sales, and conversions within a date range.
	 *
	 * @param array $args { An associative array of parameters.
	 *     @type string $start_date     The start date.
	 *     @type string $end_date       The end date.
	 *     @type string $products       The query get product.
	 *     @type string $invoice_status The status of the invoice to filter results.
	 *     @type string $invoice_order  The name of the invoice order table.
	 *     @type string $invoice        The name of the invoice table.
	 *     @type string $order          The name of the order table.
	 * }
	 * @return object Contains leads, sales, conversions, and categories (daily periods).
	 */
	public static function daily_query( $args = array() ) {
		global $wpdb;

		$start_date     = $args['start_date'] ?? '';
		$end_date       = $args['end_date'] ?? '';
		$products       = $args['products'] ?? '';
		$invoice_status = $args['invoice_status'] ?? '';
		$invoice_order  = $args['invoice_order'] ?? '';
		$invoice        = $args['invoice'] ?? '';
		$order          = $args['order'] ?? '';

		$periode = new \DatePeriod(
			new \DateTime( $start_date ),
			new \DateInterval( 'P1D' ),
			new \DateTime( $end_date )
		);

		$query =
		"SELECT
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('$invoice_status')
                AND {$order}.product_id IN ($products)
                AND DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                THEN {$invoice}.ID ELSE NULL END
            ) AS leads,
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('completed')
                AND {$order}.product_id IN ($products)
                AND DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
                THEN {$invoice}.ID ELSE NULL END
            ) AS sales,
            DATE({$invoice}.created_at) as date
        FROM $invoice 
        LEFT JOIN $invoice_order
            ON {$invoice}.ID = {$invoice_order}.invoice_id
        LEFT JOIN $order
            ON {$order}.ID = {$invoice_order}.order_id
        WHERE DATE({$invoice}.created_at) BETWEEN '$start_date' AND '$end_date'
        GROUP BY DATE({$invoice}.created_at)";

		$results = $wpdb->get_results( $query ); // phpcs:ignore

		$data = array();
		foreach ( $results as $result ) {
			$data[ $result->date ] = $result;
		}

		$categories  = array();
		$leads       = array();
		$sales       = array();
		$conversions = array();

		foreach ( $periode as $value ) {
			$categories[] = $value->format( 'Y/m/d' );
			$date_key     = $value->format( 'Y-m-d' );
			$lead         = isset( $data[ $date_key ] ) ? intval( $data[ $date_key ]->leads ) : 0;
			$sale         = isset( $data[ $date_key ] ) ? intval( $data[ $date_key ]->sales ) : 0;
			$convert      = 0 == $sale ? 0 : round( ( $sale / $lead ) * 100, 2 );

			$leads[]       = $lead;
			$sales[]       = $sale;
			$conversions[] = $convert;
		}

		return (object) array(
			'leads'       => $leads,
			'sales'       => $sales,
			'conversions' => $conversions,
			'categories'  => $categories,
		);
	}

	/**
	 * Retrieves hourly statistics for leads, sales, and conversions within a specific date.
	 *
	 * @param array $args { An associative array of parameters.
	 *     @type string $start_date     The start date.
	 *     @type string $end_date       The end date.
	 *     @type string $products       The query get product.
	 *     @type string $invoice_status The status of the invoice to filter results.
	 *     @type string $invoice_order  The name of the invoice order table.
	 *     @type string $invoice        The name of the invoice table.
	 *     @type string $order          The name of the order table.
	 * }
	 * @return object Contains leads, sales, conversions, and categories (hourly periods).
	 */
	public static function hourly_query( $args = array() ) {
		global $wpdb;

		$start_date     = $args['start_date'] ?? '';
		$end_date       = $args['end_date'] ?? '';
		$products       = $args['products'] ?? '';
		$invoice_status = $args['invoice_status'] ?? '';
		$invoice_order  = $args['invoice_order'] ?? '';
		$invoice        = $args['invoice'] ?? '';
		$order          = $args['order'] ?? '';

		$periode   = array();
		$startdate = $start_date;
		$enddate   = gmdate( 'Y-m-d 23:00:00', strtotime( $end_date ) );
		$periode[] = $startdate;
		while ( $startdate < $enddate ) {
			$startdate = strtotime( $startdate ) + 3600;
			$startdate = gmdate( 'Y-m-d H:i:s', $startdate );
			$periode[] = $startdate;
		}

		$query =
		"SELECT
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('$invoice_status')
                AND {$order}.product_id IN ($products)
                AND {$invoice}.created_at BETWEEN '$start_date' AND '$end_date'
                THEN {$invoice}.ID ELSE NULL END
            ) AS leads,
            COUNT(
                DISTINCT CASE 
                WHEN {$invoice}.status IN ('completed')
                AND {$order}.product_id IN ($products)
                AND {$invoice}.created_at BETWEEN '$start_date' AND '$end_date'
                THEN {$invoice}.ID ELSE NULL END
            ) AS sales,
            DATE_FORMAT({$invoice}.created_at, '%Y-%m-%d %H:00:00') as date
        FROM $invoice 
        LEFT JOIN $invoice_order
            ON {$invoice}.ID = {$invoice_order}.invoice_id
        LEFT JOIN $order
            ON {$order}.ID = {$invoice_order}.order_id
        WHERE {$invoice}.created_at BETWEEN '$start_date' AND '$end_date'
        GROUP BY DATE_FORMAT({$invoice}.created_at, '%Y-%m-%d %H:00:00')";

		$results = $wpdb->get_results( $query ); // phpcs:ignore

		$data = array();
		foreach ( $results as $result ) {
			$data[ $result->hour ] = $result;
		}

		$hours       = range( 0, 23 );
		$categories  = array();
		$leads       = array();
		$sales       = array();
		$conversions = array();

		foreach ( $hours as $hour ) {
			$time         = str_pad( $hour, 2, '0', STR_PAD_LEFT ) . ':00';
			$categories[] = $time;
			$lead         = isset( $data[ $hour ] ) ? intval( $data[ $hour ]->leads ) : 0;
			$sale         = isset( $data[ $hour ] ) ? intval( $data[ $hour ]->sales ) : 0;
			$convert      = 0 == $sale ? 0 : round( ( $sale / $lead ) * 100, 2 );

			$leads[]       = $lead;
			$sales[]       = $sale;
			$conversions[] = $convert;
		}

		return (object) array(
			'leads'       => $leads,
			'sales'       => $sales,
			'conversions' => $conversions,
			'categories'  => $categories,
		);
	}

	/**
	 * Retrieve a summary of affiliate statistics, including sales, leads, and commissions.
	 *
	 * @param int|false $affiliate_id Optional. Affiliate ID. Default is the current user.
	 * @return object Contains leads, sales, conversions, and categories (hourly periods).
	 */
	public static function affiliate_summary( $affiliate_id = false ) {
		global $wpdb;

		if ( ! $affiliate_id ) {
			$affiliate_id = get_current_user_id();
		}

		$affiliate_id = intval( $affiliate_id );
		$commission   = $wpdb->prefix . WDS_MODEL . '_commission';

		$and   = '';
		$query = "
        SELECT
            COUNT(CASE WHEN status IN ('unpaid', 'paid') {$and} THEN 1 ELSE NULL END) AS sales, 
            COUNT(CASE WHEN status IN ('pending', 'unpaid', 'paid', 'cancelled', 'refunded') {$and} THEN 1 ELSE NULL END) AS leads,
            JSON_OBJECT(
                'total', SUM(CASE WHEN status IN ('paid', 'unpaid') {$and} THEN amount ELSE 0 END),
                'pending', SUM(CASE WHEN status = 'pending' {$and} THEN amount ELSE 0 END),
                'paid', SUM(CASE WHEN status = 'paid' {$and} THEN amount ELSE 0 END),
                'unpaid', SUM(CASE WHEN status = 'unpaid' {$and} THEN amount ELSE 0 END),
                'cancelled', SUM(CASE WHEN status = 'cancelled' {$and} THEN amount ELSE 0 END)
            ) AS commission
        FROM {$wpdb->users} user
        LEFT JOIN {$commission} commission ON user.ID = commission.user_id
        WHERE user.ID IN ($affiliate_id)
        GROUP BY user.ID ORDER BY sales DESC, leads DESC";

		$result = $wpdb->get_row( $query ); // phpcs:ignore

		$result2 = WDS\Models\Affiliate::select( 'COUNT(*) AS clicks, COUNT(DISTINCT ip) AS uclicks' )->query( 'WHERE affiliate_id = %d', $affiliate_id )->get();

		$summary = (object) array(
			'sales'      => $result->sales,
			'leads'      => $result->leads,
			'commission' => json_decode( $result->commission ),
			'clicks'     => intval( $result2[0]->clicks ),
			'uclicks'    => intval( $result2[0]->uclicks ),
		);

		return $summary;
	}

	/**
	 * Get commission statistics including totals for each status.
	 *
	 * @return array Commission statistics for paid, unpaid, pending, and cancelled commissions.
	 */
	public static function commissions_stats() {
		$select = "SUM(CASE WHEN status IN ('paid', 'unpaid') THEN amount ELSE 0 END) AS total,
        SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) AS pending,
        SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) AS paid,  
        SUM(CASE WHEN status = 'unpaid' THEN amount ELSE 0 END) AS unpaid,
        SUM(CASE WHEN status = 'cancelled' THEN amount ELSE 0 END) AS cancelled";

		$commission = WDS\Models\Commission::select( $select );

		$result = $commission->result();

		return $result[0];
	}

	/**
	 * Get affiliate commission statistics.
	 *
	 * @param bool $stats The affiliate statistics.
	 * @return array Affiliate commission statistics.
	 */
	public static function aff_commissions_stats( $stats = true ) {
		global $wpdb;

		$user          = $wpdb->users;
		$user_id       = "SELECT ID FROM $wpdb->users";
		$commission_db = $wpdb->prefix . WDS_MODEL . '_commission';

		if ( $stats ) {
			$query =
			"SELECT
                SUM( CASE WHEN status IN ('paid', 'unpaid') THEN amount ELSE 0 END ) AS total,
                SUM( CASE WHEN status IN ('paid') THEN amount ELSE 0 END ) AS paid,
                SUM( CASE WHEN status IN ('unpaid') THEN amount ELSE 0 END ) AS unpaid,
                SUM( CASE WHEN status IN ('pending') THEN amount ELSE 0 END ) AS pending
            FROM $commission_db";

			$commission = $wpdb->get_row( $query ); // phpcs:ignore

			$result = array(
				'total'   => $commission->total,
				'paid'    => $commission->paid,
				'unpaid'  => $commission->unpaid,
				'pending' => $commission->pending,
			);

			return $result;
		} else {
			$query =
			"SELECT SQL_CALC_FOUND_ROWS
                {$user}.ID as user_id,
                {$user}.user_email as email,
                SUM( CASE WHEN status IN ('paid', 'unpaid') THEN amount ELSE 0 END ) AS total,
                SUM( CASE WHEN status IN ('paid') THEN amount ELSE 0 END ) AS paid,
                SUM( CASE WHEN status IN ('unpaid') THEN amount ELSE 0 END  ) AS unpaid,
                SUM( CASE WHEN status IN ('pending') THEN amount ELSE 0 END ) AS pending,
                COUNT( CASE WHEN status IN ('unpaid', 'paid') THEN 1 ELSE NULL END ) AS sales,
                COUNT( CASE WHEN status IN ('pending', 'unpaid', 'paid', 'cancelled', 'refunded') THEN 1 ELSE NULL END ) AS leads
            FROM $user
            LEFT JOIN $commission_db
                ON {$user}.ID = {$commission_db}.user_id
            WHERE {$user}.ID IN($user_id)
            GROUP BY {$user}.ID";

			$results = $wpdb->get_results( $query ); // phpcs:ignore

			foreach ( $results as $item ) {
				$items[] = $item;
			}

			return $items;
		}
	}

	/**
	 * Get affiliate payouts statistics.
	 *
	 * @param bool $wd The withdraw statistics.
	 * @return array Affiliate payouts statistics.
	 */
	public static function affiliate_payout_stats( $wd = true ) {
		global $wpdb;

		$user          = $wpdb->users;
		$commission_db = $wpdb->prefix . WDS_MODEL . '_commission';
		$withdrawal_db = $wpdb->prefix . WDS_MODEL . '_commission_withdrawal';

		$items = array();

		if ( ! $wd ) {
			$query =
			"SELECT SQL_CALC_FOUND_ROWS
                {$user}.ID as user_id,
                {$user}.user_email as email,
                SUM(amount) as unpaid
            FROM $commission_db
            LEFT JOIN $user
                ON {$user}.ID = {$commission_db}.user_id
            WHERE {$commission_db}.status = 'unpaid'
            GROUP BY {$commission_db}.user_id
            ORDER BY unpaid DESC";

			$result = $wpdb->get_results( $query ); // phpcs:ignore
			foreach ( $result as $item ) {
				$items[] = $item;
			}
		} else {
			$query =
			"SELECT SQL_CALC_FOUND_ROWS
                {$withdrawal_db}.*,
                {$user}.user_email as email
            FROM $withdrawal_db
            LEFT JOIN $user
                ON {$user}.ID = {$withdrawal_db}.user_id
            ORDER BY {$withdrawal_db}.ID DESC";

			$result = $wpdb->get_results( $query ); // phpcs:ignore
			foreach ( $result as $item ) {
				$items[] = $item;
			}
		}

		return $items;
	}
}
