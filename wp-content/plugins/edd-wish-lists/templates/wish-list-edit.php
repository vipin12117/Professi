<?php
/**
 * Edit Wish List template
*/

$wish_list  = get_post( get_query_var('edit') );
$post_id    = $wish_list->ID;
$content    = $wish_list->post_content;
$title      = get_the_title( $post_id );
$privacy    = get_post_status( $post_id );

?>

<h3>
	<?php _e( 'EDITAR LISTA DE DESEOS', 'edd-wish-lists' ); ?>
</h3>

<form action="<?php echo add_query_arg( 'updated', true ); ?>" class="wish-list-form" method="post">
	<p>
	    <label for="list-title"><?php _e( 'Título:', 'edd-wish-lists' ); ?> <span class="required">*</span></label>
	    <input type="text" name="list-title" id="list-title" value="<?php echo esc_attr( $title ); ?>">
	</p>
	<p>
	    <label for="list-description"><?php _e( 'Descripción:', 'edd-wish-lists' ); ?></label>
	    <textarea name="list-description" id="list-description" rows="2" cols="30"><?php echo esc_attr( $content ); ?></textarea>
	</p>
	<p>
	  <select name="privacy">
	    <option value="private" <?php selected( $privacy, 'private' ); ?>><?php _e( 'Privado - sólo visible para usted', 'edd-wish-lists' ); ?></option>
	    <option value="publish" <?php selected( $privacy, 'publish' ); ?>><?php _e( 'Pública - visible por cualquier persona', 'edd-wish-lists' ); ?></option>
	  </select>
	</p>
	<p> 
	    <input type="submit" value="<?php _e( 'ACTUALIZAR', 'edd-wish-lists' ); ?>" class="button">
	</p>

	<input type="hidden" name="submitted" id="submitted" value="true">
	
	<?php wp_nonce_field( 'list_nonce', 'list_nonce_field' ); ?>
</form>

<?php echo edd_wl_delete_list_link(); ?>