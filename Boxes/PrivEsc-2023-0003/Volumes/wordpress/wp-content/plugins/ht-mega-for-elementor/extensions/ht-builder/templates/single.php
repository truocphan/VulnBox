<?php
/*
* Single Blog Full Width
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
/**
 * Before Header-Footer page template content.
 *
 * Fires before the content of Elementor Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'elementor/page_templates/header-footer/before_content' );

    while ( have_posts() ) :
        the_post();
        if( !\Elementor\Plugin::$instance->preview->is_preview_mode() ){
            do_action( 'htmegabuilder_single_blog_content' );
        }else{
            the_content();
        }
    endwhile; // end of the loop. 

/**
 * After Header-Footer page template content.
 *
 * Fires after the content of Elementor Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'elementor/page_templates/header-footer/after_content' );

get_footer();
