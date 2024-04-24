<?php

/**
 * The template for displaying course content within loops
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/content-course.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.5.9
 */

defined( 'ABSPATH' ) || exit;

global $course;

// Ensure visibility.
if ( empty( $course ) || ! $course->is_visible() ) {
	return;
}

$author     = masteriyo_get_user( $course->get_author_id() );
$difficulty = $course->get_difficulty();
$categories = $course->get_categories( 'name' );

?>
<div class="masteriyo-col">
	<div class="masteriyo-course-item--wrapper masteriyo-course--card">
		<div class="masteriyo-course--img-wrap">
			<a href="<?php echo esc_attr( $course->get_permalink() ); ?>">

			<!-- Difficulty Badge -->
			<?php if ( $difficulty ) : ?>
			<div class="difficulty-badge <?php echo esc_attr( $difficulty['slug'] ); ?>" data-id="<?php echo esc_attr( $difficulty['id'] ); ?>">
				<?php if ( $difficulty['color'] ) : ?>
					<span class="masteriyo-badge" style="background-color: <?php echo esc_attr( $difficulty['color'] ); ?>">
						<?php echo esc_html( $difficulty['name'] ); ?>
					</span>
				<?php else : ?>
					<span class="masteriyo-badge <?php echo esc_attr( masteriyo_get_difficulty_badge_css_class( $difficulty['slug'] ) ); ?>">
						<?php echo esc_html( $difficulty['name'] ); ?>
					</span>
				<?php endif; ?>
			</div>
			<?php endif; ?>

				<!-- Featured Image -->
				<?php echo wp_kses( $course->get_image( 'masteriyo_thumbnail' ), 'masteriyo_image' ); ?>
			</a>
		</div>

		<div class="masteriyo-course--content">
			<div class="masteriyo-course--content__wrapper">
				<!-- Course category -->
				<?php if ( ! empty( $categories ) ) : ?>
					<div class="masteriyo-course--content__category">
						<?php foreach ( $categories as $category ) : ?>
							<a href="<?php echo esc_attr( $category->get_permalink() ); ?>" alt="<?php echo esc_attr( $category->get_name() ); ?>" class="masteriyo-course--content__category-items masteriyo-tag">
								<?php echo esc_html( $category->get_name() ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<!-- Title of the course -->
				<h2 class="masteriyo-course--content__title">
					<?php
					printf(
						'<a href="%s" title="%s">%s</a>',
						esc_url( $course->get_permalink() ),
						esc_html( $course->get_title() ),
						esc_html( $course->get_title() )
					);
					?>
				</h2>

				<!-- Course Expiration Info -->
				<?php masteriyo_get_template( 'course-expiration-info.php', array( 'course' => $course ) ); ?>

				<!-- Course author and course rating -->
				<div class="masteriyo-course--content__rt">
					<div class="masteriyo-course-author">
						<?php if ( $author && ! is_wp_error( $author ) ) : ?>
							<a href="<?php echo esc_url( $author->get_course_archive_url() ); ?>">
								<img src="<?php echo esc_attr( $author->profile_image_url() ); ?>" alt="<?php echo esc_attr( $author->get_display_name() ); ?>" title="<?php echo esc_attr( $author->get_display_name() ); ?>">
								<!-- Do not multiline below code, as it will create space around the display name. -->
								<span class="masteriyo-course-author--name"><?php echo esc_html( $author->get_display_name() ); ?></span>
							</a>
						<?php endif; ?>
					</div>

					<?php
					/**
					 * Fire after masteriyo course author.
					 *
					 * @since 1.5.10
					 *
					 * @param \Masteriyo\Models\Course $course Course object.
					 */
					do_action( 'masteriyo_after_course_author', $course );
					?>
					<?php if ( $course->is_review_allowed() ) : ?>
						<span class="masteriyo-icon-svg masteriyo-rating">
							<?php masteriyo_format_rating( $course->get_average_rating(), true ); ?> <?php echo esc_html( masteriyo_format_decimal( $course->get_average_rating(), 1, true ) ); ?> (<?php echo esc_html( $course->get_review_count() ); ?>)
						</span>
					<?php endif; ?>
				</div>

				<!-- Course description -->
				<div class="masteriyo-course--content__description">
					<?php if ( empty( $course->get_highlights() ) || empty( trim( wp_strip_all_tags( $course->get_highlights(), true ) ) ) ) : ?>
						<?php echo wp_kses_post( $course->get_excerpt() ); ?>
					<?php else : ?>
						<?php echo wp_kses_post( masteriyo_trim_course_highlights( $course->get_highlights() ) ); ?>
					<?php endif; ?>
				</div>

				<!-- Four Column (Course duration, comments, student enrolled and curriculum) -->
				<div class="masteriyo-course--content__stats">
					<div class="masteriyo-course-stats-duration">
						<?php masteriyo_get_svg( 'time', true ); ?> <span><?php echo esc_html( masteriyo_minutes_to_time_length_string( $course->get_duration() ) ); ?></span>
					</div>
					<div class="masteriyo-course-stats-students">
						<?php masteriyo_get_svg( 'group', true ); ?> <span><?php echo esc_html( masteriyo_count_enrolled_users( $course->get_id() ) ); ?></span>
					</div>
					<div class="masteriyo-course-stats-curriculum">
						<?php masteriyo_get_svg( 'book', true ); ?> <span><?php echo esc_html( masteriyo_get_lessons_count( $course ) ); ?></span>
					</div>
				</div>
			</div>
			<!-- Border -->
			<!-- Price and Enroll Now Button -->
			<div class="masteriyo-course-card-footer masteriyo-time-btn">
				<div class="masteriyo-course-price">
					<span class="current-amount"><?php echo wp_kses_post( masteriyo_price( $course->get_price() ) ); ?></span>
				</div>
				<?php
				/**
				 * Action hook for rendering enroll button template.
				 *
				 * @since 1.0.0
				 *
				 * @param \Masteriyo\Models\Course $course Course object.
				 */
				do_action( 'masteriyo_template_enroll_button', $course );
				?>
			</div>
		</div>
	</div>
</div>

<?php
