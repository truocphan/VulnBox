<?php
defined('ABSPATH') || die();

class HashFormFieldSeparator extends HashFormFieldType {

    protected $type = 'separator';

    public function field_settings_for_type() {
        return array(
            'label' => false,
            'default' => false,
            'description' => false,
            'label_position' => false,
            'required' => false,
            'field_alignment' => true,
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'border_width' => '2',
            'border_style' => 'solid',
            'field_alignment' => 'left',
        );
    }

    protected function input_html() {
        $field = $this->get_field();
        ?>
        <div class="hf-separator-border" style="border-bottom-style:<?php echo esc_attr($field['border_style']); ?>; border-bottom-width:<?php echo esc_attr($field['border_width']) . 'px'; ?>;" id="field_change_style_<?php echo absint($field['id']); ?>"></div>
        <?php
    }

}
