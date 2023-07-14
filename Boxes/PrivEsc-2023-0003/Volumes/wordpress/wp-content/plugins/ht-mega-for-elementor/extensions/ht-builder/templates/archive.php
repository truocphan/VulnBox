<?php
/*
* Archive / Blog Full Width
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
do_action( 'elementor/page_templates/header-footer/before_content' ); ?>

    <?php do_action( 'htmegabuilder_blog_content' ); ?>

<?php 

/**
 * After Header-Footer page template content.
 *
 * Fires after the content of Elementor Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'elementor/page_templates/header-footer/after_content' );

get_footer();

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
