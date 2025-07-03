<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$post_id = wds_sanitize_data_field( $_GET, 'post' );

$post = WDS_SLUG . '_post';

CSF::createMetabox(
	$post,
	array(
		'title'     => __( 'WeddingSaas', 'wds-notrans' ),
		'post_type' => 'post',
		'data_type' => 'unserialize',
		'nav'       => 'inline',
		'theme'     => 'light',
	)
);

CSF::createSection(
	$post,
	array(
		'fields' => array(
			array(
				'id'          => '_wds_membership',
				'type'        => 'select',
				'title'       => __( 'Membership', 'wds-notrans' ),
				'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _wds_membership',
				'placeholder' => __( 'Pilih Produk', 'weddingsaas' ),
				'options'     => wds_get_product_restrict(),
			),
			array(
				'id'       => '_wds_pep_period',
				'type'     => 'date',
				'title'    => __( 'Masa Aktif Undangan', 'weddingsaas' ),
				'subtitle' => __( 'Name', 'wds-notrans' ) . ': _wds_pep_period',
				'settings' => array(
					'dateFormat'      => 'dd M yy',
					'changeMonth'     => true,
					'changeYear'      => true,
					'monthNamesShort' => array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ),
				),
			),
			array(
				'id'       => '_wds_del_period',
				'type'     => 'date',
				'title'    => __( 'Hapus Otomatis', 'weddingsaas' ),
				'subtitle' => __( 'Name', 'wds-notrans' ) . ': _wds_del_period',
				'settings' => array(
					'dateFormat'      => 'dd M yy',
					'changeMonth'     => true,
					'changeYear'      => true,
					'monthNamesShort' => array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ),
				),
			),
			array(
				'id'       => '_wds_pep_action',
				'type'     => 'select',
				'title'    => __( 'Aksi', 'wds-notrans' ),
				'subtitle' => __( 'Name', 'wds-notrans' ) . ': _wds_pep_action',
				'options'  => array(
					'draft' => 'Draft',
					'trash' => 'Trash',
				),
			),
			array(
				'id'       => '_visitor',
				'type'     => 'number',
				'title'    => __( 'Visitor', 'wds-notrans' ),
				'subtitle' => __( 'Name', 'wds-notrans' ) . ': _visitor',
				'class'    => 'hidden-important',
			),
			array(
				'id'         => '_wds_order_id',
				'type'       => 'number',
				'title'      => __( 'Order ID', 'wds-notrans' ),
				'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _wds_order_id',
				'attributes' => array( 'readonly' => 'readonly' ),
			),
		),
	)
);


$restrict = WDS_SLUG . '_restrict';

CSF::createMetabox(
	$restrict,
	array(
		'title'     => __( 'Restrict Content', 'wds-notrans' ) . ' (WDS)',
		'post_type' => wds_option( 'restrict' ),
		'data_type' => 'unserialize',
		'nav'       => 'inline',
		'theme'     => 'light',
	)
);

CSF::createSection(
	$restrict,
	array(
		'fields' => array(
			array(
				'id'    => '_restrict_access',
				'type'  => 'switcher',
				'title' => __( 'Aktifkan', 'weddingsaas' ),
			),
			array(
				'id'         => '_required_level',
				'type'       => 'select',
				'title'      => __( 'Pilih User Group', 'weddingsaas' ),
				'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _required_level',
				'options'    => wds_list_user_group( true, false ),
				'dependency' => array( '_restrict_access', '==', 'true' ),
			),
			array(
				'id'         => '_restrict_redirect',
				'type'       => 'text',
				'title'      => __( 'URL Redirect', 'wds-notrans' ),
				'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _restrict_redirect',
				'desc'       => __( 'Alihkan pengguna ke halaman lain jika tidak memiliki akses.', 'weddingsaas' ),
				'dependency' => array( '_restrict_access', '==', 'true' ),
			),
		),
	)
);


$iframe = WDS_SLUG . '_iframe';

CSF::createMetabox(
	$iframe,
	array(
		'title'     => __( 'Iframe', 'wds-notrans' ) . ' (WDS)',
		'post_type' => wds_option( 'iframe' ),
		'data_type' => 'unserialize',
		'nav'       => 'inline',
		'theme'     => 'light',
	)
);

CSF::createSection(
	$iframe,
	array(
		'fields' => array(
			array(
				'id'       => '_iframe',
				'type'     => 'switcher',
				'title'    => __( 'Aktifkan', 'weddingsaas' ),
				'subtitle' => __( 'Name', 'wds-notrans' ) . ': _iframe',
			),
			array(
				'id'          => '_iframe_url',
				'type'        => 'text',
				'title'       => __( 'Iframe URL', 'wds-notrans' ),
				'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _iframe_url',
				'placeholder' => 'https://example.com',
				'dependency'  => array( '_iframe', '==', 'true' ),
			),
		),
	)
);


if ( wds_is_theme() ) {
	$font = WDS_SLUG . '_font';

	CSF::createMetabox(
		$font,
		array(
			'title'     => __( 'Configuration', 'wds-notrans' ),
			'post_type' => 'wds_font',
			'data_type' => 'unserialize',
			'nav'       => 'inline',
			'theme'     => 'light',
			'class'     => 'wds-post',
		)
	);

	CSF::createSection(
		$font,
		array(
			'fields' => array(
				array(
					'id'          => '_name',
					'type'        => 'text',
					'title'       => __( 'Font Family', 'wds-notrans' ),
					'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _name',
					'placeholder' => 'Ex. "Roboto", sans-serif',
					'sanitize'    => false,
				),
				array(
					'id'          => '_link',
					'type'        => 'text',
					'title'       => __( 'Font Url', 'wds-notrans' ),
					'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _link',
					'placeholder' => 'Ex. @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap")',
					'sanitize'    => false,
				),
			),
		)
	);

	$template = WDS_SLUG . '_template';

	CSF::createMetabox(
		$template,
		array(
			'title'     => __( 'Configuration', 'wds-notrans' ),
			'post_type' => 'wds_template',
			'data_type' => 'unserialize',
			'nav'       => 'inline',
			'theme'     => 'light',
			'class'     => 'wds-post',
		)
	);

	$audios = array();
	$source = WDS()->invitation->get_list_audio();
	if ( wds_check_array( $source, true ) ) {
		foreach ( $source as $audio_link => $title ) {
			$audios[ $audio_link ] = $title;
		}
	}

	CSF::createSection(
		$template,
		array(
			'fields' => array(
				array(
					'id'          => '_default_music',
					'type'        => 'select',
					'title'       => __( 'Audio', 'wds-notrans' ),
					'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _default_music',
					'placeholder' => __( 'Select a audio', 'wds-notrans' ),
					'chosen'      => true,
					'options'     => $audios,
					'class'       => 'fwidth',
				),

				array(
					'type'  => 'subheading',
					'title' => __( 'Typography', 'wds-notrans' ),
				),
				array(
					'id'          => '_font_base',
					'type'        => 'select',
					'title'       => __( 'Font Base', 'wds-notrans' ),
					'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _font_base',
					'placeholder' => __( 'Select a font', 'wds-notrans' ),
					'chosen'      => true,
					'options'     => 'page',
					'query_args'  => array(
						'post_type'      => 'wds_font',
						'posts_per_page' => -1,
					),
					'class'       => 'fwidth',
				),
				array(
					'id'          => '_font_accent',
					'type'        => 'select',
					'title'       => __( 'Font Accent', 'wds-notrans' ),
					'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _font_accent',
					'placeholder' => __( 'Select a font', 'wds-notrans' ),
					'chosen'      => true,
					'options'     => 'page',
					'query_args'  => array(
						'post_type'      => 'wds_font',
						'posts_per_page' => -1,
					),
					'class'       => 'fwidth',
				),
				array(
					'id'          => '_font_latin',
					'type'        => 'select',
					'title'       => __( 'Font Latin', 'wds-notrans' ),
					'subtitle'    => __( 'Name', 'wds-notrans' ) . ': _font_latin',
					'placeholder' => __( 'Select a font', 'wds-notrans' ),
					'chosen'      => true,
					'options'     => 'page',
					'query_args'  => array(
						'post_type'      => 'wds_font',
						'posts_per_page' => -1,
					),
					'class'       => 'fwidth',
				),

				array(
					'type'  => 'subheading',
					'title' => __( 'Color', 'wds-notrans' ),
				),
				array(
					'id'       => '_default_color',
					'type'     => 'color_group',
					'title'    => __( 'Default Color', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _default_color',
					'options'  => array(
						'base'        => __( 'Base', 'wds-notrans' ),
						'accent'      => __( 'Accent', 'wds-notrans' ),
						'button'      => __( 'Button', 'wds-notrans' ),
						'bg'          => __( 'Background', 'wds-notrans' ),
						'bg_menu'     => __( 'Background Menu', 'wds-notrans' ),
						'icon_menu'   => __( 'Icon Menu', 'wds-notrans' ),
						'icon_active' => __( 'Icon Menu Active', 'wds-notrans' ),
					),
					'default'  => array(
						'base'        => wds_post_meta( $post_id, '_inv_base_color' ),
						'accent'      => wds_post_meta( $post_id, '_inv_accent_color' ),
						'button'      => wds_post_meta( $post_id, '_button_color' ),
						'bg'          => wds_post_meta( $post_id, '_inv_bg_color' ),
						'bg_menu'     => wds_post_meta( $post_id, '_menu_bg_color' ),
						'icon_menu'   => wds_post_meta( $post_id, '_menu_icon_color' ),
						'icon_active' => wds_post_meta( $post_id, '_menu_icon_active_color' ),
					),
				),

				array(
					'type'  => 'subheading',
					'title' => __( 'Frame', 'wds-notrans' ),
				),
				array(
					'id'       => '_frame_bg_cover',
					'type'     => 'upload',
					'library'  => 'image',
					'preview'  => true,
					'title'    => __( 'Background Image Cover', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _frame_bg_cover',
					'desc'     => __( 'Jika dikosongkan akan menggunakan background undangan.', 'weddingsaas' ),
				),
				array(
					'id'       => '_frame_bg_source',
					'type'     => 'select',
					'title'    => __( 'Background Source', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _frame_bg_source',
					'chosen'   => true,
					'options'  => array(
						'img'   => __( 'Image', 'wds-notrans' ),
						'color' => __( 'Color', 'wds-notrans' ),
					),
					'default'  => wds_post_meta( $post_id, '_source_bg' ),
				),
				array(
					'id'         => '_frame_bg_img',
					'type'       => 'upload',
					'library'    => 'image',
					'preview'    => true,
					'title'      => __( 'Background Image', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_bg_img',
					'default'    => wds_post_meta( $post_id, '_frame_bg' ),
					'dependency' => array( '_frame_bg_source', '==', 'img' ),
				),
				array(
					'id'         => '_frame_bg_color',
					'type'       => 'color',
					'title'      => __( 'Background Color', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_bg_color',
					'default'    => wds_post_meta( $post_id, '_bg_color' ),
					'dependency' => array( '_frame_bg_source', '==', 'color' ),
				),
				array(
					'id'       => '_frame_template',
					'type'     => 'select',
					'title'    => __( 'Frame Template', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _frame_template',
					'chosen'   => true,
					'options'  => array(
						'2' => __( '2 Frame (Template Generator 2 Frame)', 'wds-notrans' ),
						'4' => __( '4 Frame (Template Generator 4 Frame)', 'wds-notrans' ),
					),
					'default'  => wds_post_meta( $post_id, '_frame_generator' ),
				),
				array(
					'id'         => '_frame_top',
					'type'       => 'upload',
					'library'    => 'image',
					'preview'    => true,
					'title'      => __( 'Frame Top', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_top',
					'dependency' => array( '_frame_template', '==', '2' ),
				),
				array(
					'id'         => '_frame_bottom',
					'type'       => 'upload',
					'library'    => 'image',
					'preview'    => true,
					'title'      => __( 'Frame Bottom', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_bottom',
					'dependency' => array( '_frame_template', '==', '2' ),
				),
				array(
					'id'         => '_frame_top_left',
					'type'       => 'upload',
					'library'    => 'image',
					'preview'    => true,
					'title'      => __( 'Frame Top Left', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_top_left',
					'dependency' => array( '_frame_template', '==', '4' ),
				),
				array(
					'id'         => '_frame_top_right',
					'type'       => 'upload',
					'library'    => 'image',
					'preview'    => true,
					'title'      => __( 'Frame Top Right', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_top_right',
					'dependency' => array( '_frame_template', '==', '4' ),
				),
				array(
					'id'         => '_frame_bottom_left',
					'type'       => 'upload',
					'library'    => 'image',
					'preview'    => true,
					'title'      => __( 'Frame Bottom Left', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_bottom_left',
					'dependency' => array( '_frame_template', '==', '4' ),
				),
				array(
					'id'         => '_frame_bottom_right',
					'type'       => 'upload',
					'library'    => 'image',
					'preview'    => true,
					'title'      => __( 'Frame Bottom Right', 'wds-notrans' ),
					'subtitle'   => __( 'Name', 'wds-notrans' ) . ': _frame_bottom_right',
					'dependency' => array( '_frame_template', '==', '4' ),
				),

				array(
					'type'  => 'subheading',
					'title' => __( 'Default Image', 'wds-notrans' ),
				),
				array(
					'id'       => '_cover_img',
					'type'     => 'upload',
					'library'  => 'image',
					'preview'  => true,
					'title'    => __( 'Cover Image', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _cover_img',
					'desc'     => __( 'Gunakan untuk mengganti foto cover depan.', 'weddingsaas' ),
				),
				array(
					'id'       => '_profile_img',
					'type'     => 'upload',
					'library'  => 'image',
					'preview'  => true,
					'title'    => __( 'Profile Image', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _profile_img',
					'desc'     => __( 'Gunakan untuk mengganti foto khusus template non wedding.', 'weddingsaas' ),
				),
				array(
					'id'       => '_bride_img',
					'type'     => 'upload',
					'library'  => 'image',
					'preview'  => true,
					'title'    => __( 'Bride Image', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _bride_img',
					'desc'     => __( 'Gunakan untuk mengganti foto mempelai wanita khusus template wedding.', 'weddingsaas' ),
				),
				array(
					'id'       => '_groom_img',
					'type'     => 'upload',
					'library'  => 'image',
					'preview'  => true,
					'title'    => __( 'Groom Image', 'wds-notrans' ),
					'subtitle' => __( 'Name', 'wds-notrans' ) . ': _groom_img',
					'desc'     => __( 'Gunakan untuk mengganti foto mempelai pria khusus template wedding.', 'weddingsaas' ),
				),
			),
		)
	);
}


$header_script = '';
$footer_script = '';

$getpost = get_post( $post_id );
if ( $getpost ) {
	$post_type = $getpost->post_type;
	if ( 'post' == $post_type ) {
		$header_script = wds_post_meta( $post_id, '_header_scripts_post' );
		$footer_script = wds_post_meta( $post_id, '_footer_scripts_post' );
	} elseif ( 'page' == $post_type ) {
		$header_script = wds_post_meta( $post_id, '_header_scripts_page' );
		$footer_script = wds_post_meta( $post_id, '_footer_scripts_page' );
	} elseif ( 'wds_blog' == $post_type ) {
		$header_script = wds_post_meta( $post_id, '_header_scripts_blog' );
		$footer_script = wds_post_meta( $post_id, '_footer_scripts_blog' );
	} elseif ( 'wds_template' == $post_type ) {
		$header_script = wds_post_meta( $post_id, '_header_scripts_template' );
		$footer_script = wds_post_meta( $post_id, '_footer_scripts_template' );
	}
}

$global = WDS_SLUG . '_global';

CSF::createMetabox(
	$global,
	array(
		'title'     => __( 'Custome Scripts', 'wds-notrans' ) . ' (WDS)',
		'post_type' => array( 'post', 'page', 'wds_template', 'wds_blog' ),
		'data_type' => 'unserialize',
		'nav'       => 'inline',
		'theme'     => 'light',
	)
);

CSF::createSection(
	$global,
	array(
		'fields' => array(
			array(
				'id'       => '_header_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Header Scripts', 'wds-notrans' ),
				'subtitle' => __( 'Name', 'wds-notrans' ) . ': _header_scripts',
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
				'default'  => $header_script,
			),
			array(
				'id'       => '_footer_scripts',
				'type'     => 'code_editor',
				'title'    => __( 'Footer Scripts', 'wds-notrans' ),
				'subtitle' => __( 'Name', 'wds-notrans' ) . ': _footer_scripts',
				'sanitize' => false,
				'settings' => array(
					'theme' => 'ambiance',
					'mode'  => 'htmlmixed',
				),
				'default'  => $footer_script,
			),
		),
	)
);
