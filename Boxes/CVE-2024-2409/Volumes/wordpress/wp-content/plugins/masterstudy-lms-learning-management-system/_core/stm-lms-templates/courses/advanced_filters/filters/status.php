<?php

$values = (!empty($_GET['status'])) ? $_GET['status'] : array();

$statuses = array(
    'featured' => esc_html__('Featured', 'masterstudy-lms-learning-management-system'),
    'hot' => esc_html__('Hot', 'masterstudy-lms-learning-management-system'),
    'new' => esc_html__('New', 'masterstudy-lms-learning-management-system'),
    'special' => esc_html__('Special', 'masterstudy-lms-learning-management-system'),
);

if (!empty($statuses)) : ?>

    <div class="stm_lms_courses__filter stm_lms_courses__search">

        <div class="stm_lms_courses__filter_heading">
            <h3><?php esc_html_e('Status', 'masterstudy-lms-learning-management-system'); ?></h3>
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
                               name="status[]"/>
                        <span><i class="fa fa-check"></i> </span>
                    </span>
                        <span><?php echo esc_html($status_label); ?></span>
                    </label>
                </div>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif;