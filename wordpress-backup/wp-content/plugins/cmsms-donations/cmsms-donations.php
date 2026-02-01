<?php 
/*
Plugin Name: CMSMasters Donations
Plugin URI: http://cmsmasters.net/
Description: CMSMasters Donations created by <a href="http://cmsmasters.net/" title="CMSMasters">CMSMasters</a> team. This plugin creates custom post type that allows you to collect donations using paypal in new <a href="http://themeforest.net/user/cmsmasters/portfolio" title="cmsmasters">cmsmasters</a> WordPress themes.
Version: 999.1.0.2
Author: cmsmasters
Author URI: http://cmsmasters.net/
*/

/*  Copyright 2014 CMSMasters (email : cmsmstrs@gmail.com). All Rights Reserved.
	
	This software is distributed exclusively as appendant 
	to Wordpress themes, created by CMSMasters studio and 
	should be used in strict compliance to the terms, 
	listed in the License Terms & Conditions included 
	in software archive.
	
	If your archive does not include this file, 
	you may find the license text by url 
	http://cmsmasters.net/files/license/cmsms-donations/license.txt 
	or contact CMSMasters Studio at email 
	copyright.cmsmasters@gmail.com 
	about this.
	
	Please note, that any usage of this software, that 
	contradicts the license terms is a subject to legal pursue 
	and will result copyright reclaim and damage withdrawal.
*/


if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Cmsms_Donations {
	public function __construct() {
		define('CMSMS_DONATIONS_VERSION', '1.0.2');
		
		define('CMSMS_DONATIONS_FILE', __FILE__);
		
		define('CMSMS_DONATIONS_PATH', plugin_dir_path(CMSMS_DONATIONS_FILE));
		
		define('CMSMS_DONATIONS_URL', plugin_dir_url(CMSMS_DONATIONS_FILE));
		
		define('CMSMS_DONATIONS_THEME_SHORTCODES_DIR', 'cmsms-donations/shortcodes');
		
		define('CMSMS_DONATIONS_THEME_TEMPLATES_DIR', 'cmsms-donations/templates');
		
		
		define('CMSMS_DONATIONS_FRAMEWORK', CMSMS_DONATIONS_PATH . 'framework/');
		
		define('CMSMS_DONATIONS_ADMIN', CMSMS_DONATIONS_FRAMEWORK . 'admin/');
		
		define('CMSMS_DONATIONS_FUNCTION', CMSMS_DONATIONS_FRAMEWORK . 'function/');
		
		define('CMSMS_DONATIONS_POSTTYPE', CMSMS_DONATIONS_FRAMEWORK . 'posttype/');
		
		define('CMSMS_DONATIONS_TEMPLATE', CMSMS_DONATIONS_FRAMEWORK . 'template/');
		
		
		define('CMSMS_DONATIONS_INC', CMSMS_DONATIONS_PATH . 'inc/');
		
		define('CMSMS_DONATIONS_FORMS', CMSMS_DONATIONS_INC . 'forms/');
		
		define('CMSMS_DONATIONS_GATEWAYS', CMSMS_DONATIONS_INC . 'gateways/');
		
		
		if (is_admin()) {
			require_once(CMSMS_DONATIONS_ADMIN . 'cmsms-donations-settings.php');
		}
		
		
		require_once(CMSMS_DONATIONS_POSTTYPE . 'cmsms-campaigns-posttype.php');
		
		require_once(CMSMS_DONATIONS_POSTTYPE . 'cmsms-donations-posttype.php');
		
		
		require_once(CMSMS_DONATIONS_FUNCTION . 'cmsms-donations-template-function.php');
		
		require_once(CMSMS_DONATIONS_FUNCTION . 'cmsms-donations-shortcode-function.php');
		
		require_once(CMSMS_DONATIONS_FUNCTION . 'cmsms-donations-form-function.php');
		
		
		require_once(CMSMS_DONATIONS_INC . 'cmsms-donations-forms.php');
		
		require_once(CMSMS_DONATIONS_INC . 'cmsms-donations-emails.php');
		
		
		require_once(CMSMS_DONATIONS_INC . 'cmsms-donations-api.php');
		
		require_once(CMSMS_DONATIONS_INC . 'cmsms-donations-payments.php');
		
		
		add_action('wp_enqueue_scripts', array($this, 'cmsms_donations_frontend_scripts'));
		
		
		register_activation_hook(CMSMS_DONATIONS_FILE, array($this, 'cmsms_donations_activate_deactivate'));
		
		register_deactivation_hook(CMSMS_DONATIONS_FILE, array($this, 'cmsms_donations_activate_deactivate'));
		
		
		add_filter('plugin_action_links_' . plugin_basename(CMSMS_DONATIONS_FILE), array($this, 'cmsms_donations_action_links'));
		
		// Load Plugin Local File
		load_plugin_textdomain('cmsms_donations', false, dirname(plugin_basename(CMSMS_DONATIONS_FILE)) . '/framework/languages/');
	}
	
	
	public function cmsms_donations_frontend_scripts() {
		wp_register_style('cmsms-donations-form', CMSMS_DONATIONS_URL . 'css/cmsms-donations-form.css', array(), CMSMS_DONATIONS_VERSION, 'screen');
		
		wp_register_style('cmsms-donations-form-rtl', CMSMS_DONATIONS_URL . 'css/cmsms-donations-form-rtl.css', array(), CMSMS_DONATIONS_VERSION, 'screen');
		
		
		wp_register_script('cmsmsValidation', CMSMS_DONATIONS_URL . 'js/jquery.validationEngine.min.js', array('jquery'), '2.6.2', true);
		
		wp_register_script('cmsmsValidationLang', CMSMS_DONATIONS_URL . 'js/jquery.validationEngine-lang.js', array('jquery', 'cmsmsValidation'), CMSMS_DONATIONS_VERSION, true);
		
		wp_localize_script('cmsmsValidationLang', 'cmsms_ve_lang', array( 
			'required' => 			__('* This field is required', 'cmsms_donations'), 
			'select_option' => 		__('* Please select an option', 'cmsms_donations'), 
			'required_checkbox' => 	__('* This checkbox is required', 'cmsms_donations'), 
			'min' => 				__('* Minimum', 'cmsms_donations'), 
			'max' => 				__('* Maximum', 'cmsms_donations'), 
			'allowed' => 			__(' characters allowed', 'cmsms_donations'), 
			'invalid_email' => 		__('* Invalid email address', 'cmsms_donations'), 
			'invalid_number' => 	__('* Invalid number', 'cmsms_donations'), 
			'invalid_url' => 		__('* Invalid URL', 'cmsms_donations'), 
			'numbers_spaces' => 	__('* Numbers and spaces only', 'cmsms_donations'), 
			'letters_spaces' => 	__('* Letters and spaces only', 'cmsms_donations') 
		));
		
		
		wp_register_script('cmsms-donations-form-script', CMSMS_DONATIONS_URL . 'js/jquery.cmsmsDonations-form.js', array('jquery', 'cmsmsValidation', 'cmsmsValidationLang'), CMSMS_DONATIONS_VERSION, true);
		
		wp_localize_script('cmsms-donations-form-script', 'cmsms_donations_form_script_params', array( 
			'gateway' => 	(get_option('cmsms_donations_gateway') == 'stripe') ? 'stripe' : 'paypal', 
			'confirm' => 	(get_option('cmsms_confirm_donation') == 1) ? true : false 
		) );
	}
	
	
	public function cmsms_donations_action_links($links) {
		$settings_link = '<a href="' . get_admin_url(null, 'options-general.php?page=cmsms-donations-settings') . '" title="' . __('Donations Settings', 'cmsms_donations') . '">' . __('Settings', 'cmsms_donations') . '</a>';
		
		
		array_unshift($links, $settings_link);
		
		
		return $links;
	}
	
	
	function cmsms_donations_activate_deactivate() {
		flush_rewrite_rules();
	}
}

$GLOBALS['cmsms_donations'] = new Cmsms_Donations();

