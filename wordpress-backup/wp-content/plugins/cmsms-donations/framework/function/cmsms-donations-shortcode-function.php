<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.1
 * 
 * CMSMasters Donations Shortcodes Functions
 * Created by CMSMasters
 * 
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly


/**
 * Donation Submit Form
 */
function cmsms_submit_donation_form() {
	global $cmsms_donations_forms;
	
	
	wp_enqueue_style('cmsms-donations-form');
	
	
	if (is_rtl()) {
		wp_enqueue_style('cmsms-donations-form-rtl');
	}
	
	
	wp_enqueue_script('cmsmsValidation');
	
	wp_enqueue_script('cmsmsValidationLang');
	
	
	wp_enqueue_script('cmsms-donations-form-script');
	
	
	return $cmsms_donations_forms->get_form('submit-donation');
}

add_shortcode('cmsms_submit_donation_form', 'cmsms_submit_donation_form');



/**
 * Donations
 */
function cmsms_donations($atts, $content = null) {
	$new_atts = apply_filters('cmsms_donations_atts_filter', array( 
		'orderby' => 			'', 
		'order' => 				'', 
		'count' => 				'', 
		'campaigns' => 			'', 
		'columns' => 			'', 
		'donation_metadata' => 	'', 
		'animation' => 			'', 
		'animation_delay' => 	'', 
		'classes' => 			'' 
    ) );
	
	
	$shortcode_name = 'donations';
	
	$shortcode_path = CMSMS_DONATIONS_THEME_SHORTCODES_DIR . '/cmsms-' . $shortcode_name . '.php';
	
	
	if (locate_template($shortcode_path)) {
		$template_out = cmsms_donations_load_template($shortcode_path, array( 
			'atts' => 		$atts, 
			'new_atts' => 	$new_atts, 
			'content' => 	$content 
		) );
		
		
		return $template_out;
	}
	
	
	extract(shortcode_atts($new_atts, $atts));
	
	
	$unique_id = uniqid();
	
	
	global $cmsms_donation_metadata;
	
	$cmsms_donation_metadata = $donation_metadata;
	
	
	$args = array( 
		'post_type' => 				'donation', 
		'order' => 					$order, 
		'posts_per_page' => 		$count, 
		'ignore_sticky_posts' => 	true 
	);
	
	
	if ($campaigns != '') {
		$args['meta_query'] = array( 
			array( 
				'key' => 		'cmsms_donation_campaign', 
				'value' => 		$campaigns, 
				'compare' => 	'IN'
			) 
		);
	}
	
	
	if ($orderby == 'cmsms_donation_amount') {
		$args['orderby'] = 'meta_value_num';
		
		$args['meta_key'] = $orderby;
	} else {
		$args['orderby'] = $orderby;
	}
	
	
	$query = new WP_Query($args);
	
	
	if ($columns == 1) {
		$columns_class = 'one_first';
	} elseif ($columns == 2) {
		$columns_class = 'one_half';
	} elseif ($columns == 3) {
		$columns_class = 'one_third';
	} elseif ($columns == 4) {
		$columns_class = 'one_fourth';
	}
	
	
	$counter = 0;
	
	$out = '';
	
	if ($query->have_posts()) :
		$out .= '<div id="donations_' . $unique_id . '" class="cmsms_donations' . 
			(($classes != '') ? ' ' . $classes : '') . 
			'"' . 
			(($animation != '') ? ' data-animation="' . $animation . '"' : '') . 
			(($animation != '' && $animation_delay != '') ? ' data-delay="' . $animation_delay . '"' : '') . 
		'>' . "\n" . 
			'<div class="cmsms_row_margin">' . "\n";
				
				while ($query->have_posts()) : $query->the_post();
					if ($counter == $columns) {
						$out .= '</div>' . "\n" . 
						'<div class="cmsms_row_margin">' . "\n";
						
						$counter = 0;
					}
					
					$counter += 1;
					
					
					$out .= '<div class="cmsms_column ' . $columns_class . '">' . 
						load_template_part('cmsms-donations/postType/donation/standard') . 
					'</div>';
					
				endwhile;
				
			$out .= '</div>' . "\n" . 
		'</div>' . "\n";
	endif;
	
	
	wp_reset_postdata();
	
	wp_reset_query();
	
	
	return $out; 
}

add_shortcode('cmsms_donations', 'cmsms_donations');



/**
 * Featured Campaign
 */
function cmsms_featured_campaign($atts, $content = null) {
	$new_atts = apply_filters('cmsms_featured_campaign_atts_filter', array( 
		'campaign' => 			'', 
		'campaign_metadata' => 	'', 
		'animation' => 			'', 
		'animation_delay' => 	'', 
		'classes' => 			'' 
    ) );
	
	
	$shortcode_name = 'featured-campaign';
	
	$shortcode_path = CMSMS_DONATIONS_THEME_SHORTCODES_DIR . '/cmsms-' . $shortcode_name . '.php';
	
	
	if (locate_template($shortcode_path)) {
		$template_out = cmsms_donations_load_template($shortcode_path, array( 
			'atts' => 		$atts, 
			'new_atts' => 	$new_atts, 
			'content' => 	$content 
		) );
		
		
		return $template_out;
	}
	
	
	extract(shortcode_atts($new_atts, $atts));
	
	
	$unique_id = uniqid();
	
	
	global $cmsms_featured_campaign_metadata;
	
	$cmsms_featured_campaign_metadata = $campaign_metadata;
	
	
	$args = array( 
		'p' => 						$campaign, 
		'post_type' => 				'campaign', 
		'ignore_sticky_posts' => 	true 
	);
	
	
	$query = new WP_Query($args);
	
	
	$out = '';
	
	if ($query->have_posts()) :
		$out .= '<div id="featured_campaign_' . $unique_id . '" class="cmsms_featured_campaign' . 
			(($classes != '') ? ' ' . $classes : '') . 
			'"' . 
			(($animation != '') ? ' data-animation="' . $animation . '"' : '') . 
			(($animation != '' && $animation_delay != '') ? ' data-delay="' . $animation_delay . '"' : '') . 
		'>' . "\n";
			
			while ($query->have_posts()) : $query->the_post();
				
				$out .= load_template_part('cmsms-donations/postType/campaign/vertical');
				
			endwhile;
			
		$out .= '</div>' . "\n";
	endif;
	
	
	wp_reset_postdata();
	
	wp_reset_query();
	
	
	return $out;
}

add_shortcode('cmsms_featured_campaign', 'cmsms_featured_campaign');



/**
 * Campaigns
 */
function cmsms_campaigns($atts, $content = null) {
	$new_atts = apply_filters('cmsms_campaigns_atts_filter', array( 
		'orderby' => 				'', 
		'campaigns_ids' => 			'', 
		'order' => 					'', 
		'campaigns_categories' => 	'', 
		'columns' => 				'', 
		'count' => 					'', 
		'pause' => 					'', 
		'campaigns_metadata' => 	'', 
		'animation' => 				'', 
		'animation_delay' => 		'', 
		'classes' => 				'' 
    ) );
	
	
	$shortcode_name = 'campaigns';
	
	$shortcode_path = CMSMS_DONATIONS_THEME_SHORTCODES_DIR . '/cmsms-' . $shortcode_name . '.php';
	
	
	if (locate_template($shortcode_path)) {
		$template_out = cmsms_donations_load_template($shortcode_path, array( 
			'atts' => 		$atts, 
			'new_atts' => 	$new_atts, 
			'content' => 	$content 
		) );
		
		
		return $template_out;
	}
	
	
	extract(shortcode_atts($new_atts, $atts));
	
	
	$unique_id = uniqid();
	
	
	global $cmsms_campaigns_metadata;
	
	$cmsms_campaigns_metadata = $campaigns_metadata;
	
	
	$args = array( 
		'post_type' => 				'campaign', 
		'order' => 					$order, 
		'posts_per_page' => 		$count, 
		'ignore_sticky_posts' => 	true 
	);
	
	
	if ($orderby == 'campaigns' && $campaigns_ids != '') {
		$campaigns_ids_array = explode(',', $campaigns_ids);
		
		$args['post__in'] = $campaigns_ids_array;
		
		$args['orderby'] = 'menu_order';
	} else {
		$args['orderby'] = $orderby;
		
		if ($campaigns_categories != '') {
			$cat_array = explode(',', $campaigns_categories);
			
			$args['tax_query'] = array(
				array( 
					'taxonomy' => 	'cp-categs', 
					'field' => 		'slug', 
					'terms' => 		$cat_array 
				)
			);
		}
	}
	
	
	$query = new WP_Query($args);
	
	
	$pause = ($pause == '' ? 0 : $pause);
	
	
	$out = "";
	
	
	if ($query->have_posts()) : 
		
		$out .= "<div class=\"cmsms_campaigns" . 
			(($classes != '') ? ' ' . $classes : '') . 
		"\" " . 
			(($animation != '') ? ' data-animation="' . $animation . '"' : '') . 
			(($animation != '' && $animation_delay != '') ? ' data-delay="' . $animation_delay . '"' : '') . 
		">
			<script type=\"text/javascript\">
				jQuery(document).ready(function () { 
					var container = jQuery('.cmsms_slider_{$unique_id}');
						containerWidth = container.width(), 
						firstPost = container.find('article'), 
						postMinWidth = Number(firstPost.css('minWidth').replace('px', '')), 
						postThreeColumns = (postMinWidth * 4) - 1;
						postTwoColumns = (postMinWidth * 3) - 1;
						postOneColumns = (postMinWidth * 2) - 1; 
					
					
					jQuery('.cmsms_slider_{$unique_id}').owlCarousel( {
						items : {$columns}, 
						itemsDesktop : false,
						itemsDesktopSmall : [postThreeColumns," . (($columns > 3) ? '3' : $columns) . "], 
						itemsTablet : [postTwoColumns," . (($columns > 2) ? '2' : $columns) . "], 
						itemsMobile : [postOneColumns,1], 
						transitionStyle : false, 
						rewindNav : true, 
						slideSpeed : 200, 
						paginationSpeed : 800, 
						rewindSpeed : 1000, " . 
						(($pause == '0') ? 'autoPlay : false, ' : 'autoPlay : ' . ($pause * 1000) . ', ') . 
						"stopOnHover : true, 
						autoHeight : true, 
						addClassActive : true, 
						responsiveBaseWidth : '.cmsms_slider_{$unique_id}', 
						pagination : false, 
						navigation : true, 
						navigationText : [ " . 
							'"<span class=\"cmsms_prev_arrow\"><span></span></span>", ' . 
							'"<span class=\"cmsms_next_arrow\"><span></span></span>" ' . 
						"] 
					} );
				} );
			</script>
			<div id=\"cmsms_owl_carousel_{$unique_id}\" class=\"" . 
				'cmsms_owl_slider ' . 
				'cmsms_slider_' . $unique_id . '">';
				
				while ($query->have_posts()) : $query->the_post();
					
					$out .= '<div>' . 
						load_template_part('cmsms-donations/postType/campaign/horizontal') . 
					'</div>';
					
				endwhile;
				
			$out .= '</div>' . 
		'</div>';
	
	endif;
	
	
	wp_reset_postdata();
	
	wp_reset_query();
	
	
	return $out;
}

add_shortcode('cmsms_campaigns', 'cmsms_campaigns');

