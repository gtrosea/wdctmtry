<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Archive_Tax
 */
class Archive_Tax extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'archive-tax';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Taxonomy Archive is', 'wds-notrans' );
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
		$type = ! empty( $args['type'] ) ? $args['type'] : 'show';
		$tax  = $this->explode_string( $args['value'] );

		if ( in_array( 'category', $tax ) && 'post' === get_post_type() ) {
			$result = is_category();
		} elseif ( in_array( 'post_tag', $tax ) && 'post' === get_post_type() ) {
			$result = is_tag();
		} else {
			$result = is_tax( $tax );
		}

		if ( 'hide' === $type ) {
			return ! $result;
		} else {
			return $result;
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
		$manager->register_condition( new Archive_Tax() );
	}
);
