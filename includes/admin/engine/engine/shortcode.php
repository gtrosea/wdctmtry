<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'engine',
		'title'  => __( 'Shortcode Generator', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'          => 'shortcode',
				'type'        => 'textarea',
				'title'       => __( 'Shortcode', 'wds-notrans' ),
				'shortcoder'  => 'wds_shortcodes',
				'placeholder' => __( 'Hasil shortcode...', 'weddingsaas' ),
			),
		),
	)
);

$pref = 'wds_shortcodes';

CSF::createShortcoder(
	$pref,
	array(
		'button_title'   => __( 'Generate Shortcode', 'wds-notrans' ),
		'select_title'   => __( 'Source', 'wds-notrans' ),
		'insert_title'   => __( 'Submit', 'wds-notrans' ),
		'show_in_editor' => true,
	)
);

$data_post     = wds_get_metaboxes( 'post' );
$optgroup_post = $data_post['optgroup'];

$data_user     = wds_get_metaboxes( 'user' );
$optgroup_user = $data_user['optgroup'];

$data_taxonomy = wds_get_metaboxes( 'taxonomy' );
$optgroup_tax  = $data_taxonomy['optgroup'];

$context = array(
	'current_user'        => __( 'Current User', 'wds-notrans' ),
	'current_post_author' => __( 'Current Post Author', 'wds-notrans' ),
);

if ( wds_is_replica() ) {
	$context['replica'] = __( 'Replica', 'wds-notrans' );
}

CSF::createSection(
	$pref,
	array(
		'title'     => __( 'Meta Data', 'wds-notrans' ),
		'view'      => 'normal',
		'shortcode' => 'wds',
		'fields'    => array(
			array(
				'id'      => 'source',
				'type'    => 'select',
				'title'   => __( 'Source', 'wds-notrans' ),
				'options' => array(
					'post'     => 'Post',
					'user'     => 'User',
					'taxonomy' => 'Taxonomy',
				),
				'default' => 'post',
			),
			array(
				'id'         => 'post_meta',
				'type'       => 'select',
				'title'      => __( 'Post Meta', 'wds-notrans' ),
				'options'    => $optgroup_post,
				'dependency' => array( 'source', '==', 'post' ),
			),
			array(
				'id'         => 'user_meta',
				'type'       => 'select',
				'title'      => __( 'User Meta', 'wds-notrans' ),
				'options'    => $optgroup_user,
				'dependency' => array( 'source', '==', 'user' ),
			),
			array(
				'id'         => 'term_meta',
				'type'       => 'select',
				'title'      => __( 'Taxonomy Meta', 'wds-notrans' ),
				'options'    => $optgroup_tax,
				'dependency' => array( 'source', '==', 'taxonomy' ),
			),
			array(
				'id'         => 'context',
				'type'       => 'select',
				'title'      => __( 'Context', 'wds-notrans' ),
				'options'    => $context,
				'dependency' => array( 'source', '==', 'user' ),
			),
			array(
				'id'    => 'filter',
				'type'  => 'switcher',
				'title' => __( 'Filter Output', 'wds-notrans' ),
			),
			array(
				'id'         => 'callback',
				'type'       => 'select',
				'title'      => __( 'Callback', 'wds-notrans' ),
				'options'    => array(
					'date'                 => 'Format Date',
					'initial'              => 'Inisial',
					'currency'             => 'Mata Uang',
					'background_slideshow' => 'Background Slideshow',
				),
				'default'    => 'date',
				'dependency' => array( 'filter', '==', 'true' ),
			),
			array(
				'id'         => 'date_format',
				'type'       => 'text',
				'title'      => __( 'Format', 'wds-notrans' ),
				'default'    => 'l, j F Y',
				'desc'       => 'Tutorial : <a href="https://wordpress.org/documentation/article/customize-date-and-time-format/" target="_blank">Klik Disini</a>',
				'dependency' => array( 'filter|callback', '==|==', 'true|date' ),
			),
			array(
				'id'    => 'fallback',
				'type'  => 'text',
				'title' => __( 'Fallback', 'wds-notrans' ),
			),
		),
	)
);
