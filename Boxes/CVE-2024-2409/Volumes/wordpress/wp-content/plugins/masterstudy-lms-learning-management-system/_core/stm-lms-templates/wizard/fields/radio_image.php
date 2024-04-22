<?php

/**
 * @var $model
 * @var $value
 * @var $image
 * @var $label
 *
 */

$ext = pathinfo($image, PATHINFO_EXTENSION);

$classes = array('stm_lms_splash_wizard__field_img_radio');
$classes[] = ($ext === 'svg') ? 'svg-radio' : 'img-radio';
?>

<label class=" <?php echo esc_attr(implode(' ', $classes)) ?>">
    <input type="radio" v-model="<?php echo esc_html($model); ?>" value="<?php echo esc_html($value); ?>"/>
    <div class="image_radio">
        <div class="img">
            <?php if ($ext === 'svg'): ?>
                <?php STM_LMS_Helpers::print_svg($image); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(STM_LMS_URL . $image); ?>" />
            <?php endif; ?>
        </div>
        <?php if (!empty($label)): ?>
            <div class="image_radio_label">
                <span></span>
                <?php echo esc_html($label); ?>
            </div>
        <?php endif; ?>
    </div>
</label>
