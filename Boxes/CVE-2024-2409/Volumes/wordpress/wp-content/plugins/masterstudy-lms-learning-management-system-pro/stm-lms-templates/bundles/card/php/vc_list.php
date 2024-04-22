<?php
/**
 *
 * @var $columns
 * @var $title
 * @var $posts_per_page
 */
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
STM_LMS_Templates::show_lms_template( 'shortcodes/stm_lms_course_bundles', $atts );
