<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//creating custom post type
add_action( 'init', 'edd_currency_reg_create_post_type'); //creating custom post

/**
 * Register Post Type
 *
 * Register Custom Post Type for managing registered taxonomy
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */
function edd_currency_reg_create_post_type() {
	
	$labels = array(
					    'name'				=> __('Currencies','eddcurrency'),
					    'singular_name' 	=> __('Currency','eddcurrency'),
					    'add_new' 			=> __('Add New','eddcurrency'),
					    'add_new_item' 		=> __('Add New Currency','eddcurrency'),
					    'edit_item' 		=> __('Edit Currency','eddcurrency'),
					    'new_item' 			=> __('New Currency','eddcurrency'),
					    'all_items' 		=> __('All Currencies','eddcurrency'),
					    'view_item' 		=> __('View Currency','eddcurrency'),
					    'search_items' 		=> __('Search Currency','eddcurrency'),
					    'not_found' 		=> __('No currencies found','eddcurrency'),
					    'not_found_in_trash'=> __('No currencies found in Trash','eddcurrency'),
					    'parent_item_colon' => '',
					    'menu_name' => __('Currencies','eddcurrency'),
					);
	$args = array(
				    'labels' => $labels,
				    'public' => false,
				    'publicly_queryable' => true,
				    'show_ui' => false, 
				    'show_in_menu' => false, 
				    'query_var' => true,
				    'rewrite' => array( 'slug' => EDD_CURRENCY_POST_TYPE ),
				    'capability_type' => 'post',
				    'has_archive' => true, 
				    'hierarchical' => false,
				    'menu_position' => null,
				    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
			  ); 
	
	register_post_type( EDD_CURRENCY_POST_TYPE,$args);
}
?>