<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Not_Exists
 */
class Not_Exists extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'not-exists';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Doesn\'t exist', 'wds-notrans' );
	}

	/**
	 * Check if the current value meets the condition criteria.
	 *
	 * @param array $args Arguments to check the condition.
	 * @return bool True if the condition is met, false otherwise.
	 */
	public function check( $args = array() ) {
		$type          = ! empty( $args['type'] ) ? $args['type'] : 'show';
		$current_value = $this->get_current_value( $args );

		if ( 'hide' === $type ) {
			return ! empty( $current_value );
		} else {
			return empty( $current_value );
		}
	}

	/**
	 * Determine if value detection is needed for the condition.
	 *
	 * @return bool True if value detection is needed, false otherwise.
	 */
	public function need_value_detect() {
		return false;
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new Not_Exists() );
	}
);
