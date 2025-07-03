<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Widget_Invitation_Theme Class.
 */
class Widget_Invitation_Theme extends \Elementor\Widget_Base {

	/**
	 * Get name.
	 */
	public function get_name() {
		return 'wds_invitation_theme';
	}

	/**
	 * Get title.
	 */
	public function get_title() {
		return __( 'Invitation Theme', 'wds-notrans' );
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
	 * Get keyboard.
	 */
	public function get_keywords() {
		return array( 'theme', 'tema' );
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
		$data          = $this->data();
		$categories    = $data['categories'];
		$subcategories = $data['subcategories'];
		$subthemes     = $data['subthemes'];

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show',
			array(
				'label'   => __( 'Show', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'category'    => __( 'Category', 'wds-notrans' ),
					'subcategory' => __( 'Sub Category', 'wds-notrans' ),
					'subtheme'    => __( 'Sub Theme', 'wds-notrans' ),
				),
				'default' => 'category',
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => __( 'Category', 'wds-notrans' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $categories,
				'default'     => 'all',
				'description' => __( 'Jika Anda pilih kategori tertentu, sub kategori dan sub tema tidak ditampilkan.', 'weddingsaas' ),
				'condition'   => array( 'show' => 'category' ),
			)
		);

		$this->add_control(
			'subcategory',
			array(
				'label'     => __( 'Sub Category', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $subcategories,
				'condition' => array( 'show' => 'subcategory' ),
			)
		);

		$this->add_control(
			'subtheme',
			array(
				'label'     => __( 'Sub Theme', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $subthemes,
				'condition' => array( 'show' => 'subtheme' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_section',
			array(
				'label' => __( 'Button', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'preview_text',
			array(
				'label'   => __( 'Preview Text', 'wds-notrans' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Preview',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_style',
			array(
				'label' => __( 'General', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'general_cards_margin_top',
			array(
				'label'      => __( 'Cards Margin Top', 'wds-notrans' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .tema-cards-container' => 'margin-top: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'category_style',
			array(
				'label' => __( 'Category', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'category_padding',
			array(
				'label'      => __( 'Padding', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .wds-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'category_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_text_typography',
				'selector' => '{{WRAPPER}} .wds-title',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'category_border',
				'selector' => '{{WRAPPER}} .wds-nav-link',
			)
		);

		$this->add_control(
			'category_active_color',
			array(
				'label'     => __( 'Active Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-nav-link.active:before' => 'background: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'separator_style',
			array(
				'label' => __( 'Separator', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Separator Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-sep:before, .wds-sep:after' => 'border-bottom: 1px solid {{VALUE}};' ),
			)
		);

		$this->add_control(
			'separator_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-sep span' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'separator_text_typography',
				'selector' => '{{WRAPPER}} .wds-sep span',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'subcat_style',
			array(
				'label' => __( 'Sub Category & Sub Theme', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'subcat_background',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.wds-nav-title.wds-secondary' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'subcat_background_active',
			array(
				'label'     => __( 'Background Active Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.wds-nav-title.wds-primary' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'subcat_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .wds-nav-title' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'subcat_text_typography',
				'selector' => '{{WRAPPER}} .wds-nav-title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_style',
			array(
				'label' => __( 'Card', 'wds-notrans' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_background_color',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .tema-card' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_category',
			array(
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'heading'    => __( 'Category', 'wds-notrans' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_category_text_typography',
				'selector' => '{{WRAPPER}} .tema-card__cat',
			)
		);

		$this->add_control(
			'card_category_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .tema-card__cat' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_title',
			array(
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'heading'    => __( 'Title', 'wds-notrans' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_title_text_typography',
				'selector' => '{{WRAPPER}} .tema-card__title',
			)
		);

		$this->add_control(
			'card_title_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .tema-card__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_button',
			array(
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'heading'    => __( 'Button', 'wds-notrans' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_button_text_typography',
				'selector' => '{{WRAPPER}} .tema-card__content a',
			)
		);

		$this->add_control(
			'card_button_text_color',
			array(
				'label'     => __( 'Text Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .tema-card__content a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_button_background_color',
			array(
				'label'     => __( 'Background Color', 'wds-notrans' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .tema-card__content a' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'card_button_border',
				'selector' => '{{WRAPPER}} .tema-card__content a',
			)
		);

		$this->add_control(
			'card_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .tema-card__content a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'card_button_padding',
			array(
				'label'      => __( 'Padding', 'wds-notrans' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .tema-card__content a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$show_settings        = $settings['show'];
		$category_settings    = $settings['category'];
		$subcategory_settings = $settings['subcategory'];
		$subtheme_settings    = $settings['subtheme'];

		$preview_text = $settings['preview_text'];

		// Variable
		$is_all              = 'all' == $category_settings ? 'yes' : '';
		$current_category    = isset( $_GET['category'] ) ? $_GET['category'] : '';
		$current_category    = empty( $is_all ) ? $category_settings : $current_category;
		$current_subcategory = isset( $_GET['sub'] ) ? $_GET['sub'] : '';
		$current_subtheme    = isset( $_GET['theme'] ) ? $_GET['theme'] : '';

		// If Settings Exists
		if ( 'subcategory' == $show_settings && $subcategory_settings ) {
			list($id_cat, $id_subcat) = explode( ',', $subcategory_settings );
			$current_category         = $id_cat;
			$current_subcategory      = $id_subcat;
		} elseif ( 'subtheme' == $show_settings && $subtheme_settings ) {
			list($id_cat, $id_subcat, $id_subtheme) = explode( ',', $subtheme_settings );
			if ( 0 == $id_subcat ) {
				$current_category = $id_cat;
			} else {
				$current_category    = $id_cat;
				$current_subcategory = $id_subcat;
			}
			$current_subtheme = $id_subtheme;
		}

		$get_categories    = wds_get_categories();
		$icon_all_category = wds_option( 'category_icon' );

		$get_subcategories = '';
		if ( $current_category ) {
			$get_subcategories = get_categories(
				array(
					'parent'     => $current_category,
					'hide_empty' => false,
				)
			);
		}

		$get_subthemes = array();
		if ( $current_category || $current_subcategory ) {
			if ( $current_category && $current_subcategory ) {
				$taxonomy = wds_get_selected_taxonomy( $current_subcategory );
			} else {
				$taxonomy = wds_get_selected_taxonomy( $current_category );
			}

			if ( $taxonomy ) {
				$terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'parent'     => 0,
						'hide_empty' => false,
					)
				);

				if ( $terms ) {
					foreach ( $terms as $term ) {
						$children = get_term_children( $term->term_id, $taxonomy );
						if ( ! empty( $children ) ) {
							$get_subthemes[] = (object) array(
								'ID'   => $term->term_id,
								'name' => $term->name,
							);
						}
					}
				}
			}
		}

		$show_subcategory = $get_subcategories ? '' : 'display:none;';
		$show_subtheme    = $get_subthemes ? '' : 'display:none;';

		$request = isset( $_SERVER[' REQUEST_URI'] ) ? $_SERVER[' REQUEST_URI'] : '';

		ob_start(); ?>

		<?php if ( $is_all ) : ?>

			<div class="category-wrap" id="tema">

				<ul class="wds-category-list wds-nav">

					<li class="wds-nav-item">

						<a href="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); ?>#tema" class="wds-nav-link<?php echo $current_category ? '' : ' active'; ?>">

							<?php if ( $icon_all_category ) : ?>
								<img class="wds-icon" src="<?php echo esc_url( $icon_all_category ); ?>" alt="Semua Kategori" />
							<?php endif; ?>

							<div class="wds-title"><?php esc_html_e( 'All', 'wds-notrans' ); ?></div>

						</a>

					</li>

					<?php
					foreach ( $get_categories as $cat ) :
						$_id    = $cat->term_id;
						$_name  = $cat->name;
						$_icon  = wds_term_meta( $cat->term_id, '_icon' );
						$_icon  = $_icon ? $_icon : wds_term_meta( $cat->term_id, '_custom_icon' );
						$active = $current_category == $_id ? ' active' : '';
						$link   = '?category=' . $_id;
						?>

						<li class="wds-nav-item">

							<a href="<?php echo esc_url( $link ); ?>#tema" class="wds-nav-link<?php echo esc_attr( $active ); ?>">

								<?php if ( $_icon ) : ?>
									<img class="wds-icon" src="<?php echo esc_url( $_icon ); ?>" alt="<?php echo esc_attr( $_name ); ?>" />
								<?php endif; ?>

								<div class="wds-title"><?php echo esc_html( $_name ); ?></div>

							</a>

						</li>

					<?php endforeach; ?>

				</ul>

				<div class="wds-sep" style="<?php echo esc_attr( $show_subcategory ); ?>"><span style="width: 150px;">Sub Kategori</span></div>

				<ul class="wds-subcategory-list wds-nav" style="<?php echo esc_attr( $show_subcategory ); ?>">

					<li class="wds-nav-item">

						<a href="<?php echo esc_url( strtok( $request, '?' ) . '?category=' . $current_category ); ?>#tema" class="wds-nav-title <?php echo $current_subcategory ? 'wds-secondary' : 'wds-primary'; ?>"><?php esc_html_e( 'All', 'wds-notrans' ); ?></a>

					</li>

					<?php if ( $get_subcategories ) : ?>

						<?php
						foreach ( $get_subcategories as $subcategories ) :
							$_id    = $subcategories->term_id;
							$_name  = $subcategories->name;
							$active = $current_subcategory == $_id ? ' wds-primary' : ' wds-secondary';
							$link   = '?category=' . $current_category . '&sub=' . $_id;
							?>

							<li class="wds-nav-item">
								<a href="<?php echo esc_url( $link ); ?>#tema" class="wds-nav-title<?php echo esc_attr( $active ); ?>"><?php echo esc_html( $_name ); ?></a>
							</li>

						<?php endforeach; ?>

					<?php endif; ?>

				</ul>

				<div class="wds-sep" style="<?php echo esc_attr( $show_subtheme ); ?>"><span style="width: 150px;">Sub Tema</span></div>

				<ul class="wds-subtheme-list wds-nav" style="<?php echo esc_attr( $show_subtheme ); ?>">

					<li class="wds-nav-item">

						<a href="<?php echo esc_url( strtok( $request, '?' ) . '?category=' . $current_category . '&sub=' . $current_subcategory ); ?>#tema" class="wds-nav-title <?php echo $current_subtheme ? 'wds-secondary' : 'wds-primary'; ?>"><?php esc_html_e( 'All', 'wds-notrans' ); ?></a>

					</li>

					<?php if ( $get_subthemes ) : ?>

						<?php
						foreach ( $get_subthemes as $themes ) :
							$_id    = $themes->ID;
							$_name  = $themes->name;
							$active = $current_subtheme == $_id ? ' wds-primary' : ' wds-secondary';
							$link   = '?category=' . $current_category . '&sub=' . $current_subcategory . '&theme=' . $_id;
							?>

							<li class="wds-nav-item">
								<a href="<?php echo esc_url( $link ); ?>#tema" class="wds-nav-title<?php echo esc_attr( $active ); ?>"><?php echo esc_html( $_name ); ?></a>
							</li>

						<?php endforeach; ?>

					<?php endif; ?>

				</ul>

			</div>

		<?php endif; ?>

		<?php
		$category = array();
		$theme    = array();
		$tax      = array();

		// Get All Category
		$get_categories = get_categories( array( 'hide_empty' => false ) );
		foreach ( $get_categories as $cat ) {
			$category[] = (object) array(
				'ID'   => $cat->term_id,
				'name' => $cat->name,
			);
		}

		// Filter Taxonomy
		foreach ( $category as $item ) {
			$tax[] = wds_get_selected_taxonomy( $item->ID );
		}

		$taxonomy_subtheme = '';
		if ( $current_subtheme ) {
			if ( $current_category && $current_subcategory ) {
				$taxonomy_subtheme = wds_get_selected_taxonomy( $current_subcategory );
			} else {
				$taxonomy_subtheme = wds_get_selected_taxonomy( $current_category );
			}

			$all_terms = get_term_children( $current_subtheme, $taxonomy_subtheme );
		} elseif ( $current_category && $current_subcategory ) {
			$taxonomy  = wds_get_selected_taxonomy( $current_subcategory );
			$all_terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);
		} elseif ( $current_category ) {
			$taxonomy_array = array();
			foreach ( $get_subcategories as $subcategory ) {
				$taxonomy_array[] = wds_get_selected_taxonomy( $subcategory->term_id );
			}

			if ( $taxonomy_array ) {
				$all_terms = get_terms(
					array(
						'taxonomy'   => $taxonomy_array,
						'hide_empty' => false,
					)
				);
			} else {
				$taxonomy  = wds_get_selected_taxonomy( $current_category );
				$all_terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'hide_empty' => false,
					)
				);
			}
		} else {
			$all_terms = get_terms( array( 'hide_empty' => false ) );
		}
		?>

		<div class="tema-cards-container wds-row wds-g-3 wds-g-md-4" <?php echo $is_all ? '' : 'style="margin-top:0px !important;"'; ?> >

			<?php foreach ( $all_terms as $terms ) : ?>

				<?php
				if ( $current_subtheme ) {
					$terms = get_term_by( 'id', $terms, $taxonomy_subtheme );
				}
				?>

				<?php if ( in_array( $terms->taxonomy, $tax ) ) : ?>

					<?php if ( ! in_array( $terms->term_id, wds_get_subthemes_ids() ) ) : ?>

						<?php
						$thumbnail = wds_term_meta( $terms->term_id, '_thumbnail' );
						$thumbnail = $thumbnail ? $thumbnail : wds_term_meta( $terms->term_id, '_custom_thumbnail' );

						$_id        = $terms->term_id;
						$_name      = $terms->name;
						$category   = wds_categories_name_by_taxonomy_theme( $terms->taxonomy );
						$_parent    = wds_get_parent_categories( $category['id'] );
						$_category  = $_parent . $category['title'];
						$_taxonom   = $terms->taxonomy;
						$_thumbnail = empty( wds_term_meta( $terms->term_id, '_thumbnail' ) ) && empty( wds_term_meta( $terms->term_id, '_custom_thumbnail' ) ) ? '' : $thumbnail;

						$post_id  = ! empty( wds_term_meta( $_id, '_theme' ) ) ? wds_term_meta( $_id, '_theme' ) : 0;
						$_preview = wds_term_meta( $_id, '_preview' );
						if ( wds_is_replica() ) {
							$_preview = wds_replica_replace_domain( $_preview );
						}
						$_preview = ! empty( $_preview ) ? $_preview : get_permalink( $post_id );
						?>

						<div class="tema-card-wrap wds-col-6 wds-col-md-4 wds-col-lg-3">

							<div class="tema-card">

								<div class="tema-card__thumb">

									<?php if ( $_thumbnail ) : ?>

										<img class="card-img" src="<?php echo esc_url( $_thumbnail ); ?>" alt="<?php echo esc_attr( $_name ); ?>" />

									<?php else : ?>

										<svg class="bd-placeholder-img card-img-top" width="100%" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
											<title>Placeholder</title>
											<rect width="100%" height="100%" fill="#868e96"></rect>
										</svg>

									<?php endif; ?>

								</div>

								<div class="tema-card__content">

									<div class="tema-card__cat" <?php echo $is_all ? '' : 'style="display:none;"'; ?> ><?php echo esc_html( $_category ); ?></div>

									<h6 class="tema-card__title"><?php echo esc_html( $_name ); ?></h6>

									<a href="<?php echo esc_url( $_preview ); ?>" target="_blank" class="btn btn-primary"><?php echo $preview_text ? esc_html( $preview_text ) : esc_html__( 'Preview', 'wds-notrans' ); ?></a>

								</div>

							</div>

						</div>

					<?php endif; ?>

				<?php endif; ?>

			<?php endforeach; ?>

		</div>

		<?php
		echo ob_get_clean(); //phpcs:ignore
	}

	/**
	 * Data
	 */
	protected function data() {
		$categories       = array();
		$subcategories    = array();
		$subthemes        = array();
		$subthemes_cat    = array();
		$subthemes_subcat = array();

		$categories['all'] = __( 'All', 'wds-notrans' );

		// Category
		foreach ( wds_get_categories() as $cat ) {
			$id_cat                = $cat->term_id;
			$categories[ $id_cat ] = $cat->name;

			$get_subcategory = get_categories(
				array(
					'parent'     => $id_cat,
					'hide_empty' => false,
				)
			);

			// Sub Category
			if ( $get_subcategory ) {
				foreach ( $get_subcategory as $subcategory ) {
					$id_subcat                      = $subcategory->term_id;
					$value_subcat                   = $id_cat . ',' . $id_subcat;
					$subcategories[ $value_subcat ] = $subcategory->name;

					// Sub Theme SubCategory
					$taxonomy = wds_get_selected_taxonomy( $id_subcat );
					if ( $taxonomy ) {
						$terms = get_terms(
							array(
								'taxonomy'   => $taxonomy,
								'parent'     => 0,
								'hide_empty' => false,
							)
						);

						if ( $terms ) {
							foreach ( $terms as $term ) {
								$children = get_term_children( $term->term_id, $taxonomy );
								if ( ! empty( $children ) ) {

									$value_subtheme                      = $id_cat . ',' . $id_subcat . ',' . $term->term_id;
									$subthemes_subcat[ $value_subtheme ] = $term->name;

								}
							}
						}
					}
				}
			}

			// Sub Theme Category
			$taxonomy = wds_get_selected_taxonomy( $id_cat );
			if ( $taxonomy ) {
				$terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'parent'     => 0,
						'hide_empty' => false,
					)
				);

				if ( $terms ) {
					foreach ( $terms as $term ) {
						$children = get_term_children( $term->term_id, $taxonomy );
						if ( ! empty( $children ) ) {

							$value_subtheme                   = $id_cat . ',0,' . $term->term_id;
							$subthemes_cat[ $value_subtheme ] = $term->name;

						}
					}
				}
			}
		}

		$subthemes = array_merge( $subthemes_subcat, $subthemes_cat );

		return array(
			'categories'    => $categories,
			'subcategories' => $subcategories,
			'subthemes'     => $subthemes,
		);
	}
}
