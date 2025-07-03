<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_User Class.
 */
class Dynamic_User extends \Elementor\Core\DynamicTags\Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-user-text';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'User Field', 'wds-notrans' );
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
		return array(
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
		);
	}

	/**
	 * Registers controls for selecting sections, values, and filtering options.
	 */
	protected function register_controls() {
		$user = array(
			'full_name'           => __( 'Full Name', 'wds-notrans' ),
			'phone'               => __( 'Phone', 'wds-notrans' ),
			'status'              => __( 'Status', 'wds-notrans' ),
			'membership'          => __( 'Membership', 'wds-notrans' ),
			'user_group'          => __( 'User Group', 'wds-notrans' ),
			'active_period'       => __( 'Active Period', 'wds-notrans' ),
			'invitation_quota'    => __( 'Invitation Quota', 'wds-notrans' ),
			'client_quota'        => __( 'Client Quota', 'wds-notrans' ),
			'invitation_duration' => __( 'Invitation Duration', 'wds-notrans' ),
			'branding'            => __( 'Branding Reseller', 'wds-notrans' ),
		);

		if ( wds_is_replica() ) {
			$user['wedding_replica'] = __( 'WDS Replica', 'wds-notrans' );
		}

		$this->add_control(
			'user',
			array(
				'label'   => __( 'Field', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $user,
				'default' => 'full_name',
			)
		);

		$this->add_control(
			'branding',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'branding_name'        => __( 'Brand Name', 'wds-notrans' ),
					'branding_link'        => __( 'Link Logo', 'wds-notrans' ),
					'branding_description' => __( 'Description', 'wds-notrans' ),
					'instagram'            => __( 'Instagram', 'wds-notrans' ),
					'facebook'             => __( 'Facebook', 'wds-notrans' ),
					'tiktok'               => __( 'Tiktok', 'wds-notrans' ),
					'twitter'              => __( 'Twitter', 'wds-notrans' ),
					'youtube'              => __( 'Youtube', 'wds-notrans' ),
				),
				'default'   => 'branding_name',
				'condition' => array( 'user' => 'branding' ),
			)
		);

		$this->add_control(
			'wedding_replica',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$wedding_reseller,
				'condition' => array( 'user' => 'wedding_replica' ),
			)
		);
	}

	/**
	 * Renders the custom field value with optional filtering and formatting.
	 */
	public function render() {
		$user_field     = $this->get_settings( 'user' );
		$branding_field = $this->get_settings( 'branding' );
		$replica_field  = $this->get_settings( 'wedding_replica' );

		if ( ! $user_field ) {
			return;
		}

		$user_id     = get_current_user_id();
		$field_value = '';

		if ( 'full_name' == $user_field ) {
			$field_value = wds_user_name();
		} elseif ( 'phone' == $user_field ) {
			$field_value = wds_user_phone();
		} elseif ( 'status' == $user_field ) {
			$field_value = wds_user_status();
		} elseif ( 'membership' == $user_field ) {
			$field_value = wds_user_membership();
		} elseif ( 'user_group' == $user_field ) {
			$field_value = wds_user_group();
		} elseif ( 'active_period' == $user_field ) {
			$field_value = wds_user_active_period();
		} elseif ( 'invitation_quota' == $user_field ) {
			$field_value = wds_user_invitation_quota();
		} elseif ( 'client_quota' == $user_field ) {
			$field_value = wds_user_client_quota();
		} elseif ( 'invitation_duration' == $user_field ) {
			$field_value = wds_user_invitation_duration();
		} elseif ( 'branding' == $user_field ) {
			if ( 'branding_name' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_branding_name' );
				if ( empty( $field_value ) ) {
					$field_value = wds_option( 'reseller_name_fallback' );
				}
			} elseif ( 'branding_link' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_branding_link' );
				if ( empty( $field_value ) ) {
					$field_value = wds_option( 'reseller_link_fallback' );
				}
			} elseif ( 'branding_description' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_branding_description' );
				if ( empty( $field_value ) ) {
					$field_value = wds_option( 'reseller_desc_fallback' );
				}
			} elseif ( 'instagram' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_instagram' );
			} elseif ( 'facebook' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_facebook' );
			} elseif ( 'tiktok' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_tiktok' );
			} elseif ( 'twitter' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_twitter' );
			} elseif ( 'youtube' == $branding_field ) {
				$field_value = wds_user_meta( $user_id, '_youtube' );
			}
		} elseif ( 'wedding_replica' == $user_field ) {
			$allowed_fields = array_keys( Default_Meta::$wedding_reseller );
			if ( in_array( $replica_field, $allowed_fields ) ) {
				$field_value = do_shortcode( '[wds_replica type="' . $replica_field . '"]' );
				if ( 'whatsapp' == $replica_field ) {
					$field_value = 'https://wa.me/' . do_shortcode( '[wds_replica type="phone"]' );
				}
			}
		}

		echo wp_kses_post( $field_value );
	}
}
