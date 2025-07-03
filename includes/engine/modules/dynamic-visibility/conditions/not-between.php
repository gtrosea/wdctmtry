<?php

namespace WDS\Engine\DyVi\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Not_Between
 */
class Not_Between extends Base {

	/**
	 * Get the unique identifier for the condition.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'not-between';
	}

	/**
	 * Get the name of the condition.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Not Between', 'wds-notrans' );
	}

	/**
	 * Check if the current value meets the condition criteria.
	 *
	 * @param array $args Arguments to check the condition.
	 * @return bool True if the condition is met, false otherwise.
	 */
	public function check( $args = array() ) {
		return ! parent::check( $args );
	}
}

add_action(
	'wds_dyvi_conditions_register',
	function ( $manager ) {
		$manager->register_condition( new Not_Between() );
	}
);
