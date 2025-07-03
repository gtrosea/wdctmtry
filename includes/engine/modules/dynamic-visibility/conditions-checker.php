<?php

namespace WDS\Engine\DyVi;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Condition_Checker
 *
 * Handles checking conditions for dynamic visibility settings.
 *
 * @since 1.13.0
 */
class Condition_Checker {

	/**
	 * Checks the conditions for dynamic visibility.
	 *
	 * @param array $settings The settings array for the conditions.
	 * @param array $dynamic_settings The dynamic settings array for the conditions.
	 * @return bool True if the conditions are met, false otherwise.
	 */
	public function check_cond( $settings = array(), $dynamic_settings = array() ) {
		$is_enabled = ! empty( $settings['wdsdv_enabled'] ) ? $settings['wdsdv_enabled'] : false;
		$is_enabled = filter_var( $is_enabled, FILTER_VALIDATE_BOOLEAN );

		// If dynamic visibility is not enabled, return true by default.
		if ( ! $is_enabled ) {
			return true;
		}

		$conditions     = $dynamic_settings['wdsdv_conditions'];
		$relation       = ! empty( $settings['wdsdv_relation'] ) ? $settings['wdsdv_relation'] : 'AND';
		$is_or_relation = 'OR' === $relation;
		$type           = ! empty( $settings['wdsdv_type'] ) ? $settings['wdsdv_type'] : 'show';
		$has_conditions = false;
		$result         = true;

		// Iterate through each condition to check if it meets the criteria
		foreach ( $conditions as $index => $condition ) {
			$args = array(
				'type'      => $type,
				'condition' => null,
				// 'user_role' => null,
				// 'user_id'   => null,
				'field'     => null,
				'value'     => null,
				'data_type' => null,
			);

			// Populate arguments with settings from condition or use defaults
			foreach ( $args as $arg => $default ) {
				$key          = 'wdsdv_' . $arg;
				$args[ $arg ] = ! wds_is_empty( $condition, $key ) ? $condition[ $key ] : $default;
			}

			$is_dynamic_field = isset( $condition['__dynamic__']['wdsdv_field'] );
			$is_empty_field   = empty( $condition['wdsdv_field'] );

			$args['field_raw'] = ( ! $is_dynamic_field && ! $is_empty_field ) ? $condition['wdsdv_field'] : null;

			// Skip if no condition is set
			if ( empty( $args['condition'] ) ) {
				continue;
			}

			$condition_id       = $args['condition'];
			$condition_instance = Module::instance()->conditions->get_condition( $condition_id );

			// Skip if condition instance is not found
			if ( ! $condition_instance ) {
				continue;
			}

			if ( ! $has_conditions ) {
				$has_conditions = true;
			}

			$custom_value_key = 'value_' . $condition_instance->get_id();
			$custom_value     = ! empty( $condition[ $custom_value_key ] ) ? $condition[ $custom_value_key ] : false;

			if ( $custom_value ) {
				$args['value'] = $custom_value;
			}

			$args['condition_settings'] = $condition;

			// Apply custom filter for condition arguments
			$args = apply_filters( 'wds_dyvi_condition_args', $args );

			$check = $condition_instance->check( $args );

			// Handle the condition check based on 'show' or 'hide' type and relation ('AND' or 'OR')
			if ( 'show' === $type ) {
				if ( $is_or_relation ) {
					if ( $check ) {
						return true;
					}
				} elseif ( ! $check ) {
					return false;
				}
			} elseif ( $is_or_relation ) {
				if ( ! $check ) {
					return false;
				}
			} elseif ( $check ) {
				return true;
			}
		}

		// If no conditions are present, return true by default.
		if ( ! $has_conditions ) {
			return true;
		}

		// Determine the result based on the relation and type
		$result = ( 'show' === $type ) ? ! $is_or_relation : $is_or_relation;

		return $result;
	}
}
