<?php
/**
 * Instructor review box.
 *
 * @var integer $assignment_id     - assignemnt ID.
 * @var string  $assignment_status - status of current assignment.
 * @var array   $assignment        - assignments arguments.
 *
 * @package masterstudy
 */

?>
<div class="masterstudy-user-assignment__box-inner masterstudy-user-assignment__box-column">
	<?php
	STM_LMS_Templates::show_lms_template(
		'components/radio-buttons',
		array(
			'name'  => 'status',
			'items' => array(
				array(
					'value'   => 'passed',
					'label'   => esc_html__( 'Passed', 'masterstudy-lms-learning-management-system-pro' ),
					'checked' => 'passed' === $assignment_status,
					'style'   => 'success',
				),
				array(
					'value'   => 'not_passed',
					'label'   => esc_html__( 'Failed', 'masterstudy-lms-learning-management-system-pro' ),
					'checked' => 'not_passed' === $assignment_status,
					'style'   => 'danger',
				),
			),
		)
	);

	STM_LMS_Templates::show_lms_template(
		'components/wp-editor',
		array(
			'id'        => 'editor_comment',
			'dark_mode' => false,
			'content'   => get_post_meta( $assignment_id, 'editor_comment', true ),
			'settings'  => array(
				'quicktags'     => false,
				'media_buttons' => false,
				'textarea_rows' => 13,
			),
		)
	);
	STM_LMS_Templates::show_lms_template(
		'components/attachment-media',
		array(
			'assignment_id'     => $assignment_id,
			'instructor_review' => true,
			'dark_mode'         => false,
		)
	);
	?>
	<div class="masterstudy-user-assignment__submit">
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/button',
			array(
				'id'    => 'masterstudy-review-submit',
				'title' => esc_html__( 'Submit review', 'masterstudy-lms-learning-management-system-pro' ),
				'link'  => '#',
				'style' => 'primary',
				'size'  => 'sm',
			)
		);
		?>
	</div>
</div>
