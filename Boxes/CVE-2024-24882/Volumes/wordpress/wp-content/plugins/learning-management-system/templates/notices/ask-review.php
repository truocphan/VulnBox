<?php
/**
 * Admin notice to ask for review.
 *
 * @since 1.4.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="masteriyo-notice masteriyo-review-notice updated">
	<div class="masteriyo-notice-main-content">
		<?php masteriyo_get_svg( 'heart', true ); ?>
		<div class="masteriyo-notice-main-content-wrapper">
			<p class="masteriyo-notice__title">
				<?php esc_html_e( 'Love using LMS by Masteriyo?', 'masteriyo' ); ?>
			</p>
			<div class="masteriyo-notice__description">
				Please do us a favor by providing 5-star <div class="star-icons">
					<?php masteriyo_get_svg( 'full_star', true ); ?>
					<?php masteriyo_get_svg( 'full_star', true ); ?>
					<?php masteriyo_get_svg( 'full_star', true ); ?>
					<?php masteriyo_get_svg( 'full_star', true ); ?>
					<?php masteriyo_get_svg( 'full_star', true ); ?>
				</div> rating at WordPress.org. Let us know <a href="https://masteriyo.com/contact/" target="_blank">here</a> if you have any query. - Masteriyo Team
			</div>
		</div>
		<div class="masteriyo-x-icon-container">
			<?php masteriyo_get_svg( 'x', true ); ?>
		</div>
	</div>
	<div class="masteriyo-notice__actions submit">
		<a href="https://wordpress.org/support/plugin/learning-management-system/reviews/?rate=5#new-post" class="button button-primary masteriyo-leave-review" target="_blank" rel="noopener noreferrer">
			<?php esc_html_e( 'Sure, I\'d love to', 'masteriyo' ); ?>
		</a>
		<button class="button button-secondary masteriyo-remind-me-later">
			<?php esc_html_e( 'Maybe later', 'masteriyo' ); ?>
		</button>
		<button class="button button-secondary masteriyo-already-reviewed">
			<?php esc_html_e( 'I already did', 'masteriyo' ); ?>
		</button>
	</div>
</div>
