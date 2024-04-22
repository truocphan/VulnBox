<?php

$values = (!empty($_GET['instructor'])) ? $_GET['instructor'] : array();

$user_args = array(
    'role' => STM_LMS_Instructor::role(),
    'number' => 0
);

$user_query = new WP_User_Query($user_args);
$results = $user_query->get_results();

$limit = 1;

if (!empty($results)) : ?>

    <div class="stm_lms_courses__filter stm_lms_courses__search">

        <div class="stm_lms_courses__filter_heading">
            <h3><?php esc_html_e('Instructors', 'masterstudy-lms-learning-management-system'); ?></h3>
            <div class="toggler"></div>
        </div>

        <div class="stm_lms_courses__filter_content limited_list" style="display: none;">

            <?php foreach ($results as $index => $user): ?>

                <div class="stm_lms_courses__filter_category" <?php if($index > $limit) {?> style="display: none;" <?php } ?>>
                    <label class="stm_lms_styled_checkbox">
                    <span class="stm_lms_styled_checkbox__inner">
                        <input type="checkbox"
                               <?php if ( in_array(sanitize_text_field($user->ID), $values) ) echo 'checked="checked"'; ?>
                               value="<?php echo sanitize_text_field($user->ID); ?>"
                               name="instructor[]"/>
                        <span><i class="fa fa-check"></i> </span>
                    </span>
                        <span><?php echo esc_html(STM_LMS_User::display_name($user)); ?></span>
                    </label>
                </div>

            <?php endforeach; ?>

            <?php if(count($results) > $limit): ?>
                <div class="reveal_limited">
                    <i class="lnricons-plus-circle"></i>
                    <span><?php esc_html_e('Show more', 'masterstudy-lms-learning-management-system'); ?></span>
                </div>
            <?php endif; ?>

        </div>

    </div>

<?php endif;