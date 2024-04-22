<?php
$reviews = get_post_meta(get_the_ID(), 'course_marks', true);
if (!empty($reviews)):
    $rates = STM_LMS_Course::course_average_rate($reviews);
	$average = $rates['average'];
	$percent = $rates['percent'];

	?>
    <div class="average-rating-stars">
        <div class="average-rating-stars__top">
            <div class="star-rating"
                 title="<?php printf(esc_html__('Rated %s out of 5', 'masterstudy-lms-learning-management-system'), $average); ?>">
            <span style="width:<?php echo esc_attr($percent); ?>%">
                <strong class="rating"><?php echo esc_attr($average); ?></strong>
				<?php esc_html_e('out of 5', 'masterstudy-lms-learning-management-system'); ?>
            </span>
            </div>
            <div class="average-rating-stars__av heading_font"><?php echo floatval($average); ?></div>
        </div>

        <div class="average-rating-stars__reviews">
			<?php printf(_n(
				'%s review',
				'%s reviews',
				count($reviews),
				'masterstudy-lms-learning-management-system'
			), count($reviews)); ?>
        </div>

    </div>

<?php endif; ?>
