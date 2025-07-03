<?php
/**
 * WeddingSaas Main Frontend.
 *
 * Handles the main frontend logic for different page.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Frontend
 */

namespace WDS\Frontend;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class Main extends \WDS\Abstracts\Frontend {

	/**
	 * Constructor.
	 *
	 * Initializes the frontend component for a specific page section.
	 *
	 * @param string|false $page The page section to be loaded.
	 */
	public function __construct( $page = false ) {
		global $wds_data;

		if ( ! $page || ! empty( $page ) ) {
			$wds_data['page']       = $page;
			$wds_data['logo_light'] = wds_option( 'logo_light' );
			$wds_data['logo_dark']  = wds_option( 'logo_dark' );
			$wds_data['bg_light']   = wds_option( 'bg_light' );
			$wds_data['bg_dark']    = wds_option( 'bg_dark' );

			if ( strpos( $page, 'admin' ) === 0 ) {
				$this->admin( $page );
			} elseif ( strpos( $page, 'auth' ) === 0 ) {
				$this->auth( $page );
			} elseif ( strpos( $page, 'account' ) === 0 ) {
				$this->account( $page );
			} elseif ( strpos( $page, 'dashboard' ) === 0 ) {
				$this->dashboard( $page );
			} elseif ( strpos( $page, 'public' ) === 0 ) {
				$this->public( $page );
			} else {
				$this->general( $page );
			}
		}
	}

	/**
	 * Load the page admin.
	 *
	 * @param string|false $page The page section to be loaded.
	 */
	public function admin( $page ) {
		global $wds_data;

		if ( ! wds_is_admin() ) {
			wds_redirect( wds_url( 'overview' ) );
		}

		$user_id = get_current_user_id();

		$wds_data['target'] = 'user';

		switch ( $page ) {
			case 'admin/affiliate':
				$this->id       = 'affiliate';
				$this->target   = 'user';
				$this->template = 'admin/affiliate.php';
				$this->title    = __( 'Affiliate', 'wds-notrans' );

				$wds_data['data_commission'] = \WDS_Statistics::aff_commissions_stats();
				$wds_data['data_results']    = \WDS_Statistics::aff_commissions_stats( false );
				break;

			case 'admin/affiliate-payouts':
				$this->id       = 'affiliate_payouts';
				$this->target   = 'user';
				$this->template = 'admin/affiliate-payouts.php';
				$this->title    = __( 'Affiliate Payouts', 'wds-notrans' );

				$wds_data['data_unpaid']   = \WDS_Statistics::affiliate_payout_stats( false );
				$wds_data['data_withdraw'] = \WDS_Statistics::affiliate_payout_stats();
				break;

			case 'admin/statistics':
				$this->id       = 'statistics';
				$this->target   = 'user';
				$this->template = 'admin/statistics.php';
				$this->title    = __( 'Statistics', 'wds-notrans' );
				break;

			case 'admin/users':
				$this->id       = 'users';
				$this->target   = 'user';
				$this->template = 'admin/users.php';
				$this->title    = __( 'User', 'wds-notrans' );

				// $data  = array();
				// $users = get_users();
				// foreach ( $users as $user ) {
				//  $user_id = $user->ID;

				//  $data[] = (object) array(
				//      'ID'                 => $user_id,
				//      'name'               => $user->display_name,
				//      'email'              => $user->user_email,
				//      'phone'              => wds_user_phone( $user_id ),
				//      'status'             => wds_user_status( $user_id ) == 'active' ? wds_lang( 'active' ) : wds_lang( 'inactive' ),
				//      'user_group'         => ucwords( wds_user_group( $user_id ) ),
				//      'user_expired'       => wds_user_active_period( $user_id ),
				//      'invitation_quota'   => wds_user_invitation_quota( $user_id ) ? wds_user_invitation_quota( $user_id ) : 0,
				//      'client_quota'       => wds_user_client_quota( $user_id ) ? wds_user_client_quota( $user_id ) : 0,
				//      'invitation_created' => wds_user_posts_count( $user_id ),
				//      'storage'            => wds_user_storage( $user_id ) . ' MB',
				//  );
				// }

				// $wds_data['data_users'] = $data;
				// break;
		}
	}

	/**
	 * Load the page auth.
	 *
	 * @param string|false $page The page section to be loaded.
	 */
	public function auth( $page ) {
		global $wds_data;

		$wds_data['target'] = 'auth';

		switch ( $page ) {
			case 'auth/login':
				$this->id       = 'login';
				$this->target   = 'auth';
				$this->template = 'auth/login.php';
				$this->title    = wds_lang( 'auth_login_title' );
				break;

			case 'auth/lost-password':
				$this->id       = 'lost-password';
				$this->target   = 'auth';
				$this->template = 'auth/lost-password.php';
				$this->title    = wds_lang( 'auth_lp_title' );
				break;

			case 'auth/reset-password':
				$this->id       = 'reset-password';
				$this->target   = 'auth';
				$this->template = 'auth/reset-password.php';
				$this->title    = wds_lang( 'auth_rp_title' );
				break;

			case 'auth/verify':
				$this->id       = 'verify';
				$this->target   = 'both';
				$this->template = 'auth/verify.php';
				$this->title    = wds_lang( 'auth_verify_title' );

				$wds_data['target'] = 'both';

				$dashboard_link = wds_option( 'dashboard_link' );

				if ( ! wds_option( 'account_activation' ) ) {
					wds_redirect( $dashboard_link );
				}

				if ( isset( $_GET['key'] ) && isset( $_GET['user'] ) ) {
					$key  = $_GET['key'];
					$user = sanitize_user( $_GET['user'] );
					$user = get_user_by( 'login', $user );

					if ( $user && $user->user_activation_key === $key ) {
						$is_verified = wds_is_account_verified( $user->ID );
						if ( ! $is_verified ) {
							update_user_meta( $user->ID, '_is_verified', true );
							do_action( 'wds_user_register', $user->ID );
						}
						wds_redirect( $dashboard_link );
					} else {
						wp_die( esc_html__( 'Tautan aktivasi tidak valid.', 'weddingsaas' ) );
					}
				} elseif ( is_user_logged_in() ) {
					$is_verified = wds_is_account_verified();
					if ( $is_verified ) {
						wds_redirect( $dashboard_link );
					}
				} else {
					wp_die( esc_html__( 'Tautan aktivasi tidak valid.', 'weddingsaas' ) );
				}

				if ( isset( $_GET['resend'] ) && 'true' == $_GET['resend'] ) {
					do_action( 'wds_user_activation', get_current_user_id() );
					wds_redirect( wds_url( 'verify' ) . '?resend=success' );
				}
				break;
		}
	}

	/**
	 * Load the page account.
	 *
	 * @param string|false $page The page section to be loaded.
	 */
	public function account( $page ) {
		global $wds_data;

		$wds_data['target'] = 'user';

		$user_id = get_current_user_id();

		switch ( $page ) {
			case 'account/overview':
				$this->id       = 'overview';
				$this->target   = 'user';
				$this->template = 'account/overview.php';
				$this->title    = wds_lang( 'overview' );

				$color = wds_option( 'default_color' );
				$color = $color['primary'];

				$wds_data['color'] = wds_option( 'custome_color' ) ? $color : '#009EF7';

				$wds_data['data_unpaid_invoices'] = wds_get_invoice_unpaid( $user_id );
				$wds_data['data_expired_orders']  = wds_get_order_expired( $user_id );
				break;

			case 'account/settings':
				$this->id       = 'settings';
				$this->target   = 'user';
				$this->template = 'account/settings.php';
				$this->title    = wds_lang( 'settings' );

				$data = array(
					'fullname'             => wds_user_name( $user_id ),
					'email'                => wds_user_email( $user_id ),
					'phone'                => wds_user_phone( $user_id ),
					'branding_name'        => wds_user_meta( $user_id, '_branding_name' ),
					'branding_logo'        => wds_user_meta( $user_id, '_branding_logo' ),
					'branding_link'        => wds_user_meta( $user_id, '_branding_link' ),
					'branding_description' => wds_user_meta( $user_id, '_branding_description' ),
					'instagram'            => wds_user_meta( $user_id, '_instagram' ),
					'facebook'             => wds_user_meta( $user_id, '_facebook' ),
					'tiktok'               => wds_user_meta( $user_id, '_tiktok' ),
					'twitter'              => wds_user_meta( $user_id, '_twitter' ),
					'youtube'              => wds_user_meta( $user_id, '_youtube' ),
					'invitation_price'     => wds_user_meta( $user_id, '_invitation_price' ),
				);

				$wds_data['data_user'] = $data;

				if ( wds_option( 'reseller_branding' ) ) {
					$wds_data['reseller'] = wds_user_group() != 'reseller' ? false : true;
				} else {
					$wds_data['reseller'] = false;
				}

				$hide_branding         = wds_option( 'reseller_hide' );
				$wds_data['name']      = ! empty( $hide_branding ) && in_array( 'name', $hide_branding ) ? false : true;
				$wds_data['logo']      = ! empty( $hide_branding ) && in_array( 'logo', $hide_branding ) ? false : true;
				$wds_data['link']      = ! empty( $hide_branding ) && in_array( 'link', $hide_branding ) ? false : true;
				$wds_data['desc']      = ! empty( $hide_branding ) && in_array( 'description', $hide_branding ) ? false : true;
				$wds_data['instagram'] = ! empty( $hide_branding ) && in_array( 'instagram', $hide_branding ) ? false : true;
				$wds_data['facebook']  = ! empty( $hide_branding ) && in_array( 'facebook', $hide_branding ) ? false : true;
				$wds_data['tiktok']    = ! empty( $hide_branding ) && in_array( 'tiktok', $hide_branding ) ? false : true;
				$wds_data['twitter']   = ! empty( $hide_branding ) && in_array( 'twitter', $hide_branding ) ? false : true;
				$wds_data['youtube']   = ! empty( $hide_branding ) && in_array( 'youtube', $hide_branding ) ? false : true;
				break;

			case 'account/transactions':
				$this->id       = 'transactions';
				$this->target   = 'user';
				$this->template = 'account/transactions.php';
				$this->title    = wds_lang( 'transactions' );

				$wds_data['data_invoices'] = wds_get_invoice_transactions( $user_id );
				break;

			case 'account/referrals':
				$this->id       = 'referrals';
				$this->target   = 'user';
				$this->template = 'account/referrals.php';
				$this->title    = wds_lang( 'referrals' );

				$referral_con = ! empty( wds_option( 'affiliate_hide' ) ) && in_array( wds_user_group(), wds_option( 'affiliate_hide' ) ) ? true : false;
				$restrict     = wds_user_affiliate_status() == 'inactive' ? true : $referral_con;
				if ( $restrict ) {
					wds_redirect( wds_url( 'overview' ) );
				}

				$wds_data['data_summary']  = wds_get_affiliate_summary( $user_id );
				$wds_data['data_coupons']  = wds_get_affiliate_coupon( $user_id );
				$wds_data['data_products'] = wds_get_product_active();
				break;
		}
	}

	/**
	 * Load the page dashboard.
	 *
	 * @param string|false $page The page section to be loaded.
	 */
	public function dashboard( $page ) {
		global $wds_data;

		$user_id    = get_current_user_id();
		$user_group = wds_user_group();

		$wds_data['target'] = 'user';

		switch ( $page ) {
			case 'dashboard/invitation':
				$this->id       = 'invitation';
				$this->target   = 'user';
				$this->template = 'dashboard/invitation.php';
				$this->title    = wds_lang( 'invitation' );

				if ( isset( $_GET['layout'] ) ) {
					$layout = wds_sanitize_data_field( $_GET, 'layout' );
					if ( in_array( $layout, array( 'table', 'grid' ) ) ) {
						update_user_meta( $user_id, '_layout', $layout );
						wds_redirect( wds_url( 'invitation' ) );
					}
				}

				$layout = wds_user_layout();

				$wds_data['layout'] = $layout;

				if ( 'table' == $layout ) {
					$args = array(
						'author'         => $user_id,
						'post_type'      => 'post',
						'posts_per_page' => -1,
						'post_status'    => array( 'publish', 'draft', 'trash', 'pending' ),
						'orderby'        => 'ID',
						'order'          => 'DESC',
					);
				} else {
					$args = array(
						'author'         => $user_id,
						'post_type'      => 'post',
						'posts_per_page' => 14,
						'post_status'    => array( 'publish', 'draft', 'trash', 'pending' ),
						'orderby'        => 'ID',
						'order'          => 'DESC',
						's'              => isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '',
						'paged'          => isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1,
					);
				}

				$invitation = array();

				$query = new \WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						$post_id        = get_the_ID();
						$categories     = get_the_category();
						$category_names = array();
						foreach ( $categories as $category ) {
							$category_name = $category->name;
							if ( $category->parent ) {
								$parent_category = get_term( $category->parent, 'category' );
								if ( $parent_category && ! is_wp_error( $parent_category ) ) {
									$category_name = $parent_category->name . ' (' . $category_name . ')';
								}
							}
							$category_names[] = $category_name;
						}

						$permalink = get_permalink();

						$invitation[] = (object) array(
							'ID'        => $post_id,
							'thumbnail' => get_the_post_thumbnail_url( $post_id, 'thumbnail' ),
							'title'     => get_the_title(),
							'visitor'   => get_post_meta( $post_id, '_visitor', true ),
							'comment'   => get_comments_number(),
							'rsvp'      => wds_invitation_rsvp_count( $post_id ),
							'category'  => implode( ', ', $category_names ),
							'status'    => get_post_status(),
							'permalink' => $permalink,
							'slug'      => basename( $permalink ),
						);
					}
					wp_reset_postdata();
				}

				$wds_data['data_invitation'] = $invitation;
				$wds_data['max_num_pages']   = $query->max_num_pages;
				break;

			case 'dashboard/invitation/create':
				$this->id       = 'invitation-create';
				$this->target   = 'user';
				$this->template = 'dashboard/invitation-create.php';
				$this->title    = wds_lang( 'create_invitation' );
				break;

			case 'dashboard/invitation/edit':
				$session = WDS()->session->get( 'edit_post' );
				$post_id = wds_sanitize_data_field( $_GET, 'id' );
				$post_id = ! empty( $post_id ) ? intval( $post_id ) : intval( $session );
				if ( ! $post_id ) {
					wds_redirect( wds_url( 'invitation' ) );
				}

				if ( ! get_post( $post_id ) ) {
					wds_redirect( wds_url( 'invitation' ) );
				}

				$post_author_id = get_post_field( 'post_author', $post_id );

				if ( $user_id == $post_author_id || wds_is_editor() ) {
					if ( ( get_post_status( $post_id ) == 'trash' ) || ( get_post_status( $post_id ) == 'draft' ) ) {
						wds_redirect( wds_url( 'invitation' ) );
					}
				} elseif ( ! wds_is_admin() ) {
					wds_redirect( wds_url( 'invitation' ) );
				}

				WDS()->session->set( 'edit_post', $post_id );

				$this->id       = 'invitation-edit';
				$this->target   = 'user';
				$this->template = 'dashboard/invitation-edit.php';
				$this->title    = wds_lang( 'edit_invitation' ) . ' ' . get_the_title( $post_id );

				if ( wds_is_theme() && wds_invitation_is_theme( $post_id ) ) {
					$this->target   = 'theme';
					$this->template = 'general/theme.php';

					$wds_data['wds_theme_edit'] = true;
				}

				$wds_data['post_id'] = $post_id;
				break;

			case 'dashboard/invitation/rsvp':
				$post_id = intval( wds_sanitize_data_field( $_GET, 'id' ) );
				if ( ! $post_id ) {
					wds_redirect( wds_url( 'invitation' ) );
				}

				if ( ! get_post( $post_id ) ) {
					wds_redirect( wds_url( 'invitation' ) );
				}

				$post_author_id = get_post_field( 'post_author', $post_id );

				if ( $user_id == $post_author_id ) {
					if ( ( get_post_status( $post_id ) == 'trash' ) || ( get_post_status( $post_id ) == 'draft' ) ) {
						wds_redirect( wds_url( 'invitation' ) );
					}
				} elseif ( ! wds_is_admin() ) {
					wds_redirect( wds_url( 'invitation' ) );
				}

				$this->id       = 'invitation-rsvp';
				$this->target   = 'user';
				$this->template = 'dashboard/invitation-rsvp.php';
				$this->title    = __( 'RSVP', 'wds-notrans' ) . ' ' . get_the_title( $post_id );

				$rsvp       = array();
				$data       = array();
				$present    = 0;
				$notpresent = 0;
				$notsure    = 0;

				$integration = wds_option( 'rsvp_integration' );

				$args = array(
					'post_id' => $post_id,
					'status'  => 'approve',
				);

				$comments = get_comments( $args );
				if ( $comments ) {
					foreach ( $comments as $comment ) {
						$comment_id = $comment->comment_ID;
						$date       = get_comment_date( '', $comment_id );
						$name       = get_comment_author( $comment_id );
						$comment    = get_comment_text( $comment_id );
						$attendance = get_comment_meta( $comment_id, 'attendance', true );
						$attendance = $attendance ? $attendance : get_comment_meta( $comment_id, 'konfirmasi', true );
						$guest      = intval( get_comment_meta( $comment_id, 'guest', true ) );

						if ( 'present' == $attendance || 'Hadir' == $attendance ) {
							if ( 'default' == $integration ) {
								$present += $guest;
							} else {
								++$present;
							}
						} elseif ( 'notpresent' == $attendance || 'Tidak hadir' == $attendance ) {
							++$notpresent;
						} elseif ( 'notsure' == $attendance ) {
							++$notsure;
						}

						$rsvp[] = (object) array(
							'date'       => $date,
							'name'       => $name,
							'comment'    => $comment,
							'attendance' => wds_rsvp_attendance( $attendance ),
							'guest'      => $guest,
						);
					}
				}

				$data[] = (object) array(
					'all'        => $present + $notpresent + $notsure,
					'present'    => $present,
					'notpresent' => $notpresent,
					'notsure'    => $notsure,
				);

				$wds_data['data_rsvp']       = $rsvp;
				$wds_data['data_attendance'] = $data;

				$wds_data['post_id'] = $post_id;
				break;

			case 'dashboard/client':
				$this->id       = 'client';
				$this->target   = 'user';
				$this->template = 'dashboard/client.php';
				$this->title    = wds_lang( 'client' );

				if ( 'reseller' != $user_group || ! empty( wds_option( 'client_type' ) ) ) {
					wds_redirect( wds_url( 'overview' ) );
				}

				$wds_data['data_client'] = wds_get_all_client( $user_id );
				break;

			case 'dashboard/marketing':
				$this->id       = 'marketing';
				$this->target   = 'user';
				$this->template = 'dashboard/marketing.php';
				$this->title    = wds_lang( 'marketing' );

				if ( 'reseller' != $user_group || ! empty( wds_option( 'marketing_type' ) ) ) {
					wds_redirect( wds_url( 'overview' ) );
				}
				break;

			case 'dashboard/landingpage':
				$this->id       = 'landingpage';
				$this->target   = 'user';
				$this->template = 'dashboard/landingpage.php';
				$this->title    = wds_lang( 'wdr_title' );

				if ( ! wds_is_replica() || 'reseller' != $user_group ) {
					wds_redirect( wds_url( 'overview' ) );
				}

				$query = wdr_get_by( "WHERE user_id = '$user_id'" );

				$host_number = wds_option( 'wdr_select_host', '1' );
				$host_custom = wds_option( 'wdr_domain_host_custom' );

				$url       = '#';
				$status    = '';
				$value     = false;
				$domain    = false;
				$subdomain = false;

				if ( $query ) {
					$value  = true;
					$status = $query->status;

					if ( 'nothing' != $query->domain ) {
						$domain = $query->domain;
						$url    = 'https://' . $domain;
					}

					if ( 'nothing' != $query->subdomain ) {
						$subdomain = $query->subdomain;
						$url       = 'https://' . $subdomain . '.' . wds_host();
					}
				}

				if ( '3' == $host_number ) {
					if ( $domain ) {
						if ( strpos( $domain, $host_custom ) !== false ) {
							$parts     = explode( '.', $domain );
							$subdomain = $parts[0];
							$url       = 'https://' . $subdomain . '.' . $host_custom;
							$domain    = false;
						}
					}
				}

				$data = array(
					'phone'            => wds_user_phone( $user_id ),
					'branding_name'    => wds_user_meta( $user_id, '_branding_name' ),
					'branding_logo'    => wds_user_meta( $user_id, '_branding_logo' ),
					'invitation_price' => wds_user_meta( $user_id, '_invitation_price' ),
					'url'              => $url,
					'status'           => $status,
					'value'            => $value,
					'domain'           => $domain,
					'subdomain'        => $subdomain,
					'host_number'      => $host_number,
					'host_custom'      => $host_custom,
				);

				$wds_data['data_reseller'] = $data;
				break;

			case 'dashboard/landingpage/edit':
				$this->id       = 'landingpage-edit';
				$this->target   = 'user';
				$this->template = 'dashboard/landingpage-edit.php';
				$this->title    = __( 'Edit Landing Page', 'weddingsaas' );

				if ( ! wds_is_replica() || 'reseller' != $user_group ) {
					wds_redirect( wds_url( 'overview' ) );
				}

				$query = wdr_get_by( "WHERE user_id = '$user_id'" );

				$host_number = wds_option( 'wdr_select_host', '1' );
				$host_custom = wds_option( 'wdr_domain_host_custom' );

				$url       = '#';
				$status    = '';
				$value     = false;
				$domain    = false;
				$subdomain = false;

				if ( $query ) {
					$value  = true;
					$status = $query->status;

					if ( 'nothing' != $query->domain ) {
						$domain = $query->domain;
						$url    = 'https://' . $domain;
					}

					if ( 'nothing' != $query->subdomain ) {
						$subdomain = $query->subdomain;
						$url       = 'https://' . $subdomain . '.' . wds_host();
					}
				}

				if ( '3' == $host_number ) {
					if ( $domain ) {
						if ( strpos( $domain, $host_custom ) !== false ) {
							$parts     = explode( '.', $domain );
							$subdomain = $parts[0];
							$url       = 'https://' . $subdomain . '.' . $host_custom;
							$domain    = false;
						}
					}
				}

				$data = array(
					'phone'            => wds_user_phone( $user_id ),
					'branding_name'    => wds_user_meta( $user_id, '_branding_name' ),
					'branding_logo'    => wds_user_meta( $user_id, '_branding_logo' ),
					'invitation_price' => wds_user_meta( $user_id, '_invitation_price' ),
					'url'              => $url,
					'status'           => '#' == $url ? wds_lang( 'wdr_landing_page_not_created' ) : $status,
					'form'             => wds_check_array( wds_option( 'wdr_form' ) ),
				);

				$wds_data['data_reseller'] = $data;
				break;

			case 'dashboard/upgrade':
				$this->id       = 'upgrade';
				$this->target   = 'user';
				$this->template = 'dashboard/upgrade.php';
				$this->title    = wds_lang( 'upgrade' );

				if ( empty( $user_group ) ) {
					wds_redirect( wds_url( 'overview' ) );
				}

				$upgrade_coupon   = wds_option( 'upgrade_coupon' );
				$upgrade          = wds_option( 'upgrade' );
				$upgrade_reseller = wds_option( 'upgrade_reseller' );
				$upgrade_quota    = wds_option( 'upgrade_quota' );

				if ( 'trial' == $user_group || 'member' == $user_group ) {
					$products = wds_check_array( $upgrade, true ) ? $upgrade : wds_get_product_membership( 'upgrade' );
				} elseif ( 'reseller' == $user_group ) {
					$type     = wds_sanitize_data_field( $_GET, 'type' );
					$reseller = wds_check_array( $upgrade_reseller, true ) ? $upgrade_reseller : wds_get_product_membership( 'upgrade_reseller' );
					$addon    = wds_check_array( $upgrade_quota, true ) ? $upgrade_quota : wds_get_product_membership( 'upgrade_quota' );
					$products = 'topup' == $type ? $addon : $reseller;
				}

				$data = array();
				foreach ( $products as $item ) {
					$product_id = intval( $item );
					$product    = wds_get_product( $product_id );
					if ( ! $product ) {
						continue;
					}

					$price = wds_get_product_price( $product_id );

					$payment_type        = str_replace( '/', '', wds_get_product_data( $product_id, 'payment_type' ) );
					$membership_type     = wds_get_product_data( $product_id, 'membership_type' );
					$membership_lifetime = wds_get_product_data( $product_id, 'membership_lifetime' );
					$invitation_lifetime = wds_get_product_data( $product_id, 'invitation_lifetime' );

					$coupon_code = '';
					if ( $upgrade_coupon ) {
						$coupon = wds_check_coupon_product( $upgrade_coupon, $product_id );
						if ( $coupon ) {
							$raw_rebate = wds_get_coupon_raw_rebate( $coupon );
							if ( wds_check_array( $raw_rebate, true ) ) {
								if ( 'percen' == $raw_rebate['type'] ) {
									$discount = floatval( $raw_rebate['value'] ) * floatval( $price );
									$discount = $discount / 100;
								} else {
									$discount = $raw_rebate['value'];
								}

								$price       = floatval( $price ) - intval( $discount );
								$coupon_code = '?coupon=' . $upgrade_coupon;
							}
						}
					}

					$restrict_product = wds_check_array( wds_get_product_meta( $product_id, 'restrict_product' ) );
					if ( empty( $restrict_product ) || in_array( wds_user_membership(), $restrict_product ) ) {
						$data[] = (object) array(
							'ID'                   => $product_id,
							'title'                => $product->title,
							'price'                => $price,
							'payment_type'         => $payment_type,
							'membership_data'      => $membership_type,
							'membership_type'      => wds_get_product_meta( $product_id, 'membership_type' ),
							'membership_period'    => $membership_lifetime,
							'invitation_period'    => $invitation_lifetime,
							'invitation_quota'     => wds_get_product_meta( $product_id, 'invitation_quota' ),
							'res_invitation_quota' => wds_get_product_meta( $product_id, 'reseller_invitation_quota' ),
							'res_client_quota'     => wds_get_product_meta( $product_id, 'reseller_client_quota' ),
							'checkout_link'        => wds_url( 'checkout', $product->slug ) . $coupon_code,
						);
					}
				}

				// Sorting low to high prices
				usort(
					$data,
					function ( $a, $b ) {
						return $a->price - $b->price;
					}
				);

				$wds_data['data_upgrade'] = $data;
				break;

			case 'dashboard/access':
				$this->id       = 'access';
				$this->target   = 'user';
				$this->template = 'dashboard/access.php';
				$this->title    = wds_lang( 'access' );

				if ( ! wds_is_digital() ) {
					wds_redirect( wds_url( 'overview' ) );
				}

				$posts_data = array();

				$args  = array(
					'post_type'      => 'wds_access',
					'posts_per_page' => -1,
				);
				$query = new \WP_Query( $args );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						$post_id   = get_the_ID();
						$title     = get_the_title();
						$thumbnail = get_the_post_thumbnail_url( $post_id, 'full' );
						$products  = wds_post_meta( $post_id, 'restrict_product' );
						$products  = maybe_unserialize( $products );

						$posts_data[] = array(
							'post_id'   => $post_id,
							'title'     => $title,
							'thumbnail' => $thumbnail,
							'products'  => $products,
						);
					}
					wp_reset_postdata();
				}

				$user_products = wds_user_meta( $user_id, '_wds_access_product' );

				$result = array();
				if ( ! empty( $posts_data ) ) {
					foreach ( $posts_data as $post ) {
						if ( ! empty( $post['products'] ) && ! empty( $user_products ) ) {
							$matching_products = array_intersect( $post['products'], $user_products );
							if ( ! empty( $matching_products ) ) {
								$result[] = $post;
							}
						}
					}
				}

				$wds_data['data_access'] = $result;
				break;
		}
	}

	/**
	 * Load the page public.
	 *
	 * @param string|false $page The page section to be loaded.
	 */
	public function public( $page ) {
		global $wds_data;

		$wds_data['target'] = 'public';

		switch ( $page ) {
			case 'public/rsvp':
				$post_id = intval( wds_sanitize_data_field( $_GET, 'id' ) );
				if ( ! $post_id ) {
					wds_redirect( home_url() );
				}

				if ( ! get_post( $post_id ) ) {
					wds_redirect( home_url() );
				}

				$rsvp_title = __( 'RSVP', 'wds-notrans' ) . ' ' . get_the_title( $post_id );

				$this->id       = 'invitation-rsvp';
				$this->target   = 'public';
				$this->template = 'public/rsvp.php';
				$this->title    = $rsvp_title;

				$user_id = get_post_field( 'post_author', $post_id );

				$logo_link     = ! empty( wds_user_meta( $user_id, '_branding_link' ) ) ? wds_user_meta( $user_id, '_branding_link' ) : home_url();
				$logo_src      = wds_user_meta( $user_id, '_branding_logo' );
				$branding_name = wds_user_meta( $user_id, '_branding_name' );
				$copyright     = ! empty( $branding_name ) ? 'Copyright © ' . gmdate( 'Y' ) . ' - ' . $branding_name : wds_option( 'copyright' );

				$wds_data['logo_link'] = $logo_link;
				$wds_data['logo_src']  = $logo_src;
				$wds_data['copyright'] = $copyright;

				$activate = true;
				$password = wds_option( 'rsvp_password' );

				$get1 = wds_sanitize_data_field( $_GET, 'activate' );
				$get2 = wds_sanitize_data_field( $_GET, 'pass' );

				if ( 'success' == $get1 && $get2 == $password ) {
					WDS()->session->set( 'rsvp_' . $post_id, true );
					wds_redirect( remove_query_arg( array( 'activate', 'pass' ), $_SERVER['REQUEST_URI'] ) );
				}

				if ( ! empty( $password ) ) {
					$activate = WDS()->session->get( 'rsvp_' . $post_id );
				}

				$wds_data['rsvp_activate'] = $activate;
				$wds_data['rsvp_title']    = $rsvp_title;

				$rsvp       = array();
				$data       = array();
				$present    = 0;
				$notpresent = 0;
				$notsure    = 0;

				$integration = wds_option( 'rsvp_integration' );

				$args = array(
					'post_id' => $post_id,
					'status'  => 'approve',
				);

				$comments = get_comments( $args );
				if ( $comments ) {
					foreach ( $comments as $comment ) {
						$comment_id = $comment->comment_ID;
						$date       = get_comment_date( '', $comment_id );
						$name       = get_comment_author( $comment_id );
						$comment    = get_comment_text( $comment_id );
						$attendance = get_comment_meta( $comment_id, 'attendance', true );
						$attendance = $attendance ? $attendance : get_comment_meta( $comment_id, 'konfirmasi', true );
						$guest      = intval( get_comment_meta( $comment_id, 'guest', true ) );

						if ( 'present' == $attendance || 'Hadir' == $attendance ) {
							if ( 'default' == $integration ) {
								$present += $guest;
							} else {
								++$present;
							}
						} elseif ( 'notpresent' == $attendance || 'Tidak hadir' == $attendance ) {
							++$notpresent;
						} elseif ( 'notsure' == $attendance ) {
							++$notsure;
						}

						$rsvp[] = (object) array(
							'date'       => $date,
							'name'       => $name,
							'comment'    => $comment,
							'attendance' => wds_rsvp_attendance( $attendance ),
							'guest'      => $guest,
						);
					}
				}

				$data[] = (object) array(
					'all'        => $present + $notpresent + $notsure,
					'present'    => $present,
					'notpresent' => $notpresent,
					'notsure'    => $notsure,
				);

				$wds_data['data_rsvp']       = $rsvp;
				$wds_data['data_attendance'] = $data;

				$wds_data['post_id'] = $post_id;
				break;
		}
	}

	/**
	 * Load the page general.
	 *
	 * @param string|false $page The page section to be loaded.
	 */
	public function general( $page ) {
		global $wds_data;

		$wds_data['target'] = 'both';

		switch ( $page ) {
			case 'checkout':
				$this->id       = 'checkout';
				$this->target   = 'both';
				$this->template = 'general/checkout.php';
				$this->title    = wds_lang( 'trx_checkout_title' );

				$product_id = \WDS_Checkout::instance()->get_product();
				$product    = wds_get_product( $product_id );

				if ( ! $product ) {
					$this->template = 'general/product-invalid.php';
					return;
				}

				if ( $product && 'active' != $product->status ) {
					$this->template = 'general/product-inactive.php';
					return;
				}

				$is_digital = wds_is_digital() && 'digital' == wds_get_product_meta( $product_id, 'product_type' ) ? true : false;

				if ( $is_digital ) {
					$this->template = 'general/checkout-digital.php';

					\WDS_Checkout_Digital::instance()->prepare( $product_id );
					\WDS_Checkout_Digital::instance()->load_data();
				} else {
					\WDS_Checkout::instance()->prepare( $product_id );
					\WDS_Checkout::instance()->load_data();
				}
				break;

			case 'renew':
				$this->id       = 'renew';
				$this->target   = 'both';
				$this->template = 'general/renew.php';
				$this->title    = wds_lang( 'trx_renew_title' );

				$order_id = wds_get_current_order_id();
				$order    = wds_get_order( $order_id );

				if ( ! is_user_logged_in() ) {
					wds_redirect( wds_url( 'login', false, 'renew/' . wds_encrypt_decrypt( $order_id ) ) );
				}

				if ( ! $order || 'active' == $order->status || 'onetime' == wds_get_order_meta( $order_id, 'duration' ) || get_current_user_id() != $order->user_id ) {
					$this->template = 'general/payment-notfound.php';
					return;
				}

				$product_id = $order->product_id;
				$product    = wds_get_product( $product_id );

				if ( ! $product ) {
					$this->template = 'general/product-invalid.php';
					return;
				}

				if ( $product && 'active' != $product->status ) {
					$this->template = 'general/product-inactive.php';
					return;
				}

				$membership = wds_get_product_meta( $product_id, 'membership_type' );
				if ( 'trial' == $membership || 'addon' == $membership ) {
					$this->template = 'general/product-invalid.php';
					return;
				}

				\WDS_Renew::instance()->prepare( $order );

				$is_invoice_exists = \WDS_Renew::instance()->invoice_exists();
				if ( $is_invoice_exists ) {
					wds_redirect( wds_url( 'pay', wds_encrypt_decrypt( $is_invoice_exists ) ) );
				}

				\WDS_Renew::instance()->load_data();
				break;

			case 'pay':
				$this->id       = 'pay';
				$this->target   = 'pay';
				$this->template = 'general/pay.php';
				$this->title    = wds_lang( 'trx_pay_title' );

				$invoice_id = wds_encrypt_decrypt( wds_get_current_invoice_slug(), 'decrypt' );
				$invoice    = wds_get_invoice( $invoice_id );

				if ( ! $invoice ) {
					$this->target   = 'both';
					$this->template = 'general/payment-notfound.php';
					return;
				}

				if ( 'cancelled' == $invoice->status ) {
					$product = wds_get_product( wds_invoice_summary( $invoice, 'product_id' ) );

					$wds_data['checkout'] = wds_url( 'checkout', $product->slug );

					$this->target   = 'both';
					$this->template = 'general/payment-cancelled.php';
					return;
				}

				if ( 'completed' == $invoice->status ) {
					wds_redirect( wds_url( 'thanks', wds_encrypt_decrypt( $invoice->ID ) ) );
				}

				if ( $invoice->total <= 0 ) {
					wds_update_invoice_status( $invoice->ID, 'completed' );
					wds_redirect( wds_url( 'thanks', wds_encrypt_decrypt( $invoice->ID ) ) );
				}

				$ord = wds_get_invoice_order( $invoice );

				$wds_data['invoice']  = $invoice;
				$wds_data['order_id'] = $ord->order_id;
				break;

			case 'thanks':
				$this->id       = 'thanks';
				$this->target   = 'both';
				$this->template = 'general/thanks.php';
				$this->title    = wds_lang( 'trx_thanks_title' );

				$invoice_id = wds_encrypt_decrypt( wds_get_current_invoice_slug(), 'decrypt' );
				$invoice    = wds_get_invoice( $invoice_id );

				if ( ! $invoice ) {
					$this->template = 'general/payment-notfound.php';
				}

				$url = wds_option( 'dashboard_link' );

				$type = wds_get_product_meta( wds_invoice_summary( $invoice, 'product_id' ), 'product_type' );
				if ( wds_is_digital() && 'digital' == $type ) {
					$url = wds_url( 'access' );
				}

				$wds_data['invoice'] = $invoice;
				$wds_data['url']     = $url;
				break;

			case 'reff_pro':
			case 'reff':
				$this->id       = 'reff';
				$this->target   = 'both';
				$this->template = '';
				$this->title    = '';

				$affiliate = false;
				$redirect  = wds_option( 'affiliate_redirect' );

				$user_vars = wds_get_vars( '__user_id' );
				if ( $user_vars ) {
					$user_id   = intval( $user_vars );
					$affiliate = get_userdata( $user_id );
					if ( $affiliate ) {
						wds_set_affiliate_cookie( $affiliate->user_login );
					}
				}

				$product = wds_get_product_by( 'slug', wds_get_current_product_slug() );
				if ( $product ) {
					$redirect = wds_url( 'checkout', $product->slug );
					if ( isset( $_GET['redirect'] ) ) {
						$salespage_url = wds_get_product_meta( $product->ID, 'salespage_url' );
						if ( 'salespage' == wds_sanitize_data_field( $_GET, 'redirect' ) && ! empty( $salespage_url ) ) {
							$redirect = esc_url( $salespage_url );
						}
					}
					if ( isset( $_GET['coupon'] ) ) {
						$coupon   = '?coupon=' . wds_sanitize_text_field( $_GET['coupon'] );
						$redirect = esc_url( $redirect . $coupon );
					}
				}

				$click = array(
					'product_id' => $product ? $product->ID : 'NULL',
					'uri'        => wds_sanitize_data_field( $_SERVER, 'REQUEST_URI' ),
					'referer'    => esc_url_raw( wp_get_raw_referer() ),
					'device'     => \WDS_User_Info::get_device(),
					'ip'         => \WDS_User_Info::get_ip(),
					'browser'    => \WDS_User_Info::get_browser(),
					'platform'   => \WDS_User_Info::get_platform(),
				);

				if ( $affiliate ) {
					$click['affiliate_id'] = $affiliate->ID;
				}

				wds_insert_affiliate( $click );

				wds_redirect( $redirect, 302, false );
				break;

			case 'share':
				$this->id       = 'share';
				$this->target   = 'public';
				$this->template = 'public/share.php';
				$this->title    = wds_lang( 'share' );

				$slug = wds_sanitize_data_field( $_GET, 'id', false );
				$host = wds_sanitize_data_field( $_GET, 'link', false ); // custom domain

				$cookie_id   = ! empty( $slug ) ? $slug : sanitize_title( $host );
				$cookie_name = 'data_share-' . $cookie_id;
				$cookie      = wds_sanitize_data_field( $_COOKIE, $cookie_name, false );
				$post_id     = '';
				$post_name   = '';
				$post_title  = '';
				$user_id     = '';

				if ( $slug ) {
					$post = get_page_by_path( $slug, OBJECT, 'post' );
					if ( is_object( $post ) ) {
						$ret = 'success';
						wds_set_cookie( $cookie_name, $post->post_name );
						$post_id    = $post->ID;
						$post_name  = $post->post_name;
						$post_title = get_post_field( 'post_title', $post_id );
						$user_id    = get_post_field( 'post_author', $post_id );
						$host       = home_url( $post_name . '/' );
					} else {
						$ret = 'incorret_param';
						wds_delete_cookie( $cookie_name );
					}
				} elseif ( $host ) {
					$ret = 'success';
				} elseif ( $cookie ) {
					$post = get_page_by_path( $cookie, OBJECT, 'post' );
					if ( is_object( $post ) ) {
						$ret = 'success';
					} else {
						$ret = 'empty_param';
						wds_delete_cookie( $cookie_name );
					}
				} else {
					$ret = 'empty_param';
				}

				$wds_data['share_display'] = $ret;
				if ( 'success' != $ret ) {
					return;
				}

				$logo_link     = ! empty( wds_user_meta( $user_id, '_branding_link' ) ) ? wds_user_meta( $user_id, '_branding_link' ) : home_url();
				$logo_src      = wds_user_meta( $user_id, '_branding_logo' );
				$branding_name = wds_user_meta( $user_id, '_branding_name' );
				$copyright     = ! empty( $branding_name ) ? 'Copyright © ' . gmdate( 'Y' ) . ' - ' . $branding_name : wds_option( 'copyright' );

				$wds_data['logo_link'] = $logo_link;
				$wds_data['logo_src']  = $logo_src;
				$wds_data['copyright'] = $copyright;

				$restrict_data = wds_post_meta( $post_id, '_restrict' );
				$is_restrict   = wds_check_array( $restrict_data, true );
				// $guest         = $is_restrict && isset( $restrict_data['guest'] ) ? wp_json_encode( $restrict_data['guest'] ) : '""';
				// $message       = $is_restrict && isset( $restrict_data['text'] ) ? wp_json_encode( $restrict_data['text'] ) : '""';
				$guest   = $is_restrict && isset( $restrict_data['guest'] ) ? $restrict_data['guest'] : '""';
				$message = $is_restrict && isset( $restrict_data['text'] ) ? $restrict_data['text'] : '""';

				$share_data   = wds_option( 'share_data' );
				$text_default = wds_check_array( $share_data, true ) ? $share_data[0]['text'] : '';
				$text_default = str_replace( array( '\r', '\r\n', '\n', '\n\n' ), '\n', $text_default );

				$bride_name = wds_post_meta( $post_id, '_nama_lengkap_wanita' );
				$groom_name = wds_post_meta( $post_id, '_nama_lengkap_pria' );
				$inviting   = wds_post_meta( $post_id, '_yang_mengundang' ) ? wds_post_meta( $post_id, '_yang_mengundang' ) : $post_title;

				if ( wds_is_theme() && wds_invitation_is_theme( $post_id ) ) {
					$meta_bride  = wds_post_meta( $post_id, '_section_bride' );
					$meta_groom  = wds_post_meta( $post_id, '_section_groom' );
					$meta_thanks = wds_post_meta( $post_id, '_section_thanks' );

					$bride_name = isset( $meta_bride['title'] ) ? $meta_bride['title'] : $bride_name;
					$groom_name = isset( $meta_groom['title'] ) ? $meta_groom['title'] : $groom_name;
					$inviting   = isset( $meta_thanks['textname'] ) ? $meta_thanks['textname'] : $inviting;
				}

				$data = array(
					'cookie_id'    => $cookie_id,
					'post_id'      => $post_id,
					'post_name'    => $post_name,
					'post_title'   => html_entity_decode( $post_title ),
					'user_id'      => $user_id,
					'is_restrict'  => $is_restrict,
					'guest'        => $guest,
					'message'      => $message,
					'invitation'   => $host,
					'text_default' => $text_default,
					'var_name'     => strtolower( str_replace( ' ', '-', wds_lang( 'public_share_var_name' ) ) ),
					'var_title'    => strtolower( str_replace( ' ', '-', wds_lang( 'public_share_var_title' ) ) ),
					'var_link'     => strtolower( str_replace( ' ', '-', wds_lang( 'public_share_var_link' ) ) ),
					'var_bride'    => strtolower( str_replace( ' ', '-', wds_lang( 'public_share_var_bride' ) ) ),
					'var_groom'    => strtolower( str_replace( ' ', '-', wds_lang( 'public_share_var_groom' ) ) ),
					'var_inviting' => strtolower( str_replace( ' ', '-', wds_lang( 'public_share_var_inviting' ) ) ),
					'_bride'       => html_entity_decode( $bride_name ),
					'_groom'       => html_entity_decode( $groom_name ),
					'_inviting'    => html_entity_decode( $inviting ),
				);

				$data['variable'] = '[' . $data['var_name'] . '], [' . $data['var_title'] . '], [' . $data['var_link'] . '], [' . $data['var_bride'] . '], [' . $data['var_groom'] . '], [' . $data['var_inviting'] . '] ';

				$wds_data['data_share'] = $data;
				break;

			case 'share/client':
				$this->id       = 'share-client';
				$this->target   = 'public';
				$this->template = 'public/share-client.php';
				$this->title    = wds_lang( 'share_client' );

				$cookie_name = 'data_share_client';

				$cookie = wds_sanitize_data_field( $_COOKIE, $cookie_name, false );
				$slug   = wds_sanitize_data_field( $_GET, 'id', false );
				if ( $slug ) {
					$post = get_page_by_path( $slug, OBJECT, 'post' );
					if ( is_object( $post ) ) {
						$ret = 'success';
						wds_set_cookie( $cookie_name, $post->post_name );
					} else {
						$ret = 'incorret_param';
						wds_delete_cookie( $cookie_name );
					}
				} elseif ( $cookie ) {
					$post = get_page_by_path( $cookie, OBJECT, 'post' );
					if ( is_object( $post ) ) {
						$ret = 'success';
					} else {
						$ret = 'empty_param';
						wds_delete_cookie( $cookie_name );
					}
				} else {
					$ret = 'empty_param';
				}

				$wds_data['share_display'] = $ret;
				if ( 'success' != $ret ) {
					return;
				}

				$post_id    = $post->ID;
				$post_name  = $post->post_name;
				$post_title = get_post_field( 'post_title', $post_id );
				$user_id    = get_post_field( 'post_author', $post_id );

				$logo_link     = ! empty( wds_user_meta( $user_id, '_branding_link' ) ) ? wds_user_meta( $user_id, '_branding_link' ) : home_url();
				$logo_src      = wds_user_meta( $user_id, '_branding_logo' );
				$branding_name = wds_user_meta( $user_id, '_branding_name' );
				$copyright     = ! empty( $branding_name ) ? 'Copyright © ' . gmdate( 'Y' ) . ' - ' . $branding_name : wds_option( 'copyright' );

				$wds_data['logo_link'] = $logo_link;
				$wds_data['logo_src']  = $logo_src;
				$wds_data['copyright'] = $copyright;

				$args = array(
					'invitation-link' => wds_invitation_open( $post_id ),
					'share-link'      => wds_url( 'share', $post_id ),
					'rsvp-link'       => wds_url( 'public_rsvp', $post_id ),
					'rsvp-password'   => wds_option( 'rsvp_password' ),
				);

				$message = wds_email_replace_shortcode( wds_option( 'share_client_reseller' ), $args );

				$wds_data['share_message'] = $message;
				break;
		}
	}
}
