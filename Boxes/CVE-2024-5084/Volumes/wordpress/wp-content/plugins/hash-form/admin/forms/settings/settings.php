<?php
defined('ABSPATH') || die();

$id = HashFormHelper::get_var('id', 'absint', 0);
$form = HashFormBuilder::get_form_vars($id);
$fields = HashFormFields::get_form_fields($id);

$settings = $form->settings ? $form->settings : HashFormHelper::get_form_settings_default();
?>
<div id="hf-wrap" class="hf-content">
    <?php
    self::get_admin_header(
            array(
                'form' => $form,
                'class' => 'hf-header-nav',
            )
    );

    $sections = array(
        'email-settings' => array(
            'name' => esc_html__('Email Settings', 'hash-form'),
            'icon' => 'mdi mdi-email-outline'
        ),
        'auto-responder' => array(
            'name' => esc_html__('Auto Responder', 'hash-form'),
            'icon' => 'mdi mdi-email-arrow-left-outline'
        ),
        'form-confirmation' => array(
            'name' => esc_html__('Confirmation', 'hash-form'),
            'icon' => 'mdi mdi-send-check'
        ),
        'conditional-logic' => array(
            'name' => esc_html__('Conditional Logic', 'hash-form'),
            'icon' => 'mdi mdi-checkbox-multiple-marked-outline'
        ),
        'import-export' => array(
            'name' => esc_html__('Import/Export', 'hash-form'),
            'icon' => 'mdi mdi-swap-horizontal'
        ),
    );
    $current = 'email-settings';
    ?>
    <div class="hf-body">
        <div class="hf-fields-sidebar">
            <ul class="hf-settings-tab">
                <?php foreach ($sections as $key => $section) { ?>
                    <li class="<?php echo ($current === $key ? 'hf-active' : ''); ?>">
                        <a href="#hf-<?php echo esc_attr($key); ?>">
                            <i class="<?php echo esc_attr($section['icon']) ?>"></i>
                            <?php echo esc_html($section['name']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div id="hf-form-panel">
            <?php HashFormHelper::print_message(); ?>
            <div class="hf-form-wrap">
                <form method="post" id="hf-settings-form">
                    <input type="hidden" name="id" id="form_id" value="<?php echo esc_attr($id); ?>" />
                    <?php
                    wp_nonce_field('hashform_process_form_nonce', 'process_form');
                    foreach ($sections as $key => $section) {
                        ?>
                        <div id="hf-<?php echo esc_attr($key); ?>" class="<?php echo (($current === $key) ? '' : ' hf-hidden'); ?>">
                            <h2><?php echo esc_html($section['name']); ?></h2>
                            <?php require HASHFORM_PATH . 'admin/forms/settings/' . esc_attr($key) . '.php'; ?>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>