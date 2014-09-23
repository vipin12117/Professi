<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Uninstall
 *
 * Does delete the created tables and all the plugin options
 * when uninstalling the plugin
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */

// check if the plugin really gets uninstalled 
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

global $wpdb;

	//get currency page id
	$currency_page	= get_option( 'edd_currency_select_currency_page' );
	
	//check currency page is set or not
	if ( isset( $currency_page ) && !empty( $currency_page ) ) {
		//delete page for selete currency
		wp_delete_post( $currency_page, true );
	}
	
	//delete custom main post data
	$mainargs = array( 'post_type' => 'eddcurrency', 'numberposts' => '-1', 'post_status' => 'any' );
	$mainpostdata = get_posts( $mainargs );
	
	foreach ( $mainpostdata as $post ) {
		wp_delete_post( $post->ID,true );
	}
	
	//delete set option for edd currency conveter option
	delete_option( 'edd_currency_set_option' );
	//delete select currency page option
	delete_option( 'edd_currency_select_currency_page' );
	//delete sort order option
	delete_option( 'edd_currency_sort_order' );
	//delete sort order count option
	delete_option( 'edd_currency_sort_order_count' );
	//delete open exchanage rates
	delete_transient( 'edd_currency_open_exchange_rates' );
?>