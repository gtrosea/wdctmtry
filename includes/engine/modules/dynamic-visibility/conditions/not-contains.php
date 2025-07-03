<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Not_Contains
 */
class Not_Contains extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'not-contains';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Doesn\'t contain', 'wds-notrans' );
	}

	/**
	 * Check if the current value meets the condition criteria.
	 *
	 * @param array $args Arguments to check the condition.
	 * @return bool True if the condition is met, false otherwise.
	 */
	public function check( $args = array() ) {
		$type          = ! empty( $args['type'] ) ? $args['type'] : 'show';
		$values        = $this->explode_string( $args['value'] );
		$current_value = $this->get_current_value( $args );

		if ( wds_check_array( $current_value, true ) ) {
			$current_value = wp_json_encode( $current_value );
		}

		$found = false;
		foreach ( $values as $value ) {
			if ( null !== $current_value && false !== strpos( $current_value, $value ) ) {
				$found = true;
			}
		}

		if ( 'hide' === $type ) {
			return $found;
		} else {
			return ! $found;
		}
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new Not_Contains() );
	}
);
