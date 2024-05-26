<?php
defined('ABSPATH') || die();

$id = htmlspecialchars_decode(HashFormHelper::get_var('id', 'absint'));
$form = HashFormBuilder::get_form_vars($id);

if (!$form) {
    echo '<h3>' . esc_html__('You are trying to edit a form that does not exist.', 'hash-form') . '</h3>';
    return;
}

$fields = HashFormFields::get_form_fields($id);
$styles = $form->styles ? $form->styles : array();
$form_style = isset($styles['form_style']) ? $styles['form_style'] : 'default-style';
$form_style_template = isset($styles['form_style_template']) ? $styles['form_style_template'] : '';
?>
<div id="hf-wrap" class="hf-content hf-form-style-template">
    <?php
    self::get_admin_header(
            array(
                'form' => $form,
                'class' => 'hf-header-nav',
            )
    );
    ?>
    <div class="hf-body">
        <div class="hf-fields-sidebar">
            <form class="ht-fields-panel" method="post" id="hf-style-form">
                <input type="hidden" name="id" id="hf-form-id" value="<?php echo absint($id); ?>" />
                <div class="hf-form-container hf-grid-container">
                    <div class="hf-form-row">
                        <label><?php esc_html_e('Form Style', 'hash-form'); ?></label>
                        <select name="form_style" id="hf-form-style-select"  data-condition="toggle">
                            <option value="no-style" <?php isset($form_style) ? selected('no-style', $form_style) : ''; ?>><?php esc_html_e('No Style', 'hash-form'); ?></option>
                            <option value="default-style" <?php isset($form_style) ? selected('default-style', $form_style) : ''; ?>><?php esc_html_e('Default Style', 'hash-form'); ?></option>
                            <option value="custom-style" <?php isset($form_style) ? selected('custom-style', $form_style) : ''; ?>><?php esc_html_e('Custom Style', 'hash-form'); ?></option>
                        </select>
                    </div>

                    <div class="hf-form-row" data-condition-toggle="hf-form-style-select" data-condition-val="no-style">
                        <?php esc_html_e('Choose "No Style" when you don\'t want to implement Hash Form plugin style and let theme style take over.', 'hash-form'); ?>
                        <br><br>
                        <?php esc_html_e('The preview seen here will not match with the frontend for "No Style".', 'hash-form'); ?>
                    </div>

                    <div class="hf-form-row" data-condition-toggle="hf-form-style-select" data-condition-val="default-style">
                        <?php esc_html_e('Choose "Default Style" when you want to implement Hash Form plugin styles with minimal designs.', 'hash-form'); ?>
                    </div>

                    <div class="hf-form-row" data-condition-toggle="hf-form-style-select" data-condition-val="custom-style">
                        <?php esc_html_e('Choose "Custom Style" when you want to implement your own styles', 'hash-form'); ?>
                        <br><br>
                        <?php printf(esc_html__('To create new Custom Style, go to %1sStyle Template%2s page.', 'hash-form'), '<a href="' . esc_url(admin_url('edit.php?post_type=hashform-styles')) . '" target="_blank">', '</a>'); ?>
                    </div>

                    <div class="hf-form-row" data-condition-toggle="hf-form-style-select" data-condition-val="custom-style">
                        <label><?php esc_html_e('Choose Template Style', 'hash-form'); ?></label>
                        <select name="form_style_template" id="hf-form-style-template">
                            <option value=""><?php esc_html_e('--Select Style--', 'hash-form'); ?></option>
                            <?php
                            $args = array(
                                'post_type' => 'hashform-styles',
                                'posts_per_page' => -1,
                                'post_status' => 'publish'
                            );
                            $query = new WP_Query($args);
                            $posts = $query->posts;
                            foreach ($posts as $post) {
                                $hashform_styles = get_post_meta($post->ID, 'hashform_styles', true);

                                if (!$hashform_styles) {
                                    $hashform_styles = HashFormStyles::default_styles();
                                } else {
                                    $hashform_styles = HashFormHelper::recursive_parse_args($hashform_styles, HashFormStyles::default_styles());
                                }
                                ob_start();
                                echo '#hf-container-' . absint($id) . '{';
                                HashFormStyles::get_style_vars($hashform_styles, '');
                                echo '}';
                                $tmpl_css_style = ob_get_clean();
                                ?>
                                <option value="<?php echo esc_attr($post->ID); ?>" data-style="<?php echo esc_attr($tmpl_css_style); ?>" <?php selected($post->ID, $form_style_template); ?>><?php echo esc_html($post->post_title); ?></option>
                                <?php
                            }
                            wp_reset_postdata();
                            ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div id="hf-form-panel">
            <div class="hf-form-wrap">
                <?php HashFormPreview::show_form($form->id); ?>
            </div>
        </div>
    </div>
</div>