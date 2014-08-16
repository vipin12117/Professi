<?php
/**
 * Template Name: Homepage
 *
 * @package Marketify
 */

get_header(); ?>

		<?php the_post(); if ( ! ( '' == $post->post_content && '' == $post->post_title ) ) : ?>
		<header class="page-header">
			<div class="container">
				
			</div>
		</header><!-- .page-header -->
		<?php endif; rewind_posts(); ?>

	</div>

	<div id="content" class="site-content">
	    
	    <?php the_content(); ?>
	    
		<section id="primary" class="content-area full">
			<main id="main" class="site-main" role="main">

				<div class="container">
					<?php dynamic_sidebar( 'home-1' ); ?>
				</div>

			</main><!-- #main -->
		</section><!-- #primary -->

	</div><!-- #content -->

<?php get_footer(); ?>
