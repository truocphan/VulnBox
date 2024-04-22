<?php
$data = array(
	'course_students'         => array(
		'label' => esc_html__( 'All time course students', 'masterstudy-lms-learning-management-system-pro' ),
		'affix' => '',
	),
	'course_average_progress' => array(
		'label' => esc_html__( 'Course average progress', 'masterstudy-lms-learning-management-system-pro' ),
		'affix' => '%',
	),
	'course_quizzes_procents' => array(
		'label' => esc_html__( 'Course passed quizzes', 'masterstudy-lms-learning-management-system-pro' ),
		'affix' => '%',
	),
	'course_lessons_procents' => array(
		'label' => esc_html__( 'Course passed lessons', 'masterstudy-lms-learning-management-system-pro' ),
		'affix' => '%',
	),
	'subscriptions'           => array(
		'label' => esc_html__( 'Course enrolled by subscription', 'masterstudy-lms-learning-management-system-pro' ),
		'affix' => '',
	),
);

if ( class_exists( 'STM_LMS_Assignments' ) ) {
	$data['course_assignments_procents'] = array(
		'label' => esc_html__( 'Course passed assignments', 'masterstudy-lms-learning-management-system-pro' ),
		'affix' => '%',
	);
}
?>

<div class="stm_lms_gradebook__course__details" v-if="course.opened" v-bind:class="{'loading' : course.loading}">

	<table class="table table-responsive" v-if="course.data">
		<tbody>
		<?php
		$data = array_chunk( $data, 3, true );

		foreach ( $data as $data_chunk ) :
			?>

			<tr>

				<?php
				foreach ( $data_chunk as $course_data_key => $course_data ) :

					$data_text = "{{course.data['{$course_data_key}']}}{$course_data['affix']}";
					?>
					<td>
						<span class="heading_font"><?php echo esc_html( $course_data['label'] ); ?></span>:
						<strong><?php echo wp_kses_post( $data_text ); ?></strong>
					</td>
				<?php endforeach; ?>

			</tr>

		<?php endforeach; ?>
		</tbody>
	</table>

	<h5 v-if="!course.data && !course.loading"><?php esc_html_e( 'Nothing Found', 'masterstudy-lms-learning-management-system-pro' ); ?></h5>

</div>
