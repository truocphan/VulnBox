<?php
function stm_lms_courses_grid_display( $atts ) {
	if ( empty( $atts ) ) {
		$atts = array();
	}

	ob_start();
	?>

	<div class="stm_lms_courses_grid stm_lms_courses">
		<?php
		STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $atts ) );
		if ( ! empty( $atts['load_more'] ) ) {
			STM_LMS_Templates::show_lms_template( 'courses/load_more', array( 'args' => $atts ) );
		}
		?>
	</div>

	<?php
	return ob_get_clean();

}

add_shortcode( 'stm_lms_courses_grid_display', 'stm_lms_courses_grid_display' );
