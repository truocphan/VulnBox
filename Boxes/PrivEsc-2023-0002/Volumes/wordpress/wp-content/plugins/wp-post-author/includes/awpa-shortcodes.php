<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!function_exists('awpa_get_author_shortcode')) {
    /**
     * @param $author_id
     * @return array
     */
    function awpa_get_author_shortcode($atts)
    {


        $awpa = shortcode_atts(array(
            'title' => __('WP Post Author', 'wp-post-author'),
            'author-id' => '',
            'align' => 'left',
            'image-layout' => 'square',
            'author-posts-link' => 'square',
            'icon-shape' => 'round',
            'show-role' => 'false',
            'show-email' => 'false'
        ), $atts);

        $author_id = !empty($awpa['author-id']) ? absint($awpa['author-id']) : '';
        $title = isset($awpa['title']) ? esc_attr($awpa['title']) : '';
        $align = !empty($awpa['align']) ? esc_attr($awpa['align']) : 'left';
        $image_layout = !empty($awpa['image-layout']) ? esc_attr($awpa['image-layout']) : 'square';
        $author_posts_link = !empty($awpa['author-posts-link']) ? esc_attr($awpa['author-posts-link']) : 'square';
        $icon_shape = !empty($awpa['icon-shape']) ? esc_attr($awpa['icon-shape']) : 'round';
        $show_role = !empty($awpa['show-role']) ? esc_attr($awpa['show-role']) : 'false';
        $show_email = !empty($awpa['show-email']) ? esc_attr($awpa['show-email']) : 'false';

        $show_role = ($show_role == 'true') ? true : false;
        $show_email = ($show_email == 'true') ? true : false;
        $show = true;

        if (is_single()) {
            $post_type = get_post_type();

            // Set class property
           
            $options = get_option('awpa_setting_options');
            if ($post_type == 'post') {
                $show = true;
            } else {
                if (isset($options['awpa_also_visibile_in_' . $post_type])) {
                    $visibile = $options['awpa_also_visibile_in_' . $post_type];
                    if ($visibile == $post_type) {
                        $show = true;
                    }
                } else {
                    $show = false;
                }
            }
        } elseif (is_front_page()) {

            $show = true;
        }

        if (($show == true) || is_author()) {
            ob_start();

            $post_id = get_the_ID();
            $awpa_post_authors = get_post_meta($post_id, 'wpma_author');
            $enable_author_metabox_for_post = get_option('awpa_author_metabox_integration');
            ?>
            <h3 class="awpa-title"><?php echo $title; ?></h3>
            <?php
            $multiauthor_settings = false;
            if($enable_author_metabox_for_post && $enable_author_metabox_for_post['enable_author_metabox']=='true'){
                $multiauthor_settings = true;
            }
           
            if (isset($awpa_post_authors) && !empty($awpa_post_authors) &&  $multiauthor_settings== true) :
                foreach ($awpa_post_authors as $author_id) :

                    $needle = 'guest-';
                    if (strpos($author_id, $needle) !== false) {
                        $filter_id = substr($author_id, strpos($author_id, "-") + 1);
                        $author_id = $filter_id;
                        $author_type = 'guest';
                    } else {
                        $author_id = $author_id;
                        $author_type = 'default';
                    }?>
                    <div class="wp-post-author-wrap wp-post-author-shortcode <?php echo $align; ?>">

                        <?php do_action('before_wp_post_author'); ?>
                        <?php
                        
                        
                            if ($author_type == 'default') {
                                awpa_get_author_block($author_id, $image_layout, $show_role, $show_email, $author_posts_link, $icon_shape);
                            }
                            if ($author_type == 'guest') {
                                awpa_get_guest_author_block($author_id, $image_layout, $show_role, $show_email, $author_posts_link, $icon_shape);
                            }
                        
                        ?>
                        <?php do_action('after_wp_post_author'); ?>
                    </div>
                <?php
                endforeach;
            else : ?>
                <div class="wp-post-author-wrap wp-post-author-shortcode <?php echo $align; ?>">                    
                    <?php do_action('before_wp_post_author'); ?>
                    <?php awpa_get_author_block($author_id, $image_layout, $show_role, $show_email, $author_posts_link, $icon_shape); ?>
                    <?php do_action('after_wp_post_author'); ?>
                </div>
<?php
            endif;

            return ob_get_clean();
        }
    }
}
add_shortcode('wp-post-author', 'awpa_get_author_shortcode');
