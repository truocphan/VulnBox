<?php
defined('ABSPATH') || die();

$styles = $form->styles ? $form->styles : array();
$style_id = isset($styles['form_style_template']) ? $styles['form_style_template'] : '';
$hashform_styles = get_post_meta($style_id, 'hashform_styles', true);
$submit_class = isset($form->options['submit_btn_alignment']) ? 'hf-submit-btn-align-' . esc_html($form->options['submit_btn_alignment']) : 'hf-submit-btn-align-left';
$submit = isset($form->options['submit_value']) ? esc_html($form->options['submit_value']) : esc_html__('Submit', 'hash-form');
$button_class = array('hf-submit-button');
if (isset($form->options['submit_btn_css_class'])) {
    $button_class[] = esc_attr($form->options['submit_btn_css_class']);
}

$form_title = esc_html($form->name);
$form_description = esc_html($form->description);
$show_title = isset($form->options['show_title']) ? esc_html($form->options['show_title']) : 'on';
$show_description = isset($form->options['show_description']) ? esc_html($form->options['show_description']) : 'off';
$hashform_action = htmlspecialchars_decode(HashFormHelper::get_var('action'));

if (!$hashform_styles) {
    $hashform_styles = HashFormStyles::default_styles();
} else {
    $hashform_styles = HashFormHelper::recursive_parse_args($hashform_styles, HashFormStyles::default_styles());
}
?>

<div class="hf-form-preview" id="hf-container-<?php echo esc_attr($form->id); ?>">
    <?php
    if (empty($values) || !isset($values['fields']) || empty($values['fields'])) {
        ?>
        <div class="hf-form-error">
            <strong><?php esc_html_e('Oops!', 'hash-form'); ?></strong>
            <?php printf(esc_html__('You did not add any fields to your form. %1$sGo back%2$s and add some.', 'hash-form'), '<a href="' . esc_url(admin_url('admin.php?page=hashform&hashform_action=edit&id=' . absint($id))) . '">', '</a>'); ?>
        </div>
        <?php
        return;
    }

    if ($show_title == 'on' && $form_title) {
        ?>
        <h3 class="hf-form-title"><?php echo esc_html($form_title); ?></h3>
        <?php
    }

    if ($show_description == 'on' && $form_description) {
        ?>
        <div class="hf-form-description"><?php echo esc_html($form_description); ?></div>
        <?php
    }
    ?>
    <div class="hf-container">
        <input type="hidden" name="hashform_action" value="create" />
        <input type="hidden" name="form_id" value="<?php echo absint($form->id); ?>" />
        <input type="hidden" name="form_key" value="<?php echo esc_attr($form->form_key); ?>" />
        <input type="hidden" class="hashform-form-conditions" value="<?php echo esc_attr(htmlspecialchars(wp_json_encode(HashFormBuilder::get_show_hide_conditions(absint($form->id))), ENT_QUOTES, 'UTF-8')); ?>" />
        <?php
        wp_nonce_field('hashform_submit_entry_nonce', 'hashform_submit_entry_' . absint($form->id));

        if ($values['fields']) {
            HashFormFields::show_fields($values['fields']);
        }
        ?>
        <div class="hf-submit-wrap <?php echo esc_attr($submit_class); ?>">
            <button class="<?php echo esc_attr(implode(' ', $button_class)) ?>" type="submit" <?php disabled($hashform_action, 'hashform_preview'); ?>><?php echo esc_html($submit); ?></button>
        </div>
    </div>
    <?php
    echo '<style class="hf-style-content">';
    echo '#hf-container-' . absint($form->id) . '{';
    HashFormStyles::get_style_vars($hashform_styles, '');
    echo '}';
    echo '</style>';
    ?>
</div>