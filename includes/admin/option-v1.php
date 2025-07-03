<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// wp_cache_delete( WDS_SLUG . '_v1' );

// $options = get_option( WDS_SLUG . '_v1' );
// $options = json_decode( $options, true );

// wds_log( $options, true );

/**
 * Get option data from cache or database.
 *
 * Fetch the option data from the cache first, if it is not in the cache,
 * data will be taken from the database, then stored in the cache.
 *
 * @param string $key The specific option key to retrieve. If empty, the default value is returned.
 * @param mixed  $default The default value to return if the option is not found.
 * @return mixed The value of the option if it exists, or the default value if not found.
 */
function wds_v1_option( $key = '', $default = false ) {
	if ( empty( $key ) ) {
		return $default;
	}

	$cache_key = WDS_SLUG . '_v1';

	$options = wp_cache_get( $cache_key );
	if ( false === $options ) {
		$options = get_option( $cache_key, array() );
		if ( ! empty( $options ) ) {
			$options = json_decode( $options, true );
			wp_cache_set( $cache_key, $options );
		}
	}

	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/**
 * Addon.
 */
function wds_v1_addon() {
	$options = wds_v1_option( 'weddingsaas_addon' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'title' => isset( $option['title'] ) ? $option['title'] : '',
				'id'    => isset( $option['id'] ) ? $option['id'] : '',
				'price' => isset( $option['price'] ) ? $option['price'] : '',
				'link'  => isset( $option['link'] ) ? $option['link'] : '',
			);
		}
	}

	return $data;
}

/**
 * Affiliate hide.
 */
function wds_v1_affiliate_hide() {
	$options = wds_v1_option( 'referral_hide' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Restrict post type.
 */
function wds_v1_restrict() {
	$options = wds_v1_option( 'restrict_post_types' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Iframe post type.
 */
function wds_v1_iframe() {
	$options = wds_v1_option( 'iframe_post_types' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Invitation edit v1.
 */
function wds_v1_editv1() {
	$options = wds_v1_option( 'invitation_edit_shortcode' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'category'  => isset( $option['category'] ) ? $option['category'] : '',
				'shortcode' => isset( $option['shortcode'] ) ? $option['shortcode'] : '',
			);
		}
	}

	return $data;
}

/**
 * Invitation edit v2.
 */
function wds_v1_editv2() {
	$options = wds_v1_option( 'invitation_edit_shortcode_v2' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$title  = isset( $option['title'] ) ? $option['title'] : '';
			$note   = isset( $option['note'] ) ? $option['note'] : '';
			$note   = empty( $note ) ? $title : $title . ' (' . $note . ')';
			$data[] = array(
				'note'      => $note,
				'title'     => $title,
				'icon'      => isset( $option['icon'] ) ? $option['icon'] : '',
				'shortcode' => isset( $option['shortcode'] ) ? $option['shortcode'] : '',
				'category'  => isset( $option['category'] ) ? $option['category'] : array(),
				'subtheme'  => isset( $option['subtheme'] ) ? $option['subtheme'] : array(),
				'product'   => isset( $option['product'] ) ? $option['product'] : array(),
			);
		}
	}

	return $data;
}

/**
 * Share.
 */
function wds_v1_share() {
	$options = wds_v1_option( 'share_text' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'title' => isset( $option['title'] ) ? $option['title'] : '',
				'text'  => isset( $option['text'] ) ? $option['text'] : '',
			);
		}
	}

	return $data;
}

/**
 * Client product.
 */
function wds_v1_client_product() {
	$options = wds_v1_option( 'client_product' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Reseller Hide.
 */
function wds_v1_reseller_hide() {
	$options = wds_v1_option( 'reseller_hide' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Marketing Kit.
 */
function wds_v1_marketingkit() {
	$options = wds_v1_option( 'marketing_menu_data' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'icon'  => isset( $option['icon'] ) ? $option['icon'] : '',
				'title' => isset( $option['title'] ) ? $option['title'] : '',
				'desc'  => isset( $option['description'] ) ? $option['description'] : '',
				'url'   => isset( $option['url'] ) ? $option['url'] : '',
			);
		}
	}

	return $data;
}

/**
 * Upgrade Member.
 */
function wds_v1_upgrade_member() {
	$options = wds_v1_option( 'membership_upgrade' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Upgrade Reseller.
 */
function wds_v1_upgrade_reseller() {
	$options = wds_v1_option( 'membership_upgrade_reseller' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Upgrade Quota.
 */
function wds_v1_upgrade_quota() {
	$options = wds_v1_option( 'membership_upgrade_quota' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = $option;
		}
	}

	return $data;
}

/**
 * Bank transfer.
 */
function wds_v1_banktransfer() {
	$options = wds_v1_option( 'payment_gateway_banktransfer_bank' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'name'           => isset( $option['bank_name'] ) ? $option['bank_name'] : '',
				'name_input'     => isset( $option['bank_name_input'] ) ? $option['bank_name_input'] : '',
				'account_name'   => isset( $option['bank_account_name'] ) ? $option['bank_account_name'] : '',
				'account_number' => isset( $option['bank_account_number'] ) ? $option['bank_account_number'] : '',
			);
		}
	}

	return $data;
}

/**
 * Get language.
 *
 * @param string $key The key name.
 * @param string $default The default value.
 */
function wds_v1_lang( $key, $default = false ) {
	return wds_v1_option( 'lang_' . $key, $default );
}

/**
 * Theme Testimoni.
 */
function wds_v1_theme_testimoni() {
	$options = wds_v1_option( 'fe_testimoni' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'name'  => isset( $option['nama'] ) ? $option['nama'] : '',
				'city'  => isset( $option['kota'] ) ? $option['kota'] : '',
				'image' => isset( $option['gambar'] ) ? $option['gambar'] : '',
				'text'  => isset( $option['isi'] ) ? $option['isi'] : '',
			);
		}
	}

	return $data;
}

/**
 * Theme FAQ.
 */
function wds_v1_theme_faq() {
	$options = wds_v1_option( 'fe_faq_list' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'title' => isset( $option['title'] ) ? $option['title'] : '',
				'desc'  => isset( $option['desc'] ) ? $option['desc'] : '',
			);
		}
	}

	return $data;
}

/**
 * Theme Work Hours.
 */
function wds_v1_theme_work() {
	$options = wds_v1_option( 'fe_footer_work' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'day'  => isset( $option['hari'] ) ? $option['hari'] : '',
				'time' => isset( $option['jam'] ) ? $option['jam'] : '',
			);
		}
	}

	return $data;
}

/**
 * Menu data.
 */
function wds_v1_engine_menu() {
	$options = wds_v1_option( 'wds_menu' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'title'   => isset( $option['name'] ) ? $option['name'] : '',
				'icon'    => isset( $option['icon'] ) ? $option['icon'] : '',
				'url'     => isset( $option['url'] ) ? $option['url'] : '',
				'new_tab' => isset( $option['new_tab'] ) ? $option['new_tab'] : '',
				'group'   => isset( $option['user_group'] ) ? $option['user_group'] : array(),
				'product' => isset( $option['product'] ) ? $option['product'] : array(),
			);
		}
	}

	return $data;
}

/**
 * Alert data.
 */
function wds_v1_engine_alert() {
	$options = wds_v1_option( 'alert_dashboard' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'title'   => isset( $option['title'] ) ? $option['title'] : '',
				'style'   => isset( $option['class'] ) ? $option['class'] : '',
				'message' => isset( $option['message'] ) ? $option['message'] : '',
				'group'   => isset( $option['user_group'] ) ? $option['user_group'] : array(),
				'product' => isset( $option['product'] ) ? $option['product'] : array(),
			);
		}
	}

	return $data;
}

/**
 * Auto insert data.
 */
function wds_v1_engine_insert() {
	$options = wds_v1_option( 'wds_auto_insert_data' );

	$data = array();
	if ( wds_check_array( $options, true ) ) {
		foreach ( $options as $option ) {
			$data[] = array(
				'category' => isset( $option['category'] ) ? $option['category'] : '',
				'data'     => isset( $option['data'] ) ? $option['data'] : array(),
			);
		}
	}

	return $data;
}
