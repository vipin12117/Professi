<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Renderer Class
 *
 * To handles some small HTML content for front end
 * 
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */
class EDD_Currency_Renderer {

	/**
	 * EDD Currency Converter model object
	 * 
	 * @var object
	 * @since 1.0.0
	 **/
	public $model;
	
	public function __construct() {
		
		global $edd_currency_model;
		
		$this->model = $edd_currency_model;
		
	}
	
	/**
	 * Display Currency Converter Message
	 *
	 * Handles to display currency converter message on cart page
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_checkout_message_content( $postid ) {
		
		$current_currency_name = $this->model->edd_currency_current_currency_name();
		
		//$selected_currency_code = isset( $_COOKIE['edd-currency'] ) && !empty( $_COOKIE['edd-currency'] ) ? $_COOKIE['edd-currency'] : '';
		$selected_currency_code = edd_currency_get_stored_currency();
		$selected_currency_name = $this->model->edd_currency_current_currency_name( $selected_currency_code );
		
		$message = sprintf( __( 'All orders are processed in', 'eddcurrency' ).'<strong> '.$current_currency_name.' </strong>'.__( 'Prices in', 'eddcurrency' ).' <strong> '.$selected_currency_name.' </strong> '.__( 'are for reference only.', 'eddcurrency' ) );
		
		echo '<fieldset id="edd_currency_checkout_message">'.$message.'</fieldset>';
		
	}
	/**
	 * Show Message in Footer
	 * 
	 * Handles to show saved popup message
	 * when user is click on save button of
	 * currency popup at frontside
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	public function edd_currency_saved_popup() {
		
		global $edd_options;
		
		if( isset( $_COOKIE['edd-currency-saved-msg'] ) && !empty( $_COOKIE['edd-currency-saved-msg'] ) ) {
		
			if( isset( $_COOKIE['edd-currency-detected'] ) || !isset( $edd_options['prompt_user_detection'] ) || empty( $edd_options['prompt_user_detection'] ) ) {
			
				$currency_message = '';
				if( $_COOKIE['edd-currency-saved-msg'] == 'save' ) {
					$selected_currency_code = edd_currency_get_stored_currency();
					$currency_message = __( 'Listo, ya puede ver los precios en '.$this->model->edd_currency_current_currency_name( $selected_currency_code ).'. Por favor tome nota de que Profesi actualiza las tasas de cambio semanalmente y que (por ahora) sólo aceptamos pagos en USD ($)', 'eddcurrency' );	
				} else {
					$currency_message = __( 'Currency reset successfully.', 'eddcurrency' );
				}
				
				if( !empty( $currency_message ) ) {
				?>
					<div class="edd-currency-bottom-popup edd-currency-popup-visible">
						<div class="edd-popup-save-msg">
							<span class="edd-popup-save-msg-currency">
								<strong><?php echo $currency_message; ?></strong>
							</span>
						</div>
						<div class="edd-currency-msg-close">
							<strong><a title="<?php _e( 'Close', 'eddcurrency' ); ?>" href="javascript:void(0);" class="edd-currency-close-popup"><?php _e( 'X', 'eddcurrency' ); ?></a></strong>
						</div>
					</div>
					<script type="text/javascript">
						edd_currency_erase_cookie( 'edd-currency-saved-msg' );
					</script>
				<?php
				}
			}
		}
	}
	
	/**
	 * Currency Converted Message
	 * 
	 * Handles to show the message
	 * that the converted prices are only for
	 * view purpose
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	public function edd_currency_select_message()	{
		
		$base_currency = edd_currency_base_currency_data();
		
		$message = __( 'Converted prices are for reference only - all orders are charged in', 'eddcurrency' );
		$message .= ' '. $base_currency['symbol'] . ' ';
		$message .= $base_currency['label'] . ' ';
		$message .= $base_currency['code'] . '.';

		$message = apply_filters( 'edd_currency_select_message', $message );
		?>
		<div class="edd-currency-select-msg">
			<?php echo $message; ?>
		</div>
		<?php
		
	}
	/**
	 * Show Settings Message
	 * 
	 * Handles to show settings message
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	public function edd_currency_admin_notice() {
		
		global $edd_options;
		
		$errors = array();
		
		// Check easy digital downlownload pages
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'download' ) {
		
			if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] == 'open_exchange'
				&& isset( $edd_options['exchange_app_id'] ) && empty( $edd_options['exchange_app_id'] ) ) {
				
				$errors[] = '<li><p><strong>' . __( 'Easy Digital Download - Currency Converter can not be activated on the front-side because you have selected Exchange Rates Method as Open Exchange Rates and not added it\'s APP ID.' ,'eddcurrency') . '</strong></p></li>';
				
			}
			
			if( ! edd_currency_check_base_currency_has_rate() ) {
				
				$base_currency_code = edd_get_currency();
				$errors[] = '<li><p><strong>' . __( 'Easy Digital Download base currency (' ,'eddcurrency') . $base_currency_code . __( ') must have an exchange rate.', 'eddcurrency' ) . '</strong></p>';
				
			}
			
			if( !empty( $errors ) ) {
			
				?>
				<div class="error" id="updated">
			        <ol>
			        	<?php echo implode( '', $errors ); ?>
			        </ol>
			    </div>
			    <?php
			}
		}
	}
	
	/**
	 * Show Currency Detection Message
	 * 
	 * Handles to show currency detection
	 * message to end user
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 **/
	public function edd_currency_detection_popup() {
		
		global $edd_options;
		
		//check prompt user detection is activated or not
		if( isset( $edd_options['prompt_user_detection'] ) && !empty( $edd_options['prompt_user_detection'] )
			&& !isset( $_COOKIE['edd-currency-detected'] ) ) {
			
			$currency_detected = edd_currency_get_detected_currency();
			
			if( !empty( $currency_detected ) ) {
				
				$currencies = edd_currency_get_currency_list();
				
				if(isset($currencies[$currency_detected])) {
					$detected_currency = $currencies[$currency_detected];
				} else {
					$detected_currency = '';
				}
				
				//check detected currency has rate
				if( !empty( $detected_currency ) ) {
				
				?>
				<div class="edd-currency-detection-prompt-popup">
					<div class="edd-currency-dection-close">
						<strong><a title="<?php _e( 'Close', 'eddcurrency' ); ?>" href="javascript:void(0);" class="edd-currency-close-popup"><?php _e( 'X', 'eddcurrency' ); ?></a></strong>
					</div>
					<div class="edd-currency-detection-popup-msg-wrap">
						<?php
						_e( 'Hi! We have detected your currency as', 'eddcurrency' );
						echo '<span class="edd-currency-detection-popup-msg"> ';
						esc_html_e( $detected_currency['symbol'] . ' ' . $detected_currency['label'] . ' ' . $detected_currency['code'],'eddcurrency' );
						echo '</span>';
						_e( '. Is this correct?', 'eddcurrency' );
						?>
					</div>
					<div class="edd-currency-detected-buttons">
						<button type="button" class="edd-currency-detect-button edd-currency-button-convert-yes"><?php _e( 'Yes', 'eddcurrency' ); ?></button>
						<button type="button" class="edd-currency-detect-button edd-currency-button-convert-no"><?php _e( 'No', 'eddcurrency' ); ?></button>
					</div>
					<?php $this->edd_currency_select_message(); ?>
				</div>
				<?php
				
				} //end if to check detected currency has rate
				
			} //end if to check detected currency is not empty
			
		} //end if to check prompt user detection is enabled or not
	}
}
?>