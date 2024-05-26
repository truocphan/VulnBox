<?php
defined('ABSPATH') || die();

class HashFormFieldImageSelect extends HashFormFieldType {

    protected $type = 'image_select';

    protected function field_settings_for_type() {
        return array(
            'default' => false,
            'image_max_width' => true
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'image_id' => '',
            'image_size' => '',
            'select_option_type' => 'radio',
            'options_layout' => 'inline',
            'image_max_width' => '',
            'image_max_width_unit' => '%',
        );
    }

    private function get_url($image_id) {
        $image_id = (int) $image_id;
        $src = wp_get_attachment_image_src($image_id, 'full');
        $url = is_array($src) ? $src[0] : '';
        if (!$url) {
            $url = wp_get_attachment_image_url($image_id);
        }
        return $url ? $url : '';
    }

    protected function input_html() {
        $field = $this->get_field();

        $options = $field['options'] ? $field['options'] : array();
        $default = $field['default_value'] ? $field['default_value'] : array();
        $field_type = $field['select_option_type'];
        ?>

        <div class="hf-choice-container">
            <?php
            foreach ($options as $option_key => $option) {
                ?>
                <div class="hf-choice hf-<?php echo esc_attr($field_type); ?>">
                    <label for="<?php echo esc_attr($this->html_id('-' . $option_key)); ?>">
                        <input type="<?php echo esc_attr($field_type); ?>" name="<?php echo esc_attr($this->html_name()) . '[]'; ?>" id="<?php echo esc_attr($this->html_id('-' . $option_key)); ?>" value="<?php echo esc_attr($option['label']); ?>" <?php checked(in_array($option['label'], $default), true); ?>>
                        <div class="hf-field-is-container hf-field-is-has-label">
                            <div class="hf-field-is-image">
                                <span class="hf-field-is-checked mdi-check-circle"></span>
                                <?php
                                if (isset($option['image_id']) && $option['image_id']) {
                                    ?>
                                    <img src="<?php echo esc_url($this->get_url($option['image_id'])); ?>" alt="<?php echo esc_attr($option['label']); ?>">
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="hf-field-is-label"><?php echo esc_html($option['label']); ?></div>
                        </div>
                    </label>
                </div>
                <?php
            }
            ?>

        </div>
        <?php
    }

}
