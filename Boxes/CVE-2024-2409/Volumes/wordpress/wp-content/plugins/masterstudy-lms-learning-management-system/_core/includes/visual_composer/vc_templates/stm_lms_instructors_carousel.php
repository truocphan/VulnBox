<?php
$atts         = vc_map_get_attributes( $this->getShortcode(), $atts );
$uniq         = stm_lms_create_unique_id( $atts );
$atts['uniq'] = ! empty( $uniq ) ? $uniq : 0;

STM_LMS_Templates::show_lms_template( 'shortcodes/stm_lms_instructors_carousel', $atts );
