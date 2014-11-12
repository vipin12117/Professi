<?php
/**
 * Template Name: Login
 * @package Marketify
 */
get_header();
?>

		<div class=" login_page">
			<h1 class="welcome_header">¡Bienvenido a Profesi!</h1>
			<div class="row"> 
			<div class="col-xs-12 col-sm-6 col-md-6  " style="  border-right-style: dashed; border-width:2px; border-color:#bdc3c7;">
		
					<?php echo do_shortcode("[edd_login]");?>
				
			</div>
								<div class="col-xs-12 col-sm-6 col-md-6 "  >
				       <h1 class="custom-fes-header"> Aún no es miembro?
 </h1>
				         <p>Le tomará 2 minutos registrarse!
</p>
						 <ul>
				            <li><span>Descubra productos educativos de alta calidad
</span></li>
				            <li><span>Aumente sus ingresos poniendo a la venta sus ideas para el aula
</span></li>
				            <li><span>Únase a una comunidad que fomenta la creatividad de maestros
</span></li>
				        </ul>
				       
					
				        <a href="<?php echo esc_url( home_url( '/register' ) ); ?>" class="register_submit">REGÍSTRESE AHORA
</a>
				
	</div>
				
			</div>
			</div>	
		
	
<?php get_footer(); ?>