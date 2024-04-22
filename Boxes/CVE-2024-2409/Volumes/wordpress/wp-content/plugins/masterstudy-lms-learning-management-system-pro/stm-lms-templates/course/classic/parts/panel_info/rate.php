<?php
$reviews = get_post_meta( get_the_ID(), 'course_marks', true );
if ( ! empty( $reviews ) ) :
	$rates   = STM_LMS_Course::course_average_rate( $reviews );
	$average = $rates['average'];
	$percent = $rates['percent'];

	?>
	<div class="average-rating-stars">
		<div class="average-rating-stars__av heading_font">
			<?php echo number_format( $average, 1, '.', '' ); ?>
		</div>
		<div class="average-rating-stars__top">
			<div class="star-rating"
					title="<?php /* translators: %s Average Rating */ printf( esc_html__( 'Rated %s out of 5', 'masterstudy-lms-learning-management-system-pro' ), esc_html( $average ) ); ?>">
				<span style="width:<?php echo esc_attr( $percent ); ?>%">
					<strong class="rating"></strong>
				</span>
			</div>
		</div>

		<div class="average-rating-stars__reviews">
			<?php
			printf(
				esc_html(
					/* translators: %s Reviews */
					_n(
						'%s review',
						'%s reviews',
						count( $reviews ),
						'masterstudy-lms-learning-management-system-pro'
					)
				),
				count( $reviews )
			);
			?>
		</div>

	</div>

<?php endif; ?>
