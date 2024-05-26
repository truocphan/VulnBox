<?php
defined('ABSPATH') || die();
$id = get_the_ID();
?>

<div class="hf-settings-row hf-form-row">
    <label class="hf-setting-label"><?php esc_html_e('Choose Form to Preview', 'hash-form'); ?></label>
    <select id="hf-template-preview-form-id">
        <?php
        $forms = HashFormBuilder::get_all_forms();
        ?>
        <option value=""><?php esc_html_e('Default Demo Form', 'hash-form'); ?></option>
        <?php
        foreach ($forms as $form) {
            ?>
            <option value="<?php echo esc_attr($form->id); ?>"><?php echo esc_html($form->name); ?></option>
        <?php } ?>
    </select>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Form', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>

<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Column Gap', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-form-column-gap" class="hashform-range-input-selector" type="number" name="hashform_styles[form][column_gap]" value="<?php echo is_numeric($hashform_styles['form']['column_gap']) ? intval($hashform_styles['form']['column_gap']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Row Gap', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-form-row-gap" class="hashform-range-input-selector" type="number" name="hashform_styles[form][row_gap]" value="<?php echo is_numeric($hashform_styles['form']['row_gap']) ? intval($hashform_styles['form']['row_gap']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Background Color', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-form-bg-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[form][bg_color]" value="<?php echo esc_attr($hashform_styles['form']['bg_color']); ?>">
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Box Shadow', 'hash-form') ?></label>
        <div class="hf-setting-fields">
            <ul class="hf-shadow-fields">
                <li class="hf-shadow-settings-field">
                    <input id="hf-form-shadow-x" data-unit="px" type="number" name="hashform_styles[form][shadow][x]" value="<?php echo esc_attr($hashform_styles['form']['shadow']['x']); ?>">
                    <label><?php esc_html_e('H', 'hash-form') ?></label>
                </li>
                <li class="hf-shadow-settings-field">
                    <input id="hf-form-shadow-y" data-unit="px" type="number" name="hashform_styles[form][shadow][y]" value="<?php echo esc_attr($hashform_styles['form']['shadow']['y']); ?>">
                    <label><?php esc_html_e('V', 'hash-form') ?></label>
                </li>
                <li class="hf-shadow-settings-field">
                    <input id="hf-form-shadow-blur" data-unit="px" type="number" name="hashform_styles[form][shadow][blur]" value="<?php echo esc_attr($hashform_styles['form']['shadow']['blur']); ?>">
                    <label><?php esc_html_e('Blur', 'hash-form') ?></label>
                </li>
                <li class="hf-shadow-settings-field">
                    <input id="hf-form-shadow-spread" data-unit="px" type="number" name="hashform_styles[form][shadow][spread]" value="<?php echo esc_attr($hashform_styles['form']['shadow']['spread']); ?>">
                    <label><?php esc_html_e('Spread', 'hash-form') ?></label>
                </li>
            </ul>
            <div class="hf-shadow-settings-field">
                <div class="hf-color-input-field">
                    <input id="hf-form-shadow-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[form][shadow][color]" value="<?php echo esc_attr($hashform_styles['form']['shadow']['color']); ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Border Color', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-form-border-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[form][border_color]" value="<?php echo esc_attr($hashform_styles['form']['border_color']); ?>">
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Width', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-top" data-unit="px" type="number" name="hashform_styles[form][border][top]" value="<?php echo esc_attr($hashform_styles['form']['border']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-right" data-unit="px" type="number" name="hashform_styles[form][border][right]" value="<?php echo esc_attr($hashform_styles['form']['border']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-bottom" data-unit="px" type="number" name="hashform_styles[form][border][bottom]" value="<?php echo esc_attr($hashform_styles['form']['border']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-left" data-unit="px" type="number" name="hashform_styles[form][border][left]" value="<?php echo esc_attr($hashform_styles['form']['border']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Radius', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-radius-top" data-unit="px" type="number" name="hashform_styles[form][border_radius][top]" value="<?php echo esc_attr($hashform_styles['form']['border_radius']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-radius-right" data-unit="px" type="number" name="hashform_styles[form][border_radius][right]" value="<?php echo esc_attr($hashform_styles['form']['border_radius']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-radius-bottom" data-unit="px" type="number" name="hashform_styles[form][border_radius][bottom]" value="<?php echo esc_attr($hashform_styles['form']['border_radius']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-border-radius-left" data-unit="px" type="number" name="hashform_styles[form][border_radius][left]" value="<?php echo esc_attr($hashform_styles['form']['border_radius']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Padding', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-form-padding-top" data-unit="px" type="number" name="hashform_styles[form][padding][top]" value="<?php echo esc_attr($hashform_styles['form']['padding']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-padding-right" data-unit="px" type="number" name="hashform_styles[form][padding][right]" value="<?php echo esc_attr($hashform_styles['form']['padding']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-padding-bottom" data-unit="px" type="number" name="hashform_styles[form][padding][bottom]" value="<?php echo esc_attr($hashform_styles['form']['padding']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-form-padding-left" data-unit="px" type="number" name="hashform_styles[form][padding][left]" value="<?php echo esc_attr($hashform_styles['form']['padding']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Labels', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>

<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'label'); ?>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Bottom Spacing', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-label-spacing" class="hashform-range-input-selector" type="number" name="hashform_styles[label][spacing]" value="<?php echo is_numeric($hashform_styles['label']['spacing']) ? intval($hashform_styles['label']['spacing']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>
    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Required Text Color', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-label-required-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[label][required_color]" value="<?php echo esc_attr($hashform_styles['label']['required_color']); ?>">
        </div>
    </div>
</div>


<h2 class="hf-settings-heading"><?php esc_html_e('Description', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'desc'); ?>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Top Spacing', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-desc-spacing" class="hashform-range-input-selector" type="number" name="hashform_styles[desc][spacing]" value="<?php echo is_numeric($hashform_styles['desc']['spacing']) ? intval($hashform_styles['desc']['spacing']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>
</div>


<h2 class="hf-settings-heading"><?php esc_html_e('Fields', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>

<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'field', array('color')); ?>
    </div>

    <div class="hf-tab-container">
        <ul class="hf-setting-tab">
            <li data-tab="hf-tab-normal" class="hf-tab-active"><?php esc_html_e('Normal', 'hash-form'); ?></li>
            <li data-tab="hf-tab-focus"><?php esc_html_e('Focus', 'hash-form'); ?></li>
        </ul>

        <div class="hf-setting-tab-panel">
            <div class="hf-tab-normal hf-tab-content">
                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-field-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][color_normal]" value="<?php echo esc_attr($hashform_styles['field']['color_normal']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Background Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-field-bg-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][bg_color_normal]" value="<?php echo esc_attr($hashform_styles['field']['bg_color_normal']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label"><?php esc_html_e('Box Shadow', 'hash-form') ?></label>
                    <div class="hf-setting-fields">
                        <ul class="hf-shadow-fields">
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-normal-x" data-unit="px" type="number" name="hashform_styles[field][shadow_normal][x]" value="<?php echo esc_attr($hashform_styles['field']['shadow_normal']['x']); ?>">
                                <label><?php esc_html_e('H', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-normal-y" data-unit="px" type="number" name="hashform_styles[field][shadow_normal][y]" value="<?php echo esc_attr($hashform_styles['field']['shadow_normal']['y']); ?>">
                                <label><?php esc_html_e('V', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-normal-blur" data-unit="px" type="number" name="hashform_styles[field][shadow_normal][blur]" value="<?php echo esc_attr($hashform_styles['field']['shadow_normal']['blur']); ?>">
                                <label><?php esc_html_e('Blur', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-normal-spread" data-unit="px" type="number" name="hashform_styles[field][shadow_normal][spread]" value="<?php echo esc_attr($hashform_styles['field']['shadow_normal']['spread']); ?>">
                                <label><?php esc_html_e('Spread', 'hash-form') ?></label>
                            </li>
                        </ul>
                        <div class="hf-shadow-settings-field">
                            <div class="hf-color-input-field">
                                <input id="hf-field-shadow-normal-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][shadow_normal][color]" value="<?php echo esc_attr($hashform_styles['field']['shadow_normal']['color']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Border Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-field-border-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][border_color_normal]" value="<?php echo esc_attr($hashform_styles['field']['border_color_normal']); ?>">
                    </div>
                </div>
            </div>

            <div class="hf-tab-focus hf-tab-content" style="display: none;">
                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color (Focus)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-field-color-focus" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][color_focus]" value="<?php echo esc_attr($hashform_styles['field']['color_focus']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Background Color (Focus)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-field-bg-color-focus" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][bg_color_focus]" value="<?php echo esc_attr($hashform_styles['field']['bg_color_focus']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label"><?php esc_html_e('Box Shadow (Focus)', 'hash-form') ?></label>
                    <div class="hf-setting-fields">
                        <ul class="hf-shadow-fields">
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-focus-x" data-unit="px" type="number" name="hashform_styles[field][shadow_focus][x]" value="<?php echo esc_attr($hashform_styles['field']['shadow_focus']['x']); ?>">
                                <label><?php esc_html_e('H', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-focus-y" data-unit="px" type="number" name="hashform_styles[field][shadow_focus][y]" value="<?php echo esc_attr($hashform_styles['field']['shadow_focus']['y']); ?>">
                                <label><?php esc_html_e('V', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-focus-blur" data-unit="px" type="number" name="hashform_styles[field][shadow_focus][blur]" value="<?php echo esc_attr($hashform_styles['field']['shadow_focus']['blur']); ?>">
                                <label><?php esc_html_e('Blur', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-field-shadow-focus-spread" data-unit="px" type="number" name="hashform_styles[field][shadow_focus][spread]" value="<?php echo esc_attr($hashform_styles['field']['shadow_focus']['spread']); ?>">
                                <label><?php esc_html_e('Spread', 'hash-form') ?></label>
                            </li>
                        </ul>

                        <div class="hf-shadow-settings-field">
                            <div class="hf-color-input-field">
                                <input id="hf-field-shadow-focus-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][shadow_focus][color]" value="<?php echo esc_attr($hashform_styles['field']['shadow_focus']['color']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Border Color (Focus)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-field-border-color-focus" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[field][border_color_focus]" value="<?php echo esc_attr($hashform_styles['field']['border_color_focus']); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Width', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-top" data-unit="px" type="number" name="hashform_styles[field][border][top]" value="<?php echo esc_attr($hashform_styles['field']['border']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-right" data-unit="px" type="number" name="hashform_styles[field][border][right]" value="<?php echo esc_attr($hashform_styles['field']['border']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-bottom" data-unit="px" type="number" name="hashform_styles[field][border][bottom]" value="<?php echo esc_attr($hashform_styles['field']['border']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-left" data-unit="px" type="number" name="hashform_styles[field][border][left]" value="<?php echo esc_attr($hashform_styles['field']['border']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Radius', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-radius-top" data-unit="px" type="number" name="hashform_styles[field][border_radius][top]" value="<?php echo esc_attr($hashform_styles['field']['border_radius']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-radius-right" data-unit="px" type="number" name="hashform_styles[field][border_radius][right]" value="<?php echo esc_attr($hashform_styles['field']['border_radius']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-radius-bottom" data-unit="px" type="number" name="hashform_styles[field][border_radius][bottom]" value="<?php echo esc_attr($hashform_styles['field']['border_radius']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-border-radius-left" data-unit="px" type="number" name="hashform_styles[field][border_radius][left]" value="<?php echo esc_attr($hashform_styles['field']['border_radius']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Padding', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-field-padding-top" data-unit="px" type="number" name="hashform_styles[field][padding][top]" value="<?php echo esc_attr($hashform_styles['field']['padding']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-padding-right" data-unit="px" type="number" name="hashform_styles[field][padding][right]" value="<?php echo esc_attr($hashform_styles['field']['padding']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-padding-bottom" data-unit="px" type="number" name="hashform_styles[field][padding][bottom]" value="<?php echo esc_attr($hashform_styles['field']['padding']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-field-padding-left" data-unit="px" type="number" name="hashform_styles[field][padding][left]" value="<?php echo esc_attr($hashform_styles['field']['padding']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Upload Button', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-tab-container">
        <ul class="hf-setting-tab">
            <li data-tab="hf-tab-normal" class="hf-tab-active"><?php esc_html_e('Normal', 'hash-form'); ?></li>
            <li data-tab="hf-tab-hover"><?php esc_html_e('Hover', 'hash-form'); ?></li>
        </ul>

        <div class="hf-setting-tab-panel">
            <div class="hf-tab-normal hf-tab-content">
                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-upload-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][color_normal]" value="<?php echo esc_attr($hashform_styles['upload']['color_normal']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Background Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-upload-bg-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][bg_color_normal]" value="<?php echo esc_attr($hashform_styles['upload']['bg_color_normal']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label"><?php esc_html_e('Box Shadow', 'hash-form') ?></label>
                    <div class="hf-setting-fields">
                        <ul class="hf-shadow-fields">
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-normal-x" data-unit="px" type="number" name="hashform_styles[upload][shadow_normal][x]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_normal']['x']); ?>">
                                <label><?php esc_html_e('H', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-normal-y" data-unit="px" type="number" name="hashform_styles[upload][shadow_normal][y]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_normal']['y']); ?>">
                                <label><?php esc_html_e('V', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-normal-blur" data-unit="px" type="number" name="hashform_styles[upload][shadow_normal][blur]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_normal']['blur']); ?>">
                                <label><?php esc_html_e('Blur', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-normal-spread" data-unit="px" type="number" name="hashform_styles[upload][shadow_normal][spread]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_normal']['spread']); ?>">
                                <label><?php esc_html_e('Spread', 'hash-form') ?></label>
                            </li>
                        </ul>
                        <div class="hf-shadow-settings-field">
                            <div class="hf-color-input-field">
                                <input id="hf-upload-shadow-normal-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][shadow_normal][color]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_normal']['color']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Border Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-upload-border-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][border_color_normal]" value="<?php echo esc_attr($hashform_styles['upload']['border_color_normal']); ?>">
                    </div>
                </div>
            </div>

            <div class="hf-tab-hover hf-tab-content" style="display: none;">
                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color (Hover)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-upload-color-hover" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][color_hover]" value="<?php echo esc_attr($hashform_styles['upload']['color_hover']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Background Color (Hover)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-upload-bg-color-hover" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][bg_color_hover]" value="<?php echo esc_attr($hashform_styles['upload']['bg_color_hover']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label"><?php esc_html_e('Box Shadow (Hover)', 'hash-form') ?></label>
                    <div class="hf-setting-fields">
                        <ul class="hf-shadow-fields">
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-hover-x" data-unit="px" type="number" name="hashform_styles[upload][shadow_hover][x]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_hover']['x']); ?>">
                                <label><?php esc_html_e('H', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-hover-y" data-unit="px" type="number" name="hashform_styles[upload][shadow_hover][y]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_hover']['y']); ?>">
                                <label><?php esc_html_e('V', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-hover-blur" data-unit="px" type="number" name="hashform_styles[upload][shadow_hover][blur]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_hover']['blur']); ?>">
                                <label><?php esc_html_e('Blur', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-upload-shadow-hover-spread" data-unit="px" type="number" name="hashform_styles[upload][shadow_hover][spread]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_hover']['spread']); ?>">
                                <label><?php esc_html_e('Spread', 'hash-form') ?></label>
                            </li>
                        </ul>
                        <div class="hf-shadow-settings-field">
                            <div class="hf-color-input-field">
                                <input id="hf-upload-shadow-hover-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][shadow_hover][color]" value="<?php echo esc_attr($hashform_styles['upload']['shadow_hover']['color']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Border Color (Hover)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-upload-border-color-hover" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[upload][border_color_hover]" value="<?php echo esc_attr($hashform_styles['upload']['border_color_hover']); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Width', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-top" data-unit="px" type="number" name="hashform_styles[upload][border][top]" value="<?php echo esc_attr($hashform_styles['upload']['border']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-right" data-unit="px" type="number" name="hashform_styles[upload][border][right]" value="<?php echo esc_attr($hashform_styles['upload']['border']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-bottom" data-unit="px" type="number" name="hashform_styles[upload][border][bottom]" value="<?php echo esc_attr($hashform_styles['upload']['border']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-left" data-unit="px" type="number" name="hashform_styles[upload][border][left]" value="<?php echo esc_attr($hashform_styles['upload']['border']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Radius', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-radius-top" data-unit="px" type="number" name="hashform_styles[upload][border_radius][top]" value="<?php echo esc_attr($hashform_styles['upload']['border_radius']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-radius-right" data-unit="px" type="number" name="hashform_styles[upload][border_radius][right]" value="<?php echo esc_attr($hashform_styles['upload']['border_radius']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-radius-bottom" data-unit="px" type="number" name="hashform_styles[upload][border_radius][bottom]" value="<?php echo esc_attr($hashform_styles['upload']['border_radius']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-border-radius-left" data-unit="px" type="number" name="hashform_styles[upload][border_radius][left]" value="<?php echo esc_attr($hashform_styles['upload']['border_radius']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Padding', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-upload-padding-top" data-unit="px" type="number" name="hashform_styles[upload][padding][top]" value="<?php echo esc_attr($hashform_styles['upload']['padding']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-padding-right" data-unit="px" type="number" name="hashform_styles[upload][padding][right]" value="<?php echo esc_attr($hashform_styles['upload']['padding']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-padding-bottom" data-unit="px" type="number" name="hashform_styles[upload][padding][bottom]" value="<?php echo esc_attr($hashform_styles['upload']['padding']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-upload-padding-left" data-unit="px" type="number" name="hashform_styles[upload][padding][left]" value="<?php echo esc_attr($hashform_styles['upload']['padding']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Submit Button', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'button', array('color')); ?>
    </div>

    <div class="hf-tab-container">
        <ul class="hf-setting-tab">
            <li data-tab="hf-tab-normal" class="hf-tab-active"><?php esc_html_e('Normal', 'hash-form'); ?></li>
            <li data-tab="hf-tab-hover"><?php esc_html_e('Hover', 'hash-form'); ?></li>
        </ul>

        <div class="hf-setting-tab-panel">
            <div class="hf-tab-normal hf-tab-content">
                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-button-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][color_normal]" value="<?php echo esc_attr($hashform_styles['button']['color_normal']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Background Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-button-bg-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][bg_color_normal]" value="<?php echo esc_attr($hashform_styles['button']['bg_color_normal']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label"><?php esc_html_e('Box Shadow', 'hash-form') ?></label>
                    <div class="hf-setting-fields">
                        <ul class="hf-shadow-fields">
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-normal-x" data-unit="px" type="number" name="hashform_styles[button][shadow_normal][x]" value="<?php echo esc_attr($hashform_styles['button']['shadow_normal']['x']); ?>">
                                <label><?php esc_html_e('H', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-normal-y" data-unit="px" type="number" name="hashform_styles[button][shadow_normal][y]" value="<?php echo esc_attr($hashform_styles['button']['shadow_normal']['y']); ?>">
                                <label><?php esc_html_e('V', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-normal-blur" data-unit="px" type="number" name="hashform_styles[button][shadow_normal][blur]" value="<?php echo esc_attr($hashform_styles['button']['shadow_normal']['blur']); ?>">
                                <label><?php esc_html_e('Blur', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-normal-spread" data-unit="px" type="number" name="hashform_styles[button][shadow_normal][spread]" value="<?php echo esc_attr($hashform_styles['button']['shadow_normal']['spread']); ?>">
                                <label><?php esc_html_e('Spread', 'hash-form') ?></label>
                            </li>
                        </ul>
                        <div class="hf-shadow-settings-field">
                            <div class="hf-color-input-field">
                                <input id="hf-button-shadow-normal-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][shadow_normal][color]" value="<?php echo esc_attr($hashform_styles['button']['shadow_normal']['color']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Border Color', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-button-border-color-normal" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][border_color_normal]" value="<?php echo esc_attr($hashform_styles['button']['border_color_normal']); ?>">
                    </div>
                </div>
            </div>

            <div class="hf-tab-hover hf-tab-content" style="display: none;">
                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color (Hover)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-button-color-hover" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][color_hover]" value="<?php echo esc_attr($hashform_styles['button']['color_hover']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Background Color (Hover)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-button-bg-color-hover" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][bg_color_hover]" value="<?php echo esc_attr($hashform_styles['button']['bg_color_hover']); ?>">
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label"><?php esc_html_e('Box Shadow (Hover)', 'hash-form') ?></label>
                    <div class="hf-setting-fields">
                        <ul class="hf-shadow-fields">
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-hover-x" data-unit="px" type="number" name="hashform_styles[button][shadow_hover][x]" value="<?php echo esc_attr($hashform_styles['button']['shadow_hover']['x']); ?>">
                                <label><?php esc_html_e('H', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-hover-y" data-unit="px" type="number" name="hashform_styles[button][shadow_hover][y]" value="<?php echo esc_attr($hashform_styles['button']['shadow_hover']['y']); ?>">
                                <label><?php esc_html_e('V', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-hover-blur" data-unit="px" type="number" name="hashform_styles[button][shadow_hover][blur]" value="<?php echo esc_attr($hashform_styles['button']['shadow_hover']['blur']); ?>">
                                <label><?php esc_html_e('Blur', 'hash-form') ?></label>
                            </li>
                            <li class="hf-shadow-settings-field">
                                <input id="hf-button-shadow-hover-spread" data-unit="px" type="number" name="hashform_styles[button][shadow_hover][spread]" value="<?php echo esc_attr($hashform_styles['button']['shadow_hover']['spread']); ?>">
                                <label><?php esc_html_e('Spread', 'hash-form') ?></label>
                            </li>
                        </ul>
                        <div class="hf-shadow-settings-field">
                            <div class="hf-color-input-field">
                                <input id="hf-button-shadow-hover-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][shadow_hover][color]" value="<?php echo esc_attr($hashform_styles['button']['shadow_hover']['color']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-settings-row">
                    <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Border Color (Hover)', 'hash-form'); ?></label>
                    <div class="hf-setting-fields hf-color-input-field">
                        <input id="hf-button-border-color-hover" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[button][border_color_hover]" value="<?php echo esc_attr($hashform_styles['button']['border_color_hover']); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Width', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-top" data-unit="px" type="number" name="hashform_styles[button][border][top]" value="<?php echo esc_attr($hashform_styles['button']['border']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-right" data-unit="px" type="number" name="hashform_styles[button][border][right]" value="<?php echo esc_attr($hashform_styles['button']['border']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-bottom" data-unit="px" type="number" name="hashform_styles[button][border][bottom]" value="<?php echo esc_attr($hashform_styles['button']['border']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-left" data-unit="px" type="number" name="hashform_styles[button][border][left]" value="<?php echo esc_attr($hashform_styles['button']['border']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Border Radius', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-radius-top" data-unit="px" type="number" name="hashform_styles[button][border_radius][top]" value="<?php echo esc_attr($hashform_styles['button']['border_radius']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-radius-right" data-unit="px" type="number" name="hashform_styles[button][border_radius][right]" value="<?php echo esc_attr($hashform_styles['button']['border_radius']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-radius-bottom" data-unit="px" type="number" name="hashform_styles[button][border_radius][bottom]" value="<?php echo esc_attr($hashform_styles['button']['border_radius']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-border-radius-left" data-unit="px" type="number" name="hashform_styles[button][border_radius][left]" value="<?php echo esc_attr($hashform_styles['button']['border_radius']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Padding', 'hash-form') ?></label>
        <ul class="hf-unit-fields">
            <li class="hf-unit-settings-field">
                <input id="hf-button-padding-top" data-unit="px" type="number" name="hashform_styles[button][padding][top]" value="<?php echo esc_attr($hashform_styles['button']['padding']['top']); ?>" min="0">
                <label><?php esc_html_e('Top', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-padding-right" data-unit="px" type="number" name="hashform_styles[button][padding][right]" value="<?php echo esc_attr($hashform_styles['button']['padding']['right']); ?>" min="0">
                <label><?php esc_html_e('Right', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-padding-bottom" data-unit="px" type="number" name="hashform_styles[button][padding][bottom]" value="<?php echo esc_attr($hashform_styles['button']['padding']['bottom']); ?>" min="0">
                <label><?php esc_html_e('Bottom', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <input id="hf-button-padding-left" data-unit="px" type="number" name="hashform_styles[button][padding][left]" value="<?php echo esc_attr($hashform_styles['button']['padding']['left']); ?>" min="0">
                <label><?php esc_html_e('Left', 'hash-form') ?></label>
            </li>
            <li class="hf-unit-settings-field">
                <div class="hf-link-button">
                    <span class="dashicons dashicons-admin-links hf-linked"></span>
                    <span class="dashicons dashicons-editor-unlink hf-unlinked"></span>
                </div>
            </li>
        </ul>
    </div>
</div>


<h2 class="hf-settings-heading"><?php esc_html_e('Validation', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'validation'); ?>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Top Spacing', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-validation-spacing" class="hashform-range-input-selector" type="number" name="hashform_styles[validation][spacing]" value="<?php echo is_numeric($hashform_styles['validation']['spacing']) ? intval($hashform_styles['validation']['spacing']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Text Align', 'hash-form') ?></label>
        <select id="hf-validation-textalign" name="hashform_styles[validation][textalign]">
            <option value="left" <?php selected($hashform_styles['validation']['textalign'], 'left'); ?>><?php esc_html_e('Left', 'hash-form'); ?></option>
            <option value="center" <?php selected($hashform_styles['validation']['textalign'], 'center'); ?>><?php esc_html_e('Center', 'hash-form'); ?></option>
            <option value="right" <?php selected($hashform_styles['validation']['textalign'], 'right'); ?>><?php esc_html_e('Right', 'hash-form'); ?></option>
        </select>
    </div>
</div>


<h2 class="hf-settings-heading"><?php esc_html_e('Form Title', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'form_title'); ?>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Bottom Spacing', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-form-title-spacing" class="hashform-range-input-selector" type="number" name="hashform_styles[form_title][spacing]" value="<?php echo is_numeric($hashform_styles['form_title']['spacing']) ? intval($hashform_styles['form_title']['spacing']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>
</div>


<h2 class="hf-settings-heading"><?php esc_html_e('Form Description', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'form_desc'); ?>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Bottom Spacing', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-form-desc-spacing" class="hashform-range-input-selector" type="number" name="hashform_styles[form_desc][spacing]" value="<?php echo is_numeric($hashform_styles['form_desc']['spacing']) ? intval($hashform_styles['form_desc']['spacing']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>
</div>


<h2 class="hf-settings-heading"><?php esc_html_e('Heading', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'heading'); ?>
    </div>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Paragraph', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Typography', 'hash-form'); ?></label>
        <?php self::get_typography_fields('hashform_styles', $hashform_styles, 'paragraph'); ?>
    </div>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Divider', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-divider-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[divider_color]" value="<?php echo esc_attr($hashform_styles['divider_color']); ?>">
        </div>
    </div>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Star', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Size', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-star-size" class="hashform-range-input-selector" type="number" name="hashform_styles[star][size]" value="<?php echo is_numeric($hashform_styles['star']['size']) ? intval($hashform_styles['star']['size']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>
    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-star-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[star][color]" value="<?php echo esc_attr($hashform_styles['star']['color']); ?>">
        </div>
    </div>
    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Color (Active)', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-star-color-active" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[star][color_active]" value="<?php echo esc_attr($hashform_styles['star']['color_active']); ?>">
        </div>
    </div>
</div>

<h2 class="hf-settings-heading"><?php esc_html_e('Range Slider', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Height', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-range-height" class="hashform-range-input-selector" type="number" name="hashform_styles[range][height]" value="<?php echo is_numeric($hashform_styles['range']['height']) ? intval($hashform_styles['range']['height']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Handle Size', 'hash-form'); ?></label>
        <div class="hf-setting-fields hashform-range-slider-wrap">
            <div class="hashform-range-slider"></div>
            <input data-unit="px" id="hf-range-handle-size" class="hashform-range-input-selector" type="number" name="hashform_styles[range][handle_size]" value="<?php echo is_numeric($hashform_styles['range']['handle_size']) ? intval($hashform_styles['range']['handle_size']) : ''; ?>" min="0" max="100" step="1"> px
        </div>
    </div>
    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Bar Color', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-range-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[range][color]" value="<?php echo esc_attr($hashform_styles['range']['color']); ?>">
        </div>
    </div>
    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Bar Color (Active)', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-range-color-active" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[range][color_active]" value="<?php echo esc_attr($hashform_styles['range']['color_active']); ?>">
        </div>
    </div>
    <div class="hf-settings-row">
        <label class="hf-setting-label hf-color-input-label"><?php esc_html_e('Handle Color', 'hash-form'); ?></label>
        <div class="hf-setting-fields hf-color-input-field">
            <input id="hf-range-handle-color" type="text" class="color-picker hf-color-picker" data-alpha-enabled="true" data-alpha-custom-width="30px" data-alpha-color-type="hex" name="hashform_styles[range][handle_color]" value="<?php echo esc_attr($hashform_styles['range']['handle_color']); ?>">
        </div>
    </div>
</div>


<h2 class="hf-settings-heading"><?php esc_html_e('Import/Export', 'hash-form'); ?><span class="mdi mdi-triangle-small-down"></span></h2>
<div class="hf-form-settings">
    <p>
        <?php esc_html_e("You can export the form styles and then import the form styles in the same or different website.", "hash-form"); ?>
    </p>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Export', 'hash-form'); ?></label>
        <div class="hf-setting-fields">
            <form method="post"></form>
            <form method="post">
                <input type="hidden" name="hashform_imex_action" value="export_style" />
                <input type="hidden" name="hashform_style_id" value="<?php echo esc_attr($id); ?>" />
                <?php wp_nonce_field("hashform_imex_export_nonce", "hashform_imex_export_nonce"); ?>
                <button class="button button-primary" id="hashform_export" name="hashform_export"><span class="mdi mdi-tray-arrow-down"></span> <?php esc_html_e("Export Style", "hash-form") ?></button>
            </form>
        </div>
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('Import', 'hash-form'); ?></label>
        <div class="hf-setting-fields">
            <form method="post" enctype="multipart/form-data">
                <div class="hf-preview-zone hidden">
                    <div class="hf-box hf-box-solid">
                        <div class="hf-box-body"></div>
                        <button type="button" class="button hf-remove-preview">
                            <span class="mdi mdi-window-close"></span>
                        </button>
                    </div>
                </div>
                <div class="hf-dropzone-wrapper">
                    <div class="hf-dropzone-desc">
                        <span class="mdi mdi-file-image-plus-outline"></span>
                        <p><?php esc_html_e("Choose an json file or drag it here", "hash-form"); ?></p>
                    </div>
                    <input type="file" name="hashform_import_file" class="hf-dropzone">
                </div>
                <button class="button button-primary" id="hashform_import" type="submit" name="hashform_import"><i class='icofont-download'></i> <?php esc_html_e("Import", "hash-form") ?></button>
                <input type="hidden" name="hashform_imex_action" value="import_style" />
                <input type="hidden" name="hashform_style_id" value="<?php echo esc_attr($id); ?>" />
                <?php wp_nonce_field("hashform_imex_import_nonce", "hashform_imex_import_nonce"); ?>
            </form>
        </div>
    </div>
</div>