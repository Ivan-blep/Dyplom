<?php
/**
 * TODO: Uncomment some action if you need it
 */

//======================================================================
// SHOP / ARCHIVE PAGE
//======================================================================

/**
 * Remove Result count
 */

//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Remove Sorting dropdown
 */

//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * Change Shop Loop product image size
 *
 * @param string $size Image size
 *
 * @return string
 */

function starter_change_shop_loop_item_img_size( $size ) {
	return 'medium_large';
}

add_filter( 'single_product_archive_thumbnail_size', 'starter_change_shop_loop_item_img_size' );

//======================================================================
// SINGLE PRODUCT PAGE
//======================================================================

/**
 * Replace excerpt with full content
 */

//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
//add_action( 'woocommerce_single_product_summary', 'single_product_content_replace', 20 );

function single_product_content_replace() {
	the_content();
}

/**
 * Remove info tabs under product info (Description / Reviews / ...)
 */

//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

/**
 * Add Quantity input control buttons
 */

function starter_add_decrease_qty_btn() {
	echo '<span class="s-qty-dec"></span>';
}

//add_action( 'woocommerce_before_quantity_input_field', 'starter_add_decrease_qty_btn' );

function starter_add_increase_qty_btn() {
	echo '<span class="s-qty-inc"></span>';
}

//add_action( 'woocommerce_after_quantity_input_field', 'starter_add_increase_qty_btn' );

//======================================================================
// CART PAGE
//======================================================================



//======================================================================
// CHECKOUT PAGE
//======================================================================



//======================================================================
// MY ACCOUNT PAGE
//======================================================================



//======================================================================
// DASHBOARD TWEAKS
//======================================================================