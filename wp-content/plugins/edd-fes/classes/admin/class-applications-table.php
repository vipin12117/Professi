<?php
class EDD_FES_Applications_Table extends WP_List_Table {


    /**
     * Number of results to show per page
     *
     * @since       1.7
     * @var         int
     */
    public $per_page = 10;


    function __construct() {
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
                'singular'  => 'application',     //singular name of the listed records
                'plural'    => 'applications',    //plural name of the listed records
                'ajax'      => false             //does this table support ajax?
            ) );

    }


    function column_default( $item, $column_name ) {
        switch ( $column_name ) {
			case 'name':
				return $this->column_title( $item );
			case 'view':
				return '<a href="'.admin_url('post.php?post='.$item['ID']).'&action=edit">'.__( 'View Application', 'edd_fes').'</a>';
			case 'status':
				$status = get_post_meta( $item['ID'], 'fes_status', true );
				if ( $status == 'pending'){
					return __('Pending','edd_fes');
				}
				else if ( $status == 'approved' ){
					return __('Approved','edd_fes');
				}
				else if ( $status == 'denied' ){
					return __('Denied','edd_fes');
				}
				else{
					return $status;
				}
			case 'date':
				return date_i18n( get_option( 'date_format' ), strtotime( get_post_field( 'post_date', $item['ID'] ) ) );
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
			}
    }

    function column_title( $item ) {

        //Build row actions
        $actions = array();
        $base = admin_url( 'admin.php?page=fes-applications' );
        $status = get_post_meta( $item['ID'], 'fes_status', true );

        if( $status != 'denied' ) {
            $actions['deny_app']    = sprintf( '<a href="%s&action=%s&application=%s">' . __( 'Deny Application', 'edd_fes' ) . '</a>', $base, 'deny_app', $item['ID'] );
        }
        if( $status != 'approved' ) {
            $actions['approve_app'] = sprintf( '<a href="%s&action=%s&application=%s">' . __( 'Approve Application', 'edd_fes' ) . '</a>', $base, 'approve_app', $item['ID'] );
        }

        $user = new WP_User( get_post_meta( $item['ID'], 'fes_user', true ) );

        //Return the title contents
        return $user->first_name . ' ' . $user->last_name . $this->row_actions( $actions );
    }

    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],
            /*$2%s*/ $item['ID']
        );
    }


    function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
			'name'     => __( 'Name', 'edd_fes' ),
			'view'     => __( 'View Application', 'edd_fes'),
            'status'   => __( 'Status', 'edd_fes' ),
            'date'      => __( 'Date', 'edd_fes' )
        );
        return $columns;
    }

    function get_views() {
        $base = admin_url( 'admin.php?page=fes-applications' );
        $current = isset( $_GET['view'] ) ? $_GET['view'] : '';
        $views = array(
            'all'       => sprintf( '<a href="%s"%s>%s</a>', remove_query_arg( 'view', $base ), $current === 'all' || $current == '' ? ' class="current"' : '', __( 'All' ) ),
			
			'pending'      => sprintf( '<a href="%s"%s>%s</a>', add_query_arg( 'view', 'pending', $base ), $current === 'pending' ? ' class="current"' : '', __( 'Pending' ) ),
            
			'approved'    => sprintf( '<a href="%s"%s>%s</a>', add_query_arg( 'view', 'approved', $base ), $current === 'approved' ? ' class="current"' : '', __( 'Approved' ) ),
            
			'denied'      => sprintf( '<a href="%s"%s>%s</a>', add_query_arg( 'view', 'denied', $base ), $current === 'denied' ? ' class="current"' : '', __( 'Denied' ) )
		);
        return $views;
    }


    function get_bulk_actions() {
        $actions = array(
            'approve'      => __( 'Approve Application(s)' ,'edd_fes' ),
            'deny'    => __( 'Deny Application(s)' ,'edd_fes' ),
        );
        return $actions;
    }


    /**
     * Retrieve the current page number
     *
     * @access      private
     * @since       1.7
     * @return      int
     */
    function get_paged() {
        return isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
    }

    /**
     * Gets the meta query for the log query
     *
     * This is used to return log entries that match our search query, user query, or download query
     *
     * @access      private
     * @since       1.7
     * @return      array
     */
    function get_meta_query() {

        $meta_query = array();
        $view     = isset( $_GET['view'] ) ? $_GET['view'] : false;

        if ( $view ) {
            // Show only applications of a specific status
            $meta_query[] = array(
                'key'   => 'fes_status',
                'value' => $view
            );

        }

        return $meta_query;
    }


    function process_bulk_action() {

        $ids = isset( $_GET['application'] ) ? $_GET['application'] : false;

        if( empty( $ids ) )
            return;

        if ( !is_array( $ids ) )
            $ids = array( $ids );

        foreach ( $ids as $id ) {
            if ( 'approve_app' === $this->current_action() ) {
                update_post_meta( $id, 'fes_status', 'approved' );
				$user = get_post_meta( $id, 'fes_user',true);
				$wp_user_object = new WP_User( (int) $user );
				$role = 'frontend_vendor';
				$wp_user_object->set_role( $role );
				EDD_FES()->emails->fes_notify_user_app_accepted( (int) $user );
            }
            if ( 'deny_app' === $this->current_action() ) {
                update_post_meta( $id, 'fes_status', 'denied' );
				$user = get_post_meta( $id, 'fes_user',true);
				$wp_user_object = new WP_User( (int) $user );
				$role = 'subscriber';
				$wp_user_object->set_role( $role );
				EDD_FES()->emails->fes_notify_user_app_denied( (int) $user );
            }
        }
    }

    function applications_data() {

        $applications_data = array();

        $paged    = $this->get_paged();

        $application_args = array(
            'post_type'      => 'fes-applications',
            'post_status'    => 'publish',
            'posts_per_page' => $this->per_page,
            'paged'          => $paged,
            'meta_query'     => $this->get_meta_query()
        );


        $applications = new WP_Query( $application_args );
        if ( $applications->have_posts() ) :
            while ( $applications->have_posts() ) : $applications->the_post();
                $applications_data[] = array(
                    'ID'       => get_the_ID(),
                    'title'    => get_the_title( get_the_ID() ),
                );
            endwhile;
            wp_reset_postdata();
        endif;
        return $applications_data;
    }

    /** ************************************************************************
     *
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     * *************************************************************************/
    function prepare_items() {

        $columns = $this->get_columns();
        $hidden = array(); // no hidden columns

        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );

        $this->process_bulk_action();

        $current_page = $this->get_pagenum();

        $total_items = wp_count_posts( 'fes-applications' )->publish;

        $this->items = $this->applications_data();

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $this->per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$this->per_page )   //WE have to calculate the total number of pages
        ) );
    }

}
