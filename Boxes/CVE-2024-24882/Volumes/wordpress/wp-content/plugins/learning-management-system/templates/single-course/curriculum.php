<?php
/**
 * The Template for displaying course curriculum in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/curriculum.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Fires before rendering curriculum section in single course page.
 *
 * @since 1.0.0
 * @since 1.5.15 Added $course parameter.
 *
 * @param \Masteriyo\Models\Course $course Course object.
 */
do_action( 'masteriyo_before_single_course_curriculum', $course );

$is_hidden = isset( $is_hidden ) ? $is_hidden : true;

?>

<div class="tab-content course-curriculum <?php echo $is_hidden ? 'masteriyo-hidden' : ''; ?>">
	<div class="masteriyo-stab--tcurriculum">
		<div class="masteriyo-stab--shortinfo">
			<div class="title-container">
				<h3 class="title"><?php esc_html_e( 'Curriculum', 'masteriyo' ); ?></h3>
				<ul class="masteriyo-shortinfo-wrap">
				<?php
					/**
					 * Display single course curriculum summary.( Sections, Lessons and Quizzes count)
					 *
					 * @since 1.5.15
					 *
					 * @param \Masteriyo\Model\Course $course Course object.
					 */
				do_action( 'masteriyo_single_course_curriculum_summary', $course );
				?>
				</ul>
			</div>

			<?php if ( $sections ) : ?>
				<span class="masteriyo-link-primary masteriyo-expand-collapse-all"><?php esc_html_e( 'Expand All', 'masteriyo' ); ?></span>
			<?php endif; ?>
		</div>

		<?php foreach ( $sections as $index => $section ) : ?>
		<div class="masteriyo-stab--citems <?php echo esc_attr( 0 === $index ? 'active' : '' ); ?>">
			<div class="masteriyo-cheader">
				<h5 class="masteriyo-ctitle"><?php echo esc_html( $section->get_name() ); ?></h5>

				<div class="masteriyo-ltc masteriyo-flex-ycenter">
					<?php
						/**
						 * Display single course curriculum summary.( Sections, Lessons and Quizzes count)
						 *
						 * @since 1.5.15
						 *
						 * @param \Masteriyo\Model\Course $course Course object.
						 * @param \Masteriyo\Model\Section $section Section object.
						 */
					do_action( 'masteriyo_single_course_curriculum_section_summary', $course, $section );
					?>
				</div>
			</div>

			<div class="masteriyo-cbody">
				<ol class="masteriyo-lesson-list">
				<?php
					/**
					 * Display single course curriculum section children content.
					 *
					 * @since 1.5.15
					 *
					 * @param \Masteriyo\Model\Course $course Course object.
					 * @param \Masteriyo\Model\Section $section Section object.
					 */
					do_action( 'masteriyo_single_course_curriculum_section_content', $course, $section );
				?>
				</ol>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<?php

/**
 * Fires after rendering curriculum section in single course page.
 *
 * @since 1.0.0
 * @since 1.5.15 Added $course parameter.
 *
 * @param \Masteriyo\Models\Course $course Course object.
 */
do_action( 'masteriyo_after_single_course_curriculum', $course );
