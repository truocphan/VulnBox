<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} //Exit if accessed directly ?>

<?php
/**
 * @var $course_id
 */

stm_lms_register_style( 'course_info' );

$meta        = STM_LMS_Helpers::parse_meta_field( $course_id );
$meta_fields = array();

if ( ! empty( $meta['current_students'] ) ) {
	$meta_fields[ esc_html__( 'Enrolled', 'masterstudy-lms-learning-management-system-pro' ) ] = array(
		'text' => sprintf(
			/* translators: %s Current Students */
			_n( '%s student', '%s students', $meta['current_students'], 'masterstudy-lms-learning-management-system-pro' ),
			$meta['current_students']
		),
		'icon' => 'fa-icon-stm_icon_users',
	);
}

if ( ! empty( $meta['duration_info'] ) ) {
	$meta_fields[ esc_html__( 'Duration', 'masterstudy-lms-learning-management-system-pro' ) ] = array(
		'text' => $meta['duration_info'],
		'icon' => 'fa-icon-stm_icon_clock',
	);
}


$curriculum_info = get_post_meta( $course_id, 'udemy_curriculum', true );
if ( ! empty( $curriculum_info ) && ! empty( $curriculum_info['count'] ) ) {
	$meta_fields[ esc_html__( 'Lectures', 'masterstudy-lms-learning-management-system-pro' ) ] = array(
		'text' => $curriculum_info['count'],
		'icon' => 'fa-icon-stm_icon_bullhorn',
	);
}

if ( ! empty( $meta['video_duration'] ) ) {
	$meta_fields[ esc_html__( 'Video', 'masterstudy-lms-learning-management-system-pro' ) ] = array(
		'text' => $meta['video_duration'],
		'icon' => 'fa-icon-stm_icon_film-play',
	);
}

if ( ! empty( $meta['level'] ) ) {
	$levels = array(
		'beginner'     => esc_html__( 'Beginner', 'masterstudy-lms-learning-management-system-pro' ),
		'intermediate' => esc_html__( 'Intermediate', 'masterstudy-lms-learning-management-system-pro' ),
		'advanced'     => esc_html__( 'Advanced', 'masterstudy-lms-learning-management-system-pro' ),
	);
	$meta_fields[ esc_html__( 'Level', 'masterstudy-lms-learning-management-system-pro' ) ] = array(
		'text' => $levels[ $meta['level'] ],
		'icon' => 'lnr lnr-sort-amount-asc',
	);
}

if ( ! empty( $meta_fields ) ) :
	?>
	<div class="stm-lms-course-info heading_font">
		<?php foreach ( $meta_fields as $meta_field_key => $meta_field ) : ?>
			<div class="stm-lms-course-info__single">
				<div class="stm-lms-course-info__single_label">
					<span><?php echo $meta_field_key; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>:
					<strong><?php echo $meta_field['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</div>
				<div class="stm-lms-course-info__single_icon">
					<i class="<?php echo $meta_field['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"></i>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<div class="stm-lms-course-info">
		<div class="stm-lms-course-info__single"></div>
	</div>
	<?php
endif;
