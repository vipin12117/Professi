<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class FES_Frontend_Application extends FES_Render_Form {
	private static $_instance;
	
	function __construct() {
		add_shortcode( 'fes-application', array(
			 $this,
			'application_shortcode' 
		) );
		// ajax requests
		add_action( 'wp_ajax_fes_submit_post', array(
			 $this,
			'submit_post' 
		) );
		add_action( 'wp_ajax_nopriv_fes_submit_post', array(
			 $this,
			'submit_post' 
		) );
		add_filter( 'comments_open', array(
			$this,
			'force_comments_close_on_upload',
		), 10, 2 );
	}
	
	public static function init() {
		if ( !self::$_instance ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function application_shortcode( $post_id = NULL ) {
		ob_start();
		$this->render_form( EDD_FES()->fes_options->get_option( 'fes-application-form' ), $post_id );
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public function submit_post() {
		check_ajax_referer( 'fes-form_add' );
		$form_id       = isset( $_POST[ 'form_id' ] ) ? intval( $_POST[ 'form_id' ] ) : 0;
		if ($form_id != EDD_FES()->fes_options->get_option( 'fes-application-form' ) ){
			return;
		}
		@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		$form_vars     = $this->get_input_fields( $form_id );
		$form_settings = get_post_meta( $form_id, 'fes-form_settings', true );
		list( $user_vars, $taxonomy_vars, $meta_vars ) = $form_vars;
		$user_id  = get_current_user_id();
		$userdata = array(
			 'ID' => $user_id 
		);
		if ( $this->search( $user_vars, 'name', 'first_name' ) ) {
			$userdata[ 'first_name' ] = $_POST[ 'first_name' ];
		}
		if ( $this->search( $user_vars, 'name', 'last_name' ) ) {
			$userdata[ 'last_name' ] = $_POST[ 'last_name' ];
		}
		if ( $this->search( $user_vars, 'name', 'nickname' ) ) {
			$userdata[ 'nickname' ] = $_POST[ 'nickname' ];
		}
		if ( $this->search( $user_vars, 'name', 'display_name' ) ) {
			$userdata[ 'display_name' ] = $_POST[ 'display_name' ];
		}
		if ( $this->search( $user_vars, 'name', 'user_url' ) ) {
			$userdata[ 'user_url' ] = $_POST[ 'user_url' ];
		}
		if ( $this->search( $user_vars, 'name', 'user_email' ) ) {
			$userdata[ 'user_email' ] = $_POST[ 'user_email' ];
		}
		if ( $this->search( $user_vars, 'name', 'description' ) ) {
			$userdata[ 'description' ] = $_POST[ 'description' ];
		}
		$userdata = apply_filters( 'fes_application_vars', $userdata, $form_id, $form_settings );
		$user_id  = wp_update_user( $userdata );
		if ( $user_id ) {
			// update meta fields
			$this->update_user_meta( $meta_vars, $user_id );
			do_action( 'fes_update_application', $user_id, $form_id, $form_settings );
		}
		//make post
		$user = new WP_User(get_current_user_id());
		$postarr = array(
			'post_type' => 'fes-applications',
			'post_status' => 'publish',
			'post_author' => $user->ID,
			'post_title' => $user->first_name.' '.$user->last_name,
			'post_content' => '',
			'post_excerpt' => '' 
		);
		if( ! (bool) EDD_FES()->fes_options->get_option( 'edd_fes_auto_approve_vendors' ) ) {
			$post_id = wp_insert_post( $postarr );
			update_post_meta($post_id,'fes_user',$user->ID);
			update_post_meta($post_id,'fes_status','pending');
		
			EDD_FES()->emails->notify_user_new_app($user->ID);
			EDD_FES()->emails->notify_admin_new_app($user->ID);
			$user->set_role('pending_vendor');
		}
		else{
			$user->set_role('frontend_vendor');
			$user->add_cap( 'fes_is_vendor');
			EDD_FES()->emails->fes_notify_user_app_accepted(get_current_user_id());
		}

		$response = array(
		    'success' => true,
			'redirect_to' => get_permalink( EDD_FES()->fes_options->get_option( 'vendor-dashboard-page' ) ),
			'message' => __( 'Application Submitted', 'edd_fes' ),
			'is_post' => true 
		);
		$response = apply_filters( 'fes_add_post_redirect', $response, $post_id, $form_id, $form_settings );
		echo json_encode( $response );
		exit;
	}
	
	public static function update_user_meta( $meta_vars, $user_id ) {
		// prepare meta fields
		list( $meta_key_value, $multi_repeated, $files ) = self::prepare_meta_fields( $meta_vars );

		// set featured image if there's any
		if ( isset( $_POST[ 'fes_files' ][ 'avatar' ] ) ) {
			foreach( $_POST[ 'fes_files' ][ 'avatar' ] as $attachment_id ) {
				fes_update_avatar( $user_id, $attachment_id );
			}
		}
		// save all custom fields
		foreach ( $meta_key_value as $meta_key => $meta_value ) {
			update_user_meta( $user_id, $meta_key, $meta_value );
		}
		// save any multicolumn repeatable fields
		foreach ( $multi_repeated as $repeat_key => $repeat_value ) {
			// first, delete any previous repeatable fields
			delete_user_meta( $user_id, $repeat_key );
			// now add them
			foreach ( $repeat_value as $repeat_field ) {
				add_user_meta( $user_id, $repeat_key, $repeat_field );
			}
		} //foreach
		// save any files attached
		foreach ( $files as $file_input ) {
			// delete any previous value
			delete_user_meta( $user_id, $file_input[ 'name' ] );
			foreach ( $file_input[ 'value' ] as $attachment_id ) {
				add_user_meta( $user_id, $file_input[ 'name' ], $attachment_id );
			}
		}
	}

	public function force_comments_close_on_upload( $open, $post_id ) {
		global $post, $is_fes_upload;

		// Forces comments to be closed on the upload form, related to #146 and #127

		if ( $is_fes_upload )
			$open = false;

		return $open;
	}
}
new FES_Frontend_Application;