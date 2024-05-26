<?php
defined('ABSPATH') || die();
?>
<div class="hf-form-container hf-grid-container">
    <div class="hf-form-row">
        <div class="hf-condition-repeater-blocks">
            <?php
            $conditional_logics = HashFormBuilder::get_show_hide_conditions($id);
            foreach ($conditional_logics as $key => $row) {
                ?>
                <div class="hf-condition-repeater-block">
                    <select name="condition_action[]" required>
                        <option value="show" <?php
                        if (isset($row['condition_action'])) {
                            selected($row['condition_action'], 'show');
                        }
                        ?>>Show</option>
                        <option value="hide" <?php
                        if (isset($row['condition_action'])) {
                            selected($row['condition_action'], 'hide');
                        }
                        ?>>Hide</option>
                    </select>
                    <select name="compare_from[]" required>
                        <option value="">Select Field</option>
                        <?php
                        foreach ($fields as $field) {
                            if (!($field->type == 'heading' || $field->type == 'paragraph' || $field->type == 'separator' || $field->type == 'spacer' || $field->type == 'image' || $field->type == 'captcha')) {
                                ?>
                                <option value="<?php echo esc_attr($field->id); ?>" <?php
                                if (isset($row['compare_from'])) {
                                    selected($row['compare_from'], $field->id);
                                }
                                ?>><?php echo esc_html($field->name) . ' (ID: ' . esc_attr($field->id) . ')'; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                    </select>
                    <span class="hf-condition-seperator">if</span>
                    <select name="compare_to[]" required>
                        <option value="">Select Field</option>
                        <?php
                        foreach ($fields as $field) {
                            if (!($field->type == 'heading' || $field->type == 'paragraph' || $field->type == 'separator' || $field->type == 'spacer' || $field->type == 'image' || $field->type == 'captcha' || $field->type == 'name' || $field->type == 'address')) {
                                ?>
                                <option value="<?php echo esc_attr($field->id); ?>" <?php
                                if (isset($row['compare_to'])) {
                                    selected($row['compare_to'], $field->id);
                                }
                                ?>><?php echo esc_html($field->name) . ' (ID: ' . esc_attr($field->id) . ')'; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                    </select>
                    <select name="compare_condition[]" required>
                        <option value="equal" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'equal');
                        }
                        ?>>Equals to</option>
                        <option value="not_equal" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'not_equal');
                        }
                        ?>>Not Equals to</option>
                        <option value="greater_than" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'greater_than');
                        }
                        ?>>Greater Than</option>
                        <option value="greater_than_or_equal" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'greater_than_or_equal');
                        }
                        ?>>Greater Than Or Equals to</option>
                        <option value="less_than" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'less_than');
                        }
                        ?>>Less Than</option>
                        <option value="less_than_or_equal" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'less_than_or_equal');
                        }
                        ?>>Less Than Or Equals to</option>
                        <option value="is_like" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'is_like');
                        }
                        ?>>Is Like</option>
                        <option value="is_not_like" <?php
                        if (isset($row['compare_condition'])) {
                            selected($row['compare_condition'], 'is_not_like');
                        }
                        ?>>Is Not Like</option>
                    </select>
                    <input type="text" name="compare_value[]" value="<?php echo esc_attr($row['compare_value']); ?>" required/>
                    <span class="hf-condition-remove mdi mdi-close"></span>
                </div>
            <?php } ?>
        </div>
        <button class="hf-add-more-condition"><span class="mdi mdi-plus"></span><?php echo esc_html__('Add Condition', 'hash-form'); ?></button>
    </div>
</div>