<?php

STM_LMS_Reviews::reviews_init();

class STM_LMS_Reviews
{

    private static $instance;

    public static function reviews_init()
    {
        add_action('save_post', 'STM_LMS_Reviews::save_post', 100, 1);

        add_action('wp_ajax_stm_lms_get_reviews', 'STM_LMS_Reviews::get_reviews', 100);
        add_action('wp_ajax_nopriv_stm_lms_get_reviews', 'STM_LMS_Reviews::get_reviews', 100);

        add_action('wp_ajax_stm_lms_add_review', 'STM_LMS_Reviews::add_review', 100);
        add_action('wp_ajax_nopriv_stm_lms_add_review', 'STM_LMS_Reviews::add_review', 100);
    }

    public static function save_post($post_id)
    {
        global $post;
        if (empty($post)) return;
        if ($post->post_type != 'stm-reviews') {
            return;
        }

        $course = get_post_meta($post_id, 'review_course', true);
        $mark = get_post_meta($post_id, 'review_mark', true);
        $user = get_post_meta($post_id, 'review_user', true);

        $transient_name = STM_LMS_Instructor::transient_name(get_post_field('post_author', $course), 'rating');
        delete_transient($transient_name);

        if (!empty($mark) and !empty($course) and !empty($user)) {

            $marks = get_post_meta($course, 'course_marks', true);

            if (empty($marks)) $marks = array();
            $marks[$user] = $mark;

            $rates = STM_LMS_Course::course_average_rate($marks);

            update_post_meta($course, 'course_mark_average', $rates['average']);
            update_post_meta($course, 'course_marks', $marks);


            /*Update Instructor Rating*/
            STM_LMS_Instructor::update_rating(get_post_field('post_author', $course), $mark);

        }
    }

    public static function _get_reviews($course_id, $offset = '')
    {

        $r = array(
            'posts' => array(),
            'total' => 0
        );

        $pp = get_option('posts_per_page');

        $offset = $offset * $pp;

        $args = array(
            'post_type' => 'stm-reviews',
            'posts_per_page' => $pp,
            'post_status' => 'publish',
            'offset' => $offset,
            'meta_query' => array(
                array(
                    'key' => 'review_course',
                    'compare' => '=',
                    'value' => intval($course_id)
                )
            )
        );

        $q = new WP_Query($args);
        $total = $q->found_posts;

        $r['total'] = $total <= $offset + $pp;

        if ($q->have_posts()) {
            while ($q->have_posts()) {
                $q->the_post();
                $id = get_the_ID();
                $meta = STM_LMS_Reviews::convert_meta($id);
                if (!empty($meta['review_mark']) and !empty($meta['review_user'])) {
                    $mark = $meta['review_mark'];
                    $user = $meta['review_user'];

                    $user_class = STM_LMS_User::get_current_user($user);
                    $user_data = get_user_by('id', $user);
                    if (is_wp_error($user_data)) continue;
                    $user_name = (!empty($user_data->data->display_name)) ? $user_data->data->display_name : $user_data->data->user_nicename;
                    $avatar = get_avatar_url($user);

                    if (!empty($user_class['avatar_url'])) $avatar = $user_class['avatar_url'];

                    $r['posts'][] = array(
                        'user' => $user_name,
                        'avatar_url' => $avatar,
                        'time' => stm_lms_time_elapsed_string('@' . get_post_time('U', true)),
                        'title' => get_the_title(),
                        'content' => get_the_content($id),
                        'mark' => intval($mark),
                    );
                }
            }
        }

        return $r;


    }

    public static function get_reviews()
    {

        check_ajax_referer('stm_lms_get_reviews', 'nonce');

        if (empty($_GET['post_id'])) die;
        $course_id = intval($_GET['post_id']);

        $offset = (!empty($_GET['offset'])) ? intval($_GET['offset']) : 0;

        $r = self::_get_reviews($course_id, $offset);

        wp_send_json($r);
    }

    public static function convert_meta($post_id)
    {
        $meta = get_post_meta($post_id);
        $metas = array();
        foreach ($meta as $meta_name => $meta_value) {
            $metas[$meta_name] = $meta_value[0];
        }

        return $metas;
    }

    public static function get_instance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get_user_review_on_course($course_id, $user_id)
    {
        $args = array(
            'post_type' => 'stm-reviews',
            'post_status' => array('publish', 'pending'),
            'posts_per_page' => 1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'review_course',
                    'compare' => '=',
                    'value' => $course_id
                ),
                array(
                    'key' => 'review_user',
                    'compare' => '=',
                    'value' => $user_id
                ),
            )
        );

        $q = new WP_Query($args);
        wp_reset_postdata();
        return $q;
    }

    static function _add_review($course_id, $mark, $review)
    {

        $current_user = STM_LMS_User::get_current_user();
        if (empty($current_user['id'])) die;
        $user_id = $current_user['id'];

        $r = array(
            'error' => false,
            'status' => 'success',
            'message' => esc_html__('Your review is moderating.', 'masterstudy-lms-learning-management-system'),
        );

        /*Check if user has review*/
        $prev_reviews = STM_LMS_Reviews::get_user_review_on_course($course_id, $user_id);
        if ($prev_reviews->found_posts) {
            $r = array(
                'error' => true,
                'status' => 'error',
                'message' => esc_html__('You already left review.', 'masterstudy-lms-learning-management-system'),
            );
        }

        if (empty($mark)) {
            $r = array(
                'error' => true,
                $r['status'] = 'error',
                $r['message'] = esc_html__('Please, check rating', 'masterstudy-lms-learning-management-system')
            );
        }

        if (empty($review)) {
            $r = array(
                'error' => true,
                'status' => 'error',
                'message' => esc_html__('Please, write review.', 'masterstudy-lms-learning-management-system')
            );
        }

        if (!$r['error']) {
            if ($mark > 5) $mark = 5;
            if ($mark < 1) $mark = 1;

            // Create post object
            $my_review = array(
                'post_type' => 'stm-reviews',
                'post_title' => wp_strip_all_tags(
                    sprintf(
                        esc_html__('Review on %s by %s', 'masterstudy-lms-learning-management-system'),
                        get_the_title($course_id),
                        $current_user['login']
                    )
                ),
                'post_content' => $review,
                'post_status' => 'pending',
            );

            $review_id = wp_insert_post($my_review);

            $meta_fields = array(
                'review_course' => $course_id,
                'review_user' => $user_id,
                'review_mark' => $mark
            );

            foreach ($meta_fields as $meta_key => $meta_value) {
                update_post_meta($review_id, $meta_key, $meta_value);
            }

            $course_title = get_the_title($course_id);
            $login = $current_user['login'];

            STM_LMS_Helpers::send_email(
                'admin',
                esc_html__('New Review', 'masterstudy-lms-learning-management-system'),
                sprintf(
                    esc_html__('Check out new review on course %s by %s', 'masterstudy-lms-learning-management-system'),
                    $course_title,
                    $login
                ),
                'stm_lms_new_review',
                compact('course_title', 'login')
            );

            delete_transient(STM_LMS_Instructor::transient_name($current_user['id'], 'rating'));
        }

        return $r;

    }

    public static function add_review()
    {

        check_ajax_referer('stm_lms_add_review', 'nonce');

        if (empty($_POST['post_id'])) die;
        $course_id = intval($_POST['post_id']);

        $mark = (!empty($_POST['mark'])) ? intval($_POST['mark']) : 0;
        $review = (!empty($_POST['review'])) ? wp_kses_post($_POST['review']) : '';

        if (STM_LMS_Course::check_course_author($course_id, get_current_user_id())) {
            $r = array(
                'error' => true,
                'status' => 'error',
                'message' => esc_html__('You can\'t leave review for your own course', 'masterstudy-lms-learning-management-system'),
            );
        } else {
            $r = self::_add_review($course_id, $mark, $review);
        }

        wp_send_json($r);
    }

}

;