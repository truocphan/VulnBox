<?php
$average     = get_post_meta( get_the_ID(), 'udemy_avg_rating', true );
$num_reviews = get_post_meta( get_the_ID(), 'udemy_num_reviews', true );


if ( ! empty( $average ) && ! empty( $num_reviews ) ) :
	$percent = ( $average * 100 ) / 5;

	?>
	<div class="average-rating-stars">
		<div class="average-rating-stars__top">
			<div class="star-rating"
				title="<?php /* translators: %s Average Rating */ printf( esc_html__( 'Rated %s out of 5', 'masterstudy-lms-learning-management-system-pro' ), esc_html( $average ) ); ?>">
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
					/* translators: %s Reviews */
					_n(
						'%s review on Udemy',
						'%s reviews on Udemy',
						$num_reviews,
						'masterstudy-lms-learning-management-system-pro'
					)
				),
				$num_reviews // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</div>

	</div>

<?php else : ?>
	<?php STM_LMS_Templates::show_lms_template( 'course/parts/panel_info/rate' ); ?>
<?php endif; ?>
