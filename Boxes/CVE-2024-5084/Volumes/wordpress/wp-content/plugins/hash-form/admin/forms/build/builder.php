<?php
defined('ABSPATH') || die();
?>

<div id="hf-editor-wrap" class="<?php echo ($has_fields ? 'hf-editor-has-fields' : ''); ?>">
    <ul id="hf-editor-fields" class="hf-editor-sorting inside">
        <?php
        if (!empty($vars['fields'])) {
            $grid_helper = new HashFormGridHelper();
            $vars['count'] = 0;
            foreach ($vars['fields'] as $field) {
                $vars['count'] ++;
                $grid_helper->set_field($field);
                $grid_helper->maybe_begin_field_wrapper();
                $field_obj = HashFormFields::get_field_class($field['type'], $field);
                $field_obj->load_single_field();
                $grid_helper->sync_list_size();
                unset($field);
            }
            $grid_helper->force_close_field_wrapper();
            unset($grid_helper);
        }
        ?>
    </ul>

    <div class="hf-editor-submit-button-wrap hf-submit-btn-align-<?php echo (isset($form->options['submit_btn_alignment']) ? esc_attr($form->options['submit_btn_alignment']) : 'left'); ?>">
        <button id="hf-editor-submit-button" class="hf-editor-submit-button" disabled="disabled">
            <?php echo (isset($form->options['submit_value']) ? esc_html($form->options['submit_value']) : esc_html__('Submit', 'hash-form')); ?>
        </button>
    </div>

    <div class="hf-no-fields">
        <span>
            <h3><?php esc_html_e('Add Fields Here', 'hash-form'); ?></h3>
            <p><?php esc_html_e('Click or drag a field from the sidebar to add it to your form', 'hash-form'); ?></p>
        </span>
    </div>
</div>

<div id="hf-bulk-edit-modal">
    <div class="postbox">
        <div class="hf-bulk-edit-modal-header">
            <h2>
                <?php esc_html_e('Bulk Edit Options', 'hash-form'); ?>
            </h2>
            <a class="dismiss" title="<?php esc_attr_e('Close', 'hash-form'); ?>"><span class="mdi mdi-window-close"></span></a>
        </div>

        <div class="hf-bulk-edit-body hf-editor-grid-container">
            <div class="hf-grid-8 hf-bulk-edit-content">
                <p>
                    <?php esc_html_e('Edit or add field options (one per line)', 'hash-form'); ?>
                </p>
                <textarea name="hashform_bulk_options" id="hf-bulk-options"></textarea>
                <input type="hidden" value="" id="bulk-field-id" />
                <input type="hidden" value="" id="bulk-option-type" />
            </div>
            <div class="hf-grid-4 hf-bulk-edit-sidebar">
                <h3>
                    <?php esc_html_e('Insert Presets', 'hash-form'); ?>
                </h3>
                <ul class="hf-default-opts">
                    <?php
                    $preset_options = HashFormHelper::get_options_presets();
                    foreach ($preset_options as $class => $option) {
                        ?>
                        <li class="<?php echo esc_attr($class); ?>">
                            <a href="#" class="hf-insert-preset" data-opts="<?php echo esc_attr(wp_json_encode($option['options'])); ?>">
                                <?php echo esc_html($option['label']); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="hf-bulk-edit-modal-footer">
            <button class="button" id="hf-update-bulk-options">
                <?php esc_attr_e('Update Options', 'hash-form'); ?>
            </button>
        </div>
    </div>
</div>
