<?php

namespace WDS\Frontend\Ajax;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dashboard Class.
 */
class Dashboard {

	/**
	 * Get invitation terms callback.
	 */
	public static function invitation_terms_callback() {
		$category = wds_sanitize_data_field( $_POST, 'category', false );
		$post_id  = wds_sanitize_data_field( $_POST, 'post_id', false );
		if ( $category ) {
			$taxonomy        = wds_get_selected_taxonomy( $category );
			$terms           = wds_invitation_get_template_taxonomy( $taxonomy, $post_id );
			$first_preview   = '';
			$first_thumbnail = '';
			$options         = '';

			foreach ( $terms  as $term ) {
				$theme_id  = wds_term_meta( $term->term_id, '_theme' );
				$preview   = $theme_id ? get_the_permalink( $theme_id ) : wds_term_meta( $term->term_id, '_preview' );
				$thumbnail = WDS()->invitation->get_term_thumbnail( $term->term_id );

				// Get first data for preview and thumbnail
				if ( empty( $first_preview ) && empty( $first_thumbnail ) ) {
					$first_preview   = $preview;
					$first_thumbnail = $thumbnail;
				}

				$options .= '<option value="' . $term->term_id . '" data-permalink="' . esc_url( $preview ) . '" data-thumbnail="' . esc_url( $thumbnail ) . '">' . $term->name . '</option>';
			}

			wp_send_json_success(
				array(
					'taxonomy'          => $taxonomy,
					'template'          => $options,
					'preview_permalink' => $first_preview,
					'preview_thumbnail' => $first_thumbnail,
				)
			);
		}

		wp_die();
	}

	/**
	 * Add invitation action.
	 */
	public static function invitation_add() {
		$post = $_POST;

		$title    = wds_sanitize_data_field( $post, 'title' );
		$slug     = wds_sanitize_data_field( $post, 'slug' );
		$category = intval( wds_sanitize_data_field( $post, 'category' ) );
		$taxonomy = wds_sanitize_data_field( $post, 'taxonomy' );
		$template = intval( wds_sanitize_data_field( $post, 'template' ) );
		$price    = intval( wds_sanitize_data_field( $post, 'price' ) );
		$status   = wds_option( 'invitation_status' );

		if ( 'active' != wds_user_status() ) {
			wp_send_json_error( wds_lang( 'dash_invitation_notice_user_expired' ) );
		}

		if ( empty( wds_user_invitation_quota() ) || 0 == wds_user_invitation_quota() ) {
			wp_send_json_error( wds_lang( 'dash_invitation_notice_empty_quota' ) );
		}

		$data = array(
			'post_title'    => $title,
			'post_content'  => '',
			'post_status'   => $status,
			'post_category' => array( $category ),
			'post_name'     => $slug,
		);

		$post_id = wp_insert_post( $data );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( $post_id->get_error_message() );
		}

		if ( ! empty( $taxonomy ) && ! empty( $template ) ) {
			wp_set_post_terms( $post_id, array( $template ), $taxonomy );

			if ( wds_is_theme() && class_exists( 'WDS_Theme_Main' ) ) {
				$data_meta = array(
					'post_id'  => $post_id,
					'taxonomy' => $taxonomy,
					'term_id'  => $template,
					'template' => $post['template_name'],
					'theme_id' => wds_term_meta( $template, '_theme' ),
				);

				\WDS_Theme_Main::add_default_post_meta( $data_meta );
			}
		}

		if ( ! empty( $price ) || 0 != $price ) {
			$income_args = array(
				'user_id' => get_post_field( 'post_author', $post_id ),
				'data_id' => $post_id,
				'type'    => 'invitation',
				'price'   => $price,
			);

			wds_insert_income( $income_args );
		}

		$redirect = wds_option( 'invitation_edit_link' );
		$redirect = ! empty( wds_option( 'invitation_edit_type' ) ) && ! empty( $redirect ) ? $redirect . $post_id : wds_url( 'edit', $post_id );

		wds_delete_cache_post( intval( $post_id ) );
		wp_send_json_success(
			array(
				'message'  => wds_lang( 'dash_invitation_notice_success' ),
				'redirect' => $redirect,
			)
		);
	}

	/**
	 * Activate invitation action.
	 */
	public static function invitation_activate() {
		$post_id = intval( wds_sanitize_data_field( $_POST, 'post_id' ) );
		$post    = get_post( $post_id );

		if ( $post ) {
			$updated_post = array(
				'ID'          => $post_id,
				'post_status' => 'publish',
			);

			if ( wds_user_invitation_quota() > 0 ) {
				wp_update_post( $updated_post );
				wp_send_json_success(
					array(
						'message'  => wds_lang( 'dash_invitation_notice_activate_success' ),
						'redirect' => wds_url( 'edit', $post_id ),
					)
				);
			} else {
				wp_send_json_error( wds_lang( 'dash_invitation_notice_empty_quota' ) );
			}
		} else {
			wp_send_json_error( wds_lang( 'dash_invitation_notice_activate_failed' ) );
		}
	}

	/**
	 * Extend invitation action.
	 */
	public static function invitation_extend() {
		$user_id = get_current_user_id();
		$post_id = intval( wds_sanitize_data_field( $_POST, 'post_id' ) );
		$post    = get_post( $post_id );

		if ( $post ) {
			$quota = intval( wds_user_invitation_quota() );
			if ( $quota > 0 ) {
				$membership          = wds_user_membership();
				$invitation_duration = wds_user_meta( $user_id, '_wds_invitation_duration' );
				$invitation_period   = wds_user_meta( $user_id, '_wds_invitation_period' );
				$invitation_action   = wds_user_meta( $user_id, '_wds_invitation_action' );

				if ( $invitation_duration ) {
					$old          = wds_post_meta( $post_id, '_wds_pep_period' );
					$duration_new = "+$invitation_duration $invitation_period";
					$expired      = strtotime( $duration_new, $old );

					update_post_meta( $post_id, '_wds_membership', $membership );
					update_post_meta( $post_id, '_wds_pep_period', $expired );
					update_post_meta( $post_id, '_wds_pep_action', $invitation_action );
				} else {
					update_post_meta( $post_id, '_wds_membership', $membership );
					update_post_meta( $post_id, '_wds_pep_period', '' );
					update_post_meta( $post_id, '_wds_pep_action', '' );
					update_post_meta( $post_id, '_wds_del_period', '' );
				}

				$new_quota = $quota - 1;
				update_user_meta( $user_id, '_wds_invitation_quota', $new_quota );

				wds_delete_cache_post( intval( $post_id ) );
				wp_send_json_success(
					array(
						'message'  => get_the_title( $post_id ) . ' ' . wds_lang( 'dash_invitation_notice_extend_success' ),
						'redirect' => wds_url( 'invitation' ),
					)
				);
			} else {
				wp_send_json_error( wds_lang( 'dash_invitation_notice_empty_quota' ) );
			}
		} else {
			wp_send_json_error( wds_lang( 'dash_invitation_notice_extend_failed' ) );
		}
	}

	/**
	 * Get invitation category.
	 */
	public static function invitation_get_category() {
		$category   = array();
		$category[] = (object) array(
			'ID'   => '',
			'name' => wds_lang( 'all' ),
			'icon' => wds_option( 'category_icon' ),
		);

		$categories = wds_categories_by_membership();
		foreach ( $categories as $cat ) {
			$icon = wds_term_meta( $cat->term_id, '_icon' );

			$category[] = (object) array(
				'ID'   => $cat->term_id,
				'name' => $cat->name,
				'icon' => $icon ? $icon : wds_term_meta( $cat->term_id, '_custom_icon' ),
			);
		}

		wp_send_json( $category );
	}

	/**
	 * Get invitation subcategory.
	 */
	public static function invitation_get_subcategory() {
		$subcategory = array();
		$category_id = wds_sanitize_data_field( $_POST, 'category', false );
		if ( $category_id ) {
			$all_subcategories = get_categories(
				array(
					'parent'     => $category_id,
					'hide_empty' => false,
				)
			);

			if ( $all_subcategories ) {
				foreach ( $all_subcategories as $subcategories ) {
					$taxonomy = wds_term_meta( $subcategories->term_id, '_template' );
					if ( $taxonomy ) {
						$membership = wds_check_array( wds_term_meta( $subcategories->term_id, '_membership' ) );
						if ( empty( $membership ) || in_array( wds_user_membership(), $membership ) ) {
							$subcategory[] = (object) array(
								'ID'   => $subcategories->term_id,
								'name' => $subcategories->name,
							);
						}
					}
				}

				if ( $subcategory ) {
					array_unshift(
						$subcategory,
						(object) array(
							'ID'   => '',
							'name' => wds_lang( 'all' ),
							'ex'   => 'all',
						)
					);
				}
			}
		}

		wp_send_json( $subcategory );
	}

	/**
	 * Get invitation theme.
	 */
	public static function invitation_get_theme() {
		$cat_id   = wds_sanitize_data_field( $_POST, 'category' );
		$subcat   = wds_sanitize_data_field( $_POST, 'subcategory' );
		$subtheme = wds_sanitize_data_field( $_POST, 'subtheme' );

		$category = array();
		$theme    = array();
		$tax      = array();

		// Get all category
		$get_categories = get_categories( array( 'hide_empty' => false ) );
		foreach ( $get_categories as $cat ) {
			$category[] = (object) array(
				'ID'   => $cat->term_id,
				'name' => $cat->name,
			);
		}

		// Filter taxonomy
		foreach ( $category as $item ) {
			$tax[] = wds_get_selected_taxonomy( $item->ID );
		}

		// Filter category
		$taxonomy_subtheme = '';
		if ( $subtheme ) {
			if ( $cat_id && $subcat ) {
				$taxonomy_subtheme = wds_get_selected_taxonomy( $subcat );
			} else {
				$taxonomy_subtheme = wds_get_selected_taxonomy( $cat_id );
			}

			$all_terms = get_term_children( $subtheme, $taxonomy_subtheme );
		} elseif ( $cat_id && $subcat ) {
			$taxonomy  = wds_get_selected_taxonomy( $subcat );
			$all_terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);
		} elseif ( $cat_id ) {
			// Get Sub Category
			$subcategories = get_categories(
				array(
					'parent'     => $cat_id,
					'hide_empty' => false,
				)
			);

			$taxonomy_array = array();
			foreach ( $subcategories as $subcategory ) {
				if ( wds_get_selected_taxonomy( $subcategory->term_id ) ) {
					$taxonomy_array[] = wds_get_selected_taxonomy( $subcategory->term_id );
				}
			}

			if ( $taxonomy_array ) {
				$all_terms = get_terms(
					array(
						'taxonomy'   => $taxonomy_array,
						'hide_empty' => false,
					)
				);
			} else {
				$taxonomy  = wds_get_selected_taxonomy( $cat_id );
				$all_terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'hide_empty' => false,
					)
				);
			}
		} else {
			$all_terms = get_terms( array( 'hide_empty' => false ) );
		}

		// Filter Sub Theme
		$parent_subcategory_ids = array();
		foreach ( $all_terms as $term ) {
			if ( is_object( $term ) ) {
				$children = get_term_children( $term->term_id, $term->taxonomy );
				if ( ! empty( $children ) ) {
					$parent_subcategory_ids[] = $term->term_id;
				}
			}
		}

		// Get All Theme
		foreach ( $all_terms as $terms ) {
			if ( $subtheme ) {
				$terms = get_term_by( 'id', $terms, $taxonomy_subtheme );
			}

			$term_membership = wds_check_array( wds_term_meta( $terms->term_id, '_membership' ) );
			// filter membership taxonomy
			if ( empty( $term_membership ) || in_array( wds_user_membership(), $term_membership ) ) {
				// filter taxonomy
				if ( in_array( $terms->taxonomy, $tax ) ) {
					// filter exclude sub theme
					if ( ! in_array( $terms->term_id, $parent_subcategory_ids ) ) {
						$_thumbnail           = WDS()->invitation->get_term_thumbnail( $terms->term_id );
						$_category            = wds_categories_name_by_taxonomy_theme( $terms->taxonomy );
						$_parent              = wds_get_parent_categories( $_category['id'] );
						$_category_membership = wds_check_array( wds_term_meta( $_category['id'], '_membership' ) );

						// filter membership category
						if ( empty( $_category_membership ) || in_array( wds_user_membership(), $_category_membership ) ) {
							$theme[] = (object) array(
								'ID'            => $terms->term_id,
								'name'          => $terms->name,
								'category_name' => $_parent . $_category['title'],
								'category'      => $_category,
								'taxonomy'      => $terms->taxonomy,
								'thumbnail'     => WDS()->invitation->get_term_thumbnail( $terms->term_id ),
								'preview'       => wds_term_meta( $terms->term_id, '_preview' ),
							);

							if ( wds_is_theme() ) {
								$has_taxonomy    = false;
								$taxonomy_themes = wds_theme_default_taxonomy();
								foreach ( $taxonomy_themes as $taxonomy_theme ) {
									if ( $terms->taxonomy == $taxonomy_theme ) {
										$has_taxonomy = true;
										break;
									}
								}

								if ( $has_taxonomy ) {
									$theme_id = wds_term_meta( $terms->term_id, '_theme' );
									if ( $theme_id ) {
										$theme[ count( $theme ) - 1 ]->preview = get_the_permalink( $theme_id );
									}
								}
							}
						}
					}
				}
			}
		}

		wp_send_json( $theme );
	}

	/**
	 * Get invitation subtheme.
	 */
	public static function invitation_get_subtheme() {
		$subthemes      = array();
		$category_id    = wds_sanitize_data_field( $_POST, 'category' );
		$subcategory_id = wds_sanitize_data_field( $_POST, 'subcategory' );
		if ( $category_id || $subcategory_id ) {
			if ( $category_id && $subcategory_id ) {
				$taxonomy = wds_get_selected_taxonomy( $subcategory_id );
			} else {
				$taxonomy = wds_get_selected_taxonomy( $category_id );
			}

			if ( $taxonomy ) {
				$terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'parent'     => 0,
						'hide_empty' => false,
					)
				);

				if ( $terms ) {
					foreach ( $terms as $term ) {
						$children = get_term_children( $term->term_id, $taxonomy );
						if ( ! empty( $children ) ) {
							$subthemes[] = (object) array(
								'ID'   => $term->term_id,
								'name' => $term->name,
							);
						}
					}
					if ( $subthemes ) {
						array_unshift(
							$subthemes,
							(object) array(
								'ID'   => '',
								'name' => wds_lang( 'all' ),
								'ex'   => 'all',
							)
						);
					}
				}
			}
		}

		wp_send_json( $subthemes );
	}

	/**
	 * Change invitation theme.
	 */
	public static function invitation_edit_theme() {
		$post_id  = wds_sanitize_data_field( $_POST, 'post_id', false );
		$category = wds_sanitize_data_field( $_POST, 'category', false );
		$taxonomy = wds_sanitize_data_field( $_POST, 'taxonomy', false );
		$term_id  = wds_sanitize_data_field( $_POST, 'theme', false );

		$old_taxonomy = WDS()->invitation->get_taxonomy( $post_id );

		// Update post category
		if ( $post_id && $category ) {
			wp_set_post_categories( $post_id, array( $category ) );
		}

		// Update taxonomy terms
		if ( $post_id && $term_id && $taxonomy ) {
			// Remove taxonomies first
			wp_set_post_terms( $post_id, array(), $taxonomy );
			wp_set_post_terms( $post_id, array(), $old_taxonomy );
			// Set the new taxonomy term
			wp_set_post_terms( $post_id, array( $term_id ), $taxonomy );
			wp_send_json_success( wds_lang( 'dash_invitation_theme_success' ) );
		} else {
			wp_send_json_error( wds_lang( 'dash_invitation_theme_failed' ) );
		}
	}

	/**
	 * Change invitation audio.
	 */
	public static function invitation_edit_audio() {
		$post = $_POST;
		// wds_log( $post, true );

		$post_id             = intval( wds_sanitize_data_field( $post, 'post_id', false ) );
		$audio_type          = wds_sanitize_data_field( $post, 'audio_type', false );
		$audio_url           = esc_url( $_POST['selected'] );
		$audio_start         = wds_sanitize_data_field( $post, 'audio_start', false );
		$audio_end           = wds_sanitize_data_field( $post, 'audio_end', false );
		$youtube_link        = wds_sanitize_data_field( $post, 'audio_youtube', false );
		$audio_youtube_start = wds_sanitize_data_field( $post, 'audio_youtube_start', false );
		$audio_youtube_end   = wds_sanitize_data_field( $post, 'audio_youtube_end', false );
		$audio_custom_start  = wds_sanitize_data_field( $post, 'audio_custom_start', false );
		$audio_custom_end    = wds_sanitize_data_field( $post, 'audio_custom_end', false );

		// $is_theme = 'active' == wds_sanitize_data_field( $_POST, 'theme' ) ? true : false;
		// $meta_key = $is_theme ? '_theme_music' : '_audio';
		// if ( $is_theme ) {
		//  $audio_url = array(
		//      'source' => 'local',
		//      'embed'  => '',
		//      'url'    => $audio_url,
		//  );
		// }

		wds_delete_cache_post( intval( $post_id ) );
		if ( $post_id ) {
			update_post_meta( $post_id, '_audio_type', $audio_type );
			if ( 'default' == $audio_type ) {
				update_post_meta( $post_id, '_audio', $audio_url );
				update_post_meta( $post_id, '_audio_start', $audio_start );
				update_post_meta( $post_id, '_audio_end', $audio_end );
				update_post_meta( $post_id, '_audio_youtube', '' );
			} elseif ( 'youtube' == $audio_type ) {
				update_post_meta( $post_id, '_audio_youtube', $youtube_link );
				update_post_meta( $post_id, '_audio_start', $audio_youtube_start );
				update_post_meta( $post_id, '_audio_end', $audio_youtube_end );
				update_post_meta( $post_id, '_audio', '' );
			} elseif ( 'custom' == $audio_type ) {

				if ( isset( $_FILES['custom'] ) ) {
					$file      = $_FILES['custom'];
					$file_name = $file['name'];
					$file_tmp  = $file['tmp_name'];

					$max_size_upload = wds_engine( 'audio_custom_max' ) ? wds_engine( 'audio_custom_max' ) : 5000;

					$current_user = wp_get_current_user();
					$user_id      = $current_user->ID;

					$audios_dir = wp_upload_dir()['basedir'] . '/user/' . $user_id . '/' . $post_id;

					$file_type_and_ext = wp_check_filetype_and_ext( $file_tmp, $file_name );
					if ( $file_type_and_ext['ext'] && $file_type_and_ext['type'] ) {
						$max_upload_size = $max_size_upload * 1024; // 5000 * 1024 = 5 MB
						$file_size       = filesize( $file_tmp );

						if ( $file_size <= $max_upload_size ) {
							if ( ! file_exists( $audios_dir ) ) {
								mkdir( $audios_dir, 0755, true ); // phpcs:ignore
							}

							// Generate a random string for the file name
							$random_string = wp_generate_password( 8, false );
							$audio_path    = $audios_dir . '/' . $post_id . $random_string . '.' . pathinfo( $file_name, PATHINFO_EXTENSION );

							if ( move_uploaded_file( $file_tmp, $audio_path ) ) {
								$wp_filetype = wp_check_filetype( $audio_path, null );
								$file_type   = $wp_filetype['type'];

								$attachment = array(
									'post_mime_type' => $file_type,
									'post_parent'    => $post_id,
									'post_title'     => $file_name,
									'post_content'   => '',
									'post_status'    => 'inherit',
								);

								$attachment_id = wp_insert_attachment( $attachment, $audio_path, $post_id );

								if ( ! is_wp_error( $attachment_id ) ) {
									require_once ABSPATH . 'wp-admin/includes/image.php';
									$attachment_data = wp_generate_attachment_metadata( $attachment_id, $audio_path );
									wp_update_attachment_metadata( $attachment_id, $attachment_data );

									$audio_url = wp_get_attachment_url( $attachment_id );
									update_post_meta( $post_id, '_audio', $audio_url );
									update_post_meta( $post_id, '_audio_youtube', '' );
								}
							} else {
								wp_send_json_error( __( 'Gagal memindahkan audio ke direktori unggahan.', 'weddingsaas' ) );
							}
						} else {
							wp_send_json_error( __( 'Ukuran audio terlalu besar. Maksimal:', 'weddingsaas' ) . ' ' . size_format( $max_upload_size ) . '.' );
						}
					}
				}
				update_post_meta( $post_id, '_audio_start', $audio_custom_start );
				update_post_meta( $post_id, '_audio_end', $audio_custom_end );
			}
			wp_send_json_success( wds_lang( 'dash_invitation_audio_success' ) );
		} else {
			update_post_meta( $post_id, '_audio_type', '' );
			update_post_meta( $post_id, '_audio', '' );
			update_post_meta( $post_id, '_audio_youtube', '' );
			update_post_meta( $post_id, '_audio_start', '' );
			update_post_meta( $post_id, '_audio_end', '' );
			wp_send_json_error( wds_lang( 'dash_invitation_audio_failed' ) );
		}
	}

	/**
	 * Add client action.
	 */
	public static function client_add() {
		$post = $_POST;

		$reseller_id = wds_sanitize_data_field( $post, 'reseller_id' );
		$name        = wds_sanitize_data_field( $post, 'fullname' );
		$email       = wds_sanitize_data_field( $post, 'email' );
		$phone       = wds_sanitize_data_field( $post, 'phone' );
		$password    = wds_sanitize_data_field( $post, 'password' );
		$product_id  = wds_sanitize_data_field( $post, 'product' );
		$price       = wds_sanitize_data_field( $post, 'price' );

		if ( wds_check_existing_phone( $phone ) ) {
			wp_send_json_error( wds_lang( 'phone_exist' ) );
		}

		$get_client_quota = wds_user_client_quota( $reseller_id );
		if ( empty( $get_client_quota ) || 0 == $get_client_quota ) {
			wp_send_json( wds_lang( 'dash_client_notice_empty' ) );
		}

		$client_data = array(
			'user_login'   => $email,
			'user_pass'    => $password,
			'user_email'   => $email,
			'first_name'   => $name,
			'display_name' => $name,
			'role'         => 'wds-member',
		);

		$user_id = wp_insert_user( $client_data );
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( $user_id->get_error_message() );
		}

		$client_args = array(
			'reseller_id' => $reseller_id,
			'client_id'   => $user_id,
		);

		$client_id = wds_insert_client( $client_args );

		if ( is_wp_error( $client_id ) ) {
			wp_send_json_error( $client_id->get_error_message() );
		}

		$product = wds_get_product( $product_id );
		if ( $product ) {
			$product_name = $product->title;

			$membership_type        = wds_get_product_meta( $product_id, 'membership_type' );
			$is_membership_lifetime = wds_get_product_meta( $product_id, 'membership_lifetime' );
			$membership_duration    = wds_get_product_meta( $product_id, 'membership_duration' );
			$membership_period      = wds_get_product_meta( $product_id, 'membership_period' );

			$is_invitation_lifetime = wds_get_product_meta( $product_id, 'invitation_lifetime' );
			$invitation_quota       = wds_get_product_meta( $product_id, 'invitation_quota' );
			$invitation_duration    = wds_get_product_meta( $product_id, 'invitation_duration' );
			$invitation_period      = wds_get_product_meta( $product_id, 'invitation_period' );
			$invitation_status      = wds_get_product_meta( $product_id, 'invitation_status' );

			$today        = current_time( 'timestamp' );
			$duration_new = "+$membership_duration $membership_period";

			if ( 'yes' == $is_membership_lifetime ) {
				$membership_period_timestamp = '';
			} else {
				$membership_period_timestamp = strtotime( $duration_new, $today );
			}

			if ( 'yes' == $is_invitation_lifetime ) {
				$invitation_duration = '';
				$invitation_period   = '';
				$invitation_status   = '';
			}

			$metas = array(
				'_wds_user_status'         => 'active',
				'_wds_user_group'          => $membership_type,
				'_wds_user_membership'     => $product_name,
				'_phone'                   => $phone,
				'_wds_user_active_period'  => $membership_period_timestamp,
				'_wds_invitation_quota'    => $invitation_quota,
				'_wds_invitation_duration' => $invitation_duration,
				'_wds_invitation_period'   => $invitation_period,
				'_wds_invitation_action'   => $invitation_status,
				'_password'                => $password,
			);
		} else {
			$today         = current_time( 'timestamp' );
			$active_period = strtotime( '+1 year', $today );

			$metas = array(
				'_wds_user_status'         => 'active',
				'_wds_user_group'          => 'member',
				'_wds_user_membership'     => 'Gold Invitation',
				'_phone'                   => $phone,
				'_wds_user_active_period'  => $active_period,
				'_wds_invitation_quota'    => 1,
				'_wds_invitation_duration' => 1,
				'_wds_invitation_period'   => 'year',
				'_wds_invitation_action'   => 'draft',
				'_password'                => $password,
			);
		}

		$metas['_branding_name']        = wds_user_meta( $reseller_id, '_branding_name' );
		$metas['_branding_logo']        = wds_user_meta( $reseller_id, '_branding_logo' );
		$metas['_branding_link']        = wds_user_meta( $reseller_id, '_branding_link' );
		$metas['_branding_description'] = wds_user_meta( $reseller_id, '_branding_description' );
		$metas['_affiliate_status']     = wds_option( 'client_affiliate_status' );
		$metas['_is_verified']          = true;

		foreach ( $metas as $key => $value ) {
			update_user_meta( $user_id, $key, $value );
		}

		$new_client_quota = intval( $get_client_quota ) - 1;
		update_user_meta( $reseller_id, '_wds_client_quota', $new_client_quota );

		do_action( 'wds_client_register', $client_id );

		if ( $price ) {
			$income_args = array(
				'user_id' => $reseller_id,
				'data_id' => $user_id,
				'type'    => 'client',
				'price'   => $price,
			);

			$income_id = wds_insert_income( $income_args );

			if ( is_wp_error( $income_id ) ) {
				wp_send_json_error( $income_id->get_error_message() );
			}
		}

		wp_send_json_success(
			array(
				'message'  => wds_lang( 'dash_client_notice_success' ),
				'redirect' => wds_url( 'client' ),
			),
		);
	}

	/**
	 * Landingpage add data.
	 */
	public static function landingpage_add() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$value     = wds_sanitize_data_field( $post, 'value', false );
		$domain    = wds_sanitize_data_field( $post, 'domain', false );
		$subdomain = wds_sanitize_data_field( $post, 'subdomain', false );

		$host_number = wds_option( 'wdr_select_host' );
		$host_custom = '.' . wds_option( 'wdr_domain_host_custom' );

		if ( $value ) {
			if ( 'domain' == $value ) {
				if ( ! $domain ) {
					wp_send_json_error( wds_lang( 'wdr_notif_empty_domain' ) );
				}

				$domain = str_replace( array( 'https://', 'http://' ), '', $domain );

				$check = wdr_get_by( "WHERE domain = '$domain'" );
				if ( $check && $check->ID > 0 ) {
					wp_send_json_error( wds_lang( 'wdr_notif_used_domain' ) );
				}

				$data_args = array(
					'user_id'   => $user_id,
					'domain'    => $domain,
					'subdomain' => 'nothing',
					'status'    => 'unconnected',
				);

				wdr_insert( $data_args );

				do_action( 'wds_status_domain', $data_args );

				wp_send_json_success( wds_lang( 'wdr_notif_domain_added' ) );
			} else {
				if ( ! $subdomain ) {
					wp_send_json_error( wds_lang( 'wdr_notif_empty_subdomain' ) );
				}

				$home        = home_url();
				$home        = str_replace( array( 'https://', 'http://', 'https://www.', 'http://www.' ), '', $home );
				$home_parts  = explode( '.', $home );
				$home_domain = $home_parts[0];

				$blacklist_subdomain = wds_option( 'wdr_blacklist_subdomain' );
				$blacklist_subdomain = explode( ',', $blacklist_subdomain );
				$blacklist_subdomain = array_merge( $blacklist_subdomain, array( 'www', 'admin', 'administrator', 'blog', $home_domain ) );

				if ( in_array( $subdomain, $blacklist_subdomain ) ) {
					wp_send_json_error( wds_lang( 'wdr_notif_blacklist_subdomain' ) );
				}

				if ( ! ctype_alnum( $subdomain ) ) {
					wp_send_json_error( wds_lang( 'wdr_notif_wrong_subdomain' ) );
				}

				$subdomain = preg_replace( '/[^a-zA-Z0-9]/', '', $subdomain );
				$subdomain = strtolower( $subdomain );

				$min = wds_option( 'wdr_limit_min' );
				$max = wds_option( 'wdr_limit_max' );

				if ( strlen( $subdomain ) < $min || strlen( $subdomain ) > $max ) {
					wp_send_json_error( wds_lang( 'wdr_notif_length_subdomain' ) . ' min ' . $min . ' max ' . $max );
				}

				// custom domain
				if ( '3' == $host_number ) {
					$domain = $subdomain . $host_custom;
					$check  = wdr_get_by( "WHERE domain = '$domain'" );
					if ( $check && $check->ID > 0 ) {
						wp_send_json_error( wds_lang( 'wdr_notif_used_domain' ) );
					}

					$data_args = array(
						'user_id'   => $user_id,
						'domain'    => $domain,
						'subdomain' => 'nothing',
						'status'    => 'unconnected',
					);

					do_action( 'wds_status_domain', $data_args );
				} else {
					$check = wdr_get_by( "WHERE subdomain = '$subdomain'" );
					if ( $check && $check->ID > 0 ) {
						wp_send_json_error( wds_lang( 'wdr_notif_used_subdomain' ) );
					}

					$data_args = array(
						'user_id'   => $user_id,
						'domain'    => 'nothing',
						'subdomain' => $subdomain,
						'status'    => 'active',
					);
				}

				wdr_insert( $data_args );

				wp_send_json_success( wds_lang( 'wdr_notif_subdomain_added' ) );
			}
		}

		wp_send_json_error( wds_lang( 'wdr_notif_empty_host' ) );
	}

	/**
	 * Landingpage update data.
	 */
	public static function landingpage_update() {
		$post    = $_POST;
		$user_id = get_current_user_id();

		$value     = wds_sanitize_data_field( $post, 'value', false );
		$domain    = wds_sanitize_data_field( $post, 'domain', false );
		$subdomain = wds_sanitize_data_field( $post, 'subdomain', false );

		$host_number = wds_option( 'wdr_select_host' );
		$host_custom = '.' . wds_option( 'wdr_domain_host_custom' );

		if ( $value ) {
			$check_user = wdr_get_by( "WHERE user_id = '$user_id'" );
			if ( $check_user ) {
				if ( 'domain' == $value ) {
					if ( ! $domain ) {
						wp_send_json_error( wds_lang( 'wdr_notif_empty_domain' ) );
					}

					$domain = str_replace( array( 'https://', 'http://' ), '', $domain );

					$check = wdr_get_by( "WHERE domain = '$domain'" );
					if ( $check && $check->ID > 0 ) {
						wp_send_json_error( wds_lang( 'wdr_notif_used_domain' ) );
					}

					$updated = wdr_update_domain( $user_id, $check_user->domain, $domain );
					if ( is_wp_error( $updated ) ) {
						wp_send_json_error( $updated->get_error_message() );
					}

					$data_args = array(
						'user_id'   => $user_id,
						'domain'    => $domain,
						'subdomain' => 'nothing',
						'status'    => 'unconnected',
					);

					do_action( 'wds_status_domain', $data_args );

					wdr_update_status( $check_user->ID, 'unconnected' );
					wdr_update_subdomain( $user_id, $check_user->subdomain, 'nothing' );

					wp_send_json_success( wds_lang( 'wdr_notif_domain_updated' ) );
				} else {
					if ( ! $subdomain ) {
						wp_send_json_error( wds_lang( 'wdr_notif_empty_subdomain' ) );
					}

					$home        = home_url();
					$home        = str_replace( array( 'https://', 'http://', 'https://www.', 'http://www.' ), '', $home );
					$home_parts  = explode( '.', $home );
					$home_domain = $home_parts[0];

					$blacklist_subdomain = wds_option( 'wdr_blacklist_subdomain' );
					$blacklist_subdomain = explode( ',', $blacklist_subdomain );
					$blacklist_subdomain = array_merge( $blacklist_subdomain, array( 'www', 'admin', 'administrator', 'blog', $home_domain ) );

					if ( in_array( $subdomain, $blacklist_subdomain ) ) {
						wp_send_json_error( wds_lang( 'wdr_notif_blacklist_subdomain' ) );
					}

					if ( ! ctype_alnum( $subdomain ) ) {
						wp_send_json_error( wds_lang( 'wdr_notif_wrong_subdomain' ) );
					}

					$subdomain = preg_replace( '/[^a-zA-Z0-9]/', '', $subdomain );
					$subdomain = strtolower( $subdomain );

					$min = wds_option( 'wdr_limit_min' );
					$max = wds_option( 'wdr_limit_max' );

					if ( strlen( $subdomain ) < $min || strlen( $subdomain ) > $max ) {
						wp_send_json_error( wds_lang( 'wdr_notif_length_subdomain' ) . ' min ' . $min . ' max ' . $max );
					}

					// custom domain
					if ( '3' == $host_number ) {
						$domain = $subdomain . $host_custom;
						$check  = wdr_get_by( "WHERE domain = '$domain'" );
						if ( $check && $check->ID > 0 ) {
							wp_send_json_error( wds_lang( 'wdr_notif_used_domain' ) );
						}

						$updated = wdr_update_domain( $user_id, $check_user->domain, $domain );
						if ( is_wp_error( $updated ) ) {
							wp_send_json_error( $updated->get_error_message() );
						}

						$data_args = array(
							'user_id'   => $user_id,
							'domain'    => $domain,
							'subdomain' => 'nothing',
							'status'    => 'unconnected',
						);

						do_action( 'wds_status_domain', $data_args );

						wdr_update_status( $check_user->ID, 'unconnected' );
						wdr_update_subdomain( $user_id, $check_user->subdomain, 'nothing' );

						wp_send_json_success( wds_lang( 'wdr_notif_domain_updated' ) );
					} else {
						$check = wdr_get_by( "WHERE subdomain = '$subdomain'" );
						if ( $check && $check->ID > 0 ) {
							wp_send_json_error( wds_lang( 'wdr_notif_used_subdomain' ) );
						}

						$updated = wdr_update_subdomain( $user_id, $check_user->subdomain, $subdomain );
						if ( is_wp_error( $updated ) ) {
							wp_send_json_error( $updated->get_error_message() );
						}

						wdr_update_status( $check_user->ID, 'active' );
						wdr_update_domain( $user_id, $check_user->domain, 'nothing' );

						wp_send_json_success( wds_lang( 'wdr_notif_subdomain_updated' ) );
					}
				}
			}
		}

		wp_send_json_error( wds_lang( 'wdr_notif_empty_host' ) );
	}

	/**
	 * Landingpage delete data.
	 */
	public static function landingpage_delete() {
		$post    = $_POST;
		$user_id = get_current_user_id();
		$value   = wds_sanitize_data_field( $post, 'value', false );

		if ( $value ) {
			$check = wdr_get_by( "WHERE user_id = '$user_id'" );
			if ( $check ) {
				$deleted = wdr_delete( $check->ID );
				if ( is_wp_error( $deleted ) ) {
					wp_send_json_error( $deleted->get_error_message() );
				}

				if ( 'domain' == $value ) {
					wp_send_json_success( wds_lang( 'wdr_notif_domain_deleted' ) );
				} else {
					wp_send_json_success( wds_lang( 'wdr_notif_subdomain_deleted' ) );
				}
			}
		}

		wp_send_json_error( wds_lang( 'wdr_notif_empty_host' ) );
	}
}
