<?php
$user_id = get_current_user_id();
$concat= get_option("permalink_structure")?"?":"&";
$user_id = get_current_user_id();
$pending_products = EDD_FES()->queries->get_pending_products( $user_id );
$published_products = EDD_FES()->queries->get_published_products( $user_id );

if ( !empty( $pending_products ) ) : ?>
	<h2><?php _e( 'Pending Review', 'edd_fes' ); ?></h2>

<table class="fes_table table-condensed" id="fes-pending-products">
	<thead>
		<tr>
			<th><?php _e( 'Product', 'edd_fes' ); ?></th>
			<th><?php _e( 'Date/Time Submitted for Review', 'edd_fes' ); ?></th>
	</thead>
	<tbody>

		<?php foreach ( $pending_products as $product ) : ?>
				<tr>
					<td><?php echo esc_html($product['title']); ?></td>
					<td><?php echo esc_html($product['date']); ?></td>
				</tr>
		<?php endforeach; ?>

	</tbody>
</table>
<?php endif;
if ( !empty( $published_products ) ) : ?>
	<h2><?php _e( 'Live Products', 'edd_fes' ); ?></h2>

<table class="fes_table table-condensed" id="fes-published-products">
	<thead>
		<tr>
			<th><?php _e( 'Product', 'edd_fes' ); ?></th>
			<th><?php _e( 'Sales Quantity', 'edd_fes' ) ?></th>
			<th><?php _e( 'Actions','edd_fes') ?></th>
	</thead>
	<tbody>
		<?php
		$sales = 0;
		$earnings = 0;
	    
	    foreach ( $published_products as $product ) : ?>
			<tr>
				<td><?php echo esc_html($product['title']); ?></td>
				<td><?php echo esc_html($product['sales']); ?></td>
				<td>
				<a href="<?php echo esc_html($product['url']);?>" title="<?php _e('View','edd_fes');?>" class="btn btn-mini view-product-fes"><?php _e('View','edd_fes');?></a>
				<?php if( EDD_FES()->fes_options->get_option( 'edd_fes_vendor_permissions_edit_product' ) ) { ?>
				<a href="<?php echo add_query_arg( array('task' => 'edit', 'post_id' => $product['ID'] ), get_permalink() ); ?>" title="<?php _e('Edit','edd_fes');?>" class="btn btn-mini edit-product-fes"><?php _e('Edit','edd_fes');?></a>
				<?php }?>
				<?php if( EDD_FES()->fes_options->get_option( 'edd_fes_vendor_permissions_delete_product' ) ) { ?>
				<a href="<?php echo add_query_arg( array('task' => 'delete', 'post_id' => $product['ID'] ), get_permalink() );?>" title="<?php _e('Delete','edd_fes');?>" class="btn btn-mini edit-product-fes"><?php _e('Delete','edd_fes');?></a>
				<?php }?>
				</td>
			</tr>
        <?php $sales += $product['sales'];?>
		<?php endforeach; ?>
		<tr>
			<td><strong><?php _e( 'Total Sales', 'edd_fes' ); ?></strong></td>
			<td><?php echo $sales; ?></td>
			<td></td>
		</tr>
	</tbody>
</table>
<?php endif;

if (empty($pending_products) && empty($published_products)){
	echo __('You have no products','edd_fes');
}