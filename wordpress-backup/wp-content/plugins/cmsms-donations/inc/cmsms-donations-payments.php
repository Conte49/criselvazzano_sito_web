<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.0
 * 
 * CMSMasters Donations Payments Class
 * Created by CMSMasters
 * 
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Cmsms_Donations_Payments {
	private $donation_id  = '';
	
	
	public function __construct() {
		$this->donation_id = !empty($_REQUEST['donation_id']) ? absint($_REQUEST['donation_id']) : 0;
		
		
		$this->step = !empty($_REQUEST['step']) ? max(absint($_REQUEST['step']), 0) : 0;
		
		
		add_action('init', array($this, 'init'), 12);
		
		
		add_filter('cmsms_donations_valid_submit_donation_statuses', array($this, 'valid_submit_donation_statuses'));
		
		add_filter('submit_donation_steps', array($this, 'submit_donation_steps'), 10);
		
		add_filter('submit_donation_step_preview_submit_text', array($this, 'submit_button_text'), 10);
		
		
		add_action('cmsms_donations_donation_submitted_content_pending_payment', array($this, 'donation_submitted'), 10);
		
		
		add_filter('cmsms_donations_settings', array($this, 'settings'));
		
		
		add_action('cmsms_donations_api_' . get_class($this), array($this, 'api_handler'));
		
		
		$this->gateway = $this->get_gateway();
	}
	
	
	public function init() {
		global $cmsms_donations;
		
		
		register_post_status('pending_payment', array( 
			'label' => 						__('Pending Payment', 'cmsms_donations'), 
			'public' => 					true, 
			'exclude_from_search' => 		false, 
			'show_in_admin_all_list' => 	true, 
			'show_in_admin_status_list' => 	true, 
			'label_count' => 				_n_noop('Pending Payment <span class="count">(%s)</span>', 'Pending Payments <span class="count">(%s)</span>', 'cmsms_donations') 
		));
		
		
		register_post_status('pending_offline', array( 
			'label' => 						__('Pending Offline Payment', 'cmsms_donations'), 
			'public' => 					true, 
			'exclude_from_search' => 		false, 
			'show_in_admin_all_list' => 	true, 
			'show_in_admin_status_list' => 	true, 
			'label_count' => 				_n_noop('Pending Offline Payment <span class="count">(%s)</span>', 'Pending Offline Payments <span class="count">(%s)</span>', 'cmsms_donations') 
		));
	}
	
	
	public function valid_submit_donation_statuses($status) {
		$status[] = 'pending_payment';
		
		$status[] = 'pending_offline';
		
		
		return $status;
	}
	
	
	public function submit_donation_steps($steps) {
		$steps['preview']['handler'] = array($this, 'preview_handler');
		
		
		return $steps;
	}
	
	
	public function preview_handler($send_id = false) {
		if (!$_POST) {
			return;
		}
		
		
		if (!empty($_POST['edit_donation'])) {
			Cmsms_Donations_Form_Submit_Donation::previous_step();
		}
		
		
		if ($send_id) {
			$this->donation_id = $send_id;
		}
		
		
		if (!empty($_POST['continue'])) {
			$donation = get_post($this->donation_id);
			
			
			if ($donation->post_status == 'preview') {
				$update_donation = array();
				
				$update_donation['ID'] = $donation->ID;
				
				
				if (is_online_payment($donation->ID)) {
					$update_donation['post_status'] = 'pending_payment';
				} else {
					$update_donation['post_status'] = 'pending_offline';
				}
				
				
				wp_update_post($update_donation);
			}
			
			
			if (is_online_payment($donation->ID) && $this->gateway->pay_for_donation($this->donation_id)) {
				Cmsms_Donations_Form_Submit_Donation::next_step();
			} else {
				wp_redirect( 
					add_query_arg( 
						array( 
							'success' => 		'true', 
							'donation_id' => 	urlencode($donation->ID), 
							'step' => 			urlencode($_REQUEST['step'] + 1) 
						), 
						get_permalink() 
					) 
				);
			}
		}
	}
	
	
	public function submit_button_text() {
		$donation = get_post($this->donation_id);
		
		
		if (is_online_payment($donation->ID)) {
			return __('Proceed to payment', 'cmsms_donations');
		} else {
			return __('Submit donation', 'cmsms_donations');
		}
	}
	
	
	public function donation_submitted($donation) {
		$this->gateway->return_handler();
	}
	
	
	public function settings($settings = array()) {
		require_once(CMSMS_DONATIONS_GATEWAYS . '/abstract/abstract-cmsms-donations-gateways.php');
		
		require_once(CMSMS_DONATIONS_GATEWAYS . 'cmsms-donations-paypal.php');
		
		require_once(CMSMS_DONATIONS_GATEWAYS . 'cmsms-donations-stripe.php');
		
		
		$settings['payment_gateways'] = array( 
			__('Payment Gateways', 'cmsms_donations'), 
			apply_filters('cmsms_donations_payments_settings', array( 
				array( 
					'name' => 		'cmsms_donations_currency', 
					'std' => 		'USD', 
					'label' => 		__('Currency Code', 'cmsms_donations'), 
					'desc' => 		__('Enter the currency code you wish to use. <br />E.g. for US dollars enter <code>USD</code>. <br />Your gateway must support your input currency for payments to work.', 'cmsms_donations'), 
					'type' => 		'input' 
				), 
				array( 
					'name' => 		'cmsms_donations_offline_payment_text', 
					'std' => 		'', 
					'label' => 		__('Offline Payment Instructions', 'cmsms_donations'), 
					'desc' => 		__('Please enter the instructions for your donators to send you the offline payments (BACS, cheque etc...)', 'cmsms_donations'), 
					'type' => 		'textarea' 
				), 
				array( 
					'name' => 		'cmsms_donations_gateway', 
					'std' => 		'paypal', 
					'label' => 		__('Payment Gateway', 'cmsms_donations'), 
					'desc' => 		__("Choose the gateway to use for collecting funds. <br />Depending on the gateway you choose you should ensure your donation form page is served over HTTPS. <br />You can use <a href='http://wordpress.org/plugins/wordpress-https/'>WordPress HTTPS</a> to do this. <br />Please note: stripe gateway don't allow recurring payments.", 'cmsms_donations'),
					'options' => 	apply_filters('cmsms_donations_gateways', array()), 
					'type' => 		'select' 
				) 
			) ) 
		);
		
		
		add_action('admin_footer', array($this, 'settings_js'));
		
		
		return $settings;
	}
	
	
	function api_handler() {
		if (!empty($_GET['gateway'])) {
			$gateway = $this->get_gateway($_GET['gateway']);
			
			
			$gateway->api_handler();
		}
	}
	
	
	public function get_gateway($gateway = '') {
		require_once(CMSMS_DONATIONS_GATEWAYS . '/abstract/abstract-cmsms-donations-gateways.php');
		
		
		if (!$gateway) {
			$gateway = get_option('cmsms_donations_gateway', 'paypal');
		}
		
		
		$gateway_class = apply_filters('cmsms_donations_gateway_class', 'Cmsms_Donations_' . $gateway);
		
		
		if (!class_exists($gateway_class)) {
			return require_once(CMSMS_DONATIONS_GATEWAYS . str_replace('_', '-', strtolower($gateway_class)) . '.php');
		}
		
		
		return new $gateway_class;
	}
	
	
	public function settings_js() {
		echo "
		<script type=\"text/javascript\">
			jQuery('select#setting-cmsms_donations_gateway').change(function () { 
				var cmsms_select = jQuery(this), 
					cmsms_form = cmsms_select.closest('form');
				
				
				cmsms_form.find('tr.gateway-settings').hide();
				
				
				cmsms_form.find('tr.gateway-settings-' + cmsms_select.val()).show();
			} ).trigger('change');
		</script>
";
	}
}

$GLOBALS['cmsms_donations_payments'] = new Cmsms_Donations_Payments();

