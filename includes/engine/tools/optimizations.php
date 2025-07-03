<?php

namespace WDS\Engine\Tools;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Optimizations Class.
 *
 * @since 2.0.0
 */
class Optimizations {

	/**
	 * Singleton instance of Optimizations class.
	 *
	 * @var Optimizations|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the Optimizations class.
	 *
	 * @return Optimizations Singleton instance.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Auto Resize Image, Convert Image to WebP, Compress Image, Remove Metadata
		add_filter( 'wp_handle_upload', array( $this, 'image_optimization' ) );

		// Auto Remove Attachments
		if ( wds_engine( 'autoremove_attachments' ) ) {
			add_action( 'before_delete_post', array( $this, 'autoremove_attachments' ) );
		}

		// Disable Auto-generated Image Sizes
		if ( ! empty( wds_engine( 'disable_generated_image' ) ) ) {
			add_action( 'intermediate_image_sizes_advanced', array( $this, 'disable_image_sizes' ) );
			add_action( 'init', array( $this, 'disable_other_image_sizes' ) );
			add_filter( 'big_image_size_threshold', '__return_false' );
		}

		// Auto Replace Image & Gallery
		add_action( 'update_post_metadata', array( $this, 'autoreplace_image' ), 10, 4 );
		add_action( 'update_user_meta', array( $this, 'autoreplace_image_user' ), 10, 4 );
	}

	/**
	 * Image optimizations
	 *
	 * @param array $upload The data upload image.
	 */
	public function image_optimization( $upload ) {
		if ( 'image/jpeg' == $upload['type'] || 'image/png' == $upload['type'] ) {
			$file_path = $upload['file'];
			// Check if ImageMagick or GD is available
			if ( extension_loaded( 'imagick' ) || extension_loaded( 'gd' ) ) {
				$image_editor = wp_get_image_editor( $file_path );
				if ( ! is_wp_error( $image_editor ) ) {

					// Auto Rotated
					if ( function_exists( 'exif_read_data' ) && 'image/jpeg' == $upload['type'] ) {
						$exif = exif_read_data( $file_path );
						if ( isset( $exif['Orientation'] ) ) {
							switch ( $exif['Orientation'] ) {
								case 3:
									$image_editor->rotate( 180 ); // Rotate 180°
									break;
								case 6:
									$image_editor->rotate( -90 ); // Rotate 90° Counter-Clockwise
									break;
								case 8:
									$image_editor->rotate( 90 ); // Rotate 90° Clockwise
									break;
							}
						}
					}

					// Image Resize
					if ( wds_engine( 'image_resize' ) ) {
						$original_dimensions = $image_editor->get_size();

						$resize_data = wds_engine( 'image_resize_data' );

						$max_width  = $resize_data['width'];
						$max_height = $resize_data['height'];

						if ( $original_dimensions['width'] > $max_width ) {
							$image_editor->resize( $max_width, null );
						}
						if ( $original_dimensions['height'] > $max_height ) {
							$image_editor->resize( null, $max_height );
						}
					}

					// Convert to WebP
					if ( wds_engine( 'convert_webp' ) ) {
						$file_info = pathinfo( $file_path );
						$dirname   = $file_info['dirname'];
						$filename  = $file_info['filename'];

						$def_filename  = wp_unique_filename( $dirname, $filename . '.webp' );
						$new_file_path = $dirname . '/' . $def_filename;

						$saved_image = $image_editor->save( $new_file_path, 'image/webp' );
						if ( ! is_wp_error( $saved_image ) && file_exists( $saved_image['path'] ) ) {
							// Success: replace the uploaded image with the WebP image
							$upload['file'] = $saved_image['path'];
							$upload['url']  = str_replace( basename( $upload['url'] ), basename( $saved_image['path'] ), $upload['url'] );
							$upload['type'] = 'image/webp';

							// Optionally remove the original image
							@unlink( $file_path ); // phpcs:ignore

							$this->remove_metadata( $saved_image['path'] );
						}
					} elseif ( wds_engine( 'image_resize' ) ) {
						$resized_image = $image_editor->save( $file_path );
						$this->remove_metadata( $resized_image['path'] );
						$this->compress_image( $resized_image['path'] );
					} else {
						$this->compress_image( $file_path );
					}
				}
			}
		}

		return $upload;
	}

	/**
	 * Remove metadata
	 * Strips image metadata using Imagick if enabled in settings.
	 *
	 * @param string $path The image path.
	 */
	public function remove_metadata( $path ) {
		if ( wds_engine( 'remove_metadata' ) && extension_loaded( 'imagick' ) ) {
			$imagick = new \Imagick( $path ); // phpcs:ignore
			$imagick->stripImage();
			$imagick->writeImage( $path );
			$imagick->destroy();
		}
	}

	/**
	 * Compress image.
	 *
	 *  @param string $image_path The image path.
	 */
	public function compress_image( $image_path ) {
		if ( ! wds_engine( 'compress_image' ) ) {
			return;
		}

		$image_info = getimagesize( $image_path );

		if ( 'image/jpeg' == $image_info['mime'] ) {
			$image   = imagecreatefromjpeg( $image_path );
			$success = imagejpeg( $image, $image_path, 80 ); // Adjust compression level (0 to 100)
			if ( ! $success ) {
				wds_log( 'JPEG compression failed for image: ' . $image_path );
			}
			imagedestroy( $image );
		} elseif ( 'image/png' == $image_info['mime'] ) {
			$image = imagecreatefrompng( $image_path );
			imagealphablending( $image, false );
			imagesavealpha( $image, true );
			$success = imagepng( $image, $image_path, 8, PNG_ALL_FILTERS ); // Adjust compression level (0 to 9)
			if ( ! $success ) {
				wds_log( 'PNG compression failed for image: ' . $image_path );
			}
			imagedestroy( $image );
		}
	}

	/**
	 * Automatically removes image attachments from a post.
	 *
	 * @param int $post_id Post ID.
	 */
	public function autoremove_attachments( $post_id ) {
		if ( get_post_type( $post_id ) === 'post' ) {
			$attachments = get_attached_media( '', $post_id );
			foreach ( $attachments as $attachment ) {
				if ( wp_attachment_is_image( $attachment->ID ) || wp_attachment_is( 'audio', $attachment->ID ) ) {
					wp_delete_attachment( $attachment->ID, true );
				}
			}
		}
	}

	/**
	 * Removes default WordPress image sizes from the media upload process.
	 *
	 * @param array $sizes The image size.
	 */
	public function disable_image_sizes( $sizes ) {
		unset( $sizes['thumbnail'] );
		unset( $sizes['medium'] );
		unset( $sizes['large'] );
		unset( $sizes['medium_large'] );
		unset( $sizes['1536x1536'] );
		unset( $sizes['2048x2048'] );

		return $sizes;
	}

	/**
	 * Removes custom image sizes from being generated.
	 */
	public function disable_other_image_sizes() {
		remove_image_size( 'post-thumbnail' );
		remove_image_size( 'tp-image-grid' ); // theplusaddon
	}

	/**
	 * Replaces image and deletes the old media if changed.
	 *
	 * @since 1.13.1
	 *
	 * @param mixed  $check     Original check value.
	 * @param int    $object_id Post ID.
	 * @param string $meta_key  Meta key to check.
	 * @param mixed  $meta_value New image data.
	 * @return mixed Original check value.
	 */
	public function autoreplace_image( $check, $object_id, $meta_key, $meta_value ) {

		if ( wds_engine( 'autoreplace_image' ) ) {
			$data = wds_engine( 'autoreplace_image' );

			$array = explode( ',', $data );
			if ( in_array( $meta_key, $array ) ) {
				// get old media
				$old_meta_value = get_post_meta( $object_id, $meta_key, true );
				// checking
				if ( $old_meta_value != $meta_value ) {
					// check value not empty
					if ( ! empty( $old_meta_value ) ) {
						// delete media
						if ( wds_check_array( $old_meta_value, true ) ) {
							wp_delete_attachment( $old_meta_value['id'], true );
						} elseif ( is_numeric( $old_meta_value ) ) {
							wp_delete_attachment( $old_meta_value, true );
						} else {
							$image_id = attachment_url_to_postid( $old_meta_value );
							if ( ! is_wp_error( $image_id ) ) {
								wp_delete_attachment( $image_id, true );
							}
						}
					}
				}
			}
		}

		if ( wds_engine( 'autoreplace_gallery' ) ) {
			$data = wds_engine( 'autoreplace_gallery' );

			$array = explode( ',', $data );
			if ( in_array( $meta_key, $array ) ) {

				$old_meta_value = get_post_meta( $object_id, $meta_key, true );
				$new_meta_value = $meta_value;

				if ( ! empty( $old_meta_value ) ) {
					$old_ids = array();
					$new_ids = array();

					// old value
					if ( is_string( $old_meta_value ) ) {
						$old_ids = array_map( 'trim', explode( ',', $old_meta_value ) );
					} elseif ( wds_check_array( $old_meta_value, true ) ) {
						foreach ( $old_meta_value as $image ) {
							if ( wds_check_array( $image, true ) && isset( $image['id'] ) && isset( $image['url'] ) ) {
								$old_ids[] = intval( $image['id'] );
							}
						}
					}

					// new value
					if ( is_string( $new_meta_value ) ) {
						$new_ids = array_map( 'trim', explode( ',', $new_meta_value ) );
					} elseif ( wds_check_array( $new_meta_value, true ) ) {
						foreach ( $new_meta_value as $image ) {
							if ( wds_check_array( $image, true ) && isset( $image['id'] ) && isset( $image['url'] ) ) {
								$new_ids[] = intval( $image['id'] );
							}
						}
					}

					// checking unused ids
					$unused_ids = array_diff( $old_ids, $new_ids );

					// delete media
					foreach ( $unused_ids as $id ) {
						if ( is_numeric( $id ) ) {
							wp_delete_attachment( $id, true );
						} else {
							$image_id = attachment_url_to_postid( $id );
							if ( ! is_wp_error( $image_id ) ) {
								wp_delete_attachment( $image_id, true );
							}
						}
					}
				}
			}
		}

		return $check;
	}

	/**
	 * Replaces user image and deletes the old media if changed.
	 *
	 * @since 2.4.0
	 *
	 * @param mixed  $check      Original check value.
	 * @param int    $user_id    User ID.
	 * @param string $meta_key   Meta key to check.
	 * @param mixed  $meta_value New image data.
	 * @return mixed Original check value.
	 */
	public function autoreplace_image_user( $check, $user_id, $meta_key, $meta_value ) {
		if ( wds_engine( 'autoreplace_image_user' ) ) {
			$data = wds_engine( 'autoreplace_image_user' );

			$array = explode( ',', $data );
			if ( in_array( $meta_key, $array ) ) {
				// get old media
				$old_meta_value = get_user_meta( $user_id, $meta_key, true );
				// checking
				if ( $old_meta_value != $meta_value ) {
					// check value not empty
					if ( ! empty( $old_meta_value ) ) {
						// delete media
						if ( wds_check_array( $old_meta_value, true ) ) {
							wp_delete_attachment( $old_meta_value['id'], true );
						} elseif ( is_numeric( $old_meta_value ) ) {
							wp_delete_attachment( $old_meta_value, true );
						} else {
							$image_id = attachment_url_to_postid( $old_meta_value );
							if ( ! is_wp_error( $image_id ) ) {
								wp_delete_attachment( $image_id, true );
							}
						}
					}
				}
			}
		}

		if ( wds_engine( 'autoreplace_gallery_user' ) ) {
			$data = wds_engine( 'autoreplace_gallery_user' );

			$array = explode( ',', $data );
			if ( in_array( $meta_key, $array ) ) {

				$old_meta_value = get_user_meta( $user_id, $meta_key, true );
				$new_meta_value = $meta_value;

				if ( ! empty( $old_meta_value ) ) {
					$old_ids = array();
					$new_ids = array();

					// old value
					if ( is_string( $old_meta_value ) ) {
						$old_ids = array_map( 'trim', explode( ',', $old_meta_value ) );
					} elseif ( wds_check_array( $old_meta_value, true ) ) {
						foreach ( $old_meta_value as $image ) {
							if ( wds_check_array( $image, true ) && isset( $image['id'] ) && isset( $image['url'] ) ) {
								$old_ids[] = intval( $image['id'] );
							}
						}
					}

					// new value
					if ( is_string( $new_meta_value ) ) {
						$new_ids = array_map( 'trim', explode( ',', $new_meta_value ) );
					} elseif ( wds_check_array( $new_meta_value, true ) ) {
						foreach ( $new_meta_value as $image ) {
							if ( wds_check_array( $image, true ) && isset( $image['id'] ) && isset( $image['url'] ) ) {
								$new_ids[] = intval( $image['id'] );
							}
						}
					}

					// checking unused ids
					$unused_ids = array_diff( $old_ids, $new_ids );

					// delete media
					foreach ( $unused_ids as $id ) {
						if ( is_numeric( $id ) ) {
							wp_delete_attachment( $id, true );
						} else {
							$image_id = attachment_url_to_postid( $id );
							if ( ! is_wp_error( $image_id ) ) {
								wp_delete_attachment( $image_id, true );
							}
						}
					}
				}
			}
		}

		return $check;
	}
}

Optimizations::instance();
