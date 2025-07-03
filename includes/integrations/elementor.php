<?php

namespace WDS\Integrations;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Elementor Class.
 */
class Elementor {

	/**
	 * Singleton instance.
	 *
	 * @var Elementor|null
	 */
	public static $instance = null;

	/**
	 * Background slideshow module instance.
	 *
	 * @var mixed
	 */
	public $background_slideshow;

	/**
	 * Initializes the singleton instance.
	 *
	 * @return Elementor Singleton instance of Elementor class.
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_enqueue' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'add_enqueue' ) );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'add_enqueue' ) );

		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_new_dynamic_tags' ) );
		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_new_dynamic_tag_group' ) );

		add_action( 'elementor/elements/categories_registered', array( $this, 'register_new_widget_categories' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_new_widget' ) );

		$this->modules();
	}

	/**
	 * Enqueues the main CSS file for WeddingSaas.
	 */
	public function add_enqueue() {
		wp_enqueue_style( 'wds-elementor', wds_assets( 'css/wds-elementor.css' ), array(), WDS_VERSION, 'all' );
		wp_register_script( 'wds-audio', wds_assets( 'js/wds-elementor-audio.js' ), array( 'jquery' ), WDS_VERSION, true );
		wp_register_style( 'widget-wds-floating-menu', wds_assets( 'css/wds-elementor-floating-menu.css' ), array(), WDS_VERSION );
		wp_register_script( 'wds-floating-menu', wds_assets( 'js/wds-elementor-floating-menu.js' ), array( 'jquery' ), WDS_VERSION, true );
		wp_register_script( 'wds-audio-library', wds_assets( 'js/wds-elementor-audio-library.js' ), array(), WDS_VERSION, true );
	}

	/**
	 * Registers custom dynamic tags for Elementor.
	 *
	 * @param \Elementor\DynamicTags_Manager $dynamic_tags_manager Elementor's dynamic tags manager instance.
	 */
	public function register_new_dynamic_tags( $dynamic_tags_manager ) {
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/post-custom-field.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/post-custom-gallery.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/post-custom-image.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/post-image.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/post.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/term-field.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/term-image.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/user-custom-field.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/user-custom-gallery.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/user-custom-image.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/user-image.php';
		require_once WDS_INCLUDES . '/integrations/elementor/dynamic/user.php';

		$dynamic_tags_manager->register( new Elementor\Dynamic_Post_Custom_Field() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_Post_Custom_Gallery() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_Post_Custom_Image() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_Post_Image() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_Post() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_Term_Field() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_Term_Image() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_User_Custom_Field() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_User_Custom_Gallery() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_User_Custom_Image() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_User_Image() );
		$dynamic_tags_manager->register( new Elementor\Dynamic_User() );
	}

	/**
	 * Registers a custom dynamic tag group for WeddingSaas.
	 *
	 * @param \Elementor\DynamicTags_Manager $dynamic_tags_manager Elementor's dynamic tags manager instance.
	 */
	public function register_new_dynamic_tag_group( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register_group(
			'weddingsaas',
			array( 'title' => 'WeddingSaaS' )
		);
	}

	/**
	 * Registers a custom widget category for WeddingSaas.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor's elements manager instance.
	 */
	public function register_new_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'weddingsaas',
			array(
				'title' => 'WeddingSaas',
				'icon'  => WDS_ICON,
			)
		);
	}

	/**
	 * Registers custom widgets for Elementor.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor's widgets manager instance.
	 */
	public function register_new_widget( $widgets_manager ) {
		require_once WDS_INCLUDES . 'integrations/elementor/widgets/calendar.php';
		require_once WDS_INCLUDES . 'integrations/elementor/widgets/cover.php';
		require_once WDS_INCLUDES . 'integrations/elementor/widgets/audio.php';
		require_once WDS_INCLUDES . 'integrations/elementor/widgets/audio-library.php';
		require_once WDS_INCLUDES . 'integrations/elementor/widgets/invitation-theme.php';
		require_once WDS_INCLUDES . 'integrations/elementor/widgets/floating-menu.php';

		$widgets_manager->register( new Elementor\Widget_Calendar() );
		$widgets_manager->register( new Elementor\Widget_Cover() );
		$widgets_manager->register( new Elementor\Widget_Audio() );
		$widgets_manager->register( new Elementor\Widget_Audio_Library() );
		$widgets_manager->register( new Elementor\Widget_Invitation_Theme() );
		$widgets_manager->register( new Elementor\Widget_Floating_Menu() );

		if ( 'default' == wds_option( 'rsvp_integration' ) ) {
			require_once WDS_INCLUDES . 'integrations/elementor/widgets/rsvp.php';
			$widgets_manager->register( new Elementor\Widget_RSVP() );
		}
	}

	/**
	 * Loads additional modules for Elementor integration.
	 *
	 * @return void
	 */
	private function modules() {
		require_once WDS_INCLUDES . 'integrations/elementor/helper.php';
		require_once WDS_INCLUDES . 'integrations/elementor/default-meta.php';
		if ( 'default' == wds_option( 'rsvp_integration' ) ) {
			require_once WDS_INCLUDES . 'integrations/elementor/commentpress.php';
		}
	}
}

Elementor::init();
