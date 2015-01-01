<?php
/**
 * Template Name: Homepage
 *
 * @package Marketify
 */

include_once get_template_directory().'/split_page_results.php';

$pageid = basename(get_permalink());
$isHome = (strcmp($pageid, "") == 0 || strcmp($pageid, "Professi") == 0 || strcmp($pageid, "profesi.growthlabs.ca") == 0);
$GLOBALS['is_home'] = $isHome;
get_header(); 

$search_query = "select p.ID , p.post_type, p.post_author , post_title , post_name , (sum(wm.meta_value) / 5) as average_rating , count(comment_ID) as count_rating  from wp_posts p 
				 left join
				 (
				    select c.comment_ID , comment_post_ID , meta_value from wp_comments c 
				    inner join wp_commentmeta cm on (c.comment_ID = cm.comment_id and cm.meta_key = 'edd_rating')
				    where c.comment_approved = 1 and meta_value != '' order by meta_value DESC
				 )
				 as wm on (p.ID = wm.comment_post_ID)
				 left join wp_postmeta pm on (p.ID = pm.post_id) 
				 where pm.meta_key = 'show_on_home' and  pm.meta_value = 'Yes' and post_status = 'publish' and post_type = 'download'
				 group by p.ID order by count_rating DESC , average_rating DESC";

$page = (int)$_GET['page'];		
if(!$page){
	$page = 1;
}		 

$limit = 9;
$splitPage = new splitPageResults($search_query , $limit , "", $page);			
$downloads = $wpdb->get_results($splitPage->sql_query);
?>
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
					
				<div class="download-product-review-details details-home">
					<h1>¡BIENVENIDO A PROFESI.ORG!</h1>
					<p> Donde maestros comparten, compran y venden recursos educativos</p>
					<br />
					<p><a class="greenbutton" href="<?php echo esc_url( home_url( '/fes-vendor/' ) ); ?>">COMPRA AHORA</a> 
					<?php if ( is_user_logged_in() ):?>
					<a class="greenbutton" href="<?php echo esc_url( home_url( '/fes-vendor-dashboard/?task=new-product' ) ); ?>">EMPIEZA A VENDER</a>
					  <?php else:?>
					<a class="greenbutton" href="<?php echo esc_url( home_url( '/register' ) ); ?>">EMPIEZA A VENDER</a>
					
					  <?php endif;?>	
					
					</p>
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
						
					<br clear="all" />
		    	</div>
					
				<div class="download-product-review-details content-items clearfix details-home">
					<?php if ( ! is_paged() && ! get_query_var( 'orderby' ) && ! is_page_template( 'page-templates/popular.php' ) ) : ?>
						<?php // get_template_part( 'content-grid-download', 'popular' ); ?>
					<?php endif; ?>
					
					<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-12 col-xs-12">
						<main id="main" class="site-main" role="main">

							<div class="the-title-home"><h2>PRODUCTOS DESTACADOS</h2></div>
							<div class="clearfix">
								<?php //echo do_shortcode( sprintf( '[downloads number="%s"]', 9 ) ); ?>
								
								<?php global $post; foreach($downloads as $post):?>
										<div class="col-md-4">
											<?php  get_template_part( 'content-grid-download'); ?>
										</div>
								<?php endforeach;?>
							</div>
							
							<br clear="all" />
							
							<?php if($splitPage->number_of_rows > $limit):?>
								<div id="edd_download_pagination" class="navigation">
									<?php echo $splitPage->display_links("5",null);?>
								</div>
							<?php endif;?>	
					
						</main><!-- #main -->
					</section><!-- #primary -->
					<?php get_sidebar( 'archive-download' ); ?>
				</div>
			</div><!-- #content -->
		</div>
	</div>
<?php get_footer(); ?>