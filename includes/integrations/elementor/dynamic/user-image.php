<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_User_Image Class.
 */
class Dynamic_User_Image extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-user-image';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'User Image Field', 'wds-notrans' );
	}

	/**
	 * Defines the group this tag belongs to.
	 */
	public function get_group() {
		return array( 'weddingsaas' );
	}

	/**
	 * Retrieves the categories for the dynamic tag.
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	/**
	 * Registers controls for selecting sections, values, and filtering options.
	 */
	protected function register_controls() {
		$variables = array( 'branding_logo' => __( 'Branding Logo', 'wds-notrans' ) );
		if ( wds_is_replica() ) {
			$variables['wdr_branding_logo'] = __( 'Branding Logo - Wedding Replica', 'wds-notrans' );
		}

		$this->add_control(
			'user',
			array(
				'label'   => __( 'Field', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $variables,
			)
		);
	}

	/**
	 * Get value.
	 *
	 * @param array $options The options.
	 */
	public function get_value( array $options = array() ) {
		$selected_field = $this->get_settings( 'user' );

		if ( ! $selected_field ) {
			return;
		}

		$image_id  = 0;
		$image_url = '';
		$user_id   = get_current_user_id();

		if ( 'branding_logo' == $selected_field ) {
			$image_fallback = wds_option( 'reseller_logo_fallback' );
			$hide_branding  = wds_option( 'reseller_hide' );
			$hide_branding  = ! empty( $hide_branding ) ? $hide_branding : array();

			$value = wds_user_meta( $user_id, '_branding_logo' );
			if ( ! empty( $value ) ) {
				if ( ! in_array( 'logo', $hide_branding ) ) {
					$image_id  = wds_attachment_url_to_postid( $value );
					$image_url = $value;
				} else {
					$image_id  = wds_attachment_url_to_postid( $image_fallback );
					$image_url = $image_fallback;
				}
			} else {
				$image_id  = wds_attachment_url_to_postid( $image_fallback );
				$image_url = $image_fallback;
			}
		} elseif ( 'wdr_branding_logo' == $selected_field ) {
			$image_url = do_shortcode( '[wds_replica type="logo"]' );
			$image_id  = wds_attachment_url_to_postid( $image_url );
		}

		$image_data = array(
			'id'  => $image_id,
			'url' => $image_url,
		);

		return $image_data;
	}
}
