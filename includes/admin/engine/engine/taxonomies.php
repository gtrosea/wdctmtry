<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'engine',
		'title'  => __( 'Taxonomies', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'           => 'taxonomy',
				'type'         => 'group',
				'title'        => '',
				'button_title' => __( 'Add Taxonomy', 'wds-notrans' ),
				'fields'       => array(
					array(
						'id'    => 'name',
						'type'  => 'text',
						'title' => __( 'Taxonomy Name', 'wds-notrans' ),
						'desc'  => __( 'Set unique name for your taxonomy. Eg. `Wedding`', 'wds-notrans' ),
					),
					array(
						'id'    => 'slug',
						'type'  => 'text',
						'title' => __( 'Taxonomy Slug', 'wds-notrans' ),
						'desc'  => __( 'Set slug for your taxonomy. Slug should contain only letters, numbers and `-` or `_` chars.', 'wds-notrans' ),
					),
					array(
						'id'          => 'post_type',
						'type'        => 'select',
						'title'       => __( 'Post Type', 'wds-notrans' ),
						'desc'        => __( 'Select post types to add this taxonomy for.', 'wds-notrans' ),
						'placeholder' => __( 'Select post type', 'wds-notrans' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => 'post_types',
					),
					array(
						'type'    => 'subheading',
						'content' => __( 'Configuration', 'wds-notrans' ),
					),
					array(
						'id'         => 'configuration',
						'type'       => 'accordion',
						'title'      => '',
						'accordions' => array(
							array(
								'title'  => 'Labels',
								'fields' => array(
									array(
										'id'    => 'singular_name',
										'type'  => 'text',
										'title' => __( 'Singular name', 'wds-notrans' ),
										'desc'  => __( 'Name for one object of this post type', 'wds-notrans' ),
									),
									array(
										'id'    => 'search_items',
										'type'  => 'text',
										'title' => __( 'Search items text', 'wds-notrans' ),
										'desc'  => __( 'Default is Search Tags or Search Categories', 'wds-notrans' ),
									),
									array(
										'id'    => 'all_items',
										'type'  => 'text',
										'title' => __( 'All items text', 'wds-notrans' ),
										'desc'  => __( 'Default is All Tags or All Categories', 'wds-notrans' ),
									),
									array(
										'id'    => 'edit_item',
										'type'  => 'text',
										'title' => __( 'Edit item text', 'wds-notrans' ),
										'desc'  => __( 'Default is Edit Tag or Edit Category', 'wds-notrans' ),
									),
									array(
										'id'    => 'view_item',
										'type'  => 'text',
										'title' => __( 'View Item', 'wds-notrans' ),
										'desc'  => __( 'Default is View Tag or View Category', 'wds-notrans' ),
									),
									array(
										'id'    => 'update_item',
										'type'  => 'text',
										'title' => __( 'Update item text', 'wds-notrans' ),
										'desc'  => __( 'Default is Update Tag or Update Category', 'wds-notrans' ),
									),
									array(
										'id'    => 'add_new_item',
										'type'  => 'text',
										'title' => __( 'Add new item text', 'wds-notrans' ),
										'desc'  => __( 'Default is Add New Tag or Add New Category', 'wds-notrans' ),
									),
									array(
										'id'    => 'new_item_name',
										'type'  => 'text',
										'title' => __( 'New item name text', 'wds-notrans' ),
										'desc'  => __( 'Default is New Tag Name or New Category Name', 'wds-notrans' ),
									),
									array(
										'id'    => 'parent_item',
										'type'  => 'text',
										'title' => __( 'Parent item text', 'wds-notrans' ),
										'desc'  => __( 'This string is not used on non-hierarchical taxonomies such as post tags', 'wds-notrans' ),
									),
									array(
										'id'    => 'not_found',
										'type'  => 'text',
										'title' => __( 'Items not found text', 'wds-notrans' ),
										'desc'  => __( 'The text displayed via clicking "Choose from the most used tags" in the taxonomy meta box when no tags are available and the text used in the terms list table when there are no items for a taxonomy', 'wds-notrans' ),
									),
								),
							),
							array(
								'title'  => __( 'Advance Settings', 'wds-notrans' ),
								'fields' => array(
									array(
										'id'      => 'public',
										'type'    => 'switcher',
										'title'   => __( 'Is Public', 'wds-notrans' ),
										'desc'    => __( 'Whether a taxonomy is intended for use publicly either via the admin interface or by front-end users', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'publicly_queryable',
										'type'    => 'switcher',
										'title'   => __( 'Publicly Queryable', 'wds-notrans' ),
										'desc'    => __( 'Whether the taxonomy is publicly queryable', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'show_ui',
										'type'    => 'switcher',
										'title'   => __( 'Show Admin UI', 'wds-notrans' ),
										'desc'    => __( 'Whether to generate a default UI for managing this taxonomy', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'show_in_menu',
										'type'    => 'switcher',
										'title'   => __( 'Show in Admin Menu', 'wds-notrans' ),
										'desc'    => __( 'Where to show the taxonomy in the admin menu. `Show Admin UI` must be enabled', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'query_var',
										'type'    => 'switcher',
										'title'   => __( 'Register Query Var', 'wds-notrans' ),
										'desc'    => __( 'Sets the query_var key for taxonomy', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'    => 'rewrite',
										'type'  => 'text',
										'title' => __( 'Rewrite Slug', 'wds-notrans' ),
										'desc'  => __( 'Triggers the handling of rewrites for this taxonomy. To prevent rewrites, set to false', 'wds-notrans' ),
									),
									array(
										'id'      => 'show_in_rest',
										'type'    => 'switcher',
										'title'   => __( 'Show in Rest API', 'wds-notrans' ),
										'desc'    => __( 'Whether to expose this taxonomy in the REST API. Also enable/disable Gutenberg editor for current post type', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'hierarchical',
										'type'    => 'switcher',
										'title'   => __( 'Hierarchical', 'wds-notrans' ),
										'desc'    => __( 'Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags', 'wds-notrans' ),
										'default' => true,
									),
								),
							),
						),
					),
				),
			),
		),
	)
);
