<?php
/**
 * @var $assignment_id
 */

use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentStudentRepository;

$assignment        = STM_LMS_User_Assignment::get_assignment( $assignment_id );
$attempt           = get_post_meta( $assignment_id, 'try_num', true );
$parent_id         = get_post_meta( $assignment_id, 'assignment_id', true );
$assignment_status = $assignment['status'] ?? '';
$badge_class       = '';

if ( 'not_passed' === $assignment_status ) {
	$badge_class = ' masterstudy-user-assignment__badge_danger';
}

if ( 'passed' === $assignment_status ) {
	$badge_class = ' masterstudy-user-assignment__badge_success';
}
?>

<div class="masterstudy-user-assignment__box-outter">
	<div class="masterstudy-user-assignment__box-inner masterstudy-user-assignment__box-header">
		<div class="masterstudy-user-assignment__box-row">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/back-link',
				array(
					'id'  => 'masterstudy-course-player-back',
					'url' => STM_LMS_User::user_page_url( get_current_user_id() ) . "assignments/{$parent_id}",
				)
			);
			?>
			<div class="masterstudy-user-assignment__box-column masterstudy-user-assignment__box--no-gap">
				<div class="masterstudy-user-assignment__page-title"><?php echo esc_html__( 'Student Assignment', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
				<h2 class="masterstudy-user-assignment__title"><?php echo esc_html( $assignment['assignment_title'] ?? '' ); ?></h2>
			</div>
		</div>
		<div class="masterstudy-user-assignment__box-row">
			<span class="masterstudy-user-assignment__badge">
				<?php echo esc_html__( 'Attempt', 'masterstudy-lms-learning-management-system-pro' ); ?>:  
				<?php echo esc_html( $attempt ); ?>
			</span>
			<span class="masterstudy-user-assignment__badge<?php echo esc_attr( $badge_class ); ?>">
				<?php echo esc_html( AssignmentStudentRepository::get_status( $assignment_status, false ) ); ?>
			</span>
		</div>
	</div>
	<div class="masterstudy-user-assignment__box-inner masterstudy-user-assignment__box-column">
		<h2 class="masterstudy-user-assignment__title">
			<?php echo esc_html__( 'Answered by student', 'masterstudy-lms-learning-management-system-pro' ); ?>: 
			<?php echo esc_html( AssignmentStudentRepository::get_display_name( $assignment_id ) ); ?>
		</h2>
		<div class="masterstudy-user-assignment__content">
			<?php echo wp_kses_post( $assignment['content'] ); ?>
		</div>
		<div class="masterstudy-user-assignment__attachments">
			<?php
			$attachments = STM_LMS_Assignments::get_draft_attachments( $assignment_id, 'student_attachments' );
			if ( ! empty( $attachments ) ) {
				STM_LMS_Templates::show_lms_template(
					'components/file-attachment',
					array(
						'attachments' => $attachments,
						'dark_mode'   => false,
					)
				);
			}
			?>
		</div>
	</div>
	<?php
	STM_LMS_Templates::show_lms_template(
		'account/private/instructor_parts/user_assignments/review',
		compact( 'assignment_id', 'assignment_status', 'assignment' )
	);
	?>
</div>
