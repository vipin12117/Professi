<?php
/**
 * Email sharing template
 */
?>

<p>
    <label for="edd-wl-from-name"><?php _e( 'Su Nam', 'edd-wish-lists' ); ?></label>
    <input type="text" placeholder="<?php _e( 'Su Nam', 'edd-wish-lists' ); ?>" name="edd_wl_from_name" id="edd-wl-from-name" class="edd-input" data-msg-required="<?php _e( 'Introduzca su nombre', 'edd-wish-lists' ); ?>" data-rule-required="true" />
</p>

<p>
    <label for="edd-wl-from-email"><?php _e( 'Su dirección de correo electrónico', 'edd-wish-lists' ); ?></label>
    <input type="email" placeholder="<?php _e( 'Su dirección de correo electrónico', 'edd-wish-lists' ); ?>" name="edd_wl_from_email" id="edd-wl-from-email" class="edd-input" data-rule-required="true" data-rule-email="true" data-msg-required="<?php _e( 'Introduzca su dirección de correo electrónico', 'edd-wish-lists' ); ?>" data-msg-email="<?php _e( 'Introduzca una dirección de correo electrónico válida', 'edd-wish-lists' ); ?>" />
</p>

<p>
    <label for="edd-wl-share-emails"><?php _e( 'Amigo \'s Dirección de correo electrónico', 'edd-wish-lists' ); ?></label>
    <span class="edd-description"><?php _e( 'Para enviar a múltiples direcciones de correo electrónico, separe cada dirección de correo electrónico con una coma', 'edd-wish-lists' ); ?></span>
    <input type="text" placeholder="<?php _e( 'Amigo \'s dirección de correo electrónico', 'edd-wish-lists' ); ?>" data-rule-required="true" data-rule-multiemail="true" name="edd_wl_share_emails" id="edd-wl-share-emails" class="edd-input" data-msg-required="<?php _e( 'Por favor introduzca una o más direcciones de correo electrónico', 'edd-wish-lists' ); ?>" data-msg-multiemail="<?php _e( 'Debe introducir una dirección de correo electrónico válida, o una coma varios correos electrónicos separados', 'edd-wish-lists' ); ?>" />
</p>

<p>
    <label for="edd-wl-share-message"><?php _e( 'tu mensaje', 'edd-wish-lists' ); ?></label>
    <span class="edd-description"><?php _e( 'opcional', 'edd-wish-lists' ); ?></span>
    <textarea name="edd_wl_share_message" id="edd-wl-share-message" rows="3" cols="30"></textarea>
</p>