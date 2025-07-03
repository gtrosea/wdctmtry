<?php
/**
 * WeddingSaas Category.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Contents
 */

namespace WDS;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Category Class.
 */
class Category {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'manage_edit-category_columns', array( $this, 'custom_category_columns' ) );
		add_action( 'manage_category_custom_column', array( $this, 'custom_category_column_content' ), 10, 3 );

		add_action( 'admin_init', array( $this, 'save_taxonomy_option' ), 20 );
	}

	/**
	 * Adds a new column to the category table.
	 *
	 * @param array $columns Current columns in the table.
	 * @return array Updated columns with the new custom column added.
	 */
	public function custom_category_columns( $columns ) {
		$new_columns = array();
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			if ( 'name' === $key ) {
				$new_columns['wds_taxonomy'] = __( 'Taxonomy', 'wds-notrans' );
			}
		}

		return $new_columns;
	}

	/**
	 * Populates the custom column in the category table with data.
	 *
	 * @param string $content    The current content of the column (default is empty).
	 * @param string $column_name The name of the current column.
	 * @param int    $term_id    The ID of the current term (category).
	 * @return string The content for the custom column.
	 */
	public function custom_category_column_content( $content, $column_name, $term_id ) {
		if ( 'wds_taxonomy' === $column_name ) {
			$meta     = wds_term_meta( $term_id, '_template' );
			$taxonomy = get_taxonomy( $meta );

			return $taxonomy ? $taxonomy->label : '<span style="color:#DC3232;font-weight:bold;">' . __( 'Not set', 'wds-notrans' ) . '</span>';
		}
	}

	/**
	 * Save taxonomy option.
	 */
	public function save_taxonomy_option() {
		$templates  = array();
		$excluded   = array( 'category', 'post_tag', 'post_format' );
		$taxonomies = get_object_taxonomies( array( 'post', 'wds_template' ), 'objects' );
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! in_array( $taxonomy->name, $excluded ) ) {
				$templates[ $taxonomy->name ] = $taxonomy->label;
			}
		}

		$all_categories = get_categories( array( 'hide_empty' => false ) );

		$configs       = array();
		$memberships   = array();
		$memberships[] = 'category';
		foreach ( $all_categories as $category ) {
			$template = wds_term_meta( $category->term_id, '_template' );
			if ( $template ) {
				$configs[]     = $template;
				$memberships[] = $template;
			}
		}

		$data = array(
			'template'   => $templates,
			'membership' => $memberships,
			'config'     => $configs,
		);

		update_option( 'wds_taxonomy_data', $data );
	}
}

new Category();
