<?php
stm_lms_register_script('courses');
wp_localize_script('stm-lms-courses', 'courses_view', array(
    'type' => STM_LMS_Options::get_option('courses_view', 'grid')
));

?>

<div class="text-center">
    <a href="#"
       class="btn btn-default stm_lms_load_more_courses"
       data-offset="1"
       data-template="courses/grid"
       data-url=""
       data-args='<?php echo json_encode($args); ?>'>
        <span><?php esc_html_e('Load more', 'masterstudy-lms-learning-management-system') ?></span>
    </a>
</div>