<?php
/**
 * Template Name: browse
 * Load the [downloads] shortcode.
 * @package Marketify
 */

get_header(); 
?>
<?php do_action( 'marketify_entry_before' ); ?>

<div class="container">

	<div id="content" class="site-content row">
		<div class="left sidebar">
			<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
			
			<?php //wp_list_categories(array('taxonomy' => 'download_category' , 'depth' => 2 , 'hide_empty' => 0 , 'show_count' => 1))?>
		</div>
		
		<div class="result">
		
		</div>

	</div><!-- #content -->
</div>

<?php get_footer(); ?>