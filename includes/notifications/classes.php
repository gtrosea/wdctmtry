<?php
/**
 * WeddingSaas Notifications.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Notifications
 */

namespace WDS\Notifications;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 *
 * Responsible for initializing notification targets and building content for email and WhatsApp notifications.
 */
class Main extends \WDS\Abstracts\Notification {

	/**
	 * Constructor for the Main class.
	 *
	 * @param string|bool $target The target identifier for the notification. Default is false.
	 */
	public function __construct( $target = false ) {
		if ( ! $target || ! empty( $target ) ) {
			$valid_targets = $this->get_valid_targets();
			if ( in_array( $target, $valid_targets, true ) ) {
				$this->target = $target;
			} else {
				wp_die( esc_html__( 'Invalid notification target.', 'wds-notrans' ) );
			}
		}
	}

	/**
	 * Get the list of valid notification targets.
	 *
	 * @return array List of valid targets for notifications.
	 */
	public function get_valid_targets() {
		$target = array(
			// ADMIN
			'admin_invoice_completed',
			'admin_domain_pending',
			// AFFILIATE
			'affiliate_commission_paid',
			'affiliate_new_sales',
			// CLIENT
			'client_register',
			// EXPIRED
			'expired',
			'expired_reminder1',
			'expired_reminder2',
			'expired_reminder3',
			// INVOICE
			'invoice_place_order',
			'invoice_reminder1',
			'invoice_reminder2',
			'invoice_reminder3',
			'invoice_cancelled',
			'invoice_completed',
			// RESELLER
			'reseller_client_register',
			'reseller_domain_pending',
			'reseller_domain_active',
			// RSVP
			'rsvp',
			// USER
			'user_activation',
			'user_register',
			'user_upgrade',
		);

		return apply_filters( 'wds_notification_target', $target );
	}

	/**
	 * Builds the email content including subject, body, and WhatsApp message.
	 *
	 * @param array $args The arguments used for building the content with shortcodes.
	 * @return $this The current instance for method chaining.
	 */
	public function content( $args ) {
		$this->build_subject( $args );
		$this->build_body( $args );
		$this->build_message( $args );

		return $this;
	}
}
