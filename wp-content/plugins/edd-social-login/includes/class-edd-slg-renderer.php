<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Renderer Class
 *
 * To handles some small HTML content for front end
 * 
 * @package Easy Digital Downloads - Social Login
 * @since 1.0.0
 */
class EDD_Slg_Renderer {

	var $model;
	var $socialfacebook;
	var $socialgoogle;
	var $sociallinkedin;
	var $socialtwitter;
	var $socialfoursquare;
	var $socialyahoo;
	var $socialwindowslive;
	
	public function __construct() {
		
		global $edd_slg_model,$edd_slg_social_facebook,
			$edd_slg_social_google,$edd_slg_social_linkedin,$edd_slg_social_twitter,
			$edd_slg_social_yahoo,$edd_slg_social_foursquare,$edd_slg_social_windowslive;
		
		$this->model = $edd_slg_model;
		
		//social class objects
		$this->socialfacebook 	= $edd_slg_social_facebook;
		$this->socialgoogle		= $edd_slg_social_google;
		$this->sociallinkedin 	= $edd_slg_social_linkedin;
		$this->socialtwitter 	= $edd_slg_social_twitter;
		$this->socialyahoo		= $edd_slg_social_yahoo;
		$this->socialfoursquare	= $edd_slg_social_foursquare;
		$this->socialwindowslive = $edd_slg_social_windowslive;
		
	}
	
	/**
	 * Show All Social Login Buttons
	 * 
	 * Handles to show all social login buttons
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_social_login_inner_buttons( $redirect_url = '' ) {
		
		global $edd_options, $post;
		
		// get redirect url from settings
		$login_redirect_url = isset( $edd_options['edd_slg_redirect_url'] ) ? $edd_options['edd_slg_redirect_url'] : '';
		$login_redirect_url = !empty( $redirect_url ) ? $redirect_url : $login_redirect_url; // check redirect url first from shortcode or if checkout page then use cuurent page is redirect url
		
		?>
			<div class="edd-slg-social-wrap">
				<?php 
					//do action to add social login buttons
					do_action( 'edd_slg_checkout_social_login' );
				?>
				<div class="edd-slg-clear"></div>
			</div><!--.edd-slg-social-wrap-->
			
			<div class="edd-slg-login-error"></div><!--edd-slg-login-error-->

			<div class="edd-slg-login-loader">
				<img src="<?php echo EDD_SLG_IMG_URL;?>/social-loader.gif" alt="<?php _e( 'Social Loader', 'eddslg');?>"/>
			</div><!--.edd-slg-login-loader-->
			
			<input type="hidden" class="edd-slg-redirect-url" id="edd_slg_redirect_url" value="<?php echo $login_redirect_url;?>" />
			
		<?php
			
			//enqueue social front script
			wp_enqueue_script( 'edd-slg-public-script' );
	}
	
	/**
	 * Add Social Login Buttons To 
	 * Checkout page
	 * 
	 * Handles to add all social media login
	 * buttons to edd checkout page
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	public function edd_slg_social_login_buttons( $title = '', $redirect_url = '' ) {
		
		global $edd_options, $post;
		
		//check user is logged in to site or not and any single social login button is enable or not
		if( !is_user_logged_in() && edd_slg_check_social_enable() ) {
		
			// get title from settings
			$login_heading = isset( $edd_options['edd_slg_login_heading'] ) ? $edd_options['edd_slg_login_heading'] : '';
			
			/*// get redirect url from settings 
			$defaulturl = isset( $edd_options['edd_slg_redirect_url'] ) && !empty( $edd_options['edd_slg_redirect_url'] ) 
								? $edd_options['edd_slg_redirect_url'] : edd_slg_get_current_page_url();*/
		
			$defaulturl = get_permalink( $post->ID );
			//session create for redirect url 
			EDD()->session->set( 'edd_slg_stcd_redirect_url', $defaulturl );
		
			echo '<fieldset id="edd_slg_social_login" class="edd-slg-social-container">';
				
			if( !empty($login_heading) ) {
			
				echo '<span><legend>' . $login_heading . '</legend></span>';
				
			}
					
			$redirect_url = get_permalink( $post->ID );
			$this->edd_slg_social_login_inner_buttons( $redirect_url);
					
			echo '</fieldset><!--#edd_slg_social_login-->';
			
		}
		
	}
	
	
	/**
	 * Show Facebook Login Button
	 * 
	 * Handles to show facebook social login
	 * button
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_login_facebook() {

		global $edd_options;
		
		//check facebook is enable or not
		if( !empty( $edd_options['edd_slg_enable_facebook'] ) ) {
		
		$fbimgurl = isset( $edd_options['edd_slg_fb_icon_url'] ) && !empty( $edd_options['edd_slg_fb_icon_url'] ) 
						? $edd_options['edd_slg_fb_icon_url'] : EDD_SLG_IMG_URL . '/facebook.png';
	?>
		<div class="edd-slg-login-wrapper">
			<a title="<?php _e( 'Connect with Facebook', 'eddslg');?>" href="javascript:void(0);" class="edd-slg-social-login-facebook">
				<img src="<?php echo $fbimgurl;?>" alt="<?php _e( 'Facebook', 'eddslg');?>" />
			</a>
	<?php
			if( EDD_SLG_FB_APP_ID != '' && EDD_SLG_FB_APP_SECRET != '' ) {
			
				//enqueue FB init script
				wp_enqueue_script( 'facebook' );
				wp_enqueue_script( 'edd-slg-fbinit' );
			}
		?>
		</div>
		<?php
		}
	}
	
	/**
	 * Show Google+ Login Button
	 * 
	 * Handles to show google+ social login
	 * button
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_login_googleplus() {

		global $edd_options;
		
		//check google+ is enable or not
		if( !empty( $edd_options['edd_slg_enable_googleplus'] ) ) {
		
		$gpimgurl = isset( $edd_options['edd_slg_gp_icon_url'] ) && !empty( $edd_options['edd_slg_gp_icon_url'] ) 
						? $edd_options['edd_slg_gp_icon_url'] : EDD_SLG_IMG_URL . '/googleplus.png';
	?>
		<div class="edd-slg-login-wrapper">
			<a title="<?php _e( 'Connect with Google+', 'eddslg');?>" href="javascript:void(0);" class="edd-slg-social-login-googleplus">
				<img src="<?php echo $gpimgurl;?>" alt="<?php _e( 'Google+', 'eddslg');?>" />
			</a>
	<?php
	
			if( EDD_SLG_GP_CLIENT_ID != '' && EDD_SLG_GP_CLIENT_SECRET != '' ) {
			
				$gp_authurl = $this->socialgoogle->edd_slg_get_google_auth_url();
				
				?>
					<input type="hidden" class="edd-slg-social-gp-redirect-url" id="edd_slg_social_gp_redirect_url" name="edd_slg_social_gp_redirect_url" value="<?php echo $gp_authurl; ?>"/>							
				<?php
			
			}
		?>
		</div>
		<?php
		}
	}
	
	/**
	 * Show Linkedin Login Button
	 * 
	 * Handles to show linkedin social login
	 * button
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_login_linkedin() {

		global $edd_options;
		
		//check linkedin is enable or not
		if( !empty( $edd_options['edd_slg_enable_linkedin'] ) ) {
		
		$liimgurl = isset( $edd_options['edd_slg_li_icon_url'] ) && !empty( $edd_options['edd_slg_li_icon_url'] ) 
						? $edd_options['edd_slg_li_icon_url'] : EDD_SLG_IMG_URL . '/linkedin.png';
	?>
		<div class="edd-slg-login-wrapper">
			<a title="<?php _e( 'Connect with LinkedIn', 'eddslg');?>" href="javascript:void(0);" class="edd-slg-social-login-linkedin">
				<img src="<?php echo $liimgurl;?>" alt="<?php _e( 'LinkedIn', 'eddslg');?>" />
			</a>
	<?php
	
			if( EDD_SLG_LI_APP_ID != '' && EDD_SLG_LI_APP_SECRET != '' ) {
			
				$li_authurl = $this->sociallinkedin->edd_slg_linkedin_auth_url();
				
				?>
					<input type="hidden" class="edd-slg-social-li-redirect-url" id="edd_slg_social_li_redirect_url" name="edd_slg_social_li_redirect_url" value="<?php echo $li_authurl; ?>"/>						
				<?php
			
			}
		?>
		</div>
		<?php
		}
	}
	
	/**
	 * Show Twitter Login Button
	 * 
	 * Handles to show twitter social login
	 * button
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_login_twitter() {

		global $edd_options;
		
		//check twitter is enable or not
		if( !empty( $edd_options['edd_slg_enable_twitter'] ) ) {
		
		$twimgurl = isset( $edd_options['edd_slg_tw_icon_url'] ) && !empty( $edd_options['edd_slg_tw_icon_url'] ) 
						? $edd_options['edd_slg_tw_icon_url'] : EDD_SLG_IMG_URL . '/twitter.png';
	?>
		<div class="edd-slg-login-wrapper">
			<a title="<?php _e( 'Connect with Twitter', 'eddslg');?>" href="javascript:void(0);" class="edd-slg-social-login-twitter">
				<img src="<?php echo $twimgurl;?>" alt="<?php _e( 'Twitter', 'eddslg');?>" />
			</a>
	<?php
	
			if( EDD_SLG_TW_CONSUMER_KEY != '' && EDD_SLG_TW_CONSUMER_SECRET != '' ) {
				
				$tw_authurl = $this->socialtwitter->edd_slg_get_twitter_auth_url();
				
				?>
					<input type="hidden" class="edd-slg-social-tw-redirect-url" id="edd_slg_social_tw_redirect_url" name="edd_slg_social_tw_redirect_url" value="<?php echo $tw_authurl; ?>" />
				<?php
			
			}
		?>
		</div>
		<?php
		}
	}
	
	/**
	 * Show Yahoo Login Button
	 * 
	 * Handles to show yahoo social login
	 * button
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_login_yahoo() {

		global $edd_options;
		
		//check yahoo is enable or not
		if( !empty( $edd_options['edd_slg_enable_yahoo'] ) ) {
		
		$yhimgurl = isset( $edd_options['edd_slg_yh_icon_url'] ) && !empty( $edd_options['edd_slg_yh_icon_url'] ) 
						? $edd_options['edd_slg_yh_icon_url'] : EDD_SLG_IMG_URL . '/yahoo.png';
	?>
		<div class="edd-slg-login-wrapper">
			<a title="<?php _e( 'Connect with Yahoo', 'eddslg');?>" href="javascript:void(0);" class="edd-slg-social-login-yahoo">
				<img src="<?php echo $yhimgurl;?>" alt="<?php _e( 'Yahoo', 'eddslg');?>" />
			</a>
	<?php
	
			if( EDD_SLG_YH_CONSUMER_KEY != '' && EDD_SLG_YH_CONSUMER_SECRET != '' && EDD_SLG_YH_APP_ID != '' ) {
			
				$yh_authurl = $this->socialyahoo->edd_slg_get_yahoo_auth_url();
				
				?>
					<input type="hidden" class="edd-slg-social-yh-redirect-url" id="edd_slg_social_yh_redirect_url" name="edd_slg_social_yh_redirect_url" value="<?php echo $yh_authurl;?>"/>						
				<?php
			
			}
		?>
		</div>
		<?php
		}
	}
	
	/**
	 * Show Foursquare Login Button
	 * 
	 * Handles to show foursquare social login
	 * button
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_login_foursquare() {

		global $edd_options;
		
		//check yahoo is enable or not
		if( !empty( $edd_options['edd_slg_enable_foursquare'] ) ) {
		
		$fsimgurl = isset( $edd_options['edd_slg_fs_icon_url'] ) && !empty( $edd_options['edd_slg_fs_icon_url'] ) 
						? $edd_options['edd_slg_fs_icon_url'] : EDD_SLG_IMG_URL . '/foursquare.png';
	?>
		<div class="edd-slg-login-wrapper">
			<a title="<?php _e( 'Connect with Foursquare', 'eddslg');?>" href="javascript:void(0);" class="edd-slg-social-login-foursquare">
				<img src="<?php echo $fsimgurl;?>" alt="<?php _e( 'Foursquare', 'eddslg');?>" />
			</a>
	<?php
	
			if( EDD_SLG_FS_CLIENT_ID != '' && EDD_SLG_FS_CLIENT_SECRET != '' ) {
			
				$fs_authurl = $this->socialfoursquare->edd_slg_get_foursquare_auth_url();
				
				?>
					<input type="hidden" class="edd-slg-social-fs-redirect-url" id="edd_slg_social_fs_redirect_url" name="edd_slg_social_fs_redirect_url" value="<?php echo $fs_authurl;?>"/>						
				<?php
			
			}
		?>
		</div>
		<?php
		}
	}
	
	/**
	 * Show Windows Live Login Button
	 * 
	 * Handles to show windowlive social login
	 * button
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	
	public function edd_slg_login_windowslive() {

		global $edd_options;
		
		//check yahoo is enable or not
		if( !empty( $edd_options['edd_slg_enable_windowslive'] ) ) {
		
		$wlimgurl = isset( $edd_options['edd_slg_wl_icon_url'] ) && !empty( $edd_options['edd_slg_wl_icon_url'] ) 
						? $edd_options['edd_slg_wl_icon_url'] : EDD_SLG_IMG_URL . '/windowslive.png';
	?>
		<div class="edd-slg-login-wrapper">
			<a title="<?php _e( 'Connect with Windows Live', 'eddslg');?>" href="javascript:void(0);" class="edd-slg-social-login-windowslive">
				<img src="<?php echo $wlimgurl;?>" alt="<?php _e( 'Windows Live', 'eddslg');?>" />
			</a>
	<?php
	
			if( EDD_SLG_WL_CLIENT_ID != '' && EDD_SLG_WL_CLIENT_SECRET != '' ) {
			
				$wl_authurl = $this->socialwindowslive->edd_slg_get_wl_auth_url();
				
				?>
					<input type="hidden" class="edd-slg-social-wl-redirect-url" id="edd_slg_social_wl_redirect_url" name="edd_slg_social_wl_redirect_url" value="<?php echo $wl_authurl;?>"/>						
				<?php
			
			}
		?>
		</div>
		<?php
		}
	}	
}
?>