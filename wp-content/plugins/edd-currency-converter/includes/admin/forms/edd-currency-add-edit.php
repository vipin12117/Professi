<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add/Edit Currency
 *
 * Handle Add / Edit currency
 * 
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */

	global $edd_options, $edd_currency_model, $errmsg,$error; //make global for error message to showing errors
	
	$model = $edd_currency_model;
	
	$html = '';  //start string varible with blank
	
	//set default value as blank for all fields
	//preventing notice and warnings
	$data = array( 
					'edd_currency_code'			=> '',
					'edd_currency_label' 		=> '',
					'edd_currency_symbol' 		=> '',
					'edd_currency_open_ex_rate' => __( 'N/A', 'eddcurrency' ),
					'edd_currency_custom_rate' 	=> '',
				);
	
	if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['edd_currency_id']) && !empty($_GET['edd_currency_id'])) { //check action & id is set or not
		
		//currency page title
		$currency_lable = __('Edit Currency', 'eddcurrency');
		
		//currency page submit button text either it is Add or Update
		$currency_btn = __('Update', 'eddcurrency');
		
		//get the currency id from url to update the data and get the data of currency to fill in editable fields
		$postid = $_GET['edd_currency_id'];
		
		//get the data from currency id
		$getpost = get_post( $postid );
		
		//assign retrived data to current page fields data to show filled in fields
		if($error != true) { //if error is not occured then fill with database values
			$data['edd_currency_code'] 			= $getpost->post_title;
			$data['edd_currency_label'] 		= $getpost->post_content;
			$data['edd_currency_symbol'] 		= get_post_meta( $postid, '_edd_currency_symbol', true );
			$data['edd_currency_custom_rate'] 	= get_post_meta( $postid, '_edd_currency_custom_rate', true );
		} else {
			$data = $_POST;
		}
		
	} else {
		
		//currency page title
		$currency_lable = __('Add New Currency', 'eddcurrency');
		
		//currency page submit button text either it is Add or Update
		$currency_btn = __('Save', 'eddcurrency');
		
		//if when error occured then assign $_POST to be field fields with none error fields
		if($_POST) { //check if $_POST is set then set all $_POST values
			$data = $_POST;
		}
	}
	
	if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] != 'custom_rates' ) {
							  	
		$open_exchange_rates = edd_currency_get_open_exchange_rates();
	
	   	if( isset( $open_exchange_rates['rates'][$data['edd_currency_code']] ) ) {
	    	$data['edd_currency_open_ex_rate'] = $open_exchange_rates['rates'][$data['edd_currency_code']];
	   	}
	}
	
	$html .= '<div class="wrap">';
	
	$html .= screen_icon('options-general');
	
	$html .= '<h2>'.__( $currency_lable , 'eddcurrency').'<a class="add-new-h2" href="edit.php?post_type=download&page=edd_currency_converter">' . __( 'Go Back','eddcurrency' ) . '</a></h2>';
	
	if( isset( $errmsg['edd_currency_code'] ) && !empty( $errmsg['edd_currency_code'] ) ) { //check error message for currency title
		$html .= '<div class="updated settings-error" id="setting-error-settings_updated">
					<p><strong>' . $errmsg['edd_currency_code'] . '</strong></p>
				</div>';
	}
	
	$html .= '<!-- beginning of the currency meta box -->

				<div id="edd_currency" class="post-box-container">
				
					<div class="metabox-holder">	
				
						<div class="meta-box-sortables ui-sortable">
				
							<div id="currency" class="postbox">	
				
								<div class="handlediv" title="'. __( 'Click to toggle', 'eddcurrency' ).'"><br /></div>
				
									<!-- currency box title -->
				
									<h3 class="hndle">
				
										<span style="vertical-align: top;">'. __( $currency_lable, 'eddcurrency' ).'</span>
				
									</h3>
				
									<div class="inside">';
	
							$html .= '<form action="" method="POST" id="edd-currency-add-edit-form">
										<input type="hidden" name="page" value="edd_currency_manage" />
										
										<div id="edd-currency-require-message"><strong>(</strong> <span class="edd-currency-require">*</span> <strong>) '. __( 'Required fields', 'eddcurrency' ).'</strong></div>
											<table class="form-table edd-currency-box"> 
											<tbody>';
							
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Code:', 'eddcurrency' ).'</strong><span class="edd-currency-require"> * </span></label>
													</th>';
										if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['edd_currency_id']) && !empty($_GET['edd_currency_id'])) {
											$html .='<td><code>' . $model->edd_currency_escape_attr($data['edd_currency_code']) . '</code>
													</td>';
										} else {
											$html .='<td><input type="text" id="edd_currency_code" name="edd_currency_code" value="'.$model->edd_currency_escape_attr($data['edd_currency_code']).'" class="small-text"/><br />
														<span class="description">'.__( 'Enter the unique currency code, which is not already saved in list.', 'eddcurrency' ).'</span>
													</td>';
										}
										$html .='</tr>';
														
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Label:', 'eddcurrency' ).'</strong></label>
													</th>
													<td><input type="text" id="edd_currency_label" name="edd_currency_label" value="'.$model->edd_currency_escape_attr($data['edd_currency_label']).'" class="regular-text"/><br />
														<span class="description">'.__( 'Enter the currency label.', 'eddcurrency' ).'</span>
													</td>
												 </tr>';
												
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Symbol:', 'eddcurrency' ).'</strong></label>
													</th>
													<td><input type="text" id="edd_currency_symbol" name="edd_currency_symbol" value="'.$model->edd_currency_escape_attr($data['edd_currency_symbol']).'" class="small-text"/><br />
														<span class="description">'.__( 'Enter the currency symbol.', 'eddcurrency' ).'</span>
													</td>
												 </tr>';
										
										if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] != 'custom_rates' ) {			
											
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Open Exchange Rate:', 'eddcurrency' ).'</strong></label>
													</th>
													<td><code>' . $data['edd_currency_open_ex_rate'] . '</code></td>
												 </tr>';
										}
										
										if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] != 'open_exchange' ) {			
											
										$html .='<tr>
													<th scope="row">
														<label><strong>'.__( 'Custom Rate:', 'eddcurrency' ).'</strong></label>
													</th>
													<td><input type="text" id="edd_currency_custom_rate" name="edd_currency_custom_rate" value="'.$model->edd_currency_escape_attr($data['edd_currency_custom_rate']).'" class="small-text"/><br />
														<span class="description">'.__( 'Enter the currency custom rate.', 'eddcurrency' ).'</span>
													</td>
												 </tr>';
										}
														
										$html .= '<tr>
													<td colspan="3">
														<input type="submit" class="button-primary margin_button" name="edd_currency_save" id="edd_currency_save" value="'.$currency_btn.'" />
													</td>
												</tr>
											</tbody>
											</table>
									</form>';	
							
	$html .= '					</div><!-- .inside -->
					
							</div><!-- #currency -->
				
						</div><!-- .meta-box-sortables ui-sortable -->
				
					</div><!-- .metabox-holder -->
				
				</div><!-- #edd_currency -->
				
				<!-- end of the currency meta box -->';
	
	$html .= '</div><!-- .wrap -->';
	
	echo $html;
?>