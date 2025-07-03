<?php
/**
 * WeddingSaas Contents.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Contents
 */

namespace WDS\Contents;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class Main {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'restrict_content' ) );
		add_action( 'template_redirect', array( $this, 'iframe_content' ), 10 );
		add_action( 'template_redirect', array( $this, 'restrict_invitation' ) );
		add_action( 'admin_init', array( $this, 'redirect_non_admin_users' ) );
		add_action( 'template_redirect', array( $this, 'redirect_access' ) );
		add_action( 'template_include', array( $this, 'template_access' ) );
	}

	/**
	 * Restricts content access based on post meta and user login status.
	 *
	 * If the post has restricted access and the user is not logged in,
	 * redirects to the login page. If the user is logged in but does not
	 * have the required access level, redirects to a custom URL or the home page.
	 */
	public function restrict_content() {
		$post_id = get_the_ID();
		$user_id = get_current_user_id();

		// Restrict access for non-logged-in users
		if ( wds_post_meta( $post_id, '_restrict_access' ) && ! is_user_logged_in() ) {
			$current_url = home_url( $_SERVER['REQUEST_URI'] );

			// Parse URL into path and query string
			$parsed_url   = wp_parse_url( $current_url );
			$path         = isset( $parsed_url['path'] ) ? ltrim( $parsed_url['path'], '/' ) : '';
			$query_string = isset( $parsed_url['query'] ) ? $parsed_url['query'] : '';

			// Recombine path and query string
			$slug_with_query = $path;
			if ( ! empty( $query_string ) ) {
				$slug_with_query .= '?' . $query_string;
			}

			wds_redirect( wds_url( 'login', false, $slug_with_query ) );
		}

		// Restrict access for logged-in users based on user level
		if ( wds_post_meta( $post_id, '_restrict_access' ) && is_user_logged_in() ) {
			$user_level   = wds_user_group( $user_id );
			$current_page = get_post_field( 'post_name', get_queried_object_id() );

			// Get restricted post types and posts
			$restricted_post_types = wds_get_post_type();
			$restricted_posts      = array();

			foreach ( $restricted_post_types as $post_type ) {
				$args  = array(
					'post_type'      => $post_type,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				);
				$posts = get_posts( $args );
				foreach ( $posts as $post ) {
					$restrict_access = wds_post_meta( $post->ID, '_restrict_access' );
					$required_level  = wds_post_meta( $post->ID, '_required_level' );

					if ( $restrict_access && $required_level && 'all' !== $required_level && $required_level !== $user_level ) {
						$restricted_posts[] = $post->post_name;
					}
				}
			}

			if ( in_array( $current_page, $restricted_posts ) ) {
				$redirect_url = wds_post_meta( get_queried_object_id(), '_restrict_redirect' );

				if ( ! wds_is_admin() ) {
					if ( ! empty( $redirect_url ) ) {
						wds_redirect( $redirect_url );
					} else {
						wds_redirect( home_url() );
					}
				}
			}
		}
	}

	/**
	 * Displays content in an iframe if the post type is allowed.
	 *
	 * If the current post type matches allowed types, the content is loaded
	 * inside an iframe, using an external URL specified in the post meta.
	 */
	public function iframe_content() {
		$post_id            = get_the_ID();
		$allowed_post_types = wds_option( 'iframe', array() );
		$current_post_type  = get_post_type( $post_id );

		// Check if post type is allowed for iframe display
		if ( ! empty( $allowed_post_types ) ) {
			if ( in_array( $current_post_type, $allowed_post_types ) && wds_is_page() == false ) {
				$iframe     = wds_post_meta( $post_id, '_iframe' );
				$iframe_url = wds_post_meta( $post_id, '_iframe_url' );
				if ( $iframe && ! empty( $iframe_url ) ) {
					$query_string = $_SERVER['QUERY_STRING'];
					if ( ! empty( $query_string ) ) {
						$iframe_url = add_query_arg( $query_string, '', $iframe_url );
					}

					$favicon = wds_option( 'favicon' );
					if ( wds_data( 'reseller_id' ) && function_exists( 'wds_replica_get_favicon' ) ) {
						$value   = wds_replica_get_favicon( wds_data( 'reseller_id' ) );
						$favicon = ! empty( $value ) ? $value : $favicon;
					}

					// Output iframe HTML
					echo '<!DOCTYPE html><html ' . wp_kses_post( get_language_attributes() ) . '><head><meta charset="' . wp_kses_post( get_bloginfo( 'charset' ) ) . '"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
					if ( class_exists( 'RankMath' ) ) {
						do_action( 'rank_math/head' );
					} else {
						echo '<title>' . wp_kses_post( get_the_title() ) . '</title>';
					}
					if ( $favicon ) {
						echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '"/>' . PHP_EOL;
					}
					echo '<style type="text/css">.wds-iframe{position:fixed;top:0;left:0;bottom:0;right:0;width:100%;height:100%;border:none;margin:0;padding:0;overflow:hidden;z-index:999999;}</style>';
					echo '</head><body><iframe src="' . esc_url( $iframe_url ) . '" class="wds-iframe" allow="fullscreen">Your browser does not support iframes</iframe></body></html>';
					exit;
				}
			}
		}
	}

	/**
	 * Restricts access to invitation posts based on guest list.
	 *
	 * If the post is restricted, only users whose names are in the guest list
	 * can access it. Other users are redirected to a custom URL.
	 */
	public function restrict_invitation() {
		if ( is_singular( 'post' ) && wds_option( 'restrict_invitation' ) ) {
			$post_id     = get_the_ID();
			$post_author = get_post_field( 'post_author', $post_id );

			// Allow access for admins and post authors
			if ( wds_is_admin() || get_current_user_id() == $post_author ) {
				return;
			}

			$data = wds_post_meta( $post_id, '_restrict' );
			if ( empty( $data ) ) {
				return;
			}

			// Check if guest param matches any in the guest list
			$guest_param = isset( $_GET['to'] ) ? stripslashes( urldecode( $_GET['to'] ) ) : '';
			$guest_list  = isset( $data['guest'] ) ? explode( "\n", $data['guest'] ) : array();
			$guest_list  = array_map( 'trim', $guest_list );
			$guest_param = trim( $guest_param );

			if ( empty( $guest_param ) || ! in_array( $guest_param, $guest_list ) ) {
				$redirect = ! empty( wds_option( 'restrict_invitation_redirect' ) ) ? wds_option( 'restrict_invitation_redirect' ) : home_url();
				wds_redirect( $redirect );
			}
		}
	}

	/**
	 * Redirects non-admin users away from the admin dashboard.
	 *
	 * Users who do not have admin privileges are redirected to a custom URL when they try
	 * to access the admin dashboard.
	 */
	public function redirect_non_admin_users() {
		if ( ! wds_is_admin_access() && ( ! wds_doing_ajax() ) ) {
			wds_redirect( wds_url( 'overview' ) );
		}
	}

	/**
	 * Restricts content access in cpt wds_access.
	 */
	public function redirect_access() {
		global $post;

		if ( wds_is_digital() && $post && 'wds_access' == $post->post_type ) {
			if ( ! is_user_logged_in() ) {
				wds_redirect( wds_url( 'login' ) );
			}

			$post_id = get_the_ID();
			$user_id = get_current_user_id();

			$redirect = wds_post_meta( $post_id, 'redirect_access' );
			$redirect = ! empty( $redirect ) ? $redirect : home_url();

			$products = wds_post_meta( $post_id, 'restrict_product' );
			if ( ! empty( $products ) ) {
				$user_products = wds_user_meta( $user_id, '_wds_access_product' );
				if ( empty( $user_products ) ) {
					wds_redirect( $redirect, 302, false );
				}

				if ( empty( array_intersect( $user_products, $products ) ) ) {
					wds_redirect( $redirect, 302, false );
				}
			}
		}
	}

	/**
	 * Loads a custom template for cpt 'wds_access'.
	 *
	 * @param string $template The path of the current template being used.
	 * @return string The path to the custom template if conditions are met; otherwise, the original template path.
	 */
	public function template_access( $template ) {
		global $post, $wds_data;

		if ( wds_is_digital() && $post && 'wds_access' == $post->post_type ) {
			$data = get_page_template_slug( get_the_ID() );
			if ( 'wds_access.php' == $data ) {
				$template = wds_get_template( 'pages/' . $data );
			}

			$wds_data['wp_page']    = true;
			$wds_data['page']       = 'dashboard/access';
			$wds_data['logo_light'] = wds_option( 'logo_light' );
			$wds_data['logo_dark']  = wds_option( 'logo_dark' );
			$wds_data['bg_light']   = wds_option( 'bg_light' );
			$wds_data['bg_dark']    = wds_option( 'bg_dark' );
			$wds_data['target']     = 'user';

			$access = wds_post_meta( get_the_ID(), 'access_data' );
			$access = maybe_unserialize( $access );

			$wds_data['data'] = $access;
		}

		return $template;
	}
}

new Main();
