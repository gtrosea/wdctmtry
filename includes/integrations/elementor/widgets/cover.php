<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Default_MetaClass.
 */
class Widget_Cover extends \Elementor\Widget_Base {

	/**
	 * Get name.
	 */
	public function get_name() {
		return 'wds_cover';
	}

	/**
	 * Get title.
	 */
	public function get_title() {
		return __( 'Cover & Audio', 'wds-notrans' );
	}

	/**
	 * Get icon.
	 */
	public function get_icon() {
		return 'eicon-site-identity';
	}

	/**
	 * Get categories.
	 */
	public function get_categories() {
		return array( 'weddingsaas' );
	}

	/**
	 * Get help.
	 */
	public function get_custom_help_url() {
		return 'https://weddingsaas.id/support/';
	}

	/**
	 * Register control.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_cover',
			array( 'label' => __( 'Cover', 'wds-notrans' ) )
		);

		$this->add_control(
			'important_note_cover',
			array(
				'label' => __( 'Important Note!', 'wds-notrans' ),
				'type'  => Controls_Manager::RAW_HTML,
				'raw'   => __( 'Use only one of these widgets for each page.', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'image_cover',
			array(
				'label'      => __( 'Cover Image', 'wds-notrans' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'image',
				'dynamic'    => array( 'active' => true ),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'text_cover',
			array(
				'label'       => __( 'Cover Text', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'The Invitation Of', 'wds-notrans' ),
				'placeholder' => __( 'The Invitation Of', 'wds-notrans' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'name_cover',
			array(
				'label'       => __( 'Cover Name', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Jhon & Jane',
				'placeholder' => 'Jhon & Jane',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'date_cover',
			array(
				'label'       => __( 'Cover Date', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Sunday, May 22 2030', 'wds-notrans' ),
				'placeholder' => __( 'Sunday, May 22 2030', 'wds-notrans' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'text_to_cover',
			array(
				'label'       => __( 'Text Dear/To', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'To Mr/Ms/Sister/i', 'wds-notrans' ),
				'placeholder' => __( 'To Mr/Ms/Sister/i', 'wds-notrans' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'text_invitation_cover',
			array(
				'label'       => __( 'Text Invitation', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Without reducing respect, we invite you to attend our event.', 'wds-notrans' ),
				'placeholder' => __( 'Without reducing respect, we invite you to attend our event.', 'wds-notrans' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'text_note_cover',
			array(
				'label'       => __( 'Text Information', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Sorry if there is a mistake in writing the name/title', 'wds-notrans' ),
				'placeholder' => __( 'Sorry if there is a mistake in writing the name/title', 'wds-notrans' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'text_btn_cover',
			array(
				'label'       => __( 'Button Text', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Open Invitation', 'wds-notrans' ),
				'placeholder' => __( 'Open Invitation', 'wds-notrans' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'icon_btn_cover',
			array(
				'label'            => __( 'Button Icon', 'wds-notrans' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fa fa-envelope-open',
					'library' => 'fa-solid',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_audio',
			array( 'label' => __( 'Audio', 'wds-notrans' ) )
		);

		$this->add_control(
			'important_note_audio',
			array(
				'label' => __( 'Important Note!', 'wds-notrans' ),
				'type'  => Controls_Manager::RAW_HTML,
				'raw'   => __( 'Just use one, the Audio Link or the YouTube Link.', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'audio_link_header',
			array(
				'label'     => __( 'Audio Link', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'audio_link',
			array(
				'label'         => __( 'Link', 'wds-notrans' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => 'https://example.com/music.mp3',
				'show_external' => false,
				'default'       => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				),
				'dynamic'       => array( 'active' => true ),
			)
		);

		$this->add_control(
			'youtube_link_header',
			array(
				'label'     => __( 'Youtube Link', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'youtube_link',
			array(
				'label'       => __( 'Youtube Video', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'https://youtu.be/WwgPI_V_Fkk',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'youtube_start',
			array(
				'label'              => __( 'Start Time', 'wds-notrans' ),
				'type'               => Controls_Manager::NUMBER,
				'description'        => __( 'Specify a start time (in seconds)', 'wds-notrans' ),
				'frontend_available' => true,
				'dynamic'            => array( 'active' => true ),
			)
		);

		$this->add_control(
			'youtube_end',
			array(
				'label'              => __( 'End Time', 'wds-notrans' ),
				'type'               => Controls_Manager::NUMBER,
				'description'        => __( 'Specify an end time (in seconds)', 'wds-notrans' ),
				'frontend_available' => true,
				'dynamic'            => array( 'active' => true ),
			)
		);

		$this->add_control(
			'audio_options',
			array(
				'label'     => __( 'Options', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'audio_position',
			array(
				'label'   => __( 'Position', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bl',
				'options' => array(
					'tl' => __( 'Top Left', 'wds-notrans' ),
					'tr' => __( 'Top Right', 'wds-notrans' ),
					'bl' => __( 'Bottom Left', 'wds-notrans' ),
					'br' => __( 'Bottom Right', 'wds-notrans' ),
				),
			)
		);

		$this->add_control(
			'audio_pause_icon',
			array(
				'label'            => __( 'Icon Play', 'wds-notrans' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fa fa-play-circle',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'audio_play_icon',
			array(
				'label'            => __( 'Icon Stop', 'wds-notrans' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fa fa-stop-circle',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'audio_view',
			array(
				'label'        => __( 'View', 'elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array(
					'default' => __( 'Default', 'elementor' ),
					'stacked' => __( 'Stacked', 'elementor' ),
					'framed'  => __( 'Framed', 'elementor' ),
				),
				'prefix_class' => 'elementor-view-',
			)
		);

		$this->add_control(
			'audio_shape',
			array(
				'label'        => __( 'Shape', 'elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'circle',
				'options'      => array(
					'circle' => __( 'Circle', 'elementor' ),
					'square' => __( 'Square', 'elementor' ),
				),
				'condition'    => array( 'audio_view' => 'default' ),
				'prefix_class' => 'elementor-shape-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_bg',
			array(
				'label' => __( 'Background', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'sb_content',
				'label'    => __( 'Background', 'wds-notrans' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-cover-overlay',
			)
		);

		$this->add_control(
			'sb_opacity',
			array(
				'label'     => __( 'Opacity (%)', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 0.5 ),
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-cover-overlay' => 'opacity: {{SIZE}};' ),
			)
		);

		$this->add_control(
			'sb_box_heading',
			array(
				'label'     => __( 'Background Box', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'sb_box_content',
				'label'    => __( 'Background Box', 'wds-notrans' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .centered-box',
			)
		);

		$this->add_control(
			'sb_box_max_width',
			array(
				'label'      => __( 'Max Width', 'wds-notrans' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 450,
				),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .centered-box' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_img',
			array(
				'label' => __( 'Cover Image', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'si_width',
			array(
				'label'          => __( 'Width', 'wds-notrans' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'unit' => '%' ),
				'tablet_default' => array( 'unit' => '%' ),
				'mobile_default' => array( 'unit' => '%' ),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array( '{{WRAPPER}} .wds-cover-content .wds-image-cover img' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'si_maxwidth',
			array(
				'label'          => __( 'Max Width', 'wds-notrans' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'unit' => '%' ),
				'tablet_default' => array( 'unit' => '%' ),
				'mobile_default' => array( 'unit' => '%' ),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array( '{{WRAPPER}} .wds-cover-content .wds-image-cover img' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'si_margin_bottom',
			array(
				'label'     => __( 'Space Bottom', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 10 ),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-image-cover' => 'margin-bottom:{{SIZE}}px;' ),
			)
		);

		$this->add_control(
			'si_panel_style',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->start_controls_tabs( 'si_effects' );

		$this->start_controls_tab(
			'si_effects_normal',
			array( 'label' => __( 'Normal', 'elementor' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'si_border',
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-image-cover img',
			)
		);

		$this->add_responsive_control(
			'si_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-image-cover img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'si_box_shadow',
				'exclude'  => array( 'box_shadow_position' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-image-cover img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'si_effects_hover',
			array( 'label' => __( 'Hover', 'elementor' ) )
		);

		$this->add_control(
			'si_hover_animation',
			array(
				'label' => __( 'Hover Animation', 'elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_cover',
			array(
				'label' => __( 'Cover Text', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stc_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-text-cover',
			)
		);

		$this->add_control(
			'stc_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-cover' => 'color: {{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'stc_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-cover' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'stc_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-text-cover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_name_cover',
			array(
				'label' => __( 'Cover Name', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'snc_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-name-cover',
			)
		);

		$this->add_control(
			'snc_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-name-cover' => 'color: {{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'snc_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-name-cover' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'snc_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-name-cover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_date_cover',
			array(
				'label' => __( 'Cover Date', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sdc_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-date-cover',
			)
		);

		$this->add_control(
			'sdc_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-date-cover' => 'color: {{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'sdc_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-date-cover' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'sdc_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-date-cover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_to_cover',
			array(
				'label' => __( 'Text Dear/To', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sttc_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-text-to-cover',
			)
		);

		$this->add_control(
			'sttc_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-to-cover' => 'color: {{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'sttc_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-to-cover' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'sttc_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-text-to-cover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'sttc_name_heading',
			array(
				'label'     => __( 'Text Name', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sttc_name_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-get-name',
			)
		);

		$this->add_control(
			'sttc_name_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-get-name' => 'color: {{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'sttc_name_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-get-name' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'sttc_name_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-get-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_invitation_cover',
			array(
				'label' => __( 'Text Invitation', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stic_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-text-invitation-cover',
			)
		);

		$this->add_control(
			'stic_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-invitation-cover' => 'color: {{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'stic_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-invitation-cover' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'stic_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-text-invitation-cover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_note_cover',
			array(
				'label' => __( 'Text Information', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stnc_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .wds-cover-content .wds-text-note-cover',
			)
		);

		$this->add_control(
			'stnc_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-note-cover' => 'color: {{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'stnc_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-text-note-cover' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'stnc_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-cover-content .wds-text-note-cover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_btn_cover',
			array(
				'label' => __( 'Button', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'stbc_tabs' );

		$this->start_controls_tab(
			'stbc_tab_normal',
			array( 'label' => __( 'Normal', 'elementor' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stbc_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .elementor-button',
			)
		);

		$this->add_control(
			'stbc_text_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stbc_bg_color',
			array(
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array( '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'stbc_align',
			array(
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .wds-cover-content .wds-button-cover' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'stbc_border',
				'label'       => __( 'Border', 'elementor' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .elementor-button',
			)
		);

		$this->add_control(
			'stbc_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'stbc_padding',
			array(
				'label'      => __( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'stbc_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .elementor-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'stbc_icon_margin',
			array(
				'label'     => __( 'Icon Space', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 5 ),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array( '{{WRAPPER}} .elementor-button .wds-icon-btn-cover' => 'margin-right: {{SIZE}}px;' ),
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'stbc_tab_hover',
			array( 'label' => __( 'Hover', 'elementor' ) )
		);

		$this->add_control(
			'stbc_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stbc_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stbc_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_audio_icon',
			array(
				'label' => __( 'Icon', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'audio_icon_colors' );

		$this->start_controls_tab(
			'sai_colors_normal',
			array( 'label' => __( 'Normal', 'elementor' ) )
		);

		$this->add_control(
			'sai_primary_color',
			array(
				'label'     => __( 'Primary Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sai_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'audio_view!' => 'default' ),
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sai_border_width',
			array(
				'label'     => __( 'Border Width', 'elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => array( 'audio_view' => 'framed' ),
				'selectors' => array( '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'sai_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array( 'audio_view!' => 'default' ),
				'selectors'  => array( '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'sai_size',
			array(
				'label'     => __( 'Size', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array( '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'sai_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-audio-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'sai_padding',
			array(
				'label'     => __( 'Padding', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'condition' => array( 'audio_view!' => 'default' ),
				'selectors' => array( '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'sai_rotate',
			array(
				'label'     => __( 'Rotate', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
					'unit' => 'deg',
				),
				'selectors' => array( '{{WRAPPER}} .elementor-icon i, {{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'sai_colors_hover',
			array( 'label' => __( 'Hover', 'elementor' ) )
		);

		$this->add_control(
			'sai_hover_primary_color',
			array(
				'label'     => __( 'Primary Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sai_hover_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'audio_view!' => 'default' ),
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sai_hover_animation',
			array(
				'label' => __( 'Hover Animation', 'elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image_cover' );

		if ( empty( $settings['icon_btn_cover'] ) && ! Icons_Manager::is_migration_allowed() ) {
			$settings['icon_btn_cover'] = 'fa fa-envelope-open';
		}

		if ( ! empty( $settings['icon_btn_cover'] ) ) {
			$this->add_render_attribute( 'icon_btn_cover', 'class', $settings['icon_btn_cover'] );
			$this->add_render_attribute( 'icon_btn_cover', 'aria-hidden', 'true' );
		}

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-icon-wrapper' );

		$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon' );

		if ( ! empty( $settings['sai_hover_animation'] ) ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-animation-' . $settings['sai_hover_animation'] );
		}

		$icon_tag = 'div';

		if ( empty( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			$settings['icon'] = 'fa fa-music';
		}

		if ( ! empty( $settings['icon'] ) ) {
			$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			$this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
		}

		$audio_link   = $settings['audio_link']['url'];
		$youtube_link = $settings['youtube_link'];

		$youtube     = $youtube_link ? true : false;
		$audio       = $youtube_link ? $youtube_link : $audio_link;
		$audio_start = wds_post_meta( get_the_ID(), '_audio_start' ) ? wds_post_meta( get_the_ID(), '_audio_start' ) : 0;
		$audio_end   = wds_post_meta( get_the_ID(), '_audio_end' ) ? wds_post_meta( get_the_ID(), '_audio_end' ) : null;

		$audio_position = $settings['audio_position'];

		if ( 'tl' == $audio_position ) {
			$css = 'top:0;left:0;';
		} elseif ( 'tr' == $audio_position ) {
			$css = 'top:0;right:0;';
		} elseif ( 'bl' == $audio_position ) {
			$css = 'bottom:0;left:0;';
		} elseif ( 'br' == $audio_position ) {
			$css = 'bottom:0;right:0;';
		}

		$wds_get_var = isset( $_GET['to'] ) ? wds_sanitize_text_guest_name( urldecode( $_GET['to'] ) ) : ''; ?>

		<div id="wds-cover" class="wds-cover">

			<div class="wds-cover-dialog">

				<div class="wds-cover-content">

					<div class="wds-cover-overlay"></div>

					<div class="wds-cover-body">

						<div class="centered-box">

							<div style="text-align: center;">

								<?php if ( $settings['image_cover'] ) : ?>

									<div class="elementor-image wds-image-cover <?php echo ! empty( $settings['si_hover_animation'] ) ? 'elementor-animation-' . esc_attr( $settings['si_hover_animation'] ) : ''; ?>"><?php echo wp_kses_post( $image_html ); ?></div>

								<?php endif; ?>

								<?php if ( $settings['text_cover'] ) : ?>

									<div class="wds-text-cover"><?php echo wp_kses_post( $settings['text_cover'] ); ?></div>

								<?php endif; ?>

								<?php if ( $settings['name_cover'] ) : ?>

									<div class="wds-name-cover"><?php echo wp_kses_post( $settings['name_cover'] ); ?></div>

								<?php endif; ?>

								<?php if ( $settings['date_cover'] ) : ?>

									<div class="wds-date-cover"><?php echo wp_kses_post( $settings['date_cover'] ); ?></div>

								<?php endif; ?>

								<?php if ( $settings['text_to_cover'] ) : ?>

									<div class="wds-text-to-cover"><?php echo wp_kses_post( $settings['text_to_cover'] ); ?></div>

								<?php endif; ?>

								<div class="wds-get-name"><?php echo wp_kses_post( $wds_get_var ); ?></div>

								<?php if ( $settings['text_invitation_cover'] ) : ?>

									<div class="wds-text-invitation-cover"><?php echo wp_kses_post( $settings['text_invitation_cover'] ); ?></div>

								<?php endif; ?>

								<?php if ( $settings['text_btn_cover'] ) : ?>

									<div id="wds-button-cover" class="wds-button-cover">

										<button class="elementor-button">

											<?php if ( ! empty( $settings['icon_btn_cover']['value'] ) ) : ?>

												<span class="wds-icon-btn-cover">
													<?php
													if ( $is_new || $migrated ) :
														Icons_Manager::render_icon( $settings['icon_btn_cover'], array( 'aria-hidden' => 'true' ) );
													else :
														?>
														<i <?php echo esc_attr( $this->get_render_attribute_string( 'icon_btn_cover' ) ); ?>></i>
													<?php endif; ?>
												</span>

											<?php endif; ?>

											<span class="wds-text-btn-cover"><?php echo wp_kses_post( $settings['text_btn_cover'] ); ?></span>

										</button>

									</div>

								<?php endif; ?>

								<?php if ( $settings['text_note_cover'] ) : ?>

									<div class="wds-text-note-cover"><?php echo wp_kses_post( $settings['text_note_cover'] ); ?></div>

								<?php endif; ?>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<?php if ( $audio ) : ?>

			<div id="wds-audio-container" class="wds-audio-box" style="position:fixed;z-index:50;<?php echo esc_attr( $css ); ?>">

				<?php if ( $youtube ) : ?>

					<div id="youtube-audio" data-video="<?php echo esc_url( $audio ); ?>"></div>

				<?php else : ?>

					<?php $arr = explode( '.', $audio ); ?>
					<?php $file_ext = end( $arr ); ?>

					<audio id="song" loop>
						<source src="<?php echo esc_url( $audio ); ?>" type="audio/<?php echo esc_attr( $file_ext ); ?>">
					</audio>

				<?php endif; ?>

				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore ?> id="unmute-sound" style="display: block; line-height: 0;">
					<<?php echo $icon_tag . ' ' . $this->get_render_attribute_string( 'icon-wrapper' ); // phpcs:ignore ?> style="cursor: pointer;">
						<?php
						if ( $is_new || $migrated ) :
							Icons_Manager::render_icon( $settings['audio_pause_icon'], array( 'aria-hidden' => 'true' ) );
						else :
							?>
							<i <?php echo esc_attr( $this->get_render_attribute_string( 'icon' ) ); ?>></i>
						<?php endif; ?>
					</<?php echo esc_attr( $icon_tag ); ?>>
				</div>

				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore  ?> id="mute-sound" style="display: none; line-height: 0;">
					<<?php echo $icon_tag . ' ' . $this->get_render_attribute_string( 'icon-wrapper' ); // phpcs:ignore ?> style="cursor: pointer;">
						<?php
						if ( $is_new || $migrated ) :
							Icons_Manager::render_icon( $settings['audio_play_icon'], array( 'aria-hidden' => 'true' ) );
						else :
							?>
							<i <?php echo esc_attr( $this->get_render_attribute_string( 'icon' ) ); ?>></i>
						<?php endif; ?>
					</<?php echo esc_attr( $icon_tag ); ?>>
				</div>

			</div>

		<?php endif; ?>

		<?php if ( $audio && $youtube ) : ?>
			<script src="https://www.youtube.com/iframe_api"></script> <?php // phpcs:ignore ?>
		<?php endif; ?>

		<script type="text/javascript">
			if (!jQuery('body').hasClass('elementor-editor-active')) {
				jQuery('body').css('overflow', 'hidden').css('padding-right', '17px');
			}
			jQuery('#wds-button-cover button').on('click', function() {
				const cover = jQuery('#wds-cover');
				cover.addClass('fade-out');
				setTimeout(function() {
					cover.remove();
				}, 1000);
				jQuery('body').css('overflow', '').css('padding-right', '');
			});

			<?php if ( $audio ) : ?>

				function audioActive() {
					jQuery('#mute-sound').show();
					jQuery('#unmute-sound').hide();
				}

				function audioInactive() {
					jQuery('#mute-sound').hide();
					jQuery('#unmute-sound').show();
				}

				<?php if ( ! $youtube ) : ?>

					var audioElement = jQuery('#song')[0];

					var startTime = '<?php echo esc_html( $audio_start ); ?>';
					var stopTime = '<?php echo esc_html( $audio_end ); ?>';

					// set metadata
					audioElement.addEventListener("loadedmetadata", function () {
						if (startTime && startTime > 0) {
							audioElement.currentTime = startTime;
						}
					});

					// loop
					audioElement.addEventListener("timeupdate", function () {
						if (stopTime && audioElement.currentTime >= stopTime) {
							audioElement.currentTime =
								startTime && startTime > 0 ? startTime : 0;
						}
					});

					function toggleAudio() {
						if (audioElement.paused) {
							audioElement.play();
							audioActive();
						} else {
							audioElement.pause();
							audioInactive();
						}
					}

					jQuery('#wds-button-cover button, #unmute-sound .elementor-icon').on('click', function(){
						audioElement.play();
						audioActive();
					});

					jQuery('#mute-sound .elementor-icon').on('click', function() {
						if (!audioElement.paused) {
							toggleAudio();
						}
					});

					document.addEventListener('visibilitychange', function () {
						if (document.hidden) {
							audioElement.pause();
							audioInactive();
						} else {
							audioElement.play();
							audioActive();
						}
					});

				<?php else : ?>

					var player;
					var startSeconds = '<?php echo esc_html( $settings['youtube_start'] ); ?>';
					var endSeconds = '<?php echo esc_html( $settings['youtube_end'] ); ?>';

					function toggleAudio() {
						if (player.getPlayerState() == 1 || player.getPlayerState() == 3) {
							player.pauseVideo();
							audioInactive();
						} else {
							player.playVideo();
							audioActive();
						}
					}

					function onYouTubeIframeAPIReady() {
						var ytplay = document.getElementById('youtube-audio');
						ytplay.innerHTML = '<div id="youtube-player"></div>';
						ytplay.style.cssText = 'visibility:hidden;display:none;';
						ytplay.onclick = toggleAudio;
						player = new YT.Player('youtube-player', {
							height: '20',
							width: '20',
							videoId: extractVideoID(ytplay.dataset.video),
							playerVars: {
								autoplay: ytplay.dataset.autoplay,
								loop: ytplay.dataset.loop,
								start: startSeconds,
								end: endSeconds,
								playsinline: 1
							},
							events: {
								'onReady': onPlayerReady,
								'onStateChange': onPlayerStateChange
							}
						});
					}

					function onPlayerReady(event) {
						event.target.playVideo();
						player.setPlaybackQuality('small');
					}

					function onPlayerStateChange(event) {
						if (event.data === 0) {
							player.playVideo();
							startSeconds: startSeconds;
							endSeconds: endSeconds;
							var duration = startSeconds - endSeconds;
							setTimeout(restartVideoSection, duration * 1000);
						}
					}

					function restartVideoSection() {
						player.seekTo(startSeconds);
					}

					function extractVideoID(url) {
						var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
						var match = url.match(regExp);
						if (match && match[7].length == 11) {
							return match[7];
						} else {
							console.log('Could not extract video ID.');
						}
					}

					jQuery('#wds-button-cover button, #unmute-sound .elementor-icon').on('click', function(){
						player.playVideo();
						audioActive();
					});

					jQuery('#mute-sound .elementor-icon').on('click', function() {
						if (player.getPlayerState() === 1 || player.getPlayerState() === 3) {
							player.pauseVideo();
							audioInactive();
						}
					});

					document.addEventListener('visibilitychange', function () {
						if (document.hidden) {
							player.pauseVideo();
							audioInactive();
						} else {
							toggleAudio();
						}
					});

				<?php endif; ?>

			<?php endif; ?>
		</script>
		<?php
	}

	/**
	 * Import element.
	 *
	 * @param array $element The element.
	 */
	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'icon_btn_cover' );
	}

	/**
	 * Content template.
	 */
	protected function content_template() {
		?>
		<# iconHTML=elementor.helpers.renderIcon( view, settings.audio_pause_icon, { 'aria-hidden' : true }, 'i' , 'object' ), migrated=elementor.helpers.isIconMigrated( settings, 'audio_pause_icon' ), iconTag='div' ; #>
			<div class="elementor-icon-wrapper">
				<{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}">
					<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
						{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						<# } #>
				</{{{ iconTag }}}>
			</div>
		<?php
	}
}
