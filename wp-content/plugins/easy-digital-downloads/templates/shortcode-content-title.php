<h3 itemprop="name" class="edd_download_title">
	<a title="<?php the_title_attribute(); ?>" itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h3>

<?php if ( marketify_is_multi_vendor() ) : ?>
	<?php
		global $wpdb , $post;
		$user_id = $post->post_author; 
		$author_data =  $wpdb->get_col("select display_name from wp_users where ID = '$user_id'");
		
		printf(
			__( '<span class="byline"> por <span class="user">%1$s</span></span>', 'marketify' ),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
				//marketify_edd_fes_author_url( get_the_author_meta( 'ID' ) ),
				str_replace( 'vendor', 'fes-vendor', marketify_edd_fes_author_url( $user_id ) ) ,
				esc_attr( sprintf( __( 'Visite la %s de %s', 'marketify' ), 'tienda', $user_id ) ),
				esc_html(  $author_data[0] ),
				get_avatar( $user_id, 50, apply_filters( 'marketify_default_avatar', null ) )
			)
		);
	?>
<?php endif;?>