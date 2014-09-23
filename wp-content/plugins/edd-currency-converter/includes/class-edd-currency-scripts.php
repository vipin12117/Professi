<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */
class EDD_Currency_Scripts{

	public function __construct() {

	}
	
	/**
	 * Enqueue Admin Styles
	 * 
	 * Handles to enqueue styles for admin side
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_admin_styles( $hook_suffix ) {
		
		$pages_hook_suffix = array( 'download_page_edd_currency_converter', 'download_page_edd_currency_manage' );
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
		
			wp_register_style( 'edd-currency-admin-styles', EDD_CURRENCY_URL . 'includes/css/edd-currency-admin.css', array(), null);
			wp_enqueue_style( 'edd-currency-admin-styles' );
		}
	}
	
	/**
	 * Enqueue Admin Scripts
	 * 
	 * Handles to enqueue scripts for admin
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_admin_scripts( $hook_suffix ) {
		
		$pages_hook_suffix = array( 'download_page_edd_currency_converter', 'download_page_edd_currency_manage', 'download_page_edd-settings' );
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
		
			wp_register_script( 'edd-currency-admin-scripts', EDD_CURRENCY_URL . 'includes/js/edd-currency-admin.js', array('jquery', 'jquery-ui-sortable' ) , null, true );
			wp_enqueue_script( 'edd-currency-admin-scripts' );
			wp_localize_script( 'edd-currency-admin-scripts', 'Edd_Currency_Admin', array(	'delete_msg' 	=> __( 'Are you sure want to delete?', 'eddcurrency' ),
																							'reset_order' 	=> __( 'Are you sure want to reset order?', 'eddcurrency' )
																						) );

				
			wp_register_script( 'edd-currency-sortable', EDD_CURRENCY_URL . 'includes/js/edd-currency-sortable.js', array( 'jquery', 'jquery-ui-sortable' ) , null, true );
			wp_enqueue_script( 'edd-currency-sortable' );
			
		}
	}
	
	/**
	 * Enqueue Public Scripts and Styles
	 * 
	 * Handles to enqueue scripts and styles for public side
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_public_scripts() {
		
		global $edd_options, $post;
		
		wp_register_script( 'edd-currency-public-script', EDD_CURRENCY_URL . 'includes/js/edd-currency-public.js', array( 'jquery' ), null );
		wp_localize_script( 'edd-currency-public-script', 'EDDCurrency', array(	'detected_currency'	=>	edd_currency_get_detected_currency() ) );
		
		wp_enqueue_script('edd-currency-public-script');
		
		wp_register_style( 'edd-currency-public-style', EDD_CURRENCY_URL . 'includes/css/edd-currency-public.css', array(), null );
		wp_enqueue_style( 'edd-currency-public-style' );
		
	}

	/**
	 * Loading Additional Java Script
	 *
	 * Loads the JavaScript required for toggling the meta boxes on the theme settings page
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	function edd_currency_add_currency_page_load_scripts() { 
		?>
			<script>
				//<![CDATA[
				jQuery(document).ready( function($) {
					$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
					postboxes.add_postbox_toggles( 'download_page_edd_currency_manage' );
				});
				//]]>
			</script>
		<?php
	}
	
	/**
	 * Load Some Javascript
	 * 
	 * Load JavaScript for handling functionalities for metaboxes
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	
	function edd_currency_page_print_scripts( $hook_suffix ) {
		
		if ( $hook_suffix == 'download_page_edd_currency_manage' ) {
	
			// loads the required scripts for the meta boxes
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
	
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hooks for the scripts.
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function add_hooks() {

		//add styles for back end
		add_action( 'admin_enqueue_scripts', array( $this, 'edd_currency_admin_styles') );
		
		//add script to back side for Corrency Converter
		add_action( 'admin_enqueue_scripts', array( $this, 'edd_currency_admin_scripts') );
		
		//add script for adding some required scripts for metaboxes
		add_action( 'admin_enqueue_scripts', array( $this, 'edd_currency_page_print_scripts' ) );
		
		//add script and styles to front side for Corrency Converter
		add_action( 'wp_enqueue_scripts', array( $this, 'edd_currency_public_scripts' ) );
		
	}
}
?>