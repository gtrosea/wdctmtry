<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Abstract base class for visibility conditions.
 */
abstract class Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	abstract public function get_id();

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Check the condition with the provided arguments.
	 *
	 * @param array $args Arguments to check the condition.
	 * @return mixed Result of the condition check.
	 */
	abstract public function check( $args = array() );

	/**
	 * Get the group name of the condition.
	 *
	 * @return string|false Group name or false if not applicable.
	 */
	public function get_group() {
		return false;
	}

	/**
	 * Determine if the condition is applicable for fields.
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_for_fields() {
		return true;
	}

	/**
	 * Determine if value detection is needed for the condition.
	 *
	 * @return bool True if value detection is needed, false otherwise.
	 */
	public function need_value_detect() {
		return true;
	}

	/**
	 * Determine if type detection is needed for the condition.
	 *
	 * @return bool True if type detection is needed, false otherwise.
	 */
	public function need_type_detect() {
		return false;
	}

	/**
	 * Get the current value based on provided arguments.
	 *
	 * @param array $args Arguments to retrieve the current value.
	 * @return mixed|null Current value or null if not found.
	 */
	public function get_current_value( $args = array() ) {
		$current_value = null;

		if ( ! empty( $args['field_raw'] ) ) {
			$current_value = get_post_meta( get_the_ID(), $args['field_raw'], true );
		} elseif ( ! empty( $args['field'] ) ) {
			$current_value = $args['field'];
		}

		return $current_value;
	}

	/**
	 * Convert checkboxes array to a list of selected values.
	 *
	 * @param array $array Array of checkbox values.
	 * @return array List of selected values.
	 */
	public function checkboxes_to_array( $array = array() ) {
		$result = array();
		foreach ( $array as $value => $bool ) {
			$bool = filter_var( $bool, FILTER_VALIDATE_BOOLEAN );

			if ( $bool ) {
				$result[] = $value;
			}
		}

		return $result;
	}

	/**
	 * Adjust the type of values for comparison.
	 *
	 * @param mixed  $current_value The current value to compare.
	 * @param mixed  $value_to_compare The value to compare against.
	 * @param string $data_type Type of data ('numeric' or default).
	 * @return array Array containing adjusted values for comparison.
	 */
	public function adjust_values_type( $current_value, $value_to_compare, $data_type ) {
		switch ( $data_type ) {
			case 'numeric':
				$current_value    = intval( $current_value );
				$value_to_compare = intval( $value_to_compare );
				break;

			default:
				$current_value    = strval( $current_value );
				$value_to_compare = strval( $value_to_compare );
				break;
		}

		return array(
			'current' => $current_value,
			'compare' => $value_to_compare,
		);
	}

	/**
	 * Explode a comma-separated string into an array.
	 *
	 * @param string|null $value The string to explode.
	 * @return array Array of exploded values.
	 */
	public function explode_string( $value = null ) {
		if ( wds_is_empty( $value ) ) {
			return array();
		}

		$value = explode( ',', $value );
		$value = array_map( 'trim', $value );

		return $value;
	}

	/**
	 * Get custom controls for the condition.
	 *
	 * @return array|false Array of custom controls or false if none.
	 */
	public function get_custom_controls() {
		return false;
	}
}
