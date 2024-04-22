<i class="stmlms-cats"></i>
<span>
	<?php
	echo esc_html( $course['lectures']['lessons'] );
	echo ' ';
	echo esc_html( ( $course['lectures']['lessons'] > 1 || 0 === $course['lectures']['lessons'] ) ? __( 'Lectures', 'masterstudy-lms-learning-management-system' ) : __( 'Lecture', 'masterstudy-lms-learning-management-system' ) );
	?>
</span>
