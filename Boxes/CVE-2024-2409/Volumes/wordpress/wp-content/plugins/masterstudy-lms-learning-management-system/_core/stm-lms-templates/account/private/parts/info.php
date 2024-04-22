<?php
/**
 * @var $current_user
 */

?>

<div class="stm_lms_user_side">

    <?php STM_LMS_Templates::show_lms_template('account/private/parts/avatar_edit', compact('current_user')); ?>

    <h3 class="student_name stm_lms_update_field__first_name"><?php echo esc_attr($current_user['login']); ?></h3>

    <h5 class="student_name_pos"><?php esc_html_e('Student', 'masterstudy-lms-learning-management-system'); ?></h5>

    <?php STM_LMS_User::become_instructor_block($current_user); ?>

    <?php STM_LMS_Templates::show_lms_template('account/private/parts/enterprise_form'); ?>

</div>