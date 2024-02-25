<?php

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
function replace_default_gutenberg_colors() {
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'editor-gradient-presets', [] );

	// Custom theme colors
	add_theme_support( 'editor-color-palette', get_theme_colors() );
}

add_action( 'after_setup_theme', 'replace_default_gutenberg_colors', 10 );

//======================================================================
// GUTENBERG BLOCKS
//======================================================================
function gb_blocks_scripts_and_styles() {
	wp_enqueue_style( 'fw-gutenberg-editor-style', get_stylesheet_directory_uri() . '/assets/css/admin.css', [], '1.0.0' );
}

add_action( 'enqueue_block_editor_assets', 'gb_blocks_scripts_and_styles' );

/**
 * ACF Gutenberg blocks
 *
 * @see https://developer.wordpress.org/resource/dashicons - Icons list
 */

function starter_register_acf_blocks() {

	$blocks = [
		'acf-advanced',
		'acf-sample',
		'hero-slider',
	];

	foreach ( $blocks as $block ) {
		register_block_type( get_stylesheet_directory() . '/parts/blocks/' . $block );
	}

}

add_action( 'init', 'starter_register_acf_blocks' );