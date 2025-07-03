<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * System server markup.
 */
function wds_system_server() {
	global $wpdb;

	$systemservers = array();

	$check                = array( 'title' => __( 'Operating System', 'wds-notrans' ) );
	$os                   = PHP_OS;
	$check['alert']       = 'success';
	$check['data']        = '<span class="alert--grey">' . $os . '</span>';
	$check['description'] = '';
	$systemservers['os']  = $check;

	$check                     = array( 'title' => __( 'Software', 'wds-notrans' ) );
	$software                  = wds_sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) );
	$check['alert']            = 'success';
	$check['data']             = '<span class="alert--grey">' . $software . '</span>';
	$check['description']      = '';
	$systemservers['software'] = $check;

	$check       = array( 'title' => __( 'MySQL Version', 'wds-notrans' ) );
	$sql_version = $wpdb->get_results( "SHOW VARIABLES WHERE `Variable_name` IN ( 'version_comment', 'innodb_version' )", OBJECT_K );
	$mysql       = $sql_version['version_comment']->Value . ' v' . $sql_version['innodb_version']->Value;
	if ( $mysql ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $mysql . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'Not detected', 'wds-notrans' ) . '</span>';
		$check['description'] = '<p class="description">' . __( 'Sorry, we were unable to detect the MySQL version on your server.', 'wds-notrans' ) . '</p>';
	}
	$systemservers['sql'] = $check;

	$check      = array( 'title' => __( 'PHP Version', 'wds-notrans' ) );
	$phpversion = phpversion();
	if ( function_exists( 'phpversion' ) ) {
		if ( version_compare( $phpversion, '7.4', '>=' ) ) {
			$check['alert']       = 'success';
			$check['data']        = '<span class="alert--grey">' . $phpversion . '</span>';
			$check['description'] = '';
		} else {
			$check['alert']       = 'warning';
			$check['data']        = '<span class="alert--warning">' . $phpversion . '</span>';
			$check['description'] = '<p class="description">' . __( 'We recommend to use php 7.4 or higher.', 'wds-notrans' ) . '</p>';
		}
	} else {
		$check['alert']       = 'danger';
		$check['data']        = '<span class="alert--danger">' . __( 'Not detected', 'wds-notrans' ) . '</span>';
		$check['description'] = '<p class="description">' . __( 'Sorry, we were unable to detect the PHP version on your server.', 'wds-notrans' ) . '</p>';
	}
	$systemservers['phpver'] = $check;

	$check       = array( 'title' => __( 'cURL Version', 'wds-notrans' ) );
	$curlversion = curl_version();
	if ( $curlversion ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $curlversion['version'] . ', ' . $curlversion['ssl_version'] . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'Not available', 'wds-notrans' ) . '</span>';
		$check['description'] = '<p class="description">' . __( 'Sorry, we were unable to detect the cUrl version on your server.', 'wds-notrans' ) . '</p>';
	}
	$systemservers['curl'] = $check;

	$check     = array( 'title' => __( 'PHP Max Input Vars', 'wds-notrans' ) );
	$inputvars = ini_get( 'max_input_vars' );
	if ( $inputvars ) {
		if ( $inputvars > 999 ) {
			$check['alert']       = 'success';
			$check['data']        = '<span class="alert--grey">' . $inputvars . '</span>';
			$check['description'] = '';
		} else {
			$check['alert']       = 'warning';
			$check['data']        = '<span class="alert--warning">' . $inputvars . '</span>';
			$check['description'] = '<p class="description">' . sprintf(
			/* translators: %s: reference link */
				__( 'We recommend setting PHP max_input_vars to a minimum of 1000 and above. See: <a href="%s" target="_blank">Increasing the PHP max vars limit</a>', 'wds-notrans' ),
				'https://wp-staging.com/increase-php-max-input-vars-limit-in-wordpress/'
			) . '</p>';
		}
	} else {
		$check['alert']       = 'danger';
		$check['data']        = '<span class="alert--danger">' . $inputvars . '</span>';
		$check['description'] = '';
	}
	$systemservers['maxinput'] = $check;

	$check       = array( 'title' => __( 'PHP Max Execution Time', 'wds-notrans' ) );
	$maxexectime = ini_get( 'max_execution_time' );
	if ( $maxexectime > 15 ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $maxexectime . '</span>';
		$check['description'] = '';
	} elseif ( $maxexectime < 5 ) {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $maxexectime . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'danger';
		$check['data']        = '<span class="alert--danger">' . $maxexectime . '</span>';
		$check['description'] = '';
	}
	$systemservers['maxexecut'] = $check;

	$check       = array( 'title' => __( 'PHP Post Max Size', 'wds-notrans' ) );
	$postmaxsize = ini_get( 'post_max_size' );
	if ( $postmaxsize ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $postmaxsize . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $postmaxsize . '</span>';
		$check['description'] = '';
	}
	$systemservers['postmax'] = $check;

	$check     = array( 'title' => __( 'GD Library', 'wds-notrans' ) );
	$gdlibrary = extension_loaded( 'gd' ) ? true : false;
	if ( $gdlibrary ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . __( 'Available', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'Not available', 'wds-notrans' ) . '</span>';
		$check['description'] = '<p class="description">' . __( 'GD Library is required so that WordPress can resize images uploaded to the website.', 'wds-notrans' ) . '</p>';
	}
	$systemservers['gdlib'] = $check;

	$check = array( 'title' => __( 'ZIP Installed', 'wds-notrans' ) );
	$zip   = extension_loaded( 'zip' ) ? true : false;
	if ( $zip ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . __( 'Detected', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'Not detected', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	}
	$systemservers['zip'] = $check;

	if ( strpos( strtolower( $software ), 'nginx' ) === false ) {
		$check = array( 'title' => __( 'Mod Rewrite', 'wds-notrans' ) );
		if ( function_exists( 'apache_get_modules' ) ) {
			$modrewrite = in_array( 'mod_rewrite', apache_get_modules() );
		} else {
			$modrewrite = false;
		}
		if ( $modrewrite ) {
			$check['alert']       = 'success';
			$check['data']        = '<span class="alert--grey">' . __( 'All right', 'wds-notrans' ) . '</span>';
			$check['description'] = '';
		} else {
			$check['alert']       = 'warning';
			$check['data']        = '<span class="alert--warning">' . __( 'Not all right', 'wds-notrans' ) . '</span>';
			$check['description'] = '<p class="description">' . __( 'Please ignore this message if your WordPress website continues to function properly.', 'wds-notrans' ) . '</p>';
		}
		$systemservers['modrew'] = $check;
	}

	$check     = array( 'title' => __( 'Secure Connection(HTTPS)', 'wds-notrans' ) );
	$ssl_check = 'https' === substr( get_home_url(), 0, 5 );
	if ( $ssl_check ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . __( 'Available', 'wds-notrans' ) . '</span>';
		$check['description'] = '<p class="description">' . __( 'Your site is using secure connection (HTTPS).', 'wds-notrans' ) . '</p>';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'Not available', 'wds-notrans' ) . '</span>';
		$check['description'] = '<p class="description">' . __( 'Your site is not using secure connection (HTTPS).', 'wds-notrans' ) . '</p>';
	}
	$systemservers['ssl'] = $check;

	return $systemservers;
}

/**
 * System wp markup.
 */
function wds_system_wp() {
	global $wp_rewrite;

	$systemwps = array();

	$check     = array( 'title' => __( 'WordPress Version', 'wds-notrans' ) );
	$wpversion = get_bloginfo( 'version' );
	if ( version_compare( $wpversion, '6.3', '>=' ) ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $wpversion . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $wpversion . '</span>';
		$check['description'] = '<p class="description">' . __( 'We recommend you to immediately update your WordPress with the latest version.', 'wds-notrans' ) . '</p>';
	}
	$systemwps['wpver'] = $check;

	$check                = array( 'title' => __( 'Home URL', 'wds-notrans' ) );
	$homeurl              = get_home_url();
	$check['alert']       = 'success';
	$check['data']        = '<span class="alert--grey">' . $homeurl . '</span>';
	$check['description'] = '';
	$systemwps['homeurl'] = $check;

	$check   = array( 'title' => __( 'Site URL', 'wds-notrans' ) );
	$siteurl = get_site_url();
	if ( $siteurl === $homeurl ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $siteurl . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $siteurl . '</span>';
		$check['description'] = '';
	}
	$systemwps['siteurl'] = $check;

	$check     = array( 'title' => __( 'WordPress Multisite', 'wds-notrans' ) );
	$multisite = is_multisite() ? true : false;
	if ( ! $multisite ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . __( 'No', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'Yes', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	}
	$systemwps['multisite'] = $check;

	$check         = array( 'title' => __( 'Max Upload Size', 'wds-notrans' ) );
	$maxuploadsize = wp_max_upload_size();
	$maxupload     = size_format( $maxuploadsize );
	if ( $maxuploadsize > 10000000 ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $maxupload . '</span>';
		$check['description'] = '';
	} elseif ( $maxuploadsize > 2500000 ) {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $maxupload . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $maxupload . '</span>';
		$check['description'] = '';
	}
	$systemwps['maxupload'] = $check;

	$check         = array( 'title' => __( 'PHP Memory Limit', 'wds-notrans' ) );
	$phpmemory     = ini_get( 'memory_limit' );
	$phpmemory_num = str_replace( 'M', '', $phpmemory );
	if ( $phpmemory_num > 0 && $phpmemory_num >= 64 ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $phpmemory . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $phpmemory . '</span>';
		$check['description'] = '<p class="description">' . sprintf(
		/* translators: %s: reference link */
			__( 'We recommend setting PHP memory limit to a minimum of 64M and above, <span class="success">recommended 256M</span>, so that all features run well. <a href="%s" target="_blank">More info</a>', 'wds-notrans' ),
			'https://www.atatus.com/blog/the-php-memory_limit-what-you-need-to-know-about-increasing-it/'
		) . '</p>';
	}
	$systemwps['phpmemory'] = $check;

	$check        = array( 'title' => __( 'WordPress Memory Limit', 'wds-notrans' ) );
	$wpmemory     = WP_MEMORY_LIMIT;
	$wpmemory_num = str_replace( 'M', '', $wpmemory );
	if ( $wpmemory_num > 0 && $wpmemory_num >= 64 ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $wpmemory . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $wpmemory . '</span>';
		$check['description'] = '<p class="description">' . sprintf(
		/* translators: %s: reference link */
			__( 'We recommend setting WP memory limit to a minimum of 64M and above, <span class="success">recommended 256M</span>, so that all features run well. <a href="%s" target="_blank">More info</a>', 'wds-notrans' ),
			'https://wpmet.com/how-to-increase-wordpress-memory-limit/'
		) . '</p>';
	}
	$systemwps['wpmemory'] = $check;

	$check     = array( 'title' => __( 'Permalink Structure', 'wds-notrans' ) );
	$permalink = $wp_rewrite->permalink_structure;
	if ( $permalink ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . $permalink . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . $permalink . '</span>';
		$check['description'] = '';
	}
	$systemwps['permalink'] = $check;

	$check                 = array( 'title' => __( 'Language', 'wds-notrans' ) );
	$language              = get_bloginfo( 'language' );
	$check['alert']        = 'success';
	$check['data']         = '<span class="alert--grey">' . $language . '</span>';
	$check['description']  = '';
	$systemwps['language'] = $check;

	$check    = array( 'title' => __( 'Timezone', 'wds-notrans' ) );
	$timezone = get_option( 'timezone_string' );
	if ( ! $timezone ) {
		$gmt_offset = get_option( 'gmt_offset' );
		if ( $gmt_offset > 0 ) {
			$timezone = 'UTC+' . $gmt_offset;
		} elseif ( $gmt_offset < 0 ) {
			$timezone = 'UTC' . $gmt_offset;
		} else {
			$timezone = 'UTC';
		}
	}
	$check['alert']        = 'success';
	$check['data']         = '<span class="alert--grey">' . $timezone . '</span>';
	$check['description']  = '';
	$systemwps['timezone'] = $check;

	$check                   = array( 'title' => __( 'Admin Email', 'wds-notrans' ) );
	$adminemail              = get_option( 'admin_email' );
	$check['alert']          = 'success';
	$check['data']           = '<span class="alert--grey">' . $adminemail . '</span>';
	$check['description']    = '';
	$systemwps['adminemail'] = $check;

	$check   = array( 'title' => __( 'WordPress Debug Mode', 'wds-notrans' ) );
	$wpdebug = WP_DEBUG ? true : false;
	if ( ! $wpdebug ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . __( 'Inactive', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'Active', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	}
	$systemwps['wpdebug'] = $check;

	return $systemwps;
}

/**
 * System theme markup.
 */
function wds_system_theme() {
	$systemthemes = array();

	$check                     = array( 'title' => __( 'Theme Name', 'wds-notrans' ) );
	$themename                 = wp_get_theme();
	$check['alert']            = 'success';
	$check['data']             = '<span class="alert--grey">' . $themename . '</span>';
	$check['description']      = '';
	$systemthemes['themename'] = $check;

	$check                    = array( 'title' => __( 'Theme Version', 'wds-notrans' ) );
	$themeversion             = wp_get_theme()->get( 'Version' );
	$check['alert']           = 'success';
	$check['data']            = '<span class="alert--grey">' . $themeversion . '</span>';
	$check['description']     = '';
	$systemthemes['themever'] = $check;

	$check                     = array( 'title' => __( 'Theme Author', 'wds-notrans' ) );
	$themeauthor               = wp_get_theme()->get( 'Author' );
	$check['alert']            = 'success';
	$check['data']             = '<span class="alert--grey">' . $themeauthor . '</span>';
	$check['description']      = '';
	$systemthemes['themeauth'] = $check;

	$check                  = array( 'title' => __( 'Text Domain', 'wds-notrans' ) );
	$themetextdomain        = wp_get_theme()->get( 'TextDomain' );
	$check['alert']         = 'success';
	$check['data']          = '<span class="alert--grey">' . $themetextdomain . '</span>';
	$check['description']   = '';
	$systemthemes['textdo'] = $check;

	$check       = array( 'title' => __( 'Child Theme', 'wds-notrans' ) );
	$themeachild = is_child_theme();
	if ( $themeachild ) {
		$check['alert']       = 'success';
		$check['data']        = '<span class="alert--grey">' . __( 'Yes', 'wds-notrans' ) . '</span>';
		$check['description'] = '';
	} else {
		$check['alert']       = 'warning';
		$check['data']        = '<span class="alert--warning">' . __( 'No', 'wds-notrans' ) . '</span>';
		$check['description'] = '<p class="description">' . sprintf(
		/* translators: %s: reference link */
			__( 'If you want to modify the source code of your theme, we recommend using a <a href="%s" target="_blank">child theme</a>.', 'wds-notrans' ),
			'https://developer.wordpress.org/themes/advanced-topics/child-themes/'
		) . '</p>';
	}
	$systemthemes['themechild'] = $check;

	if ( $themeachild ) {
		$check                      = array( 'title' => __( 'Parent Theme Name', 'wds-notrans' ) );
		$themeparentname            = wp_get_theme()->parent();
		$check['alert']             = 'success';
		$check['data']              = '<span class="alert--grey">' . $themeparentname . '</span>';
		$check['description']       = '';
		$systemthemes['parentname'] = $check;

		$check                     = array( 'title' => __( 'Parent Theme Version', 'wds-notrans' ) );
		$themeparentversion        = wp_get_theme()->parent()->get( 'Version' );
		$check['alert']            = 'success';
		$check['data']             = '<span class="alert--grey">' . $themeparentversion . '</span>';
		$check['description']      = '';
		$systemthemes['parentver'] = $check;

		$check                      = array( 'title' => __( 'Parent Theme Author', 'wds-notrans' ) );
		$themeparentauthor          = wp_get_theme()->parent()->get( 'Author' );
		$check['alert']             = 'success';
		$check['data']              = '<span class="alert--grey">' . $themeparentauthor . '</span>';
		$check['description']       = '';
		$systemthemes['parentauth'] = $check;
	}

	return $systemthemes;
}

/**
 * System icon markup.
 *
 * @param string $alert The color icon.
 */
function wds_system_icon( $alert ) {
	if ( 'success' == $alert ) {
		$icon = 'dashicons dashicons-thumbs-up';
	} elseif ( 'warning' == $alert ) {
		$icon = 'dashicons dashicons-warning';
	} elseif ( 'danger' == $alert ) {
		$icon = 'dashicons dashicons-dismiss';
	} else {
		$icon = 'dashicons dashicons-yes';
	}

	return $icon;
}
