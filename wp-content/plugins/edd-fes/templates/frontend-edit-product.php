<?php
$post_id = absint( $_REQUEST['post_id'] );
// check to make sure vendor is author of this download & can edit
if ( !EDD_FES()->vendor_permissions->vendor_can_edit_product($post_id) ){
	_e('Access Denied: You may only edit your own products','edd_fes');
}
else{
	echo '<h1>'.__('Edit Product #: ','edd_fes').$post_id.'</h1>';
	echo EDD_FES()->frontend_form_post->add_post_shortcode($post_id);
}