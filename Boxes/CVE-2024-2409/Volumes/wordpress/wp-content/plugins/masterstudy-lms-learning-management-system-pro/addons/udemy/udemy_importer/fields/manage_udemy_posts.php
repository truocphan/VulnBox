<?php
/**
 * @var $field
 * @var $field_name
 * @var $section_name
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";

require STM_LMS_PRO_ADDONS . '/udemy/udemy_importer/components_js/manage_udemy_posts.php';
?>

<stm-manage-post-type v-bind:post_type="<?php echo esc_attr( $field_key ); ?>['post_type']" v-bind:meta_key="<?php echo esc_attr( $field_key ); ?>['meta_key']"></stm-manage-post-type>
