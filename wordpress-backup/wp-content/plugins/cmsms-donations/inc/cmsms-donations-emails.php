<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.0
 * 
 * CMSMasters Donations Emails
 * Created by CMSMasters
 * 
 */


class Cmsms_Donations_Emails {
	public function __construct() {
		add_action('send-donator-email', array($this, 'send_donator_email'));
	}
	
	
	public function send_donator_email($donation_id) {
		$donation = get_post($donation_id);
		
		
		if (!$donation) {
			return;
		}
		
		
		$donator_email = get_post_meta($donation_id, 'cmsms_donator_email', true);
		
		
		$email = $this->format_email($donation_id);
		
		
		add_filter('wp_mail_from_name', array($this, 'mail_from_name'));
		
		
		if ($email && $donator_email != '') {
			wp_mail( 
				$donator_email, 
				apply_filters('cmsms_donations_donator_email_subject', sprintf(__('Your Donation "%s"', 'cmsms_donations'), $donation->post_title), $donation), 
				$email 
			);
		}
		
		
		remove_filter('wp_mail_from_name', array($this, 'mail_from_name'));
	}
	
	
	public function format_email($donation_id) {
		$donation = get_post($donation_id);
		
		$donator_firstname = get_post_meta($donation_id, 'cmsms_donator_firstname', true);
		
		$donator_lastname = get_post_meta($donation_id, 'cmsms_donator_lastname', true);
		
		$template = get_option('cmsms_donations_donator_email_template');
		
		
		if (!$template) {
			$template = $this->get_default_email();
		}
		
		
		ob_start();
		
		
		get_cmsms_donations_template('content-donator-email.php');
		
		
		$donation_details = ob_get_clean();
		
		
		$replacements = array( 
			'{donator_firstname}' => 	$donator_firstname, 
			'{donator_lastname}' => 	$donator_lastname, 
			'{donation_amount}' => 		get_the_donation_amount_currency($donation_id), 
			'{donation_date}' => 		date_i18n(get_option('date_format'), strtotime($donation->post_date)), 
			'{donation_details}' => 	$donation_details 
		);
		
		
		$template = str_replace(array_keys($replacements), array_values($replacements), $template);
		
		
		return apply_filters('cmsms_donations_donator_email_template', $template);
	}
	
	
	public function mail_from_name($name) {
	    return get_bloginfo('name');
	}
	
	
	public function get_default_email() {
		return "Dear {donator_firstname} {donator_lastname},

Thank you for your generous donation of {donation_amount} on {donation_date}. Your willingness to help us is deeply appreciated and we would like to thank you very much for your contribution.

Frankly, we could not do this without you. We, and those we serve, deeply appreciate your generosity.

Sincerely,

Our Team";
	}
}

$GLOBAL['cmsms_emails'] = new Cmsms_Donations_Emails();

