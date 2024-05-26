<?php
defined('ABSPATH') || die();
?>

<div class="hf-fields-settings hf-hidden hf-fields-type-<?php echo esc_attr($field_type); ?>" id="hf-fields-settings-<?php echo esc_attr($field_id); ?>" data-fid="<?php echo esc_attr($field_id); ?>">
    <input type="hidden" name="hf-form-submitted[]" value="<?php echo absint($field_id); ?>" />
    <input type="hidden" name="field_options[field_order_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['field_order']); ?>"/>
    <input type="hidden" name="field_options[grid_id_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['grid_id']); ?>" id="hf-grid-class-<?php echo esc_attr($field_id); ?>" />

    <div class="hf-field-panel-header">
        <h3><?php printf(esc_html__('%s Field', 'hash-form'), esc_html($type_name)); ?></h3>
        <div class="hf-field-panel-id">(ID <?php echo esc_html($field_id); ?>)</div>
    </div>

    <div class="hf-form-container hf-grid-container">
        <?php
        if ($field_type === 'captcha' && !HashFormFieldCaptcha::should_show_captcha()) {
            ?>
            <div class="hf-form-row">
                <?php printf(esc_html__('Captchas will not work untill the Site and Secret Keys are set up. Add Keys %1$shere%2$s.', 'hash-form'), '<a href="?page=hashform-settings" target="_blank">', '</a>'); ?>
                <label class="hf-field-desc"><?php printf(esc_html__('Tutorial to %1$sGenerate Site and Secret Keys%2$s', 'hash-form'), '<a href="https://hashthemes.com/articles/generate-site-key-and-secret-key-from-google-recaptcha/" target="_blank">', '</a>'); ?></label>
            </div>
            <?php
        }

        if ($display['label']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Field Label', 'hash-form'); ?> </label>
                <input type="text" name="field_options[name_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['name']); ?>" data-changeme="hf-editor-field-label-text-<?php echo esc_attr($field_id); ?>" data-label-show-hide="hf-label-show-hide" />
            </div>

            <div class="hf-form-row hf-grid-3">
                <label><?php esc_html_e('Label Position', 'hash-form'); ?></label>
                <select name="field_options[label_position_ <?php echo absint($field_id); ?>]">
                    <option value="top" <?php isset($field['label_position']) ? selected($field['label_position'], 'top') : ''; ?>>
                        <?php esc_html_e('Top', 'hash-form'); ?>
                    </option>
                    <option value="left" <?php isset($field['label_position']) ? selected($field['label_position'], 'left') : ''; ?>>
                        <?php esc_html_e('Left', 'hash-form'); ?>
                    </option>
                    <option value="right" <?php isset($field['label_position']) ? selected($field['label_position'], 'right') : ''; ?>>
                        <?php esc_html_e('Right', 'hash-form'); ?>
                    </option>
                    <option value="hide" <?php isset($field['label_position']) ? selected($field['label_position'], 'hide') : ''; ?>>
                        <?php esc_html_e('Hide', 'hash-form'); ?>
                    </option>
                </select>
            </div>

            <div class="hf-form-row hf-grid-3">
                <label><?php esc_html_e('Label Alignment', 'hash-form'); ?></label>
                <select name="field_options[label_alignment_<?php echo absint($field_id); ?>]">
                    <option value="left" <?php selected($field['label_alignment'], 'left'); ?>>
                        <?php esc_html_e('Left', 'hash-form'); ?>
                    </option>
                    <option value="right" <?php selected($field['label_alignment'], 'right'); ?>>
                        <?php esc_html_e('Right', 'hash-form'); ?>
                    </option>
                    <option value="center" <?php selected($field['label_alignment'], 'center'); ?>>
                        <?php esc_html_e('Center', 'hash-form'); ?>
                    </option>
                </select>
            </div>

            <div class="hf-form-row">
                <label for="hf-hide-label-field-<?php echo absint($field_id); ?>">
                    <input id="hf-hide-label-field-<?php echo absint($field_id); ?>" type="checkbox" name="field_options[hide_label_<?php echo absint($field_id); ?>]" value="1" <?php checked((isset($field['hide_label']) && $field['hide_label']), 1); ?> data-label-show-hide-checkbox="hf-label-show-hide" />
                    <?php esc_html_e('Hide Label', 'hash-form'); ?>
                </label>
            </div>
            <?php
        }

        if ($field_type === 'heading') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Select Heading', 'hash-form'); ?></label>
                <select name="field_options[heading_type_<?php echo esc_attr($field_id); ?>]">
                    <option value="h1" <?php isset($field['heading_type']) ? selected($field['heading_type'], 'h1') : ''; ?>>
                        <?php esc_html_e('H1', 'hash-form'); ?>
                    </option>
                    <option value="h2" <?php isset($field['heading_type']) ? selected($field['heading_type'], 'h2') : ''; ?>>
                        <?php esc_html_e('H2', 'hash-form'); ?>
                    </option>
                    <option value="h3" <?php isset($field['heading_type']) ? selected($field['heading_type'], 'h3') : ''; ?> >
                        <?php esc_html_e('H3', 'hash-form'); ?>
                    </option>
                    <option value="h4" <?php isset($field['heading_type']) ? selected($field['heading_type'], 'h4') : ''; ?>>
                        <?php esc_html_e('H4', 'hash-form'); ?>
                    </option>
                    <option value="h5" <?php isset($field['heading_type']) ? selected($field['heading_type'], 'h5') : ''; ?>>
                        <?php esc_html_e('H5', 'hash-form'); ?>
                    </option>
                    <option value="h6" <?php isset($field['heading_type']) ? selected($field['heading_type'], 'h6') : ''; ?>>
                        <?php esc_html_e('H6', 'hash-form'); ?>
                    </option>
                </select>
            </div>
            <?php
        }

        if ($display['content']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Content', 'hash-form'); ?></label>
                <div class="hf-form-textarea">
                    <textarea name="field_options[content_<?php echo esc_attr($field_id); ?>]" data-changeme="hf-field-<?php echo esc_attr($field_id) ?>"><?php echo isset($field['content']) ? esc_textarea($field['content']) : ''; ?></textarea>
                </div>
            </div>

            <div class="hf-form-row">
                <label><?php esc_html_e('Text Alignment', 'hash-form'); ?></label>
                <select name="field_options[text_alignment_<?php echo esc_attr($field_id); ?>]">
                    <option value="left" <?php isset($field['text_alignment']) ? selected($field['text_alignment'], 'left') : ''; ?>>
                        <?php esc_html_e('Left', 'hash-form'); ?>
                    </option>
                    <option value="right" <?php isset($field['text_alignment']) ? selected($field['text_alignment'], 'right') : ''; ?>>
                        <?php esc_html_e('Right', 'hash-form'); ?>
                    </option>
                    <option value="center" <?php isset($field['text_alignment']) ? selected($field['text_alignment'], 'center') : ''; ?>>
                        <?php esc_html_e('Center', 'hash-form'); ?>
                    </option>
                </select>
            </div>
            <?php
        }

        if ($field_type === 'image_select') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Select Type', 'hash-form'); ?></label>
                <select class="hf-select-image-type" name="field_options[select_option_type_<?php echo esc_attr($field_id); ?>]" data-is-id="<?php echo esc_attr($field_id); ?>">
                    <option value="checkbox" <?php isset($field['select_option_type']) ? selected($field['select_option_type'], 'checkbox') : ''; ?>>
                        <?php esc_html_e('Multiple', 'hash-form'); ?>
                    </option>
                    <option value="radio" <?php isset($field['select_option_type']) ? selected($field['select_option_type'], 'radio') : ''; ?>>
                        <?php esc_html_e('Single', 'hash-form'); ?>
                    </option>
                </select>
            </div>
            <?php
            $columns = array(
                'small' => esc_html__('Small', 'hash-form'),
                'medium' => esc_html__('Medium', 'hash-form'),
                'large' => esc_html__('Large', 'hash-form'),
                'xlarge' => esc_html__('Extra Large', 'hash-form'),
            );
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Image Size', 'hash-form'); ?></label>
                <select name="field_options[image_size_<?php echo absint($field_id); ?>]">
                    <?php foreach ($columns as $col => $col_label) { ?>
                        <option value="<?php echo esc_attr($col); ?>" <?php selected($field['image_size'], $col); ?>>
                            <?php echo esc_html($col_label); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <?php
        }

        if ($field_type === 'image') {
            $image_id = $image = '';
            if (isset($field['image_id'])) {
                $image_id = $field['image_id'];
                $image = wp_get_attachment_image_src($field['image_id'], 'full');
                $image = isset($image[0]) ? $image[0] : '';
            }
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Select Image', 'hash-form'); ?></label>
                <div class="hf-image-preview">
                    <input type="hidden" class="hf-image-id" name="field_options[image_id_<?php echo esc_attr($field_id); ?>]" id="hf-field-image-<?php echo absint($field_id); ?>" value="<?php echo esc_attr($image_id); ?>"/>
                    <div class="hf-image-preview-wrap<?php echo ($image ? '' : ' hf-hidden'); ?>">
                        <div class="hf-image-preview-box">
                            <img id="hf-image-preview-<?php echo absint($field_id); ?>" src="<?php echo esc_url($image); ?>" />
                        </div>
                        <button type="button" class="button hf-remove-image">
                            <span class="mdi mdi-trash-can-outline"></span>
                            <?php esc_html_e('Delete', 'hash-form'); ?>
                        </button>
                    </div>
                    <button type="button" class="button hf-choose-image<?php echo($image ? ' hf-hidden' : ''); ?>">
                        <span class="mdi mdi-tray-arrow-up"></span>
                        <?php esc_attr_e('Upload image', 'hash-form'); ?>
                    </button>
                </div>
            </div>
            <?php
        }

        if ($field_type === 'spacer') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Height (px)', 'hash-form'); ?></label>
                <input type="number" name="field_options[spacer_height_<?php echo absint($field_id); ?>]" value="<?php echo isset($field['spacer_height']) ? esc_attr($field['spacer_height']) : ''; ?>" data-changeheight="field_change_height_<?php echo absint($field_id) ?>"/>
            </div>
            <?php
        }

        if ($field_type === 'time') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Step', 'hash-form'); ?></label>
                <input type="number" name="field_options[step_<?php echo absint($field_id); ?>]" value="<?php echo isset($field['step']) ? esc_attr($field['step']) : ''; ?>" min="1"/>
            </div>
            <div class="hf-form-row">
                <label><?php esc_html_e('Min Time', 'hash-form'); ?></label>
                <input type="text" class="min-value-field" name="field_options[min_time_<?php echo absint($field_id); ?>]" value="<?php echo isset($field['min_time']) ? esc_attr($field['min_time']) : ''; ?>"/>
            </div>
            <div class="hf-form-row">
                <label><?php esc_html_e('Max Time', 'hash-form'); ?></label>
                <input type="text" class="max-value-field" name="field_options[max_time_<?php echo absint($field_id); ?>]" value="<?php echo isset($field['max_time']) ? esc_attr($field['max_time']) : ''; ?>"/>
            </div>
            <?php
        }

        if ($field_type === 'date') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Date Format', 'hash-form'); ?></label>
                <select name="field_options[date_format_<?php echo esc_attr($field_id); ?>]">
                    <option value="MM dd, yy" <?php isset($field['date_format']) ? selected($field['date_format'], 'MM dd, yy') : ''; ?>>
                        <?php esc_html_e('September 19, 2023', 'hash-form'); ?>
                    </option>
                    <option value="yy-mm-dd" <?php isset($field['date_format']) ? selected($field['date_format'], 'yy-mm-dd') : ''; ?>>
                        <?php esc_html_e('2023-09-19', 'hash-form'); ?>
                    </option>
                    <option value="mm/dd/yy" <?php isset($field['date_format']) ? selected($field['date_format'], 'mm/dd/yy') : ''; ?>>
                        <?php esc_html_e('09/19/2023', 'hash-form'); ?>
                    </option>
                    <option value="dd/mm/yy" <?php isset($field['date_format']) ? selected($field['date_format'], 'dd/mm/yy') : ''; ?>>
                        <?php esc_html_e('19/09/2023', 'hash-form'); ?>
                    </option>
                </select>
            </div>
            <?php
        }

        if ($field_type === 'textarea') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Rows', 'hash-form'); ?></label>
                <input type="number" name="field_options[rows_<?php echo absint($field_id); ?>]" value="<?php echo (isset($field['rows']) ? esc_attr($field['rows']) : ''); ?>" data-changerows="<?php echo esc_attr($this->html_id()); ?>"/>
            </div>
            <?php
        }

        if ($field_type === 'separator') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Divider Type', 'hash-form'); ?></label>
                <select name="field_options[border_style_<?php echo esc_attr($field_id); ?>]" data-changebordertype="field_change_style_<?php echo esc_attr($field_id) ?>">
                    <option value="solid" <?php isset($field['border_style']) ? selected($field['border_style'], 'solid') : ''; ?>>
                        <?php esc_html_e('Solid', 'hash-form'); ?>
                    </option>
                    <option value="double" <?php isset($field['border_style']) ? selected($field['border_style'], 'double') : ''; ?>>
                        <?php esc_html_e('Double', 'hash-form'); ?>
                    </option>
                    <option value="dotted" <?php isset($field['border_style']) ? selected($field['border_style'], 'dotted') : ''; ?>>
                        <?php esc_html_e('Dotted', 'hash-form'); ?>
                    </option>
                    <option value="dashed" <?php isset($field['border_style']) ? selected($field['border_style'], 'dashed') : ''; ?>>
                        <?php esc_html_e('Dashed', 'hash-form'); ?>
                    </option>
                    <option value="groove" <?php isset($field['border_style']) ? selected($field['border_style'], 'groove') : ''; ?>>
                        <?php esc_html_e('Groove', 'hash-form'); ?>
                    </option>
                    <option value="ridge" <?php isset($field['border_style']) ? selected($field['border_style'], 'ridge') : ''; ?>>
                        <?php esc_html_e('Ridge', 'hash-form'); ?>
                    </option>
                </select>
            </div>

            <div class="hf-form-row">
                <label><?php esc_html_e('Divider Height (px)', 'hash-form'); ?></label>
                <input type="number" name="field_options[border_width_<?php echo absint($field_id); ?>]" value="<?php echo (isset($field['border_width']) ? esc_attr($field['border_width']) : ''); ?>" data-changeborderwidth="field_change_style_<?php echo absint($field_id) ?>"/>
            </div>
            <?php
        }

        if ($display['required']) {
            ?>
            <div class="hf-form-row">
                <label for="hf-req-field-<?php echo absint($field_id); ?>">
                    <input type="checkbox" class="hf-form-field-required" id="hf-req-field-<?php echo absint($field_id); ?>" name="field_options[required_<?php echo absint($field_id); ?>]" value="1" <?php checked($field['required'], 1); ?> />
                    <?php esc_html_e('Required', 'hash-form'); ?>
                </label>
            </div>
            <?php
        }

        if ($display['range']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Number Range', 'hash-form'); ?></label>
                <div class="hf-grid-container">
                    <div class="hf-form-row hf-grid-2">
                        <label><?php esc_html_e('From', 'hash-form'); ?></label>
                        <input type="number" name="field_options[minnum_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['minnum']); ?>" data-changeme="hf-field-<?php echo esc_attr($field['field_key']); ?>" data-changeatt="min" <?php echo ($field_type === 'range_slider' ? 'data-changemin="field_change_min_' . esc_attr($field['field_key']) . '"' : ''); ?>/>
                    </div>

                    <div class="hf-form-row hf-grid-2">
                        <label><?php esc_html_e('To', 'hash-form'); ?></label>
                        <input type="number" name="field_options[maxnum_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['maxnum']); ?>" data-changeme="hf-field-<?php echo esc_attr($field['field_key']); ?>" data-changeatt="max" <?php echo ($field_type === 'range_slider' ? 'data-changemax="field_change_max_' . esc_attr($field['field_key']) . '"' : ''); ?>/>
                    </div>

                    <div class="hf-form-row hf-grid-2">
                        <label><?php esc_html_e('Step', 'hash-form'); ?></label>
                        <input type="number" name="field_options[step_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['step']); ?>" data-changeatt="step" data-changeme="hf-field-<?php echo esc_attr($field['field_key']); ?>"/>
                    </div>
                </div>
            </div>
            <?php
        }

        $this->show_primary_options();

        if ($field_type === 'upload') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Upload Label', 'hash-form'); ?></label>
                <input type="text" name="field_options[upload_label_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['upload_label']); ?>" data-changeme="hf-editor-upload-label-text-<?php echo absint($field_id); ?>"/>
            </div>

            <div class="hf-form-row">
                <label><?php esc_html_e('Extensions', 'hash-form'); ?></label>
                <input type="text" name="field_options[extensions_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['extensions']); ?>"/>
                <label class="hf-field-desc"><?php esc_html_e('The allowed extensions are pdf, doc, docx, xls, xlsx, odt, ppt, pptx, pps, ppsx, jpg, jpeg, png, gif, bmp, mp3, mp4, ogg, wav, mp4, m4v, mov, wmv, avi, mpg, ogv, 3gp, txt, zip, rar, 7z, csv', 'hash-form'); ?></label>
            </div>

            <div class="hf-form-row">
                <label><?php esc_html_e('Maximum File Size Allowed to Upload', 'hash-form'); ?></label>
                <input type="number" name="field_options[max_upload_size_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['max_upload_size']); ?>"/>
            </div>

            <div class="hf-form-row">
                <label>
                    <input type="hidden" name="field_options[multiple_uploads_<?php echo absint($field_id); ?>]" value="off" />
                    <input type="checkbox" name="field_options[multiple_uploads_<?php echo absint($field_id); ?>]" value="on" data-condition="toggle" id="hf-multiple-uploads-<?php echo absint($field_id); ?>" <?php checked($field['multiple_uploads'], 'on'); ?>/>
                    <?php esc_html_e('Multiple Uploads', 'hash-form'); ?>
                </label>
            </div>

            <div class="hf-form-row" data-condition-toggle="hf-multiple-uploads-<?php echo absint($field_id); ?>">
                <label>
                    <?php esc_html_e('Multiple Uploads Limit', 'hash-form'); ?>
                    <input type="number" name="field_options[multiple_uploads_limit_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['multiple_uploads_limit']); ?>"/>
                </label>
            </div>
            <?php
        }

        if ($display['css']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('CSS Classes', 'hash-form'); ?></label>
                <input type="text" name="field_options[classes_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['classes']); ?>"/>
            </div>
            <?php
        }

        if ($field_type === 'select' || $field_type === 'radio' || $field_type === 'checkbox' || $field_type === 'image_select') {
            $this->show_field_choices();
        }

        if ($display['auto_width']) {
            ?>
            <div class="hf-form-row">
                <label>
                    <input type="hidden" name="field_options[auto_width_<?php echo absint($field_id); ?>]" value="off" />
                    <input type="checkbox" name="field_options[auto_width_<?php echo absint($field_id); ?>]" value="on" <?php checked($field['auto_width'], 'on'); ?>/>
                    <?php esc_html_e('Automatic Width', 'hash-form'); ?>
                </label>
            </div>
            <?php
        }

        if ($display['default']) {
            $field_type_attr_val = 'text';
            if ($field_type == 'range_slider' || $field_type == 'number' || $field_type == 'spinner') {
                $field_type_attr_val = 'number';
            }

            if ($field_type == 'email') {
                $field_type_attr_val = 'email';
            }
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Default Value', 'hash-form'); ?></label>
                <input type="<?php echo esc_attr($field_type_attr_val); ?>" name="<?php echo 'default_value_' . absint($field_id); ?>" value="<?php echo esc_attr($field['default_value']); ?>" class="hf-default-value-field" data-changeme="hf-field-<?php echo esc_attr($field['field_key']); ?>" data-changeatt="value"/>
            </div>
            <?php
        }

        $this->show_after_default();

        if ($display['clear_on_focus']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Placeholder', 'hash-form'); ?></label>
                <?php
                if ($field_type === 'textarea') {
                    ?>
                    <textarea id="hf-placeholder-<?php echo absint($field_id); ?>" name="field_options[placeholder_<?php echo absint($field_id); ?>]" rows="3" data-changeme="hf-field-<?php echo esc_attr($field['field_key']); ?>" data-changeatt="placeholder"><?php echo esc_textarea($field['placeholder']); ?></textarea>
                    <?php
                } else {
                    ?>
                    <input id="hf-placeholder-<?php echo absint($field_id); ?>" type="text" name="field_options[placeholder_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['placeholder']); ?>" data-changeme="hf-field-<?php echo esc_attr($field['field_key']); ?>" data-changeatt="placeholder" />
                    <?php
                }
                ?>
            </div>
            <?php
        }

        if ($display['description']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Field Description', 'hash-form'); ?></label>
                <textarea name="field_options[description_<?php echo absint($field_id); ?>]" data-changeme="hf-field-desc-<?php echo absint($field_id); ?>"><?php echo esc_textarea($field['description']); ?></textarea>
            </div>
            <?php
        }

        if ($display['format']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Format', 'hash-form'); ?></label>
                <input type="text" value="<?php echo esc_attr($field['format']); ?>" name="field_options[format_<?php echo absint($field_id); ?>]" data-fid="<?php echo absint($field_id); ?>" />
                <p class="description"><?php esc_html_e('Enter a Regex Format to validate.', 'hash-form'); ?> <a href="https://www.phpliveregex.com" target="_blank"><?php esc_html_e('Generate Regex', 'hash-form'); ?></a></p>
            </div>
            <?php
        }

        if ($display['required']) {
            ?>
            <div class="hf-form-row hf-grid-3 hf-required-detail-<?php echo esc_attr($field_id) . ($field['required'] ? '' : ' hf-hidden'); ?>">
                <label><?php esc_html_e('Required Field Indicator', 'hash-form'); ?></label>
                <input type="text" name="field_options[required_indicator_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['required_indicator']); ?>" data-changeme="hf-editor-field-required-<?php echo absint($field_id); ?>" />
            </div>
            <?php
        }

        if ($field_type === 'radio' || $field_type === 'checkbox' || $field_type === 'image_select') {
            ?>
            <div class="hf-form-row hf-grid-3">
                <label><?php esc_html_e('Options Layout', 'hash-form'); ?></label>
                <select name="field_options[options_layout_<?php echo absint($field_id); ?>]">
                    <option value="inline" <?php selected($field['options_layout'], 'inline'); ?>>
                        <?php esc_html_e('Inline', 'hash-form'); ?>
                    </option>
                    <option value="1" <?php selected($field['options_layout'], '1'); ?>>
                        <?php esc_html_e('1 Column', 'hash-form'); ?>
                    </option>
                    <option value="2" <?php selected($field['options_layout'], '2'); ?>>
                        <?php esc_html_e('2 Columns', 'hash-form'); ?>
                    </option>
                    <option value="3" <?php selected($field['options_layout'], '3'); ?>>
                        <?php esc_html_e('3 Columns', 'hash-form'); ?>
                    </option>
                    <option value="4" <?php selected($field['options_layout'], '4'); ?>>
                        <?php esc_html_e('4 Columns', 'hash-form'); ?>
                    </option>
                    <option value="5" <?php selected($field['options_layout'], '5'); ?>>
                        <?php esc_html_e('5 Columns', 'hash-form'); ?>
                    </option>
                    <option value="6" <?php selected($field['options_layout'], '6'); ?>>
                        <?php esc_html_e('6 Columns', 'hash-form'); ?>
                    </option>
                </select>
            </div>
            <?php
        }

        if ($display['max']) {
            ?>
            <div class="hf-form-row hf-grid-3">
                <label><?php esc_html_e('Max Characters', 'hash-form'); ?></label>
                <input type="number" name="field_options[max_<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($field['max']); ?>" size="5" data-fid="<?php echo absint($field_id); ?>" />
            </div>
            <?php
        }

        if ($display['max_width']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Field Max Width', 'hash-form'); ?></label>
                <div class="hf-form-input-unit">
                    <input type="number" name="field_options[field_max_width_<?php echo esc_attr($field_id); ?>]" value="<?php echo (isset($field['field_max_width']) ? esc_attr($field['field_max_width']) : ''); ?>" />

                    <select name="field_options[field_max_width_unit_<?php echo esc_attr($field_id); ?>]">
                        <option value="%" <?php isset($field['field_max_width_unit']) ? selected($field['field_max_width_unit'], '%') : ''; ?>>
                            <?php esc_html_e('%', 'hash-form'); ?>
                        </option>
                        <option value="px" <?php isset($field['field_max_width_unit']) ? selected($field['field_max_width_unit'], 'px') : ''; ?>>
                            <?php esc_html_e('px', 'hash-form'); ?>
                        </option>
                    </select>
                </div>
            </div>
            <?php
        }

        if ($display['image_max_width']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Image Max Width', 'hash-form'); ?></label>
                <div class="hf-form-input-unit">
                    <input type="number" name="field_options[image_max_width_<?php echo esc_attr($field_id); ?>]" value="<?php echo (isset($field['image_max_width']) ? esc_attr($field['image_max_width']) : ''); ?>" />

                    <select name="field_options[image_max_width_unit_<?php echo esc_attr($field_id); ?>]">
                        <option value="%" <?php isset($field['image_max_width_unit']) ? selected($field['image_max_width_unit'], '%') : ''; ?>>
                            <?php esc_html_e('%', 'hash-form'); ?>
                        </option>
                        <option value="px" <?php isset($field['image_max_width_unit']) ? selected($field['image_max_width_unit'], 'px') : ''; ?>>
                            <?php esc_html_e('px', 'hash-form'); ?>
                        </option>
                    </select>
                </div>
            </div>
            <?php
        }

        if ($display['field_alignment']) {
            $field_alignment = isset($field['field_alignment']) ? esc_attr($field['field_alignment']) : '';
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Field Alignment', 'hash-form'); ?></label>
                <select name="field_options[field_alignment_<?php echo esc_attr($field_id); ?>]">
                    <option value="left" <?php selected($field_alignment, 'left'); ?>>
                        <?php esc_html_e('Left', 'hash-form'); ?>
                    </option>
                    <option value="right" <?php selected($field_alignment, 'right'); ?>>
                        <?php esc_html_e('Right', 'hash-form'); ?>
                    </option>
                    <option value="center" <?php selected($field_alignment, 'center'); ?>>
                        <?php esc_html_e('Center', 'hash-form'); ?>
                    </option>
                </select>
                <label class="hf-field-desc"><?php esc_html_e('This option will only work if the Field Max Width is set and width is smaller than container.', 'hash-form'); ?></label>
            </div>
            <?php
        }

        $has_validation = ($display['invalid'] || $display['required']);
        $has_invalid = $display['invalid'];

        if ($field_type === 'upload') {
            $has_validation = true;
            $has_invalid = true;
        }

        if ($has_validation) {
            ?>
            <h4 class="hf-validation-header <?php echo ($has_invalid ? 'hf-alway-show' : ($field['required'] ? '' : ' hf-hidden')); ?>"> <?php esc_html_e('Validation Messages', 'hash-form'); ?></h4>
            <?php
        }

        if ($display['required']) {
            ?>
            <div class="hf-form-row hf-required-detail-<?php echo esc_attr($field_id) . ($field['required'] ? '' : ' hf-hidden'); ?>">
                <label><?php esc_html_e('Required', 'hash-form'); ?></label>
                <input type="text" name="field_options[blank_<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($field['blank']); ?>"/>
            </div>
            <?php
        }

        if ($display['invalid']) {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Invalid Format', 'hash-form'); ?></label>
                <input type="text" name="field_options[invalid_<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($field['invalid']); ?>"/>
            </div>
            <?php
        }


        if ($field_type === 'upload') {
            ?>
            <div class="hf-form-row">
                <label><?php esc_html_e('Extensions', 'hash-form'); ?></label>
                <input type="text" name="field_options[extensions_error_message_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['extensions_error_message']); ?>"/>
            </div>

            <div class="hf-form-row" data-condition-toggle="hf-multiple-uploads-<?php echo absint($field_id); ?>">
                <label><?php esc_html_e('Multiple Uploads', 'hash-form'); ?></label>
                <input type="text" name="field_options[multiple_uploads_error_message_<?php echo absint($field_id); ?>]" value="<?php echo esc_attr($field['multiple_uploads_error_message']); ?>"/>
            </div>
            <?php
        }
        ?>                     
    </div>
</div>
