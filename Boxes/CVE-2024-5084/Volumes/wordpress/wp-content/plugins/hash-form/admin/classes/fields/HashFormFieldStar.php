<?php
defined('ABSPATH') || die();

class HashFormFieldStar extends HashFormFieldType {

    protected $type = 'star';
    protected $array_allowed = false;

    public function field_settings_for_type() {
        return array(
            'default' => false,
            'max_width' => false,
        );
    }

    public function show_primary_options() {
        $field = $this->get_field();
        ?>
        <div class="hf-form-row">
            <label>
                <?php esc_html_e('Maximum Rating', 'hash-form'); ?>
            </label>
            <input type="number" name="field_options[maxnum_<?php echo esc_attr($field['id']); ?>]" value="<?php echo esc_attr($field['maxnum']); ?>" min="1" max="50" step="1" data-changestars="hf-field-star-<?php echo esc_attr($field['id']); ?>"/>
            <input type="hidden" name="field_options[minnum_<?php echo esc_attr($field['id']); ?>]"/>
        </div>
        <?php
    }

    public function sanitize_value(&$value) {
        return HashFormHelper::sanitize_value('intval', $value);
    }

    protected function input_html() {
        $field = $this->get_field();
        $max = isset($field['maxnum']) ? $field['maxnum'] : 5;
        $field['options'] = range(1, $max);
        ?>

        <div class="hashform-star-group" id="hf-field-star-<?php echo esc_attr($field['id']); ?>">
            <?php
            foreach ($field['options'] as $opt_key => $opt) {
                ?>
                <label class="hf-star-rating">
                    <input type="radio" name="<?php echo esc_attr($this->html_name()); ?>" value="<?php echo esc_attr($opt); ?>"/>
                    <span class="mdi mdi-star-outline"></span>
                </label>
                <?php
            }
            ?>
        </div>
        <?php
    }

}
