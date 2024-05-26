<?php
defined('ABSPATH') || die();
?>
<div class="hf-form-container hf-grid-container">
    <div class="hf-form-row hf-multiple-rows hf-grid-container">
        <div class="hf-grid-3">
            <label><?php esc_html_e('To Email', 'hash-form'); ?></label>
            <div class="hf-multiple-email">
                <?php
                $email_to_array = explode(',', $settings['email_to']);
                foreach ($email_to_array as $row) {
                    ?>
                    <div class="hf-email-row">
                        <input type="email" name="email_to[]" value="<?php echo esc_attr($row); ?>"/>
                        <span class="mdi mdi-trash-can-outline hf-delete-email-row"></span>
                    </div>
                <?php } ?>
            </div>
            <button type="button" class="button button-primary hf-add-email"><?php esc_html_e('Add More Email', 'hash-form'); ?></button>
            <p></p>
            <p class="description"><?php esc_html_e('Use [admin_email] for admin email. Settings > General > Administration Email Address', 'hash-form'); ?></p>
        </div>
    </div>

    <div class="hf-form-row hf-grid-container">
        <div class="hf-grid-3">
            <label class="hf-label-with-attr">
                <?php esc_html_e('Reply To', 'hash-form'); ?>
                <div class="hf-attr-field">
                    <div class="hf-attr-field-tags">
                        <span class="mdi mdi-tag-multiple"></span>Tags
                    </div>
                    <ul class="hf-add-field-attr-to-form">
                        <?php
                        foreach ($fields as $field) {
                            if ($field->type == 'email') {
                                ?>
                                <li data-value="#field_id_<?php echo esc_attr($field->id); ?>">
                                    <?php echo esc_html($field->name); ?><span>#field_id_<?php echo esc_html($field->id); ?></span>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </label>
            <input type="text" name="reply_to_email" value="<?php echo esc_attr($settings['reply_to_email']); ?>"/>
            <p class="description"><?php esc_html_e('Choose the email field by clicking on the TAGS above.', 'hash-form'); ?></p>
        </div>
    </div>

    <div class="hf-form-row hf-grid-container">
        <div class="hf-grid-3">
            <label><?php esc_html_e('From Email', 'hash-form'); ?></label>
            <input type="text" name="email_from" value="<?php echo esc_attr($settings['email_from']); ?>"/>
            <p class="description"><?php esc_html_e('Use [admin_email] for admin email. Settings > General > Administration Email Address', 'hash-form'); ?></p>
            <p class="description" style="color:red;"><?php esc_html_e('IMPORTANT: The email address should match with your domain name for proper delivery. eg. admin@yourwebsite.com', 'hash-form'); ?></p>
        </div>
    </div>

    <div class="hf-form-row hf-grid-container">
        <div class="hf-grid-3">
            <label><?php esc_html_e('From Name', 'hash-form'); ?></label>
            <input type="text" name="email_from_name" value="<?php echo esc_attr($settings['email_from_name']); ?>"/>
        </div>
    </div>

    <div class="hf-form-row">
        <label class="hf-label-with-attr">
            <?php esc_html_e('Subject', 'hash-form'); ?>
            <div class="hf-attr-field">
                <div class="hf-attr-field-tags">
                    <span class="mdi mdi-tag-multiple"></span>Tags
                </div>
                <ul class="hf-add-field-attr-to-form">
                    <?php
                    foreach ($fields as $field) {
                        if (!($field->type == 'heading' || $field->type == 'paragraph' || $field->type == 'separator' || $field->type == 'spacer' || $field->type == 'image' || $field->type == 'captcha')) {
                            ?>
                            <li data-value="#field_id_<?php echo esc_attr($field->id); ?>">
                                <?php echo esc_html($field->name); ?><span>#field_id_<?php echo esc_html($field->id); ?></span>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </label>

        <input type="text" name="email_subject" value="<?php echo esc_attr($settings['email_subject']); ?>"/>
    </div>

    <div class="hf-form-row">
        <label class="hf-label-with-attr">
            <?php esc_html_e('Message', 'hash-form'); ?>
            <div class="hf-attr-field">
                <div class="hf-attr-field-tags">
                    <span class="mdi mdi-tag-multiple"></span>Tags
                </div>
                <ul class="hf-add-field-attr-to-form">
                    <?php
                    foreach ($fields as $field) {
                        if (!($field->type == 'heading' || $field->type == 'paragraph' || $field->type == 'separator' || $field->type == 'spacer' || $field->type == 'image' || $field->type == 'captcha')) {
                            ?>
                            <li data-value="#field_id_<?php echo esc_attr($field->id); ?>">
                                <?php echo esc_html($field->name); ?><span>#field_id_<?php echo esc_html($field->id); ?></span>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </label>
        <textarea name="email_message" rows="5"><?php echo esc_textarea($settings['email_message']); ?></textarea>
        <p class="description"><?php esc_html_e('Use #form_title for form title, #form_details for form inputs', 'hash-form'); ?></p>
    </div>
</div>