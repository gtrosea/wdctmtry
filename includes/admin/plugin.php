<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Action link for this plugin.
 *
 * @since 1.0.0
 * @param array $links The action link.
 * @return array merge to $links
 */
function wds_plugin_action_links( $links ) {
	if ( ! wds_is_active() ) {
		$url  = menu_page_url( 'weddingsaas-license', false );
		$link = array( '<a href="' . esc_url( $url ) . '" style="color:#dc392d;font-weight:600;">' . __( 'License', 'wds-notrans' ) . '</a>' );
	} else {
		$url  = menu_page_url( 'weddingsaas-settings', false );
		$link = array( '<a href="' . esc_url( $url ) . '" style="color:#dc392d;font-weight:600;">' . __( 'Settings', 'wds-notrans' ) . '</a>' );
	}

	$links = array_merge( $links, $link );

	return $links;
}
add_filter( 'plugin_action_links_' . WDS_BASE, 'wds_plugin_action_links', 10, 2 );

/**
 * Plugin row meta links.
 *
 * @since 1.0.0
 * @param array  $input The input link.
 * @param string $file The plugin file.
 * @return array merge to $input
 */
function wds_plugin_row_meta_link( $input, $file ) {
	if ( WDS_BASE != $file || ! wds_is_active() ) {
		return $input;
	}

	$link_support = esc_url(
		add_query_arg(
			array(
				'utm_source'   => 'plugins-page',
				'utm_medium'   => 'plugin-row',
				'utm_campaign' => 'admin',
			),
			WDS_STORE . 'support/'
		)
	);

	$link_docs = esc_url(
		add_query_arg(
			array(
				'utm_source'   => 'plugins-page',
				'utm_medium'   => 'plugin-row',
				'utm_campaign' => 'admin',
			),
			'https://docs.pelatform.com/collection/weddingsaas-MROupjQJn6'
		)
	);

	$links = array(
		'<a href="' . esc_url( $link_support ) . '" target="_blank" style="color:#dc392d;font-weight:600;">' . __( 'Support', 'wds-notrans' ) . '</a>',
		'<a href="' . esc_url( $link_docs ) . '" target="_blank" style="color:#2ddc67;font-weight:600;">' . __( 'Docs', 'wds-notrans' ) . '</a>',
	);

	$input = array_merge( $input, $links );

	return $input;
}
add_filter( 'plugin_row_meta', 'wds_plugin_row_meta_link', 10, 2 );

/**
 * Change default text admin footer left.
 *
 * @since 2.0.0
 * @param string $text The original footer text.
 * @return string The modified or original footer text.
 */
function wds_admin_text_left( $text ) {
	return '<span id="footer-thankyou">Thank you for using <a href="' . WDS_STORE . '" target="_blank" class="link---primary">WeddingSaas</a> plugin.</span>';
}

/**
 * Change default text admin footer right.
 *
 * @since 2.0.0
 */
function wds_admin_text_right() {
	return 'Version ' . get_bloginfo( 'version', 'display' ) . ' || <b>WDS Version ' . WDS_VERSION . '</b>';
}

$pages = new WDS\Admin\Pages();
if ( $pages->check( false ) ) {
	add_filter( 'admin_footer_text', 'wds_admin_text_left', 20 );
	add_filter( 'update_footer', 'wds_admin_text_right', 20 );
}

/**
 * Add recomendation plugin.
 *
 * @since 1.0.0
 */
function wds_tgmpa_plugins() {
	if ( ! wds_is_active() ) {
		return;
	}

	$plugins = array();

	$plugins[] = array(
		'name'     => 'Elementor Website Builder',
		'slug'     => 'elementor',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => '',
		'afflink'  => '',
	);

	$plugins[] = array(
		'name'     => 'JetFormBuilder',
		'slug'     => 'jetformbuilder',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => '',
		'afflink'  => '',
	);

	$plugins[] = array(
		'name'     => 'User Switching',
		'slug'     => 'user-switching',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => '',
		'afflink'  => '',
	);

	$plugins[] = array(
		'name'     => 'UpdraftPlus',
		'slug'     => 'updraftplus',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => '',
		'afflink'  => '',
	);

	$plugins[] = array(
		'name'     => 'FluentSMTP',
		'slug'     => 'fluent-smtp',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => '',
		'afflink'  => '',
	);

	$plugins[] = array(
		'name'     => 'Rank Math SEO',
		'slug'     => 'seo-by-rank-math',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => '',
		'afflink'  => '',
	);

	// $plugins[] = array(
	// 'name'     => 'JetEngine',
	// 'slug'     => 'jet-engine',
	// 'required' => false,
	// 'optional' => true,
	// 'version'  => '',
	// 'source'   => 'https://www.dropbox.com/scl/fi/9s4umug3ipuhw1z90khb6/jet-engine-3.5.3.zip?rlkey=hptu671zn37w1nvydl8fzf5xq&st=ix8f561t&dl=1',
	// 'afflink'  => '',
	// );

	// $plugins[] = array(
	// 'name'     => 'JetElements For Elementor',
	// 'slug'     => 'jet-elements',
	// 'required' => false,
	// 'optional' => true,
	// 'version'  => '',
	// 'source'   => 'https://www.dropbox.com/scl/fi/q96pvhj3ziv50omfxootq/jet-elements-2.6.16.zip?rlkey=q5xfbwv3g34u360vuebny7seq&dl=1',
	// 'afflink'  => '',
	// );

	// $plugins[] = array(
	// 'name'     => 'PowerPack Pro for Elementor',
	// 'slug'     => 'powerpack-elements',
	// 'required' => false,
	// 'optional' => true,
	// 'version'  => '',
	// 'source'   => 'https://www.dropbox.com/scl/fi/1oeqqg11gucmyp9f1c5h8/powerpack-elements-2.10.21.zip?rlkey=3ocnw4jwrnzdlb4p98e9a0oee&st=zlek0eh6&dl=1',
	// 'afflink'  => '',
	// );

	$plugins[] = array(
		'name'     => 'JetEngine Forms – Attach Media to Post',
		'slug'     => 'jet-forms-attach-media-to-post-main',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => 'https://github.com/Crocoblock/jet-forms-attach-media-to-post/archive/refs/heads/main.zip',
		'afflink'  => '',
	);

	$plugins[] = array(
		'name'     => 'WeddingPress',
		'slug'     => 'weddingpress',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => 'www',
		'afflink'  => 'https://weddingpress.net/aff/1963/133/?coupon=WDSPRO',
	);

	$plugins[] = array(
		'name'     => 'Templateku',
		'slug'     => 'templateku',
		'required' => false,
		'optional' => true,
		'version'  => '',
		'source'   => 'www',
		'afflink'  => 'https://my.templateku.id/ref/117/',
	);

	$plugins[] = array(
		'name'     => 'WeddingSaas - Meta Conversion API',
		'slug'     => 'weddingsaas-metacapi',
		'required' => false,
		'optional' => true,
		'version'  => '1.0.1',
		'source'   => 'https://assets.weddingsaas.id/plugin/weddingsaas-metacapi-v1.0.1.zip',
		'afflink'  => '',
	);

	$config = array(
		'id'           => 'weddingsaas-tgmpa',
		'default_path' => '',
		'menu'         => 'weddingsaas-install-plugins',
		'parent_slug'  => 'weddingsaas',
		'capability'   => 'manage_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => true,
		'message'      => '',
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'wds_tgmpa_plugins' );
