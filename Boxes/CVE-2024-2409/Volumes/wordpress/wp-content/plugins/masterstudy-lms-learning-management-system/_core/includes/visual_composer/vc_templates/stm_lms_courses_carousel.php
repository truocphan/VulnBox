<?php
$atts         = vc_map_get_attributes( $this->getShortcode(), $atts );
$uniq         = stm_lms_create_unique_id( $atts );
$atts['uniq'] = $uniq;
STM_LMS_Templates::show_lms_template( 'shortcodes/stm_lms_courses_carousel', $atts );
