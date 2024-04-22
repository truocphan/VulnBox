<?php

$redirect_url = get_site_url();

if(is_user_logged_in()) {
    $lms_settings = get_option('stm_lms_settings', array());
    $user_url = (!empty($lms_settings['user_url'])) ? $lms_settings['user_url'] : '/lms-user';
    $redirect_url .= $user_url . '/' .  get_current_user_id();
}

//wp_safe_redirect($redirect_url);