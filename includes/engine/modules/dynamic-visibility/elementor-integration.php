<?php

namespace WDS\Engine\DyVi;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Elementor_Integration
 *
 * Handles dynamic visibility integration with Elementor.
 *
 * @since 1.13.0
 */
class Elementor_Integration extends Condition_Checker {

	/**
	 * @var array Hidden element IDs that are not visible.
	 */
	private $hidden_elements_ids = array();

	/**
	 * @var bool Flag to determine if inline CSS widgets need unregistration.
	 */
	private $need_unregistered_inline_css_widget = false;

	/**
	 * @var array Column IDs that need resizing.
	 */
	private $resize_columns_ids = array();

	/**
	 * Elementor_Integration constructor.
	 *
	 * Sets up Elementor actions for handling dynamic visibility.
	 */
	public function __construct() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		require_once WDS_DYVI_PATH . 'elementor-settings.php';

		new Settings();

		$el_types = array(
			'section',
			'column',
			'widget',
			'container',
		);

		foreach ( $el_types as $el ) {
			add_action( 'elementor/frontend/' . $el . '/before_render', array( $this, 'before_element_render' ) );
			add_action( 'elementor/frontend/' . $el . '/after_render', array( $this, 'after_element_render' ) );
		}

		add_action( 'elementor/element/after_add_attributes', array( $this, 'maybe_add_resize_columns_class' ) );
		add_action( 'elementor/frontend/column/after_render', array( $this, 'add_resize_columns_prop' ) );

		add_filter( 'elementor/element/is_dynamic_content', array( $this, 'maybe_set_element_as_dynamic' ), 10, 3 );

	}

	/**
	 * Called before rendering an Elementor element.
	 *
	 * Checks if the element should be visible based on dynamic conditions.
	 *
	 * @param \Elementor\Element_Base $element The Elementor element instance.
	 */
	public function before_element_render( $element ) {
		$settings = $element->get_settings();

		$is_enabled = ! empty( $settings['wdsdv_enabled'] ) ? $settings['wdsdv_enabled'] : false;
		$is_enabled = filter_var( $is_enabled, FILTER_VALIDATE_BOOLEAN );

		if ( ! $is_enabled ) {
			return;
		}

		$is_visible = $this->check_cond( $element->get_settings(), $element->get_settings_for_display() );

		if ( ! $is_visible ) {
			add_filter( 'elementor/element/get_child_type', '__return_false' ); // Prevent getting content of inner elements.
			add_filter( 'elementor/frontend/' . $element->get_type() . '/should_render', '__return_false' );

			if ( 'widget' === $element->get_type() ) {
				$is_inline_css_mode = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_css_loading' );

				if ( $is_inline_css_mode && ! in_array( $element->get_name(), $element::$registered_inline_css_widgets ) ) {
					$this->need_unregistered_inline_css_widget = true;
				}
			}

			$this->hidden_elements_ids[] = $element->get_id();
		}
	}

	/**
	 * Called after rendering an Elementor element.
	 *
	 * Removes filters and unregisters inline CSS if needed.
	 *
	 * @param \Elementor\Element_Base $element The Elementor element instance.
	 */
	public function after_element_render( $element ) {

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		if ( ! in_array( $element->get_id(), $this->hidden_elements_ids ) ) {
			return;
		}

		remove_filter( 'elementor/element/get_child_type', '__return_false' );
		remove_filter( 'elementor/frontend/' . $element->get_type() . '/should_render', '__return_false' );

		if ( 'widget' === $element->get_type() && $this->need_unregistered_inline_css_widget ) {
			if ( in_array( $element->get_name(), $element::$registered_inline_css_widgets ) ) {
				$registered_inline_css_widgets = $element::$registered_inline_css_widgets;

				$index = array_search( $element->get_name(), $registered_inline_css_widgets );

				unset( $registered_inline_css_widgets[ $index ] );

				$element::$registered_inline_css_widgets = $registered_inline_css_widgets;
			}

			$this->need_unregistered_inline_css_widget = false;
		}
	}

	/**
	 * Adds the column ID to the list of columns that need resizing.
	 *
	 * @param \Elementor\Element_Column $column The Elementor column instance.
	 */
	public function add_resize_columns_prop( $column ) {
		if ( empty( $this->hidden_elements_ids ) ) {
			return;
		}

		if ( ! in_array( $column->get_id(), $this->hidden_elements_ids ) ) {
			return;
		}

		$settings = $column->get_settings();

		if ( ! isset( $settings['wdsdv_resize_columns'] ) ) {
			return;
		}

		if ( ! filter_var( $settings['wdsdv_resize_columns'], FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		$this->resize_columns_ids[] = $column->get_id();
	}

	/**
	 * Adds a CSS class for sections with resizeable columns.
	 *
	 * @param \Elementor\Element_Section $section The Elementor section instance.
	 */
	public function maybe_add_resize_columns_class( $section ) {
		if ( 'section' !== $section->get_type() ) {
			return;
		}

		$has_resize_columns = false;

		foreach ( $section->get_children() as $column ) {
			if ( in_array( $column->get_id(), $this->resize_columns_ids ) ) {
				$has_resize_columns = true;
				break;
			}
		}

		if ( $has_resize_columns ) {
			$section->add_render_attribute(
				'_wrapper',
				array(
					'class' => 'wdsdv-resize-columns',
				)
			);
		}
	}

	/**
	 * Determines if an element should be treated as dynamic based on its settings.
	 * Checks the 'wdsdv_enabled' field in the element's settings. If enabled,
	 *
	 * @param mixed $result The original result value.
	 * @param array $data   The data array containing the element's settings.
	 * @param mixed $element The element being processed.
	 *
	 * @return bool|mixed True if 'wdsdv_enabled' is set to true, or the original $result otherwise.
	 */
	public function maybe_set_element_as_dynamic( $result, $data, $element ) {

		if ( empty( $data['settings'] ) ) {
			return $result;
		}

		$is_dv_enabled = ! empty( $data['settings']['wdsdv_enabled'] ) ? $data['settings']['wdsdv_enabled'] : false;
		$is_dv_enabled = filter_var( $is_dv_enabled, FILTER_VALIDATE_BOOLEAN );

		if ( $is_dv_enabled ) {
			return true;
		}

		return $result;
	}
}
