<h3 itemprop="name" class="edd_download_title">
	<a title="<?php the_title_attribute(); ?>" itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h3>

<?php if ( marketify_is_multi_vendor() ) : ?>
	<?php
		printf(
			__( '<span class="byline"> by <span class="user">%1$s</span></span>', 'marketify' ),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
				//marketify_edd_fes_author_url( get_the_author_meta( 'ID' ) ),
				str_replace( 'vendor', 'fes-vendor', marketify_edd_fes_author_url( get_the_author_meta( 'ID' ) ) ) ,
				esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), get_the_author() ) ),
				esc_html( get_the_author_meta( 'display_name' ) ),
				get_avatar( get_the_author_meta( 'ID' ), 50, apply_filters( 'marketify_default_avatar', null ) )
			)
		);
	?>
<?php endif;?>