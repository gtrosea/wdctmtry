<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_Term_Field Class.
 */
class Dynamic_Term_Field extends \Elementor\Core\DynamicTags\Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-term-field';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'Term Field', 'wds-notrans' );
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
		$data          = wds_get_metaboxes( 'taxonomy' );
		$sections      = $data['sections'];
		$metaboxes     = $data['metaboxes'];
		$exclude_field = array( 'media', 'gallery' );

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
				if ( ! in_array( $field['type'], $exclude_field ) ) {
					$options[ $field['id'] ] = $field['title'];
				}
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
				'options'   => array(
					'date'    => 'Format Date',
					'initial' => 'Inisial',
				),
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

		$value    = '';
		$post_id  = get_the_ID();
		$taxonomy = wds_get_taxonomy_by_post_id( $post_id );
		$theme_id = wds_get_taxonomy_theme_id( $post_id, $taxonomy );

		if ( $theme_id ) {
			$value = get_term_meta( $theme_id, $metabox, true );
		}

		$metabox_data = wds_get_metaboxes( 'taxonomy' );
		$metaboxes    = $metabox_data['metaboxes'];

		if ( isset( $metaboxes[ $section ] ) ) {
			foreach ( $metaboxes[ $section ] as $field ) {
				if ( 'text' == $field['type'] || 'textarea' == $field['type'] ) {
					$value = preg_replace( '/\*(.*?)\*/', '<b>$1</b>', $value ); // bold
					$value = preg_replace( '/\_(.*?)\_/', '<i>$1</i>', $value ); // italic
					$value = nl2br( $value ); // enter
					break;
				}
			}
		}

		if ( 'yes' == $filter && ! empty( $callback ) ) {
			if ( 'date' == $callback && ! empty( $date_format ) ) {
				$timestamp = is_numeric( $value ) ? $value : strtotime( $value );
				$value     = date_i18n( $date_format, $timestamp );
			} elseif ( 'initial' == $callback ) {
				$value = substr( $value, 0, 1 );
			}
		}

		echo wp_kses_post( $value );
	}
}
