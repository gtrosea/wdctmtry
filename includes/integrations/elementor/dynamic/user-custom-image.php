<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_User_Custom_Image Class.
 */
class Dynamic_User_Custom_Image extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-user-custom-image';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'User Custom Image', 'wds-notrans' );
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
		$data          = wds_get_metaboxes( 'user' );
		$metaboxes     = $data['metaboxes'];
		$include_field = array( 'media' );

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

		$this->add_control(
			'fallback',
			array(
				'label'     => __( 'Fallback', 'wds-notrans' ),
				'type'      => Controls_Manager::MEDIA,
				'separator' => 'before',
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
		$field    = $this->get_settings( 'image' );
		$fallback = $this->get_settings( 'fallback' );
		$context  = $this->get_settings( 'context' );

		if ( ! $field ) {
			return;
		}

		$image_id  = 0;
		$image_url = '';
		$user_id   = 0;

		if ( 'current_user' == $context ) {
			$user_id = get_current_user_id();
		} elseif ( 'current_post_author' == $context ) {
			$user_id = get_post_field( 'post_author', get_the_ID() );
		} elseif ( 'replica' == $context ) {
			$user_id = wds_data( 'reseller_id' );
		}

		$value = get_user_meta( $user_id, $field, true );
		if ( ! empty( $value ) ) {
			if ( wds_check_array( $value, true ) ) {
				$image_id  = $value['id'];
				$image_url = $value['url'];
			} elseif ( is_numeric( $value ) ) {
				$image_id  = $value;
				$image_url = wp_get_attachment_url( $value );
			} else {
				$image_id  = wds_attachment_url_to_postid( $value );
				$image_url = $value;
			}
		} else {
			$image_id  = $fallback['id'];
			$image_url = $fallback['url'];
		}

		$image_data = array(
			'id'  => $image_id,
			'url' => $image_url,
		);

		return $image_data;
	}
}
