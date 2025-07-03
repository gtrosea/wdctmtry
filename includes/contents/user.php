<?php
/**
 * WeddingSaas User.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Contents
 */

namespace WDS;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * User Class.
 */
class User {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'csf_user_meta_value_unserialize', array( $this, 'modify_before_display' ), 10, 3 );
		add_filter( 'manage_users_columns', array( $this, 'modify_user_table' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'modify_user_table_row' ), 10, 3 );
	}

	/**
	 * Modify user meta value before display.
	 *
	 * @param mixed  $value   The current value of the user meta field.
	 * @param int    $user_id The ID of the user.
	 * @param string $key     The meta key being retrieved.
	 * @return mixed The modified value for display.
	 */
	public function modify_before_display( $value, $user_id, $key ) {
		if ( '_wds_user_active_period' == $key ) {
			if ( ! empty( $value ) ) {
				// $value = date_i18n( 'd M Y', $value );
				$value = gmdate( 'd M Y', $value );
			}
		}

		return $value;
	}

	/**
	 * Modifies the columns in the WordPress user table.
	 *
	 * @param array $column The existing columns in the user table.
	 * @return array Modified columns with custom columns added.
	 */
	public function modify_user_table( $column ) {
		$column['status']           = __( 'Status', 'wds-notrans' );
		$column['user_group']       = __( 'Group', 'wds-notrans' );
		$column['user_expired']     = __( 'Active Period', 'wds-notrans' );
		$column['invitation_quota'] = __( 'Invitation Quota', 'wds-notrans' );
		$column['client_quota']     = __( 'Client Quota', 'wds-notrans' );
		$column['storage']          = __( 'Storage', 'wds-notrans' );

		return $column;
	}

	/**
	 * Populates the custom columns for each user in the WordPress user table.
	 *
	 * @param string $val The existing column value (empty for custom columns).
	 * @param string $column_name The name of the column to display.
	 * @param int    $user_id The ID of the user being displayed.
	 * @return string The content to display in the column for the given user.
	 */
	public function modify_user_table_row( $val, $column_name, $user_id ) {
		switch ( $column_name ) {
			case 'status':
				return ! empty( wds_user_status( $user_id ) ) ? ucwords( wds_user_status( $user_id ) ) : '-';
			case 'user_group':
				return ! empty( wds_user_group( $user_id ) ) ? ucwords( wds_user_group( $user_id ) ) : '-';
			case 'user_expired':
				return wds_user_active_period( $user_id );
			case 'invitation_quota':
				return wds_user_invitation_quota( $user_id );
			case 'client_quota':
				return wds_user_client_quota( $user_id );
			case 'storage':
				return wds_user_storage( $user_id ) . ' MB';
			default:
		}

		return $val;
	}
}

new User();
