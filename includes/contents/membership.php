<?php
/**
 * WeddingSaas Membership.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Contents
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Membership Class.
 */
class WDS_Membership {

	/**
	 * Insert membership.
	 *
	 * @param int $order_id The order ID.
	 */
	public static function insert( $order_id = 0 ) {
		$order = wds_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id    = $order->user_id;
		$product_id = $order->product_id;

		$product      = wds_get_product( $product_id );
		$product_name = $product->title;
		$product_type = wds_get_product_meta( $product_id, 'product_type' );

		if ( 'digital' == $product_type ) {
			$status = wds_user_status( $user_id );
			$group  = wds_user_group( $user_id );
			if ( empty( $status ) && empty( $group ) ) {
				update_user_meta( $user_id, '_wds_user_status', 'inactive' );
				update_user_meta( $user_id, '_wds_user_group', 'trial' );
			}

			$current_access = wds_check_array( wds_user_meta( $user_id, '_wds_access_product' ) );
			if ( ! in_array( $product_id, $current_access ) ) {
				$current_access[] = $product_id;
			}

			update_user_meta( $user_id, '_wds_access_product', $current_access );
			wds_delete_cache_user( $user_id );
			return;
		} else {
			$addons = wds_get_order_meta( $order_id, 'addons' );
			if ( $addons ) {
				$addons_array = array_map( 'trim', explode( ', ', $addons ) );

				$addon_id = '';
				foreach ( wds_addon_data() as $data ) {
					if ( in_array( $data['title'], $addons_array ) ) {
						$addon_id = isset( $data['product_id'] ) ? intval( $data['product_id'] ) : '';
					}
				}

				if ( $addon_id ) {
					$current_access = wds_check_array( wds_user_meta( $user_id, '_wds_access_product' ) );
					if ( ! in_array( $addon_id, $current_access ) ) {
						$current_access[] = $addon_id;
					}

					update_user_meta( $user_id, '_wds_access_product', $current_access );
				}
			}
		}

		$membership_type        = wds_get_product_meta( $product_id, 'membership_type' );
		$is_membership_lifetime = wds_get_product_meta( $product_id, 'membership_lifetime' );
		$membership_duration    = wds_get_product_meta( $product_id, 'membership_duration' );
		$membership_period      = wds_get_product_meta( $product_id, 'membership_period' );

		$is_invitation_lifetime = wds_get_product_meta( $product_id, 'invitation_lifetime' );
		$invitation_duration    = wds_get_product_meta( $product_id, 'invitation_duration' );
		$invitation_period      = wds_get_product_meta( $product_id, 'invitation_period' );
		$invitation_status      = wds_get_product_meta( $product_id, 'invitation_status' );
		$invitation_quota       = intval( wds_get_product_meta( $product_id, 'invitation_quota' ) );
		$res_invitation_quota   = intval( wds_get_product_meta( $product_id, 'reseller_invitation_quota' ) );
		$res_client_quota       = intval( wds_get_product_meta( $product_id, 'reseller_client_quota' ) );

		$status_user           = wds_user_status( $user_id );
		$user_group            = wds_user_group( $user_id );
		$get_membership_period = wds_user_active_period( $user_id, true );
		$get_invitation_quota  = intval( wds_user_invitation_quota( $user_id ) );
		$get_client_quota      = intval( wds_user_client_quota( $user_id ) );
		$extend                = wds_option( 'user_expiration' );

		$wds_order_id = intval( wds_user_order_id( $user_id ) );

		$today        = current_time( 'timestamp' );
		$duration_new = "+$membership_duration $membership_period";

		if ( 'active' == $status_user && 'trial' == $membership_type ) {
			// menghindari tidak sengaja checkout trial
		} elseif ( 'trial' == $user_group && 'trial' == $membership_type && $wds_order_id ) {
			// nonaktif double trial
		} elseif ( 'addon' == $membership_type ) {
			$invitation_quota = $res_invitation_quota + $get_invitation_quota;
			$client_quota     = $res_client_quota + $get_client_quota;

			$metas = array(
				'_wds_invitation_quota' => $invitation_quota,
				'_wds_client_quota'     => $client_quota,
			);
			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}
		} else {
			if ( 'yes' == $is_membership_lifetime ) {
				$membership_period_timestamp = '';
			} elseif ( 'active' == $status_user ) {
				if ( $get_membership_period ) {
					$membership_period_timestamp = strtotime( $duration_new, $get_membership_period );
				} else {
					$membership_period_timestamp = strtotime( $duration_new, $today );
				}
			} else {
				$membership_period_timestamp = strtotime( $duration_new, $today );
			}
			if ( 'yes' == $is_invitation_lifetime ) {
				$invitation_duration = '';
				$invitation_period   = '';
				$invitation_status   = '';
			}
			if ( 'member' == $membership_type ) {
				if ( 'active' == $status_user ) {
					if ( 'trial' != $user_group ) {
						$invitation_quota = $invitation_quota + $get_invitation_quota;
					}
				} elseif ( $extend ) {
					$invitation_quota = $invitation_quota + $get_invitation_quota;
				}
			} elseif ( 'reseller' == $membership_type ) {
				if ( 'active' == $status_user ) {
					if ( 'trial' != $user_group ) {
						$invitation_quota = $res_invitation_quota + $get_invitation_quota;
					}
					$res_client_quota = $res_client_quota + $get_client_quota;
				} elseif ( $extend ) {
					$invitation_quota = $res_invitation_quota + $get_invitation_quota;
					$res_client_quota = $res_client_quota + $get_client_quota;
				} else {
					$invitation_quota = $res_invitation_quota;
					$res_client_quota = $res_client_quota;
				}

				if ( 'trial' == $user_group ) {
					$invitation_quota = $res_invitation_quota;
				}
			}

			if ( $wds_order_id ) {
				$cek = wds_get_order( $wds_order_id );
				if ( $cek && 'active' == $cek->status ) {
					$updated = wds_update_order(
						array(
							'ID'         => $wds_order_id,
							'expired_at' => 'NULL',
							'status'     => 'inactive',
						)
					);
				}
			}

			// update status order
			if ( $membership_period_timestamp ) {
				$updated = wds_update_order(
					array(
						'ID'         => $order_id,
						'expired_at' => gmdate( 'Y-m-d H:i:s', $membership_period_timestamp ),
					)
				);
			} else {
				$updated = wds_update_order(
					array(
						'ID'         => $order_id,
						'expired_at' => 'NULL',
					)
				);
			}

			$metas = array(
				'_wds_user_status'         => 'active',
				'_wds_user_group'          => $membership_type,
				'_wds_user_membership'     => $product_name,
				'_wds_user_active_period'  => $membership_period_timestamp,
				'_wds_invitation_quota'    => $invitation_quota,
				'_wds_client_quota'        => $res_client_quota,
				'_wds_invitation_duration' => $invitation_duration,
				'_wds_invitation_period'   => $invitation_period,
				'_wds_invitation_action'   => $invitation_status,
				'_wds_order_id'            => $order_id,
			);
			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}

			// auto extend invitation after order
			if ( 'member' == $membership_type && $wds_order_id ) {
				$args = array(
					'post_type'      => 'post',
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'     => '_wds_order_id',
							'value'   => $wds_order_id,
							'compare' => '=',
						),
					),
				);

				$query = new \WP_Query( $args );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						$post_id = get_the_ID();
						$status  = get_post_status( $post_id );

						if ( 'publish' == $status ) {
							if ( $invitation_duration ) {
								$old          = wds_post_meta( $post_id, '_wds_pep_period' );
								$duration_new = "+$invitation_duration $invitation_period";
								$expired      = strtotime( $duration_new, $old );

								update_post_meta( $post_id, '_wds_membership', $product_name );
								update_post_meta( $post_id, '_wds_pep_period', $expired );
								update_post_meta( $post_id, '_wds_pep_action', $invitation_status );
							} else {
								update_post_meta( $post_id, '_wds_membership', $product_name );
								update_post_meta( $post_id, '_wds_pep_period', '' );
								update_post_meta( $post_id, '_wds_pep_action', '' );
								update_post_meta( $post_id, '_wds_del_period', '' );
							}
							wds_delete_cache_post( $post_id );

							$new_quota = $invitation_quota - 1;
							update_user_meta( $user_id, '_wds_invitation_quota', $new_quota );
						} elseif ( 'draft' == $status ) {
							$updated_post = array(
								'ID'          => $post_id,
								'post_status' => 'publish',
							);
							wp_update_post( $updated_post );
						}
					}
					wp_reset_postdata();
				}
			}
		}
		wds_delete_cache_user( $user_id );
	}

	/**
	 * Delete membership.
	 *
	 * @param int $order_id The order ID.
	 */
	public static function delete( $order_id = 0 ) {
		$order = wds_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id      = $order->user_id;
		$product_id   = $order->product_id;
		$status_order = $order->status;

		$product_type = wds_get_product_meta( $product_id, 'product_type' );
		if ( 'digital' == $product_type ) {
			return;
		}

		$membership_type     = wds_get_product_meta( $product_id, 'membership_type' );
		$membership_duration = wds_get_product_meta( $product_id, 'membership_duration' );
		$membership_period   = wds_get_product_meta( $product_id, 'membership_period' );
		$invitation_quota    = intval( wds_get_product_meta( $product_id, 'invitation_quota' ) );

		$res_invitation_quota = intval( wds_get_product_meta( $product_id, 'reseller_invitation_quota' ) );
		$res_client_quota     = intval( wds_get_product_meta( $product_id, 'reseller_client_quota' ) );

		$get_membership_period = wds_user_active_period( $user_id, true );
		$get_invitation_quota  = intval( wds_user_invitation_quota( $user_id ) );
		$get_client_quota      = intval( wds_user_client_quota( $user_id ) );

		$duration_new = "-$membership_duration $membership_period";

		$new_membership_period = strtotime( $duration_new, $get_membership_period );
		$new_client_quota      = 0;

		if ( 'addon' == $membership_type ) {
			$new_invitation_quota = $get_invitation_quota - $res_invitation_quota;
			$new_client_quota     = $get_client_quota - $res_client_quota;

			if ( $new_invitation_quota < 0 ) {
				$new_invitation_quota = 0;
			}

			if ( $new_client_quota < 0 ) {
				$new_client_quota = 0;
			}

			$metas = array(
				'_wds_invitation_quota' => $new_invitation_quota,
				'_wds_client_quota'     => $new_client_quota,
			);
			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}
		} else {
			if ( 'reseller' == $membership_type ) {
				$new_invitation_quota = $get_invitation_quota - $res_invitation_quota;
				$new_client_quota     = $get_client_quota - $res_client_quota;
			} else {
				$new_invitation_quota = $get_invitation_quota - $invitation_quota;
			}

			if ( $new_invitation_quota < 0 ) {
				$new_invitation_quota = 0;
			}

			if ( $new_client_quota < 0 ) {
				$new_client_quota = 0;
			}

			$metas = array(
				'_wds_user_status'         => 'inactive',
				'_wds_user_active_period'  => $new_membership_period,
				'_wds_invitation_quota'    => $new_invitation_quota,
				'_wds_client_quota'        => $new_client_quota,
				'_wds_invitation_duration' => '',
				'_wds_invitation_period'   => '',
				'_wds_invitation_action'   => '',
			);

			// Checking status order
			if ( 'active' == $status_order ) {
				foreach ( $metas as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}
				$updated = wds_update_order(
					array(
						'ID'         => $order_id,
						'expired_at' => 'NULL',
					)
				);
			}
		}
		wds_delete_cache_user( $user_id );
	}
}
