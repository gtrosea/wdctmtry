<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves a product.
 *
 * @param int|object $product The product ID or object.
 * @return mixed Products object if found, false otherwise.
 */
function wds_get_product( $product = false ) {
	if ( is_object( $product ) && isset( $product->ID ) ) {
		$id = $product->ID;
	} elseif ( wds_check_array( $product, true ) && isset( $product['ID'] ) ) {
		$id = $product['ID'];
	} elseif ( is_int( $product ) || is_string( $product ) ) {
		$id = $product;
	} else {
		return false;
	}

	$product_id = intval( $id );

	return WDS()->database->get_product( 'ID', $product_id, true );
}

/**
 * Insert a new product.
 *
 * @param array $data Product data to insert.
 * @return mixed The ID of the new product, or false on failure.
 */
function wds_insert_product( $data = array() ) {
	return WDS()->database->add( 'product', $data );
}

/**
 * Update an existing product.
 *
 * @param array $data Product data to update.
 * @return mixed True if update was successful, false otherwise.
 */
function wds_update_product( $data = array() ) {
	if ( empty( $data['ID'] ) ) {
		return false;
	}

	$obj = wds_get_product( $data['ID'] );
	if ( ! $obj ) {
		return wds_insert_product( $data );
	}

	$updated = WDS()->database->update( 'product', $data );

	if ( ! is_wp_error( $updated ) ) {
		wds_delete_cache( 'wds_products' );
	}

	return $updated;
}

/**
 * Delete a product.
 *
 * @param int|object $product The product ID or object.
 * @return bool True if delete was successful, false otherwise.
 */
function wds_delete_product( $product = false ) {
	$obj = wds_get_product( $product );
	if ( ! $obj ) {
		return false;
	}

	$product_id = $obj->ID;

	$deleted = WDS()->database->delete( 'product', $product_id );

	if ( $deleted ) {
		WDS\Models\Product_Meta::delete( array( 'product_id' => $product_id ) );
		wds_delete_cache( 'wds_products' );
		wds_delete_cache( 'wds_product_meta' );
	}

	return $deleted;
}

/**
 * Update the status of a product.
 *
 * @param int|object $product The product ID or object.
 * @param string     $new_status New status for the product.
 * @return mixed True on success, false on failure.
 */
function wds_update_product_status( $product = false, $new_status = '' ) {
	$obj = wds_get_product( $product );
	if ( ! $obj ) {
		return false;
	}

	$new_status = wds_sanitize_text_field( $new_status );
	$is_exists  = wds_get_product_statuses( $new_status );
	if ( ! $is_exists ) {
		return false;
	}

	$data = array(
		'ID'         => $obj->ID,
		'status'     => $new_status,
		'updated_at' => gmdate( 'Y-m-d H:i:s' ),
		'nometa'     => true,
	);

	$updated = WDS()->database->update( 'product', $data );

	if ( ! is_wp_error( $updated ) ) {
		wds_delete_cache( 'wds_products' );
	}

	return $updated;
}

/**
 * Retrieve metadata for a product.
 *
 * @param int|object $product Product ID to retrieve metadata for.
 * @param string     $meta_key The metadata key to retrieve.
 * @param bool       $single Whether to return a single value (true) or an array of values (false).
 * @return mixed The retrieved data if found, or false if not found or invalid input.
 */
function wds_get_product_meta( $product, $meta_key, $single = true ) {
	$obj = wds_get_product( $product );
	if ( ! $obj ) {
		return false;
	}

	$product_id = $obj->ID;

	return WDS()->meta->get_product_meta( $product_id, $meta_key, $single );
}

/**
 * Add metadata for a product.
 *
 * @param int|object $product Product ID to add metadata to.
 * @param string     $meta_key The metadata key to add.
 * @param mixed      $meta_value The value to set for the metadata key.
 * @param bool       $unique Whether the metadata key should be unique (true) or not (false).
 * @return mixed The added meta if successful, or false if invalid input or failure.
 */
function wds_add_product_meta( $product, $meta_key, $meta_value, $unique = false ) {
	$obj = wds_get_product( $product );
	if ( ! $obj ) {
		return false;
	}

	return WDS()->meta->add_meta( 'product', $obj->ID, $meta_key, $meta_value, $unique );
}

/**
 * Update metadata for a product.
 *
 * @param int|object $product Product ID to update metadata for.
 * @param string     $meta_key The metadata key to update.
 * @param mixed      $meta_value The new value for the metadata key.
 * @return mixed The updated meta if successful, or false if invalid input or failure.
 */
function wds_update_product_meta( $product, $meta_key, $meta_value ) {
	$obj = wds_get_product( $product );
	if ( ! $obj ) {
		return false;
	}

	$updated = WDS()->meta->update_meta( 'product', $obj->ID, $meta_key, $meta_value );

	if ( ! is_wp_error( $updated ) ) {
		wds_delete_cache( 'wds_product_meta' );
	}

	return $updated;
}

/**
 * Delete metadata for a product.
 *
 * @param int|object $product Product ID to delete metadata from.
 * @param string     $meta_key The metadata key to delete.
 * @param mixed      $meta_value Optional. The value of the metadata to delete. If not provided, all values for the key will be deleted.
 * @return bool The deleted meta if successful, or false if invalid input or failure.
 */
function wds_delete_product_meta( $product, $meta_key, $meta_value = false ) {
	$obj = wds_get_product( $product );
	if ( ! $obj ) {
		return false;
	}

	$deleted = WDS()->meta->delete_meta( 'product', $obj->ID, $meta_key, $meta_value );

	if ( $deleted ) {
		wds_delete_cache( 'wds_product_meta' );
	}

	return $deleted;
}

/**
 * Retrieve the current product slug from WDS variables.
 *
 * @return string The current product slug.
 */
function wds_get_current_product_slug() {
	return wds_get_vars( '__product' );
}

/**
 * Retrieve a product by a specific key and value.
 *
 * @param string $key   The key to search by (e.g., 'ID', 'name', 'slug').
 * @param string $value The value to search for.
 * @return mixed Products object if found, false otherwise.
 */
function wds_get_product_by( $key, $value ) {
	return WDS()->database->get_product( $key, $value, true );
}

/**
 * Get all active products sorted by the specified order.
 *
 * @return object|array The data product with status active.
 */
function wds_get_product_active() {
	return WDS()->database->get_product( 'status', 'active' );
}

/**
 * Get the title of a product by its ID.
 *
 * @param int $product_id The product ID.
 * @return string The product title.
 */
function wds_get_product_title( $product_id = 0 ) {
	$obj = wds_get_product( $product_id );
	if ( ! $obj ) {
		return false;
	}

	return $obj->title;
}

/**
 * Get the price of a product by its ID.
 *
 * @param int|object $product The product ID.
 * @return float The product price.
 */
function wds_get_product_price( $product = false ) {
	return floatval( wds_get_product_meta( $product, 'regular_price' ) );
}

/**
 * Get the renew price of a product by its ID.
 *
 * @param int|object $product The product ID.
 * @return float The product renew price.
 */
function wds_get_product_renew_price( $product = false ) {
	return floatval( wds_get_product_meta( $product, 'renew_price' ) );
}

/**
 * Get restricted products based on membership type.
 *
 * @param string $key Restriction type ('reseller', 'client', or default) (optional).
 * @return array The data product.
 */
function wds_get_product_restrict( $key = '' ) {
	$product = array();
	foreach ( wds_get_product_active() as $products ) {
		$product_type = wds_get_product_meta( $products->ID, 'product_type' );
		if ( 'digital' == $product_type ) {
			continue;
		}

		$membership_type = wds_get_product_meta( $products->ID, 'membership_type' );

		switch ( $key ) {
			case 'reseller':
				if ( 'reseller' == $membership_type ) {
					$product[ $products->title ] = $products->title;
				}
				break;

			case 'client':
				if ( 'member' == $membership_type || 'trial' == $membership_type ) {
					$product[ $products->ID ] = $products->title;
				}
				break;

			default:
				if ( 'addon' != $membership_type ) {
					$product[ $products->title ] = $products->title;
				}
				break;
		}
	}

	ksort( $product );

	return $product;
}

/**
 * Retrieve products based on membership type and key.
 *
 * @param string $key   The key to filter products. Options: 'pricing', 'pricing_reseller', 'upgrade', 'upgrade_reseller', 'upgrade_quota'. Default is 'pricing'.
 * @param bool   $title Whether to return product titles instead of IDs. Default is false.
 * @return array The data product.
 */
function wds_get_product_membership( $key = 'pricing', $title = false ) {
	$product = array();
	foreach ( wds_get_product_active() as $products ) {
		$product_type = wds_get_product_meta( $products->ID, 'product_type' );
		if ( 'digital' == $product_type ) {
			continue;
		}

		$membership_type = wds_get_product_meta( $products->ID, 'membership_type' );

		switch ( $key ) {
			case 'pricing':
				if ( 'member' == $membership_type || 'trial' == $membership_type ) {
					if ( $title ) {
						$product[ $products->ID ] = $products->title;
					} else {
						$product[] = $products->ID;
					}
				}
				break;

			case 'pricing_reseller':
				if ( 'reseller' == $membership_type ) {
					if ( $title ) {
						$product[ $products->ID ] = $products->title;
					} else {
						$product[] = $products->ID;
					}
				}
				break;

			case 'upgrade':
				if ( 'member' == $membership_type || 'reseller' == $membership_type ) {
					if ( $title ) {
						$product[ $products->ID ] = $products->title;
					} else {
						$product[] = $products->ID;
					}
				}
				break;

			case 'upgrade_reseller':
				if ( 'reseller' == $membership_type ) {
					if ( $title ) {
						$product[ $products->ID ] = $products->title;
					} else {
						$product[] = $products->ID;
					}
				}
				break;

			case 'upgrade_quota':
				if ( 'addon' == $membership_type ) {
					if ( $title ) {
						$product[ $products->ID ] = $products->title;
					} else {
						$product[] = $products->ID;
					}
				}
				break;
		}
	}

	return $product;
}

/**
 * Get the raw affiliate commission information.
 *
 * @param int|object $product Product ID to retrieve data.
 * @return array The commission type and value.
 */
function wds_get_product_raw_affiliate_commission( $product = false ) {
	$obj = wds_get_product( $product );
	if ( ! $obj ) {
		return false;
	}

	$commission_array = array();

	$commission = wds_get_product_meta( $obj->ID, 'affiliate_commission' );
	if ( ! empty( $commission ) ) {
		if ( strpos( $commission, '%' ) ) {
			$commission = str_replace( '%', '', $commission );

			$commission_array = array(
				'type'  => 'percen',
				'value' => floatval( $commission ),
			);
		} else {
			$commission_array = array(
				'type'  => 'fixed',
				'value' => floatval( $commission ),
			);
		}
	}

	return $commission_array;
}

/**
 * Retrieve products based on product type digital.
 *
 * @return array The data product.
 */
function wds_get_product_digital() {
	$product = array();
	foreach ( wds_get_product_active() as $products ) {
		$product_type = wds_get_product_meta( $products->ID, 'product_type' );
		if ( 'digital' != $product_type ) {
			continue;
		}

		$product[ $products->ID ] = $products->title;
	}

	return $product;
}

/**
 * Retrieve data product meta by key.
 *
 * @param int    $product_id The product ID.
 * @param string $key The product data to retrive.
 * @return mixed The product data, false otherwise.
 */
function wds_get_product_data( $product_id = false, $key = false ) {
	$ret = false;

	if ( ! $product_id || ! $key ) {
		$ret = false;
	}

	$price = wds_get_product_price( $product_id );
	$renew = wds_get_product_renew_price( $product_id );
	$free  = empty( $price ) || 0 == $price ? true : false;

	$membership_type = wds_get_product_meta( $product_id, 'membership_type' );
	$payment_type    = wds_get_product_meta( $product_id, 'payment_type' );
	$renew_duration  = wds_get_product_meta( $product_id, 'renew_duration' );
	$renew_period    = wds_get_product_meta( $product_id, 'renew_period' );

	$membership_lifetime = wds_get_product_meta( $product_id, 'membership_lifetime' );
	$membership_duration = wds_get_product_meta( $product_id, 'membership_duration' );
	$membership_period   = wds_get_product_meta( $product_id, 'membership_period' );

	$invitation_lifetime = wds_get_product_meta( $product_id, 'invitation_lifetime' );
	$invitation_duration = wds_get_product_meta( $product_id, 'invitation_duration' );
	$invitation_period   = wds_get_product_meta( $product_id, 'invitation_period' );

	$invitation_quota     = wds_get_product_meta( $product_id, 'invitation_quota' );
	$res_invitation_quota = wds_get_product_meta( $product_id, 'reseller_invitation_quota' );

	switch ( $key ) {
		case 'free':
			$ret = $free;
			break;

		case 'price':
			$ret = $free ? wds_lang( 'free' ) : wds_convert_money( $price );
			break;

		case 'renew_price':
			$ret = ! empty( $renew ) || 0 != $renew ? wds_convert_money( $renew ) : wds_convert_money( $price );
			break;

		case 'payment_type':
			if ( $free ) {
				$ret = '';
			} elseif ( 'onetime' == $payment_type ) {
				$ret = '/' . wds_lang( 'onetime' );
			} else {
				if ( 'day' == $renew_period ) {
					$renew_period = wds_lang( 'day' );
				} elseif ( 'month' == $renew_period ) {
					$renew_period = wds_lang( 'month' );
				} elseif ( 'year' == $renew_period ) {
					$renew_period = wds_lang( 'year' );
				}
				$ret = '/' . $renew_duration . ' ' . $renew_period;
			}
			break;

		case 'membership_type':
			if ( 'trial' == $membership_type ) {
				$ret = wds_lang( 'trial' );
			} elseif ( 'member' == $membership_type ) {
				$ret = wds_lang( 'member' );
			} elseif ( 'reseller' == $membership_type ) {
				$ret = wds_lang( 'reseller' );
			} else {
				$ret = wds_lang( 'addon' );
			}
			break;

		case 'membership_lifetime':
			if ( 'yes' == $membership_lifetime ) {
				$ret = wds_lang( 'lifetime' );
			} else {
				if ( 'day' == $membership_period ) {
					$membership_period = wds_lang( 'day' );
				} elseif ( 'month' == $membership_period ) {
					$membership_period = wds_lang( 'month' );
				} elseif ( 'year' == $membership_period ) {
					$membership_period = wds_lang( 'year' );
				}
				$ret = $membership_duration . ' ' . $membership_period;
			}
			break;

		case 'invitation_lifetime':
			if ( 'yes' == $invitation_lifetime ) {
				$ret = wds_lang( 'lifetime' );
			} else {
				if ( 'day' == $invitation_period ) {
					$invitation_period = wds_lang( 'day' );
				} elseif ( 'month' == $invitation_period ) {
					$invitation_period = wds_lang( 'month' );
				} elseif ( 'year' == $invitation_period ) {
					$invitation_period = wds_lang( 'year' );
				}
				$ret = $invitation_duration . ' ' . $invitation_period;
			}
			break;

		case 'membership_quota':
			$ret = 'addon' == $membership_type || 'reseller' == $membership_type ? $res_invitation_quota : $invitation_quota;
			break;
	}

	return $ret;
}

/**
 * Get a list of products addon.
 *
 * @return array Associative array with addon IDs as keys and titles as values.
 */
function wds_get_product_addon() {
	$addons = array();
	$data   = wds_addon_data();
	if ( wds_check_array( $data, true ) ) {
		foreach ( $data as $addon ) {
			$addons[ $addon['id'] ] = $addon['title'];
		}
	}

	return $addons;
}
