<?php
/**
 * @var $course_id
 * @var $price
 */

$has_course = STM_LMS_User::has_course_access( $course_id, false );

if ( is_user_logged_in() ) :
	?>
	<span class="or heading_font enterprise-or">- <?php esc_html_e( 'For Business', 'masterstudy-lms-learning-management-system-pro' ); ?> -</span>

	<div class="stm-lms-buy-buttons stm-lms-buy-buttons-enterprise">
		<div class="btn btn-default btn_big heading_font text-center" data-masterstudy-modal="masterstudy-group-courses-modal">
			<span><?php esc_html_e( 'Buy for group', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
		</div>
	</div>
	<?php
	STM_LMS_Templates::show_lms_template(
		'components/modals/group-courses',
		array(
			'post_id' => $course_id,
		)
	);
endif;
