<?php

namespace WDS\Engine\DyVi;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Module
 *
 * Handles the dynamic visibility module for WeddingSaas.
 *
 * @since 1.13.0
 */
class Module {

	/**
	 * @var Module|null
	 */
	private static $instance = null;

	/**
	 * @var Condition_Manager|null
	 */
	public $conditions = null;

	/**
	 * Initializes the singleton instance of the module.
	 *
	 * @return Module
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for the Module class.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Loads the conditions manager and sets up the dynamic visibility conditions.
	 */
	public function init() {
		require_once WDS_DYVI_PATH . 'conditions-manager.php';
		require_once WDS_DYVI_PATH . 'conditions-checker.php';
		require_once WDS_DYVI_PATH . 'elementor-integration.php';

		new Elementor_Integration();

		$this->conditions = new Condition_Manager();
	}

	/**
	 * Retrieves the condition controls for dynamic visibility settings.
	 *
	 * @return array Condition controls data.
	 */
	public function get_condition_controls() {
		$data = array();

		$data['wdsdv_condition'] = array(
			'type'        => 'select',
			'label'       => __( 'Condition', 'wds-notrans' ),
			'label_block' => true,
			'groups'      => $this->conditions->get_grouped_conditions_for_options(),
		);

		$field_categories = array();
		if ( class_exists( '\Elementor\Modules\DynamicTags\Module' ) ) {
			$field_categories = array(
				\Elementor\Modules\DynamicTags\Module::BASE_GROUP,
				\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
				\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
				\Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY,
				\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
				\Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
				\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
				\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
				\Elementor\Modules\DynamicTags\Module::COLOR_CATEGORY,
			);
		}

		$data['wdsdv_field'] = array(
			'label'       => __( 'Field', 'wds-notrans' ),
			'description' => __( 'Enter meta field name or select dynamic tag to compare value against.', 'wds-notrans' ),
			'type'        => 'text',
			'label_block' => true,
			'has_html'    => true,
			'dynamic'     => array(
				'active'     => true,
				'categories' => $field_categories,
			),
			'condition'   => array(
				'wdsdv_condition' => $this->conditions->get_conditions_for_fields(),
			),
		);

		$data['wdsdv_value'] = array(
			'label'       => __( 'Value', 'wds-notrans' ),
			'description' => __( 'Set value to compare. Separate values with commas to set values list.', 'wds-notrans' ),
			'type'        => 'textarea',
			'label_block' => true,
			'dynamic'     => array(
				'active' => true,
			),
			'condition'   => array(
				'wdsdv_condition' => $this->conditions->get_conditions_with_value_detect(),
			),
		);

		$data = array_merge( $data, $this->conditions->add_condition_specific_controls() );

		$data['wdsdv_data_type'] = array(
			'type'        => 'select',
			'label'       => __( 'Data type', 'wds-notrans' ),
			'label_block' => true,
			'default'     => 'chars',
			'options'     => $this->get_data_types(),
			'condition'   => array(
				'wdsdv_condition' => $this->conditions->get_conditions_with_type_detect(),
			),
		);

		return $data;
	}

	/**
	 * Retrieves the available data types for conditions.
	 *
	 * @return array List of data types.
	 */
	public function get_data_types() {
		$data_types = apply_filters(
			'wds_dyvi_data_types',
			array(
				'chars'   => __( 'Chars (alphabetical comparison)', 'wds-notrans' ),
				'numeric' => __( 'Numeric', 'wds-notrans' ),
				// 'date'    => __( 'Datetime', 'wds-notrans' ),
			)
		);

		return $data_types;
	}
}

Module::instance();
