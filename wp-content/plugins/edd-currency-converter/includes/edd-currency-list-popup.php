<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * User Balance Popup
 *
 * This is the code for the pop up user balance, which shows up when an user clicks
 * on the adjust under currency column in user listing.
 *
 * @package Easy Digital Downloads - currency converter
 * @since 1.0.0
 **/
global  $edd_currency_model,$edd_currency_render, $edd_options;

?>

<div class="edd-currency-popup-content">

	<form action="" method="post">
		<div class="edd-currency-header">
			<div class="edd-currency-header-title">
				<h2 class="edd-select-currency-title"><?php _e( 'Choose your currency:', 'eddcurrency' );?></h2>
				<img title="<?php _e( 'Close', 'eddcurrency' ); ?>" class="edd-currency-popup-close" src="<?php echo EDD_CURRENCY_IMG_URL . '/tb-close.png'; ?>" alt="<?php _e( 'Close', 'eddcurrency' ); ?>" /></span>
				<?php $edd_currency_render->edd_currency_select_message();?>
			</div>
		</div>
		
		<div class="edd-currency-popup">
			<?php
				//$current_currency = isset( $_COOKIE['edd-currency'] ) && !empty( $_COOKIE['edd-currency'] ) ? $_COOKIE['edd-currency'] : edd_get_currency();
				$current_currency = edd_currency_get_stored_currency();
				
				$currency_data = $edd_currency_model->edd_currency_get_data();
				
				if( !empty( $currency_data ) ) { // Check currencies data are not empty
					
					$edd_currency_select_opt = '';
					$content = '';
					$content .='<ul class="edd-currency-lists">';
					
					foreach ($currency_data as $key => $currency_value) {
						
						$currency_rate = edd_currency_get_open_exchange_rate_by_code( $currency_value['post_title'] );
						if( isset( $edd_options['exchange_rates_method'] ) && $edd_options['exchange_rates_method'] == 'open_exchange' && empty( $currency_rate ) ) {
							continue;
						}
						
						$selected_currency_class = ( $current_currency == $currency_value['post_title'] ) ? ' edd-list-item-selected ' : '';
						
						$content .='<li data-code="' . $currency_value['post_title'] . '" class="edd-list-item ' . $selected_currency_class . '" value="' . $key . '">';
							$content .= '<span class="edd-currency-code">' . $currency_value['post_title'] . '</span>';
							$content .= '<span class="edd-currency-name">' . $currency_value['post_content'] . '</span>';
						$content .= '</li>';
						
						//Making Edd Currenct Select Combo options
						$edd_currency_select_opt .= '<option value="' . $currency_value['post_title'] . '"' . selected( $current_currency, $currency_value['post_title'], false ) . '>' . $currency_value['post_content'] . '</option>';
						
					}
					
					$content .='</ul>';
					
					//For Edd Currenct Select Combo 
					$content .= '<select class="edd-currencies-select edd-currencies-mb-select" name="edd-currency-select">';
					$content .= 	$edd_currency_select_opt;
					$content .= '</select>';
				}
				echo $content;
				
			?>
		</div><!--.edd-currency-popup-->
		<div class="edd-currency-footer">
			<div class="edd-footer-col">
				<div class="edd-selected-currency"></div>
			</div>
			<div class="edd-currency-buttons">
				<input class="edd-currency-button-save" type="button" value="<?php _e( 'Save', 'eddcurrency' );?>" name="save_currency">
				<button class="edd-currency-button-reset" type="button"><?php _e( 'Reset', 'eddcurrency' );?></button>
			</div>
		</div><!--.edd-currency-footer-->
	</form>
</div><!--.edd-currency-popup-content-->
<div class="edd-currency-popup-overlay"></div>