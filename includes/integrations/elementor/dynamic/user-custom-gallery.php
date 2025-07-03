<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_User_Custom_Gallery Class.
 *
 * @since 2.2.3
 */
class Dynamic_User_Custom_Gallery extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-user-custom-gallery';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'User Custom Gallery', 'wds-notrans' );
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
		return array( \Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY );
	}

	/**
	 * Registers controls for selecting sections, values, and filtering options.
	 */
	protected function register_controls() {
		$data          = wds_get_metaboxes( 'user' );
		$metaboxes     = $data['metaboxes'];
		$include_field = array( 'gallery' );

		$options = array();
		foreach ( $metaboxes as $section => $metabox_options ) {
			foreach ( $metabox_options as $field ) {
				if ( in_array( $field['type'], $include_field ) ) {
					$options[ $field['id'] ] = $field['title'];
				}
			}
		}

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Value', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $options,
			)
		);

		$context = array(
			'current_user'        => __( 'Current User', 'wds-notrans' ),
			'current_post_author' => __( 'Current Post Author', 'wds-notrans' ),
		);

		if ( wds_is_replica() ) {
			$context['replica'] = __( 'Replica', 'wds-notrans' );
		}

		$this->add_control(
			'context',
			array(
				'label'   => __( 'Context', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'current_user',
				'options' => $context,
			)
		);
	}

	/**
	 * Get value.
	 *
	 * @param array $options The options.
	 */
	public function get_value( array $options = array() ) {
		$field   = $this->get_settings( 'image' );
		$context = $this->get_settings( 'context' );

		if ( ! $field ) {
			return;
		}

		$images = array();

		if ( 'current_user' == $context ) {
			$user_id = get_current_user_id();
		} elseif ( 'current_post_author' == $context ) {
			$user_id = get_post_field( 'post_author', get_the_ID() );
		} elseif ( 'replica' == $context ) {
			$user_id = wds_data( 'reseller_id' );
		}

		$value = wds_user_meta( $user_id, $field );
		if ( ! empty( $value ) ) {
			if ( wds_check_array( $value, true ) ) {
				foreach ( $value as $image ) {
					if ( wds_check_array( $image, true ) && isset( $image['id'] ) && isset( $image['url'] ) ) {
						$images[] = array(
							'id'  => $image['id'],
							'url' => $image['url'],
						);
					}
				}
			} else {
				$values = explode( ',', $value );
				foreach ( $values as $id ) {
					$image_id  = intval( $id );
					$image_url = wp_get_attachment_url( $image_id );
					if ( $image_url ) {
						$images[] = array(
							'id'  => $image_id,
							'url' => $image_url,
						);
					}
				}
			}
		}

		return $images;
	}
}
