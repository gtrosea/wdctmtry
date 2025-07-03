<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Widget_RSVP Class.
 */
class Widget_RSVP extends \Elementor\Widget_Base {

	/**
	 * Get name.
	 */
	public function get_name() {
		return 'wds_rsvp';
	}

	/**
	 * Get title.
	 */
	public function get_title() {
		return __( 'RSVP', 'wds-notrans' );
	}

	/**
	 * Get icon.
	 */
	public function get_icon() {
		return 'eicon-envelope';
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

	// /**
	//  * Get registered styles.
	//  */
	// public function get_style_depends() {
	//  return array( 'saic_style' );
	// }

	// /**
	//  * Get registered scripts.
	//  */
	// public function get_script_depends() {
	//  return array( 'saic_library', 'wds_rsvp' );
	// }

	/**
	 * Register control.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_rsvp',
			array( 'label' => __( 'RSVP', 'wds-notrans' ) )
		);

		$this->add_control(
			'important_description',
			array(
				'raw'             => __( '<b>IMPORTANT!:</b> To use the RSVP Widget make sure the integration is default. <a href="https://weddingsaas.id/docs/cara-mengatur-dashboard-rsvp/" target="_blank">Tutorial</a>', 'wds-notrans' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type'     => 'ui',
				'type'            => Controls_Manager::RAW_HTML,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'rsvp_styles_general',
			array(
				'label' => __( 'Body', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wrapper_color',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_form_blur',
			array(
				'label'       => __( 'Background Blur', 'wds-notrans' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Adjust the blur intensity.', 'wds-notrans' ),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'   => array( '{{WRAPPER}} .saic-wrapper' => '-webkit-backdrop-filter: blur({{SIZE}}px); backdrop-filter: blur({{SIZE}}px);' ),
			)
		);

		$this->add_control(
			'wrapper_icon_color',
			array(
				'label'     => __( 'Icon Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-link a.saic-link.saic-icon-link-true .saico-comment' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-link a.saic-link' => 'font-family: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_font_size',
			array(
				'label'      => __( 'Font Size', 'wds-notrans' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-link a.saic-link' => 'font-size: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'message_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-link a.saic-link' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_style',
			array(
				'label'     => __( 'Border Style', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'wds-notrans' ),
					'solid'  => __( 'Solid', 'wds-notrans' ),
					'double' => __( 'Double', 'wds-notrans' ),
					'dashed' => __( 'Dashed', 'wds-notrans' ),
				),
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-full' => 'border-style: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_width',
			array(
				'label'      => __( 'Border Width', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .saic-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_color',
			array(
				'label'     => __( 'Border Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .saic-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_styles_general',
			array(
				'label' => __( 'Form', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wrapper_form_color',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrap-form' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'font_family_misc_elements',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .comment-form-author, {{WRAPPER}} .saic-select, {{WRAPPER}} .saic-wrap-textarea' => 'font-family: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'font_size_misc_elements',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .comment-form-author, {{WRAPPER}} .saic-select, {{WRAPPER}} .saic-wrap-textarea' => 'font-size: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'font_color_misc_elements',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .comment-form-author, {{WRAPPER}} .saic-select, {{WRAPPER}} .saic-wrap-textarea' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_misc_style',
			array(
				'label'     => __( 'Border Style', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'wds-notrans' ),
					'solid'  => __( 'Solid', 'wds-notrans' ),
					'double' => __( 'Double', 'wds-notrans' ),
					'dashed' => __( 'Dashed', 'wds-notrans' ),
				),
				'selectors' => array( '{{WRAPPER}} .comment-form-author, {{WRAPPER}} .saic-select, {{WRAPPER}} .saic-wrap-textarea' => 'border-style: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_misc_width',
			array(
				'label'      => __( 'Border Width', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .comment-form-author, {{WRAPPER}} .saic-select, {{WRAPPER}} .saic-wrap-textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_misc_color',
			array(
				'label'     => __( 'Border Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .comment-form-author, {{WRAPPER}} .saic-select, {{WRAPPER}} .saic-wrap-textarea' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'wrapper_border_misc_radius',
			array(
				'label'      => __( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .comment-form-author, {{WRAPPER}} .saic-select, {{WRAPPER}} .saic-wrap-textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rsvp_button_styles',
			array(
				'label' => __( 'Button', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Button Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"], {{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="button"].saic-form-btn:hover' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"], {{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="button"].saic-form-btn:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"], {{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="button"].saic-form-btn:hover' => 'font-family: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_font_size',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"]' => 'font-size: {{SIZE}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'button_border_style',
			array(
				'label'     => __( 'Border Style', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'wds-notrans' ),
					'solid'  => __( 'Solid', 'wds-notrans' ),
					'double' => __( 'Double', 'wds-notrans' ),
					'dashed' => __( 'Dashed', 'wds-notrans' ),
					'dotted' => __( 'Dotted', 'wds-notrans' ),
				),
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"], {{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="button"].saic-form-btn:hover' => 'border-style: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_border_width',
			array(
				'label'      => __( 'Border Width', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"], {{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="button"].saic-form-btn:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"], {{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="button"].saic-form-btn:hover' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="submit"], {{WRAPPER}} .saic-wrapper .saic-wrap-form .saic-container-form input[type="button"].saic-form-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'commenter_name_styles',
			array(
				'label' => __( 'Comment', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'comment_text_author',
			array(
				'label'     => __( 'Style Sender Name', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'commenter_name_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-info .saic-commenter-name' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'commenter_name_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-info .saic-commenter-name' => 'font-family: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'commenter_name_font_size',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-info .saic-commenter-name' => 'font-size: {{SIZE}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'comment_text_styles',
			array(
				'label'     => __( 'Style Comment', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'comment_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-text p' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'comment_text_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-text p' => 'font-family: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'comment_text_font_size',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-text p' => 'font-size: {{SIZE}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'comment_time_styles',
			array(
				'label'     => __( 'Style Time', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'comment_time_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-time' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'comment_time_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-time' => 'font-family: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'comment_time_font_size',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper ul.saic-container-comments li.saic-item-comment .saic-comment-content .saic-comment-time' => 'font-size: {{SIZE}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'comment_link_styles',
			array(
				'label'     => __( 'Reply, Edit, Delete Styles', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'comment_link_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-reply-link, {{WRAPPER}} .saic-edit-link, {{WRAPPER}} .saic-delete-link' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'comment_link_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-reply-link, {{WRAPPER}} .saic-edit-link, {{WRAPPER}} .saic-delete-link' => 'font-family: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'comment_link_font_size',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .saic-reply-link, {{WRAPPER}} .saic-edit-link, {{WRAPPER}} .saic-delete-link' => 'font-size: {{SIZE}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'success_comment_styles',
			array(
				'label'     => __( 'Style Icon Loading', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_loading_color',
			array(
				'label'     => __( 'Icon Loading Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-comment-status.saic-loading>span' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'success_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-comment-status p.saic-ajax-success' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'success_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-comment-status p.saic-ajax-success' => 'font-family: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'success_font_size',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .saic-wrapper .saic-comment-status p.saic-ajax-success' => 'font-size: {{SIZE}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'              => __( 'Avatar', 'wds-notrans' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'Active', 'wds-notrans' ),
				'label_on'           => __( 'Hide', 'wds-notrans' ),
				'frontend_available' => true,
				'return_value'       => 'yes',
				'default'            => 'no',
				'separator'          => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'saic_holder_style_section',
			array(
				'label' => __( 'Page', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'saic_holder_background_color',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-holder' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'saic_holder_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .saic-holder' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'saic_holder_font_family',
			array(
				'label'     => __( 'Font Family', 'wds-notrans' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array( '{{WRAPPER}} .saic-holder' => 'font-family: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'saic_holder_font_size',
			array(
				'label'     => __( 'Font Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .saic-holder' => 'font-size: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$avatar = wds_sanitize_data_field( $settings, 'show_avatar' );
		if ( 'yes' === $avatar ) {
			echo '<style>.saic-comment-content {margin-left: 0 !important;}.saic-comment-avatar {display: none !important;}</style>';
		}

		echo '<div class="saic-wrapper">';
		echo do_shortcode( '[wds_rsvp]' );
		echo '</div>';
	}
}
