<?php // User Submitted Posts - Template Tags

if (!defined('ABSPATH')) die();



/* 
	Returns a boolean value indicating whether the specified post is a public submission
	Usage: <?php if (function_exists('usp_is_public_submission')) usp_is_public_submission(); ?>
*/
function usp_is_public_submission($postId = false) {
	
	global $post;
	
	if (false === $postId) {
		
		if ($post) $postId = $post->ID;
		
	}
	
	if (get_post_meta($postId, 'is_submission', true) == true) {
		
		return true;
		
	}
	
	return false;
	
}



/* 
	Returns an array of URLs for the specified post image
	Usage: <?php $images = usp_get_post_images(); foreach ($images as $image) { echo $image; } ?>
*/
function usp_get_post_images($postId = false) {
	
	global $post;
	
	if (false === $postId) {
		
		if ($post) $postId = $post->ID;
		
	}
	
	if (usp_is_public_submission($postId)) {
		
		return get_post_meta($postId, 'user_submit_image');
		
	}
	
	return array();
	
}



/*
	Prints the URLs for all post attachments.
	Usage:  <?php if (function_exists('usp_post_attachments')) usp_post_attachments(); ?>
	Syntax: <?php if (function_exists('usp_post_attachments')) usp_post_attachments($size, $beforeUrl, $afterUrl, $numberImages, $postId); ?>
	Parameters:
		$size         = image size as thumbnail, medium, large or full -> default = full
		$beforeUrl    = text/markup displayed before the image URL     -> default = <img src="
		$afterUrl     = text/markup displayed after the image URL      -> default = " />
		$numberImages = the number of images to display for each post  -> default = false (display all)
		$postId       = an optional post ID to use                     -> default = uses global post
*/
function usp_post_attachments($size = 'full', $beforeUrl = '<img src="', $afterUrl = '" />', $numberImages = false, $postId = false) {
	
	global $post;
	
	if (false === $postId) {
		
		if ($post) $postId = $post->ID;
		
	}
	
	if (false === $numberImages || !is_numeric($numberImages)) {
		
		$numberImages = 99;
		
	}
	
	$args = array(
		'post_type'   => 'attachment', 
		'post_parent' => $postId, 
		'post_status' => 'inherit', 
		'numberposts' => $numberImages
	);
	
	$attachments = get_posts($args);
	
	foreach ($attachments as $attachment) {
		
		$info = wp_get_attachment_image_src($attachment->ID, $size);

		echo $beforeUrl . $info[0] . $afterUrl;
		
	}
	
}



/*
	For public-submitted posts, this tag displays the author's name as a link (if URL provided) or plain text (if URL not provided)
	For normal posts, this tag displays the author's name as a link to their author's post page
	Usage: <?php if (function_exists('usp_author_link')) usp_author_link(); ?>
*/
function usp_author_link() {
	
	global $post;

	$isSubmission     = get_post_meta($post->ID, 'is_submission', true);
	$submissionAuthor = get_post_meta($post->ID, 'user_submit_name', true);
	$submissionLink   = get_post_meta($post->ID, 'user_submit_url', true);

	if ($isSubmission && !empty($submissionAuthor)) {
		
		if (empty($submissionLink)) {
			
			echo '<span class="usp-author-link">' . $submissionAuthor . '</span>';
			
		} else {
			
			echo '<span class="usp-author-link"><a href="' . $submissionLink . '">' . $submissionAuthor . '</a></span>';
			
		}
		
	} else {
		
		the_author_posts_link();
		
	}
	
}



/*
	Function: usp_get_images()
	Returns an array of image URLs, wrapped in optional HTML
	
	Syntax: <?php if (function_exists('usp_get_images')) $images = usp_get_images($size, $format, $target, $class, $number, $post_id); ?>
	Usage:  <?php if (function_exists('usp_get_images')) $images = usp_get_images(); foreach ($images as $image) echo $image; ?>
	
	Parameters:
		$size    = image size as thumbnail, medium, large or full -> default = thumbnail
		$format  = whether to make the image a linked image       -> default = image (can use image or image_link)
		$target  = whether to open linked image in new tab        -> default = blank (can use blank or self)
		$class   = optional custom class name(s)                  -> default = none
		$number  = the number of images to display for each post  -> default = 100
		$post_id = an optional post ID to use                     -> default = false (uses global/current post)
*/
if (!function_exists('usp_get_images')) :

function usp_get_images($size = false, $format = false, $target = false, $class = false, $number = false, $post_id = false) {
	
	global $post;
	
	$post_id = ($post_id && is_numeric($post_id)) ? $post_id : $post->ID;
	
	$number  = (is_numeric($number)) ? intval($number) : intval(apply_filters('usp_image_attachments', 100));
	
	$class   = ($class) ? ' '. sanitize_html_class($class) : '';
	
	$target  = ($target === 'blank') ? ' target="_blank" rel="noopener noreferrer"' : '';
	
	$format  = ($format === 'image' || $format === 'image_link') ? $format : 'image';
	
	$size    = ($size === 'thumbnail' || $size === 'medium' || $size === 'large' || $size === 'full') ? $size : 'thumbnail';
	
	$args = array(
			'post_status'    => 'publish', 
			'post_type'      => 'attachment', 
			'post_parent'    => $post_id, 
			'post_status'    => 'inherit', 
			'posts_per_page' => $number,
			'fields'         => 'ids'
	);
	
	$args = apply_filters('usp_image_attachments_args', $args);
	
	$image_ids = get_posts($args);
	
	$urls = array(); 
	
	$i = 1;
	
	foreach ($image_ids as $id) {
		
		$url = wp_get_attachment_image_src($id, $size);
		
		$original = wp_get_attachment_image_src($id, 'full');
		
		if ($url && $original) {
			
			if ($format === 'image' && isset($url[0])) {
				
				$urls[] = '<img src="'. sanitize_url($url[0]) .'" class="usp-gallery-image'. $class .'" alt="">';
				
			} elseif ($format === 'image_link' && isset($url[0]) && isset($original[0])) {
				
				$linked_image = '<a href="'. sanitize_url($original[0]) .'" class="usp-gallery-image-link'. $class .'"'. $target .'>';
				
				$linked_image .= '<img src="'. sanitize_url($url[0]) .'" class="usp-gallery-image" alt=""></a>';
				
				$urls[] = $linked_image;
				
			}
			
			if ($i == intval($number)) break;
			
			$i++;
			
		}
		
	}
	
	return $urls;
	
}

endif;