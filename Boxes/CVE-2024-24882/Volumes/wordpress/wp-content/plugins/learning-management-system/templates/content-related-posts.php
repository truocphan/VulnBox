<?php
/**
 * The Template for displaying related courses in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/content-related-posts.php.
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

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering related courses template in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_related_posts' );

$related_courses = masteriyo_get_related_courses( $GLOBALS['course'] );

if ( empty( $related_courses ) ) {
	/**
	 * Fires when there is no related posts (i.e. courses) to display.
	 *
	 * @since 1.0.0
	 */
	do_action( 'masteriyo_no_related_posts' );
	return;
}

/**
 * Fires before rendering related posts (i.e. courses).
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_related_posts_content' );

?>
<div class="masteriyo-related-post">
	<h3 class="masteriyo-related-post__title"><?php esc_html_e( 'Related Courses', 'masteriyo' ); ?></h3>

	<div class="masteriyo-item--wrap masteriyo-w-100">
		<?php
		foreach ( $related_courses as $course ) {
			$author         = masteriyo_get_user( $course->get_author_id() );
			$comments_count = masteriyo_count_course_comments( $course );
			$difficulty     = $course->get_difficulty();
			?>
		<div class="masteriyo-col-4">
			<div class="masteriyo-course--card">
				<a href="<?php echo esc_url( $course->get_permalink() ); ?>" title="<?php esc_attr( $course->get_name() ); ?>">
					<div class="masteriyo-course--img-wrap">
						<!-- Difficulty Badge -->
						<?php if ( $difficulty ) : ?>
							<div class="difficulty-badge <?php echo esc_attr( $difficulty['slug'] ); ?>" data-id="<?php echo esc_attr( $difficulty['id'] ); ?>">
								<span class="masteriyo-badge <?php echo esc_attr( masteriyo_get_difficulty_badge_css_class( $difficulty['slug'] ) ); ?>"><?php echo esc_html( $difficulty['name'] ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Featured Image -->
						<?php echo wp_kses_post( $course->get_image( 'masteriyo_thumbnail' ) ); ?>
					</div>
				</a>

				<div class="masteriyo-course--content">
					<!-- Course category -->
					<div class="masteriyo-course--content__category">
						<?php foreach ( $course->get_categories( 'name' ) as $category ) : ?>
							<a href="<?php echo esc_url( $category->get_permalink() ); ?>" alt="<?php echo esc_attr( $category->get_name() ); ?>">
								<span class="masteriyo-course--content__category-items masteriyo-tag">
									<?php echo esc_html( $category->get_name() ); ?>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
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
					<!-- Course author and course rating -->
					<div class="masteriyo-course--content__rt">
						<div class="masteriyo-course-author">
							<?php if ( $author ) : ?>
								<a href="<?php echo esc_url( $author->get_course_archive_url() ); ?>">
									<img src="<?php echo esc_attr( $author->profile_image_url() ); ?>"
										alt="<?php echo esc_attr( $author->get_display_name() ); ?>"
										title="<?php echo esc_attr( $author->get_display_name() ); ?>"
									>
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
						<?php echo wp_kses_post( masteriyo_trim_course_highlights( $course->get_highlights() ) ); ?>
					</div>
					<!-- Four Column (Course duration, comments, student enrolled and curriculum) -->
					<div class="masteriyo-course--content__stats">
						<div class="masteriyo-course-stats-duration">
							<?php masteriyo_get_svg( 'time', true ); ?> <span><?php echo esc_html( masteriyo_minutes_to_time_length_string( $course->get_duration() ) ); ?></span>
						</div>
						<div class="masteriyo-course-stats-comments">
							<?php masteriyo_get_svg( 'comment', true ); ?><span><?php echo esc_html( $comments_count ); ?></span>
						</div>
						<div class="masteriyo-course-stats-students">
							<?php masteriyo_get_svg( 'group', true ); ?> <span><?php echo esc_html( masteriyo_count_enrolled_users( $course->get_id() ) ); ?></span>
						</div>
						<div class="masteriyo-course-stats-curriculum">
							<?php masteriyo_get_svg( 'book', true ); ?> <span><?php echo esc_html( masteriyo_get_lessons_count( $course ) ); ?></span>
						</div>
					</div>
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
		<?php } ?>
	</div>
</div>
<?php
/**
 * Fires after rendering related posts (i.e. courses).
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_related_posts_content' );

/**
 * Fires after rendering related courses template in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_related_posts' );
