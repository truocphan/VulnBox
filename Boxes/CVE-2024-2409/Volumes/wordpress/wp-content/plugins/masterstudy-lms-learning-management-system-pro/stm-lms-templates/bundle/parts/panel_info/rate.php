<?php

$rates = STM_LMS_Course_Bundle::get_bundle_rating( get_the_ID() );

if ( ! empty( $rates['count'] ) ) :
	$average = round( $rates['average'] / $rates['count'], 2 );
	$percent = round( $rates['percent'] / $rates['count'], 2 );

	?>
	<div class="average-rating-stars">
		<div class="average-rating-stars__top">
			<div class="star-rating" title="<?php printf( /* translators: %s Average Rate */ esc_html__( 'Rated %s out of 5', 'masterstudy-lms-learning-management-system-pro' ), esc_attr( $average ) ); ?>">
			<span style="width:<?php echo esc_attr( $percent ); ?>%">
				<strong class="rating"><?php echo esc_attr( $average ); ?></strong>
				<?php esc_html_e( 'out of 5', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
			</div>
			<div class="average-rating-stars__av heading_font"><?php echo floatval( $average ); ?></div>
		</div>

		<div class="average-rating-stars__reviews">
			<?php
			printf(
				esc_html(
					/* translators: %s Ratings Count */
					_n(
						'%s review',
						'%s reviews',
						esc_html( $rates['count'] ),
						'masterstudy-lms-learning-management-system-pro'
					),
				),
				esc_html( $rates['count'] )
			);
			?>
		</div>

	</div>

<?php endif; ?>
