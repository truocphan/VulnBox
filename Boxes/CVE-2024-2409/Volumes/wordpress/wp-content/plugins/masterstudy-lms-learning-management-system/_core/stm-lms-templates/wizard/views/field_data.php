<?php
/**
 * @var $title
 * @var $desc
 */

$classes = array();

if(empty($desc)) $classes[] = 'no-desc';
?>

<div class="stm_lms_splash_wizard__field_data <?php echo esc_attr(implode(' ', $classes)) ?>">

    <?php if (!empty($title)): ?>
        <h5>
            <?php echo esc_html($title); ?>
        </h5>
    <?php endif; ?>

    <?php if (!empty($desc)): ?>
        <p>
            <?php echo esc_html($desc); ?>
        </p>
    <?php endif; ?>

</div>