<?php
/**
 * Template Name: Forgot Password
 * @package Marketify
 */
get_header();

$error = false;

if(isset($_GET['action']) && $_GET['action'] == 'rp'){
	$rp_key   = $_GET['key'];
	$rp_login = $_GET['login'];
	
	if ( $rp_key ) {
		$user = check_password_reset_key( $rp_key, $rp_login );
	} 
	else {
		$user = false;
	}

	if ( ! $user || is_wp_error( $user ) ) {
		if ( $user && $user->get_error_code() === 'expired_key' )
			//wp_redirect( site_url( 'forgot-password?action=lostpassword&error=expiredkey' ) );
			echo "<script>window.location.href = 'forgot-password?action=lostpassword&error=expiredkey'</script>";
		else
			//wp_redirect( site_url( 'forgot-password?action=lostpassword&error=invalidkey' ) );
			echo "<script>window.location.href = 'forgot-password?action=lostpassword&error=invalidkey'</script>";
		exit;
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'resetpass'){
	$error = false;
	$success = false;
	
	$rp_key   = $_POST['key'];
	$rp_login = $_POST['login'];
	
	if ( $rp_key ) {
		$user = check_password_reset_key( $rp_key, $rp_login );
	} 
	else {
		$user = false;
	}
	
	if ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] )
		$error = "The passwords do not match.";
		
	if ( ( ! $error ) && $user && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
		reset_password($user, $_POST['pass1']);
		
		$success = 'Your password has been reset.';
	}		
}

if($_POST['edd_submit'] && $_POST['user_login']){
	$login = trim($_POST['user_login']);
	$user_data = get_user_by('login', $login);

	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	// Generate something random for a password reset key.
	$key = wp_generate_password( 20, false );

	do_action( 'retrieve_password_key', $user_login, $key );

	// Now insert the key, hashed, into the DB.
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	}
	$hashed = $wp_hasher->HashPassword( $key );
	$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

	$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
	$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
	$message .= '<' . network_site_url("forgot-password?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$title = sprintf( __('[%s] Password Reset'), $blogname );
	$title = apply_filters( 'retrieve_password_title', $title );

	if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ){
		$error = ( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
	}
	else{
		$success = "Email is sent for reset password.";
	}
}
elseif($_POST['edd_submit']){
	$error = "Please enter valid email address or username";
}
?>
<div class="container seller">	
 <div class="row"  > 
    <div class="col-xs-12">
		<div id="content" class="site-content abc " align="center">
			<?php if ( ! is_user_logged_in() ) :?>
					<?php if($error):?>
						<p id="edd_error_password_incorrect" class="edd_error"><?php echo $error;?></p>
					<?php endif;?>
					
					<?php if($success):?>
						<p id="edd_error_password_incorrect" class="edd_success"><?php echo $success;?></p>
					<?php endif;?>
					
					<?php if($_GET['action'] != 'rp' and $_GET['action'] != 'resetpass'):?>
							<form id="lostpasswordform" class="edd_form" action="" method="post">
								<fieldset>
                                                                   	<?php do_action( 'edd_login_fields_before' ); ?>
									<p>
										<label for="edd_user_Login"><?php _e( 'Enter Username or E-mail:', 'edd' ); ?></label>
										<input name="user_login" id="edd_user_login" class="required edd-input" type="text" title="<?php _e( 'Username', 'edd' ); ?>"/>
									</p>
									<p>
										<input type="hidden" name="edd_redirect" value="<?php echo esc_url( $edd_login_redirect ); ?>"/>
										<input type="hidden" name="action" value="lostpassword"/>
										<input id="edd_login_submit" type="submit" name="edd_submit" class="edd_submit" value="<?php _e( 'Submit', 'edd' ); ?>"/>
									</p>
									<?php do_action( 'edd_login_fields_after' ); ?>
								</fieldset>
							</form>
					<?php else:?>
							<?php if(!$success):?>
								<form name="resetpassform" id="resetpassform" action="<?php echo esc_url( site_url( 'forgot-password?action=resetpass', 'login_post' ) ); ?>" method="post" autocomplete="off">
									<input type="hidden" name="key" value="<?php echo esc_attr( $rp_key ); ?>" autocomplete="off" />
									<input type="hidden" name="login" value="<?php echo esc_attr( $rp_login ); ?>" autocomplete="off" />
								
									<p>
										<label for="pass1"><?php _e('New password') ?><br />
										<input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" /></label>
									</p>
									<p>
										<label for="pass2"><?php _e('Confirm new password') ?><br />
										<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" /></label>
									</p>
								
									<div id="pass-strength-result" class="hide-if-no-js"><?php _e('Strength indicator'); ?></div>
									<p class="description indicator-hint"><?php _e('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).'); ?></p>
								
									<br class="clear" />
									
									<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Reset Password'); ?>" /></p>
								</form>
							<?php endif;?>
					<?php endif;?>			
			<?php else : ?>
			
				<p class="edd-logged-in"><?php _e( 'You are already logged in', 'edd' ); ?></p>
				
			<?php endif; ?>
			<br clear="all" />
		</div>	
	</div>
</div></div>
<?php get_footer(); ?>