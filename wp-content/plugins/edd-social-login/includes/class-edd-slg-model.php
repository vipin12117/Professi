<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Model Class
 *
 * Handles generic plugin functionality.
 *
 * @package Easy Digital Downloads - Social Login
 * @since 1.0.0
 */
class EDD_Slg_Model {
	
	public function __construct() {
	
	}
	
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package  Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	public function edd_slg_escape_attr($data){
		return esc_attr(stripslashes($data));
	}
	
	/**
	 * Strip Slashes From Array
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	public function edd_slg_escape_slashes_deep($data = array(),$flag=false){
			
		if($flag != true) {
			$data = $this->edd_slg_nohtml_kses($data);
		}
		$data = stripslashes_deep($data);
		return $data;
	}
	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	public function edd_slg_nohtml_kses($data = array()) {
		
		if ( is_array($data) ) {
			
			$data = array_map(array($this,'edd_slg_nohtml_kses'), $data);
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}	
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 * 
	 */
	public function edd_slg_object_to_array($result)
	{
	    $array = array();
	    foreach ($result as $key=>$value)
	    {	
	        if (is_object($value))
	        {
	            $array[$key]=$this->edd_slg_object_to_array($value);
	        } else {
	        	$array[$key]=$value;
	        }
	    }
	    return $array;
	}
	
	/**
	 * Create User
	 *
	 * Function to add connected users to the WordPress users database
	 * and add the role subscriber
	 *
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */

	public function edd_slg_add_wp_user( $criteria ) {

		global $wp_version, $edd_options;

		$prefix = 'edd_user_';
		$username = $prefix . wp_rand(100, 9999999);
		while ( username_exists($username) ){ // avoid duplicate user name
			$username = $prefix . wp_rand(100, 9999999);
		}
		$name = $criteria['name'];
		$first_name = $criteria['first_name'];
		$last_name = $criteria['last_name'];
		$password = wp_generate_password(12, false);
		$email = $criteria['email'];
		$wp_id = 0;
		
		//create the WordPress user
		if ( version_compare($wp_version, '3.1', '<') ) {
			require_once( ABSPATH . WPINC . '/registration.php' );
		}
		
		//check user id is exist or not
		if ( email_exists($email) == false ) {
			
			$wp_id = wp_create_user( $username, $password, $email );
			
			if( !empty( $wp_id ) ) { //if user is created then update some data
				$role = 'subscriber';
				$user = new WP_User( $wp_id );
				$user->set_role( $role );
				
				if( isset($edd_options['edd_slg_enable_notification']) && !empty($edd_options['edd_slg_enable_notification']) ) { // check enable email notification from settings
					wp_new_user_notification( $wp_id, $password );
				}
			}
			
		} else {
			//get user from email
			$userdata = get_user_by( 'email', $email );
			
			if( !empty( $userdata ) ) { //check user is exit or not
				$wp_id = $userdata->ID;
			}			
		}
		return $wp_id;
	}
	
	/**
	 * Get Social Connected Users Count
	 * 
	 * Handles to return connected user counts
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	public function edd_slg_social_get_users( $args = array() ) {
		
		$userargs = array();
		$metausr1 = array();
		
		if( isset( $args['network'] ) && !empty( $args['network'] ) ) { //check network is set or not
			$metausr1['key'] = 'edd_slg_social_user_connect_via';
			$metausr1['value'] = $args['network'];
		}
		
		if( !empty($metausr1) ) { //meta query
			$userargs['meta_query'] = array( $metausr1 );
		}
		
		//get users data
		$result = new WP_User_Query($userargs);
		
		if ( isset( $args['getcount'] ) && !empty( $args['getcount'] ) ) { //get count of users
			$users = $result->total_users;
		} else {
			//retrived data is in object format so assign that data to array for listing
			$users = $this->edd_slg_object_to_array($users->results);
		}
	
		return $users;
	}
	
	/**
	 * Register Settings
	 * 
	 * Handels to add settings in settings page
	 * 
	 * @package Easy Digital Downloads - Social Login
	 * @since 1.0.0
	 */
	public function edd_slg_settings( $settings ) {
		
		$select_fblanguage = array( 'en_US' => __( 'English', 'eddslg' ), 'af_ZA' => __( 'Afrikaans', 'eddslg' ), 'sq_AL' => __( 'Albanian', 'eddslg' ), 'ar_AR' => __( 'Arabic', 'eddslg' ), 'hy_AM' => __( 'Armenian', 'eddslg' ), 'eu_ES' => __( 'Basque', 'eddslg' ), 'be_BY' => __( 'Belarusian', 'eddslg' ), 'bn_IN' => __( 'Bengali', 'eddslg' ), 'bs_BA' => __( 'Bosanski', 'eddslg' ), 'bg_BG' => __( 'Bulgarian', 'eddslg' ), 'ca_ES' => __( 'Catalan', 'eddslg' ), 'zh_CN' => __( 'Chinese', 'eddslg' ), 'cs_CZ' => __( 'Czech', 'eddslg' ), 'da_DK' => __( 'Danish', 'eddslg' ), 'fy_NL' => __( 'Dutch', 'eddslg' ), 'eo_EO' => __( 'Esperanto', 'eddslg' ), 'et_EE' => __( 'Estonian', 'eddslg' ), 'et_EE' => __( 'Estonian', 'eddslg' ), 'fi_FI' => __( 'Finnish', 'eddslg' ), 'fo_FO' => __( 'Faroese', 'eddslg' ), 'tl_PH' => __( 'Filipino', 'eddslg' ), 'fr_FR' => __( 'French', 'eddslg' ), 'gl_ES' => __( 'Galician', 'eddslg' ), 'ka_GE' => __( 'Georgian', 'eddslg' ), 'de_DE' => __( 'German', 'eddslg' ), 'zh_CN' => __( 'Greek', 'eddslg' ), 'he_IL' => __( 'Hebrew', 'eddslg' ), 'hi_IN' => __( 'Hindi', 'eddslg' ), 'hr_HR' => __( 'Hrvatski', 'eddslg' ), 'hu_HU' => __( 'Hungarian', 'eddslg' ), 'is_IS' => __( 'Icelandic', 'eddslg' ), 'id_ID' => __( 'Indonesian', 'eddslg' ), 'ga_IE' => __( 'Irish', 'eddslg' ), 'it_IT' => __( 'Italian', 'eddslg' ), 'ja_JP' => __( 'Japanese', 'eddslg' ), 'ko_KR' => __( 'Korean', 'eddslg' ), 'ku_TR' => __( 'Kurdish', 'eddslg' ), 'la_VA' => __( 'Latin', 'eddslg' ), 'lv_LV' => __( 'Latvian', 'eddslg' ), 'fb_LT' => __( 'Leet Speak', 'eddslg' ), 'lt_LT' => __( 'Lithuanian', 'eddslg' ), 'mk_MK' => __( 'Macedonian', 'eddslg' ), 'ms_MY' => __( 'Malay', 'eddslg' ), 'ml_IN' => __( 'Malayalam', 'eddslg' ), 'nl_NL' => __( 'Nederlands', 'eddslg' ), 'ne_NP' => __( 'Nepali', 'eddslg' ), 'nb_NO' => __( 'Norwegian', 'eddslg' ), 'ps_AF' => __( 'Pashto', 'eddslg' ), 'fa_IR' => __( 'Persian', 'eddslg' ), 'pl_PL' => __( 'Polish', 'eddslg' ), 'pt_PT' => __( 'Portugese', 'eddslg' ), 'pa_IN' => __( 'Punjabi', 'eddslg' ), 'ro_RO' => __( 'Romanian', 'eddslg' ), 'ru_RU' => __( 'Russian', 'eddslg' ), 'sk_SK' => __( 'Slovak', 'eddslg' ), 'sl_SI' => __( 'Slovenian', 'eddslg' ), 'es_LA' => __( 'Spanish', 'eddslg' ), 'sr_RS' => __( 'Srpski', 'eddslg' ), 'sw_KE' => __( 'Swahili', 'eddslg' ), 'sv_SE' => __( 'Swedish', 'eddslg' ), 'ta_IN' => __( 'Tamil', 'eddslg' ), 'te_IN' => __( 'Telugu', 'eddslg' ), 'th_TH' => __( 'Thai', 'eddslg' ), 'tr_TR' => __( 'Turkish', 'eddslg' ), 'uk_UA' => __( 'Ukrainian', 'eddslg' ), 'vi_VN' => __( 'Vietnamese', 'eddslg' ), 'cy_GB' => __( 'Welsh', 'eddslg' ), 'zh_TW' => __( 'Traditional Chinese Language', 'eddslg' ) );
		
		$edd_slg_settings = array(
				array(
					'id'	=> 'edd_slg_settings',
					'name'	=> '<strong>' . __( 'Social Login Options', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				
				//General Settings
				array(
					'id'	=> 'edd_slg_general_settings',
					'name'	=> '<strong>' . __( 'General Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login General Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_login_heading',
					'name'	=> __( 'Social Login Title:', 'eddslg' ),
					'desc'	=> __( 'Enter Social Login Title.', 'eddslg' ),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_enable_notification',
					'name'	=> __( 'Enable Email Notification:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to notify admin and user when user is registered by social media.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_redirect_url',
					'name'	=> __( 'Redirect URL:', 'eddslg' ),
					'desc'	=> __( 'Enter a redirect URL for users after they login with social media. The URL must start with', 'eddslg' ).' http://',
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				
				//Facebbok Settings
				array(
					'id'	=> 'edd_slg_facebook_settings',
					'name'	=> '<strong>' . __( 'Facebook Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login Facebook Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_facebook_desc',
					'name'	=> __( 'Facebook Application:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'facebook_desc'
				),
				array(
					'id'	=> 'edd_slg_enable_facebook',
					'name'	=> __( 'Enable Facebook:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to enable facebook social login registration.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_fb_app_id',
					'name'	=> __( 'Facebook App ID/API Key:', 'eddslg' ),
					'desc'	=> __( 'Enter Facebook API Key.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_fb_app_secret',
					'name'	=> __( 'Facebook App Secret:', 'eddslg' ),
					'desc'	=> __( 'Enter Facebook App Secret.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'		=> 'edd_slg_fb_language',
					'name'		=> __( 'Facebook API Locale:', 'eddslg' ),
					'desc'		=> __( 'Select the language for Facebook. With this option, you can explicitly tell which language you want to use for communicating with Facebook.', 'eddslg' ),
					'type'		=> 'select',
					'options'	=> $select_fblanguage
				),
				array(
					'id'	=> 'edd_slg_fb_icon_url',
					'name'	=> __( 'Custom Facebook Icon:', 'eddslg' ),
					'desc'	=> __( 'If you want to use your own Facebook Icon, upload one here.', 'eddslg' ),
					'type'	=> 'upload',
					'size'	=> 'regular'
				),
				//Google+ Settings
				array(
					'id'	=> 'edd_slg_googleplus_settings',
					'name'	=> '<strong>' . __( 'Google+ Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login Google+ Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_googleplus_desc',
					'name'	=> __( 'Google+ Application:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'googleplus_desc'
				),
				array(
					'id'	=> 'edd_slg_enable_googleplus',
					'name'	=> __( 'Enable Google+:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to enable google+ social login registration.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_gp_client_id',
					'name'	=> __( 'Google+ Client ID:', 'eddslg' ),
					'desc'	=> __( 'Enter Google+ Client ID.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_gp_client_secret',
					'name'	=> __( 'Google+ Client Secret:', 'eddslg' ),
					'desc'	=> __( 'Enter Google+ Client Secret.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_gp_redirect_url',
					'name'	=> __( 'Google+ Callback URL:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'gp_redirect_url',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_gp_icon_url',
					'name'	=> __( 'Custom Google+ Icon:', 'eddslg' ),
					'desc'	=> __( 'If you want to use your own Google+ Icon, upload one here.', 'eddslg' ),
					'type'	=> 'upload',
					'size'	=> 'regular'
				),
				
				//LinkedIn Settings
				array(
					'id'	=> 'edd_slg_linkedin_settings',
					'name'	=> '<strong>' . __( 'LinkedIn Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login LinkedIn Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_linkedin_desc',
					'name'	=> __( 'LinkedIn Application:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'linkedin_desc'
				),
				array(
					'id'	=> 'edd_slg_enable_linkedin',
					'name'	=> __( 'Enable LinkedIn:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to enable LinkedIn social login registration.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_li_app_id',
					'name'	=> __( 'LinkedIn App ID/API Key:', 'eddslg' ),
					'desc'	=> __( 'Enter LinkedIn App ID/API Key.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_li_app_secret',
					'name'	=> __( 'LinkedIn App Secret:', 'eddslg' ),
					'desc'	=> __( 'Enter LinkedIn App Secret.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_li_icon_url',
					'name'	=> __( 'Custom LinkedIn Icon:', 'eddslg' ),
					'desc'	=> __( 'If you want to use your own LinkedIn Icon, upload one here.', 'eddslg' ),
					'type'	=> 'upload',
					'size'	=> 'regular'
				),
				
				//twitter Settings
				array(
					'id'	=> 'edd_slg_twitter_settings',
					'name'	=> '<strong>' . __( 'Twitter Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login Twitter Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_twitter_desc',
					'name'	=> __( 'Twitter Application:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'twitter_desc'
				),
				array(
					'id'	=> 'edd_slg_enable_twitter',
					'name'	=> __( 'Enable Twitter:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to enable Twitter social login registration.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_tw_consumer_key',
					'name'	=> __( 'Twitter Consumer Key:', 'eddslg' ),
					'desc'	=> __( 'Enter Twitter Consumer Key.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_tw_consumer_secret',
					'name'	=> __( 'Twitter Consumer Secret:', 'eddslg' ),
					'desc'	=> __( 'Enter Twitter Consumer Secret.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_tw_icon_url',
					'name'	=> __( 'Custom Twitter Icon:', 'eddslg' ),
					'desc'	=> __( 'If you want to use your own Twitter Icon, upload one here.', 'eddslg' ),
					'type'	=> 'upload',
					'size'	=> 'regular'
				),
				
				//yahoo Settings
				array(
					'id'	=> 'edd_slg_yahoo_settings',
					'name'	=> '<strong>' . __( 'Yahoo Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login Yahoo Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_yahoo_desc',
					'name'	=> __( 'Yahoo Application:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'yahoo_desc'
				),
				array(
					'id'	=> 'edd_slg_enable_yahoo',
					'name'	=> __( 'Enable Yahoo:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to enable Yahoo social login registration.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_yh_consumer_key',
					'name'	=> __( 'Yahoo Consumer Key:', 'eddslg' ),
					'desc'	=> __( 'Enter Yahoo Consumer Key.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_yh_consumer_secret',
					'name'	=> __( 'Yahoo Consumer Secret:', 'eddslg' ),
					'desc'	=> __( 'Enter Yahoo Consumer Secret.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_yh_app_id',
					'name'	=> __( 'Yahoo App Id:', 'eddslg' ),
					'desc'	=> __( 'Enter Yahoo App Id.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_yh_redirect_url',
					'name'	=> __( 'Yahoo Callback URL:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'yh_redirect_url',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_yh_icon_url',
					'name'	=> __( 'Custom Yahoo Icon:', 'eddslg' ),
					'desc'	=> __( 'If you want to use your own Yahoo Icon, upload one here.', 'eddslg' ),
					'type'	=> 'upload',
					'size'	=> 'regular'
				),
				
				//Foursquare Settings
				array(
					'id'	=> 'edd_slg_foursquare_settings',
					'name'	=> '<strong>' . __( 'Foursquare Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login Foursquare Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_foursquare_desc',
					'name'	=> __( 'Foursquare Application:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'foursquare_desc'
				),
				array(
					'id'	=> 'edd_slg_enable_foursquare',
					'name'	=> __( 'Enable Foursquare:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to enable Foursquare social login registration.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_fs_client_id',
					'name'	=> __( 'Foursquare Client ID:', 'eddslg' ),
					'desc'	=> __( 'Enter Foursquare Client ID.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_fs_client_secret',
					'name'	=> __( 'Foursquare Client Secret:', 'eddslg' ),
					'desc'	=> __( 'Enter Foursquare Client Secret.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_fs_icon_url',
					'name'	=> __( 'Custom Foursquare Icon:', 'eddslg' ),
					'desc'	=> __( 'If you want to use your own Foursquare Icon, upload one here.', 'eddslg' ),
					'type'	=> 'upload',
					'size'	=> 'regular'
				),
				
				//Windows Live Settings
				array(
					'id'	=> 'edd_slg_windowslive_settings',
					'name'	=> '<strong>' . __( 'Windows Live Settings', 'eddslg' ) . '</strong>',
					'desc'	=> __( 'Configure Social Login Windows Live Settings', 'eddslg' ),
					'type'	=> 'header'
				),
				array(
					'id'	=> 'edd_slg_windowslive_desc',
					'name'	=> __( 'Windows Live Application:', 'eddslg' ),
					'desc'	=> '',
					'type'	=> 'windowslive_desc'
				),
				array(
					'id'	=> 'edd_slg_enable_windowslive',
					'name'	=> __( 'Enable Windows Live:', 'eddslg' ),
					'desc'	=> __( 'Check this box, if you want to enable Windows Live social login registration.', 'eddslg' ),
					'type'	=> 'checkbox'
				),
				array(
					'id'	=> 'edd_slg_wl_client_id',
					'name'	=> __( 'Windows Live Client ID:', 'eddslg' ),
					'desc'	=> __( 'Enter Windows Live Client ID.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_wl_client_secret',
					'name'	=> __( 'Windows Live Client Secret:', 'eddslg' ),
					'desc'	=> __( 'Enter Windows Live Client Secret.', 'eddslg'),
					'type'	=> 'text',
					'size'	=> 'regular'
				),
				array(
					'id'	=> 'edd_slg_wl_icon_url',
					'name'	=> __( 'Custom Windows Live Icon:', 'eddslg' ),
					'desc'	=> __( 'If you want to use your own Windows Live Icon, upload one here.', 'eddslg' ),
					'type'	=> 'upload',
					'size'	=> 'regular'
				)
				
			);
		
		return array_merge( $settings, $edd_slg_settings );
		
	}
}
?>