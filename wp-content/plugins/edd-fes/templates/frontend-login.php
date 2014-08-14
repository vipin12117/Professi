<?php
EDD_FES()->login_register->fes_login_show_error_messages(); 
?>
	<form id="fes_login_form"  class="fes-form" action="" method="POST">
		<table>
		<h3 class="fes_header"><?php _e('Login'); ?></h3>
	
			<tr>
				<td>
					<label for="fes_user_login">Username</label>
				</td>
				<td>
					<?php $value = isset( $_POST[ 'fes_user_login' ] ) ? $_POST[ 'fes_user_login' ] : ''; ?>
					<input name="fes_user_login" id="fes_user_login" class="required" type="text" value="<?php echo $value; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="fes_user_pass">Password</label>
				</td>
				<td>
					<input name="fes_user_pass" id="fes_user_pass" class="required" type="password"/>
				</td>
			</tr>
			<?php do_action('edd_fes_in_login_form'); ?>
			<tr>
				<td colspan="2">
					<input type="hidden" name="fes_login_nonce" value="<?php echo wp_create_nonce('fes-login-nonce'); ?>"/>
					<input id="fes_login_submit" type="submit" value="Login"/>
				</td>
			</tr>
		</table>
	</form>