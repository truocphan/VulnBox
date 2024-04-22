<?php
/**
 * @var $title
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
STM_LMS_Templates::show_lms_template( 'shortcodes/google_classroom', $atts );
