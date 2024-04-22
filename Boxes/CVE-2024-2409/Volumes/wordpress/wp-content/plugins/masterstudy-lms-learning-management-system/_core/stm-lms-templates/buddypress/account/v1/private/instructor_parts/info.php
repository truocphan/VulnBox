<?php
/**
 * @var $current_user
 */

stm_lms_register_style('edit_account');
stm_lms_register_script('edit_account', array('vue.js', 'vue-resource.js'));
$data = json_encode($current_user);
wp_add_inline_script('stm-lms-edit_account',
    "var stm_lms_edit_account_info = {$data}");
$rating = STM_LMS_Instructor::my_rating();
?>

<div class="stm_lms_user_side">

    <div class="stm-lms-user-avatar-edit ">
        <?php $my_avatar = get_user_meta($current_user['id'], 'stm_lms_user_avatar', true); ?>
        <input type="file"/>
        <?php if (!empty($my_avatar)): ?>
            <i class="lnricons-cross delete_avatar"></i>
        <?php endif; ?>
        <i class="lnricons-pencil" data-text="<?php esc_attr_e('Change photo', 'masterstudy-lms-learning-management-system'); ?>"></i>
        <?php if (!empty($current_user['avatar'])): ?>
            <div class="stm-lms-user_avatar">
                <?php echo wp_kses_post($current_user['avatar']); ?>
            </div>
        <?php endif; ?>
    </div>


    <div class="stm_lms_profile_buttons_set 33">
        <div class="stm_lms_profile_buttons_set__inner">

            <?php do_action('stm_lms_before_profile_buttons_all', $current_user); ?>

            <?php STM_LMS_Templates::show_lms_template('account/private/parts/settings_button', array('current_user' => $current_user)); ?>

            <?php do_action('stm_lms_after_profile_buttons'); ?>

            <?php do_action('stm_lms_after_profile_buttons_all'); ?>

        </div>
    </div>


</div>