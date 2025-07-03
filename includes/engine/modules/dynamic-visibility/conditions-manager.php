<?php

namespace WDS\Engine\DyVi;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Condition_Manager
 *
 * Manages conditions for dynamic visibility.
 *
 * @since 1.13.0
 */
class Condition_Manager {

	/**
	 * Holds registered conditions.
	 *
	 * @var array
	 */
	private $_conditions = array(); // phpcs:ignore

	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_conditions' ), 20 );
	}

	/**
	 * Registers conditions for dynamic visibility.
	 *
	 * Includes condition files and triggers a custom action for external conditions.
	 */
	public function register_conditions() {
		foreach ( glob( WDS_DYVI_PATH . 'conditions/*.php' ) as $condition ) {
			require_once $condition;
		}

		do_action( 'wds_dyvi_conditions_register', $this );
	}

	/**
	 * Registers a single condition instance.
	 *
	 * @param object $instance Condition instance to register.
	 */
	public function register_condition( $instance ) {
		$this->_conditions[ $instance->get_id() ] = $instance;
	}

	/**
	 * Retrieves the conditions as an array suitable for options.
	 *
	 * @return array Condition ID => Condition name.
	 */
	public function get_conditions_for_options() {
		$result = array();
		foreach ( $this->_conditions as $id => $instance ) {
			$result[ $id ] = $instance->get_name();
		}

		return $result;
	}

	/**
	 * Retrieves the conditions grouped by type for options.
	 *
	 * @return array Grouped conditions.
	 */
	public function get_grouped_conditions_for_options() {
		$result = apply_filters(
			'wds_dyvi_conditions_groups',
			array(
				'general' => array(
					'label'   => __( 'General', 'wds-notrans' ),
					'options' => array(),
				),
				'user'    => array(
					'label'   => __( 'User', 'wds-notrans' ),
					'options' => array(),
				),
				'posts'   => array(
					'label'   => __( 'Posts', 'wds-notrans' ),
					'options' => array(),
				),
			)
		);

		foreach ( $this->_conditions as $id => $instance ) {
			$group = $instance->get_group();
			if ( ! $group ) {
				$group = 'general';
			}

			if ( empty( $result[ $group ] ) ) {
				$result[ $group ] = array(
					'label'   => $group,
					'options' => array(),
				);
			}

			$result[ $group ]['options'][ $id ] = $instance->get_name();
		}

		return array_values( $result );
	}

	/**
	 * Retrieves conditions that are applicable for form fields.
	 *
	 * @return array List of condition IDs.
	 */
	public function get_conditions_for_fields() {
		$result = array();
		foreach ( $this->_conditions as $id => $instance ) {
			if ( $instance->is_for_fields() ) {
				$result[] = $id;
			}
		}

		return $result;
	}

	/**
	 * Retrieves conditions that require value detection.
	 *
	 * @return array List of condition IDs.
	 */
	public function get_conditions_with_value_detect() {
		$result = array();
		foreach ( $this->_conditions as $id => $instance ) {
			if ( $instance->need_value_detect() ) {
				$result[] = $id;
			}
		}

		return $result;
	}

	/**
	 * Adds condition-specific custom controls.
	 *
	 * @return array Custom controls.
	 */
	public function add_condition_specific_controls() {
		$result = array();
		foreach ( $this->_conditions as $id => $instance ) {
			$custom_controls = $instance->get_custom_controls();
			if ( empty( $custom_controls ) ) {
				continue;
			}

			foreach ( $custom_controls as $key => $control ) {
				if ( isset( $result[ $key ] ) ) {
					$result[ $key ]['condition']['wdsdv_condition'][] = $id;
					continue;
				}

				$control['condition'] = array(
					'wdsdv_condition' => array( $id ),
				);

				$result[ $key ] = $control;
			}
		}

		return $result;
	}

	/**
	 * Retrieves conditions that require type detection.
	 *
	 * @return array List of condition IDs.
	 */
	public function get_conditions_with_type_detect() {
		$result = array();
		foreach ( $this->_conditions as $id => $instance ) {
			if ( $instance->need_type_detect() ) {
				$result[] = $id;
			}
		}

		return $result;
	}

	/**
	 * Gets a specific condition by its ID.
	 *
	 * @param string $id Condition ID.
	 * @return object|false The condition instance or false if not found.
	 */
	public function get_condition( $id ) {
		return isset( $this->_conditions[ $id ] ) ? $this->_conditions[ $id ] : false;
	}
}
