<?php
defined('ABSPATH') || die();

$id = htmlspecialchars_decode(HashFormHelper::get_var('id', 'absint'));
$form = HashFormBuilder::get_form_vars($id);

if (!$form) {
    ?>
    <h3><?php esc_html_e('You are trying to edit a form that does not exist.', 'hash-form'); ?></h3>
    <?php
    return;
}
$fields = HashFormFields::get_form_fields($form->id);
$values = HashFormHelper::process_form_array($form);

$edit_message = '<span class="mdi mdi-check-circle"></span>' . esc_html__('Form was successfully updated.', 'hash-form');
$has_fields = isset($fields) && !empty($fields);

if (!empty($fields)) {
    $vars = HashFormHelper::get_fields_array($id);
}

if (defined('DOING_AJAX')) {
    wp_die();
} else {
    ?>
    <div id="hf-wrap" class="hf-content">
        <?php
        self::get_admin_header(
                array(
                    'form' => $form,
                    'class' => 'hf-header-nav',
                )
        );
        ?>
        <div class="hf-body">
            <?php require( HASHFORM_PATH . 'admin/forms/build/sidebar.php' ); ?>

            <div id="hf-form-panel">
                <div class="hf-form-wrap">
                    <form method="post">
                        <?php require( HASHFORM_PATH . 'admin/forms/build/builder.php' ); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}