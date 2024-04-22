<?php
/**
 * @var $id
 */

$enable_title = STM_LMS_Options::get_option('enable_sticky_title', false);

if ($enable_title): ?>

    <div class="stm_lms_course_sticky_panel__title heading_font">
        <?php the_title(); ?>
    </div>

<?php endif;