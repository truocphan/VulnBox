<?php
/**
 * @var $assignment_id
 */

use MasterStudy\Lms\Pro\addons\assignments\Assignments;
use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentStudentRepository;
use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentTeacherRepository;

$pending           = AssignmentTeacherRepository::user_assignments_count( $assignment_id, '', true );
$pending_transient = Assignments::pending_viewed_transient_name( $assignment_id );
set_transient( $pending_transient, $pending, 7 * 24 * 60 * 60 );

?>

<div class="info">

	<div class="total">
		<i class="fa fa-tasks"></i>
		<span>
		<?php
		printf(
			/* translators: %s Assignments Count */
			esc_html__( 'Total: %s', 'masterstudy-lms-learning-management-system-pro' ),
			esc_html( AssignmentTeacherRepository::user_assignments_count( $assignment_id ) )
		);
		?>
			</span>
	</div>

	<div class="unpassed">
		<i class="far fa-times-circle"></i>
		<span>
		<?php
		printf(
			/* translators: %s Assignments Count */
			esc_html__( 'Non passed: %s', 'masterstudy-lms-learning-management-system-pro' ),
			esc_html( AssignmentTeacherRepository::user_assignments_count( $assignment_id, AssignmentStudentRepository::STATUS_NOT_PASSED ) )
		);
		?>
			</span>
	</div>

	<div class="passed">
		<i class="far fa-check-circle"></i>
		<span>
		<?php
		printf(
			/* translators: %s Assignments Count */
			esc_html__( 'Passed: %s', 'masterstudy-lms-learning-management-system-pro' ),
			esc_html( AssignmentTeacherRepository::user_assignments_count( $assignment_id, AssignmentStudentRepository::STATUS_PASSED ) )
		);
		?>
			</span>
	</div>

	<div class="pending">
		<i class="far fa-clock"></i>
		<span>
		<?php
		printf(
			/* translators: %s Assignments Count */
			esc_html__( 'Pending: %s', 'masterstudy-lms-learning-management-system-pro' ),
			esc_html( $pending )
		);
		?>
		</span>
	</div>

</div>
