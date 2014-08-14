<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to 
 * different functions 
 * 
 * @package Easy Digital Downloads - Social Login
 * @since 1.0.0
 *
 */
	/**
	 * All Social Deals Networks
	 * 
	 * Handles to return all social networks
	 * names
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	function edd_slg_social_networks() {
		
		$socialnetworks = array(
									'facebook'	=>	__( 'Facebook', 'eddslg' ),
									'twitter'	=>	__( 'Twitter', 'eddslg' ),
									'googleplus'=>	__( 'Google+', 'eddslg' ),
									'linkedin'	=>	__( 'LinkedIn', 'eddslg' ),
									'yahoo'		=>	__( 'Yahoo', 'eddslg' ),
									'foursquare'=>	__( 'Foursquare', 'eddslg' ),
									'windowslive'=>	__( 'Windows Live', 'eddslg' ),
								);
		return apply_filters( 'edd_slg_social_networks', $socialnetworks );
		
	}
	
	/**
	 * Get Social Network Sorted List
	 * as per saved in options
	 * 
	 * Handles to return social networks sorted
	 * array to list in page
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	function edd_slg_get_sorted_social_network() {
		
		global $edd_options;
		
		$edd_social_order = get_option( 'edd_social_order' );
		
		$socials = edd_slg_social_networks();
		
		if( !isset( $edd_social_order ) || empty( $edd_social_order ) ){
			return $socials;
		}
		$sorted_socials = $edd_social_order;
		$return = array();
		for( $i = 0; $i < count( $socials ); $i++ ){
			$return[$sorted_socials[$i]] = $socials[$sorted_socials[$i]];
		}
		return apply_filters( 'edd_slg_sorted_social_networks', $return );
	}
	
	/**
	 * Initialize some needed variables
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	function edd_slg_initialize() {
		
		global $edd_options;
		
		//facebook variable initialization
		$fb_app_id = isset( $edd_options['edd_slg_fb_app_id'] ) ? $edd_options['edd_slg_fb_app_id'] : '';
		$fb_app_secret = isset( $edd_options['edd_slg_fb_app_secret'] ) ? $edd_options['edd_slg_fb_app_secret'] : '';
		
		if( !defined( 'EDD_SLG_FB_APP_ID' ) ){
			define( 'EDD_SLG_FB_APP_ID', $fb_app_id );
		}
		if( !defined( 'EDD_SLG_FB_APP_SECRET' ) ){
			define( 'EDD_SLG_FB_APP_SECRET', $fb_app_secret );
		}
		
		//google+ variable initialization
		$gp_client_id = isset( $edd_options['edd_slg_gp_client_id'] ) ? $edd_options['edd_slg_gp_client_id'] : '';
		$gp_client_secret = isset( $edd_options['edd_slg_gp_client_secret'] ) ? $edd_options['edd_slg_gp_client_secret'] : '';
		
		if( !defined( 'EDD_SLG_GP_CLIENT_ID' ) ){
			define( 'EDD_SLG_GP_CLIENT_ID', $gp_client_id );
		}
		if( !defined( 'EDD_SLG_GP_CLIENT_SECRET' ) ){
			define( 'EDD_SLG_GP_CLIENT_SECRET', $gp_client_secret );
		}
		if( !defined( 'EDD_SLG_GP_REDIRECT_URL' ) ) {
			$googleurl = add_query_arg( 'eddslg', 'google', site_url() );
			define( 'EDD_SLG_GP_REDIRECT_URL', $googleurl );
		}
		
		//linkedin variable initialization
		$li_app_id = isset( $edd_options['edd_slg_li_app_id'] ) ? $edd_options['edd_slg_li_app_id'] : '';
		$li_app_secret = isset( $edd_options['edd_slg_li_app_secret'] ) ? $edd_options['edd_slg_li_app_secret'] : '';
		
		if( !defined( 'EDD_SLG_LI_APP_ID' ) ){
			define( 'EDD_SLG_LI_APP_ID', $li_app_id );
		}
		if( !defined( 'EDD_SLG_LI_APP_SECRET' ) ){
			define( 'EDD_SLG_LI_APP_SECRET', $li_app_secret );
		}
		
		// For LinkedIn Port http / https
		if( !defined( 'LINKEDIN_PORT_HTTP' ) ) { //http port value
		 	define( 'LINKEDIN_PORT_HTTP', '80' );
		}
		if( !defined( 'LINKEDIN_PORT_HTTP_SSL' ) ) { //ssl port value
		  	define( 'LINKEDIN_PORT_HTTP_SSL', '443' );
		}
		
		//twitter variable initialization
		$tw_consumer_key = isset( $edd_options['edd_slg_tw_consumer_key'] ) ? $edd_options['edd_slg_tw_consumer_key'] : '';
		$tw_consumer_secrets = isset( $edd_options['edd_slg_tw_consumer_secret'] ) ? $edd_options['edd_slg_tw_consumer_secret'] : '';
		
		if( !defined( 'EDD_SLG_TW_CONSUMER_KEY' ) ) {
			define( 'EDD_SLG_TW_CONSUMER_KEY', $tw_consumer_key );
		}
		if( !defined( 'EDD_SLG_TW_CONSUMER_SECRET' ) ) {
			define( 'EDD_SLG_TW_CONSUMER_SECRET', $tw_consumer_secrets );
		}
		
		//yahoo variable initialization
		$yh_consumer_key = isset( $edd_options['edd_slg_yh_consumer_key'] ) ? $edd_options['edd_slg_yh_consumer_key'] : '';
		$yh_consumer_secret = isset( $edd_options['edd_slg_yh_consumer_secret'] ) ? $edd_options['edd_slg_yh_consumer_secret'] : '';
		$yh_app_id = isset( $edd_options['edd_slg_yh_app_id'] ) ? $edd_options['edd_slg_yh_app_id'] : '';
		
		if( !defined( 'EDD_SLG_YH_CONSUMER_KEY' ) ){
			define( 'EDD_SLG_YH_CONSUMER_KEY', $yh_consumer_key );
		}
		if( !defined( 'EDD_SLG_YH_CONSUMER_SECRET' ) ){
			define( 'EDD_SLG_YH_CONSUMER_SECRET', $yh_consumer_secret );
		}
		if( !defined( 'EDD_SLG_YH_APP_ID' ) ){
			define( 'EDD_SLG_YH_APP_ID', $yh_app_id );
		}
		if( !defined( 'EDD_SLG_YH_REDIRECT_URL' ) ) {
			$yahoourl = add_query_arg( 'eddslg', 'yahoo', site_url() );
			define( 'EDD_SLG_YH_REDIRECT_URL', $yahoourl );
		}
		
		//foursquare variable initialization
		$fs_client_id = isset( $edd_options['edd_slg_fs_client_id'] ) ? $edd_options['edd_slg_fs_client_id'] : '';
		$fs_client_secrets = isset( $edd_options['edd_slg_fs_client_secret'] ) ? $edd_options['edd_slg_fs_client_secret'] : '';
		
		if( !defined( 'EDD_SLG_FS_CLIENT_ID' ) ) {
			define( 'EDD_SLG_FS_CLIENT_ID', $fs_client_id );
		}
		if( !defined( 'EDD_SLG_FS_CLIENT_SECRET' ) ) {
			define( 'EDD_SLG_FS_CLIENT_SECRET', $fs_client_secrets );
		}
		if( !defined( 'EDD_SLG_FS_REDIRECT_URL' ) ) {
			$fsredirecturl = add_query_arg( 'eddslg', 'foursquare', site_url() );
			define( 'EDD_SLG_FS_REDIRECT_URL', $fsredirecturl );
		}
		
		//windows live variable initialization
		$wl_client_id = isset( $edd_options['edd_slg_wl_client_id'] ) ? $edd_options['edd_slg_wl_client_id'] : '';
		$wl_client_secrets = isset( $edd_options['edd_slg_wl_client_secret'] ) ? $edd_options['edd_slg_wl_client_secret'] : '';
		
		if( !defined( 'EDD_SLG_WL_CLIENT_ID' ) ) {
			define( 'EDD_SLG_WL_CLIENT_ID', $wl_client_id );
		}
		if( !defined( 'EDD_SLG_WL_CLIENT_SECRET' ) ) {
			define( 'EDD_SLG_WL_CLIENT_SECRET', $wl_client_secrets );
		}
		if( !defined( 'EDD_SLG_WL_REDIRECT_URL' ) ) {
			$wlredirecturl = add_query_arg( 'eddslg', 'windowslive', site_url() );
			define( 'EDD_SLG_WL_REDIRECT_URL', $wlredirecturl );
		}
	}
	
	/**
	 * Checkout Page URL
	 * 
	 * Handles to return checkout page url
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	function edd_slg_send_on_checkout_page( $queryarg = array() ) {
		
		global $edd_options;
		
		$sendcheckout = get_permalink($edd_options['purchase_page']);
	
		$sendcheckouturl = add_query_arg( $queryarg, $sendcheckout );
		
		wp_redirect( apply_filters( 'edd_slg_checkout_page_redirect', $sendcheckouturl, $queryarg ) );
		exit;
	}
	/**
	 * Check Any One Social Media
	 * Login is enable or not
	 * 
	 * Handles to Check any one social 
	 * media login is enable or not
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	function edd_slg_check_social_enable() {
		
		global $edd_options;
		
		$return = false;
		//check if any social is activated or not
		if( !empty( $edd_options['edd_slg_enable_facebook'] ) || !empty( $edd_options['edd_slg_enable_googleplus'] ) || 
			!empty( $edd_options['edd_slg_enable_linkedin'] ) || !empty( $edd_options['edd_slg_enable_twitter'] ) || 
			!empty( $edd_options['edd_slg_enable_yahoo'] ) 	  || !empty( $edd_options['edd_slg_enable_windowslive'] ) ) {
			$return = true;
		}
		return $return;
	}
	
	/**
	 * Google Redirect URL
	 * 
	 * Handle to display google redirect url description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_gp_redirect_url_callback( $args ) {
		
		echo '<code><strong>' . EDD_SLG_GP_REDIRECT_URL . '</strong></code>';
	}
	
	
	/**
	 * Yahoo Redirect URL
	 * 
	 * Handle to display yahoo redirect url description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_yh_redirect_url_callback( $args ) {
		
		echo '<code><strong>' . EDD_SLG_YH_REDIRECT_URL . '</strong></code>';
	}
	
	/**
	 * Windows Live Redirect URL
	 * 
	 * Handle to display windows live redirect url description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_wl_redirect_url_callback( $args ) {
		
		echo '<code><strong>' . site_url() . '</strong></code>';
	}
	
	/**
	 * Facebook Description
	 * 
	 * Handle to display facebook description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_facebook_desc_callback( $args ) {
		
		?>
			<ol>
				<li>
					<a href="https://www.facebook.com/developers/createapp.php" target="_blank"><?php _e( 'Create your Facebook Application', 'eddslg' ); ?></a>
				</li>
				<li>
					<?php echo __( 'Look for the', 'eddslg').' <strong>'.__('Site URL', 'eddslg').'</strong> '.__('field in the Web Site tab and enter your site URL in this field:', 'eddslg' ); ?> <code><strong><?php echo get_bloginfo( 'url' ); ?></strong></code>
				</li>
				<li>
					<?php echo __( 'After this, go to the', 'eddslg').' <a href="https://developers.facebook.com/apps" target="_blank">'.__('Facebook Application List page', 'eddslg').'</a> '.__('and select your newly created application', 'eddslg' ); ?>
				</li>
				<li>
					<?php echo __( 'Copy and paste your', 'eddslg').' <strong>'.__('App ID/API Key', 'eddslg').'</strong> '.__('and', 'eddslg').' <strong>'.__('App Secret', 'eddslg').'</strong> '.__('in to the fields below.', 'eddslg' ); ?>
				</li>
			</ol>
		
		<?php
	}
	
	/**
	 * Google+ Description
	 * 
	 * Handle to display google+ description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_googleplus_desc_callback( $args ) {
		
	?>
		<ol>
			<li>
				<a href="https://code.google.com/apis/console/" target="_blank"><?php _e( 'Create your Google+ Application', 'eddslg' ); ?></a>
			</li>
			<li>
				<?php echo __( 'Normally you get automatically redirected to the', 'eddslg').' "'.__('Services', 'eddslg').'" '.__('tab. If not, then and click on it on the top left hand side of the page. Scroll down the page until you can see the "Google+ API" and turn it ', 'eddslg').'<b>'.__('ON', 'eddslg').'</b>. '.__('You\'ll be redirected to their terms pages. Agree to them until you get redirected to the Google project page.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'On the top left hand side, click on the "API Access" link. Then click on the big blue button "Create an oAuth 2.0 Client ID". Enter your details on the first window and click on Next. Then choose Web application and enter the following URL underneath "Your site or hostname": ', 'eddslg' ); ?> <code><strong><?php echo get_bloginfo( 'url' ); ?></strong></code>
			</li>
			<li>
				<?php echo __( 'Then click on the', 'eddslg').' "'.__('Create client ID', 'eddslg').'" '.__('button.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'Copy and paste your', 'eddslg').' <b>'.__('Client ID', 'eddslg').'</b> '.__('and', 'eddslg').' <b>'.__('Client secret', 'eddslg').'</b> '.__('in to the fields below.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'Go back to the Goolge API Console. Within the API Access go to the section', 'eddslg').' "'.__('Client ID for web applications', 'eddslg').'" '.__('and click on', 'eddslg').' "'.__('Edit settings', 'eddslg').'" '.__('on the right hand side. Make sure, that the URL under', 'eddslg').' "'.__('Authorized redirect URIs', 'eddslg').'" '.__('is exactly the same as shown below in', 'eddslg').' <b>'.__('Google+ Callback URL', 'eddslg').'</b>.'; ?>
			</li>
		</ol>
	<?php	
	}
		
	/**
	 * LinkedIn Description
	 * 
	 * Handle to display linkedin description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_linkedin_desc_callback( $args ) {
		
		?>
			<ol>
				<li>
					<a href="https://www.linkedin.com/secure/developer" target="_blank"><?php _e( 'Create your LinkedIn Application', 'eddslg' );?></a>
				</li>
				<li>
					<?php echo __( 'Click on', 'eddslg').' "'.__('Add New Application', 'eddslg').'". '.__('Then enter the information in to the form. You only need to enter the ones with a star, which are required. Then agree to their terms and click on Add Application.', 'eddslg' );?>
				</li>
				<li>
					<?php echo __( 'Copy and paste your', 'eddslg').' <b>'.__('App ID/API Key', 'eddslg').'</b> '.__('and', 'eddslg').' <b>'.__('Secret Key', 'eddslg').'</b> '.__('in to the fields below.', 'eddslg' ); ?>
				</li>
			</ol>
		<?php
	}
		
	/**
	 * Twitter Description
	 * 
	 * Handle to display twitter description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_twitter_desc_callback( $args ) {
		
		?>
			<ol>
				<li>
					<a href="https://dev.twitter.com/apps/new" target="_blank"><?php _e( 'Create your Twitter Application', 'eddslg' );?></a>
				<ul class="twtlist">
					<li><?php echo __( 'If you\'re not currently logged in, log-in with the Twitter username and password which you want associated with this site.', 'eddslg' ); ?></li>
					<li><?php echo __( 'The name of your application can not include the word <b>Twitter</b>. Use the name of your web site.', 'eddslg' ); ?></li>
					<li><?php echo __( 'You can use whatever you want for your Application Description.', 'eddslg' ); ?></li>
					<li><?php echo __( 'The Website and Callback URL should be:', 'eddslg' ); ?><code><strong><?php bloginfo( 'url' ); ?></strong></code></li>
				</ul>
				</li>
				<li><?php echo __( 'Go to the', 'eddslg' ).' <b>'.__('Settings', 'eddslg' ).'</b> '.__('tab on your Twitter application page.', 'eddslg' ); ?>
					<ul class="twtlist">
						<li><?php echo '<strong>'.__( 'Important', 'eddslg' ).':</strong> '.__('You NEED to select', 'eddslg' ).' <b>'.__('Read and Write', 'eddslg' ).'</b> '.__('for the Application Type. Make sure, that Twitter saved the settings, especially', 'eddslg' ).' <b>'.__('Read and Write', 'eddslg' ).'</b> '.__('correctly.', 'eddslg' ); ?></li>
						<li><?php echo __( 'Update the application settings.', 'eddslg' ); ?></li>
					</ul>
				</li>
				<li>
					<?php echo __( 'Copy and paste your', 'eddslg' ).' <b>'.__('Consumer Key', 'eddslg' ).'</b> '.__('and', 'eddslg' ).' <b>'.__('Consumer Secret', 'eddslg' ).'</b> '.__('into the fields below.', 'eddslg' ); ?>
				</li>
		<?php
	}
		
	/**
	 * Yahoo Description
	 * 
	 * Handle to display yahoo description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_yahoo_desc_callback( $args ) {
		?>
			<ol>
				<li>
					<a href="https://developer.apps.yahoo.com/projects/" target="_blank"><?php _e( 'Create your Yahoo Application', 'eddslg' ); ?></a>
				</li>
				<li>
					<?php echo __( 'On the top right hand side, hover on your name and click', 'eddslg' ).' <b>'.__('My Projects', 'eddslg' ).'</b> '.__('link. Then click on the', 'eddslg' ).' <b>'.__('Create a Project', 'eddslg' ).'</b> '.__('button. Choose', 'eddslg' ).' <b>'.__('Web-based', 'eddslg' ).'</b> '.__('in Application Type and enter the following URL  underneath', 'eddslg' ).' <b>'.__('Home Page URL', 'eddslg' ).'</b> '.__('is exactly the same as shown below in', 'eddslg' ).' <b>'.__('Yahoo Callback URL', 'eddslg' ).'</b> ';?>
				</li>
				<li>
					<?php echo __( 'Then click on the', 'eddslg' ).' <b>'.__('Create client ID', 'eddslg' ).'</b> '.__('button.', 'eddslg');?>
				</li>
				<li>
					<?php echo __( 'Undereath the', 'eddslg' ).' <b>'.__('Permissions', 'eddslg' ).'</b> '.__('choose', 'eddslg' ).' <b>'.__('Social Directory', 'eddslg' ).'</b> '.__('then choose', 'eddslg' ).' <b>'.__('Read/Write Public and Private', 'eddslg' ).'</b> '.__('undereath', 'eddslg' ).' <b>'.__('Social Directory (Profiles)', 'eddslg' ).'</b> '.__('and then click on', 'eddslg' ).' <b>'.__('Save and Change Consumer Key', 'eddslg' ).'</b>.';?>
				</li>
				<li>
					<?php echo __( 'Copy and paste your', 'eddslg' ).' <b>'.__('Consumer Key', 'eddslg' ).'</b> '.__('and', 'eddslg' ).' <b>'.__('Consumer Secret', 'eddslg' ).'</b> '.__('in to the fields below.', 'eddslg' );?>
				</li>
			</ol>
		<?php
	}
	
	/**
	 * Foursquare Description
	 * 
	 * Handle to display foursquare description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_foursquare_desc_callback( $args ) {
		
	?>
		<ol>
			<li>
				<a href="https://foursquare.com/developers/apps" target="_blank"><?php _e( 'Create your Foursquare Application', 'eddslg' ); ?></a>
			</li>
			<li>
				<?php echo __( 'Click on', 'eddslg' ).' <b>'.__('CREATE A NEW APP', 'eddslg' ).'</b> '.__('to create a new application. Then enter the information in to the form.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'Enter the following URL underneath', 'eddslg' ).' <b>'.__('Redirect URI(s)', 'eddslg' ).'</b> '.__('by comma seperated if you have multiple domains for the application : ', 'eddslg' ); ?> <code><strong><?php echo get_bloginfo( 'url' ); ?></strong></code>
			</li>
			<li>
				<?php echo __( 'Then click on the', 'eddslg' ).' <b>'.__('SAVE CHANGES', 'eddslg' ).'</b> '.__('button.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'Copy and paste your', 'eddslg' ).' <b>'.__('Client ID', 'eddslg' ).'</b> '.__('and', 'eddslg' ).' <b>'.__('Client secret', 'eddslg' ).'</b> '.__('in to the fields below and save the settings.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'If you have already any application created then edit that application and insert following URL ', 'eddslg' ); ?><code><strong><?php echo get_bloginfo( 'url' ); ?></strong></code>
				<?php echo __( 'underneath', 'eddslg' ).' <b>'.__('Redirect URI(s)', 'eddslg' ).'</b> '.__('by seperating the comma.', 'eddslg'); ?>
			</li>
		</ol>
		
	<?php
		
	}
	
	/**
	 * Windows Live Description
	 * 
	 * Handle to display windowslive description in settings
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	function edd_windowslive_desc_callback( $args ) {
		
	?>
		<ol>
			<li>
				<a href="https://account.live.com/developers/applications/index" target="_blank"><?php _e( 'Create a your Windows Live Application', 'eddslg' ); ?></a>
			</li>
			<li>
				<?php echo __( 'Click on', 'eddslg' ).' <b>'.__('Create application', 'eddslg' ).'</b> '.__('to create a new application. Then enter name of your application and click on', 'eddslg' ).' <b>'.__('I accept', 'eddslg' ).'</b> '.__('button.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'Enter the following URL underneath', 'eddslg' ).' <b>'.__('Redirect domain', 'eddslg' ).'</b>'; ?> <code><strong><?php echo get_bloginfo( 'url' ); ?></strong></code>
			</li>
			<li>
				<?php echo __( 'Then click on the', 'eddslg' ).' <b>'.__('Save', 'eddslg' ).'</b> '.__('button.', 'eddslg' ); ?>
			</li>
			<li>
				<?php echo __( 'Copy and paste your', 'eddslg' ).' <b>'.__('Client ID', 'eddslg' ).'</b> '.__('and', 'eddslg' ).' <b>'.__('Client secret', 'eddslg' ).'</b> '.__('in to the fields below.', 'eddslg' ); ?>
			</li>
		</ol>
		
	<?php
		
	}
	
	/**
	 * Current Page URL
	 *
	 * @package  Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	function edd_slg_get_current_page_url(){
		
		$curent_page_url = remove_query_arg( array( 'oauth_token', 'oauth_verifier' ), edd_get_current_page_url() );
		return $curent_page_url;
	}
	
?>