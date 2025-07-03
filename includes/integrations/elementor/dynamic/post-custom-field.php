<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_Post_Custom_Field Class.
 */
class Dynamic_Post_Custom_Field extends \Elementor\Core\DynamicTags\Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-post-custom-field';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'Post Custom Field', 'wds-notrans' );
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
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::DATETIME_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::COLOR_CATEGORY,
		);
	}

	/**
	 * Registers controls for selecting sections, values, and filtering options.
	 */
	protected function register_controls() {
		$data      = wds_get_metaboxes( 'post' );
		$sections  = $data['sections'];
		$metaboxes = $data['metaboxes'];

		$this->add_control(
			'section',
			array(
				'label'   => __( 'Section', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $sections,
			)
		);

		foreach ( $metaboxes as $section => $metabox_options ) {
			$options = array();
			foreach ( $metabox_options as $field ) {
				$options[ $field['id'] ] = $field['title'];
			}

			$this->add_control(
				'metabox_' . $section,
				array(
					'label'     => __( 'Value', 'wds-notrans' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $options,
					'condition' => array( 'section' => $section ),
				)
			);
		}

		$this->add_control(
			'filter',
			array(
				'label' => __( 'Filter Output', 'wds-notrans' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'callback',
			array(
				'label'     => __( 'Callback', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$callback,
				'condition' => array( 'filter' => 'yes' ),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'     => __( 'Format', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$event_v2_format,
				'condition' => array(
					'filter'   => 'yes',
					'callback' => 'date',
				),
			)
		);
	}

	/**
	 * Renders the custom field value with optional filtering and formatting.
	 */
	public function render() {
		$section     = $this->get_settings( 'section' );
		$metabox     = $this->get_settings( 'metabox_' . $section );
		$filter      = $this->get_settings( 'filter' );
		$callback    = $this->get_settings( 'callback' );
		$date_format = $this->get_settings( 'date_format' );

		if ( ! $section || ! $metabox ) {
			return;
		}

		$value        = wds_post_meta( get_the_ID(), $metabox );
		$metabox_data = wds_get_metaboxes( 'post' );
		$metaboxes    = $metabox_data['metaboxes'];

		if ( wds_check_array( $value, true ) ) {
			echo wp_kses_post( wds_render_checkbox_values( $value ) );
			return $value;
		} elseif ( is_array( $value ) ) {
			echo wp_kses_post( wds_render_checkbox_values( $value ) );
			return $value;
		}

		if ( isset( $metaboxes[ $section ] ) ) {
			foreach ( $metaboxes[ $section ] as $field ) {
				if ( 'text' == $field['type'] || 'textarea' == $field['type'] ) {
					if ( ! preg_match( '/\.(mp3|wav|jpg|png|pdf|docx)$/i', $value ) ) {
						$value = preg_replace( '/\*(.*?)\*/', '<b>$1</b>', $value ); // Bold
						$value = preg_replace( '/\_(.*?)\_/', '<i>$1</i>', $value ); // Italic
					}
					$value = nl2br( $value ); // Line breaks
					break;
				}
			}
		}

		if ( 'yes' == $filter && ! empty( $callback ) ) {
			$option = ( 'date' == $callback ) && ! empty( $date_format ) ? $date_format : '';
			$value  = Helper::callback( $value, $callback, $option );
		}

		// Output the final value, with sanitized HTML
		echo wp_kses_post( $value );
	}
}
