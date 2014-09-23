<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Save Currencies
 *
 * Handle currency save and edit currencies
 * 
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */

	global $errmsg, $wpdb, $user_ID,$error,$edd_currency_model;

	$model = $edd_currency_model;
	
	// save for currency data
	if(isset($_POST['edd_currency_save']) && !empty($_POST['edd_currency_save'])) { //check submit button click

		$error = '';
		if(isset($_POST['edd_currency_code']) && empty($_POST['edd_currency_code'])) { //check currency code
			
			$errmsg['edd_currency_code'] = __('Please Enter Currency Code.','eddcurrency');
			$error = true;
			
		}
		
		if(isset($_GET['edd_currency_id']) && !empty($_GET['edd_currency_id']) && $error != true) { //check no error and currency id is set in url
			
			$postid = $_GET['edd_currency_id'];
			
			//data needs to update for currency
			$update_post = array(
									'ID'			=> 	$postid,
									'post_content'  =>	$_POST['edd_currency_label'],
									'post_status'   =>	'publish',
									'post_author'   =>	$user_ID,
								);
			
			//update currency data
			wp_update_post( $model->edd_currency_escape_slashes_deep($update_post) );
			update_post_meta( $postid, '_edd_currency_symbol',isset($_POST['edd_currency_symbol']) ? $model->edd_currency_escape_slashes_deep($_POST['edd_currency_symbol']) : '');
			update_post_meta( $postid, '_edd_currency_custom_rate',isset($_POST['edd_currency_custom_rate']) ? $model->edd_currency_escape_slashes_deep($_POST['edd_currency_custom_rate']) : '');
			
			$redirect_url = add_query_arg( array( 'post_type' => 'download', 'page' => 'edd_currency_converter', 'message' => '2' ), admin_url( 'edit.php' ) );
			wp_redirect( $redirect_url );
			exit;
			
		} else {
			
			if( isset($_POST['edd_currency_code']) && !empty( $_POST['edd_currency_code'] ) ) {
				
				$currency_code = strtoupper( $_POST['edd_currency_code'] );
				$currency_name = $model->edd_currency_current_currency_name( $currency_code );
				if( !empty( $currency_name ) ) {
					
					$errmsg['edd_currency_code'] = __('Currency Code already exists, please enter other code.','eddcurrency');
					$error = true;
					
				}
			}
			
			if($error != true) { //check there is no error then insert data in to the table
				
				$currency_code 				= strtoupper( $_POST['edd_currency_code'] );
				$edd_currency_orders 		= get_option( 'edd_currency_sort_order' );
				$edd_currency_order_count 	= get_option( 'edd_currency_sort_order_count' );
				$edd_currency_order_count 	= $edd_currency_order_count + 1;
				
				// Create post object
				$currency_arr = array(
										  'post_title'   =>	$currency_code,
										  'post_content' =>	$_POST['edd_currency_label'],
										  'post_status'  =>	'publish',
										  'post_author'  =>	$user_ID,
										  'menu_order'   =>	$edd_currency_order_count,
										  'post_type'    =>	EDD_CURRENCY_POST_TYPE
										);
				
				// Insert the post into the database
				$result = wp_insert_post( $model->edd_currency_escape_slashes_deep($currency_arr)  );
				
				if($result) { //check inserted currency id
					
					update_post_meta( $result, '_edd_currency_symbol',isset($_POST['edd_currency_symbol']) ? $model->edd_currency_escape_slashes_deep($_POST['edd_currency_symbol']) : '');
					update_post_meta( $result, '_edd_currency_custom_rate',isset($_POST['edd_currency_custom_rate']) ? $model->edd_currency_escape_slashes_deep($_POST['edd_currency_custom_rate']) : '');
				
					// store cuurency id and order no
					$edd_currency_orders[$result] = $edd_currency_order_count;
					
					//update sort order
					update_option( 'edd_currency_sort_order', $edd_currency_orders );
					
					//update sort order count
					update_option( 'edd_currency_sort_order_count', $edd_currency_order_count );
					
					$redirect_url = add_query_arg( array( 'post_type' => 'download', 'page' => 'edd_currency_converter', 'message' => '1' ), admin_url( 'edit.php' ) );
					wp_redirect( $redirect_url );
					exit;
					
				}
			}
		}
	}?>