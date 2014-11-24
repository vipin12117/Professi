<?php
/**
 * Template Name: Vendor
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

$author = get_query_var( 'vendor' );
$author = get_user_by( 'slug', $author );

if ( $author ) {
    $authorID = get_current_user_id();
    $avatar = get_avatar( $authorID, 150, apply_filters( 'marketify_default_avatar', null ) );
}

get_header(); ?>

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
                                <div class="content-grid-download">
                                    <div class="entry-image">
                                        <?php echo get_avatar($author->ID , 130);;?>
                                    </div>
                                </div>
                            </div>
                            <div class="left fontsforweb_MyriadPro">
                                <div class="teacher-name fontsforweb_fontid_9785"><?php echo $author->display_name;?></div>
                                <br />
                                
								<div class="teacher-local gray-light"> 
                               		<?php echo get_user_meta($author->ID, 'location' , 1);  ?> 
                               	</div>
                                
                                <div class="teacher-ratings gray-light">Evaluación del usuario: <span></span></div>
                                
                                <br />
                                <div class="teacher-store gray-light">N° de productos en mi tienda: <span><?php echo marketify_count_user_downloads( $author->ID ); ?></span></div>
                                
                                <br />
                                <div class="title-right right" style="padding-right: 50px;"><a href="<?php echo esc_url( home_url( '/fes-vendor/'.$author->display_name ) ); ?>">Ver todos mis  <?php echo $products;?> productos <i class="glyphicon glyphicon-play"></i></a></div>
                            </div>
                        </div>
                        <div class="form-horizontal">
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
                    <div class="teacher-dl content-items left">
	                    <?php
		                    wp_reset_query();
		                    $download_ = new WP_Query( array(
		                    	'post_type'   => 'download',
		                    	'post_status' => 'publish',
		                    	'post_author' => $author->ID,
		                        'posts_per_page' => 2
		                    ));
	                   		$products = marketify_count_user_downloads( $author->ID );
	                    ?>
                    </div>
                </div>
                <hr/>
              </div>
	        
	        <?php endif;?>    
            
            <div class="title-top-container header clearfix">
                <div class="title-top page-title fontsforweb_fontid_9785 left">PRODUCTOS DESTACADOS  <?php echo $author->display_name;?></div>
                <div class="title-right right"><a href="<?php echo esc_url( home_url( '/fes-vendor' ) ); ?>">ver todos <i class="glyphicon glyphicon-play"></i></a></div>
            </div>
            
            <div class="download-product-review-details content-items clearfix">
                <section id="primary" class="content-area col-md-12 col-sm-12 col-xs-12">
                    <main id="main" class="site-main" role="main">
                        <div class="the-title-home">PRODUCTOS DESTACADOS</div>
                        <div class="clearfix">
                            <?php echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>
                        </div>
                    </main><!-- #main -->
                </section><!-- #primary -->
            </div>
        </div><!-- #content -->
    </div>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
