<div class="stm_lms_course__image">
    <?php the_post_thumbnail('img-870-440'); ?>
</div>

<?php STM_LMS_Templates::show_lms_template(
    'course/parts/course_file',
    array('id' => get_the_ID())
); ?>

<div class="stm_lms_course__content">
    <?php the_content(); ?>
</div>