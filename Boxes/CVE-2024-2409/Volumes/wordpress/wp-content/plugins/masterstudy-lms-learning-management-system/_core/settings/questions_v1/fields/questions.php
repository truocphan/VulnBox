<?php
/**
 * @var $field_name
 * @var $section_name
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";

include STM_LMS_PATH .'/settings/answers/components_js/answers.php';
include STM_LMS_PATH .'/settings/questions_v1/components_js/questions.php';
?>

<stm-questions
		v-bind:posts="<?php echo esc_attr($field_key) ?>['post_type']"
		v-bind:stored_ids="<?php echo esc_attr($field_key) ?>['value']"
		v-on:get-questions="<?php echo esc_attr($field_key) ?>['value'] = $event"></stm-questions>

<input type="hidden"
	   name="<?php echo esc_attr($field_name); ?>"
	   v-model="<?php echo esc_attr($field_key); ?>['value']" />
