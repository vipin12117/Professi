<?php
/**
 * Plugin Name: Easy Digital Downloads - Currency Converter
 * Plugin URI: https://easydigitaldownloads.com/extensions/currency-converter/
 * Description: Allows your customers to convert product prices to a currency of their choice easily.
 * Version: 1.0.9
 * Author: WPWeb
 * Author URI: http://wpweb.co.in
 **/

/**
 * Basic plugin definitions 
 * 
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $wpdb;

if( !defined( 'EDD_CURRENCY_URL' ) ) {
	define( 'EDD_CURRENCY_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'EDD_CURRENCY_DIR' ) ) {
	define( 'EDD_CURRENCY_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'EDD_CURRENCY_IMG_URL' ) ) {
	define( 'EDD_CURRENCY_IMG_URL', EDD_CURRENCY_URL . 'includes/images' ); // plugin url
}
if( !defined( 'EDD_CURRENCY_ADMIN' ) ) {
	define( 'EDD_CURRENCY_ADMIN', EDD_CURRENCY_DIR . '/includes/admin' ); // plugin admin dir
}
if(!defined( 'EDD_CURRENCY_POST_TYPE' ) ) {
	define( 'EDD_CURRENCY_POST_TYPE', 'eddcurrency'); //post type for currency log
}
if(!defined( 'EDD_CURRENCY_BASENAME' ) ) {
	define( 'EDD_CURRENCY_BASENAME', 'edd-currency-converter' ); //currency and rewards basename
}
if(!defined( 'EDD_CURRENCY_PER_PAGE' ) ) {
	define( 'EDD_CURRENCY_PER_PAGE', '20' ); //currency list per page
}

//check class Easy_Digital_Downloads is exist
if( class_exists( 'Easy_Digital_Downloads' ) ) {
	
	// loads the Misc Functions file
	require_once( EDD_CURRENCY_DIR . '/includes/edd-currency-misc-functions.php' );
	
	//includes post types file
	require_once( EDD_CURRENCY_DIR . '/includes/edd-currency-post-types.php' );

	/**
	 * Activation Hook
	 *
	 * Register plugin activation hook.
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	register_activation_hook( __FILE__, 'edd_currency_install' );
	
	/**
	 * Plugin Setup (On Activation)
	 *
	 * Does the initial setup,
	 * stest default values for the plugin options.
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_install() {
		
		global $wpdb,$user_ID, $edd_options;
		
		//register post type
		edd_currency_reg_create_post_type();
		
		//IMP Call of Function
		//Need to call when custom post type is being used in plugin
		flush_rewrite_rules();
		
		//get option for when plugin is activating first time
		$edd_currency_set_option = get_option( 'edd_currency_set_option' );
		
		//check plugin version option
		if( empty( $edd_currency_set_option ) ) {
			
			$edd_page = array(
								'post_type' 	=> 'page',
								'post_status' 	=> 'publish',
								'post_title' 	=> __( 'Select Currency', 'eddcurrency' ),
								'post_content' 	=> '[edd_select_currency][/edd_select_currency]',
								'post_author' 	=> 1,
								'menu_order' 	=> 0,
								'comment_status' => 'closed'
							);
							
			//create main page for plugin					
			$edd_currency_page_id = wp_insert_post( $edd_page );
			
			//update currency page option
			update_option( 'edd_currency_select_currency_page', $edd_currency_page_id );
	
			$edd_currencies = edd_get_currencies();
			$edd_currency_order_count = 0;
			$edd_currency_orders = array();
			
			foreach( $edd_currencies as $currency_code => $currency_value ) {
			
				$edd_currency_order_count++;
				
				$currency_arr = array(
										  'post_title'    =>	$currency_code,
										  'post_content'  =>	$currency_value,
										  'post_status'   =>	'publish',
										  'post_author'   =>	1,
										  'menu_order'	  =>	$edd_currency_order_count,
										  'post_type'     =>	EDD_CURRENCY_POST_TYPE,
										);
				//create currency					
				$edd_currency_id = wp_insert_post( $currency_arr );
				
				if( !empty( $edd_currency_id ) ) { //check inserted currency id
					
					$edd_currency_symbol = edd_currency_get_symbol( $currency_code );
					update_post_meta( $edd_currency_id, '_edd_currency_symbol', $edd_currency_symbol );
					
					// store cuurency id and order no
					$edd_currency_orders[$edd_currency_id] = $edd_currency_order_count;
				}
			}
			
			//update sort order
			update_option( 'edd_currency_sort_order', $edd_currency_orders );
			
			//update sort order count
			update_option( 'edd_currency_sort_order_count', $edd_currency_order_count );
			
			//update plugin version to option 
			update_option( 'edd_currency_set_option', '1.0' );
			
		} //end if to check set option is empty or not
	
		//check currency set option is equal to 1.0 or not
		if( $edd_currency_set_option == '1.0' ) {
			//future code will here
		} //end if
		
		/*************** Default Options Saving to Options of EDD Start ***************/
		//create currency page option default
		$currency_page = get_option( 'edd_currency_select_currency_page' );
		
		$udpopt = false;
		
		//check exchange rates method is not set
		if( !isset( $edd_options['exchange_rates_method'] ) ) {
			$edd_options['exchange_rates_method'] = 'open_exchange';
			$udpopt = true;
		}//end if
		
		//check select currency page is not set
		if( !isset( $edd_options['select_currency'] ) ) {
			$edd_options['select_currency'] = $currency_page;
			$udpopt = true;
		}//end if
		
		//check append code enabled is not set
		if( !isset( $edd_options['append_code'] ) ) {
			$edd_options['append_code'] = '';
			$udpopt = true;
		}//end if
		
		//check append code enabled is not set
		if( !isset( $edd_options['curr_code_position'] ) ) {
			$edd_options['curr_code_position'] = 'after';
			$udpopt = true;
		}//end if
		
		//check replace base currency is not set
		if( !isset( $edd_options['replace_base_currency'] ) ) {
			$edd_options['replace_base_currency'] = '';
			$udpopt = true;
		}//end if
		
		//check display cart notification is not set 
		if( !isset( $edd_options['display_cart_notification'] ) ) {
			$edd_options['display_cart_notification'] = 'yes';
			$udpopt = true;
		}//end if
		
		//check base currency is not set
		if( !isset( $edd_options['curr_base_currency'] ) ) {
			$edd_options['curr_base_currency'] = edd_get_currency();
			$udpopt = true;
		}//end if
		
		//check exchange app id is not set
		if( !isset( $edd_options['exchange_app_id'] ) ) {
			$edd_options['exchange_app_id'] = '';
			$udpopt = true;
		}//end if
		
		//check exchange rates cache is not set
		if( !isset( $edd_options['exchange_rates_cache'] ) ) {
			$edd_options['exchange_rates_cache'] = '60';
			$udpopt = true;
		}//end if
		
		//check currency detection is not set
		if( !isset( $edd_options['currency_detection'] ) ) {
			$edd_options['currency_detection'] = 'no';
			$udpopt = true;
		}//end if
		
		//check prompt user detection is not set
		if( !isset( $edd_options['prompt_user_detection'] ) ) {
			$edd_options['prompt_user_detection'] = 'no';
			$udpopt = true;
		}//end if
		
		//check need to update the defaults value to options
		if( $udpopt == true ) { // if any of the settings need to be updated
			update_option( 'edd_settings', $edd_options );
		}
		
		/*************** Default Options Saving to Options of EDD End ***************/
	}
	
} //end if to check class Easy_Digital_Downloads exist

//add action to load plugin
add_action( 'plugins_loaded', 'edd_currency_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded 
 * successfully
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 **/
function edd_currency_plugin_loaded() {

	//check easy digital downloads is activated or not
	if( class_exists( 'Easy_Digital_Downloads' ) ) {
	
		//check EDD_License class is exist
		if( class_exists( 'EDD_License' ) ) {
			
			// Instantiate the licensing / updater. Must be placed in the main plugin file
			$license = new EDD_License( __FILE__, 'Currency Converter', '1.0.9', 'WPWeb' );
		}
		/**
		 * Load Text Domain
		 *
		 * This gets the plugin ready for translation.
		 *
		 * @package Easy Digital Downloads - Currency Converter
		 * @since 1.0.0
		 */
		function edd_currency_load_textdomain() {
		
		  load_plugin_textdomain( 'eddcurrency', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		}
		//add action to load plugin textdomain
		add_action( 'init', 'edd_currency_load_textdomain' );
		
		/**
		 * Deactivation Hook
		 *
		 * Register plugin deactivation hook.
		 *
		 * @package Easy Digital Downloads - Currency Converter
		 * @since 1.0.0
		 **/
		register_deactivation_hook( __FILE__, 'edd_currency_uninstall');
		
		/**
		 * Plugin Setup (On Deactivation)
		 *
		 * Handles to call on deactivation of plugin
		 *
		 * @package Easy Digital Downloads - Currency Converter
		 * @since 1.0.0
		 **/
		function edd_currency_uninstall() {
			
			global $wpdb;
			
			//IMP Call of Function
			//Need to call when custom post type is being used in plugin
			flush_rewrite_rules();
			
		}
		/**
		 * Includes Files
		 * 
		 * Includes some required files for plugin
		 *
		 * @package Easy Digital Downloads - Currency Converter
		 * @since 1.0.0
		 * 
		 **/
		
		global $edd_currency_model, $edd_currency_scripts,
			$edd_currency_render, $edd_currency_shortcodes,
			$edd_currency_public, $edd_currency_admin,
			$edd_options;
	
		//Model Class for generic functions
		require_once( EDD_CURRENCY_DIR . '/includes/class-edd-currency-model.php' );
		$edd_currency_model = new EDD_Currency_Model();
		
		//Scripts Class for scripts / styles
		require_once( EDD_CURRENCY_DIR . '/includes/class-edd-currency-scripts.php' );
		$edd_currency_scripts = new EDD_Currency_Scripts();
		$edd_currency_scripts->add_hooks();
		
		//Shortcodes class for handling shortcodes
		require_once( EDD_CURRENCY_DIR . '/includes/class-edd-currency-shortcodes.php' );
		$edd_currency_shortcodes = new EDD_Currency_Shortcodes();
		$edd_currency_shortcodes->add_hooks();
		
		//Renderer Class for HTML
		require_once( EDD_CURRENCY_DIR . '/includes/class-edd-currency-renderer.php' );
		$edd_currency_render = new EDD_Currency_Renderer();
	
		//Public Class for public functionlities
		require_once( EDD_CURRENCY_DIR . '/includes/class-edd-currency-public.php' );
		$edd_currency_public = new EDD_Currency_Public();
		$edd_currency_public->add_hooks();
	
		//Admin Pages Class for admin site
		require_once( EDD_CURRENCY_ADMIN . '/class-edd-currency-admin.php' );
		$edd_currency_admin = new EDD_Currency_Admin();
		$edd_currency_admin->add_hooks();
	
	}//end if to check class Easy_Digital_Downloads is exist or not
	
} //end if to check plugin loaded is called or not
?>