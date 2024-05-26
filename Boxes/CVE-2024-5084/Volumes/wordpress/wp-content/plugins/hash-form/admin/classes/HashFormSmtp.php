<?php
defined('ABSPATH') || die();

class HashFormSmtp {

    public function __construct() {
        add_action('admin_menu', array($this, 'menu'), 45);
        add_action('wp_ajax_hashform_activate_plugin', array($this, 'activate_plugin'));
        add_action('admin_init', array($this, 'redirect_to_smtp_settings'));
    }

    public function menu() {
        add_submenu_page('hashform', 'Hash Form | ' . esc_html__('SMTP', 'hash-form'), esc_html__('SMTP', 'hash-form'), 'manage_options', 'hashform-smtp', array($this, 'smtp'));
    }

    public function smtp() {
        ?>
        <div class="hf-smtp-page">
            <div class="hf-require-wp-mail-smtp-notice">
                <h3><?php esc_html_e("Why Use SMTP?", "hash-form"); ?></h3>
                <p><?php esc_html_e("WordPress’ email feature uses the Hypertext Preprocessor (PHP) mail() function by default. However, it is not the most effective tool as it may trigger spam filters and send error messages to its users.", "hash-form"); ?></p>
                <p><?php esc_html_e("The Simple Mail Transfer Protocol (SMTP) server is better for WordPress website owners who frequently exchange emails with their visitors. It offers high security and deliverability to ensure properly sent emails. To use it, connect your email service to a third-party SMTP provider and install an SMTP plugin on your WordPress site.", "hash-form"); ?></p>
                <p><?php printf(esc_html__("See Detail Article %1shere%2s", "hash-form"), '<a href="https://hashthemes.com/what-is-smtp-and-best-wordpress-smtp-plugins/" target="_blank">', '</a>'); ?></p>
                <?php
                $all_plugins = get_plugins();
                if (!array_key_exists('wp-mail-smtp/wp_mail_smtp.php', $all_plugins)) {
                    ?>
                    <a href="#" class="button hf-install-wp-mail-smtp-plugin"><?php echo esc_html__('Install WP Mail SMTP Plugin', 'hash-form') ?></a>
                    <?php
                } else if (!is_plugin_active('wp-mail-smtp/wp_mail_smtp.php')) {
                    ?>
                    <a href="#" class="button hf-activate-wp-mail-smtp-plugin"><?php echo esc_html__('Activate WP Mail SMTP Plugin', 'hash-form') ?></a>
                    <?php
                }
                ?>
            </div>
        </div>

        <?php
    }

    public static function activate_plugin() {
        if (!current_user_can('manage_options'))
            return;

        $slug = HashFormHelper::get_post('slug');
        $file = HashFormHelper::get_post('file');
        $success = false;

        if (!empty($slug) && !empty($file)) {
            $result = activate_plugin($slug . '/' . $file . '.php');
            if (!is_wp_error($result)) {
                $success = true;
            }
        }
        echo wp_json_encode(array('success' => ($success ? true : false)));
        die();
    }

    public static function redirect_to_smtp_settings() {
        if (HashFormHelper::is_admin_page('hashform-smtp') && function_exists('wp_mail_smtp') && (is_plugin_active('wp-mail-smtp/wp_mail_smtp.php'))) {
            wp_safe_redirect(admin_url('admin.php?page=wp-mail-smtp'));
            exit;
        }
    }

}

new HashFormSmtp();
