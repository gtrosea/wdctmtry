<?php

namespace WDS\Frontend\Ajax;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Account Class.
 */
class Account {

	/**
	 * Update profile form action.
	 */
	public static function update_profile_form() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$name  = wds_sanitize_data_field( $post, 'fullname' );
		$phone = wds_sanitize_data_field( $post, 'phone' );
		$check = wds_user_phone();

		// Check Existing WhatsApp
		if ( wds_phone_country_code( $phone ) != $check ) {
			if ( wds_check_existing_phone( $phone ) ) {
				wp_send_json_error( wds_lang( 'phone_exist' ) );
			}
		}

		// WhatsApp validation
		$wcheck = \WDS\Engine\Tools\Validation::whatsapp( $phone );
		if ( 'not_registered' == $wcheck ) {
			wp_send_json_error( wds_lang( 'phone_invalid' ) );
		}

		$data = array(
			'first_name' => $name,
			'_phone'     => wds_phone_country_code( $phone ),
		);

		foreach ( $data as $key => $val ) {
			update_user_meta( $user_id, wp_unslash( $key ), wp_unslash( $val ) );
		}

		wds_delete_cache_user( $user_id );
		wp_send_json_success( wds_lang( 'account_settings_profile_success' ) );
	}

	/**
	 * Update reseller form action.
	 */
	public static function update_reseller_form() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		if ( isset( $_FILES['logo'] ) && ! empty( $_FILES['logo']['tmp_name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			// Validation
			$file_info     = wp_check_filetype( $_FILES['logo']['name'] );
			$allowed_types = array( 'jpg', 'jpeg', 'png', 'webp', 'gif' );

			if ( ! in_array( $file_info['ext'], $allowed_types ) ) {
				wp_send_json_error( __( 'Invalid file type.', 'wds-notrans' ) );
			}

			// Upload to media library
			$attachment_id = media_handle_upload( 'logo', 0 );

			if ( is_wp_error( $attachment_id ) ) {
				wp_send_json_error( $attachment_id->get_error_message() );
			} else {
				// Delete Old Logo
				$logo_old = wds_user_meta( $user_id, '_branding_logo_id' );
				if ( ! empty( $logo_old ) ) {
					wp_delete_attachment( $logo_old, true );
				}

				// Save New Logo
				$attachment_url = wp_get_attachment_url( $attachment_id );
				update_user_meta( $user_id, '_branding_logo', $attachment_url );
				update_user_meta( $user_id, '_branding_logo_id', $attachment_id );
			}
		}

		$data = array(
			'_branding_name'        => wds_sanitize_data_field( $post, 'brand_name' ),
			'_branding_link'        => wds_sanitize_data_field( $post, 'link' ),
			'_branding_description' => wds_sanitize_data_field( $post, 'description' ),
			'_instagram'            => wds_sanitize_data_field( $post, 'instagram' ),
			'_facebook'             => wds_sanitize_data_field( $post, 'facebook' ),
			'_tiktok'               => wds_sanitize_data_field( $post, 'tiktok' ),
			'_twitter'              => wds_sanitize_data_field( $post, 'twitter' ),
			'_youtube'              => wds_sanitize_data_field( $post, 'youtube' ),
			'_invitation_price'     => wds_sanitize_data_field( $post, 'invitation_price' ),
		);

		foreach ( $data as $key => $val ) {
			update_user_meta( $user_id, wp_unslash( $key ), wp_unslash( $val ) );
		}

		wds_delete_cache_user( $user_id );
		wp_send_json_success( wds_lang( 'account_settings_profile_success' ) );
	}

	/**
	 * Change email form action.
	 */
	public static function change_email_form() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$email    = wds_sanitize_data_field( $post, 'emailaddress' );
		$password = wds_sanitize_data_field( $post, 'confirmemailpassword' );

		if ( wp_check_password( $password, wds_user_password( $user_id ), $user_id ) ) {
			$echeck = \WDS\Engine\Tools\Validation::email( $email );
			if ( 'email_disabled' == $echeck ) {
				wp_send_json_error( wds_lang( 'email_disabled' ) );
			}

			$updated = wp_update_user(
				array(
					'ID'         => $user_id,
					'user_email' => wp_unslash( $email ),
				)
			);

			if ( is_wp_error( $updated ) ) {
				wp_send_json_error( wds_lang( 'account_settings_email_error' ) );
			} else {
				wds_delete_cache_user( $user_id );
				wp_send_json_success( wds_lang( 'account_settings_email_success' ) );
			}
		} else {
			wp_send_json_error( wds_lang( 'account_settings_email_password' ) );
		}
	}

	/**
	 * Change password form action.
	 */
	public static function change_password_form() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$password    = wds_sanitize_data_field( $post, 'password' );
		$newpassword = wds_sanitize_data_field( $post, 'pass1' );

		if ( wp_check_password( $password, wds_user_password( $user_id ), $user_id ) ) {
			if ( strlen( $newpassword ) >= 6 ) {
				wp_set_password( $newpassword, $user_id );
				wp_send_json_success( wds_lang( 'account_settings_password_success' ) );
			} else {
				wp_send_json_error( wds_lang( 'account_settings_password_error' ) );
			}
		} else {
			wp_send_json_error( wds_lang( 'password_current_error' ) );
		}
	}

	/**
	 * Generate affiliate link action.
	 */
	public static function generate_referral_link() {
		global $wpdb;

		$prefix  = $wpdb->prefix . WDS_MODEL;
		$post    = $_POST;
		$user_id = get_current_user_id();

		$product_id = intval( wds_sanitize_data_field( $post, 'product' ) );
		$product    = wds_get_product( $product_id );

		if ( $product ) {
			$query = \WDS\Models\Coupon::right_join( WDS_MODEL . '_coupon_code', array( WDS_MODEL . '_coupon.ID', WDS_MODEL . '_coupon_code.coupon_id', '=' ) )
			->select(
				$prefix . '_coupon.*',
				$prefix . '_coupon_code.user_id',
				$prefix . '_coupon_code.code'
			)->query( "WHERE status = 'active' AND user_id IS NULL AND is_private != 1" )->get();

			$coupons = array();
			foreach ( $query as $coupon ) {
				$user_code = wds_get_coupon_code( $coupon->ID, $user_id );
				if ( $user_code ) {
					$coupons[] = $user_code;
				}
			}

			$coupon_aff    = isset( $coupons[0] ) ? '?coupon=' . $coupons[0] : '';
			$salespage_url = wds_affiliate_link( $user_id, $product_id ) . '?redirect=salespage';
			$checkout_url  = wds_affiliate_link( $user_id, $product_id ) . $coupon_aff;

			wp_send_json_success(
				array(
					'salespage_url' => $salespage_url,
					'checkout_url'  => $checkout_url,
				),
			);
		} else {
			wp_send_json_error( wds_lang( 'account_ref_generate_error' ) );
		}
	}

	/**
	 * Save withdraw method action.
	 */
	public static function save_widthdraw_method() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$data = array(
			'_affiliate_payment_method'         => wds_sanitize_data_field( $post, 'method' ),
			'_affiliate_payment_method_account' => wds_sanitize_data_field( $post, 'account' ),
		);

		foreach ( $data as $key => $val ) {
			update_user_meta( $user_id, wp_unslash( $key ), wp_unslash( $val ) );
		}

		wds_delete_cache_user( $user_id );
		wp_send_json_success( wds_lang( 'account_ref_alert_save_wd' ) );
	}

	/**
	 * Save coupon action.
	 */
	public static function save_coupon() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$coupon_id = intval( wds_sanitize_data_field( $post, 'coupon_id' ) );
		$code      = wds_sanitize_data_field( $post, 'coupon_input' );

		if ( empty( $code ) ) {
			wp_send_json_error( wds_lang( 'account_ref_alert_coupon_empty' ) );
		} elseif ( strlen( $code ) < 4 ) {
			wp_send_json_error( wds_lang( 'account_ref_alert_coupon_length' ) );
		}

		$check_default_code = wds_get_coupon_code_by_code( $code, null, true );
		if ( $check_default_code > 0 ) {
			wp_send_json_error( wds_lang( 'account_ref_alert_coupon_default' ) );
		}

		$check_same_code = wds_get_coupon_code_by_code( $code, $user_id, true );
		if ( $check_same_code > 0 ) {
			wp_send_json_error( wds_lang( 'account_ref_alert_coupon_same' ) );
		}

		$code_other_user = wds_get_coupon_code_by_code( $code, $user_id, true, true );
		if ( $code_other_user > 0 ) {
			wp_send_json_error( wds_lang( 'account_ref_alert_coupon_taken' ) );
		}

		$code_id = wds_get_coupon_code( $coupon_id, $user_id, true );
		$updated = wds_update_coupon_code( $coupon_id, intval( $code_id ), $code, $user_id );

		if ( is_wp_error( $updated ) ) {
			wp_send_json_error( $updated->get_error_message() );
		} else {
			wp_send_json_success( wds_lang( 'account_ref_alert_coupon_success' ) );
		}
	}

	/**
	 * Delete coupon action.
	 */
	public static function delete_coupon() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$code_id = wds_get_coupon_code_by_code( wds_sanitize_data_field( $post, 'coupon_input' ), $user_id, true );
		if ( $code_id ) {
			$deleted = wds_delete_coupon_code( $code_id );
			if ( $deleted ) {
				wp_send_json_success( wds_lang( 'account_ref_alert_coupon_success_delete' ) );
			}
		}

		wp_send_json_success( wds_lang( 'account_ref_alert_coupon_failed_delete' ) );
	}
}
