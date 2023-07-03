<?php
if (!class_exists('AWPA_Widget_Custom')) :
    /**
     * Adds AWPA_Widget_Custom widget.
     */
    class AWPA_Widget_Custom extends AWPA_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array(
                'awpa-post-author-title',
                'awpa-post-author-email',
                'awpa-post-author-image',
                'awpa-post-author-name',
                'awpa-post-author-role',
                'awpa-post-author-desc'
            );

            $this->url_fields = array(
                'awpa-post-author-website',
                'awpa-post-author-facebook',
                'awpa-post-author-twitter',
                'awpa-post-author-instagram',
                'awpa-post-author-youtube',
                'awpa-post-author-linkedin'
            );

            $this->select_fields = array(
                'awpa-post-author-type',
                'awpa-post-author-align',
                'awpa-post-author-image-layout',
                'awpa-post-author-social-icon-layout',

            );

            $widget_ops = array(
                'classname' => 'wp_post_author_widget',
                'description' => __('Displays posts author descriptions.', 'wp-post-author'),
                'customize_selective_refresh' => false,
            );

            parent::__construct('wp__post_author_custom', __('WP Post Author (Custom)', 'wp-post-author'), $widget_ops);
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

            /** This filter is documented in wp-includes/default-widgets.php */
            // open the widget container
            echo $args['before_widget'];

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
                        <?php awpa_get_author_block_custom($instance); ?>
                        <?php do_action('after_wp_post_author'); ?>
                    </div>
                </div>
            </div>

<?php

            // close the widget container
            echo $args['after_widget'];


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

            $alignments = array(
                'left' => __('Left', 'wp-post-author'),
                'right' => __('Right', 'wp-post-author'),
                'center' => __('Center', 'wp-post-author')
            );

            $layout = array(
                'square' => __('Square', 'wp-post-author'),
                'round' => __('Round', 'wp-post-author')

            );

            $icon_shapes = array(
                'round' => __('Round', 'wp-post-author'),
                'square' => __('Square', 'wp-post-author'),
                'none' => __('None', 'wp-post-author'),

            );



            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            echo parent::awpa_generate_text_input('awpa-post-author-title', 'Title', 'WP Post Author');
            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            echo parent::awpa_generate_select_options('awpa-post-author-align', 'Alignment', $alignments, 'Select element alignment.');
            echo parent::awpa_generate_select_options('awpa-post-author-image-layout', 'Profile image layout', $layout, 'Select author image layout.');
            echo parent::awpa_generate_select_options('awpa-post-author-social-icon-layout', 'Social icons shape', $icon_shapes, 'Select social icons shape.');
            echo parent::awpa_generate_image_upload('awpa-post-author-image', __('Profile image', 'wp-post-author'), '');
            echo parent::awpa_generate_text_input('awpa-post-author-name', __('Name', 'wp-post-author'), '');
            echo parent::awpa_generate_text_input('awpa-post-author-role', __('Role', 'wp-post-author'), '');
            echo parent::awpa_generate_text_input('awpa-post-author-email', __('Email', 'wp-post-author'), '');
            echo parent::awpa_generate_text_input('awpa-post-author-website', __('Website', 'wp-post-author'), '', 'url');
            echo parent::awpa_generate_text_input('awpa-post-author-desc', __('Descriptions', 'wp-post-author'), '');
            echo parent::awpa_generate_text_input('awpa-post-author-facebook', __('Facebook', 'wp-post-author'), '', 'url');
            echo parent::awpa_generate_text_input('awpa-post-author-instagram', __('Instagram', 'wp-post-author'), '', 'url');
            echo parent::awpa_generate_text_input('awpa-post-author-youtube', __('Youtube', 'wp-post-author'), '', 'url');
            echo parent::awpa_generate_text_input('awpa-post-author-twitter', __('Twitter', 'wp-post-author'), '', 'url');
            echo parent::awpa_generate_text_input('awpa-post-author-linkedin', __('LinkedIn', 'wp-post-author'), '', 'url');
        }
    }
endif;
