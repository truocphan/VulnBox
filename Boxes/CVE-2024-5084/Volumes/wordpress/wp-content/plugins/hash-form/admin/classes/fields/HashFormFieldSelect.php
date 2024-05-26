<?php
defined('ABSPATH') || die();

class HashFormFieldSelect extends HashFormFieldType {

    protected $type = 'select';

    protected function field_settings_for_type() {
        return array(
            'clear_on_focus' => true,
            'default' => false,
            'auto_width' => true
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'auto_width' => 'on'
        );
    }

    protected function input_html() {
        $field = $this->get_field();
        $options = $field['options'] ? $field['options'] : array();
        $default = $field['default_value'] ? $field['default_value'] : '';
        $placeholder = trim($field['placeholder']);
        ?>
        <select <?php $this->field_attrs(); ?>>
            <?php
            if ($placeholder) {
                ?>
                <option value=""><?php echo esc_html($placeholder); ?></option>
                <?php
            }
            foreach ($options as $option) {
                ?>
                <option value="<?php echo esc_attr($option['label']); ?>" <?php selected(($option['label'] == $default), true); ?>><?php echo esc_html($option['label']); ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }

}
