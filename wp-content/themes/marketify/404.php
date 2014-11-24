<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Marketify
 */

get_header(); ?>

	<header class="page-header">
		<h1 class="page-title"><?php _e( '¡Página no encontrada!', 'marketify' ); ?></h1>
	</header><!-- .page-header -->

	<?php do_action( 'marketify_entry_before' ); ?>

	<div class="container">
		<div id="content" class="site-content row">
		</div>
	</div>

<?php get_footer(); ?>