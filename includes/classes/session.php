<?php
/**
 * WeddingSaas Session.
 *
 * This is a wrapper class for WP_Session / PHP $_SESSION and handles the storage of sessions.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Session Class.
 */
class WDS_Session {

	/**
	 * Holds our session data.
	 *
	 * @var array
	 */
	private $session;

	/**
	 * Whether to use PHP $_SESSION or WP_Session.
	 *
	 * @var bool
	 */
	private $use_php_sessions = false;

	/**
	 * Session index prefix.
	 *
	 * @var string
	 */
	private $prefix = '';

	/**
	 * Constructor.
	 *
	 * Defines our WP_Session constants, includes the necessary libraries and
	 * retrieves the WP Session instance.
	 */
	public function __construct() {
		$this->use_php_sessions = $this->use_php_sessions();

		if ( $this->use_php_sessions ) {
			add_action( 'init', array( $this, 'maybe_start_session' ), -2 );
		} else {
			if ( ! $this->should_start_session() ) {
				return;
			}

			// Use WP_Session (default)
			if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
				define( 'WP_SESSION_COOKIE', 'wds_wp_session' );
			}

			if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
				require_once WDS_INCLUDES . 'core/libraries/class-recursive-arrayaccess.php';
			}

			if ( ! class_exists( 'WP_Session' ) ) {
				require_once WDS_INCLUDES . 'core/libraries/class-wp-session.php';
				require_once WDS_INCLUDES . 'core/libraries/wp-session.php';
			}

			add_filter( 'wp_session_expiration_variant', array( $this, 'set_expiration_variant_time' ), 99999 );
			add_filter( 'wp_session_expiration', array( $this, 'set_expiration_time' ), 99999 );
		}

		// Based off our session handling, we need to use different hooks and priorities.
		if ( empty( $this->session ) && ! $this->use_php_sessions ) {
			$hook     = 'plugins_loaded';
			$priority = 10;
		} else {
			$hook     = 'init';
			$priority = -1;
		}

		add_action( $hook, array( $this, 'init' ), $priority );
	}

	/**
	 * Setup the WP_Session instance.
	 */
	public function init() {
		if ( $this->use_php_sessions ) {
			$key           = 'wds' . $this->prefix;
			$this->session = isset( $_SESSION[ $key ] ) && is_array( $_SESSION[ $key ] ) ? $_SESSION[ $key ] : array();
		} else {
			$this->session = WP_Session::get_instance();
		}

		return $this->session;
	}

	/**
	 * Retrieve session ID.
	 *
	 * @return string Session ID
	 */
	public function get_id() {
		return $this->session->session_id;
	}

	/**
	 * Retrieve a session variable.
	 *
	 * @param string $key Session key.
	 * @return mixed Session variable.
	 */
	public function get( $key ) {
		$key    = sanitize_key( $key );
		$return = false;

		if ( isset( $this->session[ $key ] ) && ! empty( $this->session[ $key ] ) ) {
			preg_match( '/[oO]\s*:\s*\d+\s*:\s*"\s*(?!(?i)(stdClass))/', $this->session[ $key ], $matches );

			if ( ! empty( $matches ) ) {
				$this->set( $key, null );
				return false;
			}

			if ( is_numeric( $this->session[ $key ] ) ) {
				$return = $this->session[ $key ];
			} else {
				$maybe_json = json_decode( $this->session[ $key ] );

				// Since json_last_error is PHP 5.3+, we have to rely on a `null` value for failing to parse JSON.
				if ( is_null( $maybe_json ) ) {
					$is_serialized = is_serialized( $this->session[ $key ] );
					if ( $is_serialized ) {
						$value = @unserialize( $this->session[ $key ] ); // phpcs:ignore
						$this->set( $key, (array) $value );
						$return = $value;
					} else {
						$return = $this->session[ $key ];
					}
				} else {
					$return = json_decode( $this->session[ $key ], true );
				}
			}
		}

		return $return;
	}

	/**
	 * Set a session variable.
	 *
	 * @param string           $key Session key.
	 * @param int|string|array $value Session variable.
	 * @return mixed Session variable.
	 */
	public function set( $key, $value ) {
		$key = sanitize_key( $key );

		if ( is_array( $value ) ) {
			$this->session[ $key ] = wp_json_encode( $value );
		} else {
			$this->session[ $key ] = esc_attr( $value );
		}

		if ( $this->use_php_sessions ) {
			$_SESSION[ 'wds' . $this->prefix ] = $this->session;
		}

		return $this->session[ $key ];
	}

	/**
	 * Delete a session variable.
	 *
	 * @param string $key Session key.
	 * @return bool True if the session variable was deleted, false otherwise.
	 */
	public function delete( $key ) {
		$key = sanitize_key( $key );

		if ( isset( $this->session[ $key ] ) ) {
			unset( $this->session[ $key ] );

			if ( $this->use_php_sessions ) {
				$_SESSION[ 'wds' . $this->prefix ] = $this->session;
			}

			return true;
		}

		return false;
	}

	/**
	 * Force the cookie expiration variant time to 23 hours.
	 *
	 * @param int $exp Default expiration (1 hour).
	 * @return int Cookie expiration variant time.
	 */
	public function set_expiration_variant_time( $exp = 1 ) {
		return HOUR_IN_SECONDS * 23;
	}

	/**
	 * Force the cookie expiration time to 24 hours.
	 *
	 * @param int $exp Default expiration (1 hour).
	 * @return int Cookie expiration time.
	 */
	public function set_expiration_time( $exp = 1 ) {
		return HOUR_IN_SECONDS * 24;
	}

	/**
	 * Starts a new session if one hasn't started yet.
	 *
	 * Checks to see if the server supports PHP sessions
	 * or if the WDS_USE_PHP_SESSIONS constant is defined.
	 *
	 * @return bool $ret True if we are using PHP sessions, false otherwise.
	 */
	public function use_php_sessions() {
		// Set default return value to false.
		$ret = false;

		// If the database variable is already set, no need to run autodetection.
		$wds_use_php_sessions = (bool) get_option( 'wds_use_php_sessions' );

		if ( ! $wds_use_php_sessions ) {
			// Attempt to detect if the server supports PHP sessions
			if ( function_exists( 'session_start' ) ) {
				$this->set( 'wds_use_php_sessions', 1 );

				if ( $this->get( 'wds_use_php_sessions' ) ) {
					$ret = true;

					// Set the database option
					update_option( 'wds_use_php_sessions', true );
				}
			}
		} else {
			$ret = $wds_use_php_sessions;
		}

		// Enable or disable PHP Sessions based on the WDS_USE_PHP_SESSIONS constant.
		if ( defined( 'WDS_USE_PHP_SESSIONS' ) && WDS_USE_PHP_SESSIONS ) {
			$ret = true;
		} elseif ( defined( 'WDS_USE_PHP_SESSIONS' ) && ! WDS_USE_PHP_SESSIONS ) {
			$ret = false;
		}

		// Filter & return.
		return (bool) apply_filters( 'wds_use_php_sessions', $ret );
	}

	/**
	 * Determines if we should start sessions.
	 *
	 * @return bool True if sessions should start, false otherwise.
	 */
	public function should_start_session() {
		// Set default return value to true.
		$start_session = true;

		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$blacklist = $this->get_blacklist();
			$uri       = ltrim( $_SERVER['REQUEST_URI'], '/' );
			$uri       = untrailingslashit( $uri );

			if ( in_array( $uri, $blacklist, true ) ) {
				$start_session = false;
			}

			if ( false !== strpos( $uri, 'feed=' ) ) {
				$start_session = false;
			}

			// We do not want to start sessions in the admin unless we're processing an ajax request.
			if ( is_admin() && false === strpos( $uri, 'wp-admin/admin-ajax.php' ) ) {
				$start_session = false;
			}

			// Starting sessions while saving the file editor can break the save process, so don't start.
			if ( false !== strpos( $uri, 'wp_scrape_key' ) ) {
				$start_session = false;
			}
		}

		// Filter & return.
		return (bool) apply_filters( 'wds_start_session', $start_session );
	}

	/**
	 * Retrieve the URI blacklist.
	 *
	 * These are the URIs where we never start sessions.
	 *
	 * @return array URI blacklist.
	 */
	public function get_blacklist() {
		$blacklist = apply_filters(
			'wds_session_start_uri_blacklist',
			array(
				'feed',
				'feed/rss',
				'feed/rss2',
				'feed/rdf',
				'feed/atom',
				'comments/feed',
			)
		);

		// Look to see if WordPress is in a sub folder or this is a network site that uses sub folders
		$folder = str_replace( network_home_url(), '', get_site_url() );

		if ( ! empty( $folder ) ) {
			foreach ( $blacklist as $path ) {
				$blacklist[] = $folder . '/' . $path;
			}
		}

		return $blacklist;
	}

	/**
	 * Starts a new session if one hasn't started yet.
	 */
	public function maybe_start_session() {
		// Bail if should not start session.
		if ( ! $this->should_start_session() ) {
			return;
		}

		// Bail if headers already sent.
		if ( headers_sent() ) {
			return;
		}

		// Start if old version of PHP & no session ID exists.
		if ( version_compare( PHP_VERSION, '5.4', '<' ) && ! session_id() ) {
			session_start();

			// Start if modern PHP and session-status is not active.
		} elseif ( defined( 'PHP_SESSION_ACTIVE' ) && ( session_status() !== PHP_SESSION_ACTIVE ) ) {
			session_start();
		}
	}
}
