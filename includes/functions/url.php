<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Get url page.
 *
 * @param string $key The key of the page name.
 * @param string $param The parameter url.
 * @param string $redirect The redirect url.
 * @return mixed The url page.
 */
function wds_url( $key = '', $param = false, $redirect = false ) {
	$url = site_url();

	switch ( $key ) {
		// Admin
		case 'affiliate':
			$url .= '/admin/affiliate/';
			break;

		case 'payouts':
			$url .= '/admin/affiliate-payouts/';
			break;

		case 'statistics':
			$url .= '/admin/statistics/';
			break;

		case 'users':
			$url .= '/admin/users/';
			break;

		// Auth
		case 'login':
			$url .= '/auth/login/';
			break;

		case 'lostpass':
			$url .= '/auth/lost-password/';
			break;

		case 'resetpass':
			$url .= '/auth/reset-password/';
			break;

		case 'verify':
			$url .= '/auth/verify/';
			break;

		// Account
		case 'overview':
			$url .= '/account/overview/';
			break;

		case 'settings':
			$url .= '/account/settings/';
			break;

		case 'transactions':
			$url .= '/account/transactions/';
			break;

		case 'referrals':
			$url .= '/account/referrals/';
			break;

		// Dashboard
		case 'invitation':
			$url .= '/dashboard/invitation/';

			$custom = wds_option( 'invitation_link' );
			if ( 'url' == wds_option( 'invitation_type' ) && ! empty( $custom ) ) {
				$url = $custom;
			}
			break;

		case 'create':
			$url .= '/dashboard/invitation/create/';
			break;

		case 'edit':
			$url .= '/dashboard/invitation/edit/?id=';

			$custom = wds_option( 'invitation_edit_link' );
			if ( 'url' == wds_option( 'invitation_edit_type' ) && ! empty( $custom ) ) {
				$url = $custom;
			}
			break;

		case 'rsvp':
			$url .= '/dashboard/invitation/rsvp/?id=';

			$custom = wds_option( 'rsvp_link' );
			if ( 'url' == wds_option( 'rsvp_type' ) && ! empty( $custom ) ) {
				$url = $custom . '?id=';
			}
			break;

		case 'client':
			$url .= '/dashboard/client/';

			$custom = wds_option( 'client_link' );
			if ( 'url' == wds_option( 'client_type' ) && ! empty( $custom ) ) {
				$url = $custom;
			}
			break;

		case 'marketing':
			$url .= '/dashboard/marketing/';

			$custom = wds_option( 'marketing_link' );
			if ( 'url' == wds_option( 'marketing_type' ) && ! empty( $custom ) ) {
				$url = $custom;
			}
			break;

		case 'landingpage':
			$url .= '/dashboard/landingpage/';
			break;

		case 'landingpage_settings':
			if ( wds_option( 'wdr_settings_form' ) ) {
				$url .= '/dashboard/landingpage/edit/';
			} else {
				$url .= '/account/settings/';
			}
			break;

		case 'landingpage_edit':
			$url .= '/dashboard/landingpage/edit/';
			break;

		case 'upgrade':
			$url .= '/dashboard/upgrade/';

			$custom = wds_option( 'upgrade_link' );
			if ( 'url' == wds_option( 'upgrade_type' ) && ! empty( $custom ) ) {
				$url = $custom;
			}
			break;

		case 'upgrade_reseller':
			$url .= '/dashboard/upgrade/';

			$custom = wds_option( 'upgrade_reseller_link' );
			if ( 'url' == wds_option( 'upgrade_reseller_type' ) && ! empty( $custom ) ) {
				$url = $custom;
			}
			break;

		case 'upgrade_quota':
			$url .= '/dashboard/upgrade/?type=topup';

			$custom = wds_option( 'upgrade_quota_link' );
			if ( 'url' == wds_option( 'upgrade_quota_type' ) && ! empty( $custom ) ) {
				$url = $custom;
			}
			break;

		case 'access':
			$url .= '/dashboard/access/';
			break;

		// General
		case 'checkout':
			$url .= '/checkout/';
			break;

		case 'renew':
			$url .= '/renew/';
			if ( wds_is_replica() ) {
				$url = 'https://' . wds_option( 'wdr_domain_host' ) . '/renew/';
			}
			break;

		case 'pay':
			$url .= '/pay/';
			break;

		case 'thanks':
			$url .= '/thanks/';
			break;

		case 'reff':
			$url .= '/reff/';
			break;

		case 'share':
			$url .= '/share/?id=';

			$custom = wds_option( 'share_link' );
			if ( 'url' == wds_option( 'share_type' ) && ! empty( $custom ) ) {
				$url = site_url( '/' ) . $custom . '?id=';

				if ( wds_is_replica() && ( empty( wds_option( 'wdr_integration' ) ) || 'public' == wds_option( 'wdr_integration' ) ) ) {
					$host = wds_replica_invitation_host( $param );
					if ( $host ) {
						$url = $host . $custom . '?id=';
					}
				}
			} elseif ( wds_is_replica() && ( empty( wds_option( 'wdr_integration' ) ) || 'public' == wds_option( 'wdr_integration' ) ) ) {
				$host = wds_replica_invitation_host( $param );
				if ( $host ) {
					$url = $host . 'share/?id=';
				}
			}
			break;

		case 'share_client':
			$url .= '/share/client/?id=';
			if ( wds_is_replica() && ( empty( wds_option( 'wdr_integration' ) ) || 'public' == wds_option( 'wdr_integration' ) ) ) {
				$host = wds_replica_invitation_host( $param );
				if ( $host ) {
					$url = $host . 'share/client/?id=';
				}
			}
			break;

		// Public
		case 'public_rsvp':
			$url .= '/public/rsvp/?id=';
			break;
	}

	if ( $param ) {
		if ( 'share' == $key || 'share_client' == $key ) {
			$post  = get_post( $param );
			$param = $post->post_name;
		}

		$url .= $param;
	}

	if ( $redirect ) {
		$url = add_query_arg( 'redirect', wds_sanitize_text_field( $redirect ), $url );
	}

	return apply_filters( 'wds_url', $url, $key );
}

/**
 * Get current url.
 */
function wds_current_url() {
	global $wp;

	return site_url( '/' . $wp->request . '/' );
}

/**
 * Get current slug.
 */
function wds_current_slug() {
	global $wp;

	return $wp->request;
}

/**
 * Get current slug with query.
 */
function wds_current_slug_query() {
	global $wp;

	return $wp->request . '?' . $_SERVER['QUERY_STRING'];
}

/**
 * Get host domain.
 */
function wds_host() {
	return preg_replace( '(^https?://)', '', site_url() );
}
