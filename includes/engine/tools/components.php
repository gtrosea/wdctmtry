<?php

namespace WDS\Engine\Tools;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Components Class.
 *
 * @since 2.0.0
 */
class Components {

	/**
	 * Singleton instance of Component class.
	 *
	 * @var Component|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the Component class.
	 *
	 * @return Component Singleton instance.
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
		// Auto Delete Drafts Post - includes\classes\cron.php

		// Allowed Domain Email - includes\classes\checkout.php

		// Limit Post Revision
		if ( ! empty( wds_engine( 'limit_post_revision' ) ) ) {
			if ( defined( 'WP_POST_REVISIONS' ) ) {
				add_action( 'admin_notices', array( $this, 'notice_post_revisions' ) );
			} else {
				define( 'WP_POST_REVISIONS', wds_engine( 'limit_post_revision' ) );
			}
		}

		// Autosave Interval
		if ( ! empty( wds_engine( 'autosave_interval' ) ) ) {
			if ( defined( 'AUTOSAVE_INTERVAL' ) ) {
				add_action( 'admin_notices', array( $this, 'notice_autosave_interval' ) );
			} else {
				define( 'AUTOSAVE_INTERVAL', wds_engine( 'autosave_interval' ) );
			}
		}

		// Disable XMLRPC
		if ( wds_engine( 'disable_xmlrpc' ) ) {
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'pings_open', '__return_false', 9999 );
			add_filter( 'pre_update_option_enable_xmlrpc', '__return_false' );
			add_filter( 'pre_option_enable_xmlrpc', '__return_zero' );
			add_filter( 'wp_headers', array( $this, 'remove_x_pingback' ) );
			add_action( 'init', array( $this, 'intercept_xmlrpc_header' ) );
			add_filter( 'wp_xmlrpc_server_class', array( $this, 'disable_xmlrpc' ) );
		}

		// Disable RSS Feed
		if ( wds_engine( 'disable_rss_feeds' ) ) {
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
			remove_action( 'do_feed_rdf', 'do_feed_rdf', 10, 0 );
			remove_action( 'do_feed_rss', 'do_feed_rss', 10, 0 );
			remove_action( 'do_feed_rss2', 'do_feed_rss2', 10, 1 );
			remove_action( 'do_feed_atom', 'do_feed_atom', 10, 1 );
			add_action( 'template_redirect', array( $this, 'disable_rss_feeds' ), 1 );
		}

		// Disable Search
		if ( wds_engine( 'disable_search' ) ) {
			// Prevent search queries.
			add_action(
				'parse_query',
				function ( $query, $error = true ) {
					if ( is_search() && ! is_admin() ) {
						$query->is_search       = false;
						$query->query_vars['s'] = false;
						$query->query['s']      = false;
						if ( true === $error ) {
							$query->is_404 = true;
						}
					}
				},
				15,
				2
			);

			// Remove the Search Widget.
			add_action(
				'widgets_init',
				function () {
					unregister_widget( 'WP_Widget_Search' );
				}
			);

			// Remove the search form.
			add_filter( 'get_search_form', '__return_empty_string', 999 );

			// Remove the core search block.
			add_action(
				'init',
				function () {
					if ( ! function_exists( 'unregister_block_type' ) || ! class_exists( 'WP_Block_Type_Registry' ) ) {
						return;
					}
					$block = 'core/search';
					if ( \WP_Block_Type_Registry::get_instance()->is_registered( $block ) ) {
						unregister_block_type( $block );
					}
				}
			);

			// Remove admin bar menu search box.
			add_action(
				'admin_bar_menu',
				function ( $wp_admin_bar ) {
					$wp_admin_bar->remove_menu( 'search' );
				},
				11
			);
		}

		// Restrict Media Library
		if ( wds_engine( 'restrict_media_library' ) ) {
			add_filter( 'ajax_query_attachments_args', array( $this, 'show_current_user_media' ) );
		}

		// Disable Author
		if ( wds_engine( 'disable_author' ) ) {
			add_action( 'template_redirect', array( $this, 'disable_author' ) );
		}
	}

	/**
	 * Notice post revisions.
	 */
	public function notice_post_revisions() {
		$message = '<strong>' . __( 'WeddingSaaS Warning', 'wds-notrans' ) . ':</strong> ' . __( 'WP_POST_REVISIONS is already enabled somewhere else on your site. We suggest only enabling this feature in one place.', 'wds-notrans' );
		wds_add_notice( $message, 'error' );
	}

	/**
	 * Notice auto save interval.
	 */
	public function notice_autosave_interval() {
		$message = '<strong>' . __( 'WeddingSaaS Warning', 'wds-notrans' ) . ':</strong> ' . __( 'AUTOSAVE_INTERVAL is already enabled somewhere else on your site. We suggest only enabling this feature in one place.', 'wds-notrans' );
		wds_add_notice( $message, 'error' );
	}

	/**
	 * Removes the X-Pingback header from HTTP headers.
	 *
	 * @param array $headers An array of HTTP headers.
	 */
	public function remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'], $headers['x-pingback'] );
		return $headers;
	}

	/**
	 * Intercepts requests to xmlrpc.php and sends a 403 Forbidden header.
	 */
	public function intercept_xmlrpc_header() {
		if ( ! isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
			return;
		}

		if ( 'xmlrpc.php' !== basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
			return;
		}

		$header = 'HTTP/1.1 403 Forbidden';
		header( $header );
		echo $header; // phpcs:ignore
		die();
	}

	/**
	 * Disables XML-RPC access by sending a 403 Forbidden response.
	 *
	 * @param mixed $data Unused parameter, could be anything.
	 */
	public function disable_xmlrpc( $data ) {
		http_response_code( 403 );
		exit( 'You don\'t have permission to access this file.' );
	}

	/**
	 * Disables RSS feeds by redirecting or displaying an error message.
	 */
	public function disable_rss_feeds() {
		if ( ! is_feed() || is_404() ) {
			return;
		}

		global $wp_rewrite;
		global $wp_query;

		if ( isset( $_GET['feed'] ) ) {
			wds_redirect( esc_url_raw( remove_query_arg( 'feed' ) ), 301 );
		}

		if ( get_query_var( 'feed' ) !== 'old' ) {
			set_query_var( 'feed', '' );
		}

		redirect_canonical();

		wp_die( sprintf( wp_kses_post( "No feed available, please visit the <a href='%s'>homepage</a>!" ), esc_url( home_url( '/' ) ) ) );
	}

	/**
	 * Show current user media uploaded.
	 *
	 * @param array $query The data query.
	 */
	public function show_current_user_media( $query ) {
		$user_id = get_current_user_id();
		if ( $user_id ) {
			$query['author'] = $user_id;
		}

		return $query;
	}

	/**
	 * Disable author archive pages by redirecting to the homepage.
	 */
	public function disable_author() {
		if ( is_author() ) {
			wds_redirect( home_url() );
		}
	}
}

Components::instance();
