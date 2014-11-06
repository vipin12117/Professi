<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Marketify
 */

if ( ! is_active_sidebar( 'sidebar-download-single-comments' ) || ! is_singular( 'download' ) )
	return;
?>
	<div id="secondary" class="col-md-4 col-sm-4 col-xs-12" role="complementary">
			
		<?php dynamic_sidebar( 'sidebar-download-single-comments' ); ?>

	</div><!-- #secondary -->
