<?php
/**
 * Handles Elementor Post Navigation widget.
 *
 * @package JupiterX\Framework\API\Elementor
 *
 * @since 1.2.0
 */

add_action( 'elementor/element/post-navigation/section_post_navigation_content/before_section_start', 'jupiterx_elementor_modify_post_navigation' );
add_action( 'elementor/element/jet-blog-posts-navigation/section_general/before_section_start', 'jupiterx_elementor_modify_post_navigation' );
/**
 * Remove Jupiter X post navigation overrides.
 *
 * @since 1.2.0
 */
function jupiterx_elementor_modify_post_navigation() {
	jupiterx_remove_action( 'jupiterx_previous_post_link' );
	jupiterx_remove_action( 'jupiterx_next_post_link' );
}
