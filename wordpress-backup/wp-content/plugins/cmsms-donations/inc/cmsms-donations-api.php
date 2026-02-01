<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.0
 * 
 * CMSMasters Donations API Class
 * Created by CMSMasters
 * 
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Cmsms_Donations_API {
	public function __construct() {
		add_filter('query_vars', array($this, 'add_query_vars'), 0);
		
		
		add_action('parse_request', array($this, 'api_requests'), 0);
	}
	
	
	public function add_query_vars($vars) {
		$vars[] = 'cmsms-donations-api';
		
		
		return $vars;
	}
	
	
	public function api_requests() {
		global $wp;
		
		
		if (!empty($_GET['cmsms-donations-api'])) {
			$wp->query_vars['wc-api'] = $_GET['cmsms-donations-api'];
		}
		
		
		if (!empty($wp->query_vars['wc-api'])) {
			ob_start();
			
			
			$api = strtolower(esc_attr($wp->query_vars['cmsms-donations-api']));
			
			
			if (class_exists($api)) {
				$api_class = new $api();
			}
			
			
			do_action('cmsms_donations_api_' . $api);
			
			
			ob_end_clean();
			
			
			die('1');
		}
	}
}

new Cmsms_Donations_API();

