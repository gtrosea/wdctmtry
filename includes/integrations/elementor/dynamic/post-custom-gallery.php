<?php

namespace WDS\Integrations\Elementor;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_Post_Custom_Gallery Class.
 */
class Dynamic_Post_Custom_Gallery extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-post-custom-gallery';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'Post Custom Gallery', 'wds-notrans' );
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
		$data          = wds_get_metaboxes( 'post' );
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
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $options,
			)
		);
	}

	/**
	 * Get value.
	 *
	 * @param array $options The options.
	 */
	public function get_value( array $options = array() ) {
		$field = $this->get_settings( 'image' );

		if ( ! $field ) {
			return;
		}

		$images  = array();
		$post_id = get_the_ID();

		$value = wds_post_meta( $post_id, $field );
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
