<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Default_MetaClass.
 *
 * @since 2.4.0
 */
class Widget_Audio_Library extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'audio-library';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Audio Library', 'weddingsaas' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-video-playlist';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'weddingsaas' );
	}

	/**
	 * Retrieve the list of scripts the audio library widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'wds-audio-library',
		);
	}

	/**
	 * Register widget help.
	 */
	public function get_custom_help_url() {
		return 'https://weddingsaas.id/support/';
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'weddingsaas' ),
			)
		);

		$this->add_control(
			'search_placeholder',
			array(
				'label'       => esc_html__( 'Search', 'weddingsaas' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Cari audio...', 'weddingsaas' ),
				'placeholder' => esc_html__( 'Cari audio...', 'weddingsaas' ),
			)
		);

		$this->end_controls_section();

		// Section Style
		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'weddingsaas' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'max_height',
			array(
				'label'      => esc_html__( 'Max Height', 'weddingsaas' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 500,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .wds-audio-list' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_background',
			array(
				'label' => esc_html__( 'Background', 'weddingsaas' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wrap_bg',
				'label'    => esc_html__( 'Background', 'weddingsaas' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wds-audio-library-wrap',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'wrap_border',
				'label'    => esc_html__( 'Border', 'weddingsaas' ),
				'selector' => '{{WRAPPER}} .wds-audio-library-wrap',
			)
		);

		$this->add_control(
			'wrap_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'weddingsaas' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .wds-audio-library-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wrap_padding',
			array(
				'label'      => esc_html__( 'Padding', 'weddingsaas' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 24,
					'right'  => 24,
					'bottom' => 24,
					'left'   => 24,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .wds-audio-library-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_style',
			array(
				'label' => esc_html__( 'Search Box', 'weddingsaas' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'search_typography',
				'label'    => esc_html__( 'Typography', 'weddingsaas' ),
				'selector' => '{{WRAPPER}} .wds-audio-search',
			)
		);

		$this->add_control(
			'search_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'weddingsaas' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#886d4d',
				'selectors' => array(
					'{{WRAPPER}} .wds-audio-search' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'search_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'weddingsaas' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .wds-audio-search' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'search_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'weddingsaas' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .wds-audio-search::placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .wds-audio-search::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wds-audio-search::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wds-audio-search:-ms-input-placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'search_border',
				'label'    => esc_html__( 'Border', 'weddingsaas' ),
				'selector' => '{{WRAPPER}} .wds-audio-search',
			)
		);

		$this->add_control(
			'search_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-audio-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'search_padding',
			array(
				'label'      => esc_html__( 'Padding', 'weddingsaas' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-audio-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			array(
				'label' => esc_html__( 'Audio Item', 'weddingsaas' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_title_typography',
				'label'    => esc_html__( 'Typography', 'weddingsaas' ),
				'selector' => '{{WRAPPER}} .wds-audio-title',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_bg',
				'label'    => esc_html__( 'Background', 'weddingsaas' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wds-audio-item',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'label'    => esc_html__( 'Border', 'weddingsaas' ),
				'selector' => '{{WRAPPER}} .wds-audio-item',
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'weddingsaas' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .wds-audio-item' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'weddingsaas' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wds-audio-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'weddingsaas' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .wds-audio-item' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_active_bg',
			array(
				'label'     => esc_html__( 'Active/Hover Background', 'weddingsaas' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#222',
				'selectors' => array(
					'{{WRAPPER}} .wds-audio-item.active, {{WRAPPER}} .wds-audio-item:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_audio_player_style',
			array(
				'label' => esc_html__( 'Audio Player', 'weddingsaas' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'current_title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'weddingsaas' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .wds-audio-current-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_title_typography',
				'label'    => esc_html__( 'Typography', 'weddingsaas' ),
				'selector' => '{{WRAPPER}} .wds-audio-current-title',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		$audio_list = WDS()->invitation->get_list_audio();
		?>

		<style>
			.wds-audio-library-wrap {
				background: #A0805A;
				border-radius: 16px;
				padding: 24px;
				margin: 0 auto;
				box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
				backdrop-filter: blur(6px);
			}
			.wds-audio-search {
				width: 100%;
				padding: 10px 16px;
				border-radius: 8px;
				border: 1px solid #cbb9a5;
				margin-bottom: 18px;
				background: #886d4d;
				color: #fff;
			}
			.wds-audio-search:focus {
				border-color: #ab8e6d;
				outline: none;
			}
			.wds-audio-search::placeholder {
				color: #fff;
				opacity: 1;
			}
			.wds-audio-list {
				list-style: none;
				padding: 0;
				margin: 0;
				max-height: 500px;
				overflow-y: auto;
			}
			.wds-audio-item {
				background: rgba(0,0,0,0.4);
				margin-bottom: 8px;
				border-radius: 8px;
				color: #fff;
				cursor: pointer;
				transition: background 0.2s;
				padding: 10px 16px;
				display: flex;
				align-items: center;
			}
			.wds-audio-item.active,
			.wds-audio-item:hover {
				background: #222;
			}
			.wds-audio-title {
				flex: 1;
				font-size: 1rem;
				white-space: normal;
				overflow: hidden;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
				line-clamp: 2;
				text-overflow: ellipsis;
				word-break: break-word;
				max-height: 2.7em;
			}
			.wds-audio-current-title-wrap {
				position: relative;
				height: 0;
				margin-bottom: 0;
				width: 100%;
				overflow: hidden;
				transition: height 0.2s, margin-bottom 0.2s;
			}
			.wds-audio-current-title-wrap.active {
				height: auto;
				min-height: 1.5em;
				margin-bottom: 6px;
				overflow: visible;
			}
			.wds-audio-current-title {
				display: block;
				font-weight: bold;
				color: #fff;
				font-size: 1.05em;
				white-space: normal;
				overflow: visible;
				text-overflow: unset;
				word-break: break-word;
				max-width: 100%;
				width: 100%;
			}
		</style>
		
		<div class="wds-audio-library-wrap">
			<input type="text" class="wds-audio-search" placeholder="<?php echo esc_html( $settings['search_placeholder'] ); ?>" onkeyup="wdsAudioFilter(this)">
			<div class="wds-audio-current-title-wrap">
				<span class="wds-audio-current-title"><!-- Title Audio  --></span>
			</div>
			<audio class="wds-audio-player" style="width:100%;margin-bottom:18px;display:none;" controls>
				<source src="" type="audio/mpeg">
				Your browser does not support audio player.
			</audio>
			<ul class="wds-audio-list">
				<?php foreach ( $audio_list as $url => $title ) : ?>
					<li class="wds-audio-item" data-url="<?php echo esc_url( $url ); ?>" data-title="<?php echo esc_attr( $title ); ?>" onclick="wdsPlayAudio(this)">
						<span class="wds-audio-title"><?php echo esc_html( $title ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
}
