<?php
/**
 * WeddingSaas Cron.
 *
 * This class handles scheduled events.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Cron Class.
 */
class WDS_Cron {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
		add_action( 'wp', array( $this, 'schedule_events' ) );
		add_action( 'wds/cron/hourly', array( $this, 'invoice_cancelled' ) );
		add_action( 'wds/cron/hourly', array( $this, 'generate_expired_membership_reminder_notification' ) );
		add_action( 'wds/cron/hourly', array( $this, 'generate_unpaid_invoice_reminder_notification' ) );
		add_action( 'wds/cron/15minutes', array( $this, 'make_user_expired' ) );
		add_action( 'wds/cron/15minutes', array( $this, 'make_post_expired' ) );
		add_action( 'wds/cron/15minutes', array( $this, 'make_post_trash' ) );
	}

	/**
	 * Registers new cron schedules.
	 *
	 * @param array $schedules The schedules event.
	 */
	public function add_schedules( $schedules = array() ) {
		$schedules['every15min'] = array(
			'interval' => 900,
			'display'  => __( 'Setiap 15 Menit', 'weddingsaas' ),
		);

		return $schedules;
	}

	/**
	 * Schedules our events.
	 */
	public function schedule_events() {
		if ( ! wp_next_scheduled( 'wds/cron/15minutes' ) ) {
			wp_schedule_event( time(), 'every15min', 'wds/cron/15minutes' );
		}

		if ( ! wp_next_scheduled( 'wds/cron/hourly' ) ) {
			wp_schedule_event( time(), 'hourly', 'wds/cron/hourly' );
		}

		if ( ! wp_next_scheduled( 'wds/cron/daily' ) ) {
			wp_schedule_event( time(), 'daily', 'wds/cron/daily' );
		}

		if ( ! wp_next_scheduled( 'wds/cron/weekly' ) ) {
			wp_schedule_event( time(), 'weekly', 'wds/cron/weekly' );
		}
	}

	/**
	 * Change invoice status to cancelled after the due date has passed.
	 */
	public function invoice_cancelled() {
		$invoices = \WDS\Models\Invoice::select( 'ID' )
		->query( 'WHERE status = %s AND due_date_at IS NOT NULL AND due_date_at <= %s', 'unpaid', gmdate( 'Y-m-d H:i:s' ) )
		->get();

		if ( $invoices->found() > 0 ) {
			foreach ( $invoices as $invoice ) {
				wds_update_invoice_status( $invoice->ID, 'cancelled' );
			}
		}
	}

	/**
	 * Generate notification reminder membership expired.
	 */
	public function generate_expired_membership_reminder_notification() {
		$checked_date = get_option( 'wds_membership_expired_generate_reminder_date' );
		if ( gmdate( 'y-m-d' ) != $checked_date ) {
			update_option( 'wds_membership_expired_generate_reminder_date', gmdate( 'y-m-d' ) );
			update_option( 'wds_membership_expired_generate_reminder_paged', 1 );
		}

		$checked_paged = intval( get_option( 'wds_membership_expired_generate_reminder_paged' ) );
		if ( ! $checked_paged ) {
			$checked_paged = 1;
		}

		$days = array( 3, 2, 1 );

		foreach ( $days as $day ) {
			$is_enable = wds_option( 'expired_reminder' . $day . '_enable' );
			if ( ! $is_enable ) {
				continue;
			}

			$active_orders = WDS\Models\Order::select( 'ID' )->query(
				'WHERE status = %s AND expired_at IS NOT NULL AND DATE(DATE_ADD(expired_at, INTERVAL -%d DAY)) = %s',
				'active',
				$day,
				gmdate( 'Y-m-d' )
			)->paginate( 100, $checked_paged )->get();

			if ( $active_orders->found() > 0 ) {
				foreach ( $active_orders as $order ) {
					$data = array(
						'order_id'    => $order->ID,
						'days_before' => $day,
					);
					do_action( 'wds_reminder_membership', $data );
				}
			}
		}

		++$checked_paged;

		update_option( 'wds_membership_expired_generate_reminder_paged', $checked_paged );
	}

	/**
	 * Generate notification reminder unpaid invoice.
	 */
	public function generate_unpaid_invoice_reminder_notification() {
		$checked_date = get_option( 'wds_invoice_unpaid_generate_reminder_date' );
		if ( gmdate( 'y-m-d' ) != $checked_date ) {
			update_option( 'wds_invoice_unpaid_generate_reminder_date', gmdate( 'y-m-d' ) );
			update_option( 'wds_invoice_unpaid_generate_reminder_paged', 1 );
		}

		$checked_paged = intval( get_option( 'wds_invoice_unpaid_generate_reminder_paged' ) );
		if ( ! $checked_paged ) {
			$checked_paged = 1;
		}

		$days = array( 3, 2, 1 );

		foreach ( $days as $day ) {
			$is_enable = wds_option( 'invoice_reminder' . $day . '_enable' );
			if ( ! $is_enable ) {
				continue;
			}

			$unpaid_invoices = WDS\Models\Invoice::select( 'ID' )->query(
				'WHERE status = %s AND DATE(DATE_ADD(created_at, INTERVAL +%d DAY)) = %s',
				'unpaid',
				$day,
				gmdate( 'Y-m-d' )
			)->paginate( 100, $checked_paged )->get();

			if ( $unpaid_invoices->found() > 0 ) {
				foreach ( $unpaid_invoices as $invoice ) {
					$data = array(
						'invoice_id' => $invoice->ID,
						'days_to'    => $day,
					);

					do_action( 'wds_reminder_invoice', $data );
				}
			}
		}

		++$checked_paged;

		update_option( 'wds_invoice_unpaid_generate_reminder_paged', $checked_paged );
	}

	/**
	 * Set user to inactive when expired.
	 */
	public function make_user_expired() {
		$users = get_users();
		foreach ( $users as $user ) {
			$user_id = $user->ID;
			$period  = wds_user_active_period( $user_id, true );
			$status  = wds_user_status( $user_id );

			if ( $period && 'active' == $status ) {
				$current_timestamp = current_time( 'timestamp' );

				// Change to inactive
				if ( $current_timestamp > $period ) {
					$wds_order_id = wds_user_order_id( $user_id );
					if ( $wds_order_id ) {
						$updated = wds_update_order(
							array(
								'ID'     => $wds_order_id,
								'status' => 'inactive',
							)
						);
					}

					update_user_meta( $user_id, '_wds_user_status', 'inactive' );
					wds_delete_cache_user( $user_id );

					do_action( 'wds_user_status_changed', $user_id );
				}
			}
		}
	}

	/**
	 * Auto draft/trash when post expired.
	 */
	public function make_post_expired() {
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_wds_pep_period',
					'value'   => time(),
					'compare' => '<=',
				),
				array(
					'key'     => '_wds_pep_action',
					'value'   => array( 'draft', 'trash' ),
					'compare' => 'IN',
				),
			),
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$post_id    = $post->ID;
			$pep_period = wds_post_meta( $post_id, '_wds_pep_period' );
			$pep_action = wds_post_meta( $post_id, '_wds_pep_action' );

			if ( ! empty( $pep_period ) ) {
				if ( 'draft' === $pep_action ) {
					$delete_duration = wds_engine( 'auto_delete_draft' ); // per day
					if ( $delete_duration ) {
						$today            = current_time( 'timestamp' );
						$duration_new     = "+$delete_duration day";
						$delete_timestamp = strtotime( $duration_new, $today );

						update_post_meta( $post_id, '_wds_del_period', $delete_timestamp );
						wds_delete_cache_post( $post_id );

						wp_update_post(
							array(
								'ID'          => $post_id,
								'post_status' => 'draft',
							)
						);
					} else {
						wp_update_post(
							array(
								'ID'          => $post_id,
								'post_status' => 'draft',
							)
						);
					}
				} elseif ( 'trash' === $pep_action ) {
					wp_trash_post( $post_id );
				}
			}
		}
	}

	/**
	 * Auto trash post when auto delete activated.
	 */
	public function make_post_trash() {
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'draft',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => '_wds_del_period',
					'value'   => time(),
					'compare' => '<=',
				),
			),
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$del_period = wds_post_meta( $post->ID, '_wds_del_period' );
			if ( ! empty( $del_period ) ) {
				wp_trash_post( $post->ID );
			}
		}
	}
}

new WDS_Cron();
