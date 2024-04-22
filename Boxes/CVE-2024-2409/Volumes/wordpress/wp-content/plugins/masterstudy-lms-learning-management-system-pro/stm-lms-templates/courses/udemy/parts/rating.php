<?php
/**
 * @var $id
 *
 */

$average     = get_post_meta( get_the_ID(), 'udemy_avg_rating', true );
$num_reviews = get_post_meta( get_the_ID(), 'udemy_num_reviews', true );

if ( ! empty( $average ) && ! empty( $num_reviews ) ) :
	$percent = ( $average * 100 ) / 5;
	?>
	<div class="average-rating-stars__top">
		<div class="star-rating">
			<span style="width: <?php echo esc_attr( $percent ); ?>%">
				<strong class="rating"><?php echo esc_html( $average ); ?></strong>
			</span>
		</div>
	</div>

	<?php
else :

	$rating = get_post_meta( $id, 'course_marks', true );

	if ( ! empty( $rating ) ) {
		$rates   = STM_LMS_Course::course_average_rate( $rating );
		$average = $rates['average'];
		$percent = $rates['percent'];
		$total   = count( $rating );
	}

	if ( ! empty( $average ) ) :
		?>
		<div class="average-rating-stars__top">
			<div class="star-rating">
			<span style="width: <?php echo esc_attr( $percent ); ?>%">
				<strong class="rating"><?php echo esc_html( $average ); ?></strong>
			</span>
			</div>
		</div>
		<?php
	endif;
endif;
