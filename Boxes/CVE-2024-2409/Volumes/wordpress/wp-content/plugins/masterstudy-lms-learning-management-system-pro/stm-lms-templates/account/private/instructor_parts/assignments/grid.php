<?php

use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentStudentRepository;
use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentTeacherRepository;
use MasterStudy\Lms\Repositories\CurriculumRepository;

stm_lms_register_style( 'assignments/instructor-assignments-table' );
stm_lms_pro_register_script( 'assignments/sortable-table' );

$theads       = array(
	'assignment' => array(
		'title'    => __( 'Assignment', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'start',
		'grow'     => 'masterstudy-tcell_is-grow-md',
		'hidden'   => false,
	),
	'course'     => array(
		'title'    => __( 'In course', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'course',
		'hidden'   => true,
	),
	'total'      => array(
		'title'    => __( 'Total', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'total',
		'hidden'   => false,
	),
	'passed'     => array(
		'title'    => __( 'Passed', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'passed',
		'hidden'   => false,
	),
	'not_passed' => array(
		'title'    => __( 'Non passed', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'not_passed',
		'hidden'   => false,
	),
	'pending'    => array(
		'title'    => __( 'Pending', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'pending',
		'hidden'   => false,
	),
);
$current_page = get_query_var( 'page' ) > 0 ? get_query_var( 'page' ) : 1;
$assignments  = AssignmentTeacherRepository::get_assignments();
?>
<div class="masterstudy-table">
	<div class="masterstudy-table__toolbar">

		<div class="masterstudy-table__toolbar-header">
			<h3 class="masterstudy-table__title">
				<?php echo esc_html__( 'Assignments', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</h3>
		</div>

		<div class="masterstudy-table__filters">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/search',
				array(
					'select_name'  => 's',
					'is_queryable' => true,
					'placeholder'  => esc_html__( 'Search by assignment', 'masterstudy-lms-learning-management-system-pro' ),
				)
			);
			STM_LMS_Templates::show_lms_template(
				'components/select',
				array(
					'select_name'  => 'status',
					'placeholder'  => esc_html__( 'Status: all', 'masterstudy-lms-learning-management-system-pro' ),
					'select_width' => '160px',
					'is_queryable' => true,
					'options'      => array(
						'pending'    => esc_html__( 'Pending', 'masterstudy-lms-learning-management-system-pro' ),
						'passed'     => esc_html__( 'Passed', 'masterstudy-lms-learning-management-system-pro' ),
						'not_passed' => esc_html__( 'Non-passed', 'masterstudy-lms-learning-management-system-pro' ),
					),
				)
			);
			?>
		</div>
	</div>

	<div class="masterstudy-table__wrapper">
		<div class="masterstudy-thead">
			<?php foreach ( $theads as $thead ) : ?>
				<?php
				if ( isset( $thead['hidden'] ) && $thead['hidden'] ) {
					continue;
				}
				?>
				<div class="masterstudy-tcell masterstudy-tcell_is-<?php echo esc_attr( ( $thead['position'] ?? 'center' ) . ' ' . ( $thead['grow'] ?? '' ) ); ?>">
					<div class="masterstudy-tcell__header" data-sort="<?php echo esc_attr( $thead['sort'] ?? 'none' ); ?>">
						<span class="masterstudy-tcell__title"><?php echo esc_html( $thead['title'] ?? '' ); ?></span>
						<?php if ( isset( $thead['sort'] ) ) : ?>
						<span class="masterstudy-thead__sort-indicator">
							<span class="masterstudy-thead__sort-indicator__up"></span>
							<span class="masterstudy-thead__sort-indicator__down"></span>
						</span>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-hidden-md"></div>
		</div>

		<div class="masterstudy-tbody">

			<?php if ( $assignments->have_posts() ) : ?>
				<?php while ( $assignments->have_posts() ) : ?>
					<?php
					$assignments->the_post();

					$assignment_id = get_the_ID();
					?>
					<div class="masterstudy-table__item">
						<div class="masterstudy-tcell masterstudy-tcell_is-grow" data-th="<?php echo esc_html__( 'Assigment', 'masterstudy-lms-learning-management-system-pro' ); ?>:" data-sort="assignment" data-th-inlined="false">
							<span class="masterstudy-tcell__title"><?php the_title(); ?></span>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-grow" data-th="<?php echo esc_html__( 'In course', 'masterstudy-lms-learning-management-system-pro' ); ?>:" data-sort="course" data-th-inlined="false">
							<ul class="masterstudy-table__list">
							<?php
							$course_ids = ( new CurriculumRepository() )->get_lesson_course_ids( $assignment_id );
							if ( ! empty( $course_ids ) ) :
								foreach ( $course_ids as $course_id ) {
									echo '<li><a href="' . esc_url( get_the_permalink( $course_id ) ) . '">' . esc_html( get_the_title( $course_id ) ) . '</a></li>';
								}
								else :
									?>
								<li>
									<?php echo esc_html__( 'No linked courses', 'masterstudy-lms-learning-management-system-pro' ); ?>
								</li>
							<?php endif; ?>
							</ul>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-sm-space-between masterstudy-tcell_is-sm-border-bottom" data-th="<?php echo esc_html( $theads['total']['title'] ?? '' ); ?>:"  data-th-inlined="true">
							<span><?php echo esc_html( $theads['total']['title'] ?? '' ); ?>:&nbsp;</span>
							<span data-sort="total"><?php echo esc_html( AssignmentTeacherRepository::user_assignments_count( $assignment_id ) ); ?></span>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-sm-space-between masterstudy-tcell_is-sm-border-bottom" data-th="<?php echo esc_html( $theads['passed']['title'] ?? '' ); ?>:"   data-th-inlined="true">
							<span>
								<i class="fa fa-check"></i>
								<?php echo esc_html( $theads['passed']['title'] ?? '' ); ?>:&nbsp;
							</span>
							<span data-sort="passed"><?php echo esc_html( max( AssignmentTeacherRepository::user_assignments_count( $assignment_id, AssignmentStudentRepository::STATUS_PASSED ), 0 ) ); ?></span>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-sm-space-between masterstudy-tcell_is-sm-border-bottom" data-th="<?php echo esc_html( $theads['not_passed']['title'] ?? '' ); ?>:" data-th-inlined="true">
							<span>
								<i class="fa fa-times"></i>
								<?php echo esc_html( $theads['not_passed']['title'] ?? '' ); ?>:&nbsp;
							</span>
							<span data-sort="not_passed"><?php echo esc_html( AssignmentTeacherRepository::user_assignments_count( $assignment_id, AssignmentStudentRepository::STATUS_NOT_PASSED ) ); ?></span>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-sm-space-between" data-th="<?php echo esc_html( $theads['pending']['title'] ?? '' ); ?>:" data-th-inlined="true">
							<span>
								<i class="far fa-clock"></i>
								<?php echo esc_html( $theads['pending']['title'] ?? '' ); ?>:&nbsp;
							</span>
							<span data-sort="pending"><?php echo esc_html( AssignmentTeacherRepository::user_assignments_count( $assignment_id, '', true ) ); ?></span>
						</div>
						<div class="masterstudy-tcell">
							<span class="masterstudy-table__component">
							<?php
								STM_LMS_Templates::show_lms_template(
									'components/button',
									array(
										'title'         => esc_html__( 'More', 'masterstudy-lms-learning-management-system-pro' ),
										'style'         => 'secondary',
										'size'          => 'sm',
										'link'          => STM_LMS_User::user_page_url( get_current_user_id() ) . "assignments/{$assignment_id}",
										'id'            => 'assignment-' . $assignment_id,
										'icon_position' => '',
										'icon_name'     => '',
									)
								);
							?>
							</span>
						</div>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<div class="masterstudy-table__item">
					<div class="masterstudy-tcell masterstudy-tcell_is-empty">
						<?php echo esc_html__( 'No Assignments found.', 'masterstudy-lms-learning-management-system-pro' ); ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
		<?php if ( $assignments->max_num_pages > 1 ) : ?>
		<div class="masterstudy-tfooter">
			<div class="masterstudy-tcell masterstudy-tcell_is-space-between">
				<span>
					<?php
						STM_LMS_Templates::show_lms_template(
							'components/pagination',
							array(
								'max_visible_pages' => 3,
								'total_pages'       => $assignments->max_num_pages,
								'dark_mode'         => false,
								'current_page'      => $current_page,
								'is_queryable'      => true,
								'done_indicator'    => false,
							)
						);
					?>
				</span>
			</div>
			<div class="masterstudy-tcell masterstudy-tcell_is-space-between">
				<span>
				<?php
					STM_LMS_Templates::show_lms_template(
						'components/select',
						array(
							'select_id'    => 'assignments-per-page',
							'select_width' => '170px',
							'select_name'  => 'per_page',
							'placeholder'  => esc_html__( '10 per page', 'masterstudy-lms-learning-management-system-pro' ),
							'default'      => 10,
							'is_queryable' => true,
							'options'      => array(
								'25'  => esc_html__( '25 per page', 'masterstudy-lms-learning-management-system-pro' ),
								'50'  => esc_html__( '50 per page', 'masterstudy-lms-learning-management-system-pro' ),
								'75'  => esc_html__( '75 per page', 'masterstudy-lms-learning-management-system-pro' ),
								'100' => esc_html__( '100 per page', 'masterstudy-lms-learning-management-system-pro' ),
							),
						)
					);
				?>
				</span>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
