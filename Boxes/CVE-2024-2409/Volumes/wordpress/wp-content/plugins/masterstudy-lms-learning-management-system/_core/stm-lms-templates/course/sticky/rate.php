<?php
/**
 * @var $id
 */

$rating = get_post_meta($id, 'course_marks', true);

if (STM_LMS_Options::get_option('enable_sticky_rating', false) and !empty($rating)): ?>

    <div class="stm_lms_course_sticky_panel__rating">
        <?php STM_LMS_Templates::show_lms_template('course/parts/panel_info/rate'); ?>
    </div>

<?php endif;