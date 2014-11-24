<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @package Marketify
 */
?><!DOCTYPE html>
<html lang="es" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php wp_title( '|', true, 'right' ); ?></title>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!-- must have -->
<link href="<?php echo get_template_directory_uri(); ?>/css/vp1_html5.css" rel="stylesheet" type="text/css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/vp1_html5.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/screenfull.min.js" type="text/javascript" charset="utf-8"></script> 
<!-- must have -->
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

	<link rel="stylesheet" id="bootstrap-css" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap.min.css" type="text/css" media="all">
  <link rel="stylesheet" id="bootstrap-theme-css" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap-theme.min.css" type="text/css" media="all">
  <link rel="stylesheet" id="fonts-css" href="<?php echo get_template_directory_uri(); ?>/fonts/font.css" type="text/css" media="all">
  <link rel="stylesheet"  href="<?php echo get_template_directory_uri(); ?>/css/vp1_html5.css" type="text/css" media="all">
  <!--gooogle font-->
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
 
  
  <?php wp_head(); ?>
  
  <!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/source/jquery.mousewheel-3.0.6.pack.js"></script>
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/source/jquery.fancybox.css?v=2.1.5" media="screen" />
	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
	<!-- Pickle video player -->
<script>
$(function() {
	$('#vp1_html5_FEB').vp1_html5_Video({
		skin: 'futuristicElectricBlue',
		movieTitle: 'Profesi',
		movieDesc: 'Your movie description. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non ante vitae felis vestibulum lacinia ut sed felis. Aliquam mi libero, pretium consectetur pharetra eu, auctor non diam. Pellentesque adipiscing, justo in placerat sagittis, quam enim aliquet odio, nec laoreet leo neque et felis. Aliquam leo nulla, posuere eget dapibus quis, mattis non urna. Vestibulum blandit velit id tortor hendrerit a rhoncus tellus porta. Donec hendrerit ullamcorper sodales.'
	});
});
</script>
<!-- must have -->
	<script>
		$(function() {
			$('#vp1_html5_EM').vp1_html5_Video({
				skin: 'elegantMinimal',
				responsive:true
			});	
		});
	</script>
</head>
<body <?php body_class(); ?>>

<?php 
    global $userdata;
    get_currentuserinfo();
?>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">	
            <div class="top-bar ">
                <div class="container" >
                    <div class="right-top-bar right">
                            <ul class="none list-top clearfix">

                                    <?php if (  is_user_logged_in() ):?>
                                            <li class="pull-left btn"><i class="uiIcon16x16 uiIconTop man_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/fes-vendor-dashboard/' ) ); ?>"><?php echo $userdata->display_name;?></a></li>
											<li class="pull-left"><i class="uiIcon16x16 uiIconTop man_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/checkout/purchase-history/' ) ); ?>">Compras</a></li>
											<li class="pull-left btn"><i class="uiIcon16x16 uiIconTop man_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/' ) ); ?>fes-vendor-dashboard/?task=logout">Cerrar sesión</a></li>

                                    <?php else:?>
                                            <li class="pull-left btn"><i class="uiIcon16x16 uiIconTop man_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/' ) ); ?>login/">Iniciar Sesión</a></li>

                                    <?php endif;?>

                                    <li class="pull-left btn"><i class="uiIcon16x16 uiIconTop heart_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/' ) ); ?>wish-lists/">Lista de deseos</a></li>                                    <li class="pull-left btn"> <?php echo do_shortcode('[edd_select_currency]');?></li>
                            </ul>
                    </div>
                </div>
            </div>

             <div class="site-branding">
                    <div class=" nav_mainstuff container" >
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4" >
                                    <?php $header_image = get_header_image(); ?>
                                    <?php if ( ! empty( $header_image ) ) : ?>
                                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="custom-header"><img src="<?php echo esc_url( $header_image ); ?>" alt=""></a>
                                    <?php endif; ?>
                                            <div class="site-description-header"><?php  bloginfo( 'description' ); ?></div> 
                            </div>

                            <div class="col-xs-12 col-sm-8 col-md-8 right-header"  >
                                <div class="pull-left"><?php locate_template( array( 'searchform-header.php' ), true ); ?> </div> 
                                
                                <?php if ( is_user_logged_in() ):?>
                               		 <div id="green_button " class="pull-right"> <a href="<?php echo esc_url( home_url( '/fes-vendor-dashboard/?task=new-product' ) ); ?>" class="action-button shadow animate blue">Comienza a vender!</a> </div>
                                <?php else:?>
                                
                                	<div id="green_button " class="pull-right"> <a href="<?php echo esc_url( home_url( '/register' ) ); ?>" class="action-button shadow animate blue">Comienza a vender!</a> </div>
                                <?php endif;?>		  
                            </div>
                        </div>
                    </div>
             
             </div>
            <h1 class="site-title" style="display:none"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <nav id="site-navigation" class="main-navigation" role="navigation">
                <div class="container">  <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'main-menu clearfix') ); ?></div>

            </nav>

	</header><!-- #masthead -->

	
