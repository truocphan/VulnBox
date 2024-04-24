<?php
/**
 * The Template for displaying course review reply.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/course-review-reply.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.5
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="masteriyo-course-review is-course-review-reply" data-id="<?php echo esc_attr( $reply->get_id() ); ?>">
	<input type="hidden" name="parent" value="<?php echo esc_attr( $course_review->get_id() ); ?>">
	<div class="rating" data-value="0"></div>
	<div class="masteriyo-review masteriyo-flex masteriyo-replies masteriyo-border-none">
		<div class="masteriyo-avatar">
			<?php if ( ! $reply->get_author() ) : ?>
				<img src="<?php echo esc_attr( $pp_placeholder ); ?>" />
			<?php else : ?>
				<img src="<?php echo esc_attr( $reply->get_author()->get_avatar_url() ); ?>" />
			<?php endif; ?>
		</div>
		<div class="masteriyo-flex  justify-content-between masteriyo-reply-replies">
			<div class="masteriyo-right">
				<div class="masteriyo-reply-replies--title">
					<div class="masteriyo-flex">
						<div class="author-name" data-value="<?php echo esc_attr( $reply->get_author_name() ); ?>">
							<?php echo esc_html( $reply->get_author_name() ); ?>
						</div>
						<div class="date-created" data-value="<?php echo esc_attr( $reply->get_date_created() ); ?>">
							<?php echo esc_html( $reply->get_date_created() ); ?>
						</div>
					</div>
					<?php if ( masteriyo_current_user_can_edit_course_review( $reply ) ) : ?>
						<nav class="masteriyo-dropdown">
							<label class="menu-toggler">
								<span class='icon_box'>
									<?php masteriyo_get_svg( 'small-hamburger', true ); ?>
								</span>
							</label>
							<ul class="slide menu">
								<li class="masteriyo-edit-course-review"><strong><?php esc_html_e( 'Edit', 'masteriyo' ); ?></strong></li>
								<li class="masteriyo-delete-course-review"><strong><?php esc_html_e( 'Delete', 'masteriyo' ); ?></strong></li>
							</ul>
						</nav>
					<?php endif; ?>
				</div>
				<div class="content" data-value="<?php echo esc_attr( $reply->get_content() ); ?>">
					<?php echo esc_html( $reply->get_content() ); ?>
				</div>
			</div>

		</div>
	</div>
</div>
<?php
