<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Post_ID
 */
class Post_ID extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'post-id';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Post ID is', 'wds-notrans' );
	}

	/**
	 * Get the group of the condition.
	 *
	 * @return string
	 */
	public function get_group() {
		return 'posts';
	}

	/**
	 * Check if the current value meets the condition criteria.
	 *
	 * @param array $args Arguments to check the condition.
	 * @return bool True if the condition is met, false otherwise.
	 */
	public function check( $args = array() ) {
		$type     = ! empty( $args['type'] ) ? $args['type'] : 'show';
		$post_ids = $this->explode_string( $args['value'] );

		if ( 'hide' === $type ) {
			return ! in_array( get_the_ID(), $post_ids );
		} else {
			return in_array( get_the_ID(), $post_ids );
		}
	}

	/**
	 * Check if is condition available for meta fields control.
	 *
	 * @return bool True if the condition is met, false otherwise.
	 */
	public function is_for_fields() {
		return false;
	}

	/**
	 * Determine if value detection is needed for the condition.
	 *
	 * @return bool True if value detection is needed, false otherwise.
	 */
	public function need_value_detect() {
		return true;
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new Post_ID() );
	}
);
