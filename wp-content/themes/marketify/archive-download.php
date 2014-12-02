<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

include_once get_template_directory().'/split_page_results.php';

get_header();
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
		$key   = $ipcat->term_id;
		$pcats[$key] = $ipcat;
		if(!isset($ccats[$key])) {
			$ccats[$key] = array();
		}
		
		array_push($ccats[$key], $iccat);
	}
}

global $wp_query;
wp_reset_query(); 
/*$wp_query = new WP_Query( array(
	'post_type'   => 'download',
	'post_status' => 'publish',
	'download_category' => $cat_,
	's'    => $_GET['s'],
	'posts_per_page' => 10,
	'orderby' => 'average_rating',
	'order' => 'DESC',
));*/

$where = array();
if(strlen($s) > 1){
	$s = mysql_real_escape_string($s);
	$s_parts = explode(" ",$s);
	$string  = array();
	foreach((array)$s_parts as $word){
		$string[] = " post_title LIKE '%".mysql_real_escape_string($word)."%' ";
	}
	
	//check category name for keyword
	$term_ids   = $wpdb->get_row("select group_concat(term_id) as term_ids from wp_terms where name like '%$s%'");
	$keyword_or = "";
	if($term_ids->term_ids){
		$term_ids = $term_ids->term_ids;
		$keyword_or = " OR ( p.ID IN ( select object_id from wp_term_relationships where term_taxonomy_id IN($term_ids) ) )";
	}
		
	if(sizeof($string) > 1){
		$where[] = "( (".implode(" AND ", $string).") OR post_title LIKE '%$s%' $keyword_or)";
	}
	else{
		$where[] = "( post_title LIKE '%$s%' $keyword_or)";
	}
}

$cat_condition = array();
foreach($ccats as $cat_ids){
	$orArr = array();
	
	foreach($cat_ids as $cat_id){
		$term_ids = get_term_children($cat_id->term_id,'download_category');
		$term_ids[] = $cat_id->term_id;
		$term_ids  = array_unique($term_ids);
		$term_ids_str = implode(",",$term_ids);
		if($term_ids_str){
			$orArr[] = "p.ID IN ( select object_id from wp_term_relationships where term_taxonomy_id IN($term_ids_str) ) ";
		}
	}
	
	if($orArr){
		$cat_condition[] = "(". implode(" OR ", $orArr). ")";
	}
}

if($cat_condition){
	$where[] = implode(" AND ", $cat_condition);
}

$where[] = " post_type = 'download' ";
if($where){
	$whereCondition = "where ".implode(" AND ",$where);
}
else{
	$whereCondition = "";
}

$search_query = "select p.ID , post_title , post_name , (sum(meta_value) / 5) as average_rating , count(comment_ID) as count_rating  from wp_posts p 
				 left join
				 (
				    select c.comment_ID , comment_post_ID , meta_value from wp_comments c 
				    inner join wp_commentmeta cm on (c.comment_ID = cm.comment_id and cm.meta_key = 'edd_rating')
				    where c.comment_approved = 1 and meta_value != '' order by meta_value DESC
				 )
				 as wm on (p.ID = wm.comment_post_ID)
				 $whereCondition group by p.ID order by count_rating DESC , average_rating DESC";
$page = (int)$_GET['page'];		
if(!$page){
	$page = 1;
}		 

$splitPage = new splitPageResults($search_query , 5 , "", $page);			
$downloads = $wpdb->get_results($splitPage->sql_query);

$wp_query->found_posts = $splitPage->number_of_rows;
//print_R($search_query); exit;
?>
<div class="container result-search main-body">
  <div class="row">
	 <div class="left-container col-xs-12 col-sm-4 col-md-3 sidebar">
		<aside id="selected-categories" class="widget download-single-widget widget_edd_categories_tags_widget">
			<h1 class="download-single-widget-title"></h1>
			<ul class="edd-taxonomy-widget">
				<li class="cat-item cat-item-15">
					<a class="filter-banner">HA SELECCIONADO</a>
					<ul class="children selected-cat">
						<?php foreach($pcats as $key=>$pcat) {?>
						<li class="cat-item cat-item-selected">
								<?php 
									echo '<span class="pcat">'.$pcat->name.'</span>'; 
									$icats = $ccats[$key];
									foreach($icats as $ccat) {
										echo '<span title="Haga clic para deseleccionar." class="icon-cat" data-slug="'.$ccat->slug.'">'.$ccat->name.'<i class="icon"></i></span>'; 
									}
								?>
								<a>&nbsp;</a>
						</li>
						<?php }
							if(count($pcats) == 0) {
						?>
						<li class="cat-item cat-item-21">
								<a>Todas las categorías</a>
						</li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</aside>
				
		<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
	</div>

	<div id="content" class="right-container col-xs-12 col-sm-8 col-md-9 site-content ">
	  <div class="download-product-review-details content-items clearfix">
		 <section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-12 col-xs-12">
			<main id="main" class="site-main" role="main">
				
				<!--  <div class="the-title-home"><?php //marketify_downloads_section_title();?></div> -->
				<div class="result-info clearfix">
					<div class="result fontsforweb_fontid_9785 left"><?php echo $splitPage->number_of_rows?> resultados</div>
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

				<?php if ( $downloads ) : ?>
					<div class="download-grid-wrapper  search-result clearfix" d>
						<?php foreach($downloads as $post):?>
							<?php //get_template_part( 'content-grid', 'download' ); ?>
							
							<div  id="post-<?php the_ID(); ?>" class="content-grid-download row">
								<div class="col-md-3">
									<?php edd_get_template_part( 'shortcode', 'content-image' ); ?>
								</div>
								
								<div class="col-md-6">
									<div>
										<?php edd_get_template_part( 'shortcode', 'content-title' ); ?>
									</div>
									
									<?php $data_custom = get_post_custom($post->ID);?>
									<div class="des">
                                         <?php $text=$data_custom['add_description'][0];  ?>
                                         <?php $text=  substr($text, 0 ,170); echo $text; ?> ...
				                    </div>
									
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
									<div class="form-horizontal ">
										<div class="control-group row">
											<span class="control-label col-md-3">MATERIA:</span>
											<span class="controls green-light sub"><?php echo $category_str;?></span>
										</div>
										<div class="control-group row">
											<span class="control-label col-md-3">NIVEL:</span>
											<span class="controls green-light grades"><?php echo ($data_custom['pick_grade_level(s)'][0]); ?></span>
										</div>
										<div class="control-group row ">
											<span class="control-label lv2 col-md-3">TIPO:</span>
											<span class="controls green-light resource-type"><?php echo str_replace('|', ',', $data_custom['pick_resource_type'][0]); ?></span>
										</div>
									</div>	
								</div>
								
								<div class="col-md-3" >
									<div class="download-product-details action-container" style="padding:5px 0 5px 10px;"><!--#action-container -->
										<div class="price"><?php echo edd_cart_item_price( $post->ID, $post->options );?></div>
										<br />
										<div class="control-group">
											<span class="control-label lv2">EVALUACIÓN DEL PRODUCTO:</span>
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
												<div class="ratings"><?php echo $ratingCount; ?> comentario(s)</div>
											</span>
										</div>
										<br />
										
										<div class="type">Tipo de archivo</div>
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
											<i class="add-wl"></i>LISTA DE DESEOS</a>
										</div>
									</div><!--#action-container -->
								</div>
								<br clear="all" />
							</div><!-- #post-## -->
						<?php endforeach; ?>
					</div>

					<?php if($splitPage->number_of_rows > 5):?>
						<div id="edd_download_pagination" class="navigation">
							<?php $_SERVER['QUERY_STRING'] = preg_replace("/page=[0-9+]/is","",$_SERVER['QUERY_STRING']);?>
							<?php echo $splitPage->display_links("3",$_SERVER['QUERY_STRING']);?>
						</div>
					<?php endif;?>	
					
					<?php //marketify_content_nav( 'nav-below' ); ?>
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