<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Handles generic Admin functionality and AJAX requests.
 *
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */
class EDD_Currency_Admin {
	
	/**
	 * EDD Currency Converter model object
	 * 
	 * @var object
	 * @since 1.0.0
	 **/
	public $model;
	
	/**
	 * EDD Currency Converter render object
	 * 
	 * @var object
	 * @since 1.0.0
	 **/
	public $render;
	
	/**
	 * EDD Currency Converter scripts object
	 * 
	 * @var object
	 * @since 1.0.0
	 **/
	public $scripts;
	
	public function __construct() {	
	
		global $edd_currency_model, $edd_currency_scripts,
			$edd_currency_render;
		
		$this->model 	= $edd_currency_model;
		$this->scripts 	= $edd_currency_scripts;
		$this->render 	= $edd_currency_render;
	}
	
	
	/**
	 * Validate Settings
	 *
	 * Handles to validate settings
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function edd_currency_settings_validate( $input ) {
		
		global $edd_options;
		
		$input['exchange_app_id'] 		= $this->model->edd_currency_escape_slashes_deep( $input['exchange_app_id'] );
		$input['exchange_rates_cache'] 	= $this->model->edd_currency_escape_slashes_deep( $input['exchange_rates_cache'] );
		
		//when user is changing the exchange application id need to reset exchange rates which are stored in database
		if( isset( $input['exchange_app_id'] ) && isset( $edd_options['exchange_app_id'] )
			&& $edd_options['exchange_app_id'] != $input['exchange_app_id'] ) {
			delete_transient( 'edd_currency_open_exchange_rates' );
		}
		
		return $input;
	}
	
	/**
	 * Add Top Level Menu Page
	 *
	 * Runs when the admin_menu hook fires and adds a new
	 * top level admin page and menu item
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	
	function edd_currency_admin_menu() {

		$edd_currency_currency_converter_page = add_submenu_page( 'edit.php?post_type=download', __('Currency Converter','eddcurrency'), __('Currency Converter','eddcurrency'), 'manage_options', 'edd_currency_converter', array($this,'edd_currency_add_submenu_list_table_page') );
		
		$edd_currency_admin_add_page = add_submenu_page( '', __('Add Currency','eddcurrency'), __('','eddcurrency'), 'manage_options', 'edd_currency_manage', array($this,'edd_currency_add_submenu_page') );
		
		add_action( "admin_head-$edd_currency_admin_add_page", array( $this->scripts, 'edd_currency_add_currency_page_load_scripts' ) );
	}
	
	/**
	 * List of all Currencies
	 *
	 * Handles Function to listing all currencies
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	function edd_currency_add_submenu_list_table_page() {
		
		include_once( EDD_CURRENCY_ADMIN . '/forms/edd-currency-list.php' );
		
	}
	
	/**
	 * Adding Admin Sub Menu Page
	 *
	 * Handles Function to adding add data form
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	
	function edd_currency_add_submenu_page() {
		
		include_once( EDD_CURRENCY_ADMIN . '/forms/edd-currency-add-edit.php' );
		
	}
	
	/**
	 * Add action admin init
	 * 
	 * Handles add and edit functionality of currency
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	
	function edd_currency_admin_init() {
		
		include_once( EDD_CURRENCY_ADMIN . '/forms/edd-currency-save.php' );
		
		//Check save order functionality
		if( isset( $_GET['edd_currency_save_order'] ) && !empty( $_GET['edd_currency_save_order'] )
			&& isset( $_GET['edd_currency_sort_order'] ) && !empty( $_GET['edd_currency_sort_order'] ) && is_array( $_GET['edd_currency_sort_order'] ) ) {
			
			$redirect_url = add_query_arg( array( 'post_type' => 'download', 'page' => 'edd_currency_converter' ), admin_url( 'edit.php' ) );
				
			$sort_order = $_GET['edd_currency_sort_order'];
				
			$current_page 	= 1;
			$per_page 		= EDD_CURRENCY_PER_PAGE;
			
			if( isset( $_GET['currency_paged'] ) && !empty( $_GET['currency_paged'] ) ) {
				$current_page = $_GET['currency_paged'];
				//$redirect_url = add_query_arg( array( 'paged' => $current_page ), $redirect_url );
			}
			
			$start_page = ( $current_page - 1 ) * $per_page;
			foreach ( $sort_order as $sort_key => $currency_id ) {
				$start_page = $start_page + 1;
				if( !empty( $currency_id ) ) {
					$currency_post = array(
										      'ID'          => $currency_id,
										      'menu_order' 	=> $start_page
										  );
					wp_update_post( $currency_post );
				}
			}
			
			$redirect_url = add_query_arg( array( 'message' => '4' ), $redirect_url );
			
			if( isset( $_GET['per_page'] ) && !empty( $_GET['per_page'] ) ) {
				
				$redirect_url = add_query_arg( array( 'per_page' => $_GET['per_page'] ), $redirect_url );
			}
			
			wp_redirect( $redirect_url );
			exit;
		}
		
		//Check reset order functionality
		if( isset( $_GET['edd_currency_reset_order'] ) && !empty( $_GET['edd_currency_reset_order'] ) ) {
			
			$redirect_url = add_query_arg( array( 'post_type' => 'download', 'page' => 'edd_currency_converter' ), admin_url( 'edit.php' ) );
			
			//get sort order
			$edd_currency_orders = get_option( 'edd_currency_sort_order' );
			if( !empty( $edd_currency_orders ) && is_array( $edd_currency_orders ) ) { // Check order option is not empty and is array
				
				foreach ( $edd_currency_orders as $edd_currency_id => $edd_currency_order_no ) {
					
					$currency_post = array(
										      'ID'          => $edd_currency_id,
										      'menu_order' 	=> $edd_currency_order_no
										  );
					wp_update_post( $currency_post );
				}
				
				$redirect_url = add_query_arg( array( 'message' => '5' ), $redirect_url );
			}
			
			if( isset( $_GET['per_page'] ) && !empty( $_GET['per_page'] ) ) {
				
				$redirect_url = add_query_arg( array( 'per_page' => $_GET['per_page'] ), $redirect_url );
			}
			
			wp_redirect( $redirect_url );
			exit;
		}
	}
	
	/**
	 * Bulk Delete
	 * 
	 * Handles bulk delete functinalities of currency
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	function edd_currency_admin_bulk_delete() {
				
		// Code for followed post
		if( ( (isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) || (isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' ) )
			&& isset($_GET['page']) && $_GET['page'] == 'edd_currency_converter'
			&& isset($_GET['currency']) && !empty( $_GET['currency'] ) ) {
			
			//get bulk currency array from $_GET
			$action_on_id = $_GET['currency'];
			
			// check if we dont get array of IDs
			if( !is_array( $action_on_id ) ) {
				$action_on_id = array( $action_on_id );
			}
			
			//if there is multiple checkboxes are checked then call delete in loop
			foreach ( $action_on_id as $id ) {
				
				//parameters for delete function
				$args = array ( 'id' => $id );
				
				//delete record from database
				$this->model->edd_currency_bulk_delete( $args );
					
			}
			
			$redirect_url = add_query_arg( array( 'post_type' => 'download', 'page' => 'edd_currency_converter', 'message' => '3' ), admin_url( 'edit.php' ) );
			wp_redirect( $redirect_url );
			exit;
		}
		
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add filter to add settings
		add_filter( 'edd_settings_extensions', array( $this->model, 'edd_currency_settings') );
		
		//add filter to add settings
		add_filter( 'edd_settings_extensions_sanitize', array( $this, 'edd_currency_settings_validate') );
		
		//add new admin menu page
		add_action( 'admin_menu', array( $this, 'edd_currency_admin_menu' ) );
		
		//add admin init for saving data
		add_action( 'admin_init' , array( $this, 'edd_currency_admin_init' ) );
		
		//add admin init for bulk delete functionality
		add_action( 'admin_init' , array( $this, 'edd_currency_admin_bulk_delete' ) );
		
		//add admin notice for display settings message
		add_action( 'admin_notices' , array( $this->render, 'edd_currency_admin_notice' ) );
		
	}
}
?>