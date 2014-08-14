<div id="fes-vendor-dashboard">
	<div id="fes-vendor-announcements">
		<?php echo apply_filters( 'the_content', EDD_FES()->fes_options->get_option( 'dashboard-page-template' ) ); ?>
	</div>
	
	<div id="fes-vendor-store-link">
		<?php echo EDD_FES()->vendors->get_vendor_store_url_dashboard(); ?>
	</div> 
	
	<div class="fes-comments-wrap">
		<table id="fes-comments-table">
			<tr>
				<th class="col-author"><?php  _e( 'Author', 'fes-comment' ); ?></th>
				<th class="col-content"><?php  _e( 'Comment', 'fes-comment' ); ?></th>
			</tr>
			<?php echo EDD_FES()->comments->render( 10 ); ?>
		</table>	
	</div> 
</div>