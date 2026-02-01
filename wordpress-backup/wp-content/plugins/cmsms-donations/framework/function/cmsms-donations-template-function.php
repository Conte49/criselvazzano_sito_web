<?php
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Donations
 * @version		1.0.0
 * 
 * CMSMasters Donations Templates Functions
 * Created by CMSMasters
 * 
 */


function locate_cmsms_donations_template($template_name, $template_path = '', $default_path = '') {
	if (!$default_path) {
		$default_path = CMSMS_DONATIONS_TEMPLATE;
	}
	
	if (!$template_path) {
		$template_path = CMSMS_DONATIONS_THEME_TEMPLATES_DIR;
	}
	
	
	$template = locate_template(array( 
		trailingslashit($template_path) . $template_name, 
		$template_name 
	) );
	
	
	if (!$template) {
		$template = $default_path . $template_name;
	}
	
	
	return apply_filters('cmsms_donations_locate_template', $template, $template_name, $template_path);
}



function get_cmsms_donations_template($template_name, $args = array(), $template_path = '', $default_path = '') {
	if (is_array($args) && !empty($args)) {
		extract($args);
	}
	
	
	include(locate_cmsms_donations_template($template_name, $template_path, $default_path));
}



function get_cmsms_donations_template_part($slug, $name = '') {
	$template = '';
	
	
	if ($name) {
		$template = locate_template(array( 
			"{$slug}-{$name}.php", 
			CMSMS_DONATIONS_THEME_TEMPLATES_DIR . "/{$slug}-{$name}.php" 
		) );
	}
	
	
	if ( 
		!$template && 
		$name && 
		file_exists(CMSMS_DONATIONS_TEMPLATE . "{$slug}-{$name}.php") 
	) {
		$template = CMSMS_DONATIONS_TEMPLATE . "{$slug}-{$name}.php";
	}
	
	
	if (!$template) {
		$template = locate_template(array( 
			"{$slug}.php", 
			CMSMS_DONATIONS_THEME_TEMPLATES_DIR . "/{$slug}.php" 
		) );
	}
	
	
	if ($template) {
		load_template($template, false);
	}
}



function cmsms_donations_load_template($template_name, $args = array()) {
	$template = locate_template($template_name);
	
	
	if (is_array($args) && !empty($args)) {
		extract($args);
	}
	
	
	include($template);
	
	
	return $out;
}



function cmsms_number_format($number) {
	return number_format($number, 2, '.', '');
}



function cmsms_donations_currency($amount = 0) {
	$currency_symbol = get_option('cmsms_donations_currency_symbol', false);
	
	$currency_symbol_pos = get_option('cmsms_donations_currency_symbol_pos', false);
	
	
	return (($currency_symbol_pos == 'before') ? $currency_symbol : '') . 
		$amount . 
	(($currency_symbol_pos == 'after') ? $currency_symbol : '');
}



function get_the_donation_amount($post = null) {
	$post = get_post($post);
	
	
	if ($post->post_type !== 'donation') {
		return;
	}
	
	
	$meta = get_post_meta($post->ID, 'cmsms_donation_amount', true);
	
	
	return cmsms_number_format($meta);
}



function get_the_donation_amount_currency($post = null) {
	$amount = get_the_donation_amount($post);
	
	
	return cmsms_donations_currency($amount);
}



function get_the_donation_status($post = null) {
	$post = get_post($post);
	
	
	$status = $post->post_status;
	
	
	if ($status == 'publish') {
		$out = esc_attr__('Validated', 'cmsms_donations');
	} elseif ($status == 'pending_payment') {
		$out = esc_attr__('Pending Payment', 'cmsms_donations');
	} elseif ($status == 'pending_offline') {
		$out = esc_attr__('Pending Offline Payment', 'cmsms_donations');
	} else {
		$out = esc_attr__('Pending', 'cmsms_donations');
	}
	
	
	return $out;
}



function is_anonymous_donation($post = null) {
	$id = (!$post) ? get_the_ID() : (is_numeric($post) ? $post : $post->ID);
	
	
	$meta = get_post_meta($id, 'cmsms_anonymous_donation', true);
	
	
	return ($meta == 'true') ? true : false;
}



function is_recurring_donation($post_id = null) {
	$id = (!$post_id) ? get_the_ID() : $post_id;
	
	
	$meta = get_post_meta($id, 'cmsms_recurring_donation', true);
	
	
	return ((int) $meta == 1) ? true : false;
}



function get_the_recurrence_period($post = null) {
	$id = (!$post) ? get_the_ID() : (is_numeric($post) ? $post : $post->ID);
	
	
	$recurrence_period = get_post_meta($id, 'cmsms_recurrence_period', true);
	
	
	switch ($recurrence_period) {
	case '7':
		$period = esc_attr__('Weekly', 'cmsms_donations');
		
		
		break;
	case '30':
		$period = esc_attr__('Monthly', 'cmsms_donations');
		
		
		break;
	case '365':
		$period = esc_attr__('Yearly', 'cmsms_donations');
		
		
		break;
	default:
		$period = '';
	}
	
	
	return ($recurrence_period != '') ? $period : '';
}



function is_online_payment($id) {
	$payment_method = get_post_meta($id, 'cmsms_donation_payment_method', true);
	
	
	return ($payment_method == 'online') ? true : false;
}



function get_global_target() {
	$target = get_option('cmsms_donations_target', '0');
	
	
	return $target;
}



function get_the_donation_campaign($post = null, $link = true) {
	$id = (!$post) ? get_the_ID() : (is_numeric($post) ? $post : $post->ID);
	
	
	$campaign_id = get_post_meta($id, 'cmsms_donation_campaign', true);
	
	
	if ($campaign_id != '') {
		$campaign = (($link) ? '<a href="' . esc_url(get_permalink($campaign_id)) . '" title="' . esc_attr(get_the_title($campaign_id)) . '">' : '') . 
			esc_html(get_the_title($campaign_id)) . 
		(($link) ? '</a>' : '');
	} else {
		$campaign = false;
	}
	
	
	return $campaign;
}



function get_the_campaign_target($campaign_id, $round = false) {
    $campaign_target = get_post_meta($campaign_id, 'cmsms_campaign_target', true);
	
	
    if ($campaign_target != '') {
		$campaign_target = $round ? $campaign_target : cmsms_number_format($campaign_target);
	} else {
		$campaign_target = esc_attr__('None', 'cmsms_donations');
    }
	
	
	return $campaign_target;
}



function get_the_funds($campaign_id = false, $return_count = false) {
	$args = array( 
		'post_type' => 			'donation', 
		'post_status' => 		'publish', 
		'posts_per_page' => 	-1 
	);
	
	
	if ($campaign_id) {
		$args['meta_key'] = 'cmsms_donation_campaign';
		
		$args['meta_value'] = $campaign_id;
	}
	
	
	$query = new WP_Query($args);
	
	
	if ($return_count) {
		$out = $query->post_count;
	} else {
		$i = 1;
		
		
		$funds = 0;
		
		
		if ($query->post_count > 0) :
			while ($query->have_posts()) : $query->the_post();
				$amount = get_post_meta(get_the_ID(), 'cmsms_donation_amount', true);
				
				
				$funds += $amount;
			endwhile;
			
			
			$funds = esc_attr(strip_tags($funds));
			
			
			if ($funds > 0) {
				$out = cmsms_number_format($funds);
			} else {
				$out = '0';
			}
		else :
			$out = '0';
		endif;
	}
	
	
	wp_reset_query();
	
	
	return $out;
}



function get_the_funds_raised_percentage($campaign_id = false) {
	if ($campaign_id) {
		$target = get_the_campaign_target($campaign_id);
		
		$funds = get_the_funds($campaign_id);
	} else {
		$target = get_global_target();
		
		$funds = get_the_funds();
	}
	
	
	if ( 
		!is_numeric($target) || 
		(float) $target <= 0 || 
		!is_numeric($funds) || 
		(float) $funds <= 0 
	) {
		return '0';
	}
	
	
	return cmsms_number_format(($funds * 100) / $target);
}



function get_the_donator_meta($name = false, $post_id = null, $url = false) {
	$id = (!$post_id) ? get_the_ID() : $post_id;
	
	
	if (!$name) {
		return false;
	}
	
	
	$meta = get_post_meta($id, 'cmsms_donator_' . $name, true);
	
	
	if ($meta == '') {
		return false;
	}
	
	
	if ($url) {
		return esc_url($meta);
	} else {
		return esc_html($meta);
	}
}



function get_donation_classes($class = '', $post_id = null) {
	$post = get_post($post_id);
	
	
	$classes = array();
	
	
	if ($post->post_type != 'donation' || empty($post)) {
		return get_post_class();
	}
	
	
	if (is_anonymous_donation($post)) {
		$classes[] = 'anonymous_donation';
	}
	
	
	$post_classes = get_post_class($classes, $post->ID);
	
	
	return ' class="' . join(' ', $post_classes) . '"';
}

