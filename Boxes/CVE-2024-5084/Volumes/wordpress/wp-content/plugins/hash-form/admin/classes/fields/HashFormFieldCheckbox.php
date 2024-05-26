<?php
defined('ABSPATH') || die();

class HashFormFieldCheckbox extends HashFormFieldType {

    protected $type = 'checkbox';

    public function field_settings_for_type() {
        return array(
            'default' => false,
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'options_layout' => 'inline',
        );
    }

    protected function input_html() {
        $field = $this->get_field();
        $options = $field['options'] ? $field['options'] : array();
        $default = $field['default_value'] ? $field['default_value'] : array();
        ?>
        <div class="hf-choice-container">
            <?php
            foreach ($options as $option_key => $option) {
                ?>
                <div class="hf-choice hf-checkbox">
                    <label for="<?php echo esc_attr($this->html_id('-' . $option_key)); ?>">
                        <input type="checkbox" id="<?php echo esc_attr($this->html_id('-' . $option_key)) ?>" name="<?php echo esc_attr($this->html_name()) . '[]'; ?>" value="<?php echo esc_attr($option['label']); ?>" <?php checked(in_array($option['label'], $default), true); ?>/>
                        <?php echo esc_html($option['label']); ?>
                    </label>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

}
