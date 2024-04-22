<?php
/**
 * @var $current_user
 */

stm_lms_register_style('edit_account');
$data = json_encode($current_user);
?>

<div class="stm-lms-user-avatar-edit ">
    <?php $my_avatar = get_user_meta($current_user['id'], 'stm_lms_user_avatar', true); ?>
    <?php if (!empty($current_user['avatar'])): ?>
        <div class="stm-lms-user_avatar">
            <?php echo wp_kses_post($current_user['avatar']); ?>
        </div>
    <?php endif; ?>
</div>