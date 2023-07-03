<?php
if (!class_exists('AWPA_Widget')) :
    /**
     * Adds AWPA_Widget widget.
     */
    class AWPA_Widget extends AWPA_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('awpa-post-author-title', 'awpa-post-author-id');
            $this->select_fields = array('awpa-post-author-type', 'awpa-post-author-align', 'awpa-post-author-image-layout', 'awpa-post-author-show-role', 'awpa-post-author-show-email', 'awpa-post-author-link-layout', 'awpa-post-author-social-icon-layout');

            $widget_ops = array(
                'classname' => 'wp_post_author_widget',
                'description' => __('Displays posts author descriptions.', 'wp-post-author'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('wp__post_author', __('WP Post Author', 'wp-post-author'), $widget_ops);
        }

        /**
         * Front-end display of widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         * @see WP_Widget::widget()
         *
         */

        public function widget($args, $instance)
        {


            $title = isset($instance['awpa-post-author-title']) ? $instance['awpa-post-author-title'] : __('About Post Author', 'wp-post-author');
            $title = apply_filters('widget_title', $title, $instance, $this->id_base);

            $alignment = isset($instance['awpa-post-author-align']) ? $instance['awpa-post-author-align'] : 'left';
            $image_layout = isset($instance['awpa-post-author-image-layout']) ? $instance['awpa-post-author-image-layout'] : 'square';
            $author_posts_link = isset($instance['awpa-post-author-link-layout']) ? $instance['awpa-post-author-link-layout'] : 'square';
            $icon_shape = isset($instance['awpa-post-author-social-icon-layout']) ? $instance['awpa-post-author-social-icon-layout'] : 'round';

            $show_role = isset($instance['awpa-post-author-show-role']) ? $instance['awpa-post-author-show-role'] : 'false';
            $show_role = ($show_role == 'true') ? true : false;

            $show_email = isset($instance['awpa-post-author-show-email']) ? $instance['awpa-post-author-show-email'] : 'false';
            $show_email = ($show_email == 'true') ? true : false;

            /** This filter is documented in wp-includes/default-widgets.php */
            // open the widget container
            echo $args['before_widget'];

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
?>
                <div class="wp-post-author-wrap <?php echo $alignment; ?>">
                    <?php if (!empty($title)) : ?>
                        <div class="section-head">
                            <?php if (!empty($title)) : ?>
                                <h4 class="widget-title header-after1">
                                    <span class="header-after">
                                        <?php echo esc_html($title); ?>
                                    </span>
                                </h4>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="widget-block widget-wrapper">
                        <div class="posts-author-wrapper">
                            <?php do_action('before_wp_post_author'); ?>
                            <?php awpa_get_author_block('', $image_layout, $show_role, $show_email, $author_posts_link, $icon_shape); ?>
                            <?php do_action('after_wp_post_author'); ?>
                        </div>
                    </div>
                </div>

<?php

                // close the widget container
                echo $args['after_widget'];
            }

            $instance = parent::awpa_sanitize_data($instance, $instance);
        }

        /**
         * Back-end widget form.
         *
         * @param array $instance Previously saved values from database.
         * @see WP_Widget::form()
         *
         */
        public function form($instance)
        {
            $this->form_instance = $instance;

            $author_type = array(
                'post-author' => __('Post Author', 'wp-post-author'),
                'specific-author' => __('Specific Author', 'wp-post-author')
            );


            $alignments = array(
                'left' => __('Left', 'wp-post-author'),
                'right' => __('Right', 'wp-post-author'),
                'center' => __('Center', 'wp-post-author')
            );

            $layout = array(
                'square' => __('Square', 'wp-post-author'),
                'round' => __('Round', 'wp-post-author'),

            );


            $options = array(
                'false' => __('No', 'wp-post-author'),
                'true' => __('Yes', 'wp-post-author')

            );

            $icon_shapes = array(
                'round' => __('Round', 'wp-post-author'),
                'square' => __('Square', 'wp-post-author'),
                'none' => __('None', 'wp-post-author'),

            );

            $author_posts_link = array(
                'square' => __('Square', 'wp-post-author'),
                'round' => __('Round', 'wp-post-author'),
                'none' => __('None', 'wp-post-author'),

            );

            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            echo parent::awpa_generate_text_input('awpa-post-author-title', 'Title', 'WP Post Author');
            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            echo parent::awpa_generate_select_options('awpa-post-author-align', 'Alignment', $alignments, 'Select element alignment.');
            echo parent::awpa_generate_select_options('awpa-post-author-image-layout', 'Profile image layout', $layout, 'Select author image layout.');
            echo parent::awpa_generate_select_options('awpa-post-author-link-layout', 'Author posts Link layout', $author_posts_link, 'Select author posts link layout.');
            echo parent::awpa_generate_select_options('awpa-post-author-social-icon-layout', 'Social icons shape', $icon_shapes, 'Select social icons shape.');
            echo parent::awpa_generate_select_options('awpa-post-author-show-role', 'Show author role', $options, 'Show/hide author role.');
            echo parent::awpa_generate_select_options('awpa-post-author-show-email', 'Show author email', $options, 'Show/hode author email.');
        }
    }
endif;
