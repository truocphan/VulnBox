<?php
/**
 * @var int $course_id
 * @var string $style
 * @var boolean $dark_mode
 *
 * masterstudy-curriculum-list_dark-mode - for dark mode
 * masterstudy-curriculum-list_classic - for classic style
 * masterstudy-curriculum-list__link_disabled - for disable click on lesson
 */

use \MasterStudy\Lms\Repositories\CurriculumRepository;

$dark_mode        = false;
$udemy_curriculum = ! empty( get_post_meta( $course_id, 'udemy_course_id', true ) );
$template         = $udemy_curriculum ? 'udemy-materials' : 'materials';
$curriculum       = $udemy_curriculum
	? get_post_meta( $course_id, 'udemy_curriculum', true )
	: ( new CurriculumRepository() )->get_curriculum( $course_id, true );

if ( empty( $curriculum ) ) {
	return;
}

wp_enqueue_style( 'masterstudy-curriculum-list' );
wp_enqueue_script( 'masterstudy-curriculum-list' );
?>

<div class="masterstudy-curriculum-list <?php echo esc_attr( $dark_mode ? 'masterstudy-curriculum-list_dark-mode' : '' ); ?> <?php echo esc_attr( 'classic' === $style ? 'masterstudy-curriculum-list_classic' : '' ); ?>">
	<?php
	STM_LMS_Templates::show_lms_template(
		"components/curriculum-list/{$template}",
		array(
			'course_id'  => $course_id,
			'curriculum' => $curriculum,
			'dark_mode'  => $dark_mode,
		)
	);
	?>
</div>
