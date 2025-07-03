<?php

namespace WDS\Engine\Modules;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Audio Class.
 *
 * @since 1.13.0
 */
class Audio {

	/**
	 * Singleton instance of Audio class.
	 *
	 * @var Audio|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the Audio class.
	 *
	 * @return Audio Singleton instance.
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
		add_action( 'init', array( $this, 'register_cpt' ) );
		add_filter( 'wds_admin_cpt_menu', array( $this, 'add_menu' ) );
		add_action( 'after_setup_theme', array( $this, 'add_metabox' ) );

		add_filter( 'manage_' . $this->slug() . '_posts_columns', array( $this, 'add_custom_column' ) );
		add_filter( 'manage_' . $this->slug() . '_posts_custom_column', array( $this, 'display_custom_column' ), 10, 2 );

		add_shortcode( 'wds_audio', array( $this, 'shortcode' ) );
	}

	/**
	 * Get the slug for the audio post type.
	 */
	public function slug() {
		return 'wds_audio';
	}

	/**
	 * Register custom post type.
	 */
	public function register_cpt() {
		$labels = array(
			'name'               => 'WeddingSaas Audios',
			'singular_name'      => 'WeddingSaas Audio',
			'menu_name'          => 'WDS Audios',
			'name_admin_bar'     => 'WeddingSaas Audio',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Audio',
			'new_item'           => 'New Audio',
			'edit_item'          => 'Edit Audio',
			'view_item'          => 'View Audio',
			'all_items'          => 'All Audios',
			'search_items'       => 'Search Audios',
			'not_found'          => 'No Audios found',
			'not_found_in_trash' => 'No Audios found in trash',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $this->slug() ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'supports'           => array( 'title' ),
		);

		register_post_type( $this->slug(), $args );
	}

	/**
	 * Add custom post type menu.
	 *
	 * @param array $menus The data menu.
	 */
	public function add_menu( $menus ) {
		global $pagenow;

		$slug = $this->slug();

		$menus[ $slug ] = array(
			'group'      => 'cpt',
			'icon'       => WDS_ICON,
			'position'   => 44.1111,
			'page_title' => __( 'WDS Audio', 'wds-notrans' ),
			'menu_title' => __( 'Audio', 'wds-notrans' ),
			'url'        => admin_url( 'edit.php?post_type=' . $slug ),
			'button'     => true,
			'btn_text'   => 'post-new.php' === $pagenow ? __( 'Back', 'wds-notrans' ) : __( 'Add New', 'wds-notrans' ),
			'btn_url'    => 'post-new.php' === $pagenow ? admin_url( 'edit.php?post_type=' . $slug ) : admin_url( 'post-new.php?post_type=' . $slug ),
		);

		return $menus;
	}

	/**
	 * Add custom post type metabox.
	 */
	public function add_metabox() {
		if ( class_exists( '\CSF' ) ) {
			\CSF::createMetabox(
				$this->slug(),
				array(
					'title'     => __( 'Audio Link', 'wds-notrans' ),
					'post_type' => $this->slug(),
					'data_type' => 'unserialize',
				)
			);
			\CSF::createSection(
				$this->slug(),
				array(
					'fields' => array(
						array(
							'id'          => '_link',
							'type'        => 'text',
							'title'       => 'URL',
							'placeholder' => 'https://assets.domain.com/audio/contoh.mp3',
						),
					),
				)
			);
		}
	}

	/**
	 * Add custom URL column to the post type.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns with the custom 'URL' column.
	 */
	public function add_custom_column( $columns ) {
		$new_columns = array();
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			if ( 'title' === $key ) {
				$new_columns['url'] = 'URL';
			}
		}

		return $new_columns;
	}

	/**
	 * Display the URL in the custom column for the post type.
	 *
	 * @param string $column The column name.
	 * @param int    $post_id The post ID.
	 */
	public function display_custom_column( $column, $post_id ) {
		if ( 'url' === $column ) {
			$url = wds_post_meta( $post_id, '_link' );
			if ( $url ) {
				echo esc_url( $url );
			} else {
				echo '-';
			}
		}
	}

	/**
	 * Display a form to select and preview audio for the edit invitation.
	 *
	 * @param array $atts The data atribute.
	 */
	public function shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'title' => 'audio', // default value
			),
			$atts
		);

		$post_id      = intval( wds_sanitize_data_field( $_GET, 'id' ) );
		$music_link   = wds_post_meta( $post_id, '_audio' );
		$youtube_link = wds_post_meta( $post_id, '_audio_youtube' );
		$audio_start  = wds_post_meta( $post_id, '_audio_start' );
		$audio_end    = wds_post_meta( $post_id, '_audio_end' );
		$audio_type   = wds_sanitize_text_field( wds_post_meta( $post_id, '_audio_type' ), 'default' );

		$is_default = 'default' == $audio_type ? true : false;
		$source     = WDS()->invitation->get_list_audio();
		$parent     = wds_data( 'wds_theme_edit' ) ? 'modal_audio' : sanitize_title( $atts['title'] );

		$show_time_default = wds_engine( 'audio_time_default' ) ? '' : ' d-none';

		include_once wds_get_template( 'shortcode/audio.php' );
	}
}

Audio::instance();
