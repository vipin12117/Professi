<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class FES_Install {
	public function init() {
		$db_version = EDD_FES()->fes_options->get_option( 'db_version' );
		if ( $db_version == fes_plugin_version ){
			return;
		}
		else{
			EDD_FES()->setup->register_post_type();
			$this->install_or_update_fes();
		}
	}
	
	/**
	 * Create a page
	 *
	 * @access public
	 * @return void
	 * @param mixed   $slug         Slug for the new page
	 * @param mixed   $option       Option name to store the page's ID
	 * @param string  $page_title   (optional) (default: '') Title for the new page
	 * @param string  $page_content (optional) (default: '') Content for the new page
	 * @param int     $post_parent  (optional) (default: 0) Parent for the new page
	 */
	public function create_page( $slug, $page_title = '', $page_content = '', $post_parent = 0 ) {
		global $wpdb, $wp_version;
		$page_id = EDD_FES()->fes_options->get_option( $slug . '-page' );
		if ( $page_id > 0 && get_post( $page_id ) ) {
			return;
		}
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;", $slug ) );
		if ( $page_found ) {
			if ( !$page_id ) {
				EDD_FES()->fes_options->update_option( $slug . '-page', $page_found );
				return;
			}
			return;
		}
		$page_data = array(
			 'post_status' => 'publish',
			'post_type' => 'page',
			'post_author' => 1,
			'post_name' => $slug,
			'post_title' => $page_title,
			'post_content' => $page_content,
			'post_parent' => $post_parent,
			'comment_status' => 'closed' 
		);
		$page_id   = wp_insert_post( $page_data );
		EDD_FES()->fes_options->update_option( $slug . '-page', $page_id );
		return;
	}
	
	/**
	 * Create a post
	 *
	 * @access public
	 * @return void
	 * @param mixed   $slug         Slug for the new post
	 * @param string  $page_title   (optional) (default: '') Title for the new post
	 * @param int     $post_parent  (optional) (default: 0) Parent for the new post
	 */
	public function create_post( $slug, $page_title = '' ) {
		global $wpdb, $wp_version;
		$page_id = EDD_FES()->fes_options->get_option( $slug );
		if ( $page_id > 0 && get_post( $page_id ) ) {
			return;
		}
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;", $slug ) );
		if ( $page_found ) {
			if ( !$page_id ) {
				EDD_FES()->fes_options->update_option( $slug, $page_found );
				return;
			}
			return;
		}
		$page_data = array(
			 'post_status' => 'publish',
			'post_type' => 'fes-forms',
			'post_author' => 1,
			'post_title' => $page_title 
		);
		$page_id   = wp_insert_post( $page_data );
		EDD_FES()->fes_options->update_option( $slug, $page_id );
		return;
	}
	private function save_default_values() {
		EDD_FES()->fes_options->update_option( 'dashboard-page-template', 'This is the vendor dashboard. Add welcome text or any other information that is applicable to your vendors.' );
	}
	
	private function create_new_pages() {
		$this->create_page( 'vendor-dashboard', __( 'Vendor Dashboard', 'edd_fes' ), '[fes_vendor_dashboard]' );
		$this->create_page( 'vendor', __( 'Vendor', 'edd_fes' ), '[downloads]' );
		$this->create_post( 'fes-submission-form', __( 'Submission Form Editor', 'edd_fes' ) );
		$this->create_post( 'fes-profile-form', __( 'Profile Form Editor', 'edd_fes' ) );
		$this->create_post( 'fes-application-form', __( 'Application Form Editor', 'edd_fes' ) );
	}
	
	public function install_or_update_fes() {
		global $wpdb, $wp_version, $wp_rewrite;
		$version = EDD_FES()->fes_options->get_option( 'db_version' );
		switch ( $version ) {
			case '1.0':
			case '1.0.1':
			case '1.1':			
			case '1.1.5':
			case '1.1.6':
			case '1.1.9':
				// Remove 2 old pages
				wp_delete_post( EDD_FES()->fes_options->get_option( 'add_new_product_page' ) );
				wp_delete_post( EDD_FES()->fes_options->get_option( 'profile_page' ) );
				// Add The Two Forms
				$this->create_post( 'fes-submission-form', __( 'Submission Form Editor', 'edd_fes' ) );
				$this->create_post( 'fes-profile-form', __( 'Profile Form Editor', 'edd_fes' ) );
				$this->create_post( 'fes-application-form', __( 'Application Form Editor', 'edd_fes' ) );
				// Add The New Vendors Page
				$this->create_page( 'vendor', __( 'Vendor', 'edd_fes' ), '[downloads]' );
				// Defaults
				$this->save_default_values();
				if ( EDD_FES()->fes_options->get_option( 'vendor-dashboard_page' ) != null && EDD_FES()->fes_options->get_option( 'vendor-dashboard_page' ) ) {
				EDD_FES()->fes_options->update_option( 'vendor-dashboard-page', EDD_FES()->fes_options->get_option( 'vendor-dashboard_page' ) );
				}
				set_transient( '_edd_fes_activation_redirect', true, 30 );
				break;
			case '2.0.1':
			case '2.0.2':			
			case '2.0.3':
			case '2.0.4':
			case '2.0.5':
				if ( EDD_FES()->fes_options->get_option( 'vendor-dashboard_page' ) != null && EDD_FES()->fes_options->get_option( 'vendor-dashboard_page' ) ) {
				EDD_FES()->fes_options->update_option( 'vendor-dashboard-page', EDD_FES()->fes_options->get_option( 'vendor-dashboard_page' ) );
				}
				$this->create_post( 'fes-application-form', __( 'Application Form Editor', 'edd_fes' ) );
				set_transient( '_edd_fes_activation_redirect', true, 30 );
				break;
			case '2.1':
				$this->create_post( 'fes-application-form', __( 'Application Form Editor', 'edd_fes' ) );
				break;
			default:
				// Add The Two Forms
				$this->create_post( 'fes-submission-form', __( 'Submission Form Editor', 'edd_fes' ) );
				$this->create_post( 'fes-profile-form', __( 'Profile Form Editor', 'edd_fes' ) );
				$this->create_post( 'fes-application-form', __( 'Application Form Editor', 'edd_fes' ) );
				// Add The New Vendors Page
				$this->create_page( 'vendor', __( 'Vendor', 'edd_fes' ), '[downloads]' );
				// Defaults
				$this->save_default_values();
				set_transient( '_edd_fes_activation_redirect', true, 30 );
				break;
		}
		EDD_FES()->fes_options->update_option( 'db_version', fes_plugin_version );
	}
}