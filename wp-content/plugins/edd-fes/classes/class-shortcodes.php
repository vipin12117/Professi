<?php
if (!defined('ABSPATH')) {
	exit;
}

class FES_Shortcodes {
	function __construct() {
		add_shortcode('edd_fes_login_form', array(
			$this,
			'login_form'
		));
		add_shortcode('edd_fes_register_form', array(
			$this,
			'register_form'
		));
		add_shortcode('edd_fes_combo_form', array(
			$this,
			'combo_form'
		));
	}
	
	function login_form() {
		if (!is_user_logged_in()) {
			ob_start();
			EDD_FES()->templates->fes_get_template_part('frontend', 'login');
			return ob_get_clean();
		} else {
			EDD_FES()->vendor_permissions->vendor_not_a_vendor_redirect();
		}
	}
	
	function register_form() {
		if (!is_user_logged_in()) {
			ob_start();
			EDD_FES()->templates->fes_get_template_part('frontend', 'register');
			return ob_get_clean();
		} else {
			EDD_FES()->vendor_permissions->vendor_not_a_vendor_redirect();
		}
	}
	function combo_form() {
		if (!is_user_logged_in()) {
			// if registrations allowed
			echo '<table><tr><td id="fes_half" style="width: 48%; clear: none; float: left;">';
			// endif
			echo EDD_FES()->shortcodes->login_form();
			// if registrations allowed
			echo '</td><td id="fes_half" style="width: 48%; float: left;">';
			// endif
			if (EDD_FES()->fes_options->get_option('show_vendor_registration')) {
				echo EDD_FES()->shortcodes->register_form();
			}
			// if registrations allowed
			echo '</td></tr></div></table>';
			// endif
		} else {
			EDD_FES()->vendor_permissions->vendor_not_a_vendor_redirect();
		}
	}
}