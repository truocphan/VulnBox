<?php

/**
 * @var $current_user
 * @var $lms_template_current
 */

$position = (!empty($current_user['meta']['position'])) ? $current_user['meta']['position'] : '';
if (empty($position) and STM_LMS_Instructor::is_instructor($current_user['id'])) {
    $position = esc_html__('Instructor', 'masterstudy-lms-learning-management-system');
}
if (empty($position)) $position = esc_html__('Student', 'masterstudy-lms-learning-management-system');

$active = ($lms_template_current === 'stm-lms-user-settings') ? 'float_menu_item_active' : '';

?>

<a href="<?php echo esc_url(STM_LMS_User::settings_url()); ?>"
   class="stm_lms_user_float_menu__user float_menu_item <?php echo esc_attr($active); ?>">

    <div class="stm_lms_user_float_menu__user_avatar">
        <?php echo wp_kses_post($current_user['avatar']); ?>
    </div>

    <div class="stm_lms_user_float_menu__user_info">
        <h3><?php echo esc_html($current_user['login']); ?></h3>
        <span><?php echo esc_html($position); ?></span>
    </div>

    <div class="stm_lms_user_float_menu__user_settings">
        <i class="lnr lnr-cog"></i>
    </div>

</a>
