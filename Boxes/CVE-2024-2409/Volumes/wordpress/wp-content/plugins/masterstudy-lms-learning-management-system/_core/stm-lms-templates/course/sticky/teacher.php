<?php
/**
 * @var $id
 */

if (STM_LMS_Options::get_option('enable_sticky_teacher', false)): ?>

    <div class="stm_lms_course_sticky_panel__teacher">
        <?php STM_LMS_Templates::show_lms_template('course/parts/panel_info/teacher'); ?>
    </div>

<?php endif;