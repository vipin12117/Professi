<?php 
// The fields in this template are controlled via the admin. Edit the fields there

if( EDD_FES()->fes_options->get_option( 'show_vendor_registration') ) : ?>
<h1><?php _e( 'Vendor Application', 'edd_fes' ); ?></h1>
<?php echo EDD_FES()->frontend_application->application_shortcode(); ?>
<?php else : ?>
<?php // Put a message here for non vendors if you wish ?>
<?php endif; ?>
