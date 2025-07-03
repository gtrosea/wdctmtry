<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'engine',
		'title'  => __( 'Metaboxes', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'           => 'metabox',
				'type'         => 'group',
				'title'        => '',
				'button_title' => __( 'Add Metabox', 'wds-notrans' ),
				'fields'       => array(
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => __( 'Meta Box Title', 'wds-notrans' ),
						'desc'  => __( 'Title will be shown ar the top of Meta Box on edit Post page. Eg. `Invitation`', 'wds-notrans' ),
					),
					array(
						'id'      => 'type',
						'type'    => 'select',
						'title'   => __( 'Meta Box for', 'wds-notrans' ),
						'desc'    => __( 'Select to add this meta box to posts or users.', 'wds-notrans' ),
						'options' => array(
							'post'     => 'Post',
							'user'     => 'User',
							'taxonomy' => 'Taxonomy',
						),
						'default' => 'post',
					),
					array(
						'id'          => 'condition',
						'type'        => 'select',
						'title'       => __( 'Enable For Post Types', 'wds-notrans' ),
						'desc'        => __( 'Select post types where this meta box should be shown.', 'wds-notrans' ),
						'placeholder' => __( 'Select post type', 'wds-notrans' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => 'post_types',
						'dependency'  => array( 'type', '==', 'post' ),
					),
					array(
						'id'          => 'objects',
						'type'        => 'select',
						'title'       => __( 'Enable For Taxonomies', 'wds-notrans' ),
						'desc'        => __( 'Select taxonomies where this meta box should be shown.', 'wds-notrans' ),
						'placeholder' => __( 'Select taxonomy', 'wds-notrans' ),
						'chosen'      => true,
						'multiple'    => true,
						'options'     => 'all_taxonomies_public',
						'dependency'  => array( 'type', '==', 'taxonomy' ),
					),
					array(
						'type'    => 'subheading',
						'content' => __( 'Section', 'wds-notrans' ),
					),
					array(
						'id'           => 'section',
						'type'         => 'group',
						'title'        => '',
						'button_title' => __( 'New Section', 'wds-notrans' ),
						'fields'       => array(
							array(
								'id'    => 'name',
								'type'  => 'text',
								'title' => __( 'Name', 'wds-notrans' ),
								'desc'  => __( 'Pastikan nama section sudah benar, apabila diganti maka dynamic tag yang sudah dihubungkan akan terlepas.', 'weddingsaas' ),
							),
							array(
								'type'    => 'subheading',
								'content' => 'Meta Field',
							),
							array(
								'id'           => 'field',
								'type'         => 'group',
								'title'        => '',
								'button_title' => __( 'New Field', 'wds-notrans' ),
								'fields'       => array(
									array(
										'id'       => 'label',
										'type'     => 'text',
										'title'    => __( 'Label', 'wds-notrans' ),
										'subtitle' => __( 'Meta field label', 'wds-notrans' ),
									),
									array(
										'id'       => 'key',
										'type'     => 'text',
										'title'    => __( 'Meta Key', 'wds-notrans' ),
										'subtitle' => __( 'Meta field name/key/ID', 'wds-notrans' ),
										'desc'     => __( 'Should contain only Latin letters, numbers, `-` or `_` chars.', 'wds-notrans' ),
									),
									array(
										'id'      => 'type',
										'type'    => 'select',
										'title'   => __( 'Field Type', 'wds-notrans' ),
										'options' => array(
											'text'      => 'Text',
											'textarea'  => 'Text Area',
											'wp_editor' => 'WYSIWYG',
											'number'    => 'Number',
											'media'     => 'Media',
											'gallery'   => 'Gallery',
											'date'      => 'Date',
											'datetime'  => 'Date Time',
											'color'     => 'Color',
										),
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
