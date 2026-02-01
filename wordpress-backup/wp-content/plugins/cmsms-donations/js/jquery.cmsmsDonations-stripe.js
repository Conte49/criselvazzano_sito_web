/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version 	1.0.0
 * 
 * CMSMasters Donations Stripe Checkout Script
 * Created by CMSMasters
 * 
 */


(function ($) { 
	if (cmsms_donations_stripe_params.confirm === 'donation_submit_button') {
		var cmsmsForm = $('#submit-donation-form');
		
		
		cmsmsForm.validationEngine('attach', { 
			promptPosition : 		'topRight', 
			scroll : 				false, 
			autoPositionUpdate : 	true, 
			showArrow : 			false 
		} );
	} else {
		var cmsmsForm = $('#donation_preview');
	}
	
	
	$(document).on('click', '#' + cmsms_donations_stripe_params.confirm, function () { 
		var cmsmsButton = $(this), 
			cmsmsToken = cmsmsForm.find('input.stripe_token'), 
			cmsmsDonationAmountValue = cmsmsForm.find('input#donation_amount').val(), 
			cmsmsDonationAmount = Number(cmsmsDonationAmountValue).toFixed(2) * 100, 
			cmsmsStripeAmount = (cmsms_donations_stripe_params.amount.toString() !== '0') ? cmsms_donations_stripe_params.amount : cmsmsDonationAmount, 
			cmsmsDonationCampaignSelect = cmsmsForm.find('select#donation_campaign option:selected'), 
			cmsmsDonationCampaign = (cmsmsDonationCampaignSelect.val() !== '') ? cmsmsDonationCampaignSelect.text().split(' - (') : cmsmsDonationCampaignSelect.val().split('-'), 
			cmsmsStripeCampaign = (cmsms_donations_stripe_params.campaign !== '') ? cmsms_donations_stripe_params.campaign : cmsmsDonationCampaign[0], 
			cmsmsDonationPaymentMethod = cmsmsForm.find('input[name=donation_payment_method]:checked').val();
		
		
		if (cmsmsToken.val()) {
			return true;
		}
		
		
		if ( 
			cmsms_donations_stripe_params.confirm === 'donation_preview_submit_button' || 
			( 
				cmsms_donations_stripe_params.confirm === 'donation_submit_button' && 
				cmsmsForm.validationEngine('validate') && 
				(!cmsmsDonationPaymentMethod || cmsmsDonationPaymentMethod !== 'offline') 
			) 
		) {
			var cmsms_token_action = function (res) { 
				cmsmsForm.find('input.stripe_token').remove();
				
				cmsmsForm.append('<input type="hidden" class="stripe_token" name="stripe_token" value="' + res.id + '" />');
				
				
				cmsmsButton.trigger('click');
			};
			
			
			StripeCheckout.open( { 
				key : 				cmsms_donations_stripe_params.key, 
				name : 				cmsms_donations_stripe_params.name, 
				description : 		(cmsmsStripeCampaign !== '') ? cmsmsStripeCampaign : cmsms_donations_stripe_params.description, 
				image : 			cmsms_donations_stripe_params.image, 
				amount : 			cmsmsStripeAmount, 
				currency : 			cmsms_donations_stripe_params.currency, 
				panelLabel : 		cmsms_donations_stripe_params.label + ' {{amount}}', 
				allowRememberMe : 	true, 
				token : 			cmsms_token_action 
			} );
			
			
			return false;
		}
    } );
} )(jQuery);

