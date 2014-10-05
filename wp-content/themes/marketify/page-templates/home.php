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

	<div class="container clear">
		<div class="home-container clearfix">
			<div class="left-container sidebar left">
				<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
			</div>

			<div id="content" class="right-container site-content row left">
				<?php if($isHome == true) {?>
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
					<div class="video">
			

						<div class="home-review-content">
					<!-- elegant minimal -->
      <div class="vp1_html5" >
            <video id="vp1_html5_EM" width="704" height="396" preload="auto" poster="<?php echo get_template_directory_uri(); ?>/images/prev.jpg">
                <source src="<?php echo get_template_directory_uri(); ?>/images/promo_profesi_V1.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
                <source src="videos/big_buck_bunny_trailer.webm" type='video/webm; codecs="vp8, vorbis"' />
            </video>
      </div>


						
						</div>
																												</div>
							
					<div class="row">	
					
					<div class="col-md-12" style="margin-left:175px; padding-top:20px; padding-bottom:5px;">
				<img src=" <?php echo get_template_directory_uri(); ?>/images/dotted_lines.png" /> </div>
					
					<div class="col-md-6">
						
			<div class="round_buttons"
						<ul>
  <li ><a href="#" class="round green">Start </br> Browsing<span class="round">That is, if you already have an account.</span></a></li>
 </div>

</ul> 
<p>Become parts of our first
							group of teacher sellers.
							Sell your products and 
							keep up to 80%</p>

	</div>		
	
	<div class="col-md-6">
						
			<div class="round_buttons"
						<ul>

  <li ><a href="#" class="round red">Start  </br> Selling <span class="round">But only if you really, really want to. </span></a></li> </div>

</ul> 
<p>Become parts of our first
							group of teacher sellers.
							Sell your products and 
							keep up to 80%</p>
	</div>		
</div>			
						
					<br clear="all" />
						
						
					</div>
					
			
					
					
								
			

					
			
					
					
				<?php }?>
				
				<div class="download-product-review-details content-items clearfix">
					<?php if ( ! is_paged() && ! get_query_var( 'orderby' ) && ! is_page_template( 'page-templates/popular.php' ) ) : ?>
						<?php// get_template_part( 'content-grid-download', 'popular' ); ?>
					<?php endif; ?>
					
					<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-7 col-xs-12">
						<main id="main" class="site-main" role="main">

							<div class="the-title-home"><?php marketify_downloads_section_title();?></div>
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
