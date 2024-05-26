<?php
defined('ABSPATH') || die();

class HashFormFieldDate extends HashFormFieldType {

    protected $type = 'date';

    protected function field_settings_for_type() {
        return array(
            'clear_on_focus' => true,
            'invalid' => true,
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'format' => 'MM dd, yy'
        );
    }

    protected function input_html() {
        $field = $this->get_field();
        ?>
        <input type="text" data-format="<?php echo esc_attr($field['date_format']); ?>" <?php $this->field_attrs(); ?> autocomplete="off"/>
        <?php
    }

}
