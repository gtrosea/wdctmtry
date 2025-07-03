<?php

namespace WDS\Engine\Contents;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * AutoInsert Class.
 *
 * @since 2.0.0
 */
class AutoInsert {

	/**
	 * Singleton instance of AutoInsert class.
	 *
	 * @var AutoInsert|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the AutoInsert class.
	 *
	 * @return AutoInsert Singleton instance.
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
		add_action( 'transition_post_status', array( $this, 'auto_insert_data' ), 10, 3 );
	}

	/**
	 * Automatically inserts data into post meta upon post publication.
	 *
	 * This function is triggered when a post's status changes to 'publish' from any other status except 'draft' or 'trash'.
	 * It retrieves the post's category slug and checks if it matches any category in the `insert_data` array from `wds_engine`.
	 * If a match is found or the slug is set to 'all', it updates the post's meta with specific data fields.
	 *
	 * @param string  $new_status New status of the post.
	 * @param string  $old_status Old status of the post.
	 * @param WP_Post $post       Post object.
	 */
	public function auto_insert_data( $new_status, $old_status, $post ) {
		// Check if post status transitioned to 'publish' and is of post type 'post'
		if ( 'publish' === $new_status && 'publish' !== $old_status && 'draft' !== $old_status && 'trash' !== $old_status && 'post' === $post->post_type ) {
			$post_id       = $post->ID;
			$categories    = get_the_category( $post_id ); // Get post categories
			$category_slug = $categories[0]->slug; // Get the slug of the first category

			// Fetch the master data from the engine
			$master = wds_engine( 'insert_data' );
			if ( ! empty( $master ) ) {
				foreach ( $master as $item ) {
					$slug = (array) $item['category'];
					$data = $item['data'];

					// Match category slug and update post meta
					if ( in_array( $category_slug, $slug, true ) || in_array( 'all', $slug, true ) ) {
						foreach ( $data as $field ) {
							$name  = $field['name'];
							$type  = $field['type'];
							$value = $field[ $type ];
							update_post_meta( $post_id, $name, $value ); // Update post meta with provided data
						}
					}
				}
			}
		}
	}
}

AutoInsert::instance();
