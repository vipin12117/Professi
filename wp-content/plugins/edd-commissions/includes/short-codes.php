<?php

function eddc_user_product_list() {

	$user_id  = get_current_user_id();
	$products = eddc_get_download_ids_of_user( $user_id );

	if( ! is_user_logged_in() )
		return;

	if( empty( $products ) )
		return;

	ob_start(); ?>
	<div id="edd_commissioned_products">
		<h3 class="edd_commissioned_products_header"><?php _e('sus artículos', 'eddc'); ?></h3>
		<table id="edd_commissioned_products_table">
			<thead>
				<tr>
					<?php do_action( 'edd_commissioned_products_head_row_begin' ); ?>
					<th class="edd_commissioned_item"><?php _e('artículo', 'eddc'); ?></th>
					<th class="edd_commissioned_sales"><?php _e('venta', 'eddc'); ?></th>
					<?php do_action( 'edd_commissioned_products_head_row_end' ); ?>
				</tr>
			</thead>
			<tbody>
			<?php if( ! empty( $products ) ) : ?>
				<?php foreach( $products as $product ) : if( ! get_post( $product ) ) continue; ?>
					<tr class="edd_user_commission_row">
						<?php
						do_action( 'edd_commissioned_products_row_begin', $product, $user_id ); ?>
						<td class="edd_commissioned_item"><?php echo get_the_title( $product ); ?></td>
						<td class="edd_commissioned_sales"><?php echo edd_get_download_sales_stats( $product ); ?></td>
						<?php do_action( 'edd_commissioned_products_row_end', $product, $user_id ); ?>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="edd_commissioned_products_row_empty">
					<td colspan="4"><?php _e('Ningún artículo', 'eddc'); ?></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'edd_commissioned_products', 'eddc_user_product_list' );

function eddc_user_commissions( ) {

	global $user_ID;

	if( !is_user_logged_in() )
		return;

	$unpaid_paged = isset( $_GET['eddcup'] ) ? absint( $_GET['eddcup'] ) : 1;
	$paid_paged   = isset( $_GET['eddcp'] ) ? absint( $_GET['eddcp'] ) : 1;

	$unpaid_commissions = eddc_get_unpaid_commissions( array( 'user_id' => $user_ID, 'number' => 20, 'paged' => $unpaid_paged ) );
	$paid_commissions 	= eddc_get_paid_commissions( array( 'user_id' => $user_ID, 'number' => 20, 'paged' => $paid_paged ) );

	$total_unpaid       = eddc_count_user_commissions( $user_ID, 'unpaid' );
	$total_paid         = eddc_count_user_commissions( $user_ID, 'paid' );

	$unpaid_offset      = 20 * ( $unpaid_paged - 1 );
	$unpaid_total_pages = ceil( $total_unpaid / 20 );

	$paid_offset        = 20 * ( $paid_paged - 1 );
	$paid_total_pages   = ceil( $total_paid / 20 );

	$stats 				= '';
	if( eddc_user_has_commissions() ) : // only show tables if user has commission data
		ob_start(); ?>
			<div id="edd_user_commissions">

				<!-- unpaid -->
				<div id="edd_user_commissions_unpaid">
					<h3 class="edd_user_commissions_header"><?php _e('las comisiones pendientes', 'eddc'); ?></h3>
					<table id="edd_user_unpaid_commissions_table" class="edd_user_commissions">
						<thead>
							<tr class="edd_user_commission_row">
								<?php do_action( 'eddc_user_commissions_unpaid_head_row_begin' ); ?>
								<th class="edd_commission_item"><?php _e('artículo', 'eddc'); ?></th>
								<th class="edd_commission_amount"><?php _e('cantidad', 'eddc'); ?></th>
								<th class="edd_commission_rate"><?php _e('tarifa', 'eddc'); ?></th>
								<th class="edd_commission_date"><?php _e('fecha', 'eddc'); ?></th>
								<?php do_action( 'eddc_user_commissions_unpaid_head_row_end' ); ?>
							</tr>
						</thead>
						<tbody>
						<?php $total = (float) 0; ?>
						<?php if( ! empty( $unpaid_commissions ) ) : ?>
							<?php foreach( $unpaid_commissions as $commission ) : ?>
								<tr class="edd_user_commission_row">
									<?php
									do_action( 'eddc_user_commissions_unpaid_row_begin', $commission );
									$item_name 			= get_the_title( get_post_meta( $commission->ID, '_download_id', true ) );
									$commission_info 	= get_post_meta( $commission->ID, '_edd_commission_info', true );
									$amount 			= $commission_info['amount'];
									$rate 				= $commission_info['rate'];
									?>
									<td class="edd_commission_item"><?php echo esc_html( $item_name ); ?></td>
									<td class="edd_commission_amount"><?php echo edd_currency_filter( edd_format_amount( $amount ) ); ?></td>
									<td class="edd_commission_rate"><?php echo $rate . '%'; ?></td>
									<td class="edd_commission_date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $commission->post_date ) ); ?></td>
									<?php do_action( 'eddc_user_commissions_unpaid_row_end', $commission ); ?>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr class="edd_user_commission_row edd_row_empty">
								<td colspan="4"><?php _e('No hay comisiones pendientes', 'eddc'); ?></td>
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
					<div id="edd_user_commissions_unpaid_total"><?php _e('Total no pagado:', 'eddc');?>&nbsp;<?php echo edd_currency_filter( edd_format_amount( eddc_get_unpaid_totals( $user_ID ) ) ); ?></div>

					<div id="edd_commissions_unpaid_pagination" class="navigation">
					<?php
						$big = 999999;
						echo paginate_links( array(
							'base'    => remove_query_arg( 'eddcup', edd_get_current_page_url() ) . '%_%#edd_user_commissions_unpaid',
							'format'  => '?eddcup=%#%',
							'current' => max( 1, $unpaid_paged ),
							'total'   => $unpaid_total_pages
						) );
					?>
					</div>

				</div><!--end #edd_user_commissions_unpaid-->

				<!-- paid -->
				<div id="edd_user_commissions_paid">
					<h3 class="edd_user_commissions_header"><?php _e('Las comisiones pagadas', 'eddc'); ?></h3>
					<table id="edd_user_paid_commissions_table" class="edd_user_commissions">
						<thead>
							<tr class="edd_user_commission_row">
								<?php do_action( 'eddc_user_commissions_paid_head_row_begin' ); ?>
								<th class="edd_commission_item"><?php _e('artículo', 'eddc'); ?></th>
								<th class="edd_commission_amount"><?php _e('cantidad', 'eddc'); ?></th>
								<th class="edd_commission_rate"><?php _e('tarifa', 'eddc'); ?></th>
								<th class="edd_commission_date"><?php _e('fecha', 'eddc'); ?></th>
								<?php do_action( 'eddc_user_commissions_paid_head_row_end' ); ?>
							</tr>
						</thead>
						<tbody>
						<?php $total = (float) 0; ?>
						<?php if( ! empty( $paid_commissions ) ) : ?>
							<?php foreach( $paid_commissions as $commission ) : ?>
								<tr class="edd_user_commission_row">
									<?php
									do_action( 'eddc_user_commissions_paid_row_begin', $commission );
									$item_name 			= get_the_title( get_post_meta( $commission->ID, '_download_id', true ) );
									$commission_info 	= get_post_meta( $commission->ID, '_edd_commission_info', true );
									$amount 			= $commission_info['amount'];
									$rate 				= $commission_info['rate'];
									?>
									<td class="edd_commission_item"><?php echo esc_html( $item_name ); ?></td>
									<td class="edd_commission_amount"><?php echo edd_currency_filter( $amount ); ?></td>
									<td class="edd_commission_rate"><?php echo $rate . '%'; ?></td>
									<td class="edd_commission_date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $commission->post_date ) ); ?></td>
									<?php do_action( 'eddc_user_commissions_paid_row_end', $commission ); ?>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr class="edd_user_commission_row edd_row_empty">
								<td colspan="4"><?php _e('No hay comisiones pagadas', 'eddc'); ?></td>
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
					<div id="edd_user_commissions_paid_total"><?php _e('Total percibido:', 'eddc');?>&nbsp;<?php echo edd_currency_filter( edd_format_amount( eddc_get_paid_totals( $user_ID ) ) ); ?></div>

					<div id="edd_commissions_paid_pagination" class="navigation">
					<?php
						$big = 999999;
						echo paginate_links( array(
							'base'    => remove_query_arg( 'eddcp', edd_get_current_page_url() ) . '%_%#edd_user_commissions_paid',
							'format'  => '?eddcp=%#%',
							'current' => max( 1, $paid_paged ),
							'total'   => $paid_total_pages
						) );
					?>
					</div>

					<div id="edd_commissions_export">
						<p><strong><?php _e( 'Comisiones exportación Pagado', 'eddc' ); ?></strong></p>
						<form method="post" action="<?php echo home_url(); ?>">
							<?php echo EDD()->html->month_dropdown(); ?>
							<?php echo EDD()->html->year_dropdown(); ?>
							<input type="hidden" name="edd_action" value="generate_commission_export"/>
							<input type="submit" class="edd-submit button" value="<?php _e( 'Descarga CSV', 'eddc' ); ?>"/>
						</form><br/>
					</div>

				</div><!--end #edd_user_commissions_paid-->

			</div><!--end #edd_user_commissions-->
		<?php
		$stats = apply_filters( 'edd_user_commissions_display', ob_get_clean() );
	endif;

	return $stats;
}
add_shortcode( 'edd_commissions', 'eddc_user_commissions' );
