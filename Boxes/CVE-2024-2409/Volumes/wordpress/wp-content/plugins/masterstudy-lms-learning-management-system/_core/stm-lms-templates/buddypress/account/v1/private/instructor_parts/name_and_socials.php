<?php
/**
 * @var $current_user
 */

stm_lms_register_style('user_info_top');

?>

<div class="stm_lms_user_info_top">

	<h1>

        <?php echo esc_attr($current_user['login']); ?>

        <?php if (!empty($current_user['meta']['position'])): ?>
            <span class="pos"><?php echo sanitize_text_field($current_user['meta']['position']); ?></span>
        <?php endif; ?>

    </h1>

	<?php STM_LMS_Templates::show_lms_template('buddypress/account/v1/private/parts/socials', array('current_user' => $current_user)); ?>

</div>