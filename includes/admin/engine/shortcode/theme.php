<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'shortcode',
		'title'  => __( 'Invitation Theme', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'    => 'notice',
				'style'   => 'info',
				'content' => 'Shortcode:
				<ul>
					<li>Tema Undangan : <code>[wds_tema]</code></li>
					<li>Tema Undangan dengan kategori : <code>[wds_tema category="yes"]</code></li>
				</ul>',
			),
			array(
				'id'    => 'tema_reload',
				'type'  => 'switcher',
				'title' => __( 'Reload halaman ketika klik simpan.', 'weddingsaas' ),
			),
			array(
				'id'    => 'tema_category',
				'type'  => 'switcher',
				'title' => __( 'Tampilkan kategori pada form sebagai default.', 'weddingsaas' ),
			),
		),
	)
);
