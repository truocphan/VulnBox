<?php
/**
 * @var $field_name
 * @var $section_name
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";
$sections = "data['{$section_name}']['fields']['curriculum_sections']['value']";

include STM_LMS_PATH .'/settings/drip_content/components_js/drip_content.php';

?>

<stm-autocomplete-drip-content
	inline-template
	v-bind:label="<?php echo esc_attr($field_key); ?>['label']"
	v-bind:posts="<?php echo esc_attr($field_key) ?>['post_type']"
	v-bind:stored_ids="<?php echo esc_attr($field_key) ?>['value']"
	v-on:autocomplete-ids="<?php echo esc_attr($field_key) ?>['value'] = $event">
	<?php require_once(STM_LMS_PATH .'/settings/drip_content/components/drip_content.php'); ?>
</stm-autocomplete-drip-content>

<input type="hidden"
       name="<?php echo esc_attr($field_name); ?>"
       v-model="<?php echo esc_attr($field_key); ?>['value']" />