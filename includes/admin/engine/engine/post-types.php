<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'engine',
		'title'  => __( 'Post Types', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'           => 'post-type',
				'type'         => 'group',
				'title'        => '',
				'button_title' => __( 'Add Post Type', 'wds-notrans' ),
				'fields'       => array(
					array(
						'id'    => 'name',
						'type'  => 'text',
						'title' => __( 'Post Type Name', 'wds-notrans' ),
						'desc'  => __( 'Set unique name for your post type. Eg. `Theme`', 'wds-notrans' ),
					),
					array(
						'id'    => 'slug',
						'type'  => 'text',
						'title' => __( 'Post Type Slug', 'wds-notrans' ),
						'desc'  => __( 'Set slug for your post type. Slug should contain only letters, numbers and `-` or `_` chars.', 'wds-notrans' ),
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
										'id'    => 'menu_name',
										'type'  => 'text',
										'title' => __( 'Admin Menu', 'wds-notrans' ),
										'desc'  => __( 'Default is the same as `name`', 'wds-notrans' ),
									),
									array(
										'id'    => 'add_new',
										'type'  => 'text',
										'title' => __( 'Add New', 'wds-notrans' ),
										'desc'  => __( 'The add new text. The default is `Add New` for both hierarchical and non-hierarchical post types', 'wds-notrans' ),
									),
									array(
										'id'    => 'add_new_item',
										'type'  => 'text',
										'title' => __( 'Add New Item', 'wds-notrans' ),
										'desc'  => __( 'Default is Add New Post/Add New Page', 'wds-notrans' ),
									),
									array(
										'id'    => 'new_item',
										'type'  => 'text',
										'title' => __( 'New Item', 'wds-notrans' ),
										'desc'  => __( 'Default is New Post/New Page', 'wds-notrans' ),
									),
									array(
										'id'    => 'edit_item',
										'type'  => 'text',
										'title' => __( 'Edit Item', 'wds-notrans' ),
										'desc'  => __( 'Default is Edit Post/Edit Page', 'wds-notrans' ),
									),
									array(
										'id'    => 'view_item',
										'type'  => 'text',
										'title' => __( 'View Item', 'wds-notrans' ),
										'desc'  => __( 'Default is View Post/View Page', 'wds-notrans' ),
									),
									array(
										'id'    => 'all_items',
										'type'  => 'text',
										'title' => __( 'All Items', 'wds-notrans' ),
										'desc'  => __( 'String for the submenu', 'wds-notrans' ),
									),
									array(
										'id'    => 'search_items',
										'type'  => 'text',
										'title' => __( 'Search for items', 'wds-notrans' ),
										'desc'  => __( 'Default is Search Posts/Search Pages', 'wds-notrans' ),
									),
									array(
										'id'    => 'not_found',
										'type'  => 'text',
										'title' => __( 'Not found', 'wds-notrans' ),
										'desc'  => __( 'Default is No posts found/No pages found', 'wds-notrans' ),
									),
									array(
										'id'    => 'not_found_in_trash',
										'type'  => 'text',
										'title' => __( 'Not found in trash', 'wds-notrans' ),
										'desc'  => __( 'Default is No posts found in Trash/No pages found in Trash', 'wds-notrans' ),
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
										'desc'    => __( 'Controls how the type is visible to authors and readers', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'publicly_queryable',
										'type'    => 'switcher',
										'title'   => __( 'Publicly Queryable', 'wds-notrans' ),
										'desc'    => __( 'Whether queries can be performed on the front end as part of parse_request()', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'show_ui',
										'type'    => 'switcher',
										'title'   => __( 'Show Admin UI', 'wds-notrans' ),
										'desc'    => __( 'Whether to generate a default UI for managing this post type in the admin', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'show_in_menu',
										'type'    => 'switcher',
										'title'   => __( 'Show in Admin Menu', 'wds-notrans' ),
										'desc'    => __( 'Where to show the post type in the admin menu. show_ui must be true', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'show_in_rest',
										'type'    => 'switcher',
										'title'   => __( 'Show in Rest API', 'wds-notrans' ),
										'desc'    => __( 'Whether to expose this post type in the REST API. Also enable/disable Gutenberg editor for current post type', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'query_var',
										'type'    => 'switcher',
										'title'   => __( 'Register Query Var', 'wds-notrans' ),
										'desc'    => __( 'Sets the query_var key for this post type', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'    => 'rewrite',
										'type'  => 'text',
										'title' => __( 'Rewrite Slug', 'wds-notrans' ),
										'desc'  => __( 'Customize the permalink structure slug. Defaults to the post type slug', 'wds-notrans' ),
									),
									array(
										'id'      => 'has_archive',
										'type'    => 'switcher',
										'title'   => __( 'Has Archive', 'wds-notrans' ),
										'desc'    => __( 'Enables post type archives', 'wds-notrans' ),
										'default' => true,
									),
									array(
										'id'      => 'hierarchical',
										'type'    => 'switcher',
										'title'   => __( 'Hierarchical', 'wds-notrans' ),
										'desc'    => __( 'Whether the post type is hierarchical (e.g. page)', 'wds-notrans' ),
										'default' => false,
									),
									array(
										'id'      => 'position',
										'type'    => 'number',
										'title'   => __( 'Menu position', 'wds-notrans' ),
										'desc'    => __( 'Select existing menu item to add page after', 'wds-notrans' ),
										'default' => 5,
									),
									array(
										'id'          => 'icon',
										'type'        => 'text',
										'title'       => __( 'Menu Icon', 'wds-notrans' ),
										'subtitle'    => 'Ambil icon <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">DISINI</a>',
										'desc'        => __( 'Icon will be visible in admin menu', 'wds-notrans' ),
										'placeholder' => __( 'Ex: dashicons-menu', 'wds-notrans' ),
										'default'     => 'dashicons-menu',
									),
									array(
										'id'          => 'support',
										'type'        => 'select',
										'title'       => __( 'Supports', 'wds-notrans' ),
										'desc'        => __( 'Registers support of certain feature(s) for a current post type' ),
										'placeholder' => __( 'Select an option', 'wds-notrans' ),
										'chosen'      => true,
										'multiple'    => true,
										'options'     => array(
											'title'        => 'Title',
											'editor'       => 'Editor',
											'comments'     => 'Comments',
											'revisions'    => 'Revisions',
											'trackbacks'   => 'Trackbacks',
											'author'       => 'Author',
											'excerpt'      => 'Excerpt',
											'page-attributes' => 'Page Attributes',
											'thumbnail'    => 'Thumbnail (Featured Image)',
											'custom-fields' => 'Custom Fields',
											'post-formats' => 'Post Formats',
										),
										'default'     => array( 'title', 'editor', 'thumbnail' ),
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
