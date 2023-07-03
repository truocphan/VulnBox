<?php

function aft_wp_post_author_default_options()
{
    $default_theme_options = array(
        'awpa_global_title' => esc_html__('About Author', 'wp-post-author'),
        'awpa_global_align' => esc_html__('right', 'wp-post-author'),
        'awpa_global_image_layout' => esc_html__('Round', 'wp-post-author'),
        'awpa_author_posts_link_layout' => esc_html__('Round', 'wp-post-author'),
        'awpa_social_icon_layout' => esc_html__('Round', 'wp-post-author'),
        'awpa_global_show_role' => true,
        'awpa_global_show_email' => true,
        'awpa_highlight_color' => '#b81e1e',
        'awpa_also_visibile_in_awpa_user_form_build' => true,
        'awpa_hide_from_post_content' => false,
        'awpa_custom_css' => '',
    );

    return apply_filters('aft_wp_post_author_default_options', $default_theme_options);
}

function aft_wp_post_author_get_options($key = '')
{
    $options = get_option('awpa_setting_options');
    $default_options = aft_wp_post_author_default_options();

    if (!empty($key)) {
        if (isset($options[$key])) {
            return $options[$key];
        }
        return isset($default_options[$key]) ? $default_options[$key] : false;
    } else {
        if (!is_array($options)) {
            $options = array();
        }
        return array_merge($default_options, $options);
    }
}

function awpa_post_author_delete_options($key = '')
{
    if (!empty($key)) {
        $options = aft_wp_post_author_get_options();
        if (isset($options[$key])) {
            unset($options[$key]);
            return update_option('awpa_setting_options', $options);
        }
    } else {
        return delete_option('awpa_setting_options');
    }
}

function awpa_post_author_set_options($settings)
{
    $setting_keys = array_keys(aft_wp_post_author_default_options());
    $options = array();
    foreach ($settings as $key => $value) {
        if (in_array($key, $setting_keys)) {
            switch ($key) {
                case 'awpa_global_title' || 'awpa_highlight_color' || 'awpa_also_visibile_in_awpa_user_form_build' || 'awpa_custom_css' || 'awpa_hide_from_post_content':
                    $value = sanitize_text_field($value);
                    break;
                case 'bool': //extra for reference
                    $value = (bool) $value;
                    break;
                case 'awpa_global_align' || 'awpa_global_image_layout' || 'awpa_global_show_role' ||
                    'awpa_global_show_email' || 'awpa_author_posts_link_layout' || 'awpa_social_icon_layout':
                    $value = sanitize_key($value);
                    break;
                default:
                    $value = sanitize_key($value);
                    break;
            }
            $options[$key] = $value;
        }
    }
    return update_option('awpa_setting_options', $options);
}

function aft_wp_post_author_integration_setting_default_options()
{
    $default_integration_options = array(
        'enable_recaptcha' => false,
        'recaptcha_version' => 'v2',
        'site_key' => '',
        'secret_key' => '',
    );
    return apply_filters('aft_wp_post_author_integration_setting_default_options', $default_integration_options);
}

function aft_wp_post_author_integration_setting($key = '')
{
    $options = get_option('awpa_integrations_setting_options');
    $default_options = aft_wp_post_author_integration_setting_default_options();

    if (!empty($key)) {
        if (isset($options[$key])) {
            return $options[$key];
        }
        return isset($default_options[$key]) ? $default_options[$key] : false;
    } else {
        if (!is_array($options)) {
            $options = array();
        }
        return array_merge($default_options, $options);
    }
}

function aft_wp_post_author_set_integration_settings($settings)
{
    $setting_keys = array_keys(aft_wp_post_author_integration_setting_default_options());
    $options = array();
    foreach ($settings as $key => $value) {
        if (in_array($key, $setting_keys)) {
            switch ($key) {
                case 'recaptcha_version' || 'secret_key' || 'site_key':
                    $value = sanitize_text_field($value);
                    break;
                case 'enable_recaptcha': //extra for reference
                    $value = (bool) $value;
                    break;
                default:
                    $value = sanitize_key($value);
                    break;
            }
            $options[$key] = $value;
        }
    }
    update_option('awpa_integrations_setting_options', $options);
}


/*
 * Social Login Integration Setting
 */
function aft_wpa_social_login_integration_setting_default_options()
{
    $default_integration_options = array(
        'enable_facebook_login' => false,
        'app_id' => '',
        'app_secret' => '',
        'enable_google_login' => false,
        'client_id' => '',
    );
    return apply_filters('aft_wpa_social_login_integration_setting_default_options', $default_integration_options);
}
function aft_wpa_social_login_integration_setting($key = '')
{
    $options = get_option('aft_wpa_social_login_integrations_setting_options');
    $default_options = aft_wpa_social_login_integration_setting_default_options();

    if (!empty($key)) {
        if (isset($options[$key])) {
            return $options[$key];
        }
        return isset($default_options[$key]) ? $default_options[$key] : false;
    } else {
        if (!is_array($options)) {
            $options = array();
        }
        return array_merge($default_options, $options);
    }
}

function aft_wpa_set_social_login_integration_settings($settings)
{
    $setting_keys = array_keys(aft_wpa_social_login_integration_setting_default_options());
    $options = array();
    foreach ($settings as $key => $value) {
        if (in_array($key, $setting_keys)) {
            switch ($key) {
                case 'app_id' || 'app_secret' || 'client_id':
                    $value = sanitize_text_field($value);
                    break;
                case 'enable_facebook_login' || 'enable_google_login': //extra for reference
                    $value = (bool) $value;
                    break;
                default:
                    $value = sanitize_key($value);
                    break;
            }
            $options[$key] = $value;
        }
    }
    update_option('aft_wpa_social_login_integrations_setting_options', $options);
}


/*
 * Mailchimp Integration Setting
 */

function aft_wpa_mail_settings_default_options()
{
    $default_options = array(
        'awpa_mail_setting' => 'default',
        // 'enable_mail' => false,
        // 'default_wp_mail' => true,
        'email' => '',
        'password' => '',
        'server_name' => '',
        'authentication' => 'ssl',
        'port_number' => '',
        'from_name' => '',
    );
    return apply_filters('aft_wpa_mail_settings_default_options', $default_options);
}
function awpa_mail_setting($key = '')
{
    $options = get_option('aft_wpa_mail_settings');
    $default_options = aft_wpa_mail_settings_default_options();

    if (!empty($key)) {
        if (isset($options[$key])) {
            return $options[$key];
        }
        return isset($default_options[$key]) ? $default_options[$key] : false;
    } else {
        if (!is_array($options)) {
            $options = array();
        }
        return array_merge($default_options, $options);
    }
}

function awpa_mail_settings($settings)
{
    $setting_keys = array_keys(aft_wpa_mail_settings_default_options());
    $options = array();
    foreach ($settings as $key => $value) {
        if (in_array($key, $setting_keys)) {
            switch ($key) {
                case 'email' || 'password' || 'server_name' || 'authentication' || 'port_number' || 'awpa_mail_setting' || 'from_name':
                    $value = sanitize_text_field($value);
                    break;
                default:
                    $value = sanitize_key($value);
                    break;
            }
            $options[$key] = $value;
        }
    }
    update_option('aft_wpa_mail_settings', $options);
}


function awpa_author_metabox_default_options()
{
    $default_options = array(
        'enable_author_metabox' => false
    );
    return apply_filters('awpa_author_metabox_default_options', $default_options);
}
function awpa_get_author_metabox_setting($key = '')
{
    $options = get_option('awpa_author_metabox_integration');
    $default_options = awpa_author_metabox_default_options();

    if (!empty($key)) {
        if (isset($options[$key])) {
            return $options[$key];
        }
        return isset($default_options[$key]) ? $default_options[$key] : false;
    } else {
        if (!is_array($options)) {
            $options = array();
        }
        return array_merge($default_options, $options);
    }
}

function awpa_set_author_metabox_setting($settings)
{
    $setting_keys = array_keys(awpa_author_metabox_default_options());
    $options = array();
    foreach ($settings as $key => $value) {
        if (in_array($key, $setting_keys)) {
            switch ($key) {
                case 'enable_author_metabox':
                    $value = (bool)$value;
                    break;
                default:
                    $value = sanitize_key($value);
                    break;
            }
            $options[$key] = $value;
        }
    }
    update_option('awpa_author_metabox_integration', $options);
}
