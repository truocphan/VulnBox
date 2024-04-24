<?php
/**
 * The Template for displaying a course review replies in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/review-replies.php.
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

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="masteriyo-course-review-replies">
	<?php
	foreach ( $replies as $reply ) {
		/**
		 * Action hook for rendering course review reply template.
		 *
		 * @since 1.0.5
		 *
		 * @param array $args Template args.
		 */
		do_action(
			'masteriyo_template_course_review_reply',
			array(
				'course_review' => $course_review,
				'reply'         => $reply,
			)
		);
	}
	?>
</div>
<?php
