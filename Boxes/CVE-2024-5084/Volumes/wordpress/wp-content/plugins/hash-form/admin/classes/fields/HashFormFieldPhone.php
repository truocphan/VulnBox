<?php
defined('ABSPATH') || die();

class HashFormFieldPhone extends HashFormFieldType {

    protected $type = 'phone';

    protected function field_settings_for_type() {
        return array(
            'clear_on_focus' => true,
            'invalid' => true,
            'format' => true,
            'max' => true,
        );
    }

    public function validate($args) {
        $errors = array();
        $pattern = self::phone_format($this->field);
        $max_length = intval(HashFormFields::get_option($this->field, 'max'));

        if (!preg_match($pattern, $args['value'])) {
            $errors['field' . $args['id']] = HashFormFields::get_error_msg($this->field, 'invalid');
        }

        if ($max_length && strlen($args['value']) > $max_length) {
            $errors['field' . $args['id']] = HashFormFields::get_error_msg($this->field, 'max_char');
        }
        return $errors;
    }

    public static function phone_format($field) {
        $pattern = HashFormFields::get_option($field, 'format');

        if (trim($pattern) != '') {
            $pattern = '^((\+\d{1,3}(-|.| )?\(?\d\)?(-| |.)?\d{1,5})|(\(?\d{2,6}\)?))(-|.| )?(\d{3,4})(-|.| )?(\d{4})(( x| ext)\d{1,5}){0,1}$';
        }
        $pattern = '/' . $pattern . '/';
        return $pattern;
    }

    protected function input_html() {
        ?>
        <input type="text" <?php $this->field_attrs(); ?>/>
        <?php
    }

}
