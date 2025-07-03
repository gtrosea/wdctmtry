<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_User_Custom_Field Class.
 */
class Dynamic_User_Custom_Field extends \Elementor\Core\DynamicTags\Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-user-custom-field';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'User Custom Field', 'wds-notrans' );
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
		$data          = wds_get_metaboxes( 'user' );
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
		$context     = $this->get_settings( 'context' );
		$filter      = $this->get_settings( 'filter' );
		$callback    = $this->get_settings( 'callback' );
		$date_format = $this->get_settings( 'date_format' );

		if ( ! $section || ! $metabox ) {
			return;
		}

		$user_id = 0;

		if ( 'current_user' == $context ) {
			$user_id = get_current_user_id();
		} elseif ( 'current_post_author' == $context ) {
			$user_id = get_post_field( 'post_author', get_the_ID() );
		} elseif ( 'replica' == $context ) {
			$user_id = wds_data( 'reseller_id' );
		}

		$value        = get_user_meta( $user_id, $metabox, true );
		$metabox_data = wds_get_metaboxes( 'user' );
		$metaboxes    = $metabox_data['metaboxes'];

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

		echo wp_kses_post( $value );
	}
}
