<?php // User Submitted Posts - Enqueue Script & Style

if (!defined('ABSPATH')) die();

function usp_enqueueResources() {
	
	global $usp_options;
	
	$min_images    = isset($usp_options['min-images'])        ? $usp_options['min-images']        : null;
	$include_js    = isset($usp_options['usp_include_js'])    ? $usp_options['usp_include_js']    : null;
	$form_type     = isset($usp_options['usp_form_version'])  ? $usp_options['usp_form_version']  : null;
	$display_url   = isset($usp_options['usp_display_url'])   ? $usp_options['usp_display_url']   : null;
	$recaptcha     = isset($usp_options['usp_recaptcha'])     ? $usp_options['usp_recaptcha']     : null;
	$multi_cats    = isset($usp_options['multiple-cats'])     ? $usp_options['multiple-cats']     : null;
	$existing_tags = isset($usp_options['usp_existing_tags']) ? $usp_options['usp_existing_tags'] : null;
	
	$protocol = is_ssl() ? 'https://' : 'http://';
	
	$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'undefined';
	
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/na';
	
	$current_url = esc_url_raw($protocol . $http_host . $request_uri);
	
	$current_url = remove_query_arg(array('submission-error', 'error', 'success', 'post_id'), $current_url);
	
	$plugin_url  = plugins_url('/user-submitted-posts');
	
	$custom_url  = get_stylesheet_directory_uri() .'/usp/usp.css';
	
	$custom_path = get_stylesheet_directory() .'/usp/usp.css';
	
	$usp_css = ($form_type === 'custom' && file_exists($custom_path)) ? $custom_url : $plugin_url . '/resources/usp.css';
	
	$display_js  = false;
	$display_css = false;
	
	if (!empty($display_url)) {
		
		$display_urls = explode(',', $display_url);
		
		foreach ($display_urls as $url) {
			
			$url = esc_url_raw(trim($url));
			
			if ($url === $current_url) {
				
				if ($include_js == true)      $display_js  = true;
				if ($form_type !== 'disable') $display_css = true;
				
				break;
				
			}
			
		}
		
	} else {
		
		if ($include_js == true)      $display_js  = true;
		if ($form_type !== 'disable') $display_css = true;
		
	}
	
	if ($display_css) {
		
		wp_enqueue_style('usp_style', $usp_css, array(), USP_VERSION, 'all');
		
	}
	
	if ($display_js) {
		
		$deps = array();
		
		if ($recaptcha === 'show') {
			
			usp_enqueue_recaptcha();
			
			array_push($deps, 'usp_recaptcha');
			
		}
		
		if ($multi_cats || $existing_tags) {
			
			wp_enqueue_script('usp_chosen', $plugin_url .'/resources/jquery.chosen.js', array('jquery'), USP_VERSION);
			
			array_push($deps, 'jquery', 'usp_chosen');
			
		}
		
		array_push($deps, 'jquery', 'usp_cookie', 'usp_parsley');
		
		$deps = array_unique($deps);
		
		wp_enqueue_script('usp_cookie',  $plugin_url .'/resources/jquery.cookie.js',      array('jquery'), USP_VERSION);
		wp_enqueue_script('usp_parsley', $plugin_url .'/resources/jquery.parsley.min.js', array('jquery'), USP_VERSION);
		wp_enqueue_script('usp_core',    $plugin_url .'/resources/jquery.usp.core.js',    $deps,           USP_VERSION);
		
		usp_inline_script();
		
	}
	
}
add_action('wp_enqueue_scripts', 'usp_enqueueResources');

function usp_enqueue_recaptcha() {
	
	global $usp_options;
	
	if (isset($usp_options['usp_recaptcha']) && ($usp_options['usp_recaptcha'] == 'show')) {
		
		$recaptcha = isset($usp_options['recaptcha_public'])  ? $usp_options['recaptcha_public']  : '';
		$version   = isset($usp_options['recaptcha_version']) ? $usp_options['recaptcha_version'] : 2;
		
		$query = apply_filters('usp_recaptcha_querystring', '');
		
		if ($version == 3) {
			
			$query = !empty($query) ? '&hl='. $query : '';
			
			wp_enqueue_script('usp_recaptcha', 'https://www.google.com/recaptcha/api.js?render='. $recaptcha . $query, array(), null);
			
		} else {
			
			$query = !empty($query) ? '?hl='. $query : '';
			
			wp_enqueue_script('usp_recaptcha', 'https://www.google.com/recaptcha/api.js'. $query, array(), USP_VERSION);
			
		}
		
	}
	
}

function usp_inline_script() {
	
	global $usp_options;
	
	$min_images      = isset($usp_options['min-images'])           ? $usp_options['min-images']           : '';
	$max_images      = isset($usp_options['max-images'])           ? $usp_options['max-images']           : '';
	$custom_field    = isset($usp_options['custom_name'])          ? $usp_options['custom_name']          : '';
	$custom_field_2  = isset($usp_options['custom_name_2'])        ? $usp_options['custom_name_2']        : '';
	$custom_checkbox = isset($usp_options['custom_checkbox_name']) ? $usp_options['custom_checkbox_name'] : '';
	$usp_casing      = isset($usp_options['usp_casing'])           ? $usp_options['usp_casing']           : '';
	$usp_response    = isset($usp_options['usp_response'])         ? $usp_options['usp_response']         : '';
	$multiple_cats   = isset($usp_options['multiple-cats'])        ? $usp_options['multiple-cats']        : '';
	$existing_tags   = isset($usp_options['usp_existing_tags'])    ? $usp_options['usp_existing_tags']    : '';
	$recaptcha_disp  = isset($usp_options['usp_recaptcha'])        ? $usp_options['usp_recaptcha']        : '';
	$recaptcha_vers  = isset($usp_options['recaptcha_version'])    ? $usp_options['recaptcha_version']    : 2;
	$recaptcha_key   = isset($usp_options['recaptcha_public'])     ? $usp_options['recaptcha_public']     : '';
	
	$print_casing    = $usp_casing ? 'true' : 'false';
	$parsley_error   = apply_filters('usp_parsley_error', esc_html__('Incorrect response.', 'usp'));
	
	$script  = 'var usp_custom_field = '.       json_encode($custom_field)    .'; ';
	$script .= 'var usp_custom_field_2 = '.     json_encode($custom_field_2)  .'; ';
	$script .= 'var usp_custom_checkbox = '.    json_encode($custom_checkbox) .'; ';
	$script .= 'var usp_case_sensitivity = '.   json_encode($print_casing)    .'; ';
	$script .= 'var usp_challenge_response = '. json_encode($usp_response)    .'; ';
	$script .= 'var usp_min_images = '.         json_encode($min_images)      .'; ';
	$script .= 'var usp_max_images = '.         json_encode($max_images)      .'; ';
	$script .= 'var usp_parsley_error = '.      json_encode($parsley_error)   .'; ';
	$script .= 'var usp_multiple_cats = '.      json_encode($multiple_cats)   .'; ';
	$script .= 'var usp_existing_tags = '.      json_encode($existing_tags)   .'; ';
	$script .= 'var usp_recaptcha_disp = '.     json_encode($recaptcha_disp)  .'; ';
	$script .= 'var usp_recaptcha_vers = '.     json_encode($recaptcha_vers)  .'; ';
	$script .= 'var usp_recaptcha_key = '.      json_encode($recaptcha_key)   .'; ';
	
	wp_add_inline_script('usp_core', $script, 'before');
	
}

function usp_load_admin_styles($hook) {
	
	global $pagenow;
	
	if ($hook === 'settings_page_user-submitted-posts') {
		
		wp_enqueue_style('dashicons');
		wp_enqueue_style ('usp_admin_styles', USP_URL .'resources/usp-admin.css',       array(),         USP_VERSION, 'all');
		wp_enqueue_script('usp_admin_script', USP_URL .'resources/jquery.usp.admin.js', array('jquery'), USP_VERSION, false);
		wp_enqueue_media();
		
	}
	
	if ($pagenow === 'edit.php') {
		
		wp_enqueue_style('usp_posts_styles', USP_URL .'resources/usp-posts.css', array(), USP_VERSION, 'all');
		
	}
	
}
add_action('admin_enqueue_scripts', 'usp_load_admin_styles');
