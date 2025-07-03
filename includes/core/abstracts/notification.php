<?php
/**
 * WeddingSaas Notifications.
 *
 * Abstract base class for email & WhatsApp notifications.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Core/Abstracts
 */

namespace WDS\Abstracts;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Notification Class.
 */
abstract class Notification {

	/**
	 * @var string $target The target identifier for the notification.
	 */
	public $target;

	/**
	 * @var array $is_to The recipient(s) of the email.
	 */
	protected $is_to;

	/**
	 * @var string $is_subject The subject of the email.
	 */
	protected $is_subject;

	/**
	 * @var string $is_body The body content of the email.
	 */
	protected $is_body;

	/**
	 * @var string $is_phone The phone number for WhatsApp notifications.
	 */
	protected $is_phone;

	/**
	 * @var string $is_message The message content for WhatsApp notifications.
	 */
	protected $is_message;

	/**
	 * Filter prefix for notification.
	 *
	 * @return string The filter prefix.
	 */
	protected function filter() {
		return 'wds_notification_';
	}

	/**
	 * Set the recipient(s) of the email.
	 *
	 * @param string|array $to The recipient(s) of the email.
	 * @return $this
	 */
	public function to( $to ) {
		if ( is_array( $to ) ) {
			$this->is_to = $to;
		} else {
			$this->is_to = array( $to );
		}

		return $this;
	}

	/**
	 * Build the email subject.
	 *
	 * @param array $args The arguments for replacing shortcodes.
	 * @return void
	 */
	protected function build_subject( $args = array() ) {
		/* filter argument */
		$args = apply_filters( $this->filter() . $this->target . '_shortcodes_args', $args );

		/* get email subject */
		$subject = wds_option( $this->target . '_subject' );

		/* replace shortcode on subject */
		$subject = wds_email_replace_shortcode( $subject, $args );

		/* filter subject */
		$this->is_subject = apply_filters( $this->filter() . $this->target . '_subject', $subject );
	}

	/**
	 * Build the email body.
	 *
	 * @param array $args The arguments for replacing shortcodes.
	 * @return void
	 */
	protected function build_body( $args ) {
		/* filter argument */
		$args = apply_filters( $this->filter() . $this->target . '_shortcodes_args', $args );

		/* get body content */
		$body = wds_option( $this->target . '_body' );

		/* replace shortcode from body */
		$body = wds_email_replace_shortcode( $body, $args );

		/* filter body */
		$body = apply_filters( $this->filter() . $this->target . '_body', $body );

		/* apply shortcode from wp */
		$body = wp_kses_post( wpautop( do_shortcode( $body ) ) );

		$this->is_body = wds_email_layout( $body );
	}

	/**
	 * Send the email.
	 *
	 * @return true|WP_Error True on success, WP_Error on failure.
	 */
	public function send_email() {
		if ( count( $this->is_to ) === 0 ) {
			return new \WP_Error( 'error', __( 'Anda harus menetapkan setidaknya 1 penerima.', 'weddingsaas' ) );
		}

		if ( empty( $this->is_subject ) ) {
			return new \WP_Error( 'error', __( 'Subjek harus didefinisikan.', 'weddingsaas' ) );
		}

		if ( empty( $this->is_body ) ) {
			return new \WP_Error( 'error', __( 'Isi harus didefinisikan.', 'weddingsaas' ) );
		}

		$f_names = wds_option( 'email_from_name' );
		$f_email = wds_option( 'email_from_email' );
		$t_names = wds_option( 'email_reply_to_name' );
		$t_email = wds_option( 'email_reply_to_email' );

		$headers = array( sprintf( 'From: %s <%s>', $f_names, $f_email ) );

		if ( $t_names && $t_email ) {
			$headers[] = sprintf( 'Reply-To: %s <%s>', $t_names, $t_email );
		}

		add_filter(
			'wp_mail_content_type',
			function ( $content_type ) {
				return 'text/html';
			}
		);

		$result = wp_mail( $this->is_to, $this->is_subject, $this->is_body, $headers, array() );

		// remove filter after send email
		remove_filter( 'wp_mail_content_type', '__return_html' );
	}

	/**
	 * Set the phone number for WhatsApp notifications.
	 *
	 * @param string $phone The phone number.
	 * @return $this
	 */
	public function phone( $phone ) {
		$this->is_phone = $phone;

		return $this;
	}

	/**
	 * Build the WhatsApp message.
	 *
	 * @param array $args The arguments for replacing shortcodes.
	 * @return void
	 */
	public function build_message( $args ) {
		/* filter argument */
		$args = apply_filters( $this->filter() . $this->target . '_shortcodes_args', $args );

		/* get body content */
		$body = wds_option( $this->target . '_whatsapp' );

		/* replace shortcode from body */
		$this->is_message = wds_email_replace_shortcode( $body, $args );
	}

	/**
	 * Send the WhatsApp message.
	 *
	 * @return true|WP_Error True on success, WP_Error on failure.
	 */
	public function send_wa() {
		if ( empty( $this->is_phone ) ) {
			return new \WP_Error( 'error', __( 'Anda harus menetapkan setidaknya 1 penerima.', 'weddingsaas' ) );
		}

		if ( empty( $this->is_message ) ) {
			return new \WP_Error( 'error', __( 'Isi harus didefinisikan.', 'weddingsaas' ) );
		}

		return \WDS\Notifications\WhatsApp::send( $this->is_phone, $this->is_message );
	}
}
