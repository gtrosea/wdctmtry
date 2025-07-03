<?php

namespace WDS\Engine\DyVi;

use Elementor\Controls_Manager;
use Elementor\Repeater;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Settings
 *
 * Handles the settings for dynamic visibility in Elementor widgets.
 *
 * @since 1.13.0
 */
class Settings {

	/**
	 * Settings constructor.
	 *
	 * Initializes hooks to add visibility settings to Elementor elements.
	 */
	public function __construct() {
		$callback = array( $this, 'add_visibility_settings' );

		add_action( 'elementor/element/column/section_advanced/after_section_end', $callback, 10, 2 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', $callback, 10, 2 );
		add_action( 'elementor/element/common/_section_style/after_section_end', $callback, 10, 2 );
		add_action( 'elementor/element/container/section_layout/after_section_end', $callback, 10, 2 );

		add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
	}

	/**
	 * Adds inline styles to be applied in Elementor preview mode.
	 */
	public function preview_styles() {
		wp_add_inline_style( 'editor-preview', '.wdsdv-enabled--yes:not(.elementor-element-editable){opacity: .6;}' );
	}

	/**
	 * Enqueues necessary scripts for the Elementor editor.
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'wds-dyvi-editor',
			wds_assets( 'js/wds-dyvy-elementor.js' ),
			array( 'jquery', 'elementor-editor' ),
			WDS_VERSION,
			true
		);
	}

	/**
	 * Adds dynamic visibility controls to Elementor elements.
	 *
	 * @param \Elementor\Element_Base $element Elementor element object.
	 * @param string                  $section_id Section ID.
	 * @return void
	 */
	public function add_visibility_settings( $element, $section_id ) {
		$type = $element->get_type();

		$label_with_icon = sprintf(
			'<span style="display: flex; align-items: center;"><img src="%s" alt="Icon" style="margin-right: 5px; vertical-align: middle;"> %s</span>',
			WDS_ICON,
			__( 'Dynamic Visibility', 'wds-notrans' )
		);

		$element->start_controls_section(
			'wdsdv_section',
			array(
				'tab'   => Controls_Manager::TAB_ADVANCED,
				'label' => $label_with_icon,
			)
		);

		$element->add_control(
			'wdsdv_enabled',
			array(
				'type'           => Controls_Manager::SWITCHER,
				'label'          => __( 'Enable', 'wds-notrans' ),
				'render_type'    => 'template',
				'prefix_class'   => 'wdsdv-enabled--',
				'style_transfer' => false,
			)
		);

		$element->add_control(
			'wdsdv_type',
			array(
				'type'           => Controls_Manager::SELECT,
				'label'          => __( 'Visibility condition type', 'wds-notrans' ),
				'label_block'    => true,
				'default'        => 'show',
				'options'        => array(
					'show' => __( 'Show element if condition met', 'wds-notrans' ),
					'hide' => __( 'Hide element if condition met', 'wds-notrans' ),
				),
				'condition'      => array(
					'wdsdv_enabled' => 'yes',
				),
				'style_transfer' => false,
			)
		);

		$repeater = new Repeater();

		// Add condition controls from dynamic tags module.
		foreach ( Module::instance()->get_condition_controls() as $name => $control_data ) {
			$control_data['render_type'] = 'none';
			$repeater->add_control( $name, $control_data );
		}

		$element->add_control(
			'wdsdv_conditions',
			array(
				'label'          => __( 'Conditions', 'wds-notrans' ),
				'type'           => 'repeater',
				'fields'         => $repeater->get_controls(),
				'default'        => array(
					array( 'wdsdv_condition' => '' ),
				),
				'title_field'    => '<# var wdsdv_labels=' . wp_json_encode( Module::instance()->conditions->get_conditions_for_options() ) . '; #> {{{ wdsdv_labels[wdsdv_condition] }}}',
				'condition'      => array(
					'wdsdv_enabled' => 'yes',
				),
				'style_transfer' => false,
				'render_type'    => 'none',
			)
		);

		$element->add_control(
			'wdsdv_relation',
			array(
				'label'          => __( 'Relation', 'wds-notrans' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 'AND',
				'options'        => array(
					'AND' => __( 'AND', 'wds-notrans' ),
					'OR'  => __( 'OR', 'wds-notrans' ),
				),
				'condition'      => array(
					'wdsdv_enabled' => 'yes',
				),
				'style_transfer' => false,
			)
		);

		if ( 'column' === $type ) {
			$element->add_control(
				'wdsdv_resize_columns',
				array(
					'label'          => __( 'Resize other columns', 'wds-notrans' ),
					'type'           => Controls_Manager::SWITCHER,
					'condition'      => array(
						'wdsdv_enabled' => 'yes',
					),
					'style_transfer' => false,
				)
			);
		}

		$element->end_controls_section();
	}
}
