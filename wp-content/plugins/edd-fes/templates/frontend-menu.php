<?php
$task       = ! empty( $_GET['task'] ) ? $_GET['task'] : '';
$icon_css   = apply_filters( "fes_vendor_dashboard_menu_icon_css", "icon-white" ); //else icon-black/dark
$menu_items = EDD_FES()->dashboard->get_vendor_dashboard_menu();
?>
<nav class="fes-vendor-menu">
	<ul>
                <?php
                    $i=0;
                    $arr=array('fa-tachometer','fa-list','fa-pencil','fa-money','fa-reorder','fa-user','fa-sign-out');
                ?>
		<?php foreach ( $menu_items as $item => $values ) : ?>
			<li class="<?php if( in_array( $task, $values["task"] ) ) { echo "active"; } ?>">
				<a href='<?php echo add_query_arg( 'task', $values["task"][0], get_permalink() ); ?>'>
					<i class="fa <?php echo esc_attr( $arr[$i] ); ?> "></i> <span class="hidden-phone hidden-tablet"><?php echo $values["name"]; ?></span>
				</a>
			</li>
		<?php $i++; endforeach; ?>
	</ul>  
</nav>