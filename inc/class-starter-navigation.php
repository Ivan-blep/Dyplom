<?php

/**
 * Class Starter_Navigation
 */

class Starter_Navigation extends Walker_Nav_Menu {
	
	/**
	 * Adds custom class to dropdown menu for foundation dropdown script
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"menu submenu\">\n";
	}
	
	/**
	 * Adds custom class to parent item with dropdown menu
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		$id_field = $this->db_fields['id'];
		if ( ! empty( $children_elements[ $element->$id_field ] ) ) {
			$element->classes[] = 'has-dropdown';
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}


/**
 * Add additional class to dropdown submenu wrapper
 *
 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
 * @param WP_Post $item The current menu item.
 *
 * @return array
 */

function starter_add_custom_dropdown_class( $classes, $item ) {
	
	if ( $is_button = get_field( 'is_button', $item ) ) {
		$classes[] = 'has-button';
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'starter_add_custom_dropdown_class', 10, 2 );

/**
 * Add class to link
 *
 * @param array $atts Link attributes
 * @param WP_Post $item The current menu item.
 *
 * @return array
 */

function starter_add_class_to_link( $atts, $item ) {
	
	if ( $is_button = get_field( 'is_button', $item ) ) {
		$atts['class'] = 'button';
	}

	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'starter_add_class_to_link', 10, 2 );