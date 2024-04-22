<?php
/**
 * @var $field
 * @var $field_name
 * @var $section_name
 * @var $field_data
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";
$requirements = "data['{$section_name}']['fields']['{$field_data['requirements']}']['value']";
$view_type = "data['{$section_name}']['fields']['question_view_type']['value']";

include STM_LMS_PATH . '/settings/answers/components_js/answers.php';
?>

<div class="wpcfto-field-aside">
    <label class="wpcfto-field-aside__label" v-html="<?php echo esc_attr($field_key); ?>['label']"></label>
</div>

<div class="wpcfto-field-content">
    <stm-answers v-bind:stored_answers="<?php echo esc_attr($field_key); ?>['value']"
                 v-on:get-answers="<?php echo esc_attr($field_key); ?>['value'] = $event"
                 v-bind:view_type="<?php echo sanitize_text_field($view_type); ?>"
                 v-bind:choice="<?php echo sanitize_text_field($requirements); ?>"></stm-answers>
</div>

<div v-for="(answer, key) in <?php echo esc_attr($field_key); ?>['value']">
    <div v-for="(answer_data, property) in answer">
        <input type="hidden"
               v-bind:name="'<?php echo esc_attr($field_name); ?>' + '[' + key + '][' + property + ']'"
               v-model="<?php echo esc_attr($field_key); ?>['value'][key][property]"/>
    </div>
</div>