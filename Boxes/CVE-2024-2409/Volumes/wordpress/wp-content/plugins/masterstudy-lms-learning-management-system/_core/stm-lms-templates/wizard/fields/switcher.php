<?php

/**
 * @var $model
 * @var $desc
 *
 */

?>

<div class="desc-wrapper">

    <?php if(!empty($desc)): ?>
        <div class="desc"><?php echo esc_html($desc); ?></div>
    <?php endif; ?>

    <div class="stm_lms_splash_wizard__field_switcher desc-field">
        <input type="checkbox" v-model="<?php echo esc_html($model); ?>"/>
        <div></div>
    </div>

</div>