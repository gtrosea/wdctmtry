<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Default_MetaClass.
 */
class Widget_Audio extends \Elementor\Widget_Base {

	/**
	 * Get name.
	 */
	public function get_name() {
		return 'wds_audio';
	}

	/**
	 * Get title.
	 */
	public function get_title() {
		return __( 'Audio', 'wds-notrans' );
	}

	/**
	 * Get icon.
	 */
	public function get_icon() {
		return 'eicon-headphones';
	}

	/**
	 * Get categories.
	 */
	public function get_categories() {
		return array( 'weddingsaas' );
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
			'wds-audio',
		);
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
			'section_audio',
			array( 'label' => __( 'Audio', 'wds-notrans' ) )
		);

		$this->add_control(
			'custom',
			array(
				'label'        => __( 'Custom Data', 'wds-notrans' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Secara default menggunakan post meta dari WeddingSaas.', 'wds-notrans' ),
				'label_on'     => __( 'Yes', 'wds-notrans' ),
				'label_off'    => __( 'No', 'wds-notrans' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'important_note_audio',
			array(
				'label'     => __( 'Important Note!', 'wds-notrans' ),
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => __( 'Just use one, the Audio Link or the YouTube Link.', 'wds-notrans' ),
				'condition' => array( 'custom' => 'yes' ),
			)
		);

		$this->add_control(
			'audio_source',
			array(
				'label'     => __( 'Source', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'url',
				'options'   => array(
					'url'     => __( 'Url', 'wds-notrans' ),
					'youtube' => __( 'Youtube', 'wds-notrans' ),
				),
				'condition' => array( 'custom' => 'yes' ),
			)
		);

		$this->add_control(
			'audio_link_header',
			array(
				'label'     => __( 'Audio Link', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'custom'       => 'yes',
					'audio_source' => 'url',
				),
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
				'condition'     => array(
					'custom'       => 'yes',
					'audio_source' => 'url',
				),
			)
		);

		$this->add_control(
			'youtube_link_header',
			array(
				'label'     => __( 'Youtube Link', 'wds-notrans' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'custom'       => 'yes',
					'audio_source' => 'youtube',
				),
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
				'condition'   => array(
					'custom'       => 'yes',
					'audio_source' => 'youtube',
				),
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
				'condition'          => array(
					'custom'       => 'yes',
					'audio_source' => 'youtube',
				),
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
				'condition'          => array(
					'custom'       => 'yes',
					'audio_source' => 'youtube',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			array( 'label' => __( 'Button', 'wds-notrans' ) )
		);

		$this->add_control(
			'pause_icon',
			array(
				'label'            => __( 'Icon Play', 'wds-notrans' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fa fa-music',
					'library' => 'fa-solid',
				),
				'separator'        => 'before',
			)
		);

		$this->add_control(
			'play_icon',
			array(
				'label'            => __( 'Icon Stop', 'wds-notrans' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fa fa-volume-mute',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'view',
			array(
				'label'        => __( 'View', 'wds-notrans' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'default' => __( 'Default', 'wds-notrans' ),
					'stacked' => __( 'Stacked', 'wds-notrans' ),
					'framed'  => __( 'Framed', 'wds-notrans' ),
				),
				'default'      => 'default',
				'prefix_class' => 'elementor-view-',
			)
		);

		$this->add_control(
			'shape',
			array(
				'label'        => __( 'Shape', 'wds-notrans' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'circle' => __( 'Circle', 'wds-notrans' ),
					'square' => __( 'Square', 'wds-notrans' ),
				),
				'default'      => 'circle',
				'condition'    => array(
					'view!' => 'default',
				),
				'prefix_class' => 'elementor-shape-',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'wds-notrans' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'wds-notrans' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'wds-notrans' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'wds-notrans' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			array(
				'label' => __( 'Icon', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			array(
				'label' => __( 'Normal', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'primary_color',
			array(
				'label'     => __( 'Primary Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
			)
		);

		$this->add_control(
			'secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'view!' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			array(
				'label' => __( 'Hover', 'wds-notrans' ),
			)
		);

		$this->add_control(
			'hover_primary_color',
			array(
				'label'     => __( 'Primary Color', 'wds-notrans' ),
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
			'hover_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'view!' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => __( 'Hover Animation', 'wds-notrans' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'size',
			array(
				'label'     => __( 'Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_padding',
			array(
				'label'     => __( 'Padding', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'condition' => array(
					'view!' => 'default',
				),
			)
		);

		$this->add_responsive_control(
			'rotate',
			array(
				'label'     => __( 'Rotate', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
					'unit' => 'deg',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon i, {{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->add_control(
			'border_width',
			array(
				'label'     => __( 'Border Width', 'wds-notrans' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'view' => 'framed',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'view!' => 'default',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$post_id           = get_the_ID();
		$audio_link        = ! empty( $settings['audio_link']['url'] ) ? $settings['audio_link']['url'] : '';
		$data_audio_link   = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_audio' ) : $audio_link;
		$data_youtube_link = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_audio_youtube' ) : $settings['youtube_link'];
		$data_audio_start  = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_audio_start' ) : $settings['youtube_start'];
		$data_audio_end    = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_audio_end' ) : $settings['youtube_end'];

		// $audio_source = $settings['audio_source'];
		$audio_link   = ! empty( $data_audio_link ) ? $data_audio_link : false;
		$youtube_link = ! empty( $data_youtube_link ) ? $data_youtube_link : false;
		$audio_source = ! empty( $audio_link ) ? 'url' : 'youtube';

		$audio_element = ( $audio_link || $youtube_link ) ? true : false;

		$js_vars = array(
			'source'      => ! empty( $audio_source ) ? $audio_source : 'inactive',
			'audio_link'  => 'url' == $audio_source ? $audio_link : $youtube_link,
			'audio_start' => $data_audio_start,
			'audio_end'   => $data_audio_end,
			'autoplay'    => wds_engine( 'audio_autoplay' ),
		);

		echo '<script type="text/javascript">' . PHP_EOL;
		echo 'var WdsAudio = ' . wp_json_encode( $js_vars ) . PHP_EOL;
		echo '</script>' . PHP_EOL;

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-icon-wrapper' );
		$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon' );

		if ( ! empty( $settings['hover_animation'] ) ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		if ( empty( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			$settings['icon'] = 'fa fa-music';
		}

		if ( ! empty( $settings['icon'] ) ) {
			$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			$this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
		}

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed(); ?>


		<?php if ( $audio_element ) : ?>

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore ?> id="wds_audio_play">
				<div <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); // phpcs:ignore ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['pause_icon'], array( 'aria-hidden' => 'true' ) );
					else :
						?>
					<i <?php echo $this->get_render_attribute_string( 'icon' ); // phpcs:ignore ?>></i>
				<?php endif; ?>
				</div>
			</div> 

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore ?> id="wds_audio_pause">
				<div <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); // phpcs:ignore ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['play_icon'], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i <?php echo $this->get_render_attribute_string( 'icon' ); // phpcs:ignore ?>></i>
				<?php endif; ?>
				</div>
			</div>

			<div id="wds-audio-box" style="display:none !important;">

				<?php if ( 'youtube' == $audio_source && $youtube_link ) : ?>

					<div id="youtube-audio" data-video="<?php echo esc_url( $youtube_link ); ?>"></div>

				<?php elseif ( 'url' == $audio_source && $audio_link ) : ?>

					<?php $arr = explode( '.', $audio_link ); ?>
					<?php $file_ext = end( $arr ); ?>

					<audio id="song" loop>
						<source src="<?php echo esc_url( $audio_link ); ?>" type="audio/<?php echo esc_attr( $file_ext ); ?>">
					</audio>

				<?php endif; ?>

			</div>

			<?php
		endif;
	}

	/**
	 * Render the widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# 
			iconHTML = elementor.helpers.renderIcon( view, settings.pause_icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'pause_icon' ),
			iconTag = 'div';
		#>
		<div class="elementor-icon-wrapper">
			<{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}" >
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
