<div class="ms_lms_courses_card_item_info_rating">
	<div class="ms_lms_courses_card_item_info_rating_stars">
		<div class="ms_lms_courses_card_item_info_rating_stars_filled" style="width: <?php echo floatval( $course['rating']['percent'] ); ?>%;"></div>
	</div>
	<div class="ms_lms_courses_card_item_info_rating_quantity">
		<span><?php echo number_format( $course['rating']['average'], 1, '.', '' ); ?></span>
	</div>
</div>
