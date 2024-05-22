<?php

if ( current_theme_supports( 'acf_gutenberg' ) ) {

	//======================================================================
	// GUTENBERG SETUP
	//======================================================================

	// Enable support to full width block 
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'appearance-tools' );

	/**
	 * Replace default gutenberg color palette with theme colors
	 */
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'editor-gradient-presets', [] );

	// Custom theme colors
	add_theme_support( 'editor-color-palette', get_theme_colors() );

	/**
	 * Create custom category for custom Gutenberg blocks
	 *
	 * @param array $categories
	 *
	 * @return array
	 */
	function starter_custom_block_category( $categories ) {
		$custom_blocks = array(
			'slug'  => 'custom-blocks',
			'title' => __( 'Custom Blocks', 'starter' ),
		);

		array_unshift( $categories, $custom_blocks );

		return $categories;
	}

	add_filter( 'block_categories', 'starter_custom_block_category' );

	//======================================================================
	// GUTENBERG BLOCKS
	//======================================================================

	function starter_gb_blocks_scripts_and_styles() {
		if ( is_admin() ) {
			$version = filemtime( get_stylesheet_directory() . '/assets/css/admin.css' );
			wp_enqueue_style( 'starter-gutenberg-editor-style', get_stylesheet_directory_uri() . '/assets/css/admin.css', [], $version );
		}
	}

	add_action( 'enqueue_block_assets', 'starter_gb_blocks_scripts_and_styles' );

	/**
	 * ACF Gutenberg blocks
	 * Block will appear in editor only if ACF Plugin is activated
	 *
	 * Register ALL blocks within parts/blocks/ folder
	 */

	$element_dirs = glob( get_stylesheet_directory() . '/parts/blocks/*', GLOB_ONLYDIR );
	array_map( 'register_block_type', $element_dirs );

} else {

	/**
	 * Add gform button to editor
	 *
	 * @param bool Is Add Form button displayed
	 *
	 * @return bool
	 */
	add_filter( 'gform_display_add_form_button', '__return_true' );

	//======================================================================
	// DISABLE GUTENBERG SUPPORT
	//======================================================================

	// Disable Gutenberg Editor
	add_filter( 'use_block_editor_for_post_type', '__return_false' );
	// Disables the block editor from managing widgets in the Gutenberg plugin.
	add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
	// Disables the block editor from managing widgets.
	add_filter( 'use_widgets_block_editor', '__return_false' );

	/**
	 * Disable Gutenberg built-in styles
	 */
	function starter_disable_gutenberg_styles() {
		wp_dequeue_style( 'wp-block-library' );

		wp_dequeue_style( 'wc-blocks-vendors-style' );
		wp_dequeue_style( 'wc-blocks-style' );
	}

	add_action( 'wp_enqueue_scripts', 'starter_disable_gutenberg_styles' );

	remove_action( 'wp_enqueue_scripts', array( 'WP_Duotone', 'output_block_styles' ), 9 );
	remove_action( 'wp_enqueue_scripts', array( 'WP_Duotone', 'output_global_styles' ), 11 );
}

/**
 * Disable Flexible content by default.
 *
 * @param array $fields
 *
 * @return array
 */
function starter_remove_flexible_content_field( $fields ) {
	if ( ! empty( $fields['Layout']['flexible_content'] ) ) {
		unset( $fields['Layout']['flexible_content'] );
	}

	return $fields;
}

if ( ! current_theme_supports( 'acf_flexible' ) ) {
	add_filter( 'acf/get_field_types', 'starter_remove_flexible_content_field' );
}