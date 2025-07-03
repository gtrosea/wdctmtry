<?php
/**
 * WeddingSaas Frontend Ajax.
 *
 * Handles the frontend ajax for the WeddingSaas plugin.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Frontend
 */

namespace WDS\Frontend;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 *  Ajax Class.
 */
class Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		include_once 'ajax/admin.php';
		include_once 'ajax/auth.php';
		include_once 'ajax/account.php';
		include_once 'ajax/dashboard.php';
		include_once 'ajax/general.php';
		include_once 'ajax/public.php';

		add_action( 'wp_ajax_run_wds', array( $this, 'init' ) );
		add_action( 'wp_ajax_nopriv_run_wds', array( $this, 'init' ) );
	}

	/**
	 * Handle AJAX requests.
	 */
	public function init() {
		if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
			wp_send_json_error( __( 'Invalid request method.', 'wds-notrans' ) );
		}

		if ( ! isset( $_POST['__nonce'] ) ) {
			wp_send_json_error( __( 'Missing security information.', 'wds-notrans' ) );
		}

		if ( ! wp_verify_nonce( $_POST['__nonce'], 'wds_nonce' ) ) {
			wp_send_json_error( __( 'Nonce verification failed.', 'wds-notrans' ) );
		}

		$name = wds_sanitize_data_field( $_REQUEST, 'name' );

		switch ( $name ) {
			// Admin
			case 'admin_payouts':
				Ajax\Admin::payouts();
				break;
			case 'admin_get_stats':
				Ajax\Admin::get_stats();
				break;
			case 'admin_get_stats_chart':
				Ajax\Admin::get_stats_chart();
				break;
			case 'admin_get_income':
				Ajax\Admin::get_income();
				break;
			case 'admin_get_top_product':
				Ajax\Admin::get_top_product();
				break;
			case 'admin_get_user_statistic':
				Ajax\Admin::get_user_statistic();
				break;
			case 'admin_get_users':
				Ajax\Admin::get_users();
				break;

			// Auth
			case 'login':
				Ajax\Auth::login();
				break;
			case 'lostpass':
				Ajax\Auth::lost_password();
				break;
			case 'resetpass':
				Ajax\Auth::reset_password();
				break;

			// Account
			case 'profile_form':
				Ajax\Account::update_profile_form();
				break;
			case 'reseller_form':
				Ajax\Account::update_reseller_form();
				break;
			case 'change_email_form':
				Ajax\Account::change_email_form();
				break;
			case 'change_password_form':
				Ajax\Account::change_password_form();
				break;
			case 'generate_referral_link':
				Ajax\Account::generate_referral_link();
				break;
			case 'save_widthdraw_method':
				Ajax\Account::save_widthdraw_method();
				break;
			case 'save_coupon':
				Ajax\Account::save_coupon();
				break;
			case 'delete_coupon':
				Ajax\Account::delete_coupon();
				break;

			// Dashboard
			case 'invitation_terms_callback':
				Ajax\Dashboard::invitation_terms_callback();
				break;
			case 'invitation_add':
				Ajax\Dashboard::invitation_add();
				break;
			case 'invitation_activate':
				Ajax\Dashboard::invitation_activate();
				break;
			case 'invitation_extend':
				Ajax\Dashboard::invitation_extend();
				break;
			case 'invitation_get_category':
				Ajax\Dashboard::invitation_get_category();
				break;
			case 'invitation_get_subcategory':
				Ajax\Dashboard::invitation_get_subcategory();
				break;
			case 'invitation_get_theme':
				Ajax\Dashboard::invitation_get_theme();
				break;
			case 'invitation_get_subtheme':
				Ajax\Dashboard::invitation_get_subtheme();
				break;
			case 'invitation_edit_theme':
				Ajax\Dashboard::invitation_edit_theme();
				break;
			case 'invitation_edit_audio':
				Ajax\Dashboard::invitation_edit_audio();
				break;
			case 'invitation_get_subtheme':
				Ajax\Dashboard::invitation_get_subtheme();
				break;
			case 'client_add':
				Ajax\Dashboard::client_add();
				break;
			case 'landingpage_add':
				Ajax\Dashboard::landingpage_add();
				break;
			case 'landingpage_update':
				Ajax\Dashboard::landingpage_update();
				break;
			case 'landingpage_delete':
				Ajax\Dashboard::landingpage_delete();
				break;

			// Checkout
			case 'checkout_check_email':
				\WDS_Checkout::instance()->ajax_check_email();
				break;
			case 'checkout_check_phone':
				\WDS_Checkout::instance()->ajax_check_phone();
				break;
			case 'checkout_update_summary':
				\WDS_Checkout::instance()->ajax_update_summary();
				break;
			case 'checkout_update_refered':
				\WDS_Checkout::instance()->ajax_update_refered();
				break;
			case 'checkout_change_addon':
				\WDS_Checkout::instance()->ajax_change_addon();
				break;
			case 'checkout_change_gateway':
				\WDS_Checkout::instance()->ajax_change_gateway();
				break;
			case 'checkout_apply_coupon':
				\WDS_Checkout::instance()->ajax_apply_coupon();
				break;
			case 'checkout_process_data':
				\WDS_Checkout::instance()->ajax_process_data();
				break;

			// Renew
			case 'renew_update_summary':
				\WDS_Renew::instance()->ajax_update_summary();
				break;
			case 'renew_update_refered':
				\WDS_Renew::instance()->ajax_update_refered();
				break;
			case 'renew_change_gateway':
				\WDS_Renew::instance()->ajax_change_gateway();
				break;
			case 'renew_process_data':
				\WDS_Renew::instance()->ajax_process_data();
				break;

			// Public
			case 'share_get_message':
				Ajax\Publics::share_get_message();
				break;
			case 'share_process_data':
				Ajax\Publics::share_process_data();
				break;

			default:
				wp_send_json_error( __( 'Unknown action type.', 'wds-notrans' ) );
		}
	}
}

new Ajax();
