<?php
/**
 * @var $bundle
 */

$bundle_rating       = STM_LMS_Course_Bundle::get_bundle_rating( $bundle['id'] );
$bundle_course_price = STM_LMS_Course_Bundle::get_bundle_courses_price( $bundle['id'] );
?>

<?php
if ( ! empty( $bundle_rating ) && ! empty( $bundle_rating['count'] ) ) :
	$average = round( $bundle_rating['average'] / $bundle_rating['count'], 2 );
	$percent = round( $bundle_rating['percent'] / $bundle_rating['count'], 2 );
	?>
	<div class="stm_lms_single_bundle_card__rating heading_font">
		<div class="average-rating-stars__top">
			<div class="star-rating">
			<span style="width:<?php echo esc_attr( $percent ); ?>%">
				<strong class="rating"><?php echo esc_attr( $average ); ?></strong>
			</span>
			</div>
			<div class="average-rating-stars__av heading_font">
				<?php echo esc_html( $average ); ?> (<?php echo esc_html( $bundle_rating['count'] ); ?>)
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="stm_lms_single_bundle_card__price heading_font">
	<span class="bundle_price"><?php echo esc_html( $bundle['price'] ); ?></span>
	<span class="bundle_courses_price">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo STM_LMS_Helpers::display_price( $bundle_course_price );
		?>
	</span>
</div>
