<?php
defined('ABSPATH') || die();
?>

<div class="hf-form-container hf-grid-container">
    <div class="hf-form-row">
        <?php esc_html_e("You can export the settings and then import the form in the same or different website.", "hash-form"); ?>
    </div>

    <div class="hf-form-row">
        <h3><?php esc_html_e('Export', 'hash-form'); ?></h3>
        <form method="post"></form>
        <form method="post">
            <input type="hidden" name="hashform_imex_action" value="export_form" />
            <input type="hidden" name="hashform_form_id" value="<?php echo esc_attr($id); ?>" />
            <?php wp_nonce_field("hashform_imex_export_nonce", "hashform_imex_export_nonce"); ?>
            <button class="button button-primary" id="hashform_export" name="hashform_export"><span class="mdi mdi-tray-arrow-down"></span> <?php esc_html_e("Export Form", "hash-form") ?></button>
        </form>
    </div>

    <div class="hf-form-row"></div>

    <div class="hf-form-row">
        <h3><?php esc_html_e('Import', 'hash-form'); ?></h3>
        <form method="post" enctype="multipart/form-data">
            <div class="hf-preview-zone hidden">
                <div class="hf-box hf-box-solid">
                    <div class="hf-box-body"></div>
                    <button type="button" class="button hf-remove-preview">
                        <span class="mdi mdi-window-close"></span>
                    </button>
                </div>
            </div>
            <div class="hf-dropzone-wrapper">
                <div class="hf-dropzone-desc">
                    <span class="mdi mdi-file-image-plus-outline"></span>
                    <p><?php esc_html_e("Choose an json file or drag it here", "hash-form"); ?></p>
                </div>
                <input type="file" name="hashform_import_file" class="hf-dropzone">
            </div>
            <button class="button button-primary" id="hashform_import" type="submit" name="hashform_import"><i class='icofont-download'></i> <?php esc_html_e("Import", "hash-form") ?></button>
            <input type="hidden" name="hashform_imex_action" value="import_form" />
            <input type="hidden" name="hashform_form_id" value="<?php echo esc_attr($id); ?>" />
            <?php wp_nonce_field("hashform_imex_import_nonce", "hashform_imex_import_nonce"); ?>
        </form>
    </div>
</div>