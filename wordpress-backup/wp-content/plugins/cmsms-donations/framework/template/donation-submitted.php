<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.0
 * 
 * CMSMasters Donation Form Submission Notices Template
 * Created by CMSMasters
 * 
 */


switch ($donation->post_status) {
case 'publish':
	echo '<div class="cmsms_notice cmsms_notice_success cmsms_donation_notice cmsms_donation_notice_success cmsms-icon-check">' . 
		'<a class="notice_close cmsms_theme_icon_cancel" href="#"></a>' . 
		'<div class="notice_icon"></div>' . 
		'<div class="notice_content">' . 
			wpautop(esc_html__('Donation validated successfully.', 'cmsms_donations')) . 
		'</div>' . 
	'</div>';
	
	
	break;
case 'pending_payment':
	echo '<div class="cmsms_notice cmsms_notice_success cmsms_donation_notice cmsms_donation_notice_success cmsms-icon-check">' . 
		'<a class="notice_close cmsms_theme_icon_cancel" href="#"></a>' . 
		'<div class="notice_icon"></div>' . 
		'<div class="notice_content">' . 
			wpautop(esc_html__("Donation submitted successfully.\nYour donation will be published as soon as we receive the payment gateway validation (it can take several minutes).", 'cmsms_donations')) . 
		'</div>' . 
	'</div>';
	
	
	break;
case 'pending_offline' :
	echo '<div class="cmsms_notice cmsms_notice_success cmsms_donation_notice cmsms_donation_notice_success cmsms-icon-check">' . 
		'<a class="notice_close cmsms_theme_icon_cancel" href="#"></a>' . 
		'<div class="notice_icon"></div>' . 
		'<div class="notice_content">' . 
			wpautop(esc_html__("Donation submitted successfully.\nYour donation will be published once payment is received.\nYou choose an offline payment method, so please follow the guide below to send us your payment.", 'cmsms_donations')) . 
		'</div>' . 
	'</div>';
	
	
	if (get_option('cmsms_donations_offline_payment_text')) {
		echo '<div class="cmsms_notice cmsms_notice_info cmsms_donation_notice cmsms_donation_notice_info cmsms-icon-info">' . 
			'<a class="notice_close cmsms_theme_icon_cancel" href="#"></a>' . 
			'<div class="notice_icon"></div>' . 
			'<div class="notice_content">' . 
				wpautop(esc_html(get_option('cmsms_donations_offline_payment_text'))) . 
			'</div>' . 
		'</div>';
	}
	
	
	break;
}


echo '<a href="' . get_page_link(get_option('cmsms_donations_form_page')) . '" class="button">' . esc_html__('Make Another Donation', 'cmsms_donations') . '</a>';


do_action('cmsms_donations_donation_submitted_content_' . str_replace('-', '_', sanitize_title($donation->post_status)), $donation);

