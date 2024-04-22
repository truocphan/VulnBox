<div class="ms_lms_instructors_carousel__item_courses">
	<?php
	echo intval( $instructor->course_quantity );
	echo ' ';
	echo esc_html( ( intval( $instructor->course_quantity ) > 1 ) ? __( 'Courses', 'masterstudy-lms-learning-management-system' ) : __( 'Course', 'masterstudy-lms-learning-management-system' ) );
	?>
</div>
