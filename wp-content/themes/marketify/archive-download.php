<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); 

global $wp_query;
?>
<style>
 .col-md-4{width:100% !important;}
</style>
<div class="container">
	<div class="home-container clearfix">
		<div class="left-container sidebar left">
			<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
		</div>
		<div id="content" class="right-container site-content row left">
			<div class="download-product-review-details content-items clearfix">
				<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-7 col-xs-12">
					<main id="main" class="site-main" role="main">
	
					<div class="the-title-home">You Selected: <?php marketify_downloads_section_title(); ?></div>
	
					<?php if ( have_posts() ) : ?>
	
						<div class="download-grid-wrapper columns-<?php echo marketify_theme_mod( 'product-display', 'product-display-columns' ); ?> row clearfix" data-columns="1">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php //get_template_part( 'content-grid', 'download' ); ?>
								
								<div style="width:700px;" id="post-<?php the_ID(); ?>" class="content-grid-download">
									<div style="float:left;width:150px;">
										<?php edd_get_template_part( 'shortcode', 'content-image' ); ?>
									</div>
									
									<div style="float:left;width:300px;margin-left:20px;">
										<p>BOOK NAME:<?php edd_get_template_part( 'shortcode', 'content-title' ); ?></p>
										
										<?php edd_get_template_part( 'shortcode', 'content-full' ); ?>
										
										<?php $data_custom = get_post_custom($post->ID);?>
										<?php 
											  $rating = edd_reviews()->average_rating( false );
											  $full = intval($rating);
											  $ratingCount = edd_reviews()->count_reviews();
											  $edd_download_files = unserialize($data_custom['edd_download_files'][0]);
											  
											  $category_str = '';
											  $categories = (array)get_the_terms( $post->ID, 'download_category' );
											  foreach($categories as $category){
											  	  $category_str .= $category->name.",";
											  }
										?>
										<div class="form-horizontal" style="border:1px solid #000;padding:5px;">
											<div class="control-group">
												<span class="control-label">SUBJECTS:</span>
												<span class="controls gray-light"><?php echo $category_str;?></span>
											</div>
											<div class="control-group">
												<span class="control-label">GRADES:</span>
												<span class="controls gray-light"><?php echo ($data_custom['pick_grade_level(s)'][0]); ?></span>
											</div>
											<div class="control-group">
												<span class="control-label lv2">RESOURCE TYPES:</span>
												<span class="controls gray-light"><?php echo str_replace('|', ',', $data_custom['pick_resource_type'][0]); ?></span>
											</div>
										</div>	
									</div>
									
									<div style="float:right;width:200px;padding:5px 0 5px 10px;">
										<div class="download-product-details action-container" style="padding:5px 0 5px 10px;"><!--#action-container -->
											<div class="price">Price: <?php echo edd_cart_item_price( $post->ID, $post->options );?></div>
											<br />
											<div class="control-group">
												<span class="control-label lv2">PRODUCT RATING </span>
												<span class="controls gray-light">
													<div class="star-ratings">
														<?php $j = 0; for($i = 0; $i < $full; ++ $i)  {?>
														<i class="star star-full"></i>
														<?php $j = $j + 1; }
															if($rating > $full) {
																echo '<i class="star star-half"></i>';
																$j = $j + 1;
															}
															for($i = $j; $i < 5; ++ $i)  {
														?>
															<i class="star star-no"></i>
														<?php } ?>
														<span><?php 
														if(strlen($rating) === 1) {
															$rating = $rating.'.0';
														}
														echo $rating; 
														?></span>
													</div>
													<div class="ratings"><?php echo $ratingCount; ?> ratings</div>
												</span>
											</div>
											<br />
											
											<div class="type">Digital Download</div>
											<div class="control-group clearfix">
												<span class="control-label lv2"></span>
												<span class="controls gray-light">
													<span class="file-type"><strong>PDF (<?php echo @filesize($edd_download_files[0]['file'])/1024;?> MB)</strong></span>
												</span>
											</div>
											<br />
											<div class="add-wish-list"><a class="edd-add-to-cart-from-wish-list edd-wl-open-modal edd-has-js" href="#"
												data-action="edd_wl_open_modal"
												data-download-id="<?php echo $post->ID; ?>"
												data-variable-price="no"
												data-price-mode="single"
												>
												<i class="add-wl"></i>W I S H&nbsp;&nbsp;L I S T</a>
											</div>
										</div><!--#action-container -->
									</div>
									<br clear="all" />
								</div><!-- #post-## -->
							<?php endwhile; ?>
						</div>
	
						<?php marketify_content_nav( 'nav-below' ); ?>
	
					<?php else : ?>
	
						<?php get_template_part( 'no-results', 'download' ); ?>
	
					<?php endif; ?>
	
					</main><!-- #main -->
				</section><!-- #primary -->
			<?php get_sidebar( 'archive-download' ); ?>
			</div>
		</div><!-- #content -->
	</div><!--home-container-->
</div>
<?php get_footer(); ?>