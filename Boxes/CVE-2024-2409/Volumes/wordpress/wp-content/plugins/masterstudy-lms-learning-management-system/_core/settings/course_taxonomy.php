<?php

add_filter('stm_wpcfto_term_meta_fields', function ($fields) {
    $fields['stm_lms_course_taxonomy'] = array(
        'course_image' => array(
            'label' => esc_html__('Category Image', 'masterstudy-lms-learning-management-system'),
            'type' => 'image',
        ),
        'course_icon' => array(
            'label' => esc_html__('Category Icon', 'masterstudy-lms-learning-management-system'),
            'type' => 'icon',
        ),
        'course_color' => array(
            'label' => esc_html__('Category Color', 'masterstudy-lms-learning-management-system'),
            'type' => 'color',
        ),
    );

    return $fields;
}, -1, 1);