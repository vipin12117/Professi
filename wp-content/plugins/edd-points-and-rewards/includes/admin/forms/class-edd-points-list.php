<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Points List Page
 *
 * The html markup for the Points list
 * 
 * @package Easy Digital Downloads - Points and Rewards
 * @since 1.0.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class edd_points_log extends WP_List_Table {

	public $model, $scripts,$edd_points_model;
	
	function __construct(){
	
        global $edd_points_model, $edd_points_scripts;
		
		$this->model = $edd_points_model;
		$this->scripts = $edd_points_scripts;
                
        //Set parent defaults
        parent::__construct( array(
						            'singular'  => 'pointlog',     //singular name of the listed records
						            'plural'    => 'pointslog',    //plural name of the listed records
						            'ajax'      => false        //does this table support ajax?
						        ) );   
		
    }
	
    /**
	 * Displaying Points
	 *
	 * Does prepare the data for displaying the Points in the table.
	 * 
	 * @package Easy Digital Downloads - Points and Rewards
	 * @since 1.0.0
	 */	
	function edd_points_display_points() {
	
		//if search is call then pass searching value to function for displaying searching values
		$search = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';
		$monthyear = isset($_REQUEST['m']) ? $_REQUEST['m'] : '';
		
		$id_search = isset( $_GET['userid'] ) ? $_GET['userid'] : '';
		$event = isset( $_GET['edd_event_type'] ) ? $_GET['edd_event_type'] : '';
		$points_arr = array();
		
		if(isset($search) && !empty($search)){
			//in case of search make parameter for retriving search data
			$points_arr['search'] = $search;
		}		

		if(isset($monthyear) && !empty($monthyear)){
			//in case of month search make parameter for retriving search data
			$points_arr['monthyear'] = $monthyear;
		}		

		if(isset($id_search) && !empty($id_search)){
			$points_arr['author']	=	$id_search;
		}		
		
		if(isset($_GET['edd_event_type']) && !empty($_GET['edd_event_type'])) {
			$points_arr['event']	= $_GET['edd_event_type'];
		}
		
		//call function to retrive data from table
		$data = $this->model->edd_points_get_points( $points_arr );
		
		$item = array();
			
		foreach ( $data as $key => $value ){
			
			$customerid 			= $value['post_author'];
			$userdata 				= get_user_by( 'id', $customerid );
			
			if($userdata) {
				$user_id = $userdata->ID;
				$item['user_id'] = $user_id;
				$item['user_name'] = $userdata->user_email;
			} 
			
			$data[$key]['customer'] = $this->column_user($item);
			$data[$key]['points']   = get_post_meta( $value['ID'], '_edd_log_userpoint', true );
			$data[$key]['event']	= get_post_meta( $value['ID'], '_edd_log_events', true );
			$data[$key]['date'] 	= $value['post_date'];
		}
		
		return $data;
	}
	/**
	 * User Column Data
	 * 
	 * Handles to show user column
	 *
	 * @package Easy Digital Downloads - Points and Rewards
	 * @since 1.0.0
	 **/
	function column_user($item){
    	
		
		$display_name = $item['user_name'];
     	
     	$user_id = $item['user_id'];
    	$user = isset( $user_id ) && !empty( $user_id ) ? $user_id : $item['useremail'];
    	
    	$userlink = add_query_arg(	array(	'post_type' => 'download','page' => 'edd-points-log','userid' => $user ), admin_url('edit.php'));
     	return '<a href="'.$userlink.'">'.$display_name.'</a>';
    }
	/**
	 * Manage column data
	 *
	 * Default Column for listing table
	 * 
	 * @package Easy Digital Downloads - Points and Rewards
	 * @since 1.0.0
	 */
	function column_default( $item, $column_name ){
	
        switch( $column_name ){
            case 'customer':
            case 'points':
				return $item[ $column_name ];
            case 'date':
				return $this->model->edd_points_log_time(strtotime( $item['post_date'] ) );
            case 'event' :
            	if( $item[ $column_name ] == 'manual' ) {
					$event_description = $item['post_content'];
				} else {
					$event_description = $this->model->edd_points_get_events( $item[ $column_name ] );
				}
				return $event_description;
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
	
	/**
	 * Add Filter for Sorting
	 * 
	 * Handles to add filter for sorting
	 * in listing
	 * 
	 * @package Easy Digital Downloads - Points and Rewards
	 * @since 1.0.0
	 **/
    function extra_tablenav( $which ) {
    	
    	if( $which == 'top' ) {
    		
    		echo '<div class="alignleft actions edd-points-dropdown-wrapper">';
    			
				$all_events = array(
											'earned_purchase' 	=> __( 'Order Placed', 'eddpoints' ),
											'redeemed_purchase' => __( 'Order Redeem', 'eddpoints' ),
											//'cancel' 			=> __( 'Cancel Order', 'eddpoints' ),
											//'review' 			=> __( 'Product Review', 'eddpoints' ),
											'signup' 			=> __( 'Account Signup', 'eddpoints' ),
											'manual' 			=> __( 'Manual', 'eddpoints' ),
										);
				$checked = '';
		?>
				<select id="edd_points_userid" name="userid">
					<option value=""><?php _e( 'Show all customer', 'eddpoints' ); ?></option>
		<?php
					if( isset( $_GET['userid'] ) && !empty( $_GET['userid'] ) ) {
						$user_data = get_user_by( 'id', $_GET['userid'] );
						echo '<option value="' . $user_data->ID . '" selected="selected">' . $user_data->display_name . ' (#' . $user_data->ID . ' &ndash; ' . sanitize_email( $user_data->user_email ) . ')' . '</option>';
					}
			
		?>
				</select>
				<select id="edd_event_type" name="edd_event_type">
					
					<option value=""><?php _e( 'Show All Event Types', 'eddpoints' ); ?></option>
		<?php
					foreach ( $all_events as $event_key => $event_value ) {
						$selected = selected( isset( $_GET['edd_event_type'] ) ? $_GET['edd_event_type'] : '', $event_key, false );
						echo '<option value="' . $event_key . '" ' . $selected . '>' . $event_value . '</option>';
					}
		?>
				</select>
		<?php
			$this->months_dropdown( EDD_POINTS_LOG_POST_TYPE );
    		submit_button( __( 'Filter', 'eddpoints' ), 'button', false, false, array( 'id' => 'post-query-submit' ) );
			echo '</div>';
    	}
    }
    /**
     * Display Columns
     *
     * Handles which columns to show in table
     * 
	 * @package Easy Digital Downloads - Points and Rewards
	 * @since 1.0.0
     */
	function get_columns(){
	
        $columns = array(
					            'customer'	=> __( 'Customer','eddpoints' ),
					            'points'	=> __( 'Points','eddpoints' ),
					            'event'		=> __( 'Event','eddpoints' ),
					            'date'		=> __( 'Date','eddpoints' )
					        );
        return $columns;
    }
	
    /**
     * Sortable Columns
     *
     * Handles soratable columns of the table
     * 
	 * @package Easy Digital Downloads - Points and Rewards
	 * @since 1.0.0
     */
	function get_sortable_columns() {
		
		
        $sortable_columns = array(
						            'customer'	=> array( 'customer', true ),     //true means its already sorted
						            'points'	=> array( 'points', true ),
						            'event'		=> array( 'event', true ),
						            'date'		=> array( 'date', true ),
						        );
        return $sortable_columns;
    }
	
	function no_items() {
		
		//message to show when no records in database table
		_e( 'No points log found.','eddpoints' );
		
	}
	
	
	function prepare_items() {
        
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 10;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
         /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        //$this->process_bulk_action();
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
		$data = $this->edd_points_display_points();
		
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        if(isset($data) && is_array($data) && !empty($data)){
        	usort($data, 'usort_reorder');
        }else {
        	$data = '';
        }
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        if(isset($data) && is_array($data) && !empty($data)){
        	$data = array_slice($data,(($current_page-1)*$per_page),$per_page);        
        }
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
									            'total_items' => $total_items,                  //WE have to calculate the total number of items
									            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
									            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
									        ) );
    }
    
}

//Create an instance of our package class...
$PointsListTable = new edd_points_log();
	
//Fetch, prepare, sort, and filter our data...
$PointsListTable->prepare_items();

?>
<div class="wrap">
    <h2><?php _e( 'Points Log','eddpoints' ); ?></h2>
    
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="Points-filter" method="get">
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <input type="hidden" name="post_type" value="download" />
      
        <!-- Search Title -->
        <?php $PointsListTable->search_box( __( 'Search' ,'eddpoints'),'edd_points_search' ); ?>
         
        <!-- Now we can render the completed list table -->
        <?php $PointsListTable->display() ?>
    </form>
    
</div><!--.wrap-->