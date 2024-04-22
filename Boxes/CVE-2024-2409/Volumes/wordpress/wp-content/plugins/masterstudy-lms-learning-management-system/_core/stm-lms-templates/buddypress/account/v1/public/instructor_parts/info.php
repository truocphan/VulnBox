<?php
/**
 * @var $current_user
 */

$rating = STM_LMS_Instructor::my_rating($current_user);
?>

<div class="stm_lms_user_side">

    <?php if (!empty($current_user['avatar'])): ?>
        <div class="stm-lms-user_avatar">
            <?php echo wp_kses_post($current_user['avatar']); ?>
        </div>
    <?php endif; ?>

    <?php STM_LMS_Templates::show_lms_template('account/public/instructor_parts/fast_message', compact('current_user')); ?>

</div>