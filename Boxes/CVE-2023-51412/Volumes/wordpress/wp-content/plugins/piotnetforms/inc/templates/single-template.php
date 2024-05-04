<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

if( have_posts() ){
	while ( have_posts() ) : the_post();
		echo '<div class="piotnetforms-container">';
			the_content();
		echo '</div>';
	endwhile;
}

get_footer();

?>
