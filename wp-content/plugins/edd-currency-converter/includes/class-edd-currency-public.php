<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Pages Class
 *
 * Handles all the different features and functions
 * for the front end pages.
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */
class EDD_Currency_Public	{
	
	/**
	 * EDD Currency Converter render object
	 * 
	 * @var object
	 * @since 1.0.0
	 **/
	public $render;
	
	/**
	 * EDD Currency Converter model object
	 * 
	 * @var object
	 * @since 1.0.0
	 **/
	public $model;
	
	public function __construct() {
		
		global $edd_currency_model, $edd_currency_render, $edd_options;
		
		$this->render 	= $edd_currency_render;
		$this->model 	= $edd_currency_model;
	}
	
	/**
	 * Pop Up On Editor
	 *
	 * Includes the pop up on the user listing page
	 *
	 * @package Easy Digital Downloads - Currency converter
	 * @since 1.0.0
	 **/
	public function edd_currency_list_popup() {
		
		include_once( EDD_CURRENCY_DIR . '/includes/edd-currency-list-popup.php' );
	}

	/**
	 * Select Currency Nav Menu Class
	 *
	 * Handles to add custom class in to select currency
	 * menu at front side
	 *
	 * @package Easy Digital Downloads - Currency converter
	 * @since 1.0.0
	 */
	public function edd_currency_select_currency_nav_menu_class( $classes, $page_id ) {
		
		global $edd_options;
		if( isset( $edd_options['select_currency'] ) && $page_id == $edd_options['select_currency'] ) {
			$classes[] = 'edd-select-currency-menu-item';
		}
		return $classes;
	}
	/**
	 * Select Currency Nav Menu Class
	 *
	 * Handles to add custom css class in to select currency
	 * page menu at front side
	 *
	 * @package Easy Digital Downloads - Currency converter
	 * @since 1.0.0
	 **/
	public function edd_currency_nav_menu_css_class( $classes, $item ) {
		
		if( $item->object == 'page' ) {
			$classes = $this->edd_currency_select_currency_nav_menu_class( $classes, $item->object_id );
		}
		return $classes;
	}
	/**
	 * Select Currency Page Class
	 *
	 * Handles to add custom css class in to select currency
	 * page css class
	 *
	 * @package Easy Digital Downloads - Currency converter
	 * @since 1.0.0
	 **/
	public function edd_currency_page_css_class( $css_class, $page ) {
		
		return $this->edd_currency_select_currency_nav_menu_class( $css_class, $page->ID );
	}

	/**
	 * Append Currency Code
	 * 
	 * Handles to append currency code
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_append_code( $symbol, $price, $currency, $formatted ) {
		
		global $edd_options;
		
		$newpriceformat = $formatted; //$symbol.$price;
		
		// If replace currency code
		if( isset($edd_options['replace_currency_code']) && $edd_options['replace_currency_code'] == '1' ) {
			
			// Getting main plugin currency position
			if( isset($edd_options['currency_position']) && $edd_options['currency_position'] == 'before' ) {
				$newpriceformat = $currency.' '.$price;
			} else {
				$newpriceformat = $price.' '.$currency;
			}
			
		} elseif( isset( $edd_options['append_code'] ) && $edd_options['append_code'] == '1' ) { //Check Append Currency Code settings is checked
			
			//check append currency code is before
			if( isset( $edd_options['curr_code_position'] ) && $edd_options['curr_code_position'] == 'before' ) {
				$newpriceformat = $currency . ' ' . $newpriceformat;
			} else {
			 	$newpriceformat = $newpriceformat . ' ' . $currency;
			}
			
		} //end if to check append code is enabled or not
		
		return $newpriceformat;
	}
	
	/**
	 * Change Currency Before
	 * 
	 * Handles to change currency before
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_change_before_format( $formatted, $currency, $price ) {
		
		global $edd_options;
		
		$stored_currency = edd_currency_get_stored_currency(); // get cookie currency
		$exchange_rates  = edd_currency_get_exchange_rates();
		
		$cur_symbol 	=  edd_currency_get_symbol( $currency ) ;
		$new_formatted 	= $this->edd_currency_append_code( $cur_symbol, $price, $currency, $formatted );
		
		if( !empty( $price ) && $stored_currency != $currency && $this->model->edd_currency_check_open_exchange() && isset( $exchange_rates[$stored_currency] ) ) {
			
			// check if cookie curreny is not the same as base currency and open change rate is setup properly and custom rate is set for stored currency
			$new_symbol 		= edd_currency_get_symbol( $stored_currency );
			$new_price 			= edd_currency_get_converted_price( $price );
			$new_price_formt	= $new_symbol.$new_price;
			$new_changed_format	= $this->edd_currency_append_code( $new_symbol, $new_price, $stored_currency, $new_price_formt );
			
			if( isset( $edd_options['replace_base_currency'] ) && $edd_options['replace_base_currency'] == '1' ) {
				
				$new_formatted = $new_changed_format;
				
			} else {
				
				$new_formatted 		.= ' ( ' . $new_changed_format . ' ) ' ;
				
			}
		}
		
		return $new_formatted;
	}
	
	/**
	 * Change Currency After
	 * 
	 * Handles to change currency After
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_change_after_format( $formatted, $currency, $price ) {
		
		global $edd_options;
		
		$stored_currency	= edd_currency_get_stored_currency();
		$exchange_rates  	= edd_currency_get_exchange_rates();
		$cur_symbol 		= edd_currency_get_symbol( $currency ) ;
		$new_formatted 		= $this->edd_currency_append_code( $cur_symbol, $price, $currency, $formatted );
		
		//Check Our Cookie is set
		if( !empty( $price ) && $stored_currency != $currency && $this->model->edd_currency_check_open_exchange() && isset( $exchange_rates[$stored_currency] ) ) {
			
			$new_symbol 		= edd_currency_get_symbol( $stored_currency );
			$new_price 			= edd_currency_get_converted_price( $price );
			
			// only 1 line change , we can make this functions ( edd_currency_change_after_format and edd_currency_change_before_format) common in future
			$new_price_formt	= $new_price.$new_symbol;
			$new_changed_format	= $this->edd_currency_append_code( $new_symbol, $new_price, $stored_currency, $new_price_formt );
			
			//Check Replace Base Currency settings is checked
			if( isset( $edd_options['replace_base_currency'] ) && $edd_options['replace_base_currency'] == '1' ) {
				
				$new_formatted = $new_changed_format;
				
			} else {
				
				$new_formatted 	.= ' ( ' . $new_changed_format . ' ) ';
			}
		}
		
		return $new_formatted;
	}
	/**
	 * Adding Hooks
	 *
	 * Adding proper hoocks for the public pages.
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		global $edd_options;
		
		//check base currency has rate & is not admin side and calling ajax
		if( edd_currency_check_base_currency_has_rate() && !is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
		
			$currency_data = $this->model->edd_currency_get_data();
			if( !empty( $currency_data ) ) { // Check currencies data are not empty
			
				// mark up for popup
				add_action( 'wp_footer', array( $this,'edd_currency_list_popup' ) );
				
				// Add filter to add class in nav menu
				add_filter( 'nav_menu_css_class',	array( $this,'edd_currency_nav_menu_css_class'), 10, 2 );
				add_filter( 'page_css_class', 		array( $this,'edd_currency_page_css_class'), 10, 2 );
			}
			
			$currency = edd_get_currency(); // get edd base currency
			
			$stored_currency = edd_currency_get_stored_currency(); // get cuurency which is set in the cookie
			$exchange_rates  = edd_currency_get_exchange_rates(); // get exchance rates array 
			
			add_filter( 'edd_' . strtolower( $currency ) . '_currency_filter_before', array( $this , 'edd_currency_change_before_format' ), 10, 3 );
			add_filter( 'edd_' . strtolower( $currency ) . '_currency_filter_after', array( $this , 'edd_currency_change_after_format' ), 10, 3 );
		
			if( isset( $edd_options['display_cart_notification'] ) && $edd_options['display_cart_notification'] == '1'
				&& $stored_currency != $currency && $this->model->edd_currency_check_open_exchange() && isset( $exchange_rates[$stored_currency] ) ) {
			
				// Add message for checkout
				add_action( 'edd_before_purchase_form', array( $this->render,'edd_currency_checkout_message_content' ) );
				
			}
		}
		
		//add action to show currency popup markup
		add_action( 'wp_footer', array( $this->render, 'edd_currency_saved_popup' ) );
		
		//add action to show detection prompt user message
		add_action( 'wp_footer', array( $this->render, 'edd_currency_detection_popup' ) );

	}
	
}
?>