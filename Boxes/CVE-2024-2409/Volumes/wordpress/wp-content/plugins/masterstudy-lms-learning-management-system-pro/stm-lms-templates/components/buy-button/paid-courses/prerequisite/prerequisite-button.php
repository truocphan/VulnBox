<?php
/**
 * @var array $courses
*/

$settings    = get_option( 'stm_lms_settings' );
$theme_fonts = $settings['course_player_theme_fonts'] ?? false;
if ( empty( $theme_fonts ) ) {
	wp_enqueue_style( 'masterstudy-buy-button-prerequisites-fonts' );
}
wp_enqueue_style( 'masterstudy-buy-button-prerequisites' );
wp_enqueue_script( 'masterstudy-buy-button-prerequisites' );
?>
<div class="masterstudy-prerequisites">
	<a href="#" class="masterstudy-prerequisites__button">
		<span><?php echo esc_html__( 'Prerequisites', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	</a>

	<ul class="masterstudy-prerequisites-list">
		<?php
		foreach ( $courses as $course ) :
			$course_id = $course['course_id'];
			$progress  = $course['progress_percent'];
			?>
		<li>
			<a href="<?php the_permalink( $course_id ); ?>" class="masterstudy-prerequisites-list__title">
				<?php echo esc_html( get_the_title( $course_id ) ); ?>
			</a>
			<?php
			if ( empty( $progress ) ) :
				$price      = get_post_meta( $course_id, 'price', true );
				$sale_price = STM_LMS_Course::get_sale_price( $course_id );

				if ( empty( $price ) && ! empty( $sale_price ) ) {
					$price      = $sale_price;
					$sale_price = '';
				}

				if ( ! empty( $price ) && ! empty( $sale_price ) ) {
					list( $price, $sale_price ) = array( $sale_price, $price );
				}

				if ( ! empty( $price ) || ! empty( $sale_price ) ) :
					?>
				<div class="masterstudy-prerequisites-list__progress">
					<?php if ( ! empty( $sale_price ) ) : ?>
					<span class="masterstudy-course-sale-price"><?php echo esc_html( STM_LMS_Helpers::display_price( $sale_price ) ); ?></span>
						<?php
					endif;
					if ( ! empty( $price ) ) :
						?>
					<span class="masterstudy-course-price"><?php echo esc_html( STM_LMS_Helpers::display_price( $price ) ); ?></span>
					<?php endif; ?>
				</div>
				<?php else : ?>
				<div class="masterstudy-prerequisites-list__progress">
					<label class="price"><?php echo esc_html__( 'Free', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
				</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="masterstudy-prerequisites-list__progress-percent">
					<div class="masterstudy-prerequisites-list__progress-percent-striped"
						role="progressbar"
						aria-valuenow="45"
						aria-valuemin="0"
						aria-valuemax="100"
						style="width: <?php echo intval( $progress ); ?>%">
					</div>
				</div>
				<span class="progress-started"><?php echo esc_html__( 'Enrolled', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
		<li class="masterstudy-prerequisites-list__explanation">
			<div class="masterstudy-prerequisites-list__explanation-title">
				<i class="fa fa-question-circle"></i>
				<?php echo esc_html__( 'What is Prerequisite courses', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</div>
			<div class="masterstudy-prerequisites-list__explanation-info">
				<?php echo esc_html__( 'A prerequisite is a specific course  that you must complete before you can take another course at the next grade level.', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</div>
		</li>
	</ul>
</div>
