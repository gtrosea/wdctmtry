<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class User_Not_Logged
 */
class User_Not_Logged extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'user-not-logged';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'User not logged in', 'wds-notrans' );
	}

	/**
	 * Get the group of the condition.
	 *
	 * @return string
	 */
	public function get_group() {
		return 'user';
	}

	/**
	 * Check if the current value meets the condition criteria.
	 *
	 * @param array $args Arguments to check the condition.
	 * @return bool True if the condition is met, false otherwise.
	 */
	public function check( $args = array() ) {
		$type = ! empty( $args['type'] ) ? $args['type'] : 'show';

		if ( 'hide' === $type ) {
			return is_user_logged_in();
		} else {
			return ! is_user_logged_in();
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
		return false;
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new User_Not_Logged() );
	}
);
