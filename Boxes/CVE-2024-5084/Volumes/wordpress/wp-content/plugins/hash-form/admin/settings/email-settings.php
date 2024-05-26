<?php
defined('ABSPATH') || die();

$image_id = '';
$image = '';
if (isset($settings['header_image'])) {
    $image_id = $settings['header_image'];
    $image = wp_get_attachment_image_src($settings['header_image'], 'full');
    $image = isset($image[0]) ? esc_attr($image[0]) : '';
}
?>
<div class="hf-form-container hf-grid-container">
    <div class="hf-settings-row hf-grid-container">
        <label class="hf-setting-label"><?php esc_html_e('Header Image', 'hash-form'); ?></label>
        <div class="hf-grid-3">
            <div class="hf-image-preview">
                <input type="hidden" class="hf-image-id" name="hashform_settings[header_image]" id="header_image" value="<?php echo esc_attr($image_id); ?>"/>

                <div class="hf-image-preview-wrap<?php echo ($image ? '' : ' hf-hidden'); ?>">
                    <div class="hf-image-preview-box">
                        <img id="hf-image-preview-header-image" src="<?php echo esc_attr($image); ?>" />
                    </div>
                    <button type="button" class="button hf-remove-image">
                        <span class="mdi mdi-trash-can-outline"></span>
                        <?php esc_html_e('Delete', 'hash-form'); ?>
                    </button>
                </div>

                <button type="button" class="button hf-choose-image<?php echo ($image ? ' hf-hidden' : ''); ?>">
                    <span class="mdi mdi-tray-arrow-up"></span>
                    <?php esc_attr_e('Upload image', 'hash-form'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="hf-settings-row hf-grid-container">
        <div class="hf-grid-3">
            <label class="hf-setting-label"><?php esc_html_e('Email Template', 'hash-form'); ?></label>
            <select id="hf-settings-email-template" name="hashform_settings[email_template]">
                <option value="template1" <?php selected($settings['email_template'], 'template1'); ?>><?php esc_html_e('Template 1', 'hash-form'); ?></option>
                <option value="template2" <?php selected($settings['email_template'], 'template2'); ?>><?php esc_html_e('Template 2', 'hash-form'); ?></option>
                <option value="template3" <?php selected($settings['email_template'], 'template3'); ?>><?php esc_html_e('Template 3', 'hash-form'); ?></option>
            </select>
        </div>
    </div>

    <div class="hf-settings-row hf-grid-container">
        <div class="hf-grid-3">
            <label class="hf-setting-label"><?php esc_html_e('Send Test Email to', 'hash-form'); ?></label>
            <div class="hf-flex">
                <input type="email" id="hf-test-email" />
                <a href="#" class="button button-secondary" id="hf-test-email-button"><?php esc_attr_e('Send Test Email', 'hash-form'); ?></a>
            </div>
            <div class="hf-test-email-notice"></div>
        </div>
    </div>
</div>