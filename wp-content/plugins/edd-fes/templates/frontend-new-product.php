<?php
if (EDD_FES()->vendor_permissions->vendor_can_create_product()){
	echo EDD_FES()->frontend_form_post->add_post_shortcode();
}
