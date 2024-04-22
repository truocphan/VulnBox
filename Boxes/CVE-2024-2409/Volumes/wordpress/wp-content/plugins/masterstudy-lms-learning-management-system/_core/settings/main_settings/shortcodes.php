<?php

function stm_lms_settings_shortcodes_section()
{
    return array(
        'name' => esc_html__('Shortcodes', 'masterstudy-lms-learning-management-system'),
        'label' => esc_html__('Shortcodes Settings', 'masterstudy-lms-learning-management-system'),
        'icon' => 'fas fa-file-code',
        'fields' => array(
            'stm_lms_shortcodes' => array(
                'type' => 'stm_lms_shortcodes',
            ),
        )
    );
}
