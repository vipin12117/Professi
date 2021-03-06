<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Marketify
 */

get_header();
global $isCheckout;
$isCheckout = false;
$pagename = get_query_var( 'pagename' );
if($pagename && ($pagename == 'checkout')) {
	$isCheckout = true;
}
function printCheckout($val = '') {
	global $isCheckout;
	if($isCheckout === true) {
		echo $val;
	}
} 
?>
	<div class="container post-container<?php printCheckout(' checkout-container');?> clearfix">
		
		<div id="content" class="site-content row">
	
			<div id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-1' ) ? '8' : '12'; ?> col-xs-12">
				<main id="main" class="site-main" role="main">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post();?>

						<?php get_template_part( 'content', 'page' ); ?>

						<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || '0' != get_comments_number() )
								comments_template();
						?>

					<?php endwhile; ?>

					<?php marketify_content_nav( 'nav-below' ); ?>

				<?php else : ?>
					<?php get_template_part( 'no-results', 'index' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</div><!-- #primary -->
		<?php get_sidebar(); ?>
		</div>
	</div>

<?php get_footer(); ?>
