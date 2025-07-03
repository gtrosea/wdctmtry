<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieves the permalink or replica URL for a given invitation post.
 *
 * @param int  $post_id The ID of the invitation post.
 * @param bool $wdr     Whether to check for the WeddingReplica integration (default true).
 * @return string       The URL of the invitation post, potentially modified by WeddingReplica or query strings.
 */
function wds_invitation_open( $post_id, $wdr = true ) {
	$url = get_permalink( $post_id );
	if ( wds_is_replica() && $wdr && ( empty( wds_option( 'wdr_integration' ) ) || 'public' == wds_option( 'wdr_integration' ) ) ) {
		$slug = true;
		$host = wds_replica_invitation_host( $post_id, $slug );
		if ( $host ) {
			$url = $host . '/';
		}
	}

	$query = wds_option( 'invitation_query' );
	if ( $query ) {
		$url .= $query;
	}

	return $url;
}

/**
 * Retrieves the approved comment count for an invitation post.
 *
 * This function counts the number of approved comments for the specified post.
 *
 * @param int $post_id The ID of the invitation post.
 * @return int         The count of approved comments.
 */
function wds_invitation_comment_count( $post_id ) {
	$comment_args = array(
		'post_id' => $post_id,
		'status'  => 'approve',
	);

	$approved_comment_count = get_comments( $comment_args );

	return count( $approved_comment_count );
}

/**
 * Retrieves the total RSVP count from the comments of an invitation post.
 *
 * This function sums up the RSVP count from the comments, based on the 'guest' meta key.
 * Each comment is expected to contain the number of guests as a numeric value.
 *
 * @param int $post_id The ID of the invitation post.
 * @return int         The total count of RSVPs.
 */
function wds_invitation_rsvp_count( $post_id ) {
	$total_rsvp_count = 0;
	$present          = 0;
	$notpresent       = 0;
	$notsure          = 0;

	$integration = wds_option( 'rsvp_integration' );

	$args = array(
		'post_id' => $post_id,
		'status'  => 'approve',
	);

	$comments = get_comments( $args );

	if ( $comments ) {
		foreach ( $comments as $comment ) {
			$comment_id = $comment->comment_ID;
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
		}
		$total_rsvp_count = $present + $notpresent + $notsure;
	}

	return $total_rsvp_count;
}

/**
 * Retrieves the name of a category by its ID.
 *
 * This function returns the name of a category based on its ID.
 * If the category is not found, it returns a 'Category not found' message.
 *
 * @param int $category_id The ID of the category.
 * @return string          The name of the category, or an error message if not found.
 */
function wds_get_category_label_by_id( $category_id ) {
	$category = get_category( $category_id );

	return $category ? $category->name : __( 'Kategori tidak ditemukan.', 'weddingsaas' );
}

/**
 * Retrieves a list of categories that either have a custom taxonomy or have subcategories.
 *
 * This function filters categories based on their metadata or if they have child categories.
 *
 * @return array The filtered list of categories.
 */
function wds_get_categories() {
	$all_categories      = get_categories( array( 'hide_empty' => false ) );
	$filtered_categories = array();

	$parent_category_ids_with_children = array();

	foreach ( $all_categories as $category ) {
		$children = get_categories(
			array(
				'parent'     => $category->term_id,
				'hide_empty' => false,
			)
		);
		if ( ! empty( $children ) ) {
			$parent_category_ids_with_children[] = $category->term_id;
		}
	}

	foreach ( $all_categories as $category ) {
		$taxonomy = wds_term_meta( $category->term_id, '_template' );
		if ( $taxonomy || in_array( $category->term_id, $parent_category_ids_with_children ) ) {
			if ( 0 == $category->parent ) {
				$filtered_categories[] = $category;
			}
		}
	}

	return $filtered_categories;
}

/**
 * Retrieves the IDs of subthemes based on taxonomy and child terms.
 *
 * This function filters categories based on their metadata and then retrieves
 * their associated term IDs if they have child terms.
 *
 * @return array An array of term IDs that are considered subthemes.
 */
function wds_get_subthemes_ids() {
	$all_categories = get_categories( array( 'hide_empty' => false ) );
	$all_taxonomy   = array();
	$data           = array();

	// Filter taxonomy
	foreach ( $all_categories as $category ) {
		$tax = wds_term_meta( $category->term_id, '_template' );
		if ( $tax ) {
			$all_taxonomy[] = $tax;
		}
	}

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
					$data[] = $term->term_id;
				}
			}
		}
	}

	return $data;
}

/**
 * Retrieves categories with subcategories based on the user's membership level.
 *
 * @param string $membership Optional. The membership level to filter by. Default empty.
 * @return array The filtered list of categories.
 */
function wds_categories_with_sub_by_membership( $membership = '' ) {
	$membership_type     = $membership ? $membership : wds_user_membership();
	$all_categories      = get_categories( array( 'hide_empty' => false ) );
	$filtered_categories = array();

	foreach ( $all_categories as $category ) {
		// Filter WDS category
		$taxonomy = wds_term_meta( $category->term_id, '_template' );
		if ( $taxonomy ) {
			$category_membership = wds_check_array( wds_term_meta( $category->term_id, '_membership' ) );
			// Filter by membership
			if ( empty( $category_membership ) || in_array( $membership_type, $category_membership ) ) {
				$filtered_categories[] = $category;
			}
		}
	}

	return $filtered_categories;
}

/**
 * Retrieves parent categories by membership level, filtering based on taxonomy and subcategories.
 *
 * This function checks each category and its subcategories to determine if it should be included
 * based on the user's membership and taxonomy metadata.
 *
 * @return array The filtered list of categories.
 */
function wds_categories_by_membership() {
	$membership_user     = wds_user_membership();
	$all_categories      = get_categories( array( 'hide_empty' => false ) );
	$filtered_categories = array();

	// Store parent category IDs that have children
	$parent_category_ids_with_children = array();

	// Find categories with subcategories
	foreach ( $all_categories as $category ) {
		$children = get_categories(
			array(
				'parent'     => $category->term_id,
				'hide_empty' => false,
			)
		);
		if ( ! empty( $children ) ) {
			$parent_category_ids_with_children[] = $category->term_id;
		}
	}

	// Filter categories by membership
	foreach ( $all_categories as $category ) {
		$taxonomy = wds_term_meta( $category->term_id, '_template' );
		if ( $taxonomy || in_array( $category->term_id, $parent_category_ids_with_children ) ) {
			$category_membership = wds_check_array( wds_term_meta( $category->term_id, '_membership' ) );
			if ( empty( $category_membership ) || in_array( $membership_user, $category_membership ) ) {
				// Include parent categories or those with subcategories
				if ( 0 == $category->parent ) {
					$filtered_categories[] = $category;
				}
			}
		}
	}

	return $filtered_categories;
}

/**
 * Retrieves the category name and ID based on a specific taxonomy.
 *
 * This function searches for categories with the provided taxonomy and returns
 * the category's ID and name.
 *
 * @param string $taxonomy The taxonomy to filter categories by.
 * @return array An array containing the category ID and name, or an empty array if not found.
 */
function wds_categories_name_by_taxonomy_theme( $taxonomy ) {
	$categories = get_categories( array( 'hide_empty' => false ) );
	$label      = array();
	foreach ( $categories as $category ) {
		$template = wds_term_meta( $category->term_id, '_template' );
		if ( $template == $taxonomy ) {
			$label = array(
				'id'    => $category->term_id,
				'title' => $category->name,
			);
			break;
		}
	}

	return $label;
}

/**
 * Get slugs of taxonomies based on theme.
 *
 * @return array List of taxonomies with assigned templates.
 */
function wds_get_slug_taxonomy_taxonomy_theme() {
	$label      = array();
	$categories = get_categories( array( 'hide_empty' => false ) );
	foreach ( $categories as $category ) {
		$template = wds_term_meta( $category->term_id, '_template' );
		if ( $template ) {
			$label[] = $template;
		}
	}

	return $label;
}

/**
 * Retrieve parent category name by category ID.
 *
 * @param int $category_id Category ID.
 * @return string Parent category name with a trailing dash if exists.
 */
function wds_get_parent_categories( $category_id ) {
	$parent   = '';
	$category = get_term( $category_id, 'category' );
	if ( $category && ! is_wp_error( $category ) ) {
		$parent_category_id = $category->parent;
		if ( $parent_category_id ) {
			$parent_category      = get_term( $parent_category_id, 'category' );
			$parent_category_name = $parent_category->name;
			$parent               = $parent_category_name . ' - ';
		}
	}

	return $parent;
}

/**
 * Retrieve parent taxonomy ID by taxonomy ID.
 *
 * @param int $taxonomy_id Taxonomy ID.
 * @return int Parent taxonomy ID if exists, empty string otherwise.
 */
function wds_get_parent_taxonomy_id( $taxonomy_id ) {
	$parent = '';
	$term   = get_term( $taxonomy_id );
	if ( $term && ! is_wp_error( $term ) ) {
		$parent_term_id = $term->parent;
		if ( $parent_term_id ) {
			$parent = $parent_term_id;
		}
	}

	return $parent;
}

/**
 * Count the number of available templates.
 *
 * @return int The number of templates.
 */
function wds_templates_count() {
	return count( wds_categories_with_sub_by_membership() );
}

/**
 * Get the selected taxonomy for a specific category.
 *
 * @param int $id_category Category ID.
 * @return string Selected template associated with the category.
 */
function wds_get_selected_taxonomy( $id_category ) {
	return wds_term_meta( $id_category, '_template' );
}

/**
 * Retrieve taxonomy associated with a post by its ID.
 *
 * @param int $post_id Post ID.
 * @return string Template associated with the post's category, or an empty string if not found.
 */
function wds_get_taxonomy_by_post_id( $post_id ) {
	if ( $post_id ) {
		$categories  = wp_get_post_categories( $post_id );
		$category_id = $categories[0];
		$taxonomy    = wds_term_meta( $category_id, '_template' );
		return $taxonomy;
	}

	return '';
}

/**
 * Get the ID of the taxonomy's parent term associated with a post.
 *
 * @param int    $post_id Post ID.
 * @param string $taxonomy Taxonomy name.
 * @return int|false Taxonomy theme ID or false on failure.
 */
function wds_get_taxonomy_theme_id( $post_id, $taxonomy ) {
	$terms = wp_get_post_terms( $post_id, $taxonomy );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return false;
	}

	foreach ( $terms as $term ) {
		if ( 0 == $term->parent ) {
			return $term->term_id;
		}
	}

	return $terms[0]->term_id;
}

/**
 * Get filtered terms for invitation templates based on membership.
 *
 * @param string   $taxonomy Taxonomy name.
 * @param int|null $post_id Optional. Post ID to get membership from. Default empty.
 * @return array List of terms filtered by membership.
 */
function wds_invitation_get_template_taxonomy( $taxonomy, $post_id = '' ) {
	if ( $post_id ) {
		$membership_type = wds_post_membership( $post_id );
	} else {
		$membership_type = wds_user_membership();
	}

	$filtered_terms = array();
	$all_terms      = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		)
	);

	$parent_subtheme_ids = array();
	foreach ( $all_terms as $term ) {
		if ( is_object( $term ) ) {
			$children = get_term_children( $term->term_id, $term->taxonomy );
			if ( ! empty( $children ) ) {
				$parent_subtheme_ids[] = $term->term_id;
			}
		}
	}

	foreach ( $all_terms as $terms ) {
		if ( ! in_array( $terms->term_id, $parent_subtheme_ids ) ) {
			$term_membership = wds_check_array( wds_term_meta( $terms->term_id, '_membership' ) );
			if ( empty( $term_membership ) || in_array( $membership_type, $term_membership ) ) {
				$filtered_terms[] = $terms;
			}
		}
	}

	return $filtered_terms;
}

/**
 * Customize term label for JetFormBuilder.
 *
 * @param string  $name Term name.
 * @param WP_Term $term Term object.
 * @return string Updated term label.
 */
function wds_custom_jet_form_builder_render_choice_label_terms( $name, $term ) {
	if ( 'category' == $term->taxonomy ) {
		$category_id = $term->term_id;
		$category    = get_term( $category_id, 'category' );

		if ( $category && ! is_wp_error( $category ) && $category->parent ) {
			$parent_category = get_term( $category->parent, 'category' );
			if ( $parent_category && ! is_wp_error( $parent_category ) ) {
				$name = $parent_category->name . ' | ' . $name;
			}
		}
	}

	return $name;
}
add_filter( 'jet-form-builder/render-choice/label/terms', 'wds_custom_jet_form_builder_render_choice_label_terms', 10, 2 );

/**
 * Retrieve RSVP attendance status based on ID.
 *
 * @param string $id RSVP attendance status key.
 * @return string Attendance status label.
 */
function wds_rsvp_attendance( $id ) {
	$title = array(
		'present'     => wds_lang( 'rsvp_attendance_present' ),
		'notpresent'  => wds_lang( 'rsvp_attendance_notpresent' ),
		'notsure'     => wds_lang( 'rsvp_attendance_notsure' ),
		'Hadir'       => wds_lang( 'rsvp_attendance_present' ),
		'Tidak hadir' => wds_lang( 'rsvp_attendance_notpresent' ),
	);

	if ( isset( $title[ $id ] ) ) {
		$title = $title[ $id ];
	} else {
		$title = $id;
	}

	return $title;
}

/**
 * Get post membership information based on query parameters.
 *
 * @param int $post_id Optional. The post ID. Default empty.
 * @return string Post membership metadata.
 */
function wds_post_membership( $post_id = '' ) {
	$meta = '';

	if ( isset( $_GET['id'] ) || isset( $_GET['post_id'] ) ) {
		$post_id = ! empty( $_GET['id'] ) ? $_GET['id'] : $_GET['post_id'];
		$meta    = wds_post_meta( $post_id, '_wds_membership' );
	} elseif ( $post_id ) {
		$meta = wds_post_meta( $post_id, '_wds_membership' );
	}

	return $meta;
}

/**
 * Retrieves all terms with children based on taxonomy filtered by categories.
 *
 * @return array List of term IDs that have children.
 */
function wds_get_theme() {
	$all_categories = get_categories( array( 'hide_empty' => false ) );
	$all_taxonomy   = array();
	$data           = array();

	// Filter taxonomy based on term meta.
	foreach ( $all_categories as $category ) {
		$tax = wds_term_meta( $category->term_id, '_template' );
		if ( $tax ) {
			$all_taxonomy[] = $tax;
		}
	}

	// Collect term IDs that have children.
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
					$data[] = $term->term_id;
				}
			}
		}
	}

	return $data;
}

/**
 * Returns the time difference in a human-readable format since the provided time or post time.
 *
 * @param string $time Optional. Time in the format 'Y-m-d H:i:s'. Default is the current post time.
 * @param int    $post_id The ID of the post.
 * @return string Human-readable time since the provided time or post time.
 */
function wdsrsvp_get_time_since( $time = '', $post_id = 0 ) {
	if ( empty( $time ) ) {
		$time = get_the_time( 'U' ); // Ambil waktu posting saat ini dalam UNIX timestamp
	}

	// Pastikan waktu yang diberikan adalah numeric atau string yang dapat diproses
	if ( ! is_numeric( $time ) ) {
		$time = strtotime( $time ); // Konversi string menjadi timestamp
	}

	// Jika hasil konversi tidak valid, kembalikan teks default
	if ( ! $time || $time < 0 ) {
		$language = wds_post_meta( $post_id, '_language' );
		return ( 'en_US' === $language ) ? __( 'Some time ago', 'weddingsaas' ) : __( 'Beberapa waktu yang lalu', 'weddingsaas' );
	}

	// Hitung waktu sejak
	$time_since_posted = wdsrsvp_make_time_since( $post_id, $time, current_time( 'timestamp' ) );

	return $time_since_posted;
}

/**
 * Creates a human-readable time difference between two timestamps.
 *
 * @param int      $post_id The post ID.
 * @param int      $older_date The older timestamp.
 * @param int|bool $newer_date Optional. The newer timestamp. Default is current time.
 * @return string Human-readable time difference, e.g., "5 mins ago", "2 days ago".
 */
function wdsrsvp_make_time_since( $post_id, $older_date, $newer_date = false ) {
	$language = wds_post_meta( $post_id, '_language' );

	if ( 'en_US' === $language ) {
		$unknown_text   = __( 'Some time', 'weddingsaas' );
		$right_now_text = __( 'just now', 'weddingsaas' );
		$ago_text       = '%s ' . __( 'ago', 'weddingsaas' );

		$chunks = array(
			array( 60 * 60 * 24 * 365, __( 'year', 'weddingsaas' ), __( 'years', 'weddingsaas' ) ),
			array( 60 * 60 * 24 * 30, __( 'month', 'weddingsaas' ), __( 'months', 'weddingsaas' ) ),
			array( 60 * 60 * 24 * 7, __( 'week', 'weddingsaas' ), __( 'weeks', 'weddingsaas' ) ),
			array( 60 * 60 * 24, __( 'day', 'weddingsaas' ), __( 'days', 'weddingsaas' ) ),
			array( 60 * 60, __( 'hour', 'weddingsaas' ), __( 'hours', 'weddingsaas' ) ),
			array( 60, __( 'minute', 'weddingsaas' ), __( 'minutes', 'weddingsaas' ) ),
			array( 1, __( 'second', 'weddingsaas' ), __( 'seconds', 'weddingsaas' ) ),
		);
	} else {
		$unknown_text   = __( 'Beberapa waktu', 'weddingsaas' );
		$right_now_text = __( 'baru saja', 'weddingsaas' );
		$ago_text       = '%s ' . __( 'yang lalu', 'weddingsaas' );

		$chunks = array(
			array( 60 * 60 * 24 * 365, __( 'tahun', 'weddingsaas' ), __( 'tahun', 'weddingsaas' ) ),
			array( 60 * 60 * 24 * 30, __( 'bulan', 'weddingsaas' ), __( 'bulan', 'weddingsaas' ) ),
			array( 60 * 60 * 24 * 7, __( 'minggu', 'weddingsaas' ), __( 'minggu', 'weddingsaas' ) ),
			array( 60 * 60 * 24, __( 'hari', 'weddingsaas' ), __( 'hari', 'weddingsaas' ) ),
			array( 60 * 60, __( 'jam', 'weddingsaas' ), __( 'jam', 'weddingsaas' ) ),
			array( 60, __( 'menit', 'weddingsaas' ), __( 'menit', 'weddingsaas' ) ),
			array( 1, __( 'detik', 'weddingsaas' ), __( 'detik', 'weddingsaas' ) ),
		);
	}

	// Validasi dan konversi tanggal lama
	if ( ! is_numeric( $older_date ) ) {
		if ( ! empty( $older_date ) ) {
			$older_date = strtotime( $older_date ); // Ubah string menjadi timestamp
		} else {
			return $unknown_text; // Jika kosong, kembalikan teks "Beberapa waktu"
		}
	}

	// Validasi tanggal baru
	if ( ! $newer_date ) {
		$newer_date = time();
	} elseif ( ! is_numeric( $newer_date ) ) {
		$newer_date = strtotime( $newer_date );
	}

	// Hitung selisih waktu
	$since = $newer_date - $older_date;

	if ( $since < 0 ) {
		return $unknown_text; // Jika waktu lebih besar di masa depan
	}

	for ( $i = 0, $j = count( $chunks ); $i < $j; ++$i ) {
		$seconds = $chunks[ $i ][0];
		$count   = floor( $since / $seconds );
		if ( 0 != $count ) {
			break;
		}
	}

	if ( ! isset( $chunks[ $i ] ) ) {
		return $right_now_text;
	}

	$output = 1 == $count ? '1 ' . $chunks[ $i ][1] : $count . ' ' . $chunks[ $i ][2];

	// Tambahkan unit waktu tambahan jika relevan
	if ( $i + 1 < $j ) {
		$seconds2 = $chunks[ $i + 1 ][0];
		$count2   = floor( ( $since - ( $seconds * $count ) ) / $seconds2 );
		if ( 0 != $count2 ) {
			$output .= 1 == $count2 ? ', 1 ' . $chunks[ $i + 1 ][1] : ', ' . $count2 . ' ' . $chunks[ $i + 1 ][2];
		}
	}

	if ( ! trim( $output ) ) {
		return $right_now_text;
	}

	return sprintf( $ago_text, $output );
}

/**
 * Checks if the given post belongs to a WeddingSaas theme taxonomy.
 *
 * @param int $post_id The post ID.
 * @return bool True if the post belongs to a WeddingSaas theme taxonomy, false otherwise.
 */
function wds_invitation_is_theme( $post_id ) {
	$has_taxonomy = false;

	if ( ! empty( $post_id ) && wds_is_theme() ) {
		$taxonomy        = wds_get_taxonomy_by_post_id( $post_id );
		$taxonomy_themes = wds_theme_default_taxonomy();

		foreach ( $taxonomy_themes as $taxonomy_theme ) {
			if ( $taxonomy == $taxonomy_theme ) {
				$has_taxonomy = true;
				break;
			}
		}
	}

	return $has_taxonomy;
}

/**
 * Get wds theme taxonomy.
 */
function wds_theme_default_taxonomy() {
	$taxonomy = array(
		'wds_template_wedding',
		'wds_template_birthday',
		'wds_template_khitan',
		'wds_template_aqiqah',
	);

	return apply_filters( 'wds_theme_taxonomy', $taxonomy );
}
