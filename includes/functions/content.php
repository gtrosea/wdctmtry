<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Generate unique number.
 */
function wds_generate_unique_number() {
	if ( ! wds_option( 'unique_number' ) ) {
		return false;
	}

	$max     = intval( wds_option( 'unique_number_max' ) );
	$current = get_option( 'wds_current_unique_number' );

	if ( $current ) {
		$current_max = intval( $current );
		$max         = $current_max > 2 ? $current_max : $max;
	}

	$unique = $max - 1;
	update_option( 'wds_current_unique_number', $unique );

	return $unique;
}

/**
 * Get all post types.
 */
function wds_get_post_type() {
	$post_types = get_post_types( array( 'public' => true ) );
	$excluded   = array( 'attachment', 'elementor_library', 'jet-engine', 'jet-form-builder', 'jet-engine-booking', 'wds_font', 'wds_audio', 'wds_template' );

	$post_type_options = array();
	foreach ( $post_types as $post_type ) {
		if ( in_array( $post_type, $excluded ) ) {
			continue;
		}

		$post_type_object = get_post_type_object( $post_type );
		if ( $post_type_object ) {
			$post_type_options[ $post_type ] = $post_type_object->labels->name;
		}
	}

	return $post_type_options;
}

/**
 * Retrieves a list of subthemes based on taxonomy terms and their children.
 *
 * @return array An associative array of subthemes where the key is the term ID, and the value is the term name.
 */
function wds_get_subthemes() {
	$categories = get_categories( array( 'hide_empty' => false ) );

	$all_taxonomy = array();
	foreach ( $categories as $category ) {
		$tax = wds_term_meta( $category->term_id, '_template' );
		if ( $tax ) {
			$all_taxonomy[] = $tax;
		}
	}

	$theme_options = array();
	if ( ! empty( $all_taxonomy ) ) {
		foreach ( $all_taxonomy as $taxonomy ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);

			foreach ( $terms as $term ) {
				if ( is_object( $term ) ) {
					$children = get_term_children( $term->term_id, $term->taxonomy );
					if ( ! empty( $children ) ) {
						$theme_options[ $term->term_id ] = $term->name;
					}
				}
			}
		}
	}

	return $theme_options;
}

/**
 * Retrieve metaboxes based on the given post type.
 *
 * This function gets the metaboxes from the options and organizes them into sections,
 * metaboxes, and optgroups, depending on the provided post type.
 *
 * @param string $type The type of post (e.g., 'post', 'user', 'taxonomy). Default is 'post'.
 * @return array {
 *     Array containing sections, metaboxes, and optgroups.
 *     @type array $sections  Array of section slugs and names.
 *     @type array $metaboxes Array of metaboxes organized by section.
 *     @type array $optgroup  Array of optgroups with field IDs and labels.
 * }
 */
function wds_get_metaboxes( $type = 'post' ) {
	$cache_key = 'wds_metaboxes_' . $type;

	// get from cache
	$cached = wp_cache_get( $cache_key, 'wds_metaboxes' );
	if ( false !== $cached ) {
		return $cached;
	}

	// get from database
	$data      = wds_engine( 'metabox' );
	$sections  = array();
	$metaboxes = array();
	$optgroup  = array();

	if ( wds_check_array( $data, true ) ) {
		foreach ( $data as $metabox ) {
			if ( empty( $metabox['title'] ) || empty( $metabox['type'] ) || empty( $metabox['section'] ) ) {
				continue;
			}

			if ( $metabox['type'] == $type ) {
				foreach ( $metabox['section'] as $section ) {
					$name              = $section['name'];
					$slug              = sanitize_title( $name );
					$sections[ $slug ] = $name;

					if ( ! isset( $metaboxes[ $slug ] ) ) {
						$metaboxes[ $slug ] = array();
					}

					foreach ( $section['field'] as $field ) {
						$metaboxes[ $slug ][] = array(
							'id'    => $field['key'],
							'type'  => $field['type'],
							'title' => $field['label'],
						);
					}
				}
			}
		}

		foreach ( $sections as $slug => $name ) {
			$optgroup[ $name ] = array();
			if ( isset( $metaboxes[ $slug ] ) ) {
				foreach ( $metaboxes[ $slug ] as $metabox ) {
					$optgroup[ $name ][ $metabox['id'] ] = $metabox['title'];
				}
			}
		}
	}

	$result = array(
		'sections'  => $sections,
		'metaboxes' => $metaboxes,
		'optgroup'  => $optgroup,
	);

	// cache the result
	wp_cache_set( $cache_key, $result, 'wds_metaboxes', WEEK_IN_SECONDS );

	return $result;
}

/**
 * Check if a data is array.
 *
 * @param array $data The data to check.
 * @param bool  $_nothing The return boolean.
 * @return mixed True if the data is empty, false otherwise.
 */
function wds_check_array( $data = array(), $_nothing = false ) {
	if ( $_nothing ) {
		return ! empty( $data ) && is_array( $data );
	}

	return ! empty( $data ) && is_array( $data ) ? $data : array();
}

/**
 * Check if a value or a specific key in an array is empty.
 *
 * @param mixed       $source The value to check or an array containing the value to check.
 * @param bool|string $key (Optional) The key in the array to check if `$source` is an array. Default is false.
 * @return bool True if the value or the value associated with the key is empty, false otherwise.
 */
function wds_is_empty( $source = null, $key = false ) {
	if ( wds_check_array( $source, true ) && $key ) {

		if ( ! isset( $source[ $key ] ) ) {
			return true;
		}

		$source = $source[ $key ];
	}

	return empty( $source ) && '0' !== $source;
}

/**
 * Renders checkbox values by converting an array or object of values into a string.
 *
 * @param mixed  $value     The input value which can be null, an array, or an object.
 * @param string $delimiter The delimiter used to join the checkbox values in the output string. Default is ', '.
 * @return string           The sanitized and delimited string of checkbox values or an empty string if the value is empty.
 */
function wds_render_checkbox_values( $value = null, $delimiter = ', ' ) {
	if ( empty( $value ) ) {
		return '';
	}

	if ( is_object( $value ) ) {
		$value = get_object_vars( $value );
	}

	if ( ! $value || ! is_array( $value ) ) {
		return $value;
	}

	$result = wds_get_prepared_check_values( $value );

	return wp_kses_post( implode( $delimiter, $result ) );
}

/**
 * Prepares checkbox values by filtering out unchecked items and retaining the checked ones.
 *
 * @param mixed $value The input value, typically an array of checkbox values.
 * @return array       The prepared array of checkbox values with only checked items.
 */
function wds_get_prepared_check_values( $value = null ) {
	$result = array();

	if ( in_array( 'true', $value ) || in_array( 'false', $value ) ) {
		foreach ( $value as $key => $val ) {
			if ( 'true' === $val ) {
				$result[] = $key;
			}
		}
	} else {
		$result = $value;
	}

	return $result;
}

/**
 * Get logo payment by key.
 *
 * @param string $key The key payment.
 * @return string The logo payment key.
 */
function wds_logo_payment( $key = false ) {
	$data = 'assets/img/blank-image.svg';

	switch ( $key ) {
		case 'banktransfer':
		case 'xendit':
		case 'midtrans':
		case 'banktransfer':
		case 'tripay_ocbcva':
		case 'tripay_danamonva':
		case 'tripay_otherbankva':
		case 'flip':
			$data = 'assets/img/payment/banktransfer.webp';
			break;

		case 'qris':
		case 'duitku_sp':
		case 'duitku_lq':
		case 'duitku_nq':
		case 'tripay_ovo':
		case 'tripay_qris':
		case 'tripay_qrisc':
		case 'tripay_qris2':
		case 'tripay_dana':
		case 'tripay_shopeepay':
		case 'tripay_qris_shopeepay':
			$data = 'assets/img/payment/qris.png';
			break;

		case 'duitku_vc':
			$data = 'assets/img/payment/cc.png';
			break;

		case 'duitku_ir':
		case 'tripay_indomaret':
			$data = 'assets/img/payment/indomaret.png';
			break;

		case 'duitku_ft':
		case 'tripay_alfamart':
			$data = 'assets/img/payment/alfamart.png';
			break;

		case 'tripay_alfamidi':
			$data = 'assets/img/payment/alfamidi.png';
			break;

		case 'duitku_ag':
			$data = 'assets/img/payment/artha.png';
			break;

		case 'duitku_nc':
			$data = 'assets/img/payment/bnc.png';
			break;

		case 'duitku_a1':
			$data = 'assets/img/payment/atmbersama.png';
			break;

		case 'duitku_bc':
		case 'tripay_bcava':
			$data = 'assets/img/payment/bca-va.png';
			break;

		case 'duitku_i1':
		case 'tripay_bniva':
			$data = 'assets/img/payment/bni-va.png';
			break;

		case 'duitku_br':
		case 'tripay_briva':
			$data = 'assets/img/payment/bri-va.png';
			break;

		case 'duitku_m1':
		case 'tripay_mandiriva':
			$data = 'assets/img/payment/mandiri-va.png';
			break;

		case 'duitku_bv':
		case 'tripay_bsiva':
			$data = 'assets/img/payment/bsi-va.png';
			break;

		case 'duitku_b1':
		case 'tripay_cimbva':
			$data = 'assets/img/payment/cimb-va.png';
			break;

		case 'duitku_va':
		case 'tripay_mybva':
			$data = 'assets/img/payment/maybank-va.png';
			break;

		case 'duitku_bt':
		case 'tripay_permatava':
			$data = 'assets/img/payment/permata-va.png';
			break;

		case 'tripay_muamalatva':
			$data = 'assets/img/payment/muamalat-va.png';
			break;

		case 'tripay_sinarmasva':
			$data = 'assets/img/payment/sms-va.png';
			break;
	}

	return WDS_URL . $data;
}


/**
 * Convert an attachment URL to its corresponding post ID.
 *
 * @since 2.3.1
 * @param string $url The URL of the attachment.
 * @return int The post ID of the attachment, or 0 if not found.
 */
function wds_attachment_url_to_postid( $url ) {
	if ( empty( $url ) || ! is_string( $url ) ) {
		return 0;
	}

	$cache_key   = 'wds_attachment_url_to_postid_' . md5( $url );
	$cache_group = 'wds_attachment';
	$cached      = wp_cache_get( $cache_key, $cache_group );

	if ( false !== $cached ) {
		return (int) $cached;
	}

	$post_id = attachment_url_to_postid( $url );

	// Cache for 1 week
	wp_cache_set( $cache_key, (int) $post_id, $cache_group, 3 * DAY_IN_SECONDS );

	return $post_id;
}
