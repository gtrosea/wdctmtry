<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Contains
 */
class Contains extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'contains';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Contains', 'wds-notrans' );
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

		if ( 'hide' === $type ) {
			foreach ( $values as $value ) {
				if ( null !== $current_value && false !== strpos( $current_value, $value ) ) {
					return false;
				}
			}

			return true;
		} else {
			foreach ( $values as $value ) {
				if ( null !== $current_value && false !== strpos( $current_value, $value ) ) {
					return true;
				}
			}

			return false;
		}
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new Contains() );
	}
);
