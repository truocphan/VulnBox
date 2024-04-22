<?php
/**
 * @var $current_user
 * @var $title
 * @var $socials
 */

if(empty($current_user)) $current_user = STM_LMS_User::get_current_user();

stm_lms_register_style('user_info_top');

if (empty($title)) $title = esc_html__('My profile', 'masterstudy-lms-learning-management-system');

?>

<div class="stm_lms_user_info_top">

    <h3><?php echo stm_lms_filtered_output($title); ?></h3>

    <?php do_action('stm_lms_user_info_top', $current_user); ?>

    <?php
    if (!empty($socials)) {
        STM_LMS_Templates::show_lms_template('account/private/parts/socials', array('current_user' => $current_user));
    } ?>

</div>

<div class="multiseparator"></div>