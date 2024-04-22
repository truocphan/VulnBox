<?php

function stm_lms_settings_google_api_section()
{
    return array(
        'name' => esc_html__('Google API', 'masterstudy-lms-learning-management-system'),
        'label' => esc_html__('Google API Settings', 'masterstudy-lms-learning-management-system'),
        'icon' => 'fab fa-google',
        'fields' => array(
            'recaptcha_site_key' => array(
                'type' => 'text',
                'label' => esc_html__('Recaptcha Site Key', 'masterstudy-lms-learning-management-system'),
            ),
            'recaptcha_private_key' => array(
                'type' => 'text',
                'label' => esc_html__('Recaptcha Private Key', 'masterstudy-lms-learning-management-system'),
            ),
        )
    );
}
