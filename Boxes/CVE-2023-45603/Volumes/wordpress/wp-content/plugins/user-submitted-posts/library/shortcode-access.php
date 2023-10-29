<?php // User Submitted Posts - Access Control

/* 
	Shortcode: require login based on capability
	Syntax: [usp_access cap="read" deny=""][/usp_access]
	Can use {tag} to output <tag>
	See @ https://codex.wordpress.org/Roles_and_Capabilities#Capabilities
*/
if (!function_exists('usp_access')) :
function usp_access($attr, $content = null) {
	extract(shortcode_atts(array(
		'cap'  => 'read',
		'deny' => '',
	), $attr));
	
	$deny = str_replace("{", "<", $deny);
	$deny = str_replace("}", ">", $deny);
	
	$deny    = htmlspecialchars($deny, ENT_QUOTES);
	$content = htmlspecialchars($content, ENT_QUOTES);
	
	$caps = array_map('trim', explode(',', $cap));
	
	foreach ($caps as $c) {
		if (current_user_can($c) && !is_null($content) && !is_feed()) return do_shortcode($content);
	}
	
	return $deny;
}
add_shortcode('usp_access', 'usp_access');
endif;



/* 
	Shortcode: show content to visitors
	Syntax: [usp_visitor deny=""][/usp_visitor]
	Can use {tag} to output <tag>
*/
if (!function_exists('usp_visitor')) : 
function usp_visitor($attr, $content = null) {
	extract(shortcode_atts(array(
		'deny' => '',
	), $attr));
	
	$deny = str_replace("{", "<", $deny);
	$deny = str_replace("}", ">", $deny);
	
	$deny    = htmlspecialchars($deny, ENT_QUOTES);
	$content = htmlspecialchars($content, ENT_QUOTES);
	
	if ((!is_user_logged_in() && !is_null($content)) || is_feed()) return do_shortcode($content);
	
	return $deny;
}
add_shortcode('usp_visitor', 'usp_visitor');
endif;



/* 
	Shortcode: show content to members
	Syntax: [usp_member deny=""][/usp_member]
	Can use {tag} to output <tag>
*/
if (!function_exists('usp_member')) :
function usp_member($attr, $content = null) {
	extract(shortcode_atts(array(
		'deny' => '',
	), $attr));
	
	$deny = str_replace("{", "<", $deny);
	$deny = str_replace("}", ">", $deny);
	
	$deny    = htmlspecialchars($deny, ENT_QUOTES);
	$content = htmlspecialchars($content, ENT_QUOTES);
	
	if (is_user_logged_in() && !is_null($content) && !is_feed()) return do_shortcode($content);
	
	return $deny;
}
add_shortcode('usp_member', 'usp_member');
endif;



/*
	Shortcode Empty Paragraph Fix
*/
if (!function_exists('usp_shortcode_empty_p_fix')) :
function usp_shortcode_empty_p_fix($content) {
    $array = array(
        '<p>['    => '[',
        ']</p>'   => ']',
        ']<br />' => ']',
        ']<br>'   => ']'
    );
    $content = strtr($content, $array);
    return $content;
}
add_filter('the_content', 'usp_shortcode_empty_p_fix');
endif;
