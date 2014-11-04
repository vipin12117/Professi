<?php
/**
 * Template Name: Login
 * @package Marketify
 */
get_header();
?>

		<div class=" login_page">
			<h1 class="welcome_header">Welcome to Professi</h1>
			<div class="row"> 
			<div class="col-xs-12 col-sm-6 col-md-6  " style="  border-right-style: dashed; border-width:2px; border-color:#bdc3c7;">
		
					<?php echo do_shortcode("[edd_login]");?>
				
			</div>
								<div class="col-xs-12 col-sm-6 col-md-6 "  >
				       <h1 class="custom-fes-header"> NOT YET A MEMBER? </h1>
				         <p>Registration is free and easy</p>
						 <ul>
				            <li><span>Download FREE Resources</span></li>
				            <li><span>Shop the entire catalogue</span></li>
				            <li><span>Rate and comment on products</span></li>
				        </ul>
				       
					
				        <a href="<?php echo esc_url( home_url( '/register' ) ); ?>" class="register_submit">Register Now</a>
				
	</div>
				
			</div>
			</div>	
		
	
<?php get_footer(); ?>