<?php
wp_enqueue_media();
wp_enqueue_script( 'jquery-validation', EDD_PLUGIN_URL . 'assets/js/jquery.validate.min.js',array(), fes_plugin_version);
wp_enqueue_script( 'edd-fes-js', fes_assets_url . 'js/fes_adf.js', array( 'jquery', 'jquery-validation' ), fes_plugin_version );
wp_localize_script( 'edd-fes-js', 'EDDFESL10n', array(
	'oneoption' => __( 'At least one price option is required.', 'edd_fes' ),
	'post_id'            => 0,
	'edd_version'        => EDD_VERSION,
	'add_new_download'   => __( 'Add New Download', 'edd' ), 									// Thickbox title
	'use_this_file'      => __( 'Use This File','edd' ), 										// "use this file" button
	'one_price_min'      => __( 'You must have at least one price', 'edd' ),
	'one_file_min'       => __( 'You must have at least one file', 'edd' ),
	'one_field_min'      => __( 'You must have at least one field', 'edd' ),
	'currency_sign'      => edd_currency_filter(''),
	'currency_pos'       => isset( $edd_options['currency_position'] ) ? $edd_options['currency_position'] : 'before',
	'new_media_ui'       => apply_filters( 'edd_use_35_media_ui', 2 ),
	'remove_text'        => __( 'Remove', 'edd' ),
	'admin_ajax_url'     => admin_url(),
));