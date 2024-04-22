<?php

STM_LMS_Chat::init();

class STM_LMS_Chat
{

    public static function init()
    {
        add_action('wp_ajax_stm_lms_send_message', 'STM_LMS_Chat::add_message');

        add_action('wp_ajax_stm_lms_get_user_conversations', 'STM_LMS_Chat::get_user_conversations');

        add_action('wp_ajax_stm_lms_get_user_messages', 'STM_LMS_Chat::get_user_messages');
    }

    public static function add_message()
    {

        check_ajax_referer('stm_lms_send_message', 'nonce');

        if (empty($_GET['to'])) die;
        $user_to = intval($_GET['to']);

        $user = STM_LMS_User::get_current_user();
        if (empty($user['id'])) die;
        $user_from = $user['id'];

        $transient_name = STM_LMS_Chat::transient_name($user_to, 'chat');
        delete_transient($transient_name);

        if (empty($_GET['message'])) die;
        $message = sanitize_text_field($_GET['message']);

        $timestamp = time();
        $status = 'pending';

        do_action('stm_lms_before_send_chat_message');
        stm_lms_add_user_chat(compact('user_to', 'user_from', 'message', 'timestamp', 'status'));

        $r = array(
            'response' => esc_html__('Message Sent', 'masterstudy-lms-learning-management-system'),
            'status' => 'success',
        );

        wp_send_json($r);
    }

    public static function get_user_conversations()
    {

        check_ajax_referer('stm_lms_get_user_conversations', 'nonce');

        $user = STM_LMS_User::get_current_user();
        if (empty($user['id'])) die;
        $user_id = $user['id'];

        $transient_name = STM_LMS_Chat::transient_name($user_id, 'chat');
        delete_transient($transient_name);

        $r = array();

        $conversations = stm_lms_get_user_conversations($user['id']);
        if (!empty($conversations)) {
            foreach ($conversations as $conversation) {
                $companion_id = ($user_id == $conversation['user_from']) ? $conversation['user_to'] : $conversation['user_from'];

                $conversation['ago'] = stm_lms_time_elapsed_string(date('Y-m-d H:i:s', $conversation['timestamp']));

                $r[] = array(
                    'conversation_info' => $conversation,
                    'me' => $user,
                    'companion' => STM_LMS_User::get_current_user($companion_id),
                );

            }
        }


        wp_send_json($r);
    }

    public static function get_user_messages()
    {

        check_ajax_referer('stm_lms_get_user_messages', 'nonce');

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $user = STM_LMS_User::get_current_user();
        if (empty($user['id'])) die;
        $user_id = $user['id'];

        if (empty($_GET['id'])) die;
        $conversation_id = intval($_GET['id']);

        $just_send = (!empty($_GET['just_send']) and $_GET['just_send'] == 'true') ? true : false;

        $messages = stm_lms_get_user_messages($conversation_id, $user_id, array(), $just_send);

        if (!empty($messages)) {
            foreach ($messages as $message_key => $message) {
                $messages[$message_key]['message'] = STM_LMS_Quiz::deslash($messages[$message_key]['message']);
                $messages[$message_key]['isOwner'] = ($user_id == $message['user_from']);
                $messages[$message_key]['companion'] = STM_LMS_User::get_current_user($message['user_from']);
                $messages[$message_key]['ago'] = stm_lms_time_elapsed_string(date('Y-m-d H:i:s', $message['timestamp']));
            }
        }

        $messages = array_reverse($messages);


        $r = array(
            'messages' => $messages
        );


        wp_send_json($r);
    }

    public static function transient_name($user_id, $name = '')
    {
        return "stm_lms_chat_{$user_id}_{$name}";
    }

    public static function user_new_messages($user_id)
    {

        $transient_name = STM_LMS_Chat::transient_name($user_id, 'chat');

        if (false === ($messages_num = get_transient($transient_name))) {
            
            $conversations = stm_lms_get_user_conversations($user_id);
            $messages_num = 0;
            if (!empty($conversations)) {
                foreach ($conversations as $conversation) {
                    if($user_id == $conversation['user_from']) {
                        $messages_num += $conversation['uf_new_messages'];
                    } else if ($user_id == $conversation['user_to']) {
                        $messages_num += $conversation['ut_new_messages'];
                    }
                }
            }
            set_transient($transient_name, $messages_num, 30 * 24 * 60 * 60);
        }
        return $messages_num;
    }

    public static function chat_url()
    {

        $pages_config = STM_LMS_Page_Router::pages_config();

        return STM_LMS_User::login_page_url() . $pages_config['user_url']['sub_pages']['chat_url']['url'];
    }

}