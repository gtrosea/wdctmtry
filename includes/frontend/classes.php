<?php
/**
 * WeddingSaas Frontend.
 *
 * Handles the frontend for the WeddingSaas plugin.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Frontend
 */

namespace WDS;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Frontend Class.
 */
class Frontend {

	/**
	 * @var string
	 */
	private $component = null;

	/**
	 * Constructor.
	 *
	 * Initializes the frontend class by adding actions and filters,
	 */
	public function __construct() {
		if ( ! wds_is_admin() && ! wds_is_editor() ) {
			show_admin_bar( false );
		}

		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
		add_action( 'init', array( $this, 'add_endpoint' ) );

		add_action( 'parse_request', array( $this, 'handle_action' ) );
		add_action( 'wp', array( $this, 'handle_load' ) );
		add_action( 'template_redirect', array( $this, 'handle_page' ), 999 );

		add_action( 'wds_head', array( $this, 'custome_title' ), 1 );
		add_action( 'wds_head', array( $this, 'custome_head' ) );
		add_action( 'wds_footer', array( $this, 'custome_footer' ) );

		add_action( 'login_form_login', array( $this, 'redirect_login' ) );
		add_action( 'login_form_lostpassword', array( $this, 'redirect_lost' ) );
		add_action( 'login_form_rp', array( $this, 'redirect_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_reset' ) );
		add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );
	}

	/**
	 * Add query vars.
	 *
	 * Adds custom query variables used in frontend URLs.
	 *
	 * @param array $vars List of existing query vars.
	 * @return array Modified list of query vars.
	 */
	public function add_query_vars( $vars ) {
		$vars[] = '__wds_page';
		$vars[] = '__user_id';
		$vars[] = '__product';
		$vars[] = '__invoice';
		$vars[] = '__order';

		return $vars;
	}

	/**
	 * Add custom rewrite endpoints.
	 *
	 * Adds rewrite rules based on frontend data and flushes rewrite rules.
	 */
	public function add_endpoint() {
		$data = wds_frontend_data();
		foreach ( $data as $key => $x ) {
			add_rewrite_rule( $x['regex'], $x['query'], 'top' );
		}

		flush_rewrite_rules( false );
	}

	/**
	 * Get the component based on the current page.
	 *
	 * Determines the current frontend component to be loaded.
	 *
	 * @return \WDS\Frontend\Main|false The frontend component or false if invalid.
	 */
	private function component() {
		if ( is_null( $this->component ) ) {
			$page            = wds_is_page();
			$this->component = $page ? new Frontend\Main( wds_sanitize_text_field( $page ) ) : false;
		}

		return $this->component;
	}

	/**
	 * Handle frontend actions.
	 *
	 * Processes actions based on the current frontend component and
	 * redirects users if necessary (e.g., unauthenticated access).
	 */
	public function handle_action() {
		if ( ! wds_is_page() || empty( wds_is_page() ) ) {
			return;
		}

		$component = $this->component();
		if ( ! $component ) {
			return;
		}

		$target = $component->get_target();

		if ( ! is_user_logged_in() && ( 'user' == $target || 'theme' == $target ) ) {
			wds_redirect( wds_url( 'login', false, wds_sanitize_text_field( wds_current_slug_query() ) ) );
		} elseif ( is_user_logged_in() && 'auth' == $target ) {
			$url = wds_sanitize_text_field( wds_option( 'login_redirect' ), wds_url( 'overview' ) );
			wds_redirect( $url );
		}

		if ( ! in_array( $this->component()->get_id(), array( 'verify', 'pay' ) ) ) {
			if ( ! wds_is_admin() && is_user_logged_in() && wds_option( 'account_activation' ) ) {
				$is_verified = wds_is_account_verified( get_current_user_id() );
				if ( ! $is_verified ) {
					wds_redirect( wds_url( 'verify' ) );
				}
			}
		}

		$request = new Request();
		$component->process_action( $request );
		do_action( 'wds_frontend_' . $component->get_id() . '_action', $request, $component );
	}

	/**
	 * Handle page load.
	 *
	 * Loads the frontend component and sets a cookie for user session.
	 */
	public function handle_load() {
		global $post;

		if ( ! wds_is_page() || empty( wds_is_page() ) ) {
			return;
		}

		$component = $this->component();
		if ( ! $component ) {
			return;
		}

		$post = null;

		// if ( wds_is_page() ) {
		// show_admin_bar( false );
		// }

		do_action( 'wds_frontend_' . $component->get_id() . '_load', $component );

		$args = array(
			'ip'   => \WDS_User_Info::get_ip(),
			'time' => strtotime( 'now' ),
		);

		$nonce = wds_encrypt_decrypt( wp_json_encode( $args ) );

		setcookie( 'wp_wds', $nonce, 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
	}

	/**
	 * Handle page rendering.
	 *
	 * Loads and renders the appropriate template for the current frontend component.
	 */
	public function handle_page() {
		$component = $this->component();
		if ( ! $component ) {
			return;
		}

		$template = $component->get_template();

		if ( $template ) {
			$template = wds_get_template( $template );
			if ( $template && file_exists( $template ) ) {
				$target = $this->component()->get_target();
				wds_load_layout( $target, $template );
				exit;
			}

			wp_die( esc_html__( 'Tidak dapat memuat template.', 'weddingsaas' ) );
		}
	}

	/**
	 * Retrieves the list of pages that require specific table-related styles and scripts.
	 */
	private function table_page() {
		$data = array(
			'admin/affiliate',
			'admin/affiliate-payouts',
			'admin/users',
			'account/transactions',
			'dashboard/invitation',
			'dashboard/invitation/rsvp',
			'dashboard/client',
			'rsvp',
			'public/rsvp',
		);

		return $data;
	}

	/**
	 * Outputs the custom title and favicon for the current page.
	 */
	public function custome_title() {
		if ( ! wds_data( 'wp_page' ) ) {
			$title = $this->component()->get_title();
			$title = sprintf( '%s | %s', $title, get_bloginfo( 'name' ) );
			echo '<title>' . esc_html( $title ) . '</title>' . PHP_EOL;
		} else {
			echo '<title>' . esc_html( get_the_title() ) . '</title>' . PHP_EOL;
		}

		$favicon = wds_option( 'favicon' );
		if ( wds_data( 'reseller_id' ) && function_exists( 'wds_replica_get_favicon' ) ) {
			$value   = wds_replica_get_favicon( wds_data( 'reseller_id' ) );
			$favicon = ! empty( $value ) ? $value : $favicon;
		}
		if ( $favicon ) {
			echo '<link rel="canonical" href=""/>' . PHP_EOL;
			echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '"/>' . PHP_EOL;
		}
	}

	// phpcs:disable

	/**
	 * Outputs custom elements in the <head> section of the page.
	 */
	public function custome_head() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . PHP_EOL;
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . PHP_EOL;
		echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />' . PHP_EOL;
		if ( in_array( wds_is_page(), $this->table_page() ) ) {
			echo '<link rel="stylesheet" type="text/css" href="' . WDS_URL . 'assets/plugins/custom/datatables/datatables.bundle.css?v=' . WDS_VERSION . '" />' . PHP_EOL;
		}
		echo '<link rel="stylesheet" type="text/css" href="' . WDS_URL . 'assets/plugins/global/plugins.bundle.css?v=' . WDS_VERSION . '" />' . PHP_EOL;
		echo '<link rel="stylesheet" type="text/css" href="' . WDS_URL . 'assets/css/style.bundle.css?v=' . WDS_VERSION . '" />' . PHP_EOL;

		$mode = wds_option( 'default_mode' );
		$mode = ! empty( $mode ) ? $mode : 'light';
		echo '<script type="text/javascript">var defaultThemeMode = "' . $mode . '"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>' . PHP_EOL;
		echo '<script type="text/javascript">' . PHP_EOL;
		echo 'var WDS = ' . wp_json_encode( wds_frontend_js_variables() ) . PHP_EOL;
		echo '</script>' . PHP_EOL;
		if ( wds_option( 'custome_color' ) ) {
			wds_frontend_custome_color();
		}

        echo wds_option( 'header_scripts' );
	}

	/**
	 * Outputs custom scripts in the footer section of the page.
	 */
	public function custome_footer() {
		$page = wds_is_page();

		echo '<script src="' . WDS_URL . 'assets/plugins/global/plugins.bundle.js"></script>' . PHP_EOL;
		// echo '<script src="' . WDS_URL . 'assets/js/scripts.bundle.js"></script>' . PHP_EOL;
		echo '<script src="https://assets.weddingsaas.id/js/scripts.bundle.js"></script>' . PHP_EOL;
		
		if ( in_array( $page, $this->table_page() ) ) {
			echo '<script src="' . WDS_URL . 'assets/plugins/custom/datatables/datatables.bundle.js"></script>' . PHP_EOL;
		}
		echo '<script src="' . wds_assets( 'js/wds.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
		if ( strpos( $page, 'admin' ) === 0 ) {
			echo '<script src="' . wds_assets( 'js/wds-admin.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
		} elseif ( strpos( $page, 'auth' ) === 0 ) {
			echo '<script src="' . wds_assets( 'js/wds-auth.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
		} elseif ( strpos( $page, 'account' ) === 0 ) {
			echo '<script src="' . wds_assets( 'js/wds-account.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
		} elseif ( strpos( $page, 'dashboard' ) === 0 ) {
			echo '<script src="' . wds_assets( 'js/wds-dashboard.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
			if ( 'dashboard/invitation/edit' == $page ) {
				echo '<script src="' . wds_assets( 'js/shortcode.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
			}
		} elseif ( strpos( $page, 'public' ) === 0 || in_array( $page, array( 'share', 'share/client' ) ) ) {
			echo '<script src="' . wds_assets( 'js/wds-public.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
		} else {
			echo '<script src="' . wds_assets( 'js/wds-general.js' ) . '?v=' . WDS_VERSION . '"></script>' . PHP_EOL;
		}

        echo wds_option( 'footer_scripts' );
	}

	// phpcs:enable

	/**
	 * Redirects users based on their capabilities when accessing login.
	 */
	private function check_login() {
		if ( is_user_logged_in() ) {
			if ( wds_is_admin() ) {
				wds_redirect( admin_url() );
			} else {
				wds_redirect( wds_url( 'overview' ) );
			}
		}
	}

	/**
	 * Handles user login redirection.
	 */
	public function redirect_login() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			$this->check_login();

			$login_url = wds_url( 'login' );
			if ( isset( $_REQUEST['redirect'] ) && ! empty( $_REQUEST['redirect'] ) ) {
				$login_url = add_query_arg( 'redirect', wds_sanitize_text_field( $_REQUEST['redirect'] ), $login_url );
			}

			wds_redirect( $login_url );
		}
	}

	/**
	 * Handles redirection for lost password requests.
	 */
	public function redirect_lost() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			$this->check_login();

			wds_redirect( wds_url( 'lostpass' ) );
		}
	}

	/**
	 * Redirects default WordPress reset password.
	 */
	public function redirect_reset() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			$this->check_login();

			wds_redirect( wds_url( 'resetpass' ) );
		}
	}

	/**
	 * Modifies the password reset email message.
	 *
	 * @param string $message    The original message.
	 * @param string $key       The password reset key.
	 * @param string $user_login The user's login name.
	 * @param object $user_data  The user data object.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
		$query_args = array(
			'action' => 'rp',
			'key'    => $key,
			'login'  => $user_login,
		);

		$msg  = wds_lang( 'auth_rp_email_1' ) . "\r\n\r\n";
		$msg .= wds_lang( 'auth_rp_email_2' ) . ' ' . $user_login . '.' . "\r\n\r\n";
		$msg .= wds_lang( 'auth_rp_email_3' ) . "\r\n\r\n";
		$msg .= wds_lang( 'auth_rp_email_4' ) . "\r\n\r\n";
		$msg .= add_query_arg( $query_args, wds_url( 'resetpass' ) ) . "\r\n\r\n";
		$msg .= wds_lang( 'auth_rp_email_5' ) . "\r\n";

		return $msg;
	}
}

new Frontend();
