<?php
/**
 * Template Name: Full Width
 * Template Post Type: post, portfolio, page, elementor_library
 *
 * This core file should only be overwritten via your child theme.
 *
 * We strongly recommend to read the Jupiter documentation to find out more about
 * how to customize the Jupiter theme.
 *
 * @author JupiterX
 * @link   https://artbees.net
 * @package JupiterX\Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

jupiterx_remove_action( 'jupiterx_content_template' );

jupiterx_add_smart_action( 'jupiterx_load_document', 'jupiterx_fullwidth_template_content', 5 );

jupiterx_load_document();
