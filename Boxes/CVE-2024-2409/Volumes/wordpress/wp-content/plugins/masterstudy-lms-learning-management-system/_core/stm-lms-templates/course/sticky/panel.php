<?php

$id = get_the_ID();

$sticky_panel = STM_LMS_Options::get_option('enable_sticky', false);

$show_panel = (!is_user_logged_in()) || !STM_LMS_User::has_course_access($id, '', false);

if ($sticky_panel && $show_panel):

    stm_lms_register_script('panel/sticky');
    stm_lms_register_style('panel/sticky');

    ?>
    <div class="stm_lms_course_sticky_panel">

        <div class="container">

            <div class="stm_lms_course_sticky_panel__inner">

                <div class="stm_lms_course_sticky_panel__left">

                    <?php STM_LMS_Templates::show_lms_template('course/sticky/title', compact('id')); ?>

                    <div class="stm_lms_course_sticky_panel__panel single_product_after_title">

                        <?php STM_LMS_Templates::show_lms_template('course/sticky/category', compact('id')); ?>

                        <?php STM_LMS_Templates::show_lms_template('course/sticky/teacher', compact('id')); ?>

                        <?php STM_LMS_Templates::show_lms_template('course/sticky/rate', compact('id')); ?>

                    </div>

                </div>

                <div class="stm_lms_course_sticky_panel__right">

                    <?php STM_LMS_Templates::show_lms_template('course/sticky/price', compact('id')); ?>

                    <?php STM_LMS_Templates::show_lms_template('course/sticky/buy', compact('id')); ?>

                </div>

            </div>

        </div>

    </div>

<?php endif;