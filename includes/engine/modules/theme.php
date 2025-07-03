<?php

namespace WDS\Engine\Modules;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Theme Class.
 *
 * @since 1.13.0
 */
class Theme {

	/**
	 * Singleton instance of Tema class.
	 *
	 * @var Tema|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the Tema class.
	 *
	 * @return Tema Singleton instance.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'wds_tema', array( $this, 'shortcode' ) );
		add_shortcode( 'wds_theme', array( $this, 'shortcode' ) );
	}

	/**
	 * Display a form to select and preview theme for the edit invitation.
	 *
	 * @param array $atts The data atribute.
	 */
	public function shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'    => 'tema-undangan', // default value
				'category' => wds_engine( 'tema_category' ) ? 'yes' : '', // default value
			),
			$atts
		);

		$post_id       = wds_sanitize_data_field( $_GET, 'id', 0 );
		$data          = WDS()->invitation->get_data( $post_id );
		$category_id   = $data['category_id'];
		$taxonomy      = $data['taxonomy'];
		$term_id       = $data['term_id'];
		$is_theme      = wds_data( 'wds_theme_edit' );
		$spreview      = $is_theme ? get_the_permalink( \WDS_Theme_Main::get_theme_id( $post_id ) ) : wds_term_meta( $term_id, '_preview' );
		$parent        = $is_theme ? 'modal_theme' : sanitize_title( $atts['title'] );
		$show_category = 'yes' === $atts['category'] ? true : false;

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		include_once wds_get_template( 'shortcode/theme.php' );
	}
}

Theme::instance();
