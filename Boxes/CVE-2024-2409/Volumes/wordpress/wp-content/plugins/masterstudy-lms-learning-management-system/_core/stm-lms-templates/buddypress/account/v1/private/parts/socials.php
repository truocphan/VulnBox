<?php
/**
 * @var $current_user
 */

$socials = array('facebook', 'twitter', 'instagram', 'google-plus');
$fields = STM_LMS_User::extra_fields();
$rating = STM_LMS_Instructor::my_rating($current_user);
?>

<div class="stm_lms_user_info_top__wrapper">

    <div class="stm_lms_user_info_top__socials">
        <?php foreach ($socials as $social): ?>
            <?php if (!empty($current_user['meta'][$social])): ?>
                <a href="<?php echo esc_url($current_user['meta'][$social]); ?>"
                   target="_blank"
                   class="<?php echo esc_attr($social); ?> stm_lms_update_field__<?php echo esc_attr($social); ?>">
                    <i class="fab fa-<?php echo esc_attr($fields[$social]['icon']) ?>"></i>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($rating['total'])): ?>
        <div class="stm-lms-user_rating">
            <div class="star-rating star-rating__big">
                <span style="width: <?php echo floatval($rating['percent']); ?>%;"></span>
            </div>
            <strong class="rating heading_font"><?php echo floatval($rating['average']); ?></strong>
            <div class="stm-lms-user_rating__total">
                <?php echo sanitize_text_field($rating['total_marks']); ?>
            </div>
        </div>
    <?php endif; ?>

</div>