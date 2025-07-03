<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Floating Menu Widget
 *
 * @since 2.2.0
 */
class Widget_Floating_Menu extends \Elementor\Widget_Base {

	/**
	 * Retrieve floating menu widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wds-floating-menu';
	}

	/**
	 * Retrieve floating menu widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Floating Menu', 'wds-notrans' );
	}

	/**
	 * Retrieve floating menu widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	/**
	 * Get categories.
	 *
	 * @return array Widget category.
	 */
	public function get_categories() {
		return array( 'weddingsaas' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'floating menu', 'menu' );
	}

	/**
	 * Retrieve the list of scripts the floating menu widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'wds-floating-menu',
		);
	}

	/**
	 * Retrieve the list of styles the offcanvas content widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return array(
			'widget-wds-floating-menu',
		);
	}

	/**
	 * Register floating menu widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_menu_controls();
		$this->register_content_tooltip_controls();
		$this->register_content_settings_controls();

		/* Style Tab */
		$this->register_style_menu_box_controls();
		$this->register_style_icon_controls();
		$this->register_style_tooltip_controls();
	}

	/************************************
	 * CONTENT TAB
	 ************************************/
	protected function register_content_menu_controls() {
		/**
		 * Content Tab: Menu
		 */
		$this->start_controls_section(
			'section_menu',
			array(
				'label' => esc_html__( 'Menu', 'wds-notrans' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'section_title',
			array(
				'label'   => esc_html__( 'Title', 'wds-notrans' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Section Title', 'wds-notrans' ),
				'ai'      => array(
					'active' => false,
				),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'section_id',
			array(
				'label'   => esc_html__( 'ID CSS', 'wds-notrans' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => array(
					'active' => false,
				),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'section_visibility',
			array(
				'label'        => esc_html__( 'Visibility', 'wds-notrans' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wds-notrans' ),
				'label_off'    => esc_html__( 'No', 'wds-notrans' ),
				'return_value' => 'yes',

			)
		);

		$repeater->add_control(
			'section_field',
			array(
				'label'       => esc_html__( 'Field', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Enter meta field name to check condition.', 'weddingsaas' ),
				'ai'          => array(
					'active' => false,
				),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'section_visibility' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'section_condition',
			array(
				'label'     => esc_html__( 'Condition', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'exists',
				'options'   => array(
					'exists'     => esc_html__( 'Exists', 'wds-notrans' ),
					'not-exists' => esc_html__( 'Doesn\'t exist', 'textdomain' ),
				),
				'condition' => array(
					'section_visibility' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'section_icon',
			array(
				'label'            => esc_html__( 'Icon', 'wds-notrans' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'dot_icon',
				'default'          => array(
					'value'   => 'fas fa-envelope',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'list_menu',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'section_title' => esc_html__( 'Cover', 'wds-notrans' ),
						'section_id'    => 'cover',
						'section_icon'  => 'fa fa-envelope',
					),
					array(
						'section_title' => esc_html__( 'Couple', 'wds-notrans' ),
						'section_id'    => 'couple',
						'section_icon'  => 'fa fa-envelope',
					),
					array(
						'section_title' => esc_html__( 'Event', 'wds-notrans' ),
						'section_id'    => 'event',
						'section_icon'  => 'fa fa-envelope',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ section_title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Tooltip
	 */
	protected function register_content_tooltip_controls() {
		$this->start_controls_section(
			'section_menu_tooltip_settings',
			array(
				'label' => esc_html__( 'Tooltip', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'menu_tooltip',
			array(
				'label'        => esc_html__( 'Tooltip', 'wds-notrans' ),
				'description'  => esc_html__( 'Show tooltip on hover', 'wds-notrans' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'wds-notrans' ),
				'label_off'    => esc_html__( 'No', 'wds-notrans' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'tooltip_arrow',
			array(
				'label'        => esc_html__( 'Tooltip Arrow', 'wds-notrans' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'wds-notrans' ),
				'label_off'    => esc_html__( 'Hide', 'wds-notrans' ),
				'return_value' => 'yes',
				'condition'    => array(
					'menu_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'distance',
			array(
				'label'       => esc_html__( 'Distance', 'wds-notrans' ),
				'description' => esc_html__( 'The distance between navigation dot and the tooltip.', 'wds-notrans' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em', 'rem', 'custom' ),
				'default'     => array(
					'size' => '',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}}.wds-menu-align-top .wds-fm-icon-tooltip' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wds-menu-align-bottom .wds-fm-icon-tooltip' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wds-menu-align-left .wds-fm-icon-tooltip' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wds-menu-align-right .wds-fm-icon-tooltip' => 'right: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'menu_tooltip' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Settings
	 */
	protected function register_content_settings_controls() {
		$this->start_controls_section(
			'section_menu_settings',
			array(
				'label' => esc_html__( 'Settings', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'top_offset',
			array(
				'label'   => esc_html__( 'Row Top Offset', 'wds-notrans' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array( 'size' => '0' ),
				'range'   => array(
					'px' => array(
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					),
				),
			)
		);

		$this->add_control(
			'scrolling_speed',
			array(
				'label'   => esc_html__( 'Scrolling Speed', 'wds-notrans' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '700',
			)
		);

		$this->end_controls_section();
	}

	/************************************
	 * STYLE TAB
	 ************************************/
	protected function register_style_menu_box_controls() {
		/**
		 * Style Tab: Menu Box
		 */
		$this->start_controls_section(
			'section_menu_box_style',
			array(
				'label' => esc_html__( 'Menu Box', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'wds-notrans' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'wds-notrans' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'wds-notrans' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'left'   => array(
						'title' => esc_html__( 'Left', 'wds-notrans' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'wds-notrans' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'bottom',
				'prefix_class' => 'wds-menu-align-',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'menu_container_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wds-floating-menu',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'menu_container_border',
				'label'       => esc_html__( 'Border', 'wds-notrans' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .wds-floating-menu',
			)
		);

		$this->add_control(
			'menu_container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-floating-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'menu_container_margin',
			array(
				'label'      => esc_html__( 'Margin', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-floating-menu-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'menu_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-floating-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'menu_container_box_shadow',
				'selector'  => '{{WRAPPER}} .wds-floating-menu',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Menu Icon
	 */
	protected function register_style_icon_controls() {

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Menu Icon', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'wds-notrans' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array( 'size' => '23' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 60,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wds-fm-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'wds-notrans' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array( 'size' => '10' ),
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 30,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.wds-menu-align-right .wds-floating-menu-item, {{WRAPPER}}.wds-menu-align-left .wds-floating-menu-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wds-menu-align-top .wds-floating-menu-item, {{WRAPPER}}.wds-menu-align-bottom .wds-floating-menu-item' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-fm-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'icon_box_shadow',
				'selector'  => '{{WRAPPER}} .wds-fm-icon-wrap',
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'icon_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-fm-icon'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .wds-fm-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-fm-icon-wrap' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'wds-notrans' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .wds-fm-icon-wrap',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-fm-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-floating-menu-item .wds-fm-icon-wrap:hover .wds-fm-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wds-floating-menu-item .wds-fm-icon-wrap:hover .wds-fm-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-floating-menu-item .wds-fm-icon-wrap:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-floating-menu-item .wds-fm-icon-wrap:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active',
			array(
				'label' => esc_html__( 'Active', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'icon_color_active',
			array(
				'label'     => esc_html__( 'Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-floating-menu-item.active .wds-fm-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wds-floating-menu-item.active .wds-fm-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-floating-menu-item.active .wds-fm-icon-wrap' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-floating-menu-item.active .wds-fm-icon-wrap' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Tooltip
	 */
	protected function register_style_tooltip_controls() {

		$this->start_controls_section(
			'section_tooltips_style',
			array(
				'label'     => esc_html__( 'Tooltip', 'wds-notrans' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'menu_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-fm-icon-tooltip-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wds-fm-icon-tooltip' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'menu_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'     => esc_html__( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wds-fm-icon-tooltip-content' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'menu_tooltip' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tooltip_typography',
				'label'     => esc_html__( 'Typography', 'wds-notrans' ),
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .wds-fm-icon-tooltip',
				'condition' => array(
					'menu_tooltip' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-fm-icon-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Widget
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);

		$this->add_render_attribute( 'floating-menu-container', 'class', 'wds-floating-menu-container' );

		$this->add_render_attribute(
			'floating-menu',
			array(
				'class'             => 'wds-floating-menu',
				'id'                => 'wds-floating-menu-' . $this->get_id(),
				'data-section-id'   => 'wds-floating-menu-' . $this->get_id(),
				'data-top-offset'   => $settings['top_offset']['size'],
				'data-scroll-speed' => $settings['scrolling_speed'],
			)
		);

		$migration_allowed = Icons_Manager::is_migration_allowed();
		?>
		<div <?php $this->print_render_attribute_string( 'floating-menu-container' ); ?>>
			<ul <?php $this->print_render_attribute_string( 'floating-menu' ); ?>>
				<?php
				$i = 1;
				foreach ( $settings['list_menu'] as $index => $item ) {

					$show = true;

					// add old default
					if ( ! isset( $item['dot_icon'] ) && ! $migration_allowed ) {
						$item['dot_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
					}

					$migrated = isset( $item['__fa4_migrated']['section_icon'] );
					$is_new   = ! isset( $item['dot_icon'] ) && $migration_allowed;

					$this->add_render_attribute( 'tooltip', 'class', 'wds-fm-icon-tooltip' );

					if ( 'yes' == $settings['tooltip_arrow'] ) {
						$this->add_render_attribute( 'tooltip', 'class', 'wds-fm-tooltip-arrow' );
					}

					$section_title = $item['section_title'];
					$section_id    = $item['section_id'];
					$visibility    = $item['section_visibility'];
					$field         = $item['section_field'];
					$condition     = $item['section_condition'];

					if ( $visibility ) {
						$check = isset( $item['__dynamic__']['section_field'] ) ? $field : wds_post_meta( get_the_ID(), $field );
						if ( 'exists' == $condition ) {
							$show = ! empty( $check ) ? true : false;
						} else {
							$show = empty( $check ) ? true : false;
						}
					}

					if ( 'yes' == $settings['menu_tooltip'] ) {
						$icon_tooltip = sprintf( '<span %1$s><span class="wds-fm-icon-tooltip-content">%2$s</span></span>', $this->get_render_attribute_string( 'tooltip' ), $section_title );
					} else {
						$icon_tooltip = '';
					}

					if ( $show ) :
						?>
						<li class="wds-floating-menu-item">
							<?php echo wp_kses_post( $icon_tooltip ); ?>
							<a href="#" data-row-id="<?php echo esc_attr( $section_id ); ?>">
								<span class="wds-fm-icon-wrap">
									<span class="wds-fm-icon wds-fm-icon">
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $item['section_icon'], array( 'aria-hidden' => 'true' ) );
										} else {
											?>
												<i class="<?php echo esc_attr( $item['dot_icon'] ); ?>" aria-hidden="true"></i>
											<?php } ?>
									</span>
								</span>
							</a>
						</li>
						<?php
					endif;
					++$i;
				}
				?>
			</ul>
		</div>
		<?php
	}
}
