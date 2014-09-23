jQuery(document).ready( function($) {
	
	show_hide_append_code();
	
	//confirmation for applying delete record
	$( document ).on( 'click', '.edd-currency-delete', function() {
		
		var confirmdelete = confirm( Edd_Currency_Admin.delete_msg );
		 
		if( confirmdelete ) {
			return true;
		} else {
			return false;
		}
	});
	
	//confirmation for applying delete record
	jQuery('input[name="edd_settings[replace_currency_code]"]').click(function() {
		
		show_hide_append_code();
	});
	
	//confirmation for applying delete record
	jQuery('input[name="edd_settings[append_code]"]').click(function() {
		
		show_hide_code_position();
	});
	
	//confirmation for applying reset order
	$( document ).on( 'click', '.edd-currency-reset-order', function() {
		
		var confirmreset = confirm( Edd_Currency_Admin.reset_order );
		 
		if( confirmreset ) {
			return true;
		} else {
			return false;
		}
	});
	
});

function show_hide_append_code(){
	
	var is_checked = jQuery('input[name="edd_settings[replace_currency_code]"]').is(':checked'); 
	
	if( is_checked == true ){
		
		jQuery('input[name="edd_settings[replace_currency_code]"]').parent().parent().next().hide();
		jQuery('input[name="edd_settings[replace_currency_code]"]').parent().parent().next().next().hide();
		
	} else {
		
		jQuery('input[name="edd_settings[replace_currency_code]"]').parent().parent().next().show();
		jQuery('input[name="edd_settings[replace_currency_code]"]').parent().parent().next().next().show();
		show_hide_code_position();
	}	
}

function show_hide_code_position(){
	var append_code = jQuery('input[name="edd_settings[append_code]"]').is(':checked');
	
	if( append_code == false ){
		
		jQuery('input[name="edd_settings[append_code]"]').parent().parent().next().hide();
	
	} else {
		
		jQuery('input[name="edd_settings[append_code]"]').parent().parent().next().show();
	}
}