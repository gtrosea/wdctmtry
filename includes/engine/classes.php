<?php
/**
 * WeddingSaas Engine.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Engine
 */

namespace WDS\Engine;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class Main {

	/**
	 * Constructor for the Main class.
	 */
	public function __construct() {
		// Module Audio
		if ( wds_engine( 'module_audio' ) || wds_is_theme() ) {
			require_once WDS_INCLUDES . 'engine/modules/audio.php';
		}

		// Module Tema
		if ( wds_engine( 'module_tema' ) || wds_is_theme() ) {
			require_once WDS_INCLUDES . 'engine/modules/theme.php';
		}

		// Module Bank
		if ( wds_engine( 'module_bank' ) ) {
			require_once WDS_INCLUDES . 'engine/modules/bank.php';
		}

		// Dynamic Visibility Module
		if ( wds_engine( 'module_dynamic_visibility' ) ) {
			if ( ! defined( 'WDS_DYVI_PATH' ) ) {
				define( 'WDS_DYVI_PATH', WDS_INCLUDES . 'engine/modules/dynamic-visibility/' );
			}

			require_once WDS_DYVI_PATH . 'module.php';
		}

		// Engine register
		if ( class_exists( 'CSF' ) ) {
			add_action( 'after_setup_theme', array( $this, 'register_metaboxes' ) );
			add_action( 'init', array( $this, 'register_cpt' ) );
			add_action( 'init', array( $this, 'register_taxonomy' ) );
			add_shortcode( 'wds', array( $this, 'register_shortcode' ) );
		}

		require_once WDS_INCLUDES . 'engine/contents/auto-insert-data.php';
		require_once WDS_INCLUDES . 'engine/contents/sales-proof.php';
		require_once WDS_INCLUDES . 'engine/contents/tracking.php';
		if ( wds_is_meta_capi() ) {
			require_once WDS_INCLUDES . 'engine/contents/capi.php';
		}

		require_once WDS_INCLUDES . 'engine/tools/components.php';
		require_once WDS_INCLUDES . 'engine/tools/notifications.php';
		require_once WDS_INCLUDES . 'engine/tools/optimizations.php';

		require_once WDS_INCLUDES . 'engine/tools/contact.php';
		require_once WDS_INCLUDES . 'engine/tools/validation.php';
	}

	/**
	 * Register metaboxes.
	 */
	public function register_metaboxes() {
		$metaboxes = wds_engine( 'metabox' );
		if ( ! empty( $metaboxes ) ) {
			foreach ( $metaboxes as $metabox ) {
				if ( empty( $metabox['title'] ) || empty( $metabox['type'] ) || empty( $metabox['section'] ) ) {
					continue;
				}

				$title = $metabox['title'];
				$slug  = sanitize_title( $title );
				$type  = $metabox['type'];

				if ( 'post' == $type ) {
					if ( empty( $metabox['condition'] ) ) {
						continue;
					}

					\CSF::createMetabox(
						$slug,
						array(
							'title'     => $title,
							'post_type' => $metabox['condition'],
							'data_type' => 'unserialize',
							'theme'     => 'light',
							'nav'       => 'inline',
						)
					);
				} elseif ( 'user' == $type ) {
					\CSF::createProfileOptions(
						$slug,
						array(
							'data_type' => 'unserialize',
						)
					);
				} elseif ( 'taxonomy' == $type ) {
					if ( empty( $metabox['objects'] ) ) {
						continue;
					}

					\CSF::createTaxonomyOptions(
						$slug,
						array(
							'taxonomy'  => $metabox['objects'],
							'data_type' => 'unserialize',
						)
					);
				}

				foreach ( $metabox['section'] as $section ) {
					if ( empty( $section['field'] ) ) {
						continue;
					}

					$name = $section['name'];

					$fields = array();
					foreach ( $section['field'] as $field ) {
						$new_field = array(
							'id'       => $field['key'],
							'type'     => $field['type'],
							'title'    => $field['label'],
							'subtitle' => 'Name: ' . $field['key'],
						);

						if ( 'datetime' == $field['type'] ) {
							$new_field['settings'] = array(
								'enableTime' => true,
								'dateFormat' => 'Y-m-d H:i',
							);
						} elseif ( 'date' == $field['type'] ) {
							$new_field['settings'] = array( 'dateFormat' => 'yy-mm-dd' );
						}

						$fields[] = $new_field;
					}

					\CSF::createSection(
						$slug,
						array(
							'title'  => $name,
							'fields' => $fields,
						)
					);
				}
			}
		}
	}

	/**
	 * Register custom post types.
	 */
	public function register_cpt() {
		$post_types = wds_engine( 'post-type' );
		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $cpt ) {
				if ( empty( $cpt['name'] ) || empty( $cpt['slug'] ) || empty( $cpt['configuration'] ) ) {
					continue;
				}

				$name   = $cpt['name'];
				$slug   = sanitize_title( $cpt['slug'] );
				$config = $cpt['configuration'];

				$singular_name      = wds_sanitize_data_field( $config, 'singular_name', $name );
				$menu_name          = wds_sanitize_data_field( $config, 'menu_name', $name );
				$add_new            = wds_sanitize_data_field( $config, 'add_new', 'Add New' );
				$add_new_item       = wds_sanitize_data_field( $config, 'add_new_item', 'Add New' );
				$new_item           = wds_sanitize_data_field( $config, 'new_item', 'New ' . $singular_name );
				$edit_item          = wds_sanitize_data_field( $config, 'edit_item', 'Edit ' . $singular_name );
				$view_item          = wds_sanitize_data_field( $config, 'view_item', 'View ' . $singular_name );
				$all_items          = wds_sanitize_data_field( $config, 'all_items', 'All ' . $singular_name );
				$search_items       = wds_sanitize_data_field( $config, 'search_itemse', 'Search ' . $singular_name );
				$not_found          = wds_sanitize_data_field( $config, 'not_found', 'No ' . $singular_name . ' found' );
				$not_found_in_trash = wds_sanitize_data_field( $config, 'not_found_in_trash', 'No ' . $singular_name . ' found in trash' );

				$public             = wds_sanitize_data_field( $config, 'public', false );
				$publicly_queryable = wds_sanitize_data_field( $config, 'publicly_queryable', false );
				$show_ui            = wds_sanitize_data_field( $config, 'show_ui', false );
				$show_in_menu       = wds_sanitize_data_field( $config, 'show_in_menu', false );
				$show_in_rest       = wds_sanitize_data_field( $config, 'show_in_rest', false );
				$query_var          = wds_sanitize_data_field( $config, 'query_var', false );
				$rewrite            = wds_sanitize_data_field( $config, 'rewrite', $slug );
				$has_archive        = wds_sanitize_data_field( $config, 'has_archive', false );
				$hierarchical       = wds_sanitize_data_field( $config, 'hierarchical', false );
				$position           = wds_sanitize_data_field( $config, 'position', 5 );
				$icon               = wds_sanitize_data_field( $config, 'icon', 'dashicons-menu' );
				$support            = wds_sanitize_data_field( $config, 'support', array( 'title', 'editor', 'thumbnail' ) );

				$labels = array(
					'name'               => $name,
					'singular_name'      => $singular_name,
					'menu_name'          => $menu_name,
					'name_admin_bar'     => $singular_name,
					'add_new'            => $add_new,
					'add_new_item'       => $add_new_item,
					'new_item'           => $new_item,
					'edit_item'          => $edit_item,
					'view_item'          => $view_item,
					'all_items'          => $all_items,
					'search_items'       => $search_items,
					'not_found'          => $not_found,
					'not_found_in_trash' => $not_found_in_trash,
				);

				$args = array(
					'labels'             => $labels,
					'public'             => $public ? true : false,
					'publicly_queryable' => $publicly_queryable ? true : false,
					'show_ui'            => $show_ui ? true : false,
					'show_in_menu'       => $show_in_menu ? true : false,
					'show_in_rest'       => $show_in_rest ? true : false,
					'query_var'          => $query_var ? true : false,
					'rewrite'            => array( 'slug' => $rewrite ),
					'capability_type'    => 'post',
					'has_archive'        => $has_archive ? true : false,
					'hierarchical'       => $hierarchical ? true : false,
					'menu_position'      => intval( $position ),
					'menu_icon'          => $icon,
					'supports'           => $support,
				);

				register_post_type( $slug, $args );
			}
		}
	}

	/**
	 * Register taxonomy.
	 */
	public function register_taxonomy() {
		$taxonomies = wds_engine( 'taxonomy' );
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( empty( $taxonomy['name'] ) || empty( $taxonomy['slug'] ) || empty( $taxonomy['post_type'] ) || empty( $taxonomy['configuration'] ) ) {
					continue;
				}

				$name      = $taxonomy['name'];
				$slug      = sanitize_title( $taxonomy['slug'] );
				$post_type = $taxonomy['post_type'];
				$config    = $taxonomy['configuration'];

				$singular_name = wds_sanitize_data_field( $config, 'singular_name', $name );
				$search_items  = wds_sanitize_data_field( $config, 'search_items', 'Search ' . $singular_name );
				$all_items     = wds_sanitize_data_field( $config, 'all_items', 'All ' . $singular_name );
				$edit_item     = wds_sanitize_data_field( $config, 'edit_item', 'Edit ' . $singular_name );
				$view_item     = wds_sanitize_data_field( $config, 'view_item', 'View ' . $singular_name );
				$update_item   = wds_sanitize_data_field( $config, 'update_item', 'Update' );
				$add_new_item  = wds_sanitize_data_field( $config, 'add_new_item', 'Add New' );
				$new_item_name = wds_sanitize_data_field( $config, 'new_item_name', 'New Item' );
				$parent_item   = wds_sanitize_data_field( $config, 'parent_item', 'Parent Category' );
				$not_found     = wds_sanitize_data_field( $config, 'not_found', 'Not found' );

				$public             = wds_sanitize_data_field( $config, 'public', false );
				$publicly_queryable = wds_sanitize_data_field( $config, 'publicly_queryable', false );
				$show_ui            = wds_sanitize_data_field( $config, 'show_ui', false );
				$show_in_menu       = wds_sanitize_data_field( $config, 'show_in_menu', false );
				$query_var          = wds_sanitize_data_field( $config, 'query_var', false );
				$rewrite            = wds_sanitize_data_field( $config, 'rewrite', $slug );
				$show_in_rest       = wds_sanitize_data_field( $config, 'show_in_rest', false );
				$hierarchical       = wds_sanitize_data_field( $config, 'hierarchical', false );

				$labels = array(
					'name'          => $name,
					'menu_name'     => $name,
					'singular_name' => $singular_name,
					'search_items'  => $search_items,
					'all_items'     => $all_items,
					'edit_item'     => $edit_item,
					'view_item'     => $view_item,
					'update_item'   => $update_item,
					'add_new_item'  => $add_new_item,
					'new_item_name' => $new_item_name,
					'parent_item'   => $parent_item,
					'not_found'     => $not_found,
				);

				$args = array(
					'labels'             => $labels,
					'public'             => $public ? true : false,
					'publicly_queryable' => $publicly_queryable ? true : false,
					'show_ui'            => $show_ui ? true : false,
					'show_in_menu'       => $show_in_menu ? true : false,
					'query_var'          => $query_var ? true : false,
					'rewrite'            => array( 'slug' => $rewrite ),
					'show_in_rest'       => $show_in_rest ? true : false,
					'hierarchical'       => $hierarchical ? true : false,
				);

				register_taxonomy( $slug, $post_type, $args );
			}
		}
	}

	/**
	 * Register shortcode.
	 *
	 * @param array $atts The attribute shortcode.
	 */
	public function register_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'source'      => '',
				'post_meta'   => '',
				'user_meta'   => '',
				'term_meta'   => '',
				'context'     => '',
				'filter'      => '',
				'fallback'    => '',
				'callback'    => '',
				'date_format' => '',
			),
			$atts,
			'wds'
		);

		$value = '';

		// Handle 'post' source
		if ( 'post' == $atts['source'] && ! empty( $atts['post_meta'] ) ) {
			global $post;
			$meta_value = wds_post_meta( $post->ID, $atts['post_meta'] );
			if ( $meta_value ) {
				$value = $meta_value;
			}
		}

		// Handle 'user' source
		if ( 'user' == $atts['source'] && ! empty( $atts['user_meta'] ) ) {
			$user_id = 0;
			if ( 'current_user' == $atts['context'] ) {
				$user_id = get_current_user_id();
			} elseif ( 'current_post_author' == $atts['context'] ) {
				global $post;
				$user_id = $post->post_author;
			} elseif ( 'replica' == $atts['context'] ) {
				$user_id = wds_data( 'reseller_id' );
			}

			if ( $user_id ) {
				$user_meta_value = get_user_meta( $user_id, $atts['user_meta'], true );
				if ( $user_meta_value ) {
					$value = $user_meta_value;
				}
			}
		}

		// Handle 'taxonomy' source
		if ( 'taxonomy' == $atts['source'] && ! empty( $atts['term_meta'] ) ) {
			global $post;

			$taxonomy   = wds_get_taxonomy_by_post_id( $post->ID );
			$theme_id   = wds_get_taxonomy_theme_id( $post->ID, $taxonomy );
			$meta_value = get_term_meta( $theme_id, $atts['term_meta'], true );

			if ( $meta_value ) {
				$value = $meta_value;
			}
		}

		// Apply filter
		if ( '1' == $atts['filter'] && ! empty( $atts['callback'] ) ) {
			if ( 'date' == $atts['callback'] && ! empty( $atts['date_format'] ) ) {
				$timestamp = is_numeric( $value ) ? $value : strtotime( $value );
				$value     = date_i18n( $atts['date_format'], $timestamp );
			} elseif ( 'initial' == $atts['callback'] ) {
				$value = substr( $value, 0, 1 );
			} elseif ( 'currency' == $atts['callback'] ) {
				$value = wds_convert_money( intval( $value ) );
			} elseif ( 'background_slideshow' == $atts['callback'] ) {
				$gallery_images = wds_post_meta( get_the_ID(), $atts['post_meta'] );

				if ( $gallery_images ) {
					$gallery_data = array();
					if ( wds_check_array( $gallery_images, true ) ) {
						foreach ( $gallery_images as $image ) {
							if ( wds_check_array( $image, true ) && isset( $image['id'] ) && isset( $image['url'] ) ) {
								$gallery_data[] = array(
									'url' => $image['url'],
								);
							}
						}
					} else {
						$values = explode( ',', $gallery_images );
						foreach ( $values as $id ) {
							$image_id  = intval( $id );
							$image_url = wp_get_attachment_url( $image_id );
							if ( $image_url ) {
								$gallery_data[] = array(
									'url' => $image_url,
								);
							}
						}
					}

					$gallery_json = wp_json_encode( $gallery_data );

					$value = '<script>
								var slideshowId_' . esc_js( $atts['post_meta'] ) . ' = "' . esc_js( $atts['post_meta'] ) . '";
								var galleryData_' . esc_js( $atts['post_meta'] ) . ' = ' . $gallery_json . ';

								document.addEventListener("DOMContentLoaded", function () {
									function updateSlideshowImages(id, data) {
										var slideshowContainer = document.getElementById(id);

										if (!slideshowContainer) {
											// console.log("DEBUG: Slideshow container #" + id + " belum ditemukan.");
											return false; // Mengindikasikan belum siap
										}

										var swiperContainer = slideshowContainer.querySelector(".elementor-background-slideshow");
										var swiperWrapper = slideshowContainer.querySelector(".swiper-wrapper");

										if (!swiperContainer || !swiperWrapper) {
											// console.log("DEBUG: Swiper container atau wrapper di #" + id + " belum ditemukan.");
											return false;
										}

										// console.log("DEBUG: Semua elemen slideshow #" + id + " ditemukan. Melanjutkan update.");

										if (data.length === 1) {
											var singleImage = data[0];
											var settings = JSON.parse(slideshowContainer.getAttribute("data-settings") || "{}");
											
											swiperContainer.style.backgroundImage = "url(" + singleImage.url + ")";
											swiperContainer.style.backgroundSize = settings.background_slideshow_background_size || "cover";
											swiperContainer.style.backgroundPosition = settings.background_slideshow_background_position || "center";
											swiperContainer.style.backgroundRepeat = settings.background_slideshow_background_repeat || "no-repeat";
											swiperWrapper.style.display = "none";
											// console.log("DEBUG: Satu gambar background untuk #" + id + " ditampilkan.");
											return true;
										}

										if (data.length > 0) {
											var settings = JSON.parse(slideshowContainer.getAttribute("data-settings") || "{}");
											var transitionEffect = settings.background_slideshow_slide_transition || "fade";
											var slideDuration = settings.background_slideshow_slide_duration || 5000;
											var transitionDuration = settings.background_slideshow_transition_duration || 1000;
											var kenBurnsEnabled = settings.background_slideshow_ken_burns === "yes";
											var kenBurnsType = settings.background_slideshow_ken_burns_zoom_direction || "in";

											if (kenBurnsEnabled) {
												slideshowContainer.classList.add("elementor-ken-burns-active");
											}

											swiperWrapper.innerHTML = "";
											
											data.forEach(function (img) {
												var slide = document.createElement("div");
												slide.className = "elementor-background-slideshow__slide swiper-slide";

												var imageWrapper = document.createElement("div");
												imageWrapper.className = "elementor-background-slideshow__slide__image";

												if (kenBurnsEnabled) {
													imageWrapper.classList.add("elementor-ken-burns");
													imageWrapper.classList.add("elementor-ken-burns--" + kenBurnsType);
												}

												imageWrapper.style.backgroundImage = "url(" + img.url + ")";
												slide.appendChild(imageWrapper);
												swiperWrapper.appendChild(slide);
											});

											if (swiperContainer.swiper) {
												swiperContainer.swiper.destroy(true, true);
												// console.log("DEBUG: Swiper lama di #" + id + " dihancurkan.");
											}

											new Swiper(swiperContainer, {
												loop: settings.background_slideshow_loop === "yes",
												autoplay: {
													delay: slideDuration,
													disableOnInteraction: false
												},
												speed: transitionDuration,
												effect: transitionEffect,
												on: {
													slideChangeTransitionStart: function () {
														swiperWrapper.querySelectorAll(".elementor-ken-burns--active").forEach(function (el) {
															el.classList.remove("elementor-ken-burns--active");
														});
														var active = swiperWrapper.querySelector(".swiper-slide-active .elementor-ken-burns");
														if (active) {
															active.classList.add("elementor-ken-burns--active");
														}
													}
												}
											});
											// console.log("DEBUG: Gallery #" + id + " berhasil diupdate dan Swiper diinisialisasi ulang.");
											return true;
										}
										return false;
									}

									var currentSlideshowId = slideshowId_' . esc_js( $atts['post_meta'] ) . ';
									var currentGalleryData = galleryData_' . esc_js( $atts['post_meta'] ) . ';

									if (currentGalleryData.length > 0) {
										setTimeout(function() {
											// console.log("DEBUG: Mencoba update awal untuk #" + currentSlideshowId + " setelah 750ms.");
											if (!updateSlideshowImages(currentSlideshowId, currentGalleryData)) {

												var targetNode = document.body;
												var config = { childList: true, subtree: true };

												var observer = new MutationObserver(function(mutationsList, observerInstance) {
													// console.log("DEBUG: MutationObserver callback dipicu untuk #" + currentSlideshowId + ".");
													if (updateSlideshowImages(currentSlideshowId, currentGalleryData)) {
														observerInstance.disconnect();
														// console.log("DEBUG: MutationObserver untuk #" + currentSlideshowId + " dihentikan.");
													}
												});

												// console.log("DEBUG: Memulai MutationObserver untuk #" + currentSlideshowId + ".");
												observer.observe(targetNode, config);
											} else {
												// console.log("DEBUG: Update awal untuk #" + currentSlideshowId + " berhasil.");
											}
										}, 750); // Delay awal 750ms
									}
								});
							</script>';

					return $value;
				}
			}
		}

		// Fallback
		if ( empty( $value ) && ! empty( $atts['fallback'] ) ) {
			$value = $atts['fallback'];
		}

		return wp_kses_post( $value );
	}
}

new Main();
