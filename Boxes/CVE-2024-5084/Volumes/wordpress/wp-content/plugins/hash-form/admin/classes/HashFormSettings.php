<?php
defined('ABSPATH') || die();

class HashFormSettings {

    public function __construct() {
        add_action('admin_menu', array($this, 'menu'), 45);

        add_action('wp_ajax_hashform_test_email_template', array($this, 'send_test_email'), 10, 0);
    }

    public function menu() {
        add_submenu_page('hashform', 'Hash Form | ' . esc_html__('Settings', 'hash-form'), esc_html__('Settings', 'hash-form'), 'manage_options', 'hashform-settings', array($this, 'route'));
        add_submenu_page('hashform', esc_html__('Documentation', 'ultimate-woocommerce-cart'), esc_html__('Documentation', 'ultimate-woocommerce-cart'), 'manage_options', esc_url_raw('https://hashthemes.com/documentation/hash-form-drag-and-drop-form-builder-documentation/'));
    }

    public function route() {
        $action = HashFormHelper::get_post('hashform_action', 'sanitize_title');
        if ($action == 'process-form') {
            self::process_form();
        } else {
            self::display_form();
        }
    }

    public static function display_form() {
        $settings = self::get_settings();
        $sections = array(
            'captcha-settings' => array(
                'name' => esc_html__('Captcha', 'hash-form'),
                'icon' => 'mdi mdi-security',
            ),
            'email-settings' => array(
                'name' => esc_html__('Email Settings', 'hash-form'),
                'icon' => 'mdi mdi-email-multiple-outline'
            ),
        );
        $current = 'captcha-settings'
        ?>
        <div class="hf-settings-wrap wrap">
            <h1></h1>
            <div id="hf-settings-wrap">
                <form name="hashform_settings_form" method="post" action="?page=hashform-settings<?php echo esc_html($current ? '&amp;t=' . $current : '' ); ?>">
                    <div class="hf-page-title">
                        <h3><?php esc_html_e('Settings', 'hash-form'); ?></h3>
                    </div>
                    <div class="hf-content"> 
                        <div class="hf-body">
                            <div class="hf-fields-sidebar">
                                <ul class="hf-settings-tab">
                                    <?php foreach ($sections as $key => $section) { ?>
                                        <li class="<?php echo esc_attr($current === $key ? 'hf-active' : '' ); ?>">
                                            <a href="#hf-<?php echo esc_attr($key); ?>">
                                                <i class="<?php echo esc_attr($section['icon']); ?>"></i>
                                                <?php echo wp_kses_post($section['name']); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <div id="hf-form-panel">
                                <div class="hf-form-wrap">
                                    <?php HashFormHelper::print_message(); ?>

                                    <input type="hidden" name="hashform_action" value="process-form"/>
                                    <?php
                                    wp_nonce_field('hashform_process_form_nonce', 'process_form');
                                    foreach ($sections as $key => $section) {
                                        ?>
                                        <div id="hf-<?php echo esc_attr($key); ?>" class="<?php echo ( $current === $key ) ? '' : 'hf-hidden'; ?>">
                                            <h3><?php echo esc_html($section['name']); ?></h3>
                                            <?php
                                            include( HASHFORM_PATH . 'admin/settings/' . $key . '.php' );
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="hf-footer">
                            <input class="button button-primary button-large" type="submit" value="<?php esc_attr_e('Update', 'hash-form'); ?>"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public static function process_form() {
        $process_form = HashFormHelper::get_post('process_form');
        if (!wp_verify_nonce($process_form, 'hashform_process_form_nonce')) {
            wp_die(esc_html__('Permission Denied', 'hash-form'));
        }

        $settings = HashFormHelper::recursive_parse_args(HashFormHelper::get_post('hashform_settings', 'esc_html'), self::checkbox_settings());
        $settings = HashFormHelper::sanitize_array($settings, self::sanitize_rules());

        update_option('hashform_options', $settings);
        $_SESSION['hashform_message'] = esc_html__('Settings Saved !', 'hash-form');

        self::display_form();
    }

    public static function get_settings() {
        $settings = get_option('hashform_options');
        if (!$settings) {
            $settings = self::default_values();
        } else {
            $settings = wp_parse_args($settings, self::default_values());
        }

        return $settings;
    }

    public function send_test_email() {
        if (!current_user_can('manage_options'))
            return;

        $settings = self::get_settings();

        $header_image = $settings['header_image'];

        $email_template = HashFormHelper::get_post('email_template');
        $test_email = HashFormHelper::get_post('test_email');
        $email_subject = esc_html__('Test Email', 'hash-form');
        $count = 0;

        $contents = array(
            0 => array(
                'title' => 'Name',
                'value' => 'John Doe'
            ),
            1 => array(
                'title' => 'Email',
                'value' => 'noreply@gmail.com'
            ),
            2 => array(
                'title' => 'Subject',
                'value' => 'Exciting Updates and Important Information Inside!'
            ),
            3 => array(
                'title' => 'Message',
                'value' => '<p>I hope this email finds you well. We are thrilled to share some exciting updates and important information that we believe you will find valuable.</p><p>Your satisfaction is our priority, and we are committed to delivering the best possible experience.</p>'
            )
        );

        $email_message = '<p style="margin-bottom:20px">';
        $email_message .= esc_html__('Hello, this is a test email.', 'hash-form');
        $email_message .= '</p>';
        foreach ($contents as $content) {
            $count++;
            $email_message .= call_user_func('HashFormEmail::' . $email_template, $content['title'], $content['value'], $count);
        }
        ob_start();
        include(HASHFORM_PATH . 'admin/settings/email-templates/' . $email_template . '.php');
        $form_html = ob_get_clean();

        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        $headers = array();
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . esc_attr($site_name) . ' <' . esc_attr($admin_email) . '>';
        $mail = wp_mail($test_email, $email_subject, $form_html, $headers);
        if ($mail) {
            die(wp_json_encode(
                            array(
                                'success' => true,
                                'message' => esc_html__('Email Sent Successfully', 'hash-form')
                            )
            ));
        }
        die(wp_json_encode(
                        array(
                            'success' => false,
                            'message' => esc_html__('Failed to Send Email', 'hash-form')
                        )
        ));
    }

    public static function checkbox_settings() {
        return array();
    }

    public static function default_values() {
        return array(
            're_type' => 'v2',
            'pubkey_v2' => '',
            'privkey_v2' => '',
            'pubkey_v3' => '',
            'privkey_v3' => '',
            're_lang' => 'en',
            're_threshold' => '0.5',
            'header_image' => '',
            'email_template' => 'template1',
        );
    }

    public static function sanitize_rules() {
        return array(
            're_type' => 'sanitize_text_field',
            'pubkey_v2' => 'sanitize_text_field',
            'privkey_v2' => 'sanitize_text_field',
            'pubkey_v3' => 'sanitize_text_field',
            'privkey_v3' => 'sanitize_text_field',
            're_lang' => 'sanitize_text_field',
            're_threshold' => 'sanitize_text_field',
            'header_image' => 'sanitize_text_field',
            'email_template' => 'sanitize_text_field',
        );
    }

}

new HashFormSettings();
