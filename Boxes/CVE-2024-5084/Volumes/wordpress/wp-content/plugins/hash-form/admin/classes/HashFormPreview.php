<?php
defined('ABSPATH') || die();

class HashFormPreview {

    public function __construct() {
        add_action('wp_ajax_hashform_preview', array($this, 'preview'));
        add_action('wp_ajax_nopriv_hashform_preview', array($this, 'preview'));
    }

    public static function preview() {
        header('Content-Type: text/html; charset=' . get_option('blog_charset'));
        $id = htmlspecialchars_decode(HashFormHelper::get_var('form', 'absint'));
        $form = HashFormBuilder::get_form_vars($id);
        require( HASHFORM_PATH . 'admin/forms/preview/preview.php' );
        wp_die();
    }

    public static function show_form($id) {
        $form = HashFormBuilder::get_form_vars($id);
        if (!$form || $form->status === 'trash')
            return esc_html__('Please select a valid form', 'hash-form');

        self::get_form_contents($id);
    }

    public static function get_form_contents($id) {
        $form = HashFormBuilder::get_form_vars($id);
        $values = HashFormHelper::get_fields_array($id);

        $styles = $form->styles ? $form->styles : '';

        $form_class = array('hashform-form');
        $form_class[] = isset($form->options['form_css_class']) ? $form->options['form_css_class'] : '';
        $form_class[] = $styles && isset($styles['form_style']) ? 'hf-form-' . esc_attr($styles['form_style']) : 'hf-form-default-style';
        $form_class = apply_filters('hashform_form_classes', $form_class);
        ?>

        <div class="hf-form-tempate">
            <form enctype="multipart/form-data" method="post" class="<?php echo esc_attr(implode(' ', array_filter($form_class))); ?>" id="hf-form-id-<?php echo esc_attr($form->form_key); ?>" novalidate>
                <?php
                require HASHFORM_PATH . '/admin/forms/style/form.php';
                ?>
            </form>
        </div>
        <?php
    }

}

new HashFormPreview();
