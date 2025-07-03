<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves the name of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user name.
 */
function wds_user_name( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user = get_userdata( intval( $user_id ) );
	if ( empty( $user ) ) {
		return false;
	}

	$full_name = $user->first_name;
	if ( empty( $full_name ) ) {
		$full_name = $user->display_name;
	}

	return $full_name;
}

/**
 * Retrieves the email address of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user email address.
 */
function wds_user_email( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user = get_userdata( intval( $user_id ) );
	if ( empty( $user ) ) {
		return false;
	}

	return $user->user_email;
}

/**
 * Retrieves the userlogin of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user login.
 */
function wds_user_login( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user = get_userdata( intval( $user_id ) );
	if ( empty( $user ) ) {
		return false;
	}

	return $user->user_login;
}

/**
 * Retrieves the password of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user password.
 */
function wds_user_password( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user = get_userdata( intval( $user_id ) );
	if ( empty( $user ) ) {
		return false;
	}

	return $user->user_pass;
}

/**
 * Retrieves the avatar of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @param int       $size The avatar size.
 * @return string The user avatar url.
 */
function wds_user_avatar( $user_id = false, $size = 96 ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return get_avatar_url( $user_id, $size );
}

/**
 * Retrieve the phone number of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user phone number metadata.
 */
function wds_user_phone( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_phone' );
}

/**
 * Retrieve the status of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user status metadata.
 */
function wds_user_status( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_wds_user_status' );
}

/**
 * Retrieve the group of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user group metadata.
 */
function wds_user_group( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_wds_user_group' );
}

/**
 * Retrieve the active period of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @param bool      $original The data original or convert.
 * @return string The user active period metadata.
 */
function wds_user_active_period( $user_id = false, $original = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$period = wds_user_meta( $user_id, '_wds_user_active_period' );

	if ( $original ) {
		if ( ! empty( $period ) ) {
			$ret = $period;
		} else {
			$ret = false;
		}

		return $ret;
	}

	if ( ! empty( $period ) ) {
		$ret = date_i18n( get_option( 'date_format' ), $period );
	} else {
		$ret = wds_lang( 'lifetime' );
	}

	return $ret;
}

/**
 * Retrieve the membership of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user membership metadata.
 */
function wds_user_membership( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$membership = wds_user_meta( $user_id, '_wds_user_membership' );

	return ! empty( $membership ) ? $membership : '-';
}

/**
 * Retrieve the order id of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user order id metadata.
 */
function wds_user_order_id( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_wds_order_id' );
}

/**
 * Retrieve the invitation quota of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user invitation quota metadata.
 */
function wds_user_invitation_quota( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$quota = wds_user_meta( $user_id, '_wds_invitation_quota' );

	return ! empty( $quota ) ? intval( $quota ) : 0;
}

/**
 * Retrieve the client quota of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user client quota metadata.
 */
function wds_user_client_quota( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$quota = wds_user_meta( $user_id, '_wds_client_quota' );

	return ! empty( $quota ) ? intval( $quota ) : 0;
}

/**
 * Retrieve the invitation duration of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user invitation duration metadata.
 */
function wds_user_invitation_duration( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$duration = '';
	$meta     = wds_user_meta( $user_id, '_wds_invitation_duration' );
	if ( $meta ) {
		$period = wds_user_meta( $user_id, '_wds_invitation_period' );
		if ( 'day' == $period ) {
			$tipe = wds_lang( 'day' );
		} elseif ( 'month' == $period ) {
			$tipe = wds_lang( 'month' );
		} else {
			$tipe = wds_lang( 'year' );
		}
		$duration = $meta . ' ' . $tipe;
	} else {
		$duration = wds_lang( 'lifetime' );
	}

	return $duration;
}

/**
 * Retrieve the invitation action of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user invitation action metadata.
 */
function wds_user_invitation_action( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_wds_invitation_action' );
}

/**
 * Retrieve the affiliate status of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user affiliate status metadata.
 */
function wds_user_affiliate_status( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_affiliate_status' );
}

/**
 * Retrieve the custom affiliate commission of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user custom affiliate commission metadata.
 */
function wds_user_affiliate_commission( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_affiliate_commission' );
}

/**
 * Retrieve the payment method of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user payment method metadata.
 */
function wds_user_payment_method( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_affiliate_payment_method' );
}

/**
 * Retrieve the payment method account of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user payment method account metadata.
 */
function wds_user_payment_method_account( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return wds_user_meta( $user_id, '_affiliate_payment_method_account' );
}

/**
 * Retrieve the affiliate link of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user affiliate link.
 */
function wds_user_affiliate_link( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return site_url() . '/reff/' . intval( $user_id ) . '/';
}

/**
 * Retrieve the layout of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user layout metadata.
 */
function wds_user_layout( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$default = wds_option( 'invitation_list_layout' );
	$default = '1' == $default ? 'table' : 'grid';
	$layout  = wds_user_meta( $user_id, '_layout' );

	return ! empty( $layout ) ? $layout : $default;
}

/**
 * Retrieve the total client of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return int The user total client.
 */
function wds_user_total_client( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user_id = intval( $user_id );

	$total = WDS\Models\Client::query( 'WHERE reseller_id = %d', $user_id )->count();

	return $total;
}

/**
 * Retrieve the total income of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return int The user total income.
 */
function wds_user_total_income( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user_id = intval( $user_id );

	$total = WDS\Models\Income::where( 'user_id', $user_id, '=' )->sum( 'price' );

	return wds_convert_money( $total );
}

/**
 * Retrieve the total comment of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return int The user total comment.
 */
function wds_user_total_comment( $user_id = false ) {
	global $wpdb;

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user_id = intval( $user_id );

	$query = $wpdb->prepare(
		"SELECT SUM(comment_count) 
        FROM {$wpdb->posts} 
        WHERE post_author = %d
        AND post_status = 'publish'
        AND post_type = 'post'
        GROUP BY post_author",
		$user_id
	);

	$total = $wpdb->get_var( $query ); // phpcs:ignore

	return ! empty( $total ) ? $total : 0;
}

/**
 * Retrieve the storage of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @param bool      $format Whether to format the output. Defaults to true.
 * @return int|string The user storage in MB (formatted or raw).
 */
function wds_user_storage( $user_id = false, $format = true ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user_id = intval( $user_id );

	$cache_key     = 'wds_user_storage_' . $user_id;
	$total_size_mb = wp_cache_get( $cache_key, 'wds_user_storage' );

	if ( false === $total_size_mb ) {
		$args = array(
			'author'         => $user_id,
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		);

		$query = new WP_Query( $args );

		$total_size_bytes = 0;

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$file_path = get_attached_file( get_the_ID() );
				if ( file_exists( $file_path ) ) {
					$file_size         = filesize( $file_path );
					$total_size_bytes += $file_size;
				}
			}

			wp_reset_postdata();
		}

		// Convert to MB
		$total_size_mb = $total_size_bytes / 1048576;
		wp_cache_set( $cache_key, $total_size_mb, 'wds_user_storage', HOUR_IN_SECONDS );
	}

	if ( ! $format ) {
		return $total_size_mb;
	}

	$format = number_format( $total_size_mb, 3, ',', '.' );

	return $format;
}

/**
 * Retrieve the account activation of a user.
 *
 * @param int|false $user_id The ID of the user. Defaults to the current user if not provided.
 * @return string The user membership metadata.
 */
function wds_is_account_verified( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$activation_time = get_option( 'account_activation_time' );
	if ( ! $activation_time ) {
		return true;
	}

	$user = get_userdata( $user_id );
	$time = $user->user_registered;

	if ( strtotime( $time ) < strtotime( $activation_time ) ) {
		return true;
	} else {
		$is_verified = wds_user_meta( $user_id, '_is_verified' );

		return (bool) $is_verified;
	}
}

/**
 * Get users count.
 *
 * @param string|false $key The key of user count.
 * @return int The count of user.
 */
function wds_user_count( $key = false ) {
	$users = count_users();

	$count = $users['total_users'];

	if ( $key ) {
		switch ( $key ) {
			case 'trial':
				$key   = '_wds_user_group';
				$value = 'trial';
				break;

			case 'member':
				$key   = '_wds_user_group';
				$value = 'member';
				break;

			case 'reseller':
				$key   = '_wds_user_group';
				$value = 'reseller';
				break;

			case 'active':
				$key   = '_wds_user_status';
				$value = 'active';
				break;
		}

		$args = array(
			'meta_query' => array(
				array(
					'key'     => $key,
					'value'   => $value,
					'compare' => '=',
				),
			),
		);

		$users = get_users( $args );

		$count = count( $users );
	}

	return $count;
}

/**
 * Check phone validation.
 *
 * @param string $phone The phone number.
 * @return bool The validation phone number.
 */
function wds_check_existing_phone( $phone ) {
	if ( empty( $phone ) ) {
		return false;
	}

	$existing_user = get_users(
		array(
			'meta_key'   => '_phone',
			'meta_value' => wds_phone_country_code( $phone ),
		)
	);

	if ( ! empty( $existing_user ) ) {
		return true;
	}

	return false;
}

/**
 * Phone country code.
 *
 * @param string $phone The phone number.
 */
function wds_phone_country_code( $phone ) {
	if ( substr( $phone, 0, 1 ) === '0' ) {
		$phone = '62' . substr( $phone, 1 );
	}

	return $phone;
}

/**
 * Get all list user group.
 *
 * @param bool $all Add all option.
 * @param bool $top Add all in top.
 */
function wds_list_user_group( $all = false, $top = true ) {
	$user_group = array(
		'trial'    => 'Trial',
		'member'   => 'Member',
		'reseller' => 'Reseller',
	);

	if ( $all ) {
		$all_group = array( 'all' => __( 'Semua User Group', 'weddingsaas' ) );
		if ( $top ) {
			$user_group = $all_group + $user_group;
		} else {
			$user_group = $user_group + $all_group;
		}
	}

	return $user_group;
}


/**
 * Get cached count of posts for a user.
 *
 * @since 2.3.1
 * @param int|false $user_id The ID of the user.
 * @return int The number of posts for the user.
 */
function wds_user_posts_count( $user_id = false ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$cache_key = 'wds_post_count_' . $user_id;
	$count     = wp_cache_get( $cache_key, 'wds_posts_count' );

	if ( false === $count ) {
		$count = count_user_posts( $user_id );
		wp_cache_set( $cache_key, $count, 'wds_posts_count', HOUR_IN_SECONDS );
	}

	return $count;
}
