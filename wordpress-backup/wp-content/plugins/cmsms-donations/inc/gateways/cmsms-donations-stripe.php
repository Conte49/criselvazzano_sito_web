<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.0
 * 
 * CMSMasters Donations Stripe Payment Gateway
 * Created by CMSMasters
 * 
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Cmsms_Donations_Stripe extends Cmsms_Donations_Gateway {
	private $stripe_url = 'https://api.stripe.com/';
	
	
	public function __construct() {
		$this->gateway_id = 'stripe';
		
		$this->gateway_name = esc_attr__('Stripe Checkout', 'cmsms_donations');
		
		
		$this->settings = array( 
			array( 
				'name' => 		'cmsms_donations_stripe_testmode', 
				'std' => 		'no', 
				'label' => 		__('Test Mode', 'cmsms_donations'), 
				'desc' => 		__('Enable Test Mode', 'cmsms_donations'), 
				'options' => array( 
					'yes' => 	__('Yes', 'cmsms_donations'), 
					'no' => 	__('No', 'cmsms_donations') 
				), 
				'type' => 		'radio', 
				'class' => 		'gateway-settings gateway-settings-stripe' 
			), 
			array( 
				'name' => 		'cmsms_donations_stripe_secret_key', 
				'std' => 		'', 
				'label' => 		__('Secret Key', 'cmsms_donations'), 
				'desc' => 		__('Get your API keys from your stripe account.', 'cmsms_donations'), 
				'type' => 		'input', 
				'class' => 		'gateway-settings gateway-settings-stripe' 
			), 
			array( 
				'name' => 		'cmsms_donations_stripe_publishable_key', 
				'std' => 		'', 
				'label' => 		__('Publishable Key', 'cmsms_donations'), 
				'desc' => 		__('Get your API keys from your stripe account.', 'cmsms_donations'), 
				'type' => 		'input', 
				'class' => 		'gateway-settings gateway-settings-stripe' 
			), 
			array( 
				'name' => 		'cmsms_donations_stripe_name', 
				'std' => 		'', 
				'label' => 		__('Company Name', 'cmsms_donations'), 
				'desc' => 		__('Custom company name for stripe payment form.', 'cmsms_donations'), 
				'type' => 		'input', 
				'class' => 		'gateway-settings gateway-settings-stripe' 
			), 
			array( 
				'name' => 		'cmsms_donations_stripe_description', 
				'std' => 		'', 
				'label' => 		__('Description', 'cmsms_donations'), 
				'desc' => 		__('Enter default description for stripe payment form.', 'cmsms_donations'), 
				'type' => 		'input', 
				'class' => 		'gateway-settings gateway-settings-stripe' 
			), 
			array( 
				'name' => 		'cmsms_donations_stripe_image', 
				'std' => 		'', 
				'label' => 		__('Logo Image', 'cmsms_donations'), 
				'desc' => 		__('Choose a square image of your organization or logo for stripe payment form. <br />The recommended minimum size is 128x128px.', 'cmsms_donations'), 
				'type' => 		'upload', 
				'class' => 		'gateway-settings gateway-settings-stripe' 
			) 
		);
		
		
		parent::__construct();
		
		
		add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
	}
	
	
	public function pay_for_donation($donation_id) {
		try { 
			$stripe_token = isset($_POST['stripe_token']) ? sanitize_text_field($_POST['stripe_token']) : '';
			
			
			if (empty($stripe_token)) {
				throw new Exception(__('Please make sure your card details have been entered correctly and your browser supports JavaScript.', 'cmsms_donations'));
			}
			
			
			$response = wp_remote_post($this->stripe_url . 'v1/charges', array( 
				'method' => 			'POST', 
				'headers' => array( 
					'Authorization' => 	'Basic ' . base64_encode(get_option('cmsms_donations_stripe_secret_key') . ':') 
				), 
				'body' => array( 
					'amount' => 		get_the_donation_amount($donation_id) * 100, 
					'currency' => 		strtolower(get_option('cmsms_donations_currency')), 
					'description' => 	__('New Donation', 'cmsms_donations') . ' \u0026quot;' . get_the_title($donation_id) . '\u0026quot;', 
					'capture' => 		'true', 
					'card' => 			$stripe_token 
				), 
				'timeout' => 			60, 
				'sslverify' => 			false, 
				'user-agent' => 		'cmsms_donations' 
			) );
			
			
			if (is_wp_error($response)) {
				throw new Exception(__( 'There was a problem connecting to the gateway.', 'cmsms_donations'));
			}
			
			
			if (empty($response['body'])) {
				throw new Exception(__('Empty response.', 'cmsms_donations'));
			}
			
			
			$parsed_response = json_decode($response['body']);
			
			
			if (!empty($parsed_response->error)) {
				throw new Exception($parsed_response->error->message);
			} elseif (empty($parsed_response->id)) {
				throw new Exception(__('Invalid response.', 'cmsms_donations'));
			} else {
				update_post_meta($donation_id, 'cmsms_charge_id', $parsed_response->id);
				
				update_post_meta($donation_id, 'cmsms_payment_id', $parsed_response->id);
				
				
				if (isset($parsed_response->fee)) {
					update_post_meta($donation_id, 'cmsms_stripe_fee', cmsms_number_format($parsed_response->fee));
				}
				
				
				$this->send_admin_email($donation_id, sprintf(__("Payment has been received in full for donation #%d - this donation has been published.", 'cmsms_donations'), $donation_id));
				
				
				$this->payment_complete($donation_id);
				
				
				return true;
			}
		} catch (Exception $e) {
			Cmsms_Donations_Form_Submit_Donation::add_error($e->getMessage());
			
			
			return false;
		}
	}
	
	
	public function frontend_scripts() {
		$posted = stripslashes_deep($_POST);
		
		
		if (isset($posted['donation_amount'])) {
			$donation_amount = absint($posted['donation_amount']);
		}
		
		
		if (isset($posted['donation_campaign'])) {
			$donation_campaign = absint($posted['donation_campaign']);
		}
		
		
		if (get_option('cmsms_donations_stripe_image')) {
			$stripe_image_id = explode('|', get_option('cmsms_donations_stripe_image'));
			
			$stripe_image_array = wp_get_attachment_image_src($stripe_image_id[0]);
		}
		
		
		wp_register_script('stripe', 'https://checkout.stripe.com/v2/checkout.js', array(), '2.0', true);
		
		wp_enqueue_script('stripe');
		
		
		wp_register_script('cmsms-donations-stripe', CMSMS_DONATIONS_URL . 'js/jquery.cmsmsDonations-stripe.js', array('jquery', 'stripe', 'cmsmsValidation', 'cmsmsValidationLang'), CMSMS_DONATIONS_VERSION, true);
		
		wp_localize_script('cmsms-donations-stripe', 'cmsms_donations_stripe_params', array( 
			'confirm' => 		(get_option('cmsms_confirm_donation') == 1) ? 'donation_preview_submit_button' : 'donation_submit_button', 
			'key' => 			get_option('cmsms_donations_stripe_publishable_key'), 
			'name' => 			get_option('cmsms_donations_stripe_name') ? get_option('cmsms_donations_stripe_name') : get_bloginfo('name'), 
			'description' => 	get_option('cmsms_donations_stripe_description'), 
			'amount' => 		(!isset($donation_amount)) ? 0 : $donation_amount * 100, 
			'campaign' => 		(!isset($donation_campaign)) ? '' : $donation_campaign, 
			'image' => 			get_option('cmsms_donations_stripe_image') ? $stripe_image_array[0] : '', 
			'currency' => 		strtolower(get_option('cmsms_donations_currency')), 
			'label' => 			__('Donate', 'cmsms_donations') 
		) );
		
		wp_enqueue_script('cmsms-donations-stripe');
	}
}

return new Cmsms_Donations_Stripe();

