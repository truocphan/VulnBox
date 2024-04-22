<?php

$values = (!empty($_GET['price'])) ? $_GET['price'] : array();

$statuses = array(
    'free_courses' => esc_html__('Free Courses', 'masterstudy-lms-learning-management-system'),
    'paid_courses' => esc_html__('Paid Courses', 'masterstudy-lms-learning-management-system'),
    'subscription' => esc_html__('Only Subscription', 'masterstudy-lms-learning-management-system'),
);

if (!empty($statuses)) : ?>

    <div class="stm_lms_courses__filter stm_lms_courses__search">

        <div class="stm_lms_courses__filter_heading">
            <h3><?php esc_html_e('Price', 'masterstudy-lms-learning-management-system'); ?></h3>
            <div class="toggler"></div>
        </div>

        <div class="stm_lms_courses__filter_content" style="display: none;">

            <?php foreach ($statuses as $status => $status_label): ?>

                <div class="stm_lms_courses__filter_category">
                    <label class="stm_lms_styled_checkbox">
                    <span class="stm_lms_styled_checkbox__inner">
                        <input type="checkbox"
                               <?php if ( in_array(sanitize_text_field($status), $values) ) echo 'checked="checked"'; ?>
                               value="<?php echo sanitize_text_field($status); ?>"
                               name="price[]"/>
                        <span><i class="fa fa-check"></i> </span>
                    </span>
                        <span><?php echo esc_html($status_label); ?></span>
                    </label>
                </div>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif;