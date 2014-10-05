<?php
/**
 * Template Name: Login
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
				
				<div style="float:left;">
					<?php echo do_shortcode("[edd_login]");?>
				</div>
				<div style="float:right;">
					<div class="right">
				        <h3>NOT YET A MEMBER?</h3>
				        <ul>
				            <li><span>Download FREE Resources</span></li>
				            <li><span>Shop the entire TpT catalog</span></li>
				            <li><span>Rate and comment on products</span></li>
				        </ul>
				        <span>Registration is free and easy</span>
				        
				        <br /><br />
				        <a id="edd_login_submit" href="<?php echo esc_url( home_url( '/register' ) ); ?>" class="edd_submit">Register Now</a>
				    </div>
				</div>
				
				<br clear="all" />
			</div>	
		</div>
	</div>
<?php get_footer(); ?>