<?php
defined('ABSPATH') || die();
?>

<div id="hf-add-form-modal">
    <div class="hf-add-form-modal-wrap">
        <form id="hf-add-template" method="post">
            <h3><?php esc_attr_e('Create New Form', 'hash-form'); ?></h3>

            <div class="hf-form-row">
                <label for="hf-form-name"><?php esc_html_e('Form Name', 'hash-form'); ?></label>
                <input type="text" name="template_name" id="hf-form-name" />
            </div>

            <div class="hf-add-form-footer">
                <a href="#" class="hashform-close-form-modal"><?php esc_html_e('Cancel', 'hash-form'); ?></a>
                <button type="submit"><?php esc_html_e('Create', 'hash-form'); ?></button>
            </div>
        </form>
    </div>
</div>