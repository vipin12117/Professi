<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Model Class
 *
 * Handles generic plugin functionality.
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */
class EDD_Currency_Model {
	
	public function __construct() {
		global $edd_options;
	}
	
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package  Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_escape_attr( $data ){
		return esc_attr( stripslashes( $data ) );
	}
	
	/**
	 * Strip Slashes From Array
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_escape_slashes_deep( $data = array(), $flag = false, $limited = false ){
			
		if( $flag != true ) {
			
			$data = $this->edd_currency_nohtml_kses( $data );
			
		} else {
			
			if( $limited == true ) {
				$data = wp_kses_post( $data );
			}
			
		}
		$data = stripslashes_deep( $data );
		return $data;
	}
	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_nohtml_kses( $data = array() ) {
		
		if ( is_array($data) ) {
			
			$data = array_map( array( $this,'edd_currency_nohtml_kses' ), $data );
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses( $data );
		}
		
		return $data;
	}	
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 * 
	 */
	public function edd_currency_object_to_array( $result ) {
	    $array = array();
	    foreach ($result as $key=>$value)
	    {	
	        if (is_object($value))
	        {
	            $array[$key] = $this->edd_currency_object_to_array( $value );
	        } else {
	        	$array[$key] = $value;
	        }
	    }
	    return $array;
	}
	
	/**
	 * Register Settings
	 * 
	 * Handels to add settings in settings page
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_settings( $settings ) {
		
		global $edd_options;
		
		$success_message = '';
		
		//create currency page option default
		$currency_page = get_option( 'edd_currency_select_currency_page' );
		
		//default selected currency page
		$selected_page_id = isset( $currency_page ) ? $currency_page : '';
		
		// Display success message when click Apply Currency extensions settings
		if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'apply_currency'
			&& isset( $_GET['success_count'] ) ) {
			$success_count = $_GET['success_count'];
			$success_message = '<div class="updated" id="message"><p><strong>' . sprintf( __( '%d order(s) updated.','eddcurrency' ), $success_count ) . '</strong></p></div>';
		}
		
		$pages = get_pages();
		$pages_options = array( '' => __( '--Select Currency Page--', 'eddcurrency' ) ); // Blank option
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}
		
		//currencies for base currencies
		$base_currencies = array(  '' => __( '--Select Base Currency--', 'eddcurrency' ) );
		$currencies = edd_currency_get_list();
		if( !empty( $currencies ) ) {
			foreach ( $currencies as $curr ) {
				$base_currencies[ $curr['code'] ] = $curr['label'];
			}
		}
		
		$edd_currency_settings = array(
		
				array(
					'id'		=> 'edd_currency_settings',
					'name'		=> $success_message . '<strong>' . __( 'Currency Converter Settings', 'eddcurrency' ) . '</strong>',
					'desc'		=> __( 'Configure Currency And Rewards Settings', 'eddcurrency' ),
					'type'		=> 'header'
				),
				//Currency Converter Settings
				array(
					'id'		=> 'edd_currency_general_settings',
					'name'		=> '<strong>' . __( 'General Options', 'eddcurrency' ) . '</strong>',
					'type'		=> 'header'
				),
				array(
					'id' 		=> 'exchange_rates_method',
					'name' 		=> __( 'Exchange Rates Method', 'eddcurrency' ),
					'desc'   	=> '<p class="description">'.__( 'Choose exchange rates method. If you choose', 'eddcurrency').' "'.__('both', 'eddcurrency').'" '.__('rates then it will take custom rates, if custom rate is empty then it will consider open exchange rates. If both are empty it won\'t convert currency on front side.', 'eddcurrency' ).'</p>',
					'type' 		=> 'radio',
					'options' 	=> array( 'open_exchange' => __( 'Open Exchange Rates', 'eddcurrency' ), 'custom_rates' => __( 'Custom Rates', 'eddcurrency' ), 'both_rates' => __( 'Both Rates ( Open Exchange Rates and Custom Rates )', 'eddcurrency' ) )
				),
				array(
					'id' 		=> 'select_currency',
					'name' 		=> __( 'Select Currency Page', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Shortcode to place on page:', 'eddcurrency').' <code>[edd_select_currency]</code>'.'</p>',
					'type' 		=> 'select',
					'options'	=> $pages_options
				),
				array(
					'id' 		=> 'replace_currency_code',
					'name' 		=> __( 'Replace Currency symbol', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Replace the Currency symbol with Currency code.', 'eddcurrency' ).'</p>',
					'type' 		=> 'checkbox'
				),
				array(
					'id' 		=> 'append_code',
					'name' 		=> __( 'Append Currency Code', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Appends currency code to prices.', 'eddcurrency' ).'</p>',
					'type' 		=> 'checkbox'
				),
				array(
					'id' 		=> 'curr_code_position',
					'name' 		=> __( 'Currency Code Position', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Choose the location of the currency code, when append currency code is enabled.', 'eddcurrency').'</p>',
					'type' 		=> 'select',
					'options'	=> array( 'before' => __( 'Before - USD 10', 'eddcurrency' ), 'after' => __( 'After - 10 USD', 'eddcurrency' ) )
				),
				array(
					'id' 		=> 'replace_base_currency',
					'name' 		=> __( 'Replace Base Currency', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Replaces base prices with converted prices (alternatively the converted price will be shown beside the base price).', 'eddcurrency' ).'</p>',
					'type' 		=> 'checkbox'
				),
				array(
					'id' 		=> 'display_cart_notification',
					'name' 		=> __( 'Display Cart Notification', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'If the customer saved a currency that is different from the base EDD currency, notify the customer on the cart page that all orders are processed in the base EDD currency.', 'eddcurrency' ).'</p>',
					'type' 		=> 'checkbox'
				),
				//base currency
				array(
					'id' 		=> 'curr_base_currency',
					'name' 		=> __( 'Base Currency', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Choose base currency. if you set base currency different then the one set in general settings then it would show by default price in both currencies.', 'eddcurrency' ).'<p>',
					'type' 		=> 'select',
					'options'	=> $base_currencies,
				),
				// Start Open Exchange Rate settings
				array(
					'id'		=> 'edd_currency_exchange_rate_settings',
					'name'		=> '<strong>' . __( 'Open Exchange Rates Options', 'eddcurrency' ) . '</strong>',
					'type'		=> 'header'
				),
				array(
					'id' 		=> 'exchange_app_id',
					'name' 		=> __( 'App ID', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Enter open exchange rate APP ID, changing the APP ID will clear the cache.', 'eddcurrency' ).'<p>',
					'type' 		=> 'text',
					'size' 		=> 'regular'
				),
				array(
					'id' 		=> 'exchange_rates_cache',
					'name' 		=> __( 'Cache Length', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Length of time in minutes to cache retrieved exchange rates.', 'eddcurrency' ).'</p>',
					'type' 		=> 'text',
					'size' 		=> 'regular'
				),
				//Start Detection Options
				array(
					'id'		=> 'edd_currency_detection_settings',
					'name'		=> '<strong>' . __( 'Detection Options', 'eddcurrency' ) . '</strong>',
					'type'		=> 'header'
				),
				array(
					'id' 		=> 'currency_detection',
					'name' 		=> __( 'Enable Currency Detection', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Check this box, if you want to allow to automatic detect currency and replace it based on customer IP address.', 'eddcurrency' ).'</p>',
					'type' 		=> 'checkbox'
				),
				array(
					'id' 		=> 'prompt_user_detection',
					'name' 		=> __( 'Prompt User', 'eddcurrency' ),
					'desc' 		=> '<p class="description">'.__( 'Check this box, if you want to prompt the customer if they want to save their detected currency. If unchecked, the detected currency will be automatically saved for the customer.', 'eddcurrency' ).'</p>',
					'type' 		=> 'checkbox',
					'std'  		=> 'no'
				),
			);
		
		return array_merge( $settings, $edd_currency_settings );
		
	}
	
	/**
	 * Get Post data
	 * 
	 * Handles to get post data for
	 * currency post type
	 * 
	 * @package Easy Digital Downloads - Currency Converter
 	 * @since 1.0.0
	 */
	public function edd_currency_get_currency_post_data( $args = array() ) {
		
		$currencypostsargs = array('post_type' => EDD_CURRENCY_POST_TYPE, 'post_status' => 'publish');
		
		//return only id
		if(isset($args['fields']) && !empty($args['fields'])) {
			$currencypostsargs['fields'] = $args['fields'];
		}
		
		//return based on meta query
		if(isset($args['meta_query']) && !empty($args['meta_query'])) {
			$currencypostsargs['meta_query'] = $args['meta_query'];
		}
		
		//return based on search
		if(isset($args['search']) && !empty($args['search'])) {
			$currencypostsargs['s'] = $args['search'];
		}
		
		//show how many per page records
		if(isset($args['posts_per_page']) && !empty($args['posts_per_page'])) {
			$currencypostsargs['posts_per_page'] = $args['posts_per_page'];
		} else {
			$currencypostsargs['posts_per_page'] = -1;
		}
		
		//show per page records
		if(isset($args['paged']) && !empty($args['paged'])) {
			$currencypostsargs['paged']	=	$args['paged'];
		}
		
		//get order by records
		if(isset($args['meta_key']) && !empty($args['meta_key'])) {
			$currencypostsargs['meta_key']	=	$args['meta_key'];
		}
		
		//get order by records
		if(isset($args['order']) && !empty($args['order'])) {
			$currencypostsargs['order']	=	$args['order'];
		} else {
			$currencypostsargs['order'] = 'DESC';
		}
		
		//get order by records
		if(isset($args['orderby']) && !empty($args['orderby'])) {
			$currencypostsargs['orderby']	=	$args['orderby'];
		} else {
			$currencypostsargs['orderby'] = 'date';
		}
		
		//fire query in to table for retriving data
		$result = new WP_Query( $currencypostsargs );
		
		if(isset($args['getcount']) && $args['getcount'] == '1') {
			$postslist = $result->post_count;	
		}  else {
			//retrived data is in object format so assign that data to array for listing
			$postslist = $this->edd_currency_object_to_array($result->posts);
		}
		
		return $postslist;
	}
	
	/**
	 * Display Current Currency Name
	 *
	 * Handles to display current currency name
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_current_currency_name( $currency_code = '' )
	{
		global $edd_options;
		
		$current_currency_name = '';
		
		$current_currency = edd_get_currency();
		$current_currency = !empty( $currency_code ) ? $currency_code : $current_currency;
		
		$currency_data = $this->edd_currency_get_currency_post_data();
		if( !empty( $currency_data ) ) { // Check currencies data are not empty
		
			foreach ($currency_data as $key => $currency_value) {
				if( $currency_value['post_title'] == strtoupper( $current_currency ) ) {
					$current_currency_name = $currency_value['post_content'];
					break;
				}
			}
		}
		/*$currencies = edd_get_currencies();
		foreach ( $currencies as $key => $value ) {
			if( $key == $current_currency ) {
				$current_currency = $value;
				break;
			}
		}*/
		
		return $current_currency_name;
	}
	
	/**
 	 * Bulk Delete Action
 	 * 
 	 * @package Easy Digital Downloads - Currency Converter
 	 * @since 1.0.0
 	 */
 	public function edd_currency_bulk_delete( $args = array() ) {
 		
 		if( isset( $args['id'] ) && !empty( $args['id'] ) ) {
		
			wp_delete_post( $args['id'], true );
		}
 	}
 	
	/**
 	 * Get custom curencies from custom post type and return the array of currencies for which the rates are set
 	 * 
 	 * @package Easy Digital Downloads - Currency Converter
 	 * @since 1.0.0
 	 */
 	public function edd_currency_get_data() {
 		
 		global $edd_options;
 		
		$exchange_rates  = edd_currency_get_exchange_rates();
 		
		$currency_data = $args = array();
		if( $this->edd_currency_check_open_exchange() ) {
			
			if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] == 'custom_rates' ) {
				
				$args['meta_query'][] = array(
													'key'		=> '_edd_currency_custom_rate',
													'value'		=> '',
													'compare'	=> '!='
												);
			}
			
			$args['order'] 		= 'ASC';
			$args['orderby'] 	= 'menu_order';
			
			$currency_data = $this->edd_currency_get_currency_post_data( $args );
			
			// Code check for currency rate is empty 
			foreach ( $currency_data as $currency_key => $currency_value ) {
				if( isset( $currency_value['post_title'] ) && !empty( $currency_value['post_title'] ) && !isset( $exchange_rates[$currency_value['post_title']] ) ) {
					unset( $currency_data[$currency_key] );
				}
			}
		}
		return $currency_data;
 	}
 	
	/**
 	 * Check if open exchange method is set and app id is not empty
 	 * 
 	 * @package Easy Digital Downloads - Currency Converter
 	 * @since 1.0.0
 	 */
 	public function edd_currency_check_open_exchange() {
 		
 		global $edd_options;
 		
		if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] == 'open_exchange'
			&& isset( $edd_options['exchange_app_id'] ) && empty( $edd_options['exchange_app_id'] ) ) {
			
			return false;
		}
		return true;
 	}
}
?>