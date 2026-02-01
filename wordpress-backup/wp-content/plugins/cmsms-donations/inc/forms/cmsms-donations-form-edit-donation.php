<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.0
 * 
 * CMSMasters Donations Edit Donation Form
 * Created by CMSMasters
 * 
 */


require_once(CMSMS_DONATIONS_FORMS . 'cmsms-donations-form-submit-donation.php');


class Cmsms_Donations_Form_Edit_Donation extends Cmsms_Donations_Form_Submit_Donation {
	public static $form_name = 'edit-donation';
	
	
	public static function init() {
		self::$donation_id = !empty($_REQUEST['donation_id']) ? absint($_REQUEST['donation_id']) : 0;
	}
	
	
	public static function output() {
		self::submit_handler();
		
		
		self::submit();
	}
	
	
	public static function submit() {
		global $cmsms_donations, 
			$post;
		
		
		$donation = get_post(self::$donation_id);
		
		
		if ( 
			empty(self::$donation_id) || 
			$donation->post_status != 'publish' 
		) {
			echo wpautop(__('Invalid donation', 'cmsms_donations'));
			
			
			return;
		}
		
		
		self::init_fields();
		
		
		foreach (self::$fields as $group_key => $fields) {
			foreach ($fields as $key => $field) {
				switch ($key) {
				case 'donation_message':
					self::$fields[$group_key][$key]['value'] = $donation->post_excerpt;
					
					
					break;
				case 'recurring_donation' :
					self::$fields[$group_key][$key]['value'] = get_post_meta($donation->ID, 'cmsms_recurrence_period', true);
					
					
					break;
				default:
					self::$fields[$group_key][$key]['value'] = get_post_meta($donation->ID, 'cmsms_' . $key, true);
					
					
					break;
				}
			}
		}
		
		
		self::$fields = apply_filters('submit_donation_form_fields_get_donation_data', self::$fields, $donation);
		
		
		get_cmsms_donations_template('donation-submit.php', array( 
			'form' => 					self::$form_name, 
			'donation_id' => 			self::get_donation_id(), 
			'action' => 				self::get_action(), 
			'donation_fields' => 		self::get_fields('donation'), 
			'donator_fields' => 		self::get_fields('donator'), 
			'submit_button_text' => 	__('Update donation', 'cmsms_donations') 
		) );
	}
	
	
	public static function submit_handler() {
		if ( 
			empty($_POST['submit_donation']) || 
			!wp_verify_nonce($_POST['_wpnonce'], 'submit_form_posted') 
		) {
			return;
		}
		
		
		try {
			$values = self::get_posted_fields();
			
			
			if (is_wp_error(($return = self::validate_fields($values)))) {
				throw new Exception($return->get_error_message());
			}
			
			
			self::save_donation($values['donation']['donation_message'], 'publish');
			
			
			self::update_donation_data($values);
			
			
			echo '<div class="cmsms-donations-message">' . 
				__('Your changes have been saved.', 'cmsms_donations') . 
				' <a href="' . get_permalink(self::$donation_id) . '">' . __('View donation', 'cmsms_donations') . '</a>' . 
			'</div>';
		} catch (Exception $e) {
			echo '<div class="cmsms-donations-error">' . $e->getMessage() . '</div>';
			
			
			return;
		}
	}
}

