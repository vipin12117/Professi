<?php
/**
 * Template Name: Vendor
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

include_once get_template_directory().'/split_page_results.php';

$author = get_query_var( 'vendor' );
$author = get_user_by( 'slug', $author );

if ( $author ) {
    $authorID = $author->ID;
    $avatar = get_avatar( $authorID, 150, apply_filters( 'marketify_default_avatar', null ) );
}

get_header(); 

$author_cond = "";
if($author){
	$author_cond = " and p.post_author = '".$author->ID."' ";
}

$post_ids = array();
$result = $wpdb->get_results("select post_id , abs(meta_value) as meta_value from wp_postmeta pm inner join wp_posts p on (p.ID = pm.post_id)
							  where pm.meta_key = 'sort_order' and p.post_type = 'download' and p.post_status = 'publish' 
							  order by meta_value ASC");
if($result){
	foreach($result as $p){
		$post_ids[] = $p->post_id;
	}
	$post_ids_str = implode(",",$post_ids);
	$sort_order = "order by field(p.ID , $post_ids_str) ";//, count_rating DESC , average_rating DESC";
}
else{
	$sort_order = "order by count_rating DESC , average_rating DESC";
}

$search_query = "select p.ID , p.post_type, p.post_author , post_title , post_name , (sum(wm.meta_value) / 5) as average_rating , count(comment_ID) as count_rating  from wp_posts p 
				 left join
				 (
				    select c.comment_ID , comment_post_ID , meta_value from wp_comments c 
				    inner join wp_commentmeta cm on (c.comment_ID = cm.comment_id and cm.meta_key = 'edd_rating')
				    where c.comment_approved = 1 and meta_value != '' order by meta_value DESC
				 )
				 as wm on (p.ID = wm.comment_post_ID)
				 left join wp_postmeta pm on (p.ID = pm.post_id) 
				 where  post_status = 'publish' and post_type = 'download'
				 $author_cond
				 group by p.ID $sort_order";
				 //pm.meta_key = 'is_featured_download' and  pm.meta_value = 'Yes' and

$page = (int)$_GET['pg'];		
if(!$page){
	$page = 1;
}	

$page_url = home_url('/fes-vendor/');
if($author){
	$page_url = str_replace( 'vendor', 'fes-vendor', marketify_edd_fes_author_url( $author->ID ) );
}

$limit = 9;
$splitPage = new splitPageResults($search_query , $limit , $page_url , $page);			
$downloads = $wpdb->get_results($splitPage->sql_query);
?>
<div class="container vendor main-body">
    <?php while ( have_posts() ) : the_post(); ?>
    <div class="row">
        <div class="left-container col-xs-12 col-sm-4 col-md-4 sidebar">
            <?php dynamic_sidebar( 'sidebar-download-single' ); ?>
        </div>

        <div id="content" class="right-container col-xs-12 col-sm-8 col-md-8 site-content ">
        	<?php if(!$author):?>
	            <div class="title-top-container header clearfix">
	                <div class="title-top page-title fontsforweb_fontid_9785 left">NUESTROS PROFE-VENDEDORES</div>
	                <div class="title-right right"><a href="<?php echo esc_url( home_url( '/fes-vendor' ) ); ?>">ver todos <i class="glyphicon glyphicon-play"></i></a></div>
	            </div>
	            <div class="download-product-review-details content-items clearfix">
	                <section id="primary" class="content-area col-md-12 col-sm-12 col-xs-12">
	                    <main id="main" class="site-main" role="main">
	
	                        <div class="the-title-home">PROFE-VENDEDORES</div>
	                        <div class="teacher-autors clearfix">
	                            <?php echo pippin_list_authors(); ?>
	                            <?php //echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>
	                        </div>
	                    </main><!-- #main -->
	                </section><!-- #primary -->
	            </div>
	            
	        <?php else:?>
	        	<div class="title-top-container header clearfix">
                	<div class="title-top page-title fontsforweb_fontid_9785">PROFE-VENDEDOR</div>
            	</div>
            
            	<div class="teacher-author">
               	  <hr/>
                  <div class="teacher-container clearfix">
               	     <div class="teacher-info left">
                        <div class="content-items clearfix">
                            <div class="teacher-autors image-info left">
                                <div class="content-grid-download2">
                                    <div class="entry-image">
                                        <?php echo get_avatar($author->ID , 130);;?>
                                    </div>
                                </div>
                            </div>
                            <div class="fontsforweb_MyriadPro">
                                <div class="teacher-name fontsforweb_fontid_9785"><?php echo $author->display_name;?></div>
                                <br />
                                
								<div class="teacher-local gray-light"> 
                               		<?php echo get_user_meta($author->ID, 'location' , 1);  ?> 
                               	</div>
                                
                                <div class="teacher-ratings gray-light">Evaluación del usuario: <span></span></div>
                                
                                <br />
                                <div class="teacher-store gray-light">N° de productos en mi tienda: <span><?php echo marketify_count_user_downloads( $author->ID ); ?></span></div>
                                
                                <br />
                                <?php $products = marketify_count_user_downloads( $author->ID );?>
                                <div class="title-right right" style="padding-right: 50px;"><a href="<?php echo esc_url( home_url( '/fes-vendor/'.$author->display_name ) ); ?>">Ver todos mis  <?php echo $products;?> productos <i class="glyphicon glyphicon-play"></i></a></div>
                            </div>
                        </div>
                        <div class="form-horizontal" style="margin-top:0;">
                            <div class="control-group">
                                <span class="control-label"><b>EDAD DE MIS ALUMNOS: </b></span>
                                <span class="controls gray-light"><?php echo get_user_meta($author->ID, 'what_level_do_you_teach?' , 1);  ?></span>
                            </div>
                            <div class="control-group">
                                <span class="control-label"><b>MATERIA:</b></span>
                                <span class="controls gray-light"><?php echo get_user_meta($author->ID, 'what_subject_do_you_teach?' , 1);  ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
              </div>
	        
	        <?php endif;?>    
            
            <div class="title-top-container header clearfix">
                <div class="title-top page-title fontsforweb_fontid_9785 left">PRODUCTOS DESTACADOS  <?php echo $author->display_name;?></div>
            </div>
            
            <div class="download-product-review-details content-items clearfix">
                <section id="primary" class="content-area col-md-12 col-sm-12 col-xs-12">
                    <main id="main" class="site-main" role="main">
                        <div class="the-title-home">PRODUCTOS DESTACADOS</div>
                        	<?php if($downloads):?>
		                        <div class="clearfix">
		                            <?php //echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>
		                            
		                            <?php global $post; foreach($downloads as $post):?>
											<div class="col-md-4">
												<?php  get_template_part( 'content-grid-download'); ?>
											</div>
									<?php endforeach;?>
									
									<br clear="all" />
									
									<?php if($splitPage->number_of_rows > $limit):?>
										<div id="edd_download_pagination" class="navigation">
											<?php echo $splitPage->display_links("5",null,'pg');?>
										</div>
									<?php endif;?>	
		                        </div>
		                   <?php else:?>
		                   
		                   		<p>No ha subido productos</p>
		                   		
		                   <?php endif;?>     
                    </main><!-- #main -->
                </section><!-- #primary -->
            </div>
        </div><!-- #content -->
    </div>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>