<?php

namespace WDS\Integrations\Elementor;

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Widget_Calendar Class.
 */
class Widget_Calendar extends \Elementor\Widget_Base {

	/**
	 * Get name.
	 */
	public function get_name() {
		return 'wds_calendar';
	}

	/**
	 * Get title.
	 */
	public function get_title() {
		return __( 'Google Calendar', 'wds-notrans' );
	}

	/**
	 * Get icon.
	 */
	public function get_icon() {
		return 'eicon-calendar';
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
		return 'https://weddingsaas.id/docs/';
	}

	/**
	 * Register control.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_calendar',
			array( 'label' => __( 'Calendar', 'wds-notrans' ) )
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
			'calendar_title',
			array(
				'label'       => __( 'Title', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array( 'custom' => 'yes' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'calendar_start',
			array(
				'label'       => __( 'Date Time Start', 'wds-notrans' ),
				'type'        => Controls_Manager::DATE_TIME,
				'label_block' => true,
				'condition'   => array( 'custom' => 'yes' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'calendar_end',
			array(
				'label'       => __( 'Date Time End', 'wds-notrans' ),
				'type'        => Controls_Manager::DATE_TIME,
				'label_block' => true,
				'condition'   => array( 'custom' => 'yes' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'calendar_location',
			array(
				'label'       => __( 'Location', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array( 'custom' => 'yes' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'calendar_description',
			array(
				'label'       => __( 'Description', 'wds-notrans' ),
				'type'        => Controls_Manager::WYSIWYG,
				'description' => __( 'You can use variable [guest-name] to call the guest name.', 'wds-notrans' ),
				'condition'   => array( 'custom' => 'yes' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			array( 'label' => __( 'Button', 'wds-notrans' ) )
		);

		$this->add_control(
			'button_type',
			array(
				'label'        => __( 'Type', 'wds-notrans' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => array(
					''        => __( 'Default', 'wds-notrans' ),
					'info'    => __( 'Info', 'wds-notrans' ),
					'success' => __( 'Success', 'wds-notrans' ),
					'warning' => __( 'Warning', 'wds-notrans' ),
					'danger'  => __( 'Danger', 'wds-notrans' ),
				),
				'prefix_class' => 'elementor-button-',
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'wds-notrans' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Save on the calendar', 'wds-notrans' ),
				'placeholder' => __( 'Save on the calendar', 'wds-notrans' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'wds-notrans' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'wds-notrans' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'wds-notrans' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'wds-notrans' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'wds-notrans' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'center',
			)
		);

		$this->add_control(
			'size',
			array(
				'label'          => __( 'Size', 'wds-notrans' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 'sm',
				'options'        => Default_Meta::$button_size,
				'style_transfer' => true,
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'            => __( 'Icon', 'wds-notrans' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'icon',
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'                => __( 'Icon Position', 'wds-notrans' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => is_rtl() ? 'row-reverse' : 'row',
				'options'              => array(
					'row'         => array(
						'title' => __( 'Start', 'wds-notrans' ),
						'icon'  => 'eicon-h-align-left',
					),
					'row-reverse' => array(
						'title' => __( 'End', 'wds-notrans' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'  => is_rtl() ? 'row-reverse' : 'row',
					'right' => is_rtl() ? 'row' : 'row-reverse',
				),
				'selectors'            => array( '{{WRAPPER}} .elementor-button-content-wrapper' => 'flex-direction: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'icon_indent',
			array(
				'label'      => __( 'Icon Spacing', 'wds-notrans' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 50 ),
					'em'  => array( 'max' => 5 ),
					'rem' => array( 'max' => 5 ),
				),
				'selectors'  => array( '{{WRAPPER}} .elementor-button .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'wds-notrans' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 60,
					),
				),
				'selectors' => array( '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => __( 'View', 'wds-notrans' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Button', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array( 'label' => __( 'Normal', 'wds-notrans' ) )
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array( 'label' => __( 'Hover', 'wds-notrans' ) )
		);

		$this->add_control(
			'hover_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} a.elementor-button:hover svg, {{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} a.elementor-button:focus svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'border_border!' => '' ),
				'selectors' => array( '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};' ),
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

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'selector'  => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			)
		);

		$this->add_responsive_control(
			'text_padding',
			array(
				'label'      => __( 'Padding', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Default Meta
		$post_id     = get_the_ID();
		$guest       = isset( $_GET['to'] ) ? $_GET['to'] : '';
		$date_format = 'Y-m-d H:i';

		$description      = '';
		$description_meta = wds_post_meta( $post_id, '_calendar_description' );
		if ( $description_meta ) {
			$args = array(
				'guest-name' => $guest,
				'nama-tamu'  => $guest,
			);

			$description = wds_email_replace_shortcode( $description_meta, $args );
		}

		$calendar_title       = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_calendar_title' ) : $settings['calendar_title'];
		$calendar_start       = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_calendar_start' ) : $settings['calendar_start'];
		$calendar_end         = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_calendar_end' ) : $settings['calendar_end'];
		$calendar_location    = 'yes' != $settings['custom'] ? wds_post_meta( $post_id, '_calendar_location' ) : $settings['calendar_location'];
		$calendar_description = 'yes' != $settings['custom'] ? $description : $settings['calendar_description'];

		$calendar_url = 'https://www.google.com/calendar/render?action=TEMPLATE';

		if ( $calendar_title ) {
			$calendar_url .= '&text=' . rawurlencode( $calendar_title );
		}

		if ( $calendar_description ) {
			$calendar_url .= '&details=' . rawurlencode( $calendar_description );
		}

		if ( $calendar_location ) {
			$calendar_url .= '&location=' . rawurlencode( $calendar_location );
		}

		// Calendar Start
		$start = str_replace( 'T', ' ', $calendar_start );
		if ( \DateTime::createFromFormat( 'Y-m-d H:i:s', $start ) !== false ) {
			if ( strlen( $start ) == 19 ) {
				$start = substr_replace( $start, '', 16 );
			}
		}

		if ( empty( $start ) ) {
			$start = new \DateTime();
		} else {
			$start = \DateTime::createFromFormat( $date_format, $start );
		}
		if ( $start ) {
			$calendar_url .= '&dates=' . rawurlencode( get_gmt_from_date( $start->format( 'Y-m-d H:i' ), 'Ymd\\THi00\\Z' ) );
		}

		// Calendar End
		$end = str_replace( 'T', ' ', $calendar_end );
		if ( \DateTime::createFromFormat( 'Y-m-d H:i:s', $end ) !== false ) {
			if ( strlen( $end ) == 19 ) {
				$end = substr_replace( $end, '', 16 );
			}
		}

		if ( empty( $end ) && $start ) {
			$end = new \DateTime( $start->format( 'Y-m-d H:i' ) );
			$end = $end->modify( '+ 1 day' );
		} elseif ( empty( $end ) && ! $start ) {
			$end = new \DateTime();
		} else {
			$end = \DateTime::createFromFormat( $date_format, $end );
		}

		if ( $end ) {
			$calendar_url .= '%2F' . rawurlencode( get_gmt_from_date( $end->format( 'Y-m-d H:i' ), 'Ymd\\THi00\\Z' ) );
		}

		if ( get_option( 'timezone_string' ) ) {
			$calendar_url .= '&ctz=' . get_option( 'timezone_string' );
		}

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );
		$this->add_render_attribute( 'button', 'href', $calendar_url );
		$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
		$this->add_render_attribute( 'button', 'target', '_blank' );
		$this->add_render_attribute( 'button', 'rel', 'nofollow' );
		$this->add_render_attribute( 'button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . sanitize_text_field( $settings['size'] ) );
		}

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . sanitize_text_field( $settings['hover_animation'] ) );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ); ?>>

			<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'button' ) ); ?>><?php $this->render_text(); ?></a>
			
		</div>
		<?php
	}

	/**
	 * Render text.
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$settings['icon_align'] = 'row';
		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			$settings['icon_align'] = $this->get_settings( 'icon_align' );
		}

		$this->add_render_attribute(
			array(
				'content-wrapper' => array(
					'class' => array( 'elementor-button-content-wrapper', 'wds-flexbox' ),
				),
				'icon_align'      => array(
					'class' => array(
						'elementor-button-icon',
						'elementor-align-icon-' . $settings['icon_align'],
					),
				),
				'text'            => array( 'class' => 'elementor-button-text' ),
			)
		);

		$this->add_inline_editing_attributes( 'text', 'none' );
		?>
		<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'content-wrapper' ) ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
				<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon_align' ) ); ?>>
					<?php
					if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
					else :
						?>
						<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
					<?php endif; ?>
				</span>
			<?php endif; ?>
			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'text' ) ); ?>><?php echo wp_kses_post( $settings['text'] ); ?></span>
		</span>
		<?php
	}

	/**
	 * Import element.
	 *
	 * @param string $element The element.
	 */
	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
	}
}
