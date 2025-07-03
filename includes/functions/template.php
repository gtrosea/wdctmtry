<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve the template file path based on various directories.
 *
 * @param string $file The file name of the template.
 * @return string|false The path of the template file or false if the file does not exist.
 */
function wds_get_template( $file ) {
	if ( empty( $file ) ) {
		return false;
	}

	$custom_path = apply_filters( 'wds_template_directory', false );
	$plugin_path = WDS_TEMPLATES;

	$theme_path  = get_template_directory() . '/weddingsaas/';
	$theme_style = get_stylesheet_directory() . '/weddingsaas/';

	$template = $plugin_path . $file;

	if ( file_exists( $theme_path . $file ) ) {
		$template = $theme_path . $file;
	}

	if ( file_exists( $theme_style . $file ) ) {
		$template = $theme_style . $file;
	}

	if ( file_exists( $custom_path . $file ) ) {
		$template = $custom_path . $file;
	}

	$template = apply_filters( 'wds_template_file', $template, $file );

	if ( ! file_exists( $template ) ) {
		return false;
	}

	return $template;
}

/**
 * Load a specific layout file and pass a template.
 *
 * @param string $target   The target layout file.
 * @param string $template The template data to pass to the layout.
 */
function wds_load_layout( $target, $template ) {
	global $wds_data;

	$layout = wds_get_template( 'layouts/' . $target . '.php' );

	if ( 'pay' == $target ) {
		$layout = wds_get_template( 'general/pay.php' );
	} elseif ( 'theme' == $target ) {
		$layout = wds_get_template( 'general/theme.php' );
	}

	if ( ! $layout ) {
		wp_die( esc_html__( 'Template tidak ditemukan.', 'weddingsaas' ) );
	}

	$wds_data['template'] = $template;

	load_template( $layout );
}

/**
 * Load and display a section of the template.
 *
 * @param string $file The file name of the section.
 * @return bool False if the template does not exist, true otherwise.
 */
function wds_template_section( $file ) {
	$template = wds_get_template( $file );

	if ( ! $template ) {
		return false;
	}

	require $template;
}

/**
 * Load and display the header template part.
 *
 * @return bool False if the template does not exist, true otherwise.
 */
function wds_header() {
	$template = wds_get_template( 'components/header.php' );

	if ( ! $template ) {
		return false;
	}

	load_template( $template, true );
}

/**
 * Load and display the topbar template part.
 *
 * @return bool False if the template does not exist, true otherwise.
 */
function wds_topbar() {
	$template = wds_get_template( 'components/topbar.php' );

	if ( ! $template ) {
		return false;
	}

	load_template( $template );
}

/**
 * Load and display the sidebar template part.
 *
 * @return bool False if the template does not exist, true otherwise.
 */
function wds_sidebar() {
	$template = wds_get_template( 'components/sidebar.php' );

	if ( ! $template ) {
		return false;
	}

	require $template;
}

/**
 * Load and display the dark mode template part.
 *
 * @return bool False if the template does not exist, true otherwise.
 */
function wds_dark_mode() {
	$template = wds_get_template( 'components/mode.php' );

	if ( ! $template ) {
		return false;
	}

	load_template( $template, true );
}

/**
 * Load and display the copyright template part.
 *
 * @return bool False if the template does not exist, true otherwise.
 */
function wds_copyright() {
	$template = wds_get_template( 'components/copyright.php' );

	if ( ! $template ) {
		return false;
	}

	load_template( $template, true );
}

/**
 * Load and display the footer template part.
 *
 * @return bool False if the template does not exist, true otherwise.
 */
function wds_footer() {
	$template = wds_get_template( 'components/footer.php' );

	if ( ! $template ) {
		return false;
	}

	load_template( $template, true );
}
