<?php
if ( EDD_FES()->vendors->is_commissions_active() ) { ?>
	<h2><?php _e( 'Commissions Overview', 'edd_fes' ); ?></h2>
	<?php 
	if( eddc_user_has_commissions() ) {
		echo do_shortcode('[edd_commissions]'); 
	}
	else{
		echo __( 'You haven\'t made any sales yet!', 'edd_fes' );
	}
} else {
	echo 'Error 4908';
}
?>