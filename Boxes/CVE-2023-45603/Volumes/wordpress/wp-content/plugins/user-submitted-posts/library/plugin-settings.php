<?php // User Submitted Posts - Plugin Settings

if (!defined('ABSPATH')) die();



function usp_add_options_page() {
	
	add_options_page(USP_PLUGIN, __('Submitted Posts', 'usp'), 'manage_options', 'user-submitted-posts', 'usp_render_form');
	
}
add_action('admin_menu', 'usp_add_options_page');



function usp_init() {
	
	register_setting('usp_plugin_options', 'usp_options', 'usp_validate_options');
	
}
add_action('admin_init', 'usp_init');



function usp_plugin_action_links($links, $file) {
	
	if ($file === USP_FILE && current_user_can('manage_options')) {
		
		$settings = '<a href="'. admin_url('options-general.php?page=user-submitted-posts') .'">'. esc_html__('Settings', 'usp') .'</a>';
		
		array_unshift($links, $settings);
		
	}
	
	if ($file === USP_FILE) {
		
		$pro_href  = 'https://plugin-planet.com/usp-pro/';
		$pro_title = esc_attr__('Get USP Pro for unlimited forms!', 'usp');
		$pro_text  = esc_html__('Go Pro', 'usp');
		$pro_style = 'font-weight:bold;';
		
		$pro = '<a target="_blank" rel="noopener noreferrer" href="'. $pro_href .'" title="'. $pro_title .'" style="'. $pro_style .'">'. $pro_text .'</a>';
		
		array_unshift($links, $pro);
		
	}
	
	return $links;
	
}
add_filter('plugin_action_links', 'usp_plugin_action_links', 10, 2);



function add_usp_links($links, $file) {
	
	if ($file === USP_FILE) {
		
		$home_href  = 'https://perishablepress.com/user-submitted-posts/';
		$home_title = esc_attr__('Plugin Homepage', 'usp');
		$home_text  = esc_html__('Homepage', 'usp');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		
		$rate_href  = 'https://wordpress.org/support/plugin/user-submitted-posts/reviews/?rate=5#new-post';
		$rate_title = esc_attr__('Give USP a 5-star rating at WordPress.org', 'usp');
		$rate_text  = esc_html__('Rate this plugin&nbsp;&raquo;', 'usp');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';
		
	}
	
	return $links;
	
}
add_filter('plugin_row_meta', 'add_usp_links', 10, 2);



function usp_admin_footer_text($text) {
	
	$screen_id = usp_get_current_screen_id();
	
	$ids = array('settings_page_user-submitted-posts/user-submitted-posts');
	
	if ($screen_id && apply_filters('usp_admin_footer_text', in_array($screen_id, $ids))) {
		
		$text = __('Like this plugin? Give it a', 'usp');
		
		$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/user-submitted-posts/reviews/?rate=5#new-post">';
		
		$text .= __('★★★★★ rating&nbsp;&raquo;', 'usp') .'</a>';
		
	}
	
	return $text;
	
}
add_filter('admin_footer_text', 'usp_admin_footer_text', 10, 1);



function usp_get_current_screen_id() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'id')) return $screen->id;
	
	return false;
	
}



function usp_get_current_screen_post_type() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'post_type')) return $screen->post_type;
	
	return false;
	
}



function usp_filter_safe_styles($styles) {
	
	 $styles[] = 'display'; 
	 
	 return $styles;
	 
}
add_filter('safe_style_css', 'usp_filter_safe_styles');



function usp_compare_version() {
	
	global $usp_options;
	
	$usp_options = get_option('usp_options');
	
	$version_current = intval(USP_VERSION);
	$version_previous = isset($usp_options['usp_version']) ? intval($usp_options['usp_version']) : $version_current;
	
	if ($version_current > $version_previous) {
		
		$usp_options['version_alert'] = 0;
		$usp_options['usp_version'] = $version_current;
		
	} else {
		
		$usp_options['usp_version'] = $version_previous;
		
	}
	
	update_option('usp_options', $usp_options);
	
}
add_action('admin_init', 'usp_compare_version');



function usp_post_type() {
	
	$post_type = array(
		
		'post' => array(
			'value' => 'post',
			'label' => esc_html__('WP Post (recommended)', 'usp'),
		),
		'page' => array(
			'value' => 'page',
			'label' => esc_html__('WP Page', 'usp'),
		),
	);
	
	return apply_filters('usp_post_type_options', $post_type);
	
}



function usp_form_version() {
	
	$form_version = array(
		
		'current' => array(
			'value' => 'current',
			'label' => esc_html__('HTML5 Form + Default CSS', 'usp') .' <span class="mm-item-caption">'. esc_html__('(Recommended)', 'usp') .'</span>',
		),
		'disable' => array(
			'value' => 'disable',
			'label' => esc_html__('HTML5 Form + Disable CSS', 'usp') .' <span class="mm-item-caption">'. esc_html__('(Provide your own styles)', 'usp') .'</span>',
		),
		'custom' => array(
			'value' => 'custom',
			'label' => esc_html__('Custom Form + Custom CSS', 'usp') .' <span class="mm-item-caption">'. esc_html__('(Provide your own form template &amp; styles)', 'usp') .'</span>',
		),
	);
	
	return $form_version;
	
}



function usp_image_display() {
	
	$image_display = array(
		
		'before' => array(
			'value' => 'before',
			'label' => esc_html__('Before post content', 'usp')
		),
		'after' => array(
			'value' => 'after',
			'label' => esc_html__('After post content', 'usp')
		),
		'disable' => array(
			'value' => 'disable',
			'label' => esc_html__('Do not display', 'usp')
		),
	);
	
	return $image_display;
	
}



function usp_email_display() {
	
	$email_display = array(
		
		'before' => array(
			'value' => 'before',
			'label' => esc_html__('Before post content', 'usp')
		),
		'after' => array(
			'value' => 'after',
			'label' => esc_html__('After post content', 'usp')
		),
		'disable' => array(
			'value' => 'disable',
			'label' => esc_html__('Do not display', 'usp')
		),
	);
	
	return $email_display;
	
}



function usp_name_display() {
	
	$name_display = array(
		
		'before' => array(
			'value' => 'before',
			'label' => esc_html__('Before post content', 'usp')
		),
		'after' => array(
			'value' => 'after',
			'label' => esc_html__('After post content', 'usp')
		),
		'disable' => array(
			'value' => 'disable',
			'label' => esc_html__('Do not display', 'usp')
		),
	);
	
	return $name_display;
	
}



function usp_url_display() {
	
	$url_display = array(
		
		'before' => array(
			'value' => 'before',
			'label' => esc_html__('Before post content', 'usp')
		),
		'after' => array(
			'value' => 'after',
			'label' => esc_html__('After post content', 'usp')
		),
		'disable' => array(
			'value' => 'disable',
			'label' => esc_html__('Do not display', 'usp')
		),
	);
	
	return $url_display;
	
}



function usp_custom_display() {
	
	$custom_display = array(
		
		'before' => array(
			'value' => 'before',
			'label' => esc_html__('Before post content', 'usp')
		),
		'after' => array(
			'value' => 'after',
			'label' => esc_html__('After post content', 'usp')
		),
		'disable' => array(
			'value' => 'disable',
			'label' => esc_html__('Do not display', 'usp')
		),
	);
	
	return $custom_display;
	
}



function usp_recaptcha_version() {
	
	$recaptcha_version = array(
		
		2 => array(
			'value' => 2,
			'label' => esc_html__('v2 (I&rsquo;m not a robot)', 'usp')
		),
		3 => array(
			'value' => 3,
			'label' => esc_html__('v3 (Hidden reCaptcha)', 'usp')
		),
	);
	
	return $recaptcha_version;
	
}



function usp_form_field_options($args) {
	
	global $usp_options;
	
	$name  = isset($args[0]) ? $args[0] : '';
	$label = isset($args[1]) ? $args[1] : '';
	
	$option = isset($usp_options[$name]) ? $usp_options[$name] : '';
	
	$selected_show = ($option === 'show') ? 'selected="selected"' : '';
	$selected_optn = ($option === 'optn') ? 'selected="selected"' : '';
	$selected_hide = ($option === 'hide') ? 'selected="selected"' : '';
	
	$output  = '<tr>';
	$output .= '<th scope="row"><label class="description" for="usp_options['. esc_attr($name) .']">'. esc_html($label) .'</label></th>';
	$output .= '<td>';
	$output .= '<select name="usp_options['. esc_attr($name) .']" id="usp_options['. esc_attr($name) .']">';
	
	$output .= '<option '. $selected_show .' value="show">'. esc_html__('Enable and require', 'usp')        .'</option>';
	$output .= '<option '. $selected_optn .' value="optn">'. esc_html__('Enable but do not require', 'usp') .'</option>';
	$output .= '<option '. $selected_hide .' value="hide">'. esc_html__('Disable this field', 'usp')        .'</option>';
	
	$output .= '</select>';
	$output .= '</td>';
	$output .= '</tr>';
	
	return $output;
	
}



function usp_form_field_options_custom($field) {
	
	global $usp_options;
	
	if ($field === '1') {
		
		$name  = 'custom_field';
		$label = esc_html__('Custom Field 1', 'usp');
		
	} else {
		
		$name  = 'custom_field_2';
		$label = esc_html__('Custom Field 2', 'usp');
		
	}
	
	$option = isset($usp_options[$name]) ? $usp_options[$name] : '';
	
	$selected_show = ($option === 'show') ? 'selected="selected"' : '';
	$selected_optn = ($option === 'optn') ? 'selected="selected"' : '';
	$selected_hide = ($option === 'hide') ? 'selected="selected"' : '';
	
	$output  = '<tr>';
	$output .= '<th scope="row"><label class="description" for="usp_options['. esc_attr($name) .']">'. esc_html($label) .'</label></th>';
	$output .= '<td>';
	$output .= '<select name="usp_options['. esc_attr($name) .']" id="usp_options['. esc_attr($name) .']">';
	
	$output .= '<option '. $selected_show .' value="show">'. esc_html__('Enable and require', 'usp')        .'</option>';
	$output .= '<option '. $selected_optn .' value="optn">'. esc_html__('Enable but do not require', 'usp') .'</option>';
	$output .= '<option '. $selected_hide .' value="hide">'. esc_html__('Disable this field', 'usp')        .'</option>';
	
	$output .= '</select>';
	$output .= '</td>';
	$output .= '</tr>';
	
	return $output;
	
}



function usp_form_field_options_captcha() {
	
	global $usp_options;
	
	$name  = 'usp_captcha';
	$label = esc_html__('Challenge Question', 'usp');
	
	$option = isset($usp_options[$name]) ? $usp_options[$name] : '';
	
	$selected_show = ($option === 'show') ? 'selected="selected"' : '';
	$selected_hide = ($option === 'hide') ? 'selected="selected"' : '';
	
	$output  = '<tr>';
	$output .= '<th scope="row"><label class="description" for="usp_options['. esc_attr($name) .']">'. esc_html($label) .'</label></th>';
	$output .= '<td>';
	$output .= '<select name="usp_options['. esc_attr($name) .']" id="usp_options['. esc_attr($name) .']">';
	
	$output .= '<option '. $selected_show .' value="show">'. esc_html__('Enable and require', 'usp') .'</option>';
	$output .= '<option '. $selected_hide .' value="hide">'. esc_html__('Disable this field', 'usp')  .'</option>';
	
	$output .= '</select>';
	$output .= '</td>';
	$output .= '</tr>';
	
	return $output;
	
}



function usp_form_field_options_recaptcha() {
	
	global $usp_options;
	
	$name  = 'usp_recaptcha';
	$label = esc_html__('Google reCaptcha', 'usp');
	
	$option = isset($usp_options[$name]) ? $usp_options[$name] : '';
	
	$selected_show = ($option === 'show') ? 'selected="selected"' : '';
	$selected_hide = ($option === 'hide') ? 'selected="selected"' : '';
	
	$output  = '<tr>';
	$output .= '<th scope="row"><label class="description" for="usp_options['. esc_attr($name) .']">'. esc_html($label) .'</label></th>';
	$output .= '<td>';
	$output .= '<select name="usp_options['. esc_attr($name) .']" id="usp_options['. esc_attr($name) .']">';
	
	$output .= '<option '. $selected_show .' value="show">'. esc_html__('Enable and require', 'usp') .'</option>';
	$output .= '<option '. $selected_hide .' value="hide">'. esc_html__('Disable this field', 'usp')  .'</option>';
	
	$output .= '</select>';
	$output .= '</td>';
	$output .= '</tr>';
	
	return $output;
	
}



function usp_form_field_options_images() {
	
	global $usp_options;
	
	$name  = 'usp_images';
	$label = esc_html__('Image Uploads', 'usp');
	
	$option = isset($usp_options[$name]) ? $usp_options[$name] : '';
	
	$selected_show = ($option === 'show') ? 'selected="selected"' : '';
	$selected_hide = ($option === 'hide') ? 'selected="selected"' : '';
	
	$output  = '<tr>';
	$output .= '<th scope="row"><label class="description" for="usp_options['. esc_attr($name) .']">'. esc_html($label) .'</label></th>';
	$output .= '<td>';
	$output .= '<select name="usp_options['. esc_attr($name) .']" id="usp_options['. esc_attr($name) .']">';
	
	$output .= '<option '. $selected_show .' value="show">'. esc_html__('Enable', 'usp') .'</option>';
	$output .= '<option '. $selected_hide .' value="hide">'. esc_html__('Disable', 'usp') .'</option>';
	
	$output .= '</select>';
	$output .= '</td>';
	$output .= '</tr>';
	
	return $output;
	
}



function usp_form_display_options() {
	
	global $usp_options;
	
	$radio_setting = isset($usp_options['usp_form_version']) ? $usp_options['usp_form_version'] : '';
	
	$form_styles = usp_form_version();
	
	$output = '';
	
	foreach ($form_styles as $form_style) {
		
		$label = isset($form_style['label']) ? $form_style['label'] : '';
		$value = isset($form_style['value']) ? $form_style['value'] : '';
		
		$checked = (!empty($radio_setting) && $radio_setting === $value) ? 'checked="checked"' : '';
		
		$class = ($value === 'custom') ? 'usp-custom-form' : 'usp-form';
		
		$output .= '<div class="mm-radio-inputs">';
		$output .= '<input type="radio" name="usp_options[usp_form_version]" class="'. $class .'" value="'. esc_attr($value) .'" '. $checked .' /> '. $label;
		$output .= '</div>';
		
	}
	
	return $output;
	
}



function usp_post_type_options() {
	
	global $usp_options;
	
	$usp_post_type = usp_post_type();
	
	$selected_option = isset($usp_options['usp_post_type']) ? $usp_options['usp_post_type'] : 'post';
	
	$select_options = '<select name="usp_options[usp_post_type]">';
	
	foreach($usp_post_type as $k => $v) {
		
		$selected = selected($selected_option === $k, true, false);
		
		$value = isset($v['value']) ? $v['value'] : null;
		$label = isset($v['label']) ? $v['label'] : null;
		
		$select_options .= '<option value="'. $value .'"'. $selected .'>'. $label .'</option>';
		
	}
	
	$select_options .= '</select>';
	
	return $select_options;
	
}



function usp_post_status_options() {
	
	global $usp_options;
	
	$approved = isset($usp_options['number-approved']) ? intval($usp_options['number-approved']) : -1;
	
	$output = '<select id="usp_options[number-approved]" name="usp_options[number-approved]">';
	
	foreach (range(-2, 20) as $v) {
		
		if     ($v === -2) $k = esc_html__('Draft', 'usp');	
		elseif ($v === -1) $k = esc_html__('Pending (default)', 'usp');
		elseif ($v === 0)  $k = esc_html__('Publish immediately', 'usp');
		elseif ($v === 1)  $k = esc_html__('Publish after 1 approved post', 'usp');
		else               $k = esc_html__('Publish after ', 'usp') . $v . esc_html__(' approved posts', 'usp');
		
		$selected = selected($v, $approved, false);
		
		$output .= '<option '. $selected .' value="'. $v .'">'. $k .'</option>';
		
	}
	
	$output .= '</select>';
	
	return $output;
	
}



function usp_post_category_options() {
	
	global $usp_options;
	
	$options = isset($usp_options['categories']) ? $usp_options['categories'] : array();
	
	$cats = get_categories(array('parent' => 0, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 0));
	
	if (empty($cats)) return;
	
	$usp_cats = array();
	
	$output  = '<div class="mm-item-desc">'. esc_html__('Select categories to include in the Category field:', 'usp') .'</div>';
	$output .= '<ul class="usp-category-options">';
	
	foreach ($cats as $c) {
		
		// parents
		
		$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c->term_id) .'" '. checked(true, in_array($c->term_id, $options), false) .'> ';
		$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c->term_id)) .'" title="Cat ID: '. esc_attr($c->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c->name) .'</a></label></li>';
		
		$usp_cats['c'][] = array('id' => esc_attr($c->term_id), 'c1' => array());
		$children = get_terms('category', array('parent' => esc_attr($c->term_id), 'hide_empty' => 0));
		
		if (!empty($children)) {
			
			$output .= '<li><ul>';
			
			foreach ($children as $c1) {

				// children
				
				$usp_cats['c'][]['c1'][] = array('id' => esc_attr($c1->term_id), 'c2' => array());
				$grandchildren = get_terms('category', array('parent' => esc_attr($c1->term_id), 'hide_empty' => 0));
				
				if (!empty($grandchildren)) {
					
					$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c1->term_id) .'" '. checked(true, in_array($c1->term_id, $options), false) .'> ';
					$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c1->term_id)) .'" title="Cat ID: '. esc_attr($c1->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c1->name) .'</a></label>';
					$output .= '<ul>';
					
					foreach ($grandchildren as $c2) {

						// grandchildren
						
						$usp_cats['c'][]['c1'][]['c2'][] = array('id' => esc_attr($c2->term_id), 'c3' => array());
						$great_grandchildren = get_terms('category', array('parent' => esc_attr($c2->term_id), 'hide_empty' => 0));
						
						if (!empty($great_grandchildren)) {
							
							$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c2->term_id) .'" '. checked(true, in_array($c2->term_id, $options), false) .'> ';
							$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c2->term_id)) .'" title="Cat ID: '. esc_attr($c2->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c2->name) .'</a></label>';
							$output .= '<ul>';
							
							foreach ($great_grandchildren as $c3) {
								
								// great enkelkinder
								
								$usp_cats['c'][]['c1'][]['c2'][]['c3'][] = array('id' => esc_attr($c3->term_id), 'c4' => array());
								$great_great_grandchildren = get_terms('category', array('parent' => esc_attr($c3->term_id), 'hide_empty' => 0));
								
								if (!empty($great_great_grandchildren)) {
									
									$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c3->term_id) .'" '. checked(true, in_array($c3->term_id, $options), false) .'> ';
									$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c3->term_id)) .'" title="Cat ID: '. esc_attr($c3->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c3->name) .'</a></label>';
									$output .= '<ul>';
									
									foreach ($great_great_grandchildren as $c4) {
										
										// great great grandchildren
										
										$usp_cats['c'][]['c1'][]['c2'][]['c3'][]['c4'][] = array('id' => esc_attr($c4->term_id));
										$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c4->term_id) .'" '. checked(true, in_array($c4->term_id, $options), false) .'> ';
										$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c4->term_id)) .'" title="Cat ID: '. esc_attr($c4->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c4->name) .'</a></label></li>';
										
									}
									$output .= '</ul></li>'; // great great grandchildren
									
								} else {
									$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c3->term_id) .'" '. checked(true, in_array($c3->term_id, $options), false) .'> ';
									$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c3->term_id)) .'" title="Cat ID: '. esc_attr($c3->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c3->name) .'</a></label></li>';
								}
							}
							$output .= '</ul></li>'; // great grandchildren
						} else {
							$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c2->term_id) .'" '. checked(true, in_array($c2->term_id, $options), false) .'> ';
							$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c2->term_id)) .'" title="Cat ID: '. esc_attr($c2->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c2->name) .'</a></label></li>';
						}
					}
					$output .= '</ul></li>'; // grandchildren
				} else {
					$output .= '<li><input type="checkbox" name="usp_options[categories][]" id="usp_options[categories][]" value="'. esc_attr($c1->term_id) .'" '. checked(true, in_array($c1->term_id, $options), false) .'> ';
					$output .= '<label for="usp_options[categories][]"><a href="'. esc_url(get_category_link($c1->term_id)) .'" title="Cat ID: '. esc_attr($c1->term_id) .'" target="_blank" rel="noopener noreferrer">'. esc_html($c1->name) .'</a></label></li>';
				}
			}
			$output .= '</ul></li>'; // children
		}
	}
	
	$output .= '</ul>'; // parents
	
	return $output;
	
}



function usp_post_author_options() {
	
	global $usp_options, $wpdb;
	
	$user_count = count_users();
	
	$user_total = isset($user_count['total_users']) ? intval($user_count['total_users']) : 1;
	
	$user_max = apply_filters('usp_max_users', 200);
	
	$limit = ($user_total > $user_max) ? $user_max : $user_total;
	
	if (is_multisite()) {
		
		$args = array('blog_id' => get_current_blog_id(), 'number'  => $limit);
		
		$user_query = new WP_User_Query($args);
		
		$users = $user_query->get_results();
		
	} else {
		
		$query = "SELECT ID, display_name FROM {$wpdb->users} LIMIT %d";
		
		$users = $wpdb->get_results($wpdb->prepare($query, $limit));
		
	}
	
	$output = '<select id="usp_options[author]" name="usp_options[author]">';
	
	foreach($users as $user) {
		
		$selected = isset($usp_options['author']) ? selected($usp_options['author'], $user->ID, false) : '';
		
		$output .= '<option '. $selected .'value="'. esc_attr($user->ID) .'">'. esc_html($user->display_name) .'</option>';
		
	}
	
	$output .= '</select>';
	
	return $output;
	
}



function usp_auto_display_options($item) {
	
	global $usp_options;
	
	$usp_image_display  = usp_image_display();
	$usp_email_display  = usp_email_display();
	$usp_name_display   = usp_name_display();
	$usp_url_display    = usp_url_display();
	$usp_custom_display = usp_custom_display();
	
	if ($item === 'images') {
		
		$array = $usp_image_display;
		$key = 'auto_display_images';
		
	} elseif ($item === 'email') {
		
		$array = $usp_email_display;
		$key = 'auto_display_email';
		
	} elseif ($item === 'name') {
		
		$array = $usp_name_display;
		$key = 'auto_display_name';
		
	} elseif ($item === 'url') {
		
		$array = $usp_url_display;
		$key = 'auto_display_url';
		
	} elseif ($item === 'custom') {
		
		$array = $usp_custom_display;
		$key = 'auto_display_custom';
		
	} elseif ($item === 'custom_2') {
		
		$array = $usp_custom_display;
		$key = 'auto_display_custom_2';
		
	}
	
	$radio_setting = isset($usp_options[$key]) ? $usp_options[$key] : '';
	
	$output = '';
	
	foreach ($array as $arr) {
		
		$label = isset($arr['label']) ? $arr['label'] : '';
		$value = isset($arr['value']) ? $arr['value'] : '';
		
		$checked = (!empty($radio_setting) && $radio_setting === $value) ? 'checked="checked"' : '';
		
		$output .= '<div class="mm-radio-inputs">';
		$output .= '<input type="radio" name="usp_options['. esc_attr($key) .']" value="'. esc_attr($value) .'" '. $checked .' /> '. esc_html($label);
		$output .= '</div>';
		
	}
	
	return $output;
	
}



function usp_form_field_recaptcha() {
	
	global $usp_options;
	
	$version = isset($usp_options['recaptcha_version']) ? $usp_options['recaptcha_version'] : 2;
	
	$output = '<select id="usp_options[recaptcha_version]" name="usp_options[recaptcha_version]">';
	
	foreach(usp_recaptcha_version() as $option) {
		
		$option_value = isset($option['value']) ? $option['value'] : '';
		$option_label = isset($option['label']) ? $option['label'] : '';
		
		$output .= '<option '. selected($option_value, $version, false) .' value="'. esc_attr($option_value) .'">'. esc_attr($option_label) .'</option>';
		
	}
	
	$output .= '</select>';
	
	return $output;
	
}



function usp_add_defaults() {
	
	$currentUser = wp_get_current_user();
	
	$admin_mail = get_bloginfo('admin_email');
	
	$tmp = get_option('usp_options');
	
	if ((isset($tmp['default_options']) && $tmp['default_options'] == '1') || (!is_array($tmp))) {
		
		$arr = array(
			'usp_version'           => USP_VERSION,
			'version_alert'         => 0,
			'default_options'       => 0,
			'author'                => $currentUser->ID,
			'categories'            => array(get_option('default_category')),
			'multiple-cats'         => false,
			'number-approved'       => -1,
			'redirect-url'          => '',
			'error-message'         => esc_html__('There was an error. Please check required fields and try again.', 'usp'),
			'min-images'            => 0,
			'max-images'            => 1,
			'min-image-height'      => 0,
			'min-image-width'       => 0,
			'max-image-height'      => 1500,
			'max-image-width'       => 1500,
			'usp_name'              => 'show',
			'usp_url'               => 'show',
			'usp_email'             => 'hide',
			'usp_title'             => 'show',
			'usp_tags'              => 'show',
			'usp_category'          => 'show',
			'usp_images'            => 'hide',
			'upload-message'        => esc_html__('Please select your image(s) to upload.', 'usp'),
			'usp_question'          => '1 + 1 =',
			'usp_response'          => '2',
			'usp_casing'            => 0,
			'usp_captcha'           => 'show',
			'usp_content'           => 'show',
			'success-message'       => esc_html__('Success! Thank you for your submission.', 'usp'),
			'usp_form_version'      => 'current',
			'usp_email_alerts'      => 1,
			'usp_email_html'        => 0,
			'usp_email_address'     => $admin_mail,
			'usp_email_from'        => $admin_mail,
			'usp_use_author'        => 0,
			'usp_use_url'           => 0,
			'usp_use_email'         => 0,
			'usp_use_cat'           => 0,
			'usp_use_cat_id'        => '',
			'usp_include_js'        => 1,
			'usp_display_url'       => '',
			'usp_form_content'      => '',
			'usp_existing_tags'     => 0,
			'usp_richtext_editor'   => 0,
			'usp_featured_images'   => 0,
			'usp_add_another'       => '',
			'disable_required'      => 0,
			'titles_unique'         => 0,
			'enable_shortcodes'     => 0,
			'disable_ip_tracking'   => 0,
			'email_alert_subject'   => '',
			'email_alert_message'   => '',
			'auto_display_images'   => 'disable',
			'auto_display_email'    => 'disable', 
			'auto_display_name'     => 'disable', 
			'auto_display_url'      => 'disable', 
			'auto_image_markup'     => '<a href="%%full%%"><img src="%%thumb%%" width="%%width%%" height="%%height%%" alt="%%title%%" style="display:inline-block;"></a> ',
			'auto_email_markup'     => '<p><a href="mailto:%%email%%">'. esc_html__('Email', 'usp') .'</a></p>',
			'auto_name_markup'      => '<p>%%author%%</p>',
			'auto_url_markup'       => '<p><a href="%%url%%">'. esc_html__('URL', 'usp') .'</a></p>',
			'logged_in_users'       => 0,
			'disable_author'        => 0,
			'recaptcha_public'      => '',
			'recaptcha_private'     => '',
			'recaptcha_version'     => 2,
			'usp_recaptcha'         => 'hide',
			'usp_post_type'         => 'post',
			'custom_field'          => 'hide',
			'custom_name'           => 'usp_custom_field',
			'custom_label'          => esc_html__('Custom Field 1', 'usp'),
			'custom_field_2'        => 'hide',
			'custom_name_2'         => 'usp_custom_field_2',
			'custom_label_2'        => esc_html__('Custom Field 2', 'usp'),
			'auto_display_custom'   => 'disable',
			'auto_custom_markup'    => '<p>%%custom_label%% : %%custom_name%% : %%custom_value%%</p>',
			'auto_display_custom_2' => 'disable',
			'auto_custom_markup_2'  => '<p>%%custom_label_2%% : %%custom_name_2%% : %%custom_value_2%%</p>',
			'custom_checkbox'       => false,
			'custom_checkbox_name'  => 'usp_custom_checkbox',
			'custom_checkbox_text'  => 'I agree the to the terms.',
			'custom_checkbox_err'   => 'Custom checkbox required',
		);
		
		update_option('usp_options', $arr);
		
	}
	
}



function usp_delete_plugin_options() {
	
	delete_option('usp_options');
	
}



function usp_update_category_option($option_name, $old_value, $value) { 
	
	usp_clear_cookies();
	
}
add_action('updated_option', 'usp_update_category_option', 10, 3);



function usp_validate_options($input) {
	
	global $usp_options;
	
	if (!isset($input['version_alert'])) $input['version_alert'] = null;
	$input['version_alert'] = ($input['version_alert'] == 1 ? 1 : 0);
	
	if (!isset($input['default_options'])) $input['default_options'] = null;
	$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);
	
	if (isset($input['categories'])) $input['categories'] = is_array($input['categories']) && !empty($input['categories']) ? array_unique($input['categories']) : array(get_option('default_category'));
	
	$input['number-approved']  = is_numeric($input['number-approved']) ? intval($input['number-approved']) : -1;
	
	$input['min-images']       = is_numeric($input['min-images']) ? intval($input['min-images']) : $input['max-images'];
	$input['max-images']       = (is_numeric($input['max-images']) && ($usp_options['min-images'] <= abs($input['max-images']))) ? intval($input['max-images']) : $usp_options['max-images'];
	
	$input['min-image-height'] = is_numeric($input['min-image-height']) ? intval($input['min-image-height']) : $usp_options['min-image-height'];
	$input['min-image-width']  = is_numeric($input['min-image-width'])  ? intval($input['min-image-width'])  : $usp_options['min-image-width'];
	
	$input['max-image-height'] = (is_numeric($input['max-image-height']) && ($usp_options['min-image-height'] <= $input['max-image-height'])) ? intval($input['max-image-height']) : $usp_options['max-image-height'];
	$input['max-image-width']  = (is_numeric($input['max-image-width'])  && ($usp_options['min-image-width']  <= $input['max-image-width']))  ? intval($input['max-image-width'])  : $usp_options['max-image-width'];
	
	$usp_form_version = usp_form_version();
	if (!isset($input['usp_form_version'])) $input['usp_form_version'] = null;
	if (!array_key_exists($input['usp_form_version'], $usp_form_version)) $input['usp_form_version'] = null;
	
	$usp_image_display = usp_image_display();
	if (!isset($input['auto_display_images'])) $input['auto_display_images'] = null;
	if (!array_key_exists($input['auto_display_images'], $usp_image_display)) $input['auto_display_images'] = null;
	
	$usp_email_display = usp_email_display();
	if (!isset($input['auto_display_email'])) $input['auto_display_email'] = null;
	if (!array_key_exists($input['auto_display_email'], $usp_email_display)) $input['auto_display_email'] = null;
	
	$usp_url_display = usp_url_display();
	if (!isset($input['auto_display_url'])) $input['auto_display_url'] = null;
	if (!array_key_exists($input['auto_display_url'], $usp_url_display)) $input['auto_display_url'] = null;
	
	$usp_custom_display = usp_custom_display();
	if (!isset($input['auto_display_custom'])) $input['auto_display_custom'] = null;
	if (!array_key_exists($input['auto_display_custom'], $usp_custom_display)) $input['auto_display_custom'] = null;
	
	if (!isset($input['auto_display_custom_2'])) $input['auto_display_custom_2'] = null;
	if (!array_key_exists($input['auto_display_custom_2'], $usp_custom_display)) $input['auto_display_custom_2'] = null;
	
	$usp_post_type = usp_post_type();
	if (!isset($input['usp_post_type'])) $input['usp_post_type'] = null;
	if (!array_key_exists($input['usp_post_type'], $usp_post_type)) $input['usp_post_type'] = null;
	
	$usp_recaptcha_version = usp_recaptcha_version();
	if (!isset($input['recaptcha_version'])) $input['recaptcha_version'] = null;
	if (!array_key_exists($input['recaptcha_version'], $usp_recaptcha_version)) $input['recaptcha_version'] = null;
	
	if (isset($input['author']))               $input['author']               = wp_filter_nohtml_kses($input['author']);               else $input['author']               = null;
	if (isset($input['usp_name']))             $input['usp_name']             = wp_filter_nohtml_kses($input['usp_name']);             else $input['usp_name']             = null;
	if (isset($input['usp_url']))              $input['usp_url']              = wp_filter_nohtml_kses($input['usp_url']);              else $input['usp_url']              = null; 
	if (isset($input['usp_email']))            $input['usp_email']            = wp_filter_nohtml_kses($input['usp_email']);            else $input['usp_email']            = null;
	if (isset($input['usp_title']))            $input['usp_title']            = wp_filter_nohtml_kses($input['usp_title']);            else $input['usp_title']            = null;
	if (isset($input['usp_tags']))             $input['usp_tags']             = wp_filter_nohtml_kses($input['usp_tags']);             else $input['usp_tags']             = null;
	if (isset($input['usp_category']))         $input['usp_category']         = wp_filter_nohtml_kses($input['usp_category']);         else $input['usp_category']         = null;
	if (isset($input['usp_images']))           $input['usp_images']           = wp_filter_nohtml_kses($input['usp_images']);           else $input['usp_images']           = null;
	if (isset($input['usp_question']))         $input['usp_question']         = wp_filter_nohtml_kses($input['usp_question']);         else $input['usp_question']         = null;
	if (isset($input['usp_captcha']))          $input['usp_captcha']          = wp_filter_nohtml_kses($input['usp_captcha']);          else $input['usp_captcha']          = null;
	if (isset($input['usp_content']))          $input['usp_content']          = wp_filter_nohtml_kses($input['usp_content']);          else $input['usp_content']          = null;
	if (isset($input['usp_email_address']))    $input['usp_email_address']    = wp_filter_nohtml_kses($input['usp_email_address']);    else $input['usp_email_address']    = null;
	if (isset($input['usp_email_from']))       $input['usp_email_from']       = wp_filter_nohtml_kses($input['usp_email_from']);       else $input['usp_email_from']       = null;
	if (isset($input['usp_use_cat_id']))       $input['usp_use_cat_id']       = wp_filter_nohtml_kses($input['usp_use_cat_id']);       else $input['usp_use_cat_id']       = null;
	if (isset($input['usp_display_url']))      $input['usp_display_url']      = wp_filter_nohtml_kses($input['usp_display_url']);      else $input['usp_display_url']      = null;
	if (isset($input['redirect-url']))         $input['redirect-url']         = wp_filter_nohtml_kses($input['redirect-url']);         else $input['redirect-url']         = null;
	if (isset($input['email_alert_subject']))  $input['email_alert_subject']  = wp_filter_nohtml_kses($input['email_alert_subject']);  else $input['email_alert_subject']  = null;
	if (isset($input['recaptcha_public']))     $input['recaptcha_public']     = wp_filter_nohtml_kses($input['recaptcha_public']);     else $input['recaptcha_public']     = null;
	if (isset($input['recaptcha_private']))    $input['recaptcha_private']    = wp_filter_nohtml_kses($input['recaptcha_private']);    else $input['recaptcha_private']    = null;
	if (isset($input['usp_recaptcha']))        $input['usp_recaptcha']        = wp_filter_nohtml_kses($input['usp_recaptcha']);        else $input['usp_recaptcha']        = null;
	if (isset($input['custom_field']))         $input['custom_field']         = wp_filter_nohtml_kses($input['custom_field']);         else $input['custom_field']         = null;
	if (isset($input['custom_name']))          $input['custom_name']          = wp_filter_nohtml_kses($input['custom_name']);          else $input['custom_name']          = null;
	if (isset($input['custom_label']))         $input['custom_label']         = wp_filter_nohtml_kses($input['custom_label']);         else $input['custom_label']         = null;
	if (isset($input['custom_field_2']))       $input['custom_field_2']       = wp_filter_nohtml_kses($input['custom_field_2']);       else $input['custom_field_2']       = null;
	if (isset($input['custom_name_2']))        $input['custom_name_2']        = wp_filter_nohtml_kses($input['custom_name_2']);        else $input['custom_name_2']        = null;
	if (isset($input['custom_label_2']))       $input['custom_label_2']       = wp_filter_nohtml_kses($input['custom_label_2']);       else $input['custom_label_2']       = null;
	if (isset($input['custom_checkbox_name'])) $input['custom_checkbox_name'] = wp_filter_nohtml_kses($input['custom_checkbox_name']); else $input['custom_checkbox_name'] = null;
	if (isset($input['custom_checkbox_err']))  $input['custom_checkbox_err']  = wp_filter_nohtml_kses($input['custom_checkbox_err']);  else $input['custom_checkbox_err']  = null;
	
	if (isset($input['usp_featured_image_default'])) $input['usp_featured_image_default'] = wp_filter_nohtml_kses($input['usp_featured_image_default']); else $input['usp_featured_image_default'] = null;
	
	// dealing with kses
	
	global $allowedposttags;
	
	$default_allowedposttags = $allowedposttags; 
	
	$allowed_atts = array(
		'align'      => array(),
		'class'      => array(),
		'type'       => array(),
		'id'         => array(),
		'dir'        => array(),
		'lang'       => array(),
		'style'      => array(),
		'xml:lang'   => array(),
		'src'        => array(),
		'alt'        => array(),
		'href'       => array(),
		'rel'        => array(),
		'rev'        => array(),
		'target'     => array(),
		'novalidate' => array(),
		'type'       => array(),
		'value'      => array(),
		'name'       => array(),
		'tabindex'   => array(),
		'action'     => array(),
		'method'     => array(),
		'for'        => array(),
		'width'      => array(),
		'height'     => array(),
		'data'       => array(),
		'data-rel'   => array(),
		'title'      => array(),
	);
	$allowedposttags['form']     = $allowed_atts;
	$allowedposttags['label']    = $allowed_atts;
	$allowedposttags['input']    = $allowed_atts;
	$allowedposttags['textarea'] = $allowed_atts;
	$allowedposttags['iframe']   = $allowed_atts;
	$allowedposttags['script']   = $allowed_atts;
	$allowedposttags['style']    = $allowed_atts;
	$allowedposttags['strong']   = $allowed_atts;
	$allowedposttags['small']    = $allowed_atts;
	$allowedposttags['table']    = $allowed_atts;
	$allowedposttags['span']     = $allowed_atts;
	$allowedposttags['abbr']     = $allowed_atts;
	$allowedposttags['code']     = $allowed_atts;
	$allowedposttags['pre']      = $allowed_atts;
	$allowedposttags['div']      = $allowed_atts;
	$allowedposttags['img']      = $allowed_atts;
	$allowedposttags['h1']       = $allowed_atts;
	$allowedposttags['h2']       = $allowed_atts;
	$allowedposttags['h3']       = $allowed_atts;
	$allowedposttags['h4']       = $allowed_atts;
	$allowedposttags['h5']       = $allowed_atts;
	$allowedposttags['h6']       = $allowed_atts;
	$allowedposttags['ol']       = $allowed_atts;
	$allowedposttags['ul']       = $allowed_atts;
	$allowedposttags['li']       = $allowed_atts;
	$allowedposttags['em']       = $allowed_atts;
	$allowedposttags['hr']       = $allowed_atts;
	$allowedposttags['br']       = $allowed_atts;
	$allowedposttags['tr']       = $allowed_atts;
	$allowedposttags['td']       = $allowed_atts;
	$allowedposttags['p']        = $allowed_atts;
	$allowedposttags['a']        = $allowed_atts;
	$allowedposttags['b']        = $allowed_atts;
	$allowedposttags['i']        = $allowed_atts;
	
	if (isset($input['usp_form_content']))     $input['usp_form_content']     = wp_kses_post($input['usp_form_content'],     $allowedposttags); else $input['usp_form_content']     = null;
	if (isset($input['error-message']))        $input['error-message']        = wp_kses_post($input['error-message'],        $allowedposttags); else $input['error-message']        = null;
	if (isset($input['upload-message']))       $input['upload-message']       = wp_kses_post($input['upload-message'],       $allowedposttags); else $input['upload-message']       = null;
	if (isset($input['success-message']))      $input['success-message']      = wp_kses_post($input['success-message'],      $allowedposttags); else $input['success-message']      = null;
	if (isset($input['usp_add_another']))      $input['usp_add_another']      = wp_kses_post($input['usp_add_another'],      $allowedposttags); else $input['usp_add_another']      = null;
	if (isset($input['email_alert_message']))  $input['email_alert_message']  = wp_kses_post($input['email_alert_message'],  $allowedposttags); else $input['email_alert_message']  = null;
	if (isset($input['auto_image_markup']))    $input['auto_image_markup']    = wp_kses_post($input['auto_image_markup'],    $allowedposttags); else $input['auto_image_markup']    = null;
	if (isset($input['auto_email_markup']))    $input['auto_email_markup']    = wp_kses_post($input['auto_email_markup'],    $allowedposttags); else $input['auto_email_markup']    = null;
	if (isset($input['auto_url_markup']))      $input['auto_url_markup']      = wp_kses_post($input['auto_url_markup'],      $allowedposttags); else $input['auto_url_markup']      = null;
	if (isset($input['auto_custom_markup']))   $input['auto_custom_markup']   = wp_kses_post($input['auto_custom_markup'],   $allowedposttags); else $input['auto_custom_markup']   = null;
	if (isset($input['custom_checkbox_text'])) $input['custom_checkbox_text'] = wp_kses_post($input['custom_checkbox_text'], $allowedposttags); else $input['custom_checkbox_text'] = null;
	
	$allowedposttags = $default_allowedposttags;
	
	if (!isset($input['usp_casing'])) $input['usp_casing'] = null;
	$input['usp_casing'] = ($input['usp_casing'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_email_alerts'])) $input['usp_email_alerts'] = null;
	$input['usp_email_alerts'] = ($input['usp_email_alerts'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_email_html'])) $input['usp_email_html'] = null;
	$input['usp_email_html'] = ($input['usp_email_html'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_use_author'])) $input['usp_use_author'] = null;
	$input['usp_use_author'] = ($input['usp_use_author'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_use_url'])) $input['usp_use_url'] = null;
	$input['usp_use_url'] = ($input['usp_use_url'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_use_email'])) $input['usp_use_email'] = null;
	$input['usp_use_email'] = ($input['usp_use_email'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_use_cat'])) $input['usp_use_cat'] = null;
	$input['usp_use_cat'] = ($input['usp_use_cat'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_include_js'])) $input['usp_include_js'] = null;
	$input['usp_include_js'] = ($input['usp_include_js'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_existing_tags'])) $input['usp_existing_tags'] = null;
	$input['usp_existing_tags'] = ($input['usp_existing_tags'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_richtext_editor'])) $input['usp_richtext_editor'] = null;
	$input['usp_richtext_editor'] = ($input['usp_richtext_editor'] == 1 ? 1 : 0);
	
	if (!isset($input['usp_featured_images'])) $input['usp_featured_images'] = null;
	$input['usp_featured_images'] = ($input['usp_featured_images'] == 1 ? 1 : 0);
	
	if (!isset($input['disable_required'])) $input['disable_required'] = null;
	$input['disable_required'] = ($input['disable_required'] == 1 ? 1 : 0);
	
	if (!isset($input['titles_unique'])) $input['titles_unique'] = null;
	$input['titles_unique'] = ($input['titles_unique'] == 1 ? 1 : 0);
	
	if (!isset($input['enable_shortcodes'])) $input['enable_shortcodes'] = null;
	$input['enable_shortcodes'] = ($input['enable_shortcodes'] == 1 ? 1 : 0);
	
	if (!isset($input['disable_ip_tracking'])) $input['disable_ip_tracking'] = null;
	$input['disable_ip_tracking'] = ($input['disable_ip_tracking'] == 1 ? 1 : 0);
	
	if (!isset($input['logged_in_users'])) $input['logged_in_users'] = null;
	$input['logged_in_users'] = ($input['logged_in_users'] == 1 ? 1 : 0);
	
	if (!isset($input['disable_author'])) $input['disable_author'] = null;
	$input['disable_author'] = ($input['disable_author'] == 1 ? 1 : 0);
	
	if (!isset($input['custom_checkbox'])) $input['custom_checkbox'] = null;
	$input['custom_checkbox'] = ($input['custom_checkbox'] == 1 ? 1 : 0);
	
	if (!isset($input['multiple-cats'])) $input['multiple-cats'] = null;
	$input['multiple-cats'] = ($input['multiple-cats'] == 1 ? 1 : 0);
	
	return apply_filters('usp_input_validate', $input);
	
}

//

function usp_admin_notice() {
	
	if (usp_get_current_screen_id() === 'settings_page_user-submitted-posts') {
		
		if (!usp_check_date_expired() && !usp_dismiss_notice_check()) {
			
			?>
			
			<div class="notice notice-success">
				<p>
					<strong><?php esc_html_e('Plugin Sale:', 'usp'); ?></strong> 
					<?php esc_html_e('Save 20% on any of our', 'usp'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/"><?php esc_html_e('Pro WordPress plugins', 'usp'); ?></a>. 
					<?php esc_html_e('Apply code', 'usp'); ?> <code>PLANET2023</code> <?php esc_html_e('at checkout. Sale ends 9/9/23.', 'usp'); ?> 
					<?php echo usp_dismiss_notice_link(); ?>
				</p>
			</div>
			
			<?php
			
		}
		
	}
	
}
add_action('admin_notices', 'usp_admin_notice');

function usp_dismiss_notice_activate() {
	
	delete_option('user-submitted-posts-dismiss-notice');
	
}

function usp_dismiss_notice_version() {
	
	$version_current = USP_VERSION;
	
	$version_previous = get_option('user-submitted-posts-dismiss-notice');
	
	$version_previous = ($version_previous) ? $version_previous : $version_current;
	
	if (version_compare($version_current, $version_previous, '>')) {
		
		delete_option('user-submitted-posts-dismiss-notice');
		
	}
	
}
add_action('admin_init', 'usp_dismiss_notice_version');

function usp_dismiss_notice_check() {
	
	$check = get_option('user-submitted-posts-dismiss-notice');
	
	return ($check) ? true : false;
	
}

function usp_dismiss_notice_save() {
	
	if (isset($_GET['dismiss-notice-verify']) && wp_verify_nonce($_GET['dismiss-notice-verify'], 'usp_dismiss_notice')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$result = update_option('user-submitted-posts-dismiss-notice', USP_VERSION, false);
		
		$result = $result ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=user-submitted-posts&dismiss-notice='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}
add_action('admin_init', 'usp_dismiss_notice_save');

function usp_dismiss_notice_link() {
	
	$nonce = wp_create_nonce('usp_dismiss_notice');
	
	$href  = add_query_arg(array('dismiss-notice-verify' => $nonce), admin_url('options-general.php?page=user-submitted-posts'));
	
	$label = esc_html__('Dismiss', 'usp');
	
	echo '<a class="usp-dismiss-notice" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function usp_check_date_expired() {
	
	$expires = apply_filters('usp_check_date_expired', '2023-09-09');
	
	return (new DateTime() > new DateTime($expires)) ? true : false;
	
}
