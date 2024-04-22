<?php
/**
 * @var $post_id
 * @var $item_id
 */

use \MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use \MasterStudy\Lms\Repositories\CurriculumSectionRepository;

$material = ( new CurriculumMaterialRepository() )->find_by_course_lesson( $post_id, $item_id );
if ( ! empty( $material ) ) {
	$section = ( new CurriculumSectionRepository() )->find( $material->section_id );
}
?>

<div class="stm-lms-course__lesson-content__top">
	<?php if ( ! empty( $section ) ) : ?>
		<h3><?php echo esc_html( $section->title ); ?></h3>
	<?php endif; ?>
	<h1><?php echo esc_html( get_the_title( $item_id ) ); ?></h1>
</div>
