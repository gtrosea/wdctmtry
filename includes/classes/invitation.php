<?php
/**
 * WeddingSaas Invitation.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Invitation Class.
 */
class WDS_Invitation {

	/**
	 * Get invitation data.
	 *
	 * @param int $post_id The post ID.
	 * @return array Associative array with 'term_id', 'taxonomy'.
	 */
	public function get_data( $post_id ) {
		return array(
			'category_id' => $this->get_category( $post_id ),
			'term_id'     => $this->get_term_id( $post_id ),
			'taxonomy'    => $this->get_taxonomy( $post_id ),
		);
	}

	/**
	 * Get category for the given post.
	 *
	 * @since 2.3.0
	 * @param int $post_id The post ID.
	 * @return string The category.
	 */
	public function get_category( $post_id ) {
		$categories = wp_get_post_categories( $post_id );
		return $categories[0];
	}

	/**
	 * Get taxonomy metadata for a post's primary category.
	 *
	 * @param int $post_id The post ID.
	 * @return mixed The taxonomy metadata.
	 */
	public function get_taxonomy( $post_id ) {
		$categories  = wp_get_post_categories( $post_id );
		$category_id = $categories[0];
		$taxonomy    = wds_term_meta( $category_id, '_template' );

		return $taxonomy;
	}

	/**
	 * Get term ID for the given post.
	 *
	 * @param int $post_id The post ID.
	 * @return array|false Term data or false if not found.
	 */
	public function get_term_id( $post_id ) {
		$taxonomy = $this->get_taxonomy( $post_id );
		$terms    = wp_get_post_terms( $post_id, $taxonomy );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				return $term->term_id;
			}
		}

		return false;
	}

	/**
	 * Get subtheme slug for the given post.
	 *
	 * @since 2.0.5
	 * @param int $post_id The post ID.
	 * @return string|false slug data or false if not found.
	 */
	public function get_subtheme( $post_id ) {
		$taxonomy = $this->get_taxonomy( $post_id );
		$terms    = wp_get_post_terms( $post_id, $taxonomy );
		$term     = reset( $terms ); // get first term

		if ( $term ) {
			$parent_id = $term->parent;

			if ( $parent_id ) {
				$parent_term = get_term( $parent_id, $taxonomy );

				if ( ! is_wp_error( $parent_term ) ) {
					return $parent_term->slug;
				}
			}
		}

		return false;
	}

	/**
	 * Get term thumbnail.
	 *
	 * @param int    $term_id The term ID.
	 * @param string $default The default thumbnail.
	 * @return string The url thumbnail.
	 */
	public function get_term_thumbnail( $term_id, $default = WDS_BLANK_IMAGE ) {
		$thumbnail = wds_term_meta( $term_id, '_thumbnail' );
		$thumbnail = $thumbnail ? $thumbnail : wds_term_meta( $term_id, '_custom_thumbnail' );
		$thumbnail = ! empty( $thumbnail ) ? $thumbnail : $default;

		return $thumbnail;
	}

	/**
	 * Get term icon.
	 *
	 * @param int $term_id The term ID.
	 * @return string The url icon.
	 */
	public function get_term_icon( $term_id ) {
		$icon = wds_term_meta( $term_id, '_icon' );
		$icon = $icon ? $icon : wds_term_meta( $term_id, '_custom_icon' );

		return $icon;
	}

	/**
	 * Get IDs of subtheme terms that have child terms.
	 *
	 * @return array List of subtheme term IDs.
	 */
	public function get_subtheme_ids() {
		$all_categories = get_categories( array( 'hide_empty' => false ) );
		$all_taxonomy   = array();
		$data           = array();

		// filter taxonomy
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
	 * Get list audio titles and links based on the source setting from posts or a custom table.
	 *
	 * @return array An associative array of audio links and titles.
	 */
	public function get_list_audio() {
		$source = wds_engine( 'audio' );

		$audio_data = array();
		if ( wds_engine( 'module_audio' ) && ( empty( $source ) || 'default' == $source ) ) {
			$pages_query = new \WP_Query(
				array(
					'post_type'      => 'wds_audio',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
				)
			);

			if ( $pages_query->have_posts() ) {
				while ( $pages_query->have_posts() ) {
					$pages_query->the_post();
					$post_id = get_the_ID();
					$title   = get_the_title();
					$link    = esc_url( wds_post_meta( $post_id, '_link' ) );

					$audio_data[ $link ] = html_entity_decode( $title );
				}

				wp_reset_postdata();
			}

			return $audio_data;

		} elseif ( 'cct' == $source ) {
			global $wpdb;

			$cct  = wds_engine( 'audio_cct' );
			$slug = $cct['slug'];
			$name = $cct['name'];
			$url  = $cct['url'];

			$table_name = $wpdb->prefix . 'jet_cct_' . $slug;
			$results    = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT `$name`, `$url` FROM $table_name WHERE cct_status = %s", // phpcs:ignore
					'publish'
				)
			);
			if ( $results ) {
				foreach ( $results as $result ) {

					$column_name = $result->$name;
					$column_url  = esc_url( $result->$url );

					$audio_data[ $column_url ] = stripslashes( html_entity_decode( $column_name ) );
				}
			}

			asort( $audio_data );

			return $audio_data;
		}

		return $audio_data;
	}

	/**
	 * Retrieves the audio title based on the given URL.
	 *
	 * @since 2.1.0
	 * @param string $url The audio URL to look up.
	 * @return string|false The audio title if found, otherwise false.
	 */
	public function get_audio_name( $url ) {
		foreach ( $this->get_list_audio() as $audio => $title ) {
			if ( $url == $audio ) {
				return $title;
			}
		}
		return false;
	}
}
