<?php

namespace WDS\Engine\Tools;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Notifications Class.
 *
 * @since 2.0.0
 */
class Notifications {

	/**
	 * Singleton instance of Notifications class.
	 *
	 * @var Notifications|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the Notifications class.
	 *
	 * @return Notifications Singleton instance.
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
		add_action( 'wds_entry_content', array( $this, 'init_alert' ) );
		add_action( 'wds_footer', array( $this, 'init_popup' ), 999 );
	}

	/**
	 * Display alert notification.
	 */
	public function init_alert() {
		$data = wds_engine( 'alert' );
		if ( ! empty( $data ) ) {
			echo '<style>.alert-ct>*:last-child {margin-bottom: 0 !important;}</style>';
			foreach ( $data as $x ) {
				$title   = wds_sanitize_data_field( $x, 'title' );
				$style   = wds_sanitize_data_field( $x, 'style', 'primary' );
				$message = wds_sanitize_data_field( $x, 'message', '', false );
				$pages   = wds_sanitize_data_field( $x, 'pages' );
				$group   = wds_sanitize_data_field( $x, 'group', array() );
				$product = wds_sanitize_data_field( $x, 'product', array() );

				if ( empty( $message ) || empty( $pages ) ) {
					continue;
				}

				if ( wds_check_array( $pages, true ) && in_array( wds_is_page(), $pages ) ) {
					if ( empty( $group ) || in_array( wds_user_group(), $group ) ) {
						if ( empty( $product ) || in_array( wds_user_membership(), $product ) ) {
							include wds_get_template( 'partials/alert.php' );
						}
					}
				}
			}
		}
	}

	/**
	 * Display popup notification.
	 */
	public function init_popup() {
		$data = wds_engine( 'popup' );
		if ( ! empty( $data ) ) {
			foreach ( $data as $x ) {
				$title    = wds_sanitize_data_field( $x, 'title' );
				$delay    = wds_sanitize_data_field( $x, 'delay', '0' );
				$interval = wds_sanitize_data_field( $x, 'interval', '0' );
				$content  = wds_sanitize_data_field( $x, 'content', '', false );
				$pages    = wds_sanitize_data_field( $x, 'pages' );
				$restrict = wds_sanitize_data_field( $x, 'restrict', array() );

				// wds_log( $interval );

				if ( empty( $title ) || empty( $content ) || empty( $pages ) ) {
					continue;
				}

				$cookie = 'wds_popup_' . sanitize_title( $title );

				if ( wds_check_array( $pages, true ) && in_array( wds_is_page(), $pages ) ) {
					if ( empty( $restrict ) || in_array( wds_user_group(), $restrict ) ) {
						if ( ! isset( $_COOKIE[ $cookie ] ) ) {
							include wds_get_template( 'partials/popup.php' );
						}
					}
				}
			}
		}
	}
}

Notifications::instance();
