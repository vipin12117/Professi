<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcodes Class
 *
 * Handles shortcodes functionality of plugin
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */
class EDD_Currency_Shortcodes {
	
	/**
	 * EDD Currency Converter model object
	 * 
	 * @var object
	 * @since 1.0.0
	 **/
	public $model;
	
	public function __construct(){
		
		global $edd_currency_model;
		
		$this->model = $edd_currency_model;
	}
	
	/**
	 * Show All Currency Converter Buttons
	 * 
	 * Handles to show all Currency Converter buttons on the viewing page
	 * whereever user put shortcode
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	public function edd_select_currency_shortcode( $content ) {
		
		global $edd_options;
		
		//get currency data
		$currency_data = $this->model->edd_currency_get_data();
		
		//check currency data is not empty
		if( !empty( $currency_data ) ) { // Check currencies data are not empty
			
			$selected_currency = edd_currency_get_stored_currency();
			
			$currency_rate = edd_currency_get_open_exchange_rate_by_code( $selected_currency );
			if( isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] == 'open_exchange' && empty( $currency_rate ) ) {
				return $content;
			}
			
			$content = '';
			$content .= '<div class="edd-currency-wrap">';
			$content .='	<select class="edd-currencies-select" name="edd-currency-select">';
				foreach ($currency_data as $key => $currency_value) {
					$content .= '<option value="' . $currency_value['post_title'] . '"' . selected( $selected_currency, $currency_value['post_title'], false ) . '>' . $currency_value['post_content'] . '</option>';
				}
			$content .='	</select>';
			$content .=' 	<input class="edd-currency-save-button edd-button button" type="button" value="' . __( 'Save', 'eddcurrency' ) . '" />';
			$content .=' 	<input class="edd-currency-button-reset edd-button button edd-curr-btn-reset" type="button" value="' . __( 'Reset', 'eddcurrency' ) . '" />
		   				</div>';
			
		} //end if to check currency data is not empty
		return $content;
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for calling shortcodes.
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	public function add_hooks() {
		
		//add shortcode to show all Currency Converter buttons
		add_shortcode( 'edd_select_currency', array( $this, 'edd_select_currency_shortcode' ) );
		
	}
}
?>