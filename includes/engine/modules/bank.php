<?php

namespace WDS\Engine\Modules;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Bank Class.
 *
 * @since 1.13.0
 */
class Bank {

	/**
	 * Singleton instance of Bank class.
	 *
	 * @var Bank|null
	 */
	protected static $instance = null;

	/**
	 * Retrieve the singleton instance of the Bank class.
	 *
	 * @return Bank Singleton instance.
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
		add_action( 'pre_get_posts', array( $this, 'modify_orderby' ) );
	}

	/**
	 * Get the slug for the bank post type.
	 */
	public function slug() {
		return 'wds-bank';
	}

	/**
	 * Register custom post type.
	 */
	public function register_cpt() {
		$labels = array(
			'name'               => 'WeddingSaas Bank',
			'singular_name'      => 'WeddingSaas Bank',
			'menu_name'          => 'WDS Bank',
			'name_admin_bar'     => 'WeddingSaas Bank',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Bank',
			'new_item'           => 'New Bank',
			'edit_item'          => 'Edit Bank',
			'view_item'          => 'View Bank',
			'all_items'          => 'All Banks',
			'search_items'       => 'Search Banks',
			'not_found'          => 'No Banks found',
			'not_found_in_trash' => 'No Banks found in trash',
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
			'position'   => 44.1122,
			'page_title' => __( 'WDS Bank', 'wds-notrans' ),
			'menu_title' => __( 'Bank', 'wds-notrans' ),
			'url'        => admin_url( 'edit.php?post_type=' . $slug ),
			'button'     => true,
			'btn_text'   => 'post-new.php' === $pagenow ? __( 'Back', 'wds-notrans' ) : __( 'Add New', 'wds-notrans' ),
			'btn_url'    => 'post-new.php' === $pagenow ? admin_url( 'edit.php?post_type=' . $slug ) : admin_url( 'post-new.php?post_type=' . $slug ),
		);

		return $menus;
	}

	/**
	 * Add cpt metabox.
	 */
	public function add_metabox() {
		if ( class_exists( '\CSF' ) ) {
			\CSF::createMetabox(
				$this->slug(),
				array(
					'title'     => __( 'Logo Bank Link', 'wds-notrans' ),
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
							'placeholder' => 'https://assets.domain.com/bank/bca.png',
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
	 * Modifies the query order for post type to order by title in ascending order.
	 *
	 * @param WP_Query $query The query object.
	 */
	public function modify_orderby( $query ) {
		if ( isset( $query->query_vars['post_type'] ) && $query->get( 'post_type' ) === $this->slug() ) {
			if ( $query->get( 'orderby' ) !== 'title' ) {
				$query->set( 'orderby', 'title' );
				$query->set( 'order', 'ASC' );
			}
		}
	}
}

Bank::instance();
