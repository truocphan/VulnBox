<div class="ms_lms_instructors_carousel__item_rating">
	<div class="ms_lms_instructors_carousel__item_rating_stars">
		<div class="ms_lms_instructors_carousel__item_rating_stars_filled" style="width: <?php echo floatval( $rating['percent'] ); ?>%;"></div>
	</div>
	<?php if ( ! empty( $show_reviews_count ) && ! empty( $rating['average'] ) ) { ?>
		<div class="ms_lms_instructors_carousel__item_rating_quantity">
			<?php echo number_format( $rating['average'], 1, '.', '' ) . ' (' . esc_html( $rating['marks_num'] ) . ')'; ?>
		</div>
	<?php } ?>
</div>
