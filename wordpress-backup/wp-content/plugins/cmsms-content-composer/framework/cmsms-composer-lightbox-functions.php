<?php 
/**
 * @package 	WordPress Plugin
 * @subpackage 	CMSMasters Content Composer
 * @version		1.4.8
 * 
 * Composer Lightbox Functions
 * Created by CMSMasters
 * 
 */


global $pagenow;


if ( 
	is_admin() && 
	$pagenow == 'post-new.php' || 
	($pagenow == 'post.php' && isset($_GET['post']) && get_post_type($_GET['post']) != 'attachment') 
) {
	add_action('admin_footer', 'cmsms_composer_shortcodes_init');
}


function cmsms_composer_shortcodes_init() {
	if (wp_script_is('cmsms_content_composer_js', 'queue') && wp_script_is('cmsms_composer_lightbox_js', 'queue')) {
		cmsms_composer_colors();
		
		cmsms_composer_cf7_forms();
		
		cmsms_composer_cfb_forms();
		
		cmsms_composer_layer_slider();
		
		cmsms_composer_rev_slider();
		
		cmsms_composer_fonts();
		
		cmsms_composer_font_weight();
		
		cmsms_composer_font_style();
		
		cmsms_composer_sidebars();
		
		cmsms_composer_categories();
		
		cmsms_composer_pj_categories();
		
		cmsms_composer_pl_categories();
		
		cmsms_composer_thumbnail_sizes();
		
		cmsms_composer_products();
		
		if (CMSMS_WOOCOMMERCE) {
			cmsms_composer_product_ids();
		}
		
		cmsms_composer_paypal_donations();
		
		cmsms_composer_donations();
		
		if (CMSMS_DONATIONS) {
			cmsms_composer_campaign_ids();
			
			cmsms_composer_donation_but_campaign_ids();
			
			cmsms_composer_campaign_categories();
		}
		
		cmsms_composer_timetable();
		
		if (CMSMS_TIMETABLE) {
			cmsms_composer_timetable_events();
			
			cmsms_composer_timetable_event_categories();
			
			cmsms_composer_timetable_hour_categories();
			
			cmsms_composer_timetable_columns();
		}
	}
}


function cmsms_composer_colors() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_colors() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	foreach (cmsms_color_schemes_list() as $key => $value) {
		$out .= "\t\t\t\"" . $key . "\" : \"" . $value . "\", \n";
	}
	
	
	$out = substr($out, 0, -3);
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_cf7_forms() {
	global $post;
	
	
	$admin_post_object = $post;
	
	
	$option_query = new WP_Query(array( 
		'orderby' => 			'name', 
		'order' => 				'ASC', 
		'post_type' => 			'wpcf7_contact_form', 
		'posts_per_page' => 	-1 
	));
	
	
	$forms = array();
	
	
	if ($option_query->have_posts()) : 
		while ($option_query->have_posts() ) : $option_query->the_post();
			$forms[get_the_ID()] = get_the_title();
		endwhile;
	endif;
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_cf7_forms() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	foreach ($forms as $form_key => $form_value) {
		$out .= "\t\t\t\"" . $form_key . "{|}" . addslashes($form_value) . "\" : \"" . $form_value . "\", \n";
	}
	
	
	if (!empty($forms)) {
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	wp_reset_query();
	
	
	$post = $admin_post_object;
	
	
	echo $out;
}


function cmsms_composer_cfb_forms() {
	global $frm_bldr;
	
	
	if (!empty($frm_bldr)) {
		$forms = $frm_bldr->form_builder_forms_list();
	} else {
		$forms = '';
	}
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_cfb_forms() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($forms)) {
		foreach ($forms as $form_key => $form_value) {
			$out .= "\t\t\t\"" . $form_key . "\" : \"" . $form_value . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_layer_slider() {
	if (class_exists('LS_Sliders')) {
		$sliders = LS_Sliders::find(array( 
			'limit' => 	100 
		));
	}
	
	
	if (!isset($sliders)) {
		$sliders = '';
	}
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_layer_slider() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($sliders)) {
		foreach ($sliders as $item) {
			$name = empty($item['name']) ? __('Unnamed', 'cmsms_content_composer') : $item['name'];
			
			
			$out .= "\t\t\t\"" . $item['id'] . "\" : \"" . $name . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_rev_slider() {
	if (class_exists('RevSlider')) {
		$slider = new RevSlider();
		
		
		$arrSlidersTitle = $slider->getArrSlidersShort();
		
		$arrSlidersAlias = $slider->getAllSliderAliases();
	}
	
	
	if (!isset($arrSlidersTitle)) {
		$arrSlidersTitle = '';
		
		$arrSlidersAlias = '';
	}
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_rev_slider() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($arrSlidersTitle)) {
		for ($i = 0; $i < sizeof($arrSlidersAlias); $i++) {
			$out .= "\t\t\t\"" . $arrSlidersAlias[$i] . "\" : \"" . (($arrSlidersTitle[$i + 1] != '') ? $arrSlidersTitle[$i + 1] : $arrSlidersAlias[$i]) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_fonts() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_fonts() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	foreach (cmsms_google_fonts_list() as $key => $value) {
		$out .= "\t\t\t\"" . $key . "\" : \"" . $value . "\", \n";
	}
	
	
	$out = substr($out, 0, -3);
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_font_weight() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_font_weight() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	foreach (cmsms_font_weight_list() as $key => $value) {
		$out .= "\t\t\t\"" . $key . "\" : \"" . $value . "\", \n";
	}
	
	
	$out = substr($out, 0, -3);
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_font_style() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_font_style() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	foreach (cmsms_font_style_list() as $key => $value) {
		$out .= "\t\t\t\"" . $key . "\" : \"" . $value . "\", \n";
	}
	
	
	$out = substr($out, 0, -3);
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_sidebars() {
	global $wp_registered_sidebars;
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_sidebars() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	foreach ($wp_registered_sidebars as $wp_registered_sidebar) {
		$out .= "\t\t\t\"" . $wp_registered_sidebar['id'] . "\" : \"" . $wp_registered_sidebar['name'] . "\", \n";
	}
	
	
	$out = substr($out, 0, -3);
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_categories() {
	$categories = get_categories(array( 
		'hide_empty' => 0 
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_categories() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($categories)) {
		foreach ($categories as $category) {
			$out .= "\t\t\t\"" . $category->slug . "\" : \"" . esc_html($category->name) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_pj_categories() {
	$categories = get_terms('pj-categs', array( 
		'hide_empty' => 0 
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_pj_categories() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($categories)) {
		foreach ($categories as $category) {
			$out .= "\t\t\t\"" . $category->slug . "\" : \"" . esc_html($category->name) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_pl_categories() {
	$categories = get_terms('pl-categs', array( 
		'hide_empty' => 0 
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_pl_categories() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($categories)) {
		foreach ($categories as $category) {
			$out .= "\t\t\t\"" . $category->slug . "\" : \"" . esc_html($category->name) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_thumbnail_sizes() {
	$cmsms_sizes = cmsms_image_thumbnail_list();
	
	
	$sizes_array = get_intermediate_image_sizes();
	
	
	array_push($sizes_array, 'full');
	
	
	$sizes = array();
	
	
	foreach($sizes_array as $s) {
		if (in_array($s, array('thumbnail', 'medium', 'large'))) {
			if ($s == 'thumbnail') {
				$sizes[$s][0] = __('Thumbnail', 'cmsms_content_composer');
			} elseif ($s == 'medium') {
				$sizes[$s][0] = __('Medium', 'cmsms_content_composer');
			} elseif ($s == 'large') {
				$sizes[$s][0] = __('Large', 'cmsms_content_composer');
			}
			
			
			$sizes[$s][1] = get_option($s . '_size_w');
			
			
			$sizes[$s][2] = get_option($s . '_size_h');
		} elseif ($s == 'full') {
			$sizes[$s] = array(__('Full Size', 'cmsms_content_composer'), __('Original image size', 'cmsms_content_composer'), '');
		} else {
			if (isset($cmsms_sizes) && isset($cmsms_sizes[$s]) && isset($cmsms_sizes[$s]['title'])) {
				$sizes[$s] = array($cmsms_sizes[$s]['title'], $cmsms_sizes[$s]['width'], $cmsms_sizes[$s]['height']);
			}
		}
	}
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_thumbnail_sizes() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	foreach ($sizes as $size => $attrs) {
		$out .= "\t\t\t\"" . $size . "\" : \"" . $attrs[0] . " &ndash; " . $attrs[1] . (($attrs[2] != '') ? " &#735; " . $attrs[2] : '') . "\", \n";
	}
	
	
	$out = substr($out, 0, -3);
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_products() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_products() { ' . "\n\t\t";
	
	
	if (CMSMS_WOOCOMMERCE) {
		$out .= "return 'true'; \n";
	} else {
		$out .= "return 'false'; \n";
	}
	
	
	$out .= '} ' . "\n" . 
		'cmsms_composer_products();' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_product_ids() {
	$product_ids = get_posts(array(
		'numberposts' => -1, 
		'post_type' => 'product'
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_product_ids() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($product_ids)) {
		foreach ($product_ids as $product_id) {
			$out .= "\t\t\t\"" . $product_id->ID . "\" : \"" . esc_html($product_id->post_title) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_paypal_donations() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_paypal_donations() { ' . "\n\t\t";
	
	
	if (CMSMS_PAYPALDONATIONS) {
		$out .= "return 'true'; \n";
	} else {
		$out .= "return 'false'; \n";
	}
	
	
	$out .= '} ' . "\n" . 
		'cmsms_composer_paypal_donations();' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_donations() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_donations() { ' . "\n\t\t";
	
	
	if (CMSMS_DONATIONS) {
		$out .= "return 'true'; \n";
	} else {
		$out .= "return 'false'; \n";
	}
	
	
	$out .= '} ' . "\n" . 
		'cmsms_composer_donations();' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_campaign_ids() {
	$campaign_ids = get_posts(array(
		'numberposts' => -1, 
		'post_type' => 'campaign'
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_campaign_ids() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($campaign_ids)) {
		foreach ($campaign_ids as $campaign_id) {
			$out .= "\t\t\t\"" . $campaign_id->ID . "\" : \"" . esc_html($campaign_id->post_title) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_donation_but_campaign_ids() {
	$campaign_ids = get_posts(array(
		'numberposts' => -1, 
		'post_type' => 'campaign'
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_donation_but_campaign_ids() { ' . "\n\t\t" . 
			'return { ' . "\n" . 
				"\t\t\t\"\" : \"" . esc_html__('No specific campaign', 'cmsms_content_composer') . "\", \n";
	
	
	if (!empty($campaign_ids)) {
		foreach ($campaign_ids as $campaign_id) {
			$out .= "\t\t\t\"" . $campaign_id->ID . "\" : \"" . esc_html($campaign_id->post_title) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_campaign_categories() {
	$categories = get_terms('cp-categs', array( 
		'hide_empty' => 0 
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_campaign_categories() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($categories)) {
		foreach ($categories as $category) {
			$out .= "\t\t\t\"" . $category->slug . "\" : \"" . esc_html($category->name) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_timetable() {
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_timetable() { ' . "\n\t\t";
	
	
	if (CMSMS_TIMETABLE) {
		$out .= "return 'true'; \n";
	} else {
		$out .= "return 'false'; \n";
	}
	
	
	$out .= '} ' . "\n" . 
		'cmsms_composer_timetable();' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_timetable_events() {
	$timetable_events = get_posts(array(
		'numberposts' => -1, 
		'post_type' => 'events'
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_timetable_events() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($timetable_events)) {
		foreach ($timetable_events as $timetable_event) {
			$out .= "\t\t\t\"" . urldecode($timetable_event->post_name) . "\" : \"" . esc_html($timetable_event->post_title) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_timetable_event_categories() {
	$categories = get_terms('events_category', array( 
		'hide_empty' => 0 
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_timetable_event_categories() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($categories)) {
		foreach ($categories as $category) {
			$out .= "\t\t\t\"" . urldecode(esc_attr($category->slug)) . "\" : \"" . esc_html($category->name) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_timetable_hour_categories() {
	global $wpdb;
	
	
	$query = "SELECT distinct(category) AS category FROM " . $wpdb->prefix . "event_hours AS t1
		LEFT JOIN {$wpdb->posts} AS t2 ON t1.event_id=t2.ID 
		WHERE 
		t2.post_type='events'
		AND t2.post_status='publish'
		AND category<>''";
	
	
	$categories = $wpdb->get_results($query);
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_timetable_hour_categories() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($categories)) {
		foreach ($categories as $category) {
			$out .= "\t\t\t\"" . esc_attr($category->category) . "\" : \"" . esc_html($category->category) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}


function cmsms_composer_timetable_columns() {
	$timetable_columns = get_posts(array(
		'numberposts' => -1, 
		'post_type' => 'timetable_weekdays'
	));
	
	
	$out = "\n" . '<script type="text/javascript"> ' . "\n" . 
	'/* <![CDATA[ */' . "\n\t" . 
		'function cmsms_composer_timetable_columns() { ' . "\n\t\t" . 
			'return { ' . "\n";
	
	
	if (!empty($timetable_columns)) {
		foreach ($timetable_columns as $timetable_column) {
			$out .= "\t\t\t\"" . urldecode($timetable_column->post_name) . "\" : \"" . esc_html($timetable_column->post_title) . "\", \n";
		}
		
		
		$out = substr($out, 0, -3);
	}
	
	
	$out .= "\n\t\t" . '}; ' . "\n\t" . 
		'} ' . "\n" . 
	'/* ]]> */' . "\n" . 
	'</script>' . "\n\n";
	
	
	echo $out;
}

