<?php
EDD_FES()->login_register->fes_register_show_error_messages(); 
?>
	<form id="fes_registration_form" class="fes-form" action="" method="POST">
		<table>
			<h3 class="fes_header"><?php _e('Register New Vendor Account'); ?></h3>
			<tr>
				<td>
					<label for="fes_user_first"><?php _e('First Name'); ?></label>
				</td>
				<td>
					<?php $value = isset( $_POST[ 'fes_user_first' ] ) ? $_POST[ 'fes_user_first' ] : ''; ?>
					<input name="fes_user_first" id="fes_user_first" class="required" type="text" value="<?php echo $value; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="fes_user_last"><?php _e('Last Name'); ?></label>
				</td>
				<td>
					<?php $value = isset( $_POST[ 'fes_user_last' ] ) ? $_POST[ 'fes_user_last' ] : ''; ?>
					<input name="fes_user_last" id="fes_user_last" class="required" type="text" value="<?php echo $value; ?>" />
				</td>
			</tr>				
			<tr>
				<td>
					<label for="fes_user_email"><?php _e('Email'); ?></label>
				</td>
				<td>
					<?php $value = isset( $_POST[ 'fes_user_email' ] ) ? $_POST[ 'fes_user_email' ] : ''; ?>
					<input name="fes_user_email" id="fes_user_email" class="required" type="email" value="<?php echo $value; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="fes_registration_login"><?php _e('Username'); ?></label>
				</td>
				<td>
					<?php $value = isset( $_POST[ 'fes_registration_login' ] ) ? $_POST[ 'fes_registration_login' ] : ''; ?>
					<input name="fes_registration_login" id="fes_registration_login" class="required" type="text" value="<?php echo $value; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="fes_user_pass"><?php _e('Password'); ?></label>
				</td>
				<td>						
					<?php $value = isset( $_POST[ 'fes_user_pass' ] ) ? $_POST[ 'fes_user_pass' ] : ''; ?>
					<input name="fes_user_pass" id="fes_user_pass" class="required" type="password" value="<?php echo $value; ?>" />
				</td>
			</tr>
			<?php 
			if($terms_page = EDD_FES()->fes_options->get_option( 'terms_to_apply_page' )){	?>
			<tr>
				<td>
					<label for="fes_user_agree"><?php _e('Terms of Service', 'edd_fes');?></label>
				</td>
				<td>
					<?php printf(__( 'I accept the <a href="%s">Terms of Service</a>', 'edd_fes' ), get_permalink( $terms_page ) ); ?>
					<input class="input-checkbox" id="fes_user_agree" <?php checked( isset( $_POST['fes_user_agree'] ), true ) ?> type="checkbox" name="fes_user_agree" value="1" />
				</td>
			</tr>
			<?php } ?>
			<?php do_action('edd_fes_in_register_form'); ?>
			<tr>
				<td colspan="2">
					<input type="hidden" name="fes_register_nonce" value="<?php echo wp_create_nonce('fes-register-nonce'); ?>"/>
					<input type="submit" value="<?php _e('Register Your Account'); ?>"/>
				</td>
			</tr>
		</table>
	</form>