<?php
wp_enqueue_script('vue-resource.js');
stm_lms_register_style('reviews');
stm_lms_register_script('reviews');

wp_add_inline_script('vue-resource.js',
	'var stm_lms_post_id = ' . get_the_ID());

$reviews = get_post_meta(get_the_ID(), 'course_marks', true);
?>


<?php if (!empty($reviews)):
	$average = round(array_sum($reviews) / count($reviews), 1);
	$percent = $average * 100 / 5;
	$marks = array(
		'5' => 0,
		'4' => 0,
		'3' => 0,
		'2' => 0,
		'1' => 0,
	);
	foreach ($reviews as $review) {
		$marks[$review]++;
	}
	?>
    <div class="clearfix stm_lms_average__rating">

        <!-- Reviews Average ratings -->
        <div class="average_rating">
            <div class="average_rating_unit heading_font">
                <div class="average_rating_value"><?php echo esc_attr($average); ?></div>
                <div class="average-rating-stars">
                    <div class="star-rating"
                         title="<?php printf(esc_html__('Rated %s out of 5', 'masterstudy-lms-learning-management-system'), $average); ?>">
						<span style="width:<?php echo esc_attr($percent); ?>%">
							<strong class="rating"><?php echo esc_attr($average); ?></strong>
							<?php esc_html_e('out of 5', 'masterstudy-lms-learning-management-system'); ?>
						</span>
                    </div>
                </div>
                <div class="average_rating_num"><?php printf(esc_html__('%s Ratings', 'masterstudy-lms-learning-management-system'), count($reviews)); ?></div>
            </div>
        </div>
        <!-- Reviews Average ratings END -->

        <!-- Review detailed Rating -->
        <div class="detailed_rating">
            <h4 class="rating_sub_title"><?php esc_html_e('Detailed Rating', 'masterstudy-lms-learning-management-system'); ?></h4>
            <table class="detail_rating_unit normal_font">
                <tbody>
				<?php foreach ($marks as $mark => $mark_count): ?>
                    <tr class="stars_5">
                        <td class="key"><?php printf(esc_html__('Stars %s', 'masterstudy-lms-learning-management-system'), $mark) ?></td>
                        <td class="bar">
                            <div class="full_bar">
                                <div class="bar_filler"
                                     style="width:<?php echo esc_attr($mark_count * 100 / count($reviews)) ?>%"></div>
                            </div>
                        </td>
                        <td class="value"><?php echo esc_attr($mark_count); ?></td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Review detailed Rating END -->
    </div>

    <div id="stm-lms-reviews" v-bind:class="{'loading' : loading}">
        <div class="stm-lms-reviews">
            <div class="stm-lms-reviews-single" v-for="(review, id) in reviews">
                <div class="stm-lms-reviews-top">
                    <h4>{{ review.user }}</h4>
                    <div class="stm-lms-ago">
                        {{ review.time }}
                    </div>
                </div>
                <div class="media">
                    <div class="media-left media-top">
                        <div class="testimonial-media-unit">
                            <img v-bind:src="review.avatar_url" width="96" height="96"
                                 class="testimonial-media-unit-rounded"/>
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="star-rating">
                            <span v-bind:style="{width: ((review.mark * 100) / 5) +'%'}">&nbsp;</span>
                        </div>
                        <p v-html="review.content" v-if="review.content"></p>
                    </div>
                </div>

            </div>

            <a @click="getReviews()" v-if="!total" class="btn btn-default" v-bind:class="{'loading' : loading}">
                <span><?php esc_html_e('Show more', 'masterstudy-lms-learning-management-system'); ?></span>
            </a>
        </div>
    </div>
<?php else: ?>
    <h4><?php esc_html_e('Be the first to add a review.', 'masterstudy-lms-learning-management-system'); ?></h4>
<?php endif; ?>

<?php STM_LMS_Templates::show_lms_template('course/parts/add_review', array('post_id' => get_the_ID()));
