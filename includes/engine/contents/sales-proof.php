<?php

namespace WDS\Engine\Contents;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * SalesProof Class.
 *
 * @since 2.0.0
 */
class SalesProof {

	/**
	 * Singleton instance of SalesProof class.
	 *
	 * @var SalesProof|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the SalesProof class.
	 *
	 * @return SalesProof Singleton instance.
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
		add_action( 'wds_footer', array( $this, 'init' ), 999 );
		add_action( 'wp_footer', array( $this, 'init_wp' ), 999 );
	}

	/**
	 * Display sales proof on checkout page.
	 */
	public function init() {
		if ( wds_engine( 'sp' ) ) {
			$image    = wds_engine( 'sp_image' );
			$text1    = wds_engine( 'sp_text1' );
			$text2    = wds_engine( 'sp_text2' );
			$position = wds_engine( 'sp_position' );
			$delay    = wds_engine( 'sp_delay' );
			$time     = wds_engine( 'sp_time' );

			if ( 'tl' == $position ) {
				$style = 'top:-100px;left:20px;';
			} elseif ( 'tr' == $position ) {
				$style = 'top:-100px;right:20px;';
			} elseif ( 'bl' == $position ) {
				$style = 'bottom:-100px;left:20px;';
			} else {
				$style = 'bottom:-100px;right:20px;';
			}

			$y = 'tl' == $position || 'tr' == $position ? 'top' : 'bottom';

			$cache          = wds_engine( 'sp_cache' );
			$array_invoices = array();

			if ( $cache ) {
				$array_invoices = get_transient( 'wds_sales_notifications' );
				$array_invoices = false !== $array_invoices ? $array_invoices : wp_cache_get( 'sales', 'wds_sales_notification' );
			} else {
				delete_transient( 'wds_sales_notifications' );
			}

			if ( empty( $array_invoices ) ) {
				$query    = 'WHERE total > 0 AND status = "completed"';
				$invoices = \WDS\Models\Invoice::query( $query )->order( 'ID', 'DESC' )->get();

				$user_ids_shown = array();
				foreach ( $invoices as $invoice ) {
					$product = '';
					foreach ( $invoice->orders() as $order ) {
						$product = wds_get_product_title( $order->product_id );
					}

					$user_id = $invoice->user_id;
					if ( ! in_array( $user_id, $user_ids_shown ) ) {
						$user_ids_shown[] = $user_id;
						$array_invoices[] = (object) array(
							'name'    => wds_user_name( $user_id ),
							'product' => $text1 . ' <span>' . $product . '</span>',
						);
					}
				}

				if ( $cache ) {
					set_transient( 'wds_sales_notifications', $array_invoices, HOUR_IN_SECONDS );
					wp_cache_set( 'sales', $array_invoices, 'wds_sales_notifications', HOUR_IN_SECONDS );
				}
			}

			if ( 'checkout' == wds_is_page() && ! empty( $array_invoices ) ) {
				include_once wds_get_template( 'partials/salesproof.php' );
			}
		}
	}

	/**
	 * Display sales proof on selected page.
	 */
	public function init_wp() {
		if ( wds_engine( 'sp' ) ) {
			$image    = wds_engine( 'sp_image' );
			$text1    = wds_engine( 'sp_text1' );
			$text2    = wds_engine( 'sp_text2' );
			$position = wds_engine( 'sp_position' );
			$delay    = wds_engine( 'sp_delay' );
			$time     = wds_engine( 'sp_time' );
			$page     = wds_engine( 'sp_page' );

			if ( 'tl' == $position ) {
				$style = 'top:-100px;left:20px;';
			} elseif ( 'tr' == $position ) {
				$style = 'top:-100px;right:20px;';
			} elseif ( 'bl' == $position ) {
				$style = 'bottom:-100px;left:20px;';
			} else {
				$style = 'bottom:-100px;right:20px;';
			}

			$y = 'tl' == $position || 'tr' == $position ? 'top' : 'bottom';

			$cache          = wds_engine( 'sp_cache' );
			$array_invoices = array();

			if ( $cache ) {
				$array_invoices = get_transient( 'wds_sales_notifications' );
				$array_invoices = false !== $array_invoices ? $array_invoices : wp_cache_get( 'sales', 'wds_sales_notification' );
			} else {
				delete_transient( 'wds_sales_notifications' );
			}

			if ( empty( $array_invoices ) ) {
				$query    = 'WHERE total > 0 AND status = "completed"';
				$invoices = \WDS\Models\Invoice::query( $query )->order( 'ID', 'DESC' )->get();

				$user_ids_shown = array();
				foreach ( $invoices as $invoice ) {
					$product = '';
					foreach ( $invoice->orders() as $order ) {
						$product = wds_get_product_title( $order->product_id );
					}

					$user_id = $invoice->user_id;
					if ( ! in_array( $user_id, $user_ids_shown ) ) {
						$user_ids_shown[] = $user_id;
						$array_invoices[] = (object) array(
							'name'    => wds_user_name( $user_id ),
							'product' => $text1 . ' <span>' . $product . '</span>',
						);
					}
				}

				if ( $cache ) {
					set_transient( 'wds_sales_notifications', $array_invoices, HOUR_IN_SECONDS );
					wp_cache_set( 'sales', $array_invoices, 'wds_sales_notifications', HOUR_IN_SECONDS );
				}
			}

			if ( wds_check_array( $page, true ) && in_array( get_the_ID(), $page ) && ! empty( $array_invoices ) ) {
				include_once wds_get_template( 'partials/salesproof.php' );
			}
		}
	}
}

SalesProof::instance();
