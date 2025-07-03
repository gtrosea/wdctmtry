<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Greater_Than
 */
class Greater_Than extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'greater-than';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Greater than', 'wds-notrans' );
	}

	/**
	 * Check if the current value meets the condition criteria.
	 *
	 * @param array $args Arguments to check the condition.
	 * @return bool True if the condition is met, false otherwise.
	 */
	public function check( $args = array() ) {
		$type          = ! empty( $args['type'] ) ? $args['type'] : 'show';
		$data_type     = ! empty( $args['data_type'] ) ? $args['data_type'] : 'chars';
		$current_value = $this->get_current_value( $args );
		$value         = $args['value'];
		$values        = $this->adjust_values_type( $current_value, $value, $data_type );

		if ( 'hide' === $type ) {
			return $values['current'] <= $values['compare'];
		} else {
			return $values['current'] > $values['compare'];
		}
	}

	/**
	 * Determine if type detection is needed for the condition.
	 *
	 * @return bool True if type detection is needed, false otherwise.
	 */
	public function need_type_detect() {
		return true;
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new Greater_Than() );
	}
);
