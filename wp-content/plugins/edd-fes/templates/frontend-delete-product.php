<?php
$post_id = absint( $_REQUEST['post_id'] );
// check to make sure vendor is author of this download & can delete
if ( !EDD_FES()->vendor_permissions->vendor_can_delete_product($post_id) ){
	_e('Access Denied: You may only delete your own products','edd_fes');
} else{ ?>
	<h1><?php _e('Delete Product #: ','edd_fes'); echo $post_id; ?></h1>
	<p><?php _e( 'Are you sure you want to delete this product? This action is irreversible.', 'edd_fes' ); ?></p>
	<form id="fes-delete-form" action="" method="post">
		<input type="hidden" name="pid" value="<?php  echo $post_id; ?>">
		<?php  wp_nonce_field('fes_delete_nonce', 'fes_nonce'); ?>
		<button class="fes-delete button" type="submit"><?php  _e( 'Delete', 'edd_fes' ); ?></button>
	</form>
	<?php
}