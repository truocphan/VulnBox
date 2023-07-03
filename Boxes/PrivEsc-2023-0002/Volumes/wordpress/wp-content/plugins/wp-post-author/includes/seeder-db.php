<?php  global $wpdb;
    $table_name = $wpdb->prefix . "wpa_membership_plan";
    // if (!$wpdb->get_var("SELECT COUNT(*) FROM $table_name")) {
        $wpdb->insert($table_name, array(
            'name' => 'Bronze Plan',
            'description' => 'Bronze Plan New',
            'price' => '10',
            'currency' => 'USD',
            'plan_id' => '0',
            'payment_gateway' => 'Manual',
            'status' => 1,
            'image_name' => 'bronze.jpg',
            'created_at' => gmdate('Y-m-d H:i:s', time()),
            'updated_at' => gmdate('Y-m-d H:i:s', time()),
        ), array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s'));
        $wpdb->insert($table_name, array(
            'name' => 'Silver Plan',
            'description' => 'Silver Plan New',
            'price' => '20',
            'currency' => 'USD',
            'plan_id' => '0',
            'payment_gateway' => 'Manual',
            'status' => 1,
            'image_name' => 'silver.jpg',
            'created_at' => gmdate('Y-m-d H:i:s', time()),
            'updated_at' => gmdate('Y-m-d H:i:s', time()),
        ), array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s'));
        $wpdb->insert($table_name, array(
            'name' => 'Gold Plan',
            'description' => 'Gold Plan New',
            'price' => '30',
            'currency' => 'USD',
            'plan_id' => '0',
            'payment_gateway' => 'Manual',
            'status' => 1,
            'image_name' => 'gold.jpg',
            'created_at' => gmdate('Y-m-d H:i:s', time()),
            'updated_at' => gmdate('Y-m-d H:i:s', time()),
        ), array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s'));
    // }
    $table_name = $wpdb->prefix . "wpa_form_builder";
    // if (!$wpdb->get_var("SELECT COUNT(*) FROM $table_name")) {
        $wpdb->insert($table_name, array(
            'post_author' => 1,
            'post_title' => 'User Registration (Free)',
            'post_content' => '[{"id":1,"name":"email","type":"email","label":"E-mail","hide_label":"false","tool_tip_msg":"","required":true,"description":"","placeholder":"Enter e-mail address","classname":"email","custom_classname":""},{"id":2,"name":"password","type":"password","label":"Password","hide_label":"false","tool_tip_msg":"","required":true,"description":"","placeholder":"","classname":"password","custom_classname":""}]',
            'form_settings' => '{"enable_mailchimp":false,"enable_constant_contact":false}',
            'payment_data' => '{"paymentProcess":"free","currency":"USD","amount":"10","membershipPlans":[]}',
            'social_login_setting' => '',
            'other_settings' => null,
            'post_status' => 'publish',
            'post_date' => gmdate('Y-m-d H:i:s', time()),
            'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
            'post_modified' => gmdate('Y-m-d H:i:s', time()),
            'post_modified_gmt' => gmdate('Y-m-d H:i:s', time()),
            'editable' => 0,
        ), array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'));
        // $wpdb->insert($table_name, array(
        //     'post_author' => 1,
        //     'post_title' => 'User Registration (Paid)',
        //     'post_content' => '[{"id":1,"name":"email","type":"email","label":"E-mail Address","hide_label":"false","tool_tip_msg":"","required":true,"description":"","placeholder":"Enter e-mail address","classname":"email","custom_classname":""},{"id":2,"name":"password","type":"password","label":"Password","hide_label":"false","tool_tip_msg":"","required":true,"description":"","placeholder":"","classname":"password","custom_classname":""},{"id":"44462792-bd73-4b75-ad8a-314ece172c78","name":"nickname","type":"text","label":"Nick Name","hide_label":"false","tool_tip_msg":"","required":false,"description":"","placeholder":"","classname":"nickname","custom_classname":"","defaultValue":[]}]',
        //     'form_settings' => '{"enable_mailchimp":false,"enable_constant_contact":false}',
        //     'payment_data' => '{"paymentProcess":"paid","currency":"USD","amount":"10","membershipPlans":[{"id":"3","name":"Gold Plan","description":"Gold Plan New","price":"30","currency":"USD","plan_id":null,"payment_gateway":"Manual","status":"1","image_name":"gold.png","created_at":null,"updated_at":null}]}',
        //     'social_login_setting' => '',
        //     'other_settings' => null,
        //     'post_status' => 'publish',
        //     'post_date' => gmdate('Y-m-d H:i:s', time()),
        //     'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
        //     'post_modified' => gmdate('Y-m-d H:i:s', time()),
        //     'post_modified_gmt' => gmdate('Y-m-d H:i:s', time()),
        //     'editable' => 0,
        // ), array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'));
    // }