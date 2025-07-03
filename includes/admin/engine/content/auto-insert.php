<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$categories = get_categories( array( 'hide_empty' => false ) );
$opt        = array();
$opt['all'] = __( 'Semua Kategori', 'weddingsaas' );
foreach ( $categories as $category ) {
	$parent      = get_term( $category->parent, 'category' );
	$parent_name = ( $parent && ! is_wp_error( $parent ) ) ? $parent->name . ' | ' : '';

	$opt[ $category->slug ] = $parent_name . $category->name;
}

CSF::createSection(
	$prefix,
	array(
		'parent' => 'content',
		'title'  => __( 'Auto Insert Data', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'      => 'insert_data',
				'type'    => 'group',
				'title'   => '',
				'fields'  => array(
					array(
						'id'    => 'note',
						'type'  => 'text',
						'title' => __( 'Catatan', 'wds-notrans' ),
					),
					array(
						'id'       => 'category',
						'type'     => 'select',
						'chosen'   => true,
						'multiple' => true,
						'title'    => __( 'Category', 'wds-notrans' ),
						'options'  => $opt,
					),
					array(
						'id'     => 'data',
						'type'   => 'group',
						'title'  => '',
						'fields' => array(
							array(
								'id'    => 'name',
								'type'  => 'text',
								'title' => __( 'Field Name', 'wds-notrans' ),
							),
							array(
								'id'      => 'type',
								'type'    => 'select',
								'title'   => __( 'Type', 'wds-notrans' ),
								'options' => array(
									'textarea'  => 'Text',
									'rich_text' => 'WYSIWYG',
								),
							),
							array(
								'id'         => 'textarea',
								'type'       => 'textarea',
								'title'      => __( 'Value', 'wds-notrans' ),
								'sanitize'   => false,
								'dependency' => array( 'type', '==', 'textarea' ),
							),
							array(
								'id'         => 'rich_text',
								'type'       => 'wp_editor',
								'title'      => __( 'Value', 'wds-notrans' ),
								'sanitize'   => false,
								'dependency' => array( 'type', '==', 'rich_text' ),
							),
						),
					),
				),
				'default' => wds_v1_engine_insert(),
			),
		),
	)
);
