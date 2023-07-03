<?php
/**
 * Implement theme metabox.
 *
 * @package WP Post Author
 */
function awpa_form_builder($hook)
{
    wp_register_style('awpa-admin-style', AWPA_PLUGIN_URL . 'assets/css/awpa-backend-style.css', array(), '', 'all');
    wp_enqueue_style('awpa-admin-style');

    //for addon addition, need to make conditional wp_enqueue_script with/out dependency 
    wp_enqueue_script(
        'wpauthor-blocks-block-js',
        AWPA_PLUGIN_URL . 'assets/dist/blocks.build.js',
        // array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-pa-admin-constant-contact-scripts')
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor')

    );
    $awpa_priview_img_url = AWPA_PLUGIN_URL . 'assets/awpa-placeholder-img-square.jpg';
    wp_localize_script(
        'wpauthor-blocks-block-js',
        'wpauthor_globals',
        array(
            'srcUrl' => untrailingslashit(plugins_url('/', AWPA_BASE_DIR . '/dist/')),
            'rest_url' => esc_url(rest_url()),
            'img' => $awpa_priview_img_url,
            'pluginDir'=> AWPA_PLUGIN_URL
        )
    );
}
add_action('admin_enqueue_scripts', 'awpa_form_builder');
if (!function_exists('awpa_add_theme_meta_box')) :

    /**
     * Add the Meta Box
     *
     * @since 1.0.0
     */
    function awpa_add_theme_meta_box()
    {

        $screens = array('awpa_user_form_build');

        foreach ($screens as $screen) {
                add_meta_box(
                'awpa-theme-settings',
                esc_html__('Form Options', 'wp-post-author'),
                'awpa_render_layout_options_metabox',
                $screen,
                'advanced',
                'high'    

            );
        }

    }

endif;

add_action('add_meta_boxes', 'awpa_add_theme_meta_box');

if (!function_exists('awpa_render_layout_options_metabox')) :

    /**
     * Render theme settings meta box.
     *
     * @since 1.0.0
     */
    function awpa_render_layout_options_metabox($post, $metabox)
    {
        $post_id = $post->ID;

        // Meta box nonce for verification.
        wp_nonce_field(basename(__FILE__), 'awpa_meta_box_nonce');
        // Fetch Options list.
//        $content_layout = get_post_meta($post_id, 'awpa-meta-content-alignment', true);
//
//        if (empty($content_layout)) {
//            $content_layout = awpa_get_option('global_content_alignment');
//        }


        ?>
        <div id="awpa-form-builder-container" class="awpa-form-builder-container">
            <p>This should not render!!!</p>
        </div>

        <?php
    }

endif;


if (!function_exists('awpa_save_layout_options_meta')) :

    /**
     * Save theme settings meta box value.
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID.
     * @param WP_Post $post Post object.
     */
    function awpa_save_layout_options_meta($post_id, $post)
    {

        // Verify nonce.
        if (!isset($_POST['awpa_meta_box_nonce']) || !wp_verify_nonce($_POST['awpa_meta_box_nonce'], basename(__FILE__))) {
            return;
        }

        // Bail if auto save or revision.
        if (defined('DOING_AUTOSAVE') || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
        if (empty($_POST['post_ID']) || $_POST['post_ID'] != $post_id) {
            return;
        }

        // Check permission.
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else if (!current_user_can('edit_post', $post_id)) {
            return;
        }

//        $content_layout = isset($_POST['awpa-meta-content-alignment']) ? $_POST['awpa-meta-content-alignment'] : '';
//        update_post_meta($post_id, 'awpa-meta-content-alignment', sanitize_text_field($content_layout));


    }

endif;

add_action('save_post', 'awpa_save_layout_options_meta', 10, 2);
