<?php global $products; ?>
<h1 class="fes-headers" id="fes-products-page-title"><?php echo EDD_FES()->vendors->get_product_constant_name( $plural = true, $uppercase = true ) ?></h1>
<?php echo EDD_FES()->dashboard->product_list_status_bar(); ?>

<table class="table fes-table table-condensed  table-striped" id="fes-product-list">
	<thead>
		<tr>
			<th><?php _e( 'IMAGEN', 'edd_fes' ); ?></th>
			<th><?php _e( 'NOMBRE', 'edd_fes' ); ?></th>
			<th><?php _e( 'ESTADO', 'edd_fes' ); ?></th>
			<th><?php _e( 'PRECIO', 'edd_fes' ); ?></th>
			<th><?php _e( 'VENTAS', 'edd_fes' ) ?></th>
			<th><?php _e( 'ACCIONES','edd_fes') ?></th>
			<th><?php _e( 'FECHA', 'edd_fes' ); ?></th>
			<?php do_action('fes-product-table-column-title'); ?>
		</tr>
	</thead>
	<tbody>
		<?php
		if (count($products) > 0 ){
		foreach ( $products as $product ) : ?>
			<tr>
				<td class = "fes-product-list-td"><?php echo get_the_post_thumbnail( $product->ID, array(100,100)); ?></td>
				<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_title($product->ID); ?></td>
				<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_status($product->ID); ?></td>
				<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_price($product->ID); ?></td>
				<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_sales_esc($product->ID); ?></td>
				<td class = "fes-product-list-td"><?php EDD_FES()->dashboard->product_list_actions($product->ID); ?></td>
				<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_date($product->ID); ?></td>
				<?php do_action('fes-product-table-column-value'); ?>
			</tr>
		<?php endforeach;
		}
		else{
			echo '<tr><td colspan="7" class = "fes-product-list-td" >'.__('No ha subido productos', 'edd_fes').'</td></tr>';
		}
		?>
	</tbody>
</table>
<?php EDD_FES()->dashboard->product_list_pagination();