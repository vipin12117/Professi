<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); 

$data_custom = get_post_custom($post->ID);

$rating = edd_reviews()->average_rating( false );
$full = intval($rating);
$ratingCount = edd_reviews()->count_reviews();
$author = get_the_author();
?>
<div class="container post-container clearfix">
		<div class="left-post left"><!--#left-post -->
			<header class="post-header">
				<?php the_post(); ?>
				<div class="post-title fontsforweb_fontid_9785">
					<span><!--BOOK NAME:--> </span><span><?php the_title();?></span>
				</div>
				<div class="header-container clearfix">
					<div class="left">
						<div class="download-product-details">
							<div class="entry-image">
								<?php if ( class_exists( 'MultiPostThumbnails' ) && MultiPostThumbnails::get_the_post_thumbnail( 'download', 'grid-image' ) ) : ?>
								<?php MultiPostThumbnails::the_post_thumbnail( 'download', 'grid-image', null, 'content-grid-download' ); ?>
								<?php elseif ( has_post_thumbnail() ) : ?>
									 <?php $full_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');  ?>
									<a class="image-box" href="<?php echo $full_img[0]; ?>">
										<?php the_post_thumbnail("content-grid-download"); ?>
									</a>
								<?php else : ?>
									<span class="image-placeholder"></span>
								<?php endif; ?>
							</div>
							<div class="more-images">
								<?php $preview_file_1 = unserialize($data_custom['muestra_del_producto_-_imagen_#1'][0]);?>
								<?php 
									if($preview_file_1[0]){
										$preview_file1_url = $wpdb->get_col("select guid from wp_posts where ID = '".$preview_file_1[0]."'");
									}
								?>
								<a href="<?php echo $preview_file1_url[0]; ?>" rel="lightbox">
									<img width="70" src="<?php echo $preview_file1_url[0]; ?>"/>
								</a>
								
								<?php $preview_file_2 = unserialize($data_custom['muestra_del_producto_-_imagen_#2'][0]);?>
								<?php 
									if($preview_file_2[0]){
										$preview_file2_url = $wpdb->get_col("select guid from wp_posts where ID = '".$preview_file_2[0]."'");
									}
								?>
								<a href="<?php echo $preview_file2_url[0]; ?>" rel="lightbox">
									<img width="70" src="<?php echo $preview_file2_url[0]; ?>"/>
								</a>
								
								<?php $preview_file_3 = unserialize($data_custom['muestra_del_producto_-_imagen_#3'][0]);?>
								<?php 
									if($preview_file_3[0]){
										$preview_file3_url = $wpdb->get_col("select guid from wp_posts where ID = '".$preview_file_3[0]."'");
									}
								?>
								<a href="<?php echo $preview_file3_url[0]; ?>" rel="lightbox">
									<img width="70" src="<?php echo $preview_file3_url[0]; ?>"/>
								</a>
							</div>
						</div>
					</div>
					<?php 
						  $category_str = '';
						  $categories = (array)get_the_terms( $post->ID, 'download_category' );
						  foreach($categories as $category){
						  	  if(in_array($category->parent , array(52,53,63,72,81,85,92))){
						  		  $category_str .= $category->name.",";
						  	  }
						  }
					?>
					<div class="teacher-info fontsforweb_fontid_9785 left">
						<div class="form-horizontal">
							<div class="control-group">
								<span class="control-label">MATERIA </span>
								<span class="controls gray-light"><?php echo $category_str;?></span>
							</div>
							<div class="control-group">
								<span class="control-label">NIVEL (EDAD)</span>
								<span class="controls gray-light"><?php echo ($data_custom['seleccione_el_nivel'][0]); ?></span>
							</div>
							<div class="control-group">
								<span class="control-label lv2">TIPO DE RECURSO </span>
								<span class="controls gray-light"><?php echo str_replace('|', ',', $data_custom['tipo_de_recurso'][0]); ?></span>
							</div>
							<div class="control-group">
								<span class="control-label lv2">EVALUACIÓN DEL PRODUCTO </span>
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
									<div class="ratings"><?php echo $ratingCount; ?>  comentario(s)</div>
								</span>
							</div>
							<div class="control-group clearfix">
								<span class="control-label lv2"></span>
								<br/>
								<span class="control-label lv2 left"></span>
								<span class="controls file-comment">Asegúrese de tener un programa para abrir este tipo de archivo antes de descargar y / o comprar.</span>
								<br/>
								<span class="control-label lv2 left"></span>
								
								<?php $edd_download_files = unserialize($data_custom['edd_download_files'][0]);?>
								
								<?php if(!$edd_download_files[0]['file']):?>
									<?php $edd_download_files[0]['file'] = $edd_download_files[0];?>
								<?php endif;?>
								<?php $file_parts = explode(".",$edd_download_files[0]['file']);?>
								
								<span class="controls file-comment">
									<?php echo ucfirst(end($file_parts));?> (<?php echo getSizeFile($edd_download_files[0]['file']);?> MB)  |  
									
									<?php echo $data_custom['add_number_of_pages_or_slides'][0]?> páginas
								</span>
							</div>
						</div>
					</div>
				</div>
				<?php rewind_posts(); ?>
			</header><!-- .page-header -->
			<hr/>
			<div class="description-post">
				<div class="post-title fontsforweb_fontid_9785">
					<span>DESCRIPCIÓN DEL PRODUCTO</span>
				</div>
				<div class="post-description fontsforweb_fontid_9785">
					<span class="gray-light" style="float:left;word-wrap:break-word;width:95%"><?php echo str_replace(array("\n"), '<br/>', $data_custom['descripción_del_producto'][0]); ?></span>
				</div>
				<br clear="all" />
				<div class="info-post">NÚMERO DE PÁGINAS:<span class="gray-light"><?php echo $data_custom['add_number_of_pages_or_slides'][0]?></span></div>
				<div class="info-post">DURACIÓN DE LA ENSEÑANZA:<span class="gray-light"><?php echo $data_custom['add_teaching_duration'][0]?></span></div>
				<div class="report-post gray-light"><a href="mailto:<?php echo get_the_author_meta( 'email' )?>"><i class="report-icon"></i> Infórmenos de cualquier violación de derechos del autor </a></div>
			</div>
			<hr/>
			<div class="comment-post" style="display:none;">
				<div class="post-title fontsforweb_fontid_9785">
					<span>COMENTARIOS Y RATINGS</span>
				</div>
				<div style="padding-left:20px"><!-- #comment-content -->
					<div class="info-post">Media de valoraciones</div>
					<div class="row clearfix">
						<div class="col-md-4">
								<div class="type-ratings left">Calidad general:</div>
								<div class="star-ratings left">
									<i class="star star-full"></i>
									<i class="star star-full"></i>
									<i class="star star-half"></i>
									<i class="star star-no"></i>
									<i class="star star-no"></i>
								</div>
								<div class="type-ratings left">PrecisiÃ³n:</div>
								<div class="star-ratings left">
								<?php for($i = $j; $i < 5; ++ $i)  {?>
									<i class="star star-full"></i>
								<?php } ?>
								</div>
								<div class="type-ratings left">AplicaciÃ³n en la prÃ¡ctica:</div>
								<div class="star-ratings left">
									<?php for($i = $j; $i < 5; ++ $i)  {?>
									<i class="star star-full"></i>
								<?php } ?>
								</div>
						</div>
						<div class="col-md-4">
							<div class="type-ratings left">Throughness:</div>
								<div class="star-ratings left">
								<?php for($i = $j; $i < 5; ++ $i)  {?>
									<i class="star star-full"></i>
								<?php } ?>
								</div>
								<div class="type-ratings left">Creatividad:</div>
								<div class="star-ratings left">
								<?php for($i = $j; $i < 5; ++ $i)  {?>
									<i class="star star-full"></i>
								<?php } ?>
								</div>
								<div class="type-ratings left">Claridad:</div>
								<div class="star-ratings left">
									<?php for($i = $j; $i < 5; ++ $i)  {?>
									<i class="star star-full"></i>
								<?php } ?>
								</div>
						</div>
						<div class="col-md-4">
							<div class="left" style="width:60px;">total:</div>
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
								echo '&nbsp;'.$rating; 
								?></span>
							</div>
							<div class="ratings" style="padding-left:60px;"><?php echo $ratingCount; ?> calificaciones</div>
						</div>
					</div>
				</div><!-- #comment-content -->
			</div>
			<hr/>
			<div class="comment-container">
				<?php comments_template(); ?>
			</div>
			
			<div style="display:none" id="hidden-old-data"><!-- #hidden old logic -->
				<?php
					if (
						'grid' == marketify_theme_mod( 'product-display', 'product-display-single-style' ) &&
						'1' == marketify_theme_mod( 'product-display', 'product-display-show-buy' )
					) :
				?>
					<div class="download-actions">
						<?php do_action( 'marketify_download_actions' ); ?>
					</div>
				<?php endif; ?>
	
				<?php if ( 'classic' == marketify_theme_mod( 'product-display', 'product-display-single-style' ) ) : ?>
					<div class="download-actions">
						<?php do_action( 'marketify_download_actions' ); ?>
					</div>
	
					<div class="download-info">
						<?php do_action( 'marketify_download_info' ); ?>
					</div>
	
					<div class="featured-image container">
						<?php do_action( 'marketify_download_featured_area' ); ?>
					</div>
				<?php endif; ?>
			
				<?php //do_action( 'marketify_entry_before' ); ?>
	
				<div id="content" class="site-content row">
					<section id="primary" class="content-area <?php echo ! is_active_sidebar( 'sidebar-download-single' ) ? 'col-xs-12' : 'col-md-8 col-sm-8 col-xs-12'; ?>">
						<main id="main" class="site-main" role="main">
	
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content-single', 'download' ); ?>
						<?php endwhile; rewind_posts(); ?>
	
						</main><!-- #main -->
					</section><!-- #primary -->
	
					<?php //get_sidebar( 'single-download' ); ?>
	
				</div><!-- #content -->
	
				<?php do_action( 'marketify_single_download_after' ); ?>
			</div><!-- #hidden old logic -->
		</div><!--#left-post -->
		
		<div class="right-post left">
			<div class="download-product-details action-container fontsforweb_fontid_9785"><!--#action-container -->
				<div class="price"><?php echo edd_cart_item_price( $post->ID, $post->options );?></div>
				<!--<div class="type">Digital Download</div>-->
				<div class="add-to-card"><a id="main-add-to-card" href="#">COMPRAR</a></div>
				<!-- <div class="by-licence"><a href="#">BUY LICENCE TO SHARE</a></div> -->
				
				<div class="add-wish-list"><a class="edd-add-to-cart-from-wish-list edd-wl-open-modal edd-has-js" href="#"
				data-action="edd_wl_open_modal"
				data-download-id="<?php echo $post->ID; ?>"
				data-variable-price="no"
				data-price-mode="single"
				><i class="add-wl"></i>LISTA DE DESEOS</a></div>
				<hr/>
				<div class="user-info clearfix">
					<div class="avatar-info left">
						<div class="download-product-details radius50">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 100, apply_filters( 'marketify_default_avatar', null ) );?>
						</div>
					</div>
					<div class="info left">
						<div class="madeby-lb gray-light"><i>Hecho por</i></div>
						<div class="teacher-name fontsforweb_fontid_9785">
							<?php $archive_url = str_replace( 'vendor', 'fes-vendor', marketify_edd_fes_author_url( get_the_author_meta( 'ID' ) ) );?>
							<a href="<?php echo $archive_url?>"	 title="<?php echo esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), $author ) );?>"><?php echo esc_html( get_the_author_meta( 'display_name' ) );?></a>
						</div>
						<div class="user-rating gray-light"> profe-vendedor</div>
						<div style="padding: 20px 0px 0px 5px;"><a href="<?php echo $archive_url; ?>">Visite mi tienda <i class="glyphicon glyphicon-play"></i></a></div>
					</div>
				</div>
				
			</div><!--#action-container -->
			<br/>
			<div class="download-product-details download-list"><!--#download-list -->
				<div class="top-list"><span>MÁS PRODUCTOS CREADOS POR:</span></div>
				<div class="user-info clearfix" style="padding: 0px 0px 10px 20px">
					<div class="avatar-info left"  style="width:90px">
						<div class="download-product-details radius50">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 100, apply_filters( 'marketify_default_avatar', null ) );?>
						</div>
					</div>
					<div class="info left" style="padding-top:40px">
						<div class="teacher-name fontsforweb_fontid_9785">
							<?php echo esc_html( get_the_author_meta( 'display_name' ) );?>
						</div>
						<div class="teacher-local gray-light">
                            <?php echo get_user_meta(get_the_author_ID() , 'location' , 1);  ?> 
						</div>
					</div>
				</div>
				<?php
					wp_reset_query(); 
					
					$downloads = new WP_Query( array(
						'post_type'   => 'download',
						'post_status' => 'publish',
						'author' => get_the_author_meta( 'ID' ),
						'posts_per_page' => 3
					) );
					
					//print_r($downloads); exit;
				?>			
				<div class="wishlist">
					<div class="dlcontainer">
						<?php while ( $downloads->have_posts() ) : $downloads->the_post(); ?>
							<div class="content-items">
								<?php get_template_part( 'content-grid', 'download' ); ?>
							</div>
						<?php endwhile; ?>
					</div>
					<div style="">
						<a href="<?php echo $archive_url;?>"
							title="<?php echo esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), $author ) );?>">Ver todos <i class="glyphicon glyphicon-play"></i></a>
					</div>
				</div>
				<?php 
					wp_reset_query(); 
				?>		
			</div><!--#download-list -->
		</div>
	</div>
<?php get_footer(); ?>
