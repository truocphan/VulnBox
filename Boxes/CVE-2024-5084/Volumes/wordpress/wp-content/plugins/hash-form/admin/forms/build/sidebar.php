<?php
defined('ABSPATH') || die();
?>

<div class="hf-fields-sidebar">
    <div class="hf-fields-container">
        <ul id="hf-fields-tabs" class="hf-fields-tabs">
            <li class="hf-active-tab"><a href="#hf-add-fields-panel" id="hf-add-fields-tab"><?php esc_html_e('Add Fields', 'hash-form'); ?></a></li>
            <li><a href="#hf-options-panel" id="hf-options-tab"><?php esc_html_e('Field Options', 'hash-form'); ?></a></li>
            <li><a href="#hf-meta-panel" id="hf-design-tab"><?php esc_html_e('Form Title', 'hash-form'); ?></a></li>
        </ul>

        <div class="hf-fields-panels">
            <div id="hf-add-fields-panel" class="ht-fields-panel">
                <?php
                HashFormHelper::show_search_box(array(
                    'input_id' => 'field-list',
                    'placeholder' => esc_html__('Search Fields', 'hash-form'),
                    'tosearch' => 'hf-field-box',
                ));
                ?>
                <ul class="hf-fields-list">
                    <?php
                    $registered_fields = HashFormFields::field_selection();
                    foreach ($registered_fields as $field_key => $field_type) {
                        ?>
                        <li class="hf-field-box hashform_<?php echo esc_attr($field_key); ?>" id="<?php echo esc_attr($field_key); ?>">
                            <a href="#" class="hf-add-field" title="<?php echo esc_html($field_type['name']); ?>">
                                <i class="<?php echo esc_attr($field_type['icon']); ?>"></i>
                                <span><?php echo esc_html($field_type['name']); ?></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>

            <div id="hf-options-panel" class="ht-fields-panel">
                <div class="hf-fields-settings">
                    <div class="hf-no-field-placeholder">
                        <div class="hf-no-field-msg"><?php esc_html_e('Select a field to see the options', 'hash-form'); ?></div>
                    </div>
                </div>

                <form method="post" id="hf-fields-form">
                    <input type="hidden" name="id" id="hf-form-id" value="<?php echo esc_attr($values['id']); ?>" />
                    <?php wp_nonce_field('hashform_save_form_nonce', 'hashform_save_form'); ?>
                    <input type="hidden" id="hf-end-form-marker" />
                </form>
            </div>

            <div id="hf-meta-panel" class="ht-fields-panel">
                <form method="post" id="hf-meta-form">
                    <div class="hf-form-container hf-grid-container">
                        <div class="hf-form-row">
                            <label><?php esc_html_e('Form Title', 'hash-form'); ?></label>
                            <input type="text" name="title" value="<?php echo esc_attr($values['name']); ?>">
                        </div>

                        <div class="hf-form-row">
                            <label>
                                <input type="checkbox" name="show_title" value="on" <?php isset($values['show_title']) ? checked($values['show_title'], 'on') : ''; ?> />
                                <?php esc_html_e('Show the form title', 'hash-form'); ?>
                            </label>
                        </div>

                        <div class="hf-form-row">
                            <label><?php esc_html_e('Form Description', 'hash-form'); ?></label>
                            <textarea name="description"><?php echo esc_textarea($values['description']); ?></textarea>
                        </div>

                        <div class="hf-form-row">
                            <label>
                                <input type="checkbox" name="show_description" value="on" <?php isset($values['show_description']) ? checked($values['show_description'], 'on') : ''; ?> />
                                <?php esc_html_e('Show the form description', 'hash-form'); ?>
                            </label>
                        </div>

                        <div class="hf-form-row">
                            <label><?php esc_html_e('Submit Button Text', 'hash-form'); ?></label>
                            <input type="text" name="submit_value" value="<?php echo isset($values['submit_value']) ? esc_attr($values['submit_value']) : ''; ?>" data-changeme="hf-editor-submit-button">
                        </div>

                        <div class="hf-form-row">
                            <label><?php esc_html_e('Form CSS Class', 'hash-form'); ?></label>
                            <input type="text" name="form_css_class" value="<?php echo isset($values['form_css_class']) ? esc_attr($values['form_css_class']) : ''; ?>">
                        </div>

                        <div class="hf-form-row">
                            <label><?php esc_html_e('Submit Button CSS Class', 'hash-form'); ?></label>
                            <input type="text" name="submit_btn_css_class" value="<?php echo isset($values['submit_btn_css_class']) ? esc_attr($values['submit_btn_css_class']) : ''; ?>">
                        </div>

                        <div class="hf-form-row">
                            <label><?php esc_html_e('Submit Button Alignment', 'hash-form'); ?></label>
                            <select name="submit_btn_alignment">
                                <option value="left" <?php isset($values['submit_btn_alignment']) ? selected($values['submit_btn_alignment'], 'left') : ''; ?>>
                                    <?php esc_html_e('Left', 'hash-form'); ?>
                                </option>
                                <option value="right" <?php isset($values['submit_btn_alignment']) ? selected($values['submit_btn_alignment'], 'right') : ''; ?>>
                                    <?php esc_html_e('Right', 'hash-form'); ?>
                                </option>
                                <option value="center" <?php isset($values['submit_btn_alignment']) ? selected($values['submit_btn_alignment'], 'center') : ''; ?>>
                                    <?php esc_html_e('Center', 'hash-form'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="hf-hidden">
                    <?php wp_editor('', 'hf-init-tinymce'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
