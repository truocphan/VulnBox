<?php
defined('ABSPATH') || die();

class HashFormFieldUrl extends HashFormFieldType {

    protected $type = 'url';

    protected function field_settings_for_type() {
        return array(
            'clear_on_focus' => true,
            'invalid' => true,
        );
    }

    public function validate($args) {
        $errors = array();

        $value = $args['value'];
        if (trim($value) == 'http://' || empty($value)) {
            $value = '';
        } else {
            $value = esc_url_raw($value);
            $value = preg_match('/^(https?|ftps?|mailto|news|feed|telnet):/is', $value) ? $value : 'http://' . $value;
        }

        if (!empty($value) && !preg_match('/^http(s)?:\/\/(?:localhost|(?:[\da-z\.-]+\.[\da-z\.-]+))/i', $value)) {
            $errors['field' . $args['id']] = HashFormFields::get_error_msg($this->field, 'invalid');
        }

        return $errors;
    }

    public function sanitize_value(&$value) {
        return HashFormHelper::sanitize_value('esc_url_raw', $value);
    }

    protected function input_html() {
        $field_type = $this->type;
        ?>
        <input type="<?php echo esc_attr($field_type); ?>" <?php $this->field_attrs(); ?>/>
        <?php
    }

}
