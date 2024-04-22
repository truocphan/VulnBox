<?php

/**
 * @var $assignment_id
 */

use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentStudentRepository;

stm_lms_register_style( 'assignments/instructor-assignments-table' );
stm_lms_pro_register_script( 'assignments/sortable-table' );

$theads       = array(
	'student_name'   => array(
		'title'    => esc_html__( 'Student name', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'start',
		'hidden'   => false,
		'grow'     => 'masterstudy-tcell_is-grow',
	),
	'course'         => array(
		'title'    => esc_html__( 'Course', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'hidden'   => false,
	),
	'date'           => array(
		'title'    => esc_html__( 'Date', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'date',
		'hidden'   => false,
	),
	'attempt_number' => array(
		'title'    => esc_html__( 'Attempt number', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'attempt_number',
		'hidden'   => false,
	),
	'status'         => array(
		'title'    => esc_html__( 'Status', 'masterstudy-lms-learning-management-system-pro' ),
		'position' => 'center',
		'sort'     => 'status',
		'hidden'   => false,
	),
);
$current_page = get_query_var( 'page' ) > 0 ? get_query_var( 'page' ) : 1;
$assignments  = AssignmentStudentRepository::get_assignments( $assignment_id );
?>
<div class="masterstudy-table">
	<div class="masterstudy-table__toolbar">

		<div class="masterstudy-table__toolbar-header">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/back-link',
				array(
					'id'  => 'masterstudy-course-player-back',
					'url' => STM_LMS_User::user_page_url( get_current_user_id() ) . 'assignments',
				)
			);
			?>
			<h3 class="masterstudy-table__title">
				<?php echo esc_html__( 'Student assignments', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</h3>
		</div>

		<div class="masterstudy-table__filters">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/search',
				array(
					'is_queryable' => true,
					'placeholder'  => esc_html__( 'Search by name', 'masterstudy-lms-learning-management-system-pro' ),
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
					<?php $assignments->the_post(); ?>
					<div class="masterstudy-table__item">
						<div class="masterstudy-tcell masterstudy-tcell_is-grow" data-th="<?php echo esc_html__( 'Assigments', 'masterstudy-lms-learning-management-system-pro' ); ?>:" data-sort="assignment" data-th-inlined="false">
							<span class="masterstudy-tcell__title"><?php the_title(); ?></span>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-grow" data-th="<?php echo esc_html__( 'In course', 'masterstudy-lms-learning-management-system-pro' ); ?>:" data-sort="course" data-th-inlined="false">
							<ul class="masterstudy-table__list">
							<?php
								$course_id    = get_post_meta( get_the_ID(), 'course_id', true );
								$course_title = get_the_title( $course_id );
							if ( $course_title ) :
								?>
							<li><?php echo esc_html( $course_title ); ?></li>
							<?php else : ?>
							<li>
								<?php echo esc_html__( 'No linked courses', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</li>
							<?php endif; ?>
							</ul>

						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-sm-space-between masterstudy-tcell_is-sm-border-bottom" data-th="<?php echo esc_html( $theads['date']['title'] ?? '' ); ?>:"  data-th-inlined="true"> 
							<span class="masterstudy-tcell__item masterstudy-tcell__item-mobile"><?php echo esc_html( $theads['date']['title'] ?? '' ); ?>:&nbsp;</span>
							<span data-sort="date"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></span>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-sm-space-between masterstudy-tcell_is-sm-border-bottom" data-th="<?php echo esc_html( $theads['attempt_number']['title'] ?? '' ); ?>:" data-th-inlined="true">
							<span class="masterstudy-tcell__item masterstudy-tcell__item-mobile"><?php echo esc_html( $theads['attempt_number']['title'] ?? '' ); ?>:&nbsp;</span>
							<span data-sort="attempt_number"><?php echo esc_html( get_post_meta( get_the_ID(), 'try_num', true ) ); ?></span>
						</div>
						<div class="masterstudy-tcell masterstudy-tcell_is-center masterstudy-tcell_is-sm-space-between" data-th="<?php echo esc_html( $theads['status']['title'] ?? '' ); ?>:" data-th-inlined="true">
								<?php
								$icons   = array(
									'pending'    => 'far fa-clock',
									'passed'     => 'fa fa-check',
									'not_passed' => 'fa fa-times',
								);
								$_status = get_post_status( get_the_ID() );

								if ( ! in_array( $_status, array( 'darft', 'pending' ), true ) ) {
									$_status = get_post_meta( get_the_ID(), 'status', true );
								}

								$status_title = AssignmentStudentRepository::get_status( $_status, false );
								?>
							<span><i class="<?php echo esc_attr( $icons[ $_status ] ?? '' ); ?>"></i>&nbsp;</span>
							<span data-sort="status"><?php echo esc_html( $status_title ?? '' ); ?></span>
						</div>
						<div class="masterstudy-tcell">
							<span class="masterstudy-table__component">
							<?php
								STM_LMS_Templates::show_lms_template(
									'components/button',
									array(
										'title'         => esc_html__( 'Review', 'masterstudy-lms-learning-management-system-pro' ),
										'style'         => 'secondary',
										'size'          => 'sm',
										'link'          => STM_LMS_User::user_page_url( get_current_user_id() ) . 'user-assignment/' . get_the_ID(),
										'id'            => 'assignment-' . get_the_ID(),
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
				<span class="masterstudy-assignment__pagination">
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
						'placeholder'  => __( '10 per page', 'masterstudy-lms-learning-management-system-pro' ),
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
