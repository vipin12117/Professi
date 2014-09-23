<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Currency List Page
 *
 * The html markup for the product list
 * 
 * @package Easy Digital Downloads - Currency Converter
 * @since 1.0.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class Edd_Currency_List extends WP_List_Table {

	public $model, $per_page;
	
	function __construct(){
	
        global $edd_currency_model, $page;
                
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'currency',     //singular name of the listed records
							            'plural'    => 'currencies',    //plural name of the listed records
							            'ajax'      => false        //does this table support ajax?
							        ) );   
		
		$this->model = $edd_currency_model;
		
		// Add per page functionality
		$per_page = isset( $_REQUEST['per_page'] ) ? intval( $_REQUEST['per_page'] ) : '';
		$this->per_page = !empty( $per_page ) ? $per_page : EDD_CURRENCY_PER_PAGE;
		
    }
    
    /**
	 * Displaying Prodcuts
	 *
	 * Does prepare the data for displaying the currencies in the table.
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */	
	function display_currencies() {
	
		//if search is call then pass searching value to function for displaying searching values
		$search = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';
		
		//in case of search make parameter for retriving search data
		$search_arr = array(
									'search'	=>	$search,
									'order'		=>	'ASC',
									'orderby'	=>	'menu_order',
								);
		
		if( isset( $_REQUEST['paged'] ) && !empty( $_REQUEST['paged'] ) ) {
			$search_arr['paged'] = $_REQUEST['paged'];
		}
		
		//call function to retrive data from table
		$data = $this->model->edd_currency_get_currency_post_data( $search_arr );
		$resultdata = array();
		
		foreach ($data as $key => $value) {
			
			$resultdata[$key]['ID'] 			= $value['ID'];
			$resultdata[$key]['post_title'] 	= $value['post_title'];
			$resultdata[$key]['post_content'] 	= $value['post_content'];
			$resultdata[$key]['menu_order'] 	= $value['menu_order'];
				
			$resultdata[$key]['symbol'] 		= get_post_meta($value['ID'],'_edd_currency_symbol',true);
			$resultdata[$key]['open_ex_rate'] 	= '';
			$resultdata[$key]['post_drag'] 		= '<img class="edd-currency-drag" src="'.EDD_CURRENCY_IMG_URL.'/drag.png" alt="'.__('Drag','eddcurrency').'" />';
			$custom_rate 						= get_post_meta($value['ID'],'_edd_currency_custom_rate',true);
			$resultdata[$key]['custom_rate'] 	= !empty( $custom_rate ) ? $custom_rate : __( 'N/A', 'eddcurrency' );
			$resultdata[$key]['post_date'] 		= date_i18n( get_option('date_format'). ' '. get_option('time_format') ,strtotime($value['post_date']));
			
		}
		return $resultdata;
	}
	
	/**
	 * Mange column data
	 *
	 * Default Column for listing table
	 * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
	function column_default( $item, $column_name ){
	
		global $edd_options;
		
        switch( $column_name ){
            case 'post_content':
            	return $item[ $column_name ] . '<input type="hidden" name="edd_currency_sort_order[]" value="' . $item[ 'ID' ] . '" />';
            case 'post_drag':
            case 'post_title':
            case 'symbol' :
            case 'custom_rate' :
            case 'post_date':
				return $item[ $column_name ];
            case 'open_ex_rate' :
				if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] != 'custom_rates' ) {
				  	
					$open_exchange_rates = edd_currency_get_open_exchange_rates();
				
				   	if( isset( $open_exchange_rates['rates'][$item[ 'post_title' ]] ) ) {
				    	return $open_exchange_rates['rates'][$item[ 'post_title' ]];
				   	}
				}
				return __( 'N/A', 'eddcurrency' );
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
	
   
    /**
     * Manage Edit/Delete Link
     * 
     * Does to show the edit and delete link below the column cell
     * function name should be column_{field name}
     * For ex. I want to put Edit/Delete link below the post title 
     * so i made its name is column_post_title
     * 
     * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
     */
    function column_post_title($item){
    
        $actions = array(
            'edit'      => sprintf('<a class="edd-currency-edit" href="edit.php?post_type=download&page=%s&action=%s&edd_currency_id=%s">'.__('Edit', 'eddcurrency').'</a>','edd_currency_manage','edit',$item['ID']),
            'delete'    => sprintf('<a class="edd-currency-delete" href="edit.php?post_type=download&page=%s&action=%s&currency[]=%s">'.__('Delete', 'eddcurrency').'</a>',$_REQUEST['page'],'delete',$item['ID']),
         );
         
        //Return the title contents	        
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['post_title'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     *
     * Handles which columns to show in table
     * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
     */
	function get_columns(){
	
		global $edd_options;
		
        $columns = array(
	        					'cb'      		=> '<input type="checkbox" />', //Render a checkbox instead of text
					            'post_drag'		=> __( 'Drag', 'eddcurrency' ),
					            'post_title'	=> __( 'Code', 'eddcurrency' ),
					            'post_content'	=> __( 'Label', 'eddcurrency' ),
					            'symbol'		=> __( 'Symbol', 'eddcurrency' ),
					        );
					        
		if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] != 'custom_rates' ) {
			$columns['open_ex_rate'] = __( 'Open Exchange Rate', 'eddcurrency' );
		}
		if( isset( $edd_options[ 'exchange_rates_method' ] ) && $edd_options[ 'exchange_rates_method' ] != 'open_exchange' ) {
			$columns['custom_rate'] = __( 'Custom Rate', 'eddcurrency' );
		}
					        
        return $columns;
    }
    /**
	 * Override The Row Class 
	 * 
	 * Handles to overrie the row class
	 *
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
	 */
    function single_row( $item ) {
    	
    	echo '<tr class="edd-currency-list-row"">';
		$this->single_row_columns( $item );
		echo '</tr>';
    }
	
    /**
     * Sortable Columns
     *
     * Handles soratable columns of the table
     * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
     */
	function get_sortable_columns() {
		
		
        $sortable_columns = array(
							            'post_title'	=> array( 'post_title', true ),     //true means its already sorted
							            'post_content'	=> array( 'post_content', true )
							        );
        return $sortable_columns;
    }
	
	function no_items() {
		//message to show when no records in database table
		_e( 'No currencies found.', 'eddcurrency' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
     * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
     */
	function get_bulk_actions() {
		//bulk action combo box parameter
		//if you want to add some more value to bulk action parameter then push key value set in below array
        $actions = array(
					            'delete'    => 'Delete'
					        );
        return $actions;
    }
    
	function process_bulk_action() {
    
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            
        	wp_die(__( 'Items deleted (or they would be if we had items to delete)!', 'eddcurrency' ));
        } 
        
    }
    
    /**
     * Per Page Field
     *
     * Handles Per Page Functionality
     * 
	 * @package Easy Digital Downloads - Currency Converter
	 * @since 1.0.0
     */
    function extra_tablenav( $which ) {
    	
    	if( $which == 'top' ) {
    		
			$html = '';
			
    		$html .= '<div class=" actions">';
    			$html .= '<input size="2" type="text" value="'.$this->per_page.'" name="per_page" />';
    			$html .= '<input class="button action" type="submit" value="'.__( 'Show Per Page', 'eddcurrency' ).'" name="edd_currency_show" />';
    		$html .= '</div">';
    		
    		echo $html;
    	}
    }
	
	function prepare_items() {
        
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = $this->per_page; //EDD_CURRENCY_PER_PAGE;
        
        
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
		$data = $this->display_currencies();
		
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'menu_order'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        // usort function disturb to sort order listing so commented
        //usort($data, 'usort_reorder');
       
                
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
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);        
        
        
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
$CurrencyListTable = new Edd_Currency_List();
	
//Fetch, prepare, sort, and filter our data...
$CurrencyListTable->prepare_items();

?>

<div class="wrap">
    
    <?php echo screen_icon('options-general'); ?>
	
    <h2>
    	<?php _e( 'Currencies', 'eddcurrency' ); ?>
    	<a class="add-new-h2" href="edit.php?post_type=download&page=edd_currency_manage"><?php _e( 'Add New','eddcurrency' ); ?></a>
    </h2>
   	<?php 
   		$html = '';
		if(isset($_GET['message']) && !empty($_GET['message']) ) { //check message
			
			if( $_GET['message'] == '1' ) { //check insert message
				$html .= '<div class="updated settings-error" id="setting-error-settings_updated">
							<p><strong>'.__("Currency Inserted Successfully.",'eddcurrency').'</strong></p>
						</div>'; 
			} else if($_GET['message'] == '2') {//check update message
				$html .= '<div class="updated" id="message">
							<p><strong>'.__("Currency Updated Successfully.",'eddcurrency').'</strong></p>
						</div>'; 
			} else if($_GET['message'] == '3') {//check delete message
				$html .= '<div class="updated" id="message">
							<p><strong>'.__("Currency deleted Successfully.",'eddcurrency').'</strong></p>
						</div>'; 
			} else if($_GET['message'] == '4') {//check save order message
				$html .= '<div class="updated" id="message">
							<p><strong>'.__("Sort Order Updated Successfully.",'eddcurrency').'</strong></p>
						</div>'; 
			} else if($_GET['message'] == '5') {//check reset order message
				$html .= '<div class="updated" id="message">
							<p><strong>'.__("Sort Order Reset Successfully.",'eddcurrency').'</strong></p>
						</div>'; 
			}
		}
		echo $html;

	?>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="product-filter" method="get">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
    	<?php $paged = ( isset( $_REQUEST['paged'] ) ) ? $_REQUEST['paged'] : 1; ?>
        <input type="hidden" name="currency_paged" value="<?php echo $paged ?>" />
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <input type="hidden" name="post_type" value="download" />
        
        <!-- Search Title -->
        <?php $CurrencyListTable->search_box( __( 'Search', 'eddcurrency' ), 'edd_currency_search' ); ?>
        
        <input type="submit" name="edd_currency_save_order" class="button-primary edd-currency-save-order" value="<?php _e( 'Save Order', 'eddcurrency' ) ?>" />
        <input type="submit" name="edd_currency_reset_order" class="button-secondary edd-currency-reset-order" value="<?php _e( 'Reset Order', 'eddcurrency' ) ?>" />
        
        <!-- Now we can render the completed list table -->
        <?php $CurrencyListTable->display() ?>
        
    </form>
	        
</div>