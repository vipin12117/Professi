<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to 
 * different functions 
 * 
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 **/

	/**
	 * Return Currency Symbol 
	 * 
	 * Handles to return currency symbol
	 * from currency code
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	 function edd_currency_get_symbol( $cur_code = '' ){
 	
	 	if ( ! $cur_code ) {
			$cur_code = edd_get_currency();
	 	}
	 	
		$symbol = edd_currency_check_currency_symbol( $cur_code );
		if( !empty( $symbol ) ) {
			return apply_filters( 'edd_currency_get_symbol', $symbol, $cur_code );	
		}
		
		switch ( $cur_code ) {
			case 'BRL' :
						$symbol = '&#82;&#36;';
						break;
			case 'AUD' :
			case 'CAD' :
			case 'MXN' :
			case 'NZD' :
			case 'HKD' :
			case 'SGD' :
			case 'USD' :
						$symbol = '&#36;';
						break;
			case 'EUR' :
						$symbol = '&euro;';
						break;
			case 'CNY' :
			case 'RMB' :
			case 'JPY' :
						$symbol = '&yen;';
						break;
			case 'RUB' :
						$symbol = '&#1088;&#1091;&#1073;.';
						break;
			case 'KRW' : 
						$symbol = '&#8361;';
						break;
			case 'TRY' : 
						$symbol = '&#84;&#76;';
						break;
			case 'NOK' : 
						$symbol = '&#107;&#114;';
						break;
			case 'ZAR' : 
						$symbol = '&#82;';
						break;
			case 'CZK' : 
						$symbol = '&#75;&#269;';
						break;
			case 'MYR' : 
						$symbol = '&#82;&#77;';
						break;
			case 'DKK' : 
						$symbol = '&#107;&#114;'; 
						break;
			case 'HUF' : 
						$symbol = '&#70;&#116;'; 
						break;
			case 'IDR' : 
						$symbol = 'Rp'; 
						break;
			case 'INR' : 
						$symbol = '&#8377;';
						break;
			case 'ILS' : 
						$symbol = '&#8362;';
						break;
			case 'PHP' : 
						$symbol = '&#8369;';
						break;
			case 'PLN' :
						$symbol = '&#122;&#322;';
						break;
			case 'SEK' :
						$symbol = '&#107;&#114;';
						break;
			case 'CHF' : 
						$symbol = '&#67;&#72;&#70;';
						break;
			case 'TWD' : 
						$symbol = '&#78;&#84;&#36;';
						break;
			case 'THB' : 
						$symbol = '&#3647;';
						break;
			case 'GBP' :
						$symbol = '&pound;';
						break;
			case 'RON' :
						$symbol = 'lei'; 
						break;
			case 'RIAL' :
						$symbol = '&#65020;'; 
						break;
			default    :
						$symbol = $cur_code; 
						break;
		}
	
		return apply_filters( 'edd_currency_convert_symbol', $symbol, $cur_code );
	 }
	 /**
	  * Return Open Exchange Rate
	  * 
	  * Handles to return open exchange rate
	  * from passed argumented price
	  * 
	  * @package Easy Digital Downloads - Currency Converter
	  * @since 1.0.0
	  **/
	 function edd_currency_get_open_exchange_rates(){
	
	 	global $edd_options;
	 	
	 	$savedrates = array(
								'error' => 1,
								'error_msg' => __( 'No APP ID is Inserted', 'eddcurrency' )
							);

		if( isset( $edd_options['exchange_app_id'] ) && !empty( $edd_options['exchange_app_id'] ) ) {
			
			//saved rates
			$savedrates = get_transient( 'edd_currency_open_exchange_rates' );

			//check is there any rates saved before in database
			if ( empty( $savedrates ) ) {
				
				//url to call openexchange rates api
				$openexchangeurl = 'http://openexchangerates.org/api/latest.json?app_id=' . $edd_options['exchange_app_id'];
				
				//get response rates from url
				$response = wp_remote_get( $openexchangeurl );
				
				//response rate
				$response_rates = !is_wp_error( $response ) ? $response['body'] : false;
				
				$exchang_rates = '';
				
				if(!empty($response_rates)) {
					
					//json decode
					$exchang_rates = json_decode( $response_rates, true );
				}
				
				//check get rates should not be empty returned by api && it should not contains error
				if( !empty( $response_rates ) && ( !isset( $exchang_rates['error'] ) || empty( $exchang_rates['error'] ) ) ) {
					
					//check base currency is set in response or not
					if( isset( $exchang_rates['base'] ) ) {
						
						//get option for exchange rate caching
						$cache_rates = $edd_options['exchange_rates_cache'];
						
						//check rate caching is empty or not
						if( empty( $cache_rates ) ) { $cache_rates = 60; }

						//cache time for exchange rate
						$cache_time = 60 * intval( $cache_rates );
						
						//save temporary returned exchange rates to database for further use
				  		set_transient( 'edd_currency_open_exchange_rates', $response_rates, $cache_time );
				  		
					} //end if to check base rate in saved data
					
				} else {
					
					//error occred via curl
					$savedrates = array(
						'error' 	=> 1,
						'status'	=> $exchang_rates['status'],
						'error_msg' => $exchang_rates['description']
					);
					
				} //end else
				
			} else {
				
				//get saved rates
				$savedrates = json_decode( $savedrates, true );
				
			} //end else
			
			//check is there any error is set or not
			if( isset( $savedrates['error'] ) ) {
				
				//an open exchange error occured
				$error = $savedrates['status'] . ' ' . $savedrates['error_msg'];
				$savedrates = array(
								'error' 	=> 1,
								'error_msg'	=> $error
							);
			} //end if to check is there any error or not
		}

		return apply_filters( 'edd_currency_all_open_exchange_rates', $savedrates );
	}
	/**
	 * Convert Price
	 * 
	 * Handles to return converted price
	 * with appropriate method which is seleted
	 * in backend
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_converted_price( $price ){
		
		global $edd_options;
		
		$edd_currency 	 = edd_get_currency();
		$stored_currency = edd_currency_get_stored_currency();
		$exchange_rates  = edd_currency_get_exchange_rates();
		$base_rate 		 = edd_currency_get_exchange_base_rates();
		$price 			 = edd_sanitize_amount($price); // sanitized amount by stripping out thousands separators
		
		if( isset( $exchange_rates[$stored_currency] ) ) {
			 
			// removed code from version 1.0.4
			//check base currency & base rates are same or not
			/*if( $edd_currency == $base_rate ) {
				$price = $price * $exchange_rates[$stored_currency];
			} elseif( $stored_currency == $base_rate ) { 
				$price = $price / $exchange_rates[$edd_currency];
			} else {*/
			
			$price = $price / $exchange_rates[$edd_currency];
			$price = $price * $exchange_rates[$stored_currency];
			//}

		}
		return apply_filters( 'edd_currnency_get_convert_price', edd_format_amount( $price ) );
	}
	/**
	 * Get Exchange Rates (of open exchange + custom rates) array for all currencies
	 * 
	 * Handles to get all exchange rates
	 * as per appropriate methods
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_exchange_rates(){
		
		global $edd_options;
		
		//get cached rates
		$exchangerates = wp_cache_get( 'edd_currency_exchange_rates' );
		
		//check is there any cached rate is set or not
		if( empty( $exchangerates ) ) {

			$exchangerates = array();

			//check if exchange method is open exchange or not
			if( isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] != 'custom_rates' ) {
				
				//get open exchange rates
				$open_exchange_rates = edd_currency_get_open_exchange_rates();
				
				// if there are no errors, add open exchange rates
				if( ! isset( $open_exchange_rates['error'] ) || $open_exchange_rates['error'] != 1 ) {
					$exchangerates = $open_exchange_rates['rates'];
				}
			}
			
			//get currency list
			$currencies = edd_currency_get_list();
			foreach( $currencies as $currency ) {
				// don't allow custom rate for exchange rates base currency
			
				if( isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] != 'open_exchange' ){
					$custom_rate = floatval( $currency['custom_rate'] );
					if( $custom_rate > 0 ) {
						$exchangerates[$currency['code']] = $custom_rate;
					}
				}
			}
			
			// removed code from version 1.0.4
			//if( isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] != 'custom_rates' ) { 
			/*if( isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] == 'open_exchange' ) { 
				echo $baserate = edd_currency_get_exchange_base_rates();
				$exchangerates[$baserate] = 1;
			} 
			*/
		
			wp_cache_set( 'edd_currency_exchange_rates', $exchangerates );
			
		} //end if check to is there any exchange rates are cached or not

		return apply_filters( 'edd_currency_get_all_exchange_rates', $exchangerates );
	}
	/**
	 * Get Exchange Rates Base
	 * 
	 * Handles to return exchange rates
	 * base from rates
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_exchange_base_rates(){
		
		global $edd_options;
		
		// removed code from version 1.0.4
		
		$base_rate = 'USD'; //edd_get_currency();
		
		/*//check exchange method is open exchange or not
		if( isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] == 'open_exchange' ) {

			//get open exchange rates		
			$open_exchange = edd_currency_get_open_exchange_rates();
			
			//check is there any base exchange rate is set or not
			if( isset( $open_exchange['base'] ) ) {
				$base_rate = $open_exchange['base'];
			} //end if to check is there any base exchange rate is set or not
			
		} //end else*/

		return apply_filters( 'edd_currency_get_base_exchange_rates', $base_rate );
	}
	/**
	 * Get Stored Currency in Cookies
	 * 
	 * Handles to return stored currency in Cookies
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_stored_currency() {
		
		global $edd_options;
		
		//get stored currency in backend settings page
		$stored_currency = edd_currency_get_base_currency();

		//check if cookie is set or not
		if( isset( $_COOKIE['edd-currency'] ) ) {
			$stored_currency = $_COOKIE['edd-currency'];
		} //end if to check cookie is set or not
		
		//check currency auto detection is enabled and prompt user detection is not set
		//and currency is already not detected
		else if( isset( $edd_options['currency_detection'] ) && !empty( $edd_options['currency_detection'] )
				 && empty( $edd_options['prompt_user_detection'] )
				 && !isset( $_COOKIE['edd-currency-detected'] ) ) {
		
			$currency_detected = edd_currency_get_detected_currency();
			//check detected currency is not empty
			if( !empty( $currency_detected ) ) {
				$stored_currency = $currency_detected;
			} //end it to check detected  currency is not empty
				
		}
		
		$currency_list = edd_currency_get_currency_list();
		//check if sotred currency has rate
  		if( ! isset( $currency_list[$stored_currency] ) ) {
			$stored_currency = edd_get_currency();
	  	}
	  	
		return apply_filters( 'edd_currency_get_saved_currency', $stored_currency );
	}
	/**
	 * Get Detected Currency From IP
	 * 
	 * Handles to get customer detected currncey
	 * based on IP Address
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_detected_currency() {
		
		global $edd_options;
	
		//get currency code detected
		$currency_detected = wp_cache_get( 'edd_currency_detected' );
		
		//check currency detected is not empty
		if( empty( $currency_detected ) ) {
			
			$detecteddata = array();
			
			//get currency from IP address of customer
			$currency_url 	= 'http://www.geoplugin.net/php.gp?ip=' . edd_get_ip();
			$remotedata 	= wp_remote_get( $currency_url, array( 'sslverify'   => false ) );
			
			if ( !is_wp_error( $remotedata ) ) { // Check error are not set
				$detecteddata 	= isset( $remotedata['body'] ) && !empty( $remotedata['body'] ) ? maybe_unserialize( $remotedata['body'] ) : false;
			}
			//check currency detection data should not empty
			if( !empty( $detecteddata ) ) {
				
				//check currency code is detected or not
				if ( isset( $detecteddata['geoplugin_currencyCode'] ) ) {
					
					$currency_detected = $detecteddata['geoplugin_currencyCode'];
					
					//get all currencies data
					$currencies = edd_currency_get_currency_list();
					
					//check base currency & detected currency is same
					if( $currency_detected == edd_get_currency() ) {
						$currency_detected = false;
					}
					
					//check detected currency is empty and not set in currency list
					if( empty( $currency_detected ) && !isset( $currencies[$currency_detected] ) ) {
						$currency_detected = false;
					}
				}  //end if to check currency code is set or not based on ip
				else {
					$currency_detected = false;
				}
				
			} //end if to check detected currecny data is not empty

			//check detected currency is empty then set it false
			if( empty( $currency_detected ) ) {	$currency_detected = false; }
	
			//store detected currency in cache
			wp_cache_set( 'edd_currency_detected', $currency_detected );
			
		} //end if to check detected currency is not empty
		
		//check detected currency is empty then set it false
		if( empty( $currency_detected ) ) {	$currency_detected = false; }
		
		return apply_filters( 'edd_currency_get_customer_detected_currency', $currency_detected );
	}
	
	/**
	 * Get All Currencies
	 * 
	 * Handles to return all currencies 
	 * data
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_list() {
		
		$resultdata = array();
		
		$currargs = array( 'post_type'	=>	EDD_CURRENCY_POST_TYPE, 'posts_per_page'   => -1 );
		
		$getcurrencies = get_posts( $currargs );
		
		foreach ( $getcurrencies as $key => $curr ) {
			
			$resultdata[] = array( 
									'ID'			=>	$curr->ID,
									'code'			=>	$curr->post_title,
									'label'   		=>  $curr->post_content,
									'custom_rate'	=>	get_post_meta( $curr->ID, '_edd_currency_custom_rate', true ),
									'symbol'		=>	get_post_meta( $curr->ID, '_edd_currency_symbol', true ),
								);
		}
		
		return apply_filters( 'edd_currency_get_stored_list', $resultdata );
	}
	
	/**
	 * Get Open Exchange Rate based on code
	 * 
	 * Handles to get open exchange rate based on code
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_open_exchange_rate_by_code( $currency_code ) {
		
		global $edd_options;
		
		//check exchange method is open exchange or not
		if( !empty( $currency_code ) && isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] == 'open_exchange' ) {

			//get open exchange rates		
			$open_exchange = edd_currency_get_open_exchange_rates();
			//check is there any base exchange rate is set or not
			if( isset( $open_exchange['rates'][$currency_code] ) ) {
            	return $open_exchange['rates'][$currency_code];
			}
		} //end else

		return false;
	}
	
	/**
	 * Get Currency List
	 * 
	 * Handles to get currency list
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_currency_list() {
		
		$valid_list = wp_cache_get( 'edd_all_currency_exchange_rates' );
		if( $valid_list === false ) {
			$valid_list = array();

			//get currency list
			$currency_list = edd_currency_get_list();
			$exchange_rates = edd_currency_get_exchange_rates();

			foreach( $currency_list as $currency ) {
				if( isset( $exchange_rates[$currency['code']] ) ) {
					$valid_list[$currency['code']] = $currency;
				}
			}
			wp_cache_set( 'edd_all_currency_exchange_rates', $valid_list );
		}
		return apply_filters( 'edd_currency_get_all_currency_list', $valid_list );
	}
	
	/**
	 * Check Currency Symbol
	 * 
	 * Handles to check currency symbol
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_check_currency_symbol( $currency_code ) {
		
		$currency_symbol = '';
		
		if( !empty( $currency_code ) ) {
			//get currency list
			$currencies = edd_currency_get_list();
			foreach( $currencies as $currency ) {
				if( isset( $currency['code'] ) && $currency['code'] == $currency_code ) {
					$currency_symbol = $currency['symbol'];
					break;
				}
			}
		}
		return $currency_symbol;
	}
	
	/**
	 * Check Currency Rate
	 * 
	 * Handles to check currency rate
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_get_custom_currency_rate( $currency_code ) {
		
		$currency_rate = '';
		
		if( !empty( $currency_code ) ) {
			//get currency list
			$currencies = edd_currency_get_list();
			foreach( $currencies as $currency ) {
				if( isset( $currency['code'] ) && $currency['code'] == $currency_code ) {
					$currency_rate = $currency['custom_rate'];
					break;
				}
			}
		}
		return $currency_rate;
	}
	
	/**
	 * Check Base Currency Rate
	 * 
	 * Handles to check base currency rate
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_check_base_currency_has_rate() {
		
		$check = false;
		$base_currency_code = edd_get_currency();
		$exchange_rates 	= edd_currency_get_exchange_rates();
		
		if( isset( $exchange_rates[$base_currency_code] ) ) {
			$check = true;
		}
		return apply_filters( 'edd_currency_check_base_currency_rate', $check );
	}
	
	/**
	 * Return EDD Currency Converted Array
	 * 
	 * Handles to return converted currnecy
	 * array result
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	function edd_currency_base_currency_data() {
	
		$base_currency_code = edd_get_currency();
		$currency_list = edd_currency_get_currency_list();
		$base_currency = $currency_list[$base_currency_code];
		
		return apply_filters( 'edd_currency_get_base_currency_data', $base_currency );
	}
	/**
	 * Return EDD Currency Base Currency 
	 *
	 * Handles to return edd currency base currency
	 * chosen in edd currrency converter options
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.1
	 **/
	function edd_currency_get_base_currency() {
		
		global $edd_options;
		
		$base_currency = edd_get_currency();
		
		//check base currency is set and not empty in currency converter plugin
		if( isset( $edd_options['curr_base_currency'] ) && !empty( $edd_options['curr_base_currency'] ) ) {
			$base_currency = $edd_options['curr_base_currency'];
		} //end if
		
		//return base currency
		return $base_currency;
	}
?>