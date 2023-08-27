<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

$content = '';

$content = apply_filters( 'jupiterx-conditions-manager-template', $content );

add_filter( 'the_content', function() use ( $content ) {
	return $content;
}, 10 );

the_content();

get_footer();
