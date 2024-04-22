<?php
$course_id = get_the_ID();
stm_lms_register_style( 'reviews' );

$reviews = get_post_meta( $course_id, 'udemy_rating_distribution', true );
$average = get_post_meta( $course_id, 'udemy_avg_rating', true );
$total   = get_post_meta( $course_id, 'udemy_num_reviews', true );

if ( ! empty( $reviews ) && ! empty( $average ) && ! empty( $total ) ) :
	$average = round( $average, 1 );
	$percent = $average * 100 / 5;
	$marks   = array(
		'5' => 0,
		'4' => 0,
		'3' => 0,
		'2' => 0,
		'1' => 0,
	);
	foreach ( $reviews as $review ) {
		$marks[ $review['rating'] ] = $review['count'];
	}
	?>
	<div class="clearfix stm_lms_average__rating">

		<!-- Reviews Average ratings -->
		<div class="average_rating">
			<div class="average_rating_unit heading_font">
				<div class="average_rating_value"><?php echo esc_attr( $average ); ?></div>
				<div class="average-rating-stars">
					<div class="star-rating"
							title="<?php /* translators: %s Average Rating */ printf( esc_html__( 'Rated %s out of 5', 'masterstudy-lms-learning-management-system-pro' ), esc_attr( $average ) ); ?>">
						<span style="width:<?php echo esc_attr( $percent ); ?>%">
							<strong class="rating"><?php echo esc_attr( $average ); ?></strong>
							<?php esc_html_e( 'out of 5', 'masterstudy-lms-learning-management-system-pro' ); ?>
						</span>
					</div>
				</div>
				<div class="average_rating_num"><?php /* translators: %s Rating Total */ printf( esc_html__( '%s Ratings', 'masterstudy-lms-learning-management-system-pro' ), esc_attr( $total ) ); ?></div>
			</div>
		</div>
		<!-- Reviews Average ratings END -->

		<!-- Review detailed Rating -->
		<div class="detailed_rating">
			<h4 class="rating_sub_title"><?php esc_html_e( 'Detailed Rating', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>
			<table class="detail_rating_unit normal_font">
				<tbody>
				<?php foreach ( $marks as $mark => $mark_count ) : ?>
					<tr class="stars_5">
						<td class="key"><?php /* translators: %s Mark */ printf( esc_html__( 'Stars %s', 'masterstudy-lms-learning-management-system-pro' ), esc_attr( $mark ) ); ?></td>
						<td class="bar">
							<div class="full_bar">
								<div class="bar_filler" style="width:<?php echo esc_attr( $mark_count * 100 / $total ); ?>%"></div>
							</div>
						</td>
						<td class="value"><?php echo esc_attr( $mark_count ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- Review detailed Rating END -->
	</div>
<?php else : ?>
	<?php STM_LMS_Templates::show_lms_template( 'course/parts/tabs/reviews' ); ?>
	<?php
endif;
