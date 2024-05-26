<?php
defined('ABSPATH') || die();

class HashFormFieldHeading extends HashFormFieldType {

    protected $type = 'heading';

    public function field_settings_for_type() {
        return array(
            'label' => false,
            'default' => false,
            'description' => false,
            'label_position' => false,
            'required' => false,
            'content' => true,
            'field_alignment' => true,
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'heading_type' => 'h1',
            'content' => 'Heading',
            'text_alignment' => 'left',
            'field_alignment' => 'left',
        );
    }

    protected function input_html() {
        $field = $this->get_field();
        ?>
        <<?php echo (isset($field['heading_type']) ? esc_attr($field['heading_type']) : 'h1'); ?> class="hf-heading-field" id="hf-field-<?php echo esc_attr($field['id']); ?>">
        <?php echo isset($field['content']) ? esc_html($field['content']) : ''; ?>
        </<?php echo (isset($field['heading_type']) ? esc_attr($field['heading_type']) : 'h1'); ?>>
        <?php
    }

}
