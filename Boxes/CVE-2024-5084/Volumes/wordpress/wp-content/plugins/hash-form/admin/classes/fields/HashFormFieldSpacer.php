<?php
defined('ABSPATH') || die();

class HashFormFieldSpacer extends HashFormFieldType {

    protected $type = 'spacer';

    public function field_settings_for_type() {
        return array(
            'name' => false,
            'valuee' => false,
            'label' => false,
            'default' => false,
            'description' => false,
            'label_position' => false,
            'required' => false,
            'max_width' => false,
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'spacer_height' => '50',
        );
    }

    protected function input_html() {
        $field = $this->get_field();
        ?>
        <div id="field_change_height_<?php echo esc_attr($this->field_id); ?>" style="height:<?php echo esc_attr($field['spacer_height']) . 'px'; ?>;" <?php $this->field_attrs(); ?>></div>
        <?php
    }

}
