<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Between
 */
class Between extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'between';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Between', 'wds-notrans' );
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

		$result = false;

		if ( isset( $values[0] ) && isset( $values[1] ) ) {
			if ( $values[1] > $values[0] ) {
				$result = $values[0] <= $current_value && $values[1] >= $current_value;
			} else {
				$result = $values[1] <= $current_value && $values[0] >= $current_value;
			}
		}

		if ( 'hide' === $type ) {
			return ! $result;
		} else {
			return $result;
		}
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new Between() );
	}
);
