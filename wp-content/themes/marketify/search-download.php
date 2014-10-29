<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

//

get_header();
//$Cats = (isset($GLOBALS['cat_search'])) ? $GLOBALS['cat_search'] : '';
$cat_ = $_GET['absc_search_cat'];

$obj = get_queried_object();
if($obj->slug){
	$cat_ = $obj->slug;
}

$pcats = array();
$ccats = array();
if($cat_ && strlen($cat_) > 0) {
	$cats = explode(',', $cat_);
	$cats = array_unique($cats);
	
	foreach($cats as $slug) {
		$iccat = get_term_by( 'slug', $slug, 'download_category' );
		$ipcat = get_term_by( 'term_id', $iccat->parent, 'download_category' );
		$key =$ipcat->term_id;
		$pcats[$key] = $ipcat;
		if(!isset($ccats[$key])) {
			$ccats[$key] = array();
		}
		
		array_push($ccats[$key], $iccat);
	}
}

global $wp_query;

wp_reset_query(); 
$wp_query = new WP_Query( array(
	'post_type'   => 'download',
	'post_status' => 'publish',
	'download_category'    => $cat_,
	's'    => $_GET['s'],
	'posts_per_page' => 10,
	'orderby' => 'average_rating',
	'order' => 'DESC'
));

//print_R($wp_query); exit;

$GLOBALS['view'] = "viewWhishlist";
?>
<style>
 .col-md-4{width:100% !important;}
</style>
<div class="container result-search main-body">
  <div class="row">
	 <div class="left-container col-xs-4 sidebar">
		<aside id="selected-categories" class="widget download-single-widget widget_edd_categories_tags_widget">
			<h1 class="download-single-widget-title"></h1>
			<ul class="edd-taxonomy-widget">
				<li class="cat-item cat-item-15">
					<a class="filter-banner">YOU SELECTED</a>
					<ul class="children selected-cat">
						<?php foreach($pcats as $key=>$pcat) {?>
						<li class="cat-item cat-item-selected">
								<?php 
									echo '<span class="pcat">'.$pcat->name.'</span>'; 
									$icats = $ccats[$key];
									foreach($icats as $ccat) {
										echo '<span title="Click on this category to remove selected search." class="icon-cat" data-slug="'.$ccat->slug.'">'.$ccat->name.'<i class="icon"></i></span>'; 
									}
								?>
								<a>&nbsp;</a>
						</li>
						<?php }
							if(count($pcats) == 0) {
						?>
						<li class="cat-item cat-item-21">
								<a>Search in all categories</a>
						</li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</aside>
				
		<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
	</div>

	<div id="content" class="right-container col-xs-8 site-content ">
	  <div class="download-product-review-details content-items clearfix">
		 <section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-7 col-xs-12">
			<main id="main" class="site-main" role="main">
				
				<!--  <div class="the-title-home"><?php //marketify_downloads_section_title();?></div> -->
				<div class="result-info clearfix">
					<div class="result fontsforweb_fontid_9785 left"><?php echo $wp_query->found_posts;?> results</div>
					<div class="result-selectbox right" style="display:none;">
						<span>sort by:</span>
						<select id="selext-orderby" class="form-control">
							<option value="title">Name</option>
							<option value="date">Date</option>
							<option value="rating">Rating</option>
							<option value="rand">Randome</option>
						</select>
					</div>
				</div>
				<br />

				<?php if ( have_posts() ) : ?>
					<div class="download-grid-wrapper columns-<?php echo marketify_theme_mod( 'product-display', 'product-display-columns' ); ?> row clearfix" data-columns="1">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php //get_template_part( 'content-grid', 'download' ); ?>
							
							<div style="width:700px;" id="post-<?php the_ID(); ?>" class="content-grid-download">
								<div style="float:left;width:150px;">
									<?php edd_get_template_part( 'shortcode', 'content-image' ); ?>
								</div>
								
								<div style="float:left;width:300px;margin-left:20px;">
									<p>
										<?php edd_get_template_part( 'shortcode', 'content-title' ); ?>
									</p>
									
									<?php $data_custom = get_post_custom($post->ID);?>
									<p><?php echo ($data_custom['add_description'][0]); ?></p>
									
									<?php $data_custom = get_post_custom($post->ID);?>
									<?php 
										  $full = 0;
										  $rating = 0;
										  $rating = edd_reviews()->average_rating( false );
										  $full = intval($rating);
										  $ratingCount = edd_reviews()->count_reviews();
										  $edd_download_files = unserialize($data_custom['edd_download_files'][0]);
										  
										  $category_str = '';
										  $categories = (array)get_the_terms( $post->ID, 'download_category' );
										  foreach($categories as $category){
										  	  if(in_array($category->parent , array(52,53,63,72,81,85,92))){
										  	  	$category_str .= $category->name.",";
										  	  }
										  }
										  
										  //print $rating . " -- " . $post->ID . " -- " . $ratingCount . "<br />";
									?>
									<div class="form-horizontal">
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
													
													<?php //echo edd_reviews()->microdata();?>
												</div>
												<div class="ratings"><?php echo $ratingCount; ?> ratings</div>
											</span>
										</div>
										<br />
										
										<div class="type">Digital Download</div>
										<div class="control-group clearfix">
											<span class="control-label lv2"></span>
											<span class="controls gray-light">
												<?php if(!$edd_download_files[0]['file']):?>
													<?php $edd_download_files[0]['file'] = $edd_download_files[0];?>
												<?php endif;?>
												<?php $file_parts = explode(".",$edd_download_files[0]['file']);?>
												
												<span class="file-type"><strong><?php echo ucfirst(end($file_parts));?> (<?php echo getSizeFile($edd_download_files[0]['file']);?> MB)</strong></span>
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
 </div>
</div>

<script type="text/javascript">
	window.searchResult = true;
	window.currentSelect = '<?php echo (isset($GLOBALS['search_order']) ? $GLOBALS['search_order'] : ''); ?>';
	window.lastSearchCats = '<?php echo $cat_; ?>';
</script>
<script type='text/javascript'>
	var edd_wl_scripts = {
		"wish_list_page":"<?php echo edd_wl_get_wish_list_uri();?>",
		"wish_list_add":"<?php echo edd_wl_get_wish_list_create_uri();?>",
		"ajax_nonce":"<?php echo wp_create_nonce( 'edd_wl_ajax_nonce' );?>"
	};
</script>
<script type="text/javascript" src="<?php echo content_url(); ?>/plugins/edd-wish-lists/includes/js/edd-wl.min.js?ver=1.0.6"></script>
<script type="text/javascript" src="<?php echo content_url(); ?>/plugins/edd-wish-lists/includes/js/modal.min.js?ver=1.0.6"></script>
<?php get_footer(); ?>