<?php
/**
 * Template Name: Seller
 * @package Marketify
 */
get_header();
?>
	<div class="container clear">
		<div class="home-container clearfix">
			<div id="content" class="right-container site-content row left">
				<br /><br />
				<h3>Welcome to Professi</h3>
				<br /><br />

				<?php echo do_shortcode("[edd_register]");?>
								
				<br clear="all" />
			</div>	
		</div>
	</div>
<?php get_footer(); ?>