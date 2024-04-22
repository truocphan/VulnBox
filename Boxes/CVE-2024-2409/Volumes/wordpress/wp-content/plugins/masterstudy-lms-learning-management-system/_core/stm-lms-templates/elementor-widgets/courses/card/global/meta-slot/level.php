<i class="stmlms-levels"></i>
<?php
$levels = STM_LMS_Helpers::get_course_levels();

if ( ! empty( $course['level'] ) && isset( $levels[ $course['level'] ] ) ) {
	echo esc_html( $levels[ $course['level'] ] );
} else {
	echo esc_html( __( 'No Level', 'masterstudy-lms-learning-management-system' ) );
}
?>
