<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
STM_LMS_Templates::show_lms_template( 'shortcodes/stm_lms_recent_courses', $atts );
