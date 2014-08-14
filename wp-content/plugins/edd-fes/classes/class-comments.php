<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
class FES_Comments {
	function __construct(){
		add_action('init',array($this,'comment_intercept'));
		add_action('init',array($this,'mark_comment_as_read'));
	}
	function comment_intercept(){

		if( ! isset( $_POST['fes_nonce'] ) || ! isset( $_POST['newcomment_body'] ) ) {
			return;
		}

		if ( !wp_verify_nonce($_POST['fes_nonce'], 'fes_comment_nonce') || $_POST['newcomment_body'] === '' ) {
			return;
		}

		$comment_id = absint( $_POST['cid'] );
		$author_id = absint( $_POST['aid'] );
		$post_id = absint( $_POST['pid'] );
		$content = $_POST['newcomment_body'];
		update_comment_meta( $comment_id,'fes-already-processed', 'edd_fes' );
		$new_id = wp_insert_comment( array(
			'user_id' => $author_id,
			'comment_parent' => $comment_id,
			'comment_post_ID' => $post_id,
			'comment_content' => $content
		) );
		// This ensures author replies are not shown in the list
		update_comment_meta( $new_id, 'fes-already-processed', 'edd_fes' );
	}

	
	function mark_comment_as_read(){
		
		if ( ! isset( $_POST['fes_nonce'] ) || ! wp_verify_nonce( $_POST['fes_nonce'], 'fes_ignore_nonce' ) ) {
			return;
		}

		$comment_id = absint( $_POST['cid'] );
		update_comment_meta( $comment_id, 'fes-already-processed', 'edd_fes');
	}

	function render( $limit ) {
		global $current_user, $wpdb;
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) :
		1;
		$offset = ( $pagenum - 1 ) * $limit;
		$args = array(
			'number' => $limit,
			'offset' => $offset,
			'post_author' => $current_user->ID,
			'post_type' => 'download',
			'status' => 'approve',
			'meta_query' => array(
				array(
					'key' => 'fes-already-processed',
					'compare' => 'NOT EXISTS'
				),
			)
		);
		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );
		
		if ( count( $comments ) == 0 ) {
			echo '<tr><td colspan="4">' . __( 'No Comments Found', 'edd_fes' ) . '</td></tr>';
		}
		foreach ($comments as $comment) {
			$this->render_row( $comment );
		}

		$args = array('post_author'  => $current_user->ID,'post_type'    => 'download','status'       => 'approve','author__not_in' => array($current_user->ID),'meta_query'   => array(array('key' => 'fes-already-processed','compare' => 'NOT EXISTS',),));
		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );
		
		if ( count($comments) > 0){
			$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) :
			1;
			$num_of_pages = ceil( count($comments) / $limit );
			$page_links = paginate_links( array('base' => add_query_arg( 'pagenum', '%#%' ),'format' => '','prev_text' => __( '&laquo;', 'aag' ),'next_text' => __( '&raquo;', 'aag' ),'total' => $num_of_pages,'current' => $pagenum) );
			
			if ( $page_links ) {
				echo '<div class="fes-pagination">' . $page_links . '</div>';
			}
		}
	}
	
	function render_row( $comment ) {
		$comment_date = get_comment_date( 'Y/m/d \a\t g:i a', $comment->comment_ID );
		$comment_author_img = get_avatar( $comment->comment_author_email, 32 );
		$purchased = edd_has_user_purchased( $comment->user_id, $comment->comment_post_ID );
		?>
	<tr>
		<td class="col-author" style="width:25%;">
			<div class="fes-author-img"><?php echo $comment_author_img; ?></div>
			<span id="fes-comment-author"><?php echo $comment->comment_author; ?></span>
			<br /><br />
			<?php
			if ($purchased){
				echo '<div class="fes-light-green">'.__('Has Purchased','edd_fes').'</div>';
			} else {
				echo '<div class="fes-light-red">'.__('Has Not Purchased','edd_fes').'</div>';
			}

			?>
			<span id="fes-comment-date"><?php echo $comment_date; ?>&nbsp;&nbsp;&nbsp;</span><br />
			<span id="fes-product-name">
				<b><?php _e( 'Product: ','edd_fes' ); ?></b>
				<a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) ); ?>"><?php echo get_the_title( $comment->comment_post_ID ); ?></a>&nbsp;&nbsp;&nbsp;
			</span><br />
			<span id="fes-view-comment">
				<a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID ); ?>"><?php _e( 'View Comment','edd_fes' ); ?></a>
			</span>
		</td>
		<td class="col-content" style="width:70%;">
			<div class="fes-comments-content"><?php echo $comment->comment_content; ?></div>
			<hr/>
			<div id="<?php echo $comment->comment_post_ID; ?>" class="fes-vendor-comment-respond-form">
				<span><?php _e( 'Respond:', 'edd_fes' );; ?></span><br/>
				<table>
					<tr>
						<form id="fes_comments-form" action="" method="post">
							<input type="hidden" name="cid" value="<?php echo $comment->comment_ID; ?>">
							<input type="hidden" name="pid" value="<?php echo $comment->comment_post_ID; ?>">
							<input type="hidden" name="aid" value="<?php echo get_current_user_id(); ?>">
							<?php wp_nonce_field('fes_comment_nonce', 'fes_nonce'); ?>
							<textarea class="fes-cmt-body" name="newcomment_body" cols="50" rows="8"></textarea>
							<button class="fes-cmt-submit-form button" type="submit"><?php  _e( 'Post Response', 'edd_fes' ); ?></button>
						</form>				
						<form id="fes_ignore-form" action="" method="post">
							<input type="hidden" name="cid" value="<?php echo $comment->comment_ID; ?>">
							<?php wp_nonce_field('fes_ignore_nonce', 'fes_nonce'); ?>
							<button class="fes-ignore button" type="submit"><?php _e( 'Mark as Read', 'edd_fes' ); ?></button>
						</form>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<?php
	}

}