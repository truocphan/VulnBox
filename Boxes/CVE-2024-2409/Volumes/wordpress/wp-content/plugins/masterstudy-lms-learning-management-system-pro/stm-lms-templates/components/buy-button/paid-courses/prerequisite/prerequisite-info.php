<?php
/**
 * @var array $courses
*/

$settings    = get_option( 'stm_lms_settings' );
$theme_fonts = $settings['course_player_theme_fonts'] ?? false;
if ( empty( $theme_fonts ) ) {
	wp_enqueue_style( 'masterstudy-prerequisites-info-fonts' );
}
wp_enqueue_style( 'masterstudy-prerequisites-info' );
?>
<div class="masterstudy-prerequisites">
	<div class="masterstudy-prerequisites-info">
		<div class="masterstudy-prerequisites-info_title"><?php echo esc_html__( 'Prerequisite course', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
		<div class="masterstudy-prerequisites-info_notice">
			<h2><?php echo esc_html__( 'You must complete this courses before continuing', 'masterstudy-lms-learning-management-system-pro' ); ?></h2>
			<div class="masterstudy-prerequisites-info_notice-description"><?php echo esc_html__( 'A prerequisite is a specific course that you must complete before you can take another course at the next grade level.', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
		</div>
	</div>
	<ul class="masterstudy-prerequisites-list">
		<?php
		foreach ( $courses as $course ) :
			$course_id         = $course['course_id'];
			$progress          = $course['progress_percent'];
			$thumbnail_url     = get_the_post_thumbnail_url( $course_id, 'img-480-380' );
			$author_id         = get_post_field( 'post_author', $course_id );
			$custom_avatar     = get_user_meta( $author_id, 'stm_lms_user_avatar', true );
			$author_avatar     = $custom_avatar ? $custom_avatar : get_avatar_url( $author_id, array( 'size' => 64 ) );
			$author_first_name = get_the_author_meta( 'first_name', $author_id );
			$author_last_name  = get_the_author_meta( 'last_name', $author_id );
			$user              = ( ! empty( $author_first_name ) || ! empty( $author_last_name ) )
				? $author_first_name . ' ' . $author_last_name
				: get_the_author_meta( 'user_login', $author_id );
			$level             = get_post_meta( $course_id, 'level', true );
			$price             = get_post_meta( $course_id, 'price', true );
			$sale_price        = STM_LMS_Course::get_sale_price( $course_id );
			$not_in_membership = get_post_meta( $course_id, 'not_membership', true );

			$subscription_enabled = ( empty( $not_in_membership ) && STM_LMS_Subscriptions::subscription_enabled() && STM_LMS_Course::course_in_plan( $course_id ) );
			if ( $subscription_enabled ) {
				$plans_courses = STM_LMS_Course::course_in_plan( $course_id );
			}

			$subscription_enabled = ( empty( $not_in_membership ) && STM_LMS_Subscriptions::subscription_enabled() && STM_LMS_Course::course_in_plan( $course_id ) );
			if ( $subscription_enabled ) {
				$plans_courses = STM_LMS_Course::course_in_plan( $course_id );
			}

			if ( '100' !== $progress ) :
				?>
				<li>
					<div class="masterstudy-prerequisites-list__image">
						<a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" <?php echo ( '100' === $progress ) ? 'disabled="disabled"' : ''; ?> target="_blank">
							<?php echo '<img src="' . esc_url( ! empty( $thumbnail_url ) ? $thumbnail_url : STM_LMS_URL . 'assets/img/image_not_found.png' ) . '" width="270" height="150" alt="' . esc_html( get_the_title( $course_id ) ) . '">'; ?>
						</a>
					</div>
					<div class="masterstudy-prerequisites-list__info">
						<a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" class="masterstudy-prerequisites-list__info-title" <?php echo ( '100' === $progress ) ? 'disabled="disabled"' : ''; ?> target="_blank">
							<?php echo esc_html( get_the_title( $course_id ) ); ?>
						</a>
						<div class="masterstudy-prerequisites-list__info-author">
						<?php if ( $author_avatar ) : ?>
							<img src="<?php echo esc_url( $author_avatar ); ?>" width="24" height="24" alt="<?php echo esc_html( $user ); ?>">
							<?php
							endif;
							echo esc_html( $user );
						?>
						</div>
						<div class="masterstudy-prerequisites-list__info-meta">
							<div class="masterstudy-prerequisites-list__info-level">
								<i class="stmlms-levels"></i>
								<span><?php echo esc_html( ( ! empty( $level ) ) ? $level : __( 'No Level', 'masterstudy-lms-learning-management-system' ) ); ?></span>
							</div>
							<div class="masterstudy-prerequisites-list__info-price<?php echo esc_html( ( $sale_price ) ? ' has-sale' : '' ); ?>">
							<?php if ( $sale_price ) : ?>
								<span class="masterstudy-prerequisites-list__info-price_sale"><?php echo esc_html( STM_LMS_Helpers::display_price( $sale_price ) ); ?></span>
								<?php
							endif;
							if ( $price ) :
								?>
								<span class="masterstudy-prerequisites-list__info-price_regular"><?php echo esc_html( STM_LMS_Helpers::display_price( $price ) ); ?></span>
								<?php
							endif;
							if ( empty( $sale_price ) && empty( $price ) && empty( $plans_courses ) ) {
								echo esc_html__( 'Free', 'masterstudy-lms-learning-management-system-pro' );
							} elseif ( empty( $sale_price ) && empty( $price ) && ! empty( $plans_courses ) ) {
								if ( ! empty( $not_in_membership ) ) {
									echo esc_html__( 'Free', 'masterstudy-lms-learning-management-system-pro' );
								} else {
									echo esc_html__( 'Members Only', 'masterstudy-lms-learning-management-system-pro' );
								}
							}
							?>
							</div>
						</div>
						<div class="masterstudy-prerequisites-list__info-progress">
							<div class="masterstudy-prerequisites-list__info-progress-bar">
							<?php if ( $progress ) : ?>
								<div class="masterstudy-prerequisites-list__info-progress-bar_empty"></div>
								<div class="masterstudy-prerequisites-list__info-progress-bar_filled"
										role="progressbar"
										aria-valuenow="45"
										aria-valuemin="0"
										aria-valuemax="100"
										style="width: <?php echo intval( $progress ); ?>%">
								</div>
								<div class="masterstudy-prerequisites-list__info-progress-bar_title">
									<?php echo esc_html__( 'Progress', 'masterstudy-lms-learning-management-system' ) . ': ' . esc_html( $progress ) . '%'; ?>
								</div>
							<?php endif; ?>
							</div>
							<?php
							STM_LMS_Templates::show_lms_template(
								'components/button',
								array(
									'title'  => ( empty( $progress ) ) ? __( 'Get now', 'masterstudy-lms-learning-management-system' ) : __( 'Continue', 'masterstudy-lms-learning-management-system' ),
									'link'   => get_permalink( $course_id ),
									'target' => '_blank',
									'style'  => 'primary',
									'size'   => 'sm',
								)
							);
							?>
						</div>
					</div>
				</li>
				<?php
			endif;
		endforeach;
		?>
	</ul>
</div>
