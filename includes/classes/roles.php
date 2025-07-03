<?php
/**
 * WeddingSaas Roles.
 *
 * This class handles the role creation and assignment of capabilities for those roles.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Roles Class.
 */
class WDS_Roles {

	/**
	 * Add new roles.
	 */
	public function add_roles() {
		add_role(
			'wds-member',
			'WDS Member',
			array(
				'read'                 => true,
				'publish_posts'        => true,
				'upload_files'         => true,
				'edit_published_posts' => true,
			)
		);
	}

	/**
	 * Remove roles (called on uninstall).
	 */
	public function remove_roles() {
		remove_role( 'wds-member' );
	}
}
