<?php
defined('ABSPATH') || die();

class HashFormFieldTime extends HashFormFieldType {

    protected $type = 'time';

    protected function field_settings_for_type() {
        return array(
            'clear_on_focus' => true,
            'invalid' => true,
        );
    }

    protected function extra_field_default_opts() {
        return array(
            'step' => '60',
            'min_time' => '00:00',
            'max_time' => '23:59',
        );
    }

    protected function input_html() {
        $field = $this->get_field();
        $step = intval($field['step']);
        $step = $step ? $step : 60;
        ?>
        <input type="text" class="hf-timepicker" data-step="<?php echo absint($step); ?>" data-min-time="<?php echo esc_attr($field['min_time']); ?>" data-max-time="<?php echo esc_attr($field['max_time']); ?>" <?php $this->field_attrs(); ?>>
        <?php
    }

    protected function prepare_esc_value() {
        $field = $this->get_field();
        $value = isset($field['default_value']) ? $field['default_value'] : '';
        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        if (strpos($value, '&lt;') !== false) {
            $value = htmlentities($value);
        }

        if (!$value) {
            return $value;
        }

        $time_value = explode(":", $value);
        $hour = absint($time_value[0]);
        $hour = $hour % 24;
        $ampm = ($hour < 12 ? "am" : "pm");

        $hour = $hour % 12;
        $hour = $hour ? $hour : 12;

        $minute = isset($time_value[1]) ? absint($time_value[1]) : 0;
        $minute = $minute % 60;

        if ($minute < 10) {
            $minute = '0' . absint($minute);
        }

        return $hour . ':' . $minute . $ampm;
    }

}
