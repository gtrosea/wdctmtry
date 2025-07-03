<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Get frontend data.
 */
function wds_frontend_data() {
	$page = 'index.php?__wds_page';

	$data = array(
		// Admin
		'admin/affiliate'             => array(
			'regex' => '^admin/affiliate/?$',
			'query' => $page . '=admin/affiliate',
		),
		'admin/affiliate-payouts'     => array(
			'regex' => '^admin/affiliate-payouts/?$',
			'query' => $page . '=admin/affiliate-payouts',
		),
		'admin/statistics'            => array(
			'regex' => '^admin/statistics/?$',
			'query' => $page . '=admin/statistics',
		),
		'admin/users'                 => array(
			'regex' => '^admin/users/?$',
			'query' => $page . '=admin/users',
		),

		// Auth
		'auth/login'                  => array(
			'regex' => '^auth/login/?$',
			'query' => $page . '=auth/login',
		),
		'auth/lost-password'          => array(
			'regex' => '^auth/lost-password/?$',
			'query' => $page . '=auth/lost-password',
		),
		'auth/reset-password'         => array(
			'regex' => '^auth/reset-password/?$',
			'query' => $page . '=auth/reset-password',
		),
		'auth/verify'                 => array(
			'regex' => '^auth/verify/?$',
			'query' => $page . '=auth/verify',
		),

		// Account
		'account/overview'            => array(
			'regex' => '^account/overview/?$',
			'query' => $page . '=account/overview',
		),
		'account/settings'            => array(
			'regex' => '^account/settings/?$',
			'query' => $page . '=account/settings',
		),
		'account/transactions'        => array(
			'regex' => '^account/transactions/?$',
			'query' => $page . '=account/transactions',
		),
		'account/referrals'           => array(
			'regex' => '^account/referrals/?$',
			'query' => $page . '=account/referrals',
		),

		// Dashboard
		'dashboard/invitation'        => array(
			'regex' => '^dashboard/invitation/?$',
			'query' => $page . '=dashboard/invitation',
		),
		'dashboard/invitation/create' => array(
			'regex' => '^dashboard/invitation/create/?$',
			'query' => $page . '=dashboard/invitation/create',
		),
		'dashboard/invitation/edit'   => array(
			'regex' => '^dashboard/invitation/edit/?$',
			'query' => $page . '=dashboard/invitation/edit',
		),
		'dashboard/invitation/rsvp'   => array(
			'regex' => '^dashboard/invitation/rsvp/?$',
			'query' => $page . '=dashboard/invitation/rsvp',
		),
		'dashboard/client'            => array(
			'regex' => '^dashboard/client/?$',
			'query' => $page . '=dashboard/client',
		),
		'dashboard/marketing'         => array(
			'regex' => '^dashboard/marketing/?$',
			'query' => $page . '=dashboard/marketing',
		),
		'dashboard/landingpage'       => array(
			'regex' => '^dashboard/landingpage/?$',
			'query' => $page . '=dashboard/landingpage',
		),
		'dashboard/landingpage/edit'  => array(
			'regex' => '^dashboard/landingpage/edit/?$',
			'query' => $page . '=dashboard/landingpage/edit',
		),
		'dashboard/upgrade'           => array(
			'regex' => '^dashboard/upgrade/?$',
			'query' => $page . '=dashboard/upgrade',
		),
		'dashboard/access'            => array(
			'regex' => '^dashboard/access/?$',
			'query' => $page . '=dashboard/access',
		),

		// General
		'checkout'                    => array(
			'regex' => '^checkout/([^/]+)/?$',
			'query' => $page . '=checkout&__product=$matches[1]',
		),
		'renew'                       => array(
			'regex' => '^renew/([^/]+)/?$',
			'query' => $page . '=renew&__order=$matches[1]',
		),
		'pay'                         => array(
			'regex' => '^pay/([^/]+)/?$',
			'query' => $page . '=pay&__invoice=$matches[1]',
		),
		'thanks'                      => array(
			'regex' => '^thanks/([^/]+)/?$',
			'query' => $page . '=thanks&__invoice=$matches[1]',
		),
		'reff_pro'                    => array(
			'regex' => '^reff/([^/]+)/([^/]+)/?$',
			'query' => $page . '=reff&__user_id=$matches[1]&__product=$matches[2]',
		),
		'reff'                        => array(
			'regex' => '^reff/([^/]+)/?$',
			'query' => $page . '=reff&__user_id=$matches[1]',
		),
		'share'                       => array(
			'regex' => '^share/?$',
			'query' => $page . '=share',
		),
		'share/client'                => array(
			'regex' => '^share/client/?$',
			'query' => $page . '=share/client',
		),

		// Public
		'public/rsvp'                 => array(
			'regex' => '^public/rsvp/?$',
			'query' => $page . '=public/rsvp',
		),
	);

	return apply_filters( 'wds_frontend_data', $data );
}

/**
 * Get frontend custome color.
 */
function wds_frontend_js_variables() {
	$required = ' ' . wds_lang( 'required' );

	$login_redirect         = wds_url( 'overview' );
	$redirect_login_setting = wds_option( 'login_redirect' );
	if ( $redirect_login_setting ) {
		$login_redirect = $redirect_login_setting;
	}
	if ( isset( $_GET['redirect'] ) ) {
		$login_redirect = home_url( wds_sanitize_text_field( $_GET['redirect'] ) );
	}
	if ( wds_data( 'wds_custome_host' ) ) {
		$login_redirect = 'https://' . wds_sanitize_data_field( $_SERVER, 'HTTP_HOST' ) . $redirect_login_setting;
	}

	$data = array(
		'ajax_url'   => admin_url( 'admin-ajax.php' ),
		'ajax_nonce' => wp_create_nonce( 'wds_nonce' ),
		'page_name'  => wds_is_page(),
		'blog_info'  => get_bloginfo( 'name' ),
		'is_login'   => is_user_logged_in() ? 'yes' : 'no',
		'text'       => array(
			'ok_go'  => wds_lang( 'ok_go' ),
			'copied' => wds_lang( 'copied' ),
			'error'  => array(
				'input'                         => wds_lang( 'input_error' ),
				'input_field'                   => wds_lang( 'input_error_field' ),
				'email_required'                => wds_lang( 'email' ) . $required,
				'email_invalid'                 => wds_lang( 'email_invalid' ),
				'password_required'             => wds_lang( 'password' ) . $required,
				'password_invalid'              => wds_lang( 'password_invalid' ),
				'password_confirm_required'     => wds_lang( 'password_confirm' ) . $required,
				'password_confirm_error'        => wds_lang( 'password_confirm_error' ),
				'password_current_required'     => wds_lang( 'password_current' ) . $required,
				'password_new_required'         => wds_lang( 'password_new' ) . $required,
				'password_new_confirm_required' => wds_lang( 'password_new_confirm' ) . $required,
				'name_required'                 => wds_lang( 'fullname' ) . $required,
				'phone_required'                => wds_lang( 'phone' ) . $required,
				'generate'                      => wds_lang( 'account_ref_generate_error' ),
				'payment_method'                => wds_lang( 'payment_method' ) . $required,
				'payment_method_account'        => wds_lang( 'payment_method_account' ) . $required,
				'sk'                            => wds_lang( 'trx_checkout_alert_sk' ),
			),
		),
		'inv'        => array(
			'layout'          => wds_data( 'layout' ),
			'templates_count' => wds_templates_count() > 1 ? 'true' : 'false',
			'activate_text1'  => wds_lang( 'dash_invitation_activate_tittle' ),
			'activate_text2'  => wds_lang( 'dash_invitation_activate_yes' ),
			'activate_text3'  => wds_lang( 'dash_invitation_activate_no' ),
			'activate_text4'  => wds_lang( 'dash_invitation_activate_failed' ),
			'extend_text1'    => wds_lang( 'dash_invitation_extend_tittle' ),
			'extend_text2'    => wds_lang( 'dash_invitation_extend_yes' ),
			'extend_text3'    => wds_lang( 'dash_invitation_extend_no' ),
			'extend_text4'    => wds_lang( 'dash_invitation_extend_failed' ),
			'theme_reload'    => wds_engine( 'tema_reload' ) ? 'yes' : 'no',
			'theme_notfound'  => wds_lang( 'dash_invitation_theme_notfound' ),
			'max_upload_mp3'  => wds_engine( 'audio_custom_max' ),
			'preview'         => wds_lang( 'dash_invitation_preview' ),
			'use'             => wds_lang( 'dash_invitation_use' ),
		),
		'url'        => array(
			'loginRedirect' => $login_redirect,
			'login'         => wds_url( 'login' ),
			'settings'      => wds_url( 'settings' ),
			'landingpage'   => wds_url( 'landingpage' ),
			'upgrade'       => wds_url( 'upgrade' ),
		),
		'sk'         => wds_option( 'sk' ) ? 'active' : 'inactive',
		'inv_date'   => wds_get_invoices_start_date(),
		'leads'      => __( 'Leads', 'wds-notrans' ),
		'sales'      => __( 'Sales', 'wds-notrans' ),
		'conversion' => __( 'Conversion', 'wds-notrans' ),
		'time'       => array(
			'all'       => __( 'All', 'wds-notrans' ),
			'today'     => __( 'Today', 'wds-notrans' ),
			'yesterday' => __( 'Yesterday', 'wds-notrans' ),
			'last7d'    => __( 'Last 7 Days', 'wds-notrans' ),
			'last30d'   => __( 'Last 30 Days', 'wds-notrans' ),
			'tm'        => __( 'This Month', 'wds-notrans' ),
			'lm'        => __( 'Last Month', 'wds-notrans' ),
			'ty'        => __( 'This Year', 'wds-notrans' ),
			'ly'        => __( 'Last Year', 'wds-notrans' ),
		),
		'public'     => array(
			'rsvp_password' => wds_option( 'rsvp_password' ) . '^_^',
			'text_rsvp1'    => wds_lang( 'public_rsvp_empty_pass' ),
			'text_rsvp2'    => wds_lang( 'public_rsvp_incorrect_pass' ),
			'text_rsvp3'    => wds_lang( 'public_rsvp_success_pass' ),
			'text_share1'   => wds_lang( 'public_share_copy_link_success' ),
			'text_share2'   => wds_lang( 'public_share_copy_link_failed' ),
			'text_share3'   => wds_lang( 'public_share_copy_msg_success' ),
			'text_share4'   => wds_lang( 'public_share_copy_msg_failed' ),
		),
	);

	return apply_filters( 'wds_localize_script_data', $data );
}

/**
 * Get frontend custome color.
 */
function wds_frontend_custome_color() {
	$default_color = wds_option( 'default_color' );
	$default_hover = wds_option( 'default_active_color' );

	$primary_color       = $default_color['primary'];
	$primary_hover_color = $default_hover['primary'];

	$success_color       = $default_color['success'];
	$success_hover_color = $default_hover['success'];

	$info_color       = $default_color['info'];
	$info_hover_color = $default_hover['info'];

	$warning_color       = $default_color['warning'];
	$warning_hover_color = $default_hover['warning'];

	$danger_color       = $default_color['danger'];
	$danger_hover_color = $default_hover['danger'];

	$topbar_color = wds_option( 'topbar_color' );
	$menu_color   = wds_option( 'menu_color' );

	list($r1, $g1, $b1) = sscanf( $primary_hover_color, '#%02x%02x%02x' );
	$primary_rgb        = "$r1, $g1, $b1";

	list($r1, $g1, $b1) = sscanf( $success_hover_color, '#%02x%02x%02x' );
	$success_rgb        = "$r1, $g1, $b1";

	list($r1, $g1, $b1) = sscanf( $info_hover_color, '#%02x%02x%02x' );
	$info_rgb           = "$r1, $g1, $b1";

	list($r1, $g1, $b1) = sscanf( $warning_hover_color, '#%02x%02x%02x' );
	$warning_rgb        = "$r1, $g1, $b1";

	list($r1, $g1, $b1) = sscanf( $danger_hover_color, '#%02x%02x%02x' );
	$danger_rgb         = "$r1, $g1, $b1";

	echo '<style>
    :root,
    [data-bs-theme="light"] {
        --bs-primary: ' . esc_attr( $primary_color ) . ';
        --bs-link-color: ' . esc_attr( $primary_color ) . ';
        --bs-link-color: ' . esc_attr( $primary_color ) . ';
        --bs-link-hover-color: ' . esc_attr( $primary_hover_color ) . ';
        --bs-success: ' . esc_attr( $success_color ) . ';
        --bs-info: ' . esc_attr( $info_color ) . ';
        --bs-warning: ' . esc_attr( $warning_color ) . ';
        --bs-danger: ' . esc_attr( $danger_color ) . ';
        --bs-form-valid-color: ' . esc_attr( $success_color ) . ';
        --bs-form-valid-border-color: ' . esc_attr( $success_color ) . ';
        --bs-form-invalid-color: ' . esc_attr( $danger_color ) . ';
        --bs-form-invalid-border-color: ' . esc_attr( $danger_color ) . ';
    }

    [data-bs-theme="light"] {
        --bs-primary: ' . esc_attr( $primary_color ) . ';
        --bs-text-primary: ' . esc_attr( $primary_color ) . ';
        --bs-component-active-bg: ' . esc_attr( $primary_color ) . ';
        --bs-component-hover-color: ' . esc_attr( $primary_color ) . ';
        --bs-component-checked-bg: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-hover: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-show: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-here: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-active: ' . esc_attr( $primary_color ) . ';
        --bs-ribbon-label-bg: ' . esc_attr( $primary_color ) . ';
        --bs-primary-light: ' . esc_attr( $primary_color ) . '12;
        --bs-primary-active: ' . esc_attr( $primary_hover_color ) . ';
        --bs-primary-rgb: ' . esc_attr( $primary_rgb ) . ';
        --bs-success: ' . esc_attr( $success_color ) . ';
        --bs-text-success: ' . esc_attr( $success_color ) . ';
        --bs-success-light: ' . esc_attr( $success_color ) . '12;
        --bs-success-active: ' . esc_attr( $success_hover_color ) . ';
        --bs-success-rgb: ' . esc_attr( $success_rgb ) . ';
        --bs-info: ' . esc_attr( $info_color ) . ';
        --bs-text-info: ' . esc_attr( $info_color ) . ';
        --bs-info-light: ' . esc_attr( $info_color ) . '12;
        --bs-info-active: ' . esc_attr( $info_hover_color ) . ';
        --bs-info-rgb: ' . esc_attr( $info_rgb ) . ';
        --bs-warning: ' . esc_attr( $warning_color ) . ';
        --bs-text-warning: ' . esc_attr( $warning_color ) . ';
        --bs-warning-light: ' . esc_attr( $warning_color ) . '12;
        --bs-warning-active: ' . esc_attr( $warning_hover_color ) . ';
        --bs-warning-rgb: ' . esc_attr( $warning_rgb ) . ';
        --bs-danger: ' . esc_attr( $danger_color ) . ';
        --bs-text-danger: ' . esc_attr( $danger_color ) . ';
        --bs-danger-light: ' . esc_attr( $danger_color ) . '12;
        --bs-danger-active: ' . esc_attr( $danger_hover_color ) . ';
        --bs-danger-rgb: ' . esc_attr( $danger_rgb ) . ';
    }

    [data-bs-theme="dark"] {
        --bs-primary: ' . esc_attr( $primary_color ) . ';
        --bs-text-primary: ' . esc_attr( $primary_color ) . ';
        --bs-component-active-bg: ' . esc_attr( $primary_color ) . ';
        --bs-component-hover-color: ' . esc_attr( $primary_color ) . ';
        --bs-component-checked-bg: ' . esc_attr( $primary_color ) . ';
        --bs-scrolltop-bg-color: ' . esc_attr( $primary_color ) . ';
        --bs-scrolltop-bg-color-hover: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-hover: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-show: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-here: ' . esc_attr( $primary_color ) . ';
        --bs-menu-link-color-active: ' . esc_attr( $primary_color ) . ';
        --bs-ribbon-label-bg: ' . esc_attr( $primary_color ) . ';
        --bs-primary-light: ' . esc_attr( $primary_color ) . '12;
        --bs-primary-active: ' . esc_attr( $primary_hover_color ) . ';
        --bs-primary-rgb: ' . esc_attr( $primary_rgb ) . ';
        --bs-success: ' . esc_attr( $success_color ) . ';
        --bs-text-success: ' . esc_attr( $success_color ) . ';
        --bs-success-light: ' . esc_attr( $success_color ) . '12;
        --bs-success-active: ' . esc_attr( $success_hover_color ) . ';
        --bs-success-rgb: ' . esc_attr( $success_rgb ) . ';
        --bs-info: ' . esc_attr( $info_color ) . ';
        --bs-text-info: ' . esc_attr( $info_color ) . ';
        --bs-info-light: ' . esc_attr( $info_color ) . '12;
        --bs-info-active: ' . esc_attr( $info_hover_color ) . ';
        --bs-info-rgb: ' . esc_attr( $info_rgb ) . ';
        --bs-warning: ' . esc_attr( $warning_color ) . ';
        --bs-text-warning: ' . esc_attr( $warning_color ) . ';
        --bs-warning-light: ' . esc_attr( $warning_color ) . '12;
        --bs-warning-active: ' . esc_attr( $warning_hover_color ) . ';
        --bs-warning-rgb: ' . esc_attr( $warning_rgb ) . ';
        --bs-danger: ' . esc_attr( $danger_color ) . ';
        --bs-text-danger: ' . esc_attr( $danger_color ) . ';
        --bs-danger-light: ' . esc_attr( $danger_color ) . '12;
        --bs-danger-active: ' . esc_attr( $danger_hover_color ) . ';
        --bs-danger-rgb: ' . esc_attr( $danger_rgb ) . ';
    }

    .form-check-input:checked {
        background-color: ' . esc_attr( $primary_color ) . ';
        border-color: ' . esc_attr( $primary_color ) . ';
    }

    .form-check-input[type="checkbox"]:indeterminate {
        background-color: ' . esc_attr( $primary_color ) . ';
        border-color: ' . esc_attr( $primary_color ) . ';
    }

    .form-range::-webkit-slider-thumb {
        background-color: ' . esc_attr( $primary_color ) . ';
    }

    .form-range::-moz-range-thumb {
        background-color: ' . esc_attr( $primary_color ) . ';
    }

    .btn-primary {
        --bs-btn-bg: ' . esc_attr( $primary_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $primary_color ) . ';
        --bs-btn-disabled-bg: ' . esc_attr( $primary_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $primary_color ) . ';
    }

    .btn-outline-primary {
        --bs-btn-color: ' . esc_attr( $primary_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $primary_color ) . ';
        --bs-btn-hover-bg: ' . esc_attr( $primary_color ) . ';
        --bs-btn-hover-border-color: ' . esc_attr( $primary_color ) . ';
        --bs-btn-active-bg: ' . esc_attr( $primary_color ) . ';
        --bs-btn-active-border-color: ' . esc_attr( $primary_color ) . ';
        --bs-btn-disabled-color: ' . esc_attr( $primary_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $primary_color ) . ';
    }

    .btn-success {
        --bs-btn-bg: ' . esc_attr( $success_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $success_color ) . ';
        --bs-btn-disabled-bg: ' . esc_attr( $success_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $success_color ) . ';
    }
    
    .btn-outline-success {
        --bs-btn-color: ' . esc_attr( $success_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $success_color ) . ';
        --bs-btn-hover-bg: ' . esc_attr( $success_color ) . ';
        --bs-btn-hover-border-color: ' . esc_attr( $success_color ) . ';
        --bs-btn-active-bg: ' . esc_attr( $success_color ) . ';
        --bs-btn-active-border-color: ' . esc_attr( $success_color ) . ';
        --bs-btn-disabled-color: ' . esc_attr( $success_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $success_color ) . ';
    }

    .btn-info {
        --bs-btn-bg: ' . esc_attr( $info_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $info_color ) . ';
        --bs-btn-disabled-bg: ' . esc_attr( $info_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $info_color ) . ';
    }
    
    .btn-outline-info {
        --bs-btn-color: ' . esc_attr( $info_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $info_color ) . ';
        --bs-btn-hover-bg: ' . esc_attr( $info_color ) . ';
        --bs-btn-hover-border-color: ' . esc_attr( $info_color ) . ';
        --bs-btn-active-bg: ' . esc_attr( $info_color ) . ';
        --bs-btn-active-border-color: ' . esc_attr( $info_color ) . ';
        --bs-btn-disabled-color: ' . esc_attr( $info_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $info_color ) . ';
    }

    .btn-warning {
        --bs-btn-bg: ' . esc_attr( $warning_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $warning_color ) . ';
        --bs-btn-disabled-bg: ' . esc_attr( $warning_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $warning_color ) . ';
    }
    
    .btn-outline-warning {
        --bs-btn-color: ' . esc_attr( $warning_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $warning_color ) . ';
        --bs-btn-hover-bg: ' . esc_attr( $warning_color ) . ';
        --bs-btn-hover-border-color: ' . esc_attr( $warning_color ) . ';
        --bs-btn-active-bg: ' . esc_attr( $warning_color ) . ';
        --bs-btn-active-border-color: ' . esc_attr( $warning_color ) . ';
        --bs-btn-disabled-color: ' . esc_attr( $warning_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $warning_color ) . ';
    }

    .btn-danger {
        --bs-btn-bg: ' . esc_attr( $danger_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $danger_color ) . ';
        --bs-btn-disabled-bg: ' . esc_attr( $danger_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $danger_color ) . ';
    }
    
    .btn-outline-danger {
        --bs-btn-color: ' . esc_attr( $danger_color ) . ';
        --bs-btn-border-color: ' . esc_attr( $danger_color ) . ';
        --bs-btn-hover-bg: ' . esc_attr( $danger_color ) . ';
        --bs-btn-hover-border-color: ' . esc_attr( $danger_color ) . ';
        --bs-btn-active-bg: ' . esc_attr( $danger_color ) . ';
        --bs-btn-active-border-color: ' . esc_attr( $danger_color ) . ';
        --bs-btn-disabled-color: ' . esc_attr( $danger_color ) . ';
        --bs-btn-disabled-border-color: ' . esc_attr( $danger_color ) . ';
    }

    .nav-pills {
        --bs-nav-pills-link-active-bg: ' . esc_attr( $primary_color ) . ';
    }

    .progress,
    .progress-stacked {
        --bs-progress-bar-bg: ' . esc_attr( $primary_color ) . ';
    }

    .separator.separator-content.border-primary::after,
    .separator.separator-content.border-primary::before {
        border-color: ' . esc_attr( $primary_color ) . ' !important;
    }

    .form-check.form-check-solid .form-check-input[type="checkbox"]:indeterminate {
        background-color: ' . esc_attr( $primary_color ) . ';
    }

    [data-kt-sticky-landing-header="on"] .landing-header .menu .menu-link.active {
        color: ' . esc_attr( $primary_color ) . ';
    }

    @media (max-width: 991.98px) {
        .landing-header .menu .menu-link.active {
            color: ' . esc_attr( $primary_color ) . ';
        }
    }

    .separator.separator-content.border-success::after,
    .separator.separator-content.border-success::before {
        border-color: ' . esc_attr( $success_color ) . ' !important
    }
    
    .separator.separator-content.border-info::after,
    .separator.separator-content.border-info::before {
        border-color: ' . esc_attr( $info_color ) . ' !important
    }
    
    .separator.separator-content.border-warning::after,
    .separator.separator-content.border-warning::before {
        border-color: ' . esc_attr( $warning_color ) . ' !important
    }
    
    .separator.separator-content.border-danger::after,
    .separator.separator-content.border-danger::before {
        border-color: ' . esc_attr( $danger_color ) . ' !important
    }

    .explore-btn-outline.active,
    .explore-btn-outline:hover {
        border: 1px dashed ' . esc_attr( $success_color ) . ' !important;
    }
    .explore-icon-success {
        color: ' . esc_attr( $success_color ) . ';
    }

    .explore-label-pro {
        background-color: ' . esc_attr( $success_color ) . ';
    }
    
    .explore-icon-danger {
        color: ' . esc_attr( $danger_color ) . ';
    }

    .header {
        background-color: ' . esc_attr( $topbar_color ) . ';
    }

	#kt_aside_toggle i {
		color: ' . esc_attr( $menu_color ) . ';
	}
    </style>';
}
