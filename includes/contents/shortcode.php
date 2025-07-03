<?php
/**
 * WeddingSaas Shortcode.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Contents
 */

namespace WDS;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Shortcode Class.
 */
class Shortcode {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'wds_user_name', array( $this, 'wds_user_name' ) );
		add_shortcode( 'wds_title_by_id', array( $this, 'wds_title_by_id' ) );
		add_shortcode( 'wds_slug_by_id', array( $this, 'wds_slug_by_id' ) );
		add_shortcode( 'wds_user_status', array( $this, 'wds_user_status' ) );
		add_shortcode( 'wds_user_group', array( $this, 'wds_user_group' ) );
		add_shortcode( 'wds_membership', array( $this, 'wds_membership' ) );
		add_shortcode( 'wds_membership_by_id', array( $this, 'wds_membership_by_id' ) );
		add_shortcode( 'wds_active_period', array( $this, 'wds_active_period' ) );
		add_shortcode( 'wds_invitation_quota', array( $this, 'wds_invitation_quota' ) );
		add_shortcode( 'wds_total_invitation_created', array( $this, 'wds_total_invitation_created' ) );
		add_shortcode( 'wds_total_comment', array( $this, 'wds_total_comment' ) );
		add_shortcode( 'wds_logout', array( $this, 'wds_logout' ) );
		add_shortcode( 'wds_phone', array( $this, 'wds_phone' ) );
		add_shortcode( 'wds_invitation_duration', array( $this, 'wds_invitation_duration' ) );
		add_shortcode( 'wds_invitation_duration_by_id', array( $this, 'wds_invitation_duration_by_id' ) );
		add_shortcode( 'wds_category_by_id', array( $this, 'wds_category_by_id' ) );
		add_shortcode( 'wds_subtheme_by_id', array( $this, 'wds_subtheme_by_id' ) );

		// Pro
		add_shortcode( 'wds_client_quota', array( $this, 'wds_client_quota' ) );
		add_shortcode( 'wds_total_client', array( $this, 'wds_total_client' ) );
		add_shortcode( 'wds_total_income', array( $this, 'wds_total_income' ) );
		add_shortcode( 'wds_visitor_by_id', array( $this, 'wds_visitor_by_id' ) );
		add_shortcode( 'wds_affiliate_link', array( $this, 'wds_affiliate_link' ) );
	}

	/**
	 * Returns the user name.
	 *
	 * @return string The user name.
	 */
	public function wds_user_name() {
		return wds_user_name();
	}

	/**
	 * Retrieves the post title by post ID from URL parameters.
	 *
	 * @return string The post title.
	 */
	public function wds_title_by_id() {
		$title = '';
		if ( isset( $_GET['inserted_post_id'] ) ) {
			$inserted_post_id = $_GET['inserted_post_id'];
			if ( $inserted_post_id ) {
				$link = 'dashboard/invitation/edit' === wds_current_slug() ? wds_url( 'edit', $inserted_post_id ) : get_page_link() . '?id=' . $inserted_post_id;
				wds_redirect( $link );
			}
		}

		$id      = wds_sanitize_data_field( $_GET, 'id' );
		$post_id = wds_sanitize_data_field( $_GET, 'post_id' );

		if ( $id || $post_id ) {
			$post_id     = ! empty( $id ) ? $id : $post_id;
			$post_author = get_post_field( 'post_author', $post_id );
			if ( get_current_user_id() == $post_author ) {
				if ( in_array( get_post_status( $post_id ), array( 'trash', 'draft' ), true ) ) {
					wds_redirect( wds_url( 'invitation' ) );
				} else {
					$title = get_the_title( $post_id );
				}
			} elseif ( ! wds_is_admin() ) {
				wds_redirect( site_url() );
			} else {
				$title = get_the_title( $post_id );
			}
		} elseif ( ! wds_is_admin() ) {
			wds_redirect( site_url() );
		}

		return $title;
	}

	/**
	 * Retrieves the post slug by post ID from URL parameters.
	 *
	 * @return string The post slug.
	 */
	public function wds_slug_by_id() {
		$slug = '';
		if ( isset( $_GET['id'] ) || isset( $_GET['post_id'] ) ) {
			$post_id = ! empty( $_GET['id'] ) ? $_GET['id'] : $_GET['post_id'];
			$post    = get_post( $post_id );
			if ( $post ) {
				$slug = $post->post_name;
			}
		}

		return $slug;
	}

	/**
	 * Returns the user's status (active or inactive).
	 *
	 * @return string The user status.
	 */
	public function wds_user_status() {
		return 'active' == wds_user_status() ? wds_lang( 'active' ) : wds_lang( 'inactive' );
	}

	/**
	 * Returns the user's group.
	 *
	 * @return string The user group.
	 */
	public function wds_user_group() {
		$meta = wds_user_group();
		return $meta ? ucwords( $meta ) : '';
	}

	/**
	 * Retrieves the user's membership.
	 *
	 * @return string The user's membership.
	 */
	public function wds_membership() {
		return wds_user_membership();
	}

	/**
	 * Retrieves the membership meta by post ID.
	 *
	 * @return string The membership meta.
	 */
	public function wds_membership_by_id() {
		$meta = '';
		if ( isset( $_GET['id'] ) || isset( $_GET['post_id'] ) ) {
			$post_id = ! empty( $_GET['id'] ) ? $_GET['id'] : $_GET['post_id'];
			$meta    = wds_post_meta( $post_id, '_wds_membership' );
		}

		return $meta;
	}

	/**
	 * Retrieves the user's active period.
	 *
	 * @return string The user's active period.
	 */
	public function wds_active_period() {
		return wds_user_active_period();
	}

	/**
	 * Retrieves the user's invitation quota.
	 *
	 * @return string The user's invitation quota.
	 */
	public function wds_invitation_quota() {
		return wds_user_invitation_quota();
	}

	/**
	 * Retrieves the total number of invitations created by the user.
	 *
	 * @return int The total number of invitations.
	 */
	public function wds_total_invitation_created() {
		return count_user_posts( get_current_user_id() );
	}

	/**
	 * Retrieves the total number of comments on posts by the current user.
	 *
	 * @return int The total number of comments.
	 */
	public function wds_total_comment() {
		return wds_user_total_comment();
	}

	/**
	 * Returns the logout URL.
	 *
	 * @return string The logout URL.
	 */
	public function wds_logout() {
		$logout = wds_option( 'logout_redirect' );
		$url    = is_user_logged_in() && ! empty( $logout ) ? wp_logout_url( $logout ) : wp_logout_url( home_url() );

		return $url;
	}

	/**
	 * Retrieves the user's phone number.
	 *
	 * @return string The user's phone number.
	 */
	public function wds_phone() {
		return wds_user_phone();
	}

	/**
	 * Retrieves the user's invitation duration.
	 *
	 * @return string The invitation duration.
	 */
	public function wds_invitation_duration() {
		return wds_user_invitation_duration();
	}

	/**
	 * Retrieves the invitation duration meta by post ID.
	 *
	 * @return string The invitation duration.
	 */
	public function wds_invitation_duration_by_id() {
		$duration = '';
		if ( isset( $_GET['id'] ) || isset( $_GET['post_id'] ) ) {
			$post_id = ! empty( $_GET['id'] ) ? $_GET['id'] : $_GET['post_id'];
			$meta    = wds_post_meta( $post_id, '_wds_pep_period' );
			if ( $meta ) {
				$duration = date_i18n( get_option( 'date_format' ), $meta );
			}
		}

		return $duration;
	}

	/**
	 * Retrieves the category by ID.
	 *
	 * @return string The category.
	 */
	public function wds_category_by_id() {
		$post_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		if ( $post_id > 0 ) {
			$categories = get_the_category( $post_id );
			if ( ! empty( $categories ) ) {
				$first_category = $categories[0];
				return $first_category->slug;
			} else {
				return __( 'No category found.', 'wds-notrans' );
			}
		} else {
			return __( 'Invalid post_id parameter.', 'wds-notrans' );
		}
	}

	/**
	 * Retrieves the sub theme by ID.
	 *
	 * @since 2.0.5
	 * @return string The subtheme.
	 */
	public function wds_subtheme_by_id() {
		$post_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		if ( $post_id > 0 ) {
			return WDS()->invitation->get_subtheme( $post_id );
		} else {
			return __( 'Invalid post_id parameter.', 'wds-notrans' );
		}
	}

	/**
	 * Retrieves the user's client quota.
	 *
	 * @return string The client quota.
	 */
	public function wds_client_quota() {
		return wds_user_client_quota();
	}

	/**
	 * Retrieves the total number of clients.
	 *
	 * @return int The total number of clients.
	 */
	public function wds_total_client() {
		return wds_user_total_client();
	}

	/**
	 * Retrieves the total income.
	 *
	 * @return float The total income.
	 */
	public function wds_total_income() {
		return wds_user_total_income();
	}

	/**
	 * Retrieves the visitor count by post ID.
	 *
	 * @return int The visitor count.
	 */
	public function wds_visitor_by_id() {
		$visitor = 0;
		if ( isset( $_GET['id'] ) || isset( $_GET['post_id'] ) ) {
			$post_id = ! empty( $_GET['id'] ) ? $_GET['id'] : $_GET['post_id'];
			$visitor = wds_post_meta( $post_id, '_wds_visitor_count' );
		}

		return ! empty( $visitor ) ? $visitor : 0;
	}

	/**
	 * Retrieves the affiliate link.
	 *
	 * @return string The affiliate link.
	 */
	public function wds_affiliate_link() {
		return is_user_logged_in() ? wds_user_affiliate_link() : '';
	}
}

new Shortcode();
