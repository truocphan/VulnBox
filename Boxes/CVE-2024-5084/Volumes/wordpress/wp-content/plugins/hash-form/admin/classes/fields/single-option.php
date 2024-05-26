<?php
defined('ABSPATH') || die();

if ($field['type'] == 'image_select') {
    $field_type = $field['select_option_type'] ? esc_attr($field['select_option_type']) : 'radio';
    $field_name = 'default_value_' . absint($field['id']) . '[' . esc_attr($opt_key) . ']';
} else if ($field['type'] == 'select') {
    $field_type = 'radio';
    $field_name = 'default_value_' . absint($field['id']);
} else {
    $field_type = $field['type'];
    $field_name = $field_type == 'radio' ? 'default_value_' . absint($field['id']) : 'default_value_' . esc_attr($field['id']) . '[' . esc_attr($opt_key) . ']';
}
?>
<li id="hf-option-list-<?php echo absint($field['id']) . '-' . esc_attr($opt_key); ?>" data-optkey="<?php echo esc_attr($opt_key); ?>" class="<?php echo ($opt_key === '000' ? ' hf-hidden hf-option-template' : ''); ?>">
    <div class="hf-single-option">
        <span class="mdi mdi-drag hf-drag"></span>
        <input class="hf-choice-input" type="<?php echo esc_attr($field_type); ?>" name="<?php echo esc_attr($field_name); ?>" value="<?php echo esc_attr($field_val); ?>" <?php echo wp_kses_post($checked); ?>/>

        <input class="<?php echo esc_attr($html_id . '-' . $opt_key); ?>" type="text" name="field_options[options_<?php echo esc_attr($field['id']); ?>][<?php echo esc_attr($opt_key); ?>][label]" value="<?php echo esc_attr($field_val); ?>" />

        <a href="javascript:void(0)" class="hf-remove-field" data-fid="<?php echo esc_attr($field['id']); ?>" data-removeid="hf-option-list-<?php echo absint($field['id']) . '-' . esc_attr($opt_key); ?>" >
            <span class="mdi mdi-trash-can-outline"></span>
        </a>
    </div>
    <?php
    if ($field['type'] == 'image_select') {
        $opt = isset($field['options'][$opt_key]) ? $field['options'][$opt_key] : '';
        $image_id = isset($opt['image_id']) ? absint($opt['image_id']) : 0;
        $src = wp_get_attachment_image_src($image_id, 'full');
        $url = is_array($src) ? $src[0] : '';
        if (!$url) {
            $url = wp_get_attachment_image_url($image_id);
        }
        $image = array(
            'id' => $image_id,
            'url' => $url ? $url : '',
        );
        ?>
        <div class="hf-is-image-preview field_<?php echo esc_attr($field['id']); ?>_image_id">
            <input type="hidden" class="hf-image-id" name="field_options[options_<?php echo esc_attr($field['id']); ?>][<?php echo esc_attr($opt_key); ?>][image_id]" id="hf-field-image-<?php echo absint($field['id']) . '-' . esc_attr($opt_key); ?>" value="<?php echo (empty($image['id']) ? '' : absint($image['id'])); ?>" />
            <div class="hf-is-image-preview-box<?php echo (empty($image['url']) ? '' : ' hf-image-added'); ?>">
                <span class="hf-is-image-holder">
                    <?php
                    if (!empty($image['url'])) {
                        ?>
                        <img id="hf-is-image-preview-<?php echo absint($field['id']) . '-' . esc_attr($opt_key); ?>" src="<?php echo esc_url($image['url']); ?>"/>
                        <?php
                    }
                    ?>
                </span>
                <a class="hf-is-remove-image" href="#"><span class="mdi mdi-close"></span></a>
            </div>
        </div>
        <?php
    }
    ?>
</li>
