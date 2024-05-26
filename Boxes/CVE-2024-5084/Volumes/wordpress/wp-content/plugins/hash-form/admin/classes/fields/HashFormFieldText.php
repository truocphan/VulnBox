<?php
defined('ABSPATH') || die();

class HashFormFieldText extends HashFormFieldType {

    protected $type = 'text';

    protected function field_settings_for_type() {
        return array(
            'clear_on_focus' => true,
            'invalid' => true,
            'format' => true,
            'max' => true
        );
    }

    public function validate($args) {
        $errors = array();

        $pattern = self::format($this->field);
        $max_length = intval(HashFormFields::get_option($this->field, 'max'));

        if (!preg_match($pattern, $args['value'])) {
            $errors['field' . $args['id']] = HashFormFields::get_error_msg($this->field, 'invalid');
        }

        if ($max_length && strlen($args['value']) > $max_length) {
            $errors['field' . $args['id']] = HashFormFields::get_error_msg($this->field, 'max_char');
        }
        return $errors;
    }

    public static function format($field) {
        $pattern = HashFormFields::get_option($field, 'format');
        $pattern = '/' . $pattern . '/';
        return $pattern;
    }

    protected function input_html() {
        ?>
        <input type="text" <?php $this->field_attrs(); ?>/>
        <?php
    }

}
