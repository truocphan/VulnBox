<?php
/**
 * @var $current_user
 */
stm_lms_register_style( 'courses' );
stm_lms_register_script( 'courses' );
stm_lms_register_style( 'instructor_courses' );

$args = array(
	'author' => $current_user['id'],
	'class'  => 'vue_is_disabled',
);
?>

<ul class="masterstudy-lms-public-account-courses-tabs">
	<?php
	if ( is_ms_lms_addon_enabled( 'multi_instructors' ) ) {
		?>
		<h3><?php esc_html_e( 'Teacher Courses', 'masterstudy-lms-learning-management-system' ); ?></h3>
		<div class="tabs">
			<li class="active" id="tab_courses"><?php esc_html_e( 'Courses', 'masterstudy-lms-learning-management-system' ); ?></li>
			<li class="" id="tab_co_courses"><?php esc_html_e( 'Co-Owned Courses', 'masterstudy-lms-learning-management-system' ); ?></li>
		</div>
		<?php
	} else {
		?>
		<h3><?php esc_html_e( 'Courses', 'masterstudy-lms-learning-management-system' ); ?></h3>
		<?php
	}
	?>
</ul>

<div class="stm_lms_courses" id="stm_lms_instructor_courses">

	<?php STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $args ) ); ?>

	<?php
		STM_LMS_Templates::show_lms_template(
			'courses/load_more',
			array(
				'args' => array_merge(
					$args,
					array(
						'per_row'        => STM_LMS_Options::get_option( 'courses_per_row', 10 ),
						'posts_per_page' => STM_LMS_Options::get_option( 'courses_per_page', get_option( 'posts_per_page' ) ),
					)
				),
			)
		);
		?>
</div>
<?php do_action( 'stm_lms_instructor_courses_end' ); ?>
