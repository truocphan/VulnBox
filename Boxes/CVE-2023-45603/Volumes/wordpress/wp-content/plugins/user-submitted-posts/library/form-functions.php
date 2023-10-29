<?php // User Submitted Posts - Form helper functions

if (!defined('ABSPATH')) die();

function usp_display_custom_checkbox() {
	
	global $usp_options;
	
	$enable   = (isset($usp_options['custom_checkbox'])  && !empty($usp_options['custom_checkbox']))  ? true  : false;
	$required = (isset($usp_options['disable_required']) && !empty($usp_options['disable_required'])) ? false : true;
	
	$name   = isset($usp_options['custom_checkbox_name']) ? $usp_options['custom_checkbox_name'] : null;
	$text   = isset($usp_options['custom_checkbox_text']) ? $usp_options['custom_checkbox_text'] : '';
	
	$output = '';
	
	if ($enable && $name) {
		
		$text = str_replace("{", "<", $text);
		$text = str_replace("}", ">", $text);
		
		$required_markup = $required ? ' data-required="true" required' : '';
		
		$output .= '<fieldset class="usp-checkbox">';
		$output .= '<input id="user-submitted-checkbox" name="'. esc_attr($name) .'" type="checkbox" value=""'. $required_markup .'> ';
		$output .= '<label for="user-submitted-checkbox">'. $text .'</label>';
		$output .= '</fieldset>';
		
	}
	
	return $output;
	
}

function usp_get_form_vars() {
	
	global $usp_options;
	
	$usp_current_user = wp_get_current_user();
	$usp_user_name    = $usp_current_user->user_login;
	$usp_user_email   = $usp_current_user->user_email;
	$usp_user_url     = $usp_current_user->user_url;
	
	if ($usp_options['disable_required']) {
		
		$usp_required = ''; 
		$usp_captcha  = '';
		
	} else {
		
		$usp_required = ' data-required="true" required';
		$usp_captcha  = ' user-submitted-captcha';
		
	}
	
	if (isset($usp_options['multiple-cats']) && $usp_options['multiple-cats']) {
		
		$multiple_cats  = ' multiple="multiple"';
		$category_class = ' class="usp-select usp-multiple"';
		
	} else {
		
		$multiple_cats  = '';
		$category_class = ' class="usp-select"';
		
	}
	
	$usp_display_name  = (is_user_logged_in() && $usp_options['usp_use_author']) ? false : true;
	$usp_display_email = (is_user_logged_in() && $usp_options['usp_use_email'])  ? false : true;
	$usp_display_url   = (is_user_logged_in() && $usp_options['usp_use_url'])    ? false : true;
	
	$usp_existing_tags = (isset($usp_options['usp_existing_tags']) && !empty($usp_options['usp_existing_tags'])) ? true : false;
	
	$usp_recaptcha_public  = (isset($usp_options['recaptcha_public'])  && !empty($usp_options['recaptcha_public']))  ? true : false;
	$usp_recaptcha_private = (isset($usp_options['recaptcha_private']) && !empty($usp_options['recaptcha_private'])) ? true : false;
	
	$usp_recaptcha_version = isset($usp_options['recaptcha_version']) ? $usp_options['recaptcha_version'] : 2;
	$usp_recaptcha_display = isset($usp_options['usp_recaptcha'])     ? $usp_options['usp_recaptcha']     : '';
	
	$usp_data_sitekey = isset($usp_options['recaptcha_public']) ? $usp_options['recaptcha_public'] : '';
	
	$usp_custom_name  = isset($usp_options['custom_name'])  ? $usp_options['custom_name']  : '';
	$usp_custom_label = isset($usp_options['custom_label']) ? $usp_options['custom_label'] : '';
	
	$usp_custom_name_2  = isset($usp_options['custom_name_2'])  ? $usp_options['custom_name_2']  : '';
	$usp_custom_label_2 = isset($usp_options['custom_label_2']) ? $usp_options['custom_label_2'] : '';
	
	$options = array(
					'usp_user_name'         => $usp_user_name,
					'usp_user_email'        => $usp_user_email,
					'usp_user_url'          => $usp_user_url,
					'usp_required'          => $usp_required,
					'usp_captcha'           => $usp_captcha,
					'multiple_cats'         => $multiple_cats,
					'category_class'        => $category_class,
					'usp_display_name'      => $usp_display_name,
					'usp_display_email'     => $usp_display_email,
					'usp_display_url'       => $usp_display_url,
					'usp_existing_tags'     => $usp_existing_tags,
					'usp_recaptcha_public'  => $usp_recaptcha_public,
					'usp_recaptcha_private' => $usp_recaptcha_private,
					'usp_recaptcha_version' => $usp_recaptcha_version,
					'usp_recaptcha_display' => $usp_recaptcha_display,
					'usp_data_sitekey'      => $usp_data_sitekey,
					'usp_custom_name'       => $usp_custom_name,
					'usp_custom_label'      => $usp_custom_label,
					'usp_custom_name_2'     => $usp_custom_name_2,
					'usp_custom_label_2'    => $usp_custom_label_2,
				);
	
	return $options;
	
}

function usp_get_tag_options() {
	
	$output = '';
	
	$args = array('hide_empty' => 0);
	
	$args = apply_filters('usp_get_tag_options_args', $args); // ref @ https://bit.ly/33Ad99z
	
	$tags = get_tags($args);
	
	foreach ($tags as $tag) {
		
		$name = isset($tag->name) ? $tag->name : '';
		$slug = isset($tag->slug) ? $tag->slug : '';
		
		$output .= '<option value="'. esc_attr($slug) .'">'. esc_html($name) .'</option>';
		
	}
	
	return $output;
	
}

function usp_get_cat_options() {
	
	global $usp_options;
	
	$output = '';
	
	$cats = usp_get_cats();
	
	foreach ($cats as $cat) {
		
		$category = get_category($cat['id']);
		
		if (!$category) continue;
		
		$cat_id    = isset($cat['id'])    ? $cat['id']    : null;
		$cat_level = isset($cat['level']) ? $cat['level'] : null;
		
		if (isset($usp_options['multiple-cats']) && $usp_options['multiple-cats']) {
			
			    if ($cat_level == 'parent')                 $class = 'usp-cat-parent';
			elseif ($cat_level == 'child')                  $class = 'usp-cat-child';
			elseif ($cat_level == 'grandchild')             $class = 'usp-cat-grand';
			elseif ($cat_level == 'great_grandchild')       $class = 'usp-cat-great';
			elseif ($cat_level == 'great_great_grandchild') $class = 'usp-cat-great-great';
			else                                            $class = 'usp-cat';
			
			$output .= '<option value="'. esc_attr($cat_id) .'" class="'. $class .'">'. esc_html(get_cat_name($cat_id)) .'</option>';
			
		} else {
			
			    if ($cat_level == 'parent')                 $indent = '';
			elseif ($cat_level == 'child')                  $indent = '&emsp;';
			elseif ($cat_level == 'grandchild')             $indent = '&emsp;&emsp;';
			elseif ($cat_level == 'great_grandchild')       $indent = '&emsp;&emsp;&emsp;';
			elseif ($cat_level == 'great_great_grandchild') $indent = '&emsp;&emsp;&emsp;&emsp;';
			
			$output .= '<option value="'. esc_attr($cat_id) .'">'. $indent . esc_html(get_cat_name($cat_id)) .'</option>';
			
		}
		
	}
	
	return $output;
	
}

function usp_get_cats() {
	
	global $usp_options;
	
	$usp_cats = usp_get_categories();
	
	$cats = isset($usp_options['categories']) ? $usp_options['categories'] : array();
	
	$cats_on = array();
	
	foreach ($usp_cats as $v0) {
		if (is_array($v0)) {
			foreach ($v0 as $v1) {
				if (is_array($v1)) {
					if (!empty($v1['id'])) {
						if (in_array($v1['id'], $cats)) $cats_on[] = array('id' => intval($v1['id']), 'level' => 'parent');
					} else {
						foreach ($v1['c1'] as $v2) {
							if (is_array($v2)) {
								if (!empty($v2['id'])) {
									if (in_array($v2['id'], $cats)) $cats_on[] = array('id' => intval($v2['id']), 'level' => 'child');
								} else {
									foreach ($v2['c2'] as $v3) {
										if (is_array($v3)) {
											if (!empty($v3['id'])) {
												if (in_array($v3['id'], $cats)) $cats_on[] = array('id' => intval($v3['id']), 'level' => 'grandchild');
											} else {
												foreach ($v3['c3'] as $v4) {
													if (is_array($v4)) {
														if (!empty($v4['id'])) {
															if (in_array($v4['id'], $cats)) $cats_on[] = array('id' => intval($v4['id']), 'level' => 'great_grandchild');
														} else {
															foreach ($v4['c4'] as $v5) {
																if (is_array($v5)) {
																	if (!empty($v5['id'])) {
																		if (in_array($v5['id'], $cats)) $cats_on[] = array('id' => intval($v5['id']), 'level' => 'great_great_grandchild');
																	}
																}
															}
														}	
													}
												}
											}		
										}
									}
								}	
							}
						}
					}
				}
			}
		}
	}
	
	return $cats_on;
	
}

function usp_get_categories() {
	
	$cats = get_categories(array('parent' => 0, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 0));
	
	$usp_cats = array();
	
	if (!empty($cats)) {
		foreach ($cats as $c) {
			// parents
			$usp_cats['c'][] = array('id' => $c->term_id, 'c1' => array());
			$children = get_terms('category', array('parent' => $c->term_id, 'hide_empty' => 0));
			if (!empty($children)) {
				foreach ($children as $c1) {
					// children
					$usp_cats['c'][]['c1'][] = array('id' => $c1->term_id, 'c2' => array());
					$grandchildren = get_terms('category', array('parent' => $c1->term_id, 'hide_empty' => 0));
					if (!empty($grandchildren)) {
						foreach ($grandchildren as $c2) {
							// grandchildren
							$usp_cats['c'][]['c1'][]['c2'][] = array('id' => $c2->term_id, 'c3' => array());
							$great_grandchildren = get_terms('category', array('parent' => $c2->term_id, 'hide_empty' => 0));
							if (!empty($great_grandchildren)) {
								foreach ($great_grandchildren as $c3) {
									// great enkelkinder
									$usp_cats['c'][]['c1'][]['c2'][]['c3'][] = array('id' => $c3->term_id, 'c4' => array());
									$great_great_grandchildren = get_terms('category', array('parent' => $c3->term_id, 'hide_empty' => 0));
									if (!empty($great_great_grandchildren)) {
										foreach ($great_great_grandchildren as $c4) {
											// great great grandchildren
											$usp_cats['c'][]['c1'][]['c2'][]['c3'][]['c4'][] = array('id' => $c4->term_id);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	return $usp_cats;
	
}