<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_Post_Custom_Image Class.
 */
class Dynamic_Post_Custom_Image extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-post-custom-image';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'Post Custom Image', 'wds-notrans' );
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
		$data          = wds_get_metaboxes( 'post' );
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
	}

	/**
	 * Get value.
	 *
	 * @param array $options The options.
	 */
	public function get_value( array $options = array() ) {
		$field    = $this->get_settings( 'image' );
		$fallback = $this->get_settings( 'fallback' );

		if ( ! $field ) {
			return;
		}

		$image_id  = 0;
		$image_url = '';
		$post_id   = get_the_ID();

		$value = wds_post_meta( $post_id, $field );
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
