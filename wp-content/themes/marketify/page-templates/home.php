<?php
/**
 * Template Name: Homepage
 *
 * @package Marketify
 */

$pageid = basename(get_permalink());
$isHome = (strcmp($pageid, "") == 0 || strcmp($pageid, "Professi") == 0 || strcmp($pageid, "profesi.growthlabs.ca") == 0);
$GLOBALS['is_home'] = $isHome;
get_header(); ?>

	<div class="container main-body">
		<div class="row">
			<div class="left-container col-xs-12 col-sm-4 col-md-4 sidebar ">
				<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
			</div>

			<div id="content" class="right-container col-xs-12 col-sm-8 col-md-8 site-content  ">
				<?php if($isHome == true) ?>
					<!--<div class="download-product-review-details">
						<div class="home-review-content">
							<?php  /** if ( is_active_sidebar( 'preview-1' ) ) :  ?>
								<?php dynamic_sidebar( 'preview-1' ); ?>
							<?php endif; */ ?>
						</div>
						
						<div style="width:500px;margin:0 auto;">
						<div style="float:left; width:200px;" align="center">
							<a href="/fes-vendor">Start browsing</a> <br />
							Discover great resources 
							created by teachers 
							for teachers
						</div>
						
						<div style="float:right;width:200px;" align="center">
							<a href="/vendor-dashboard">Start selling</a><br />
							Become parts of our first
							group of teacher sellers.
							Sell your products and 
							keep up to 80%
						</div>
					</div>
					<br clear="all" />
						
						
					</div> !-->
					
					
					<div class="download-product-review-details">
					<h1>BIENVENIDO A PROFESI.ORG</h1>
					<p> DONDE MAESTROS COMPARTEN COMPRAN Y VIENDEN SUS INNOVACIONES EDUCATIVAS</p>


					<div class="video">
			

						<div class="home-review-content">
					<!-- elegant minimal -->
      <div class="vp1_html5" style="border:4px solid #d3e8e7;overflow:hidden;" >
            <video id="vp1_html5_EM" width="704" height="396" preload="auto" poster="<?php echo get_template_directory_uri(); ?>/images/prev.jpg">
                <source src="<?php echo get_template_directory_uri(); ?>/images/promo.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
                <source src="<?php echo get_template_directory_uri(); ?>/images/promo.webm" type='video/webm; codecs="vp8, vorbis"' />
                <source src="<?php echo get_template_directory_uri(); ?>/images/promo.ogg"  type='video/ogg; codecs="theora, vorbis"' />
            </video>
      </div>


						
						</div>
																												</div>
							
					<div class="row">	
					
					<div class="col-md-12" style=" padding-top:20px; padding-bottom:5px;">
                                            <div style="margin-left:175px;"><img src=" <?php echo get_template_directory_uri(); ?>/images/dotted_lines.png" /> </div></div>
					
					<div class="col-md-6">
						
			<div class="round_buttons"
						<ul>
  <li ><a href="<?php echo esc_url( home_url( '/fes-vendor/' ) ); ?>" class="round green">Start </br> Browsing<span class="round">That is, if you already have an account.</span></a></li>
 </div>

</ul> 
<p>Discover great resources created by teachers for teachers</p>

	</div>		
	
	<div class="col-md-6">
						
			<div class="round_buttons"
						<ul>

  <li ><a href="<?php echo esc_url( home_url( '/register' ) ); ?>" class="round red">Start  </br> Selling <span class="round">But only if you really, really want to. </span></a></li> </div>

</ul> 
<p>Become parts of our first
							group of teacher sellers.
							Sell your products and 
							keep up to 80%</p>
	</div>		
</div>			
						
					<br clear="all" />
						
						
					</div>
					
				<?php ?>
				
				<div class="download-product-review-details content-items clearfix">
					<?php if ( ! is_paged() && ! get_query_var( 'orderby' ) && ! is_page_template( 'page-templates/popular.php' ) ) : ?>
						<?php // get_template_part( 'content-grid-download', 'popular' ); ?>
					<?php endif; ?>
					
					<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-12 col-xs-12">
						<main id="main" class="site-main" role="main">

							<div class="the-title-home">PRODUCTOS DESTACADOS</div>
							<div class="clearfix">
								<?php echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>
							</div>
						</main><!-- #main -->
					</section><!-- #primary -->
					<?php get_sidebar( 'archive-download' ); ?>
				</div>
			</div><!-- #content -->
		</div>
	</div>
<?php get_footer(); ?>
