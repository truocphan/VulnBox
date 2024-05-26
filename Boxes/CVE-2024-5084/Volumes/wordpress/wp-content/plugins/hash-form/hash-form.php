<?php

/*
 * Plugin Name: Hash Form - Drag & Drop Form Builder
 * Description: Design, Embed, Connect: Your Ultimate Form Companion for WordPress
 * Version: 1.1.0
 * Author: HashThemes
 * Author URI: https://hashthemes.com/
 * Text Domain: hash-form
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */


defined('ABSPATH') || die();

define('HASHFORM_VERSION', '1.1.0');
define('HASHFORM_FILE', __FILE__);
define('HASHFORM_PATH', plugin_dir_path(HASHFORM_FILE));
define('HASHFORM_URL', plugin_dir_url(HASHFORM_FILE));
define('HASHFORM_UPLOAD_DIR', '/hashform');

require HASHFORM_PATH . 'admin/classes/HashFormBlock.php';
require HASHFORM_PATH . 'admin/classes/HashFormUploader.php';
require HASHFORM_PATH . 'admin/classes/HashFormCreateTable.php';
require HASHFORM_PATH . 'admin/classes/HashFormBuilder.php';
require HASHFORM_PATH . 'admin/classes/HashFormHelper.php';
require HASHFORM_PATH . 'admin/classes/HashFormFields.php';
require HASHFORM_PATH . 'admin/classes/HashFormLoader.php';
require HASHFORM_PATH . 'admin/classes/HashFormSmtp.php';
require HASHFORM_PATH . 'admin/classes/HashFormEntry.php';
require HASHFORM_PATH . 'admin/classes/HashFormImportExport.php';
require HASHFORM_PATH . 'admin/classes/HashFormListing.php';
require HASHFORM_PATH . 'admin/classes/HashFormEntryListing.php';
require HASHFORM_PATH . 'admin/classes/HashFormValidate.php';
require HASHFORM_PATH . 'admin/classes/HashFormPreview.php';
require HASHFORM_PATH . 'admin/classes/HashFormShortcode.php';
require HASHFORM_PATH . 'admin/classes/HashFormSettings.php';
require HASHFORM_PATH . 'admin/classes/HashFormStyles.php';
require HASHFORM_PATH . 'admin/classes/HashFormGridHelper.php';
require HASHFORM_PATH . 'admin/classes/HashFormEmail.php';

/**
 * Register widget.
 */
add_action('elementor/widgets/register', 'hashform_elementor_widget_register');

function hashform_elementor_widget_register($widgets_manager) {
    require HASHFORM_PATH . 'admin/classes/HashFormElement.php';
    $widgets_manager->register(new \HashFormElement());
}

/**
 * Plugin Activation.
 */
register_activation_hook(HASHFORM_FILE, 'hashform_network_create_table');

function hashform_network_create_table($network_wide) {
    global $wpdb;

    if (is_multisite() && $network_wide) {
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            $db = new HashFormCreateTable();
            $db->upgrade();
            restore_current_blog();
        }
    } else {
        $db = new HashFormCreateTable();
        $db->upgrade();
    }
}

/**
 * Create form tables on single site.
 */
function hashform_create_table() {
    $db = new HashFormCreateTable();
    $db->upgrade();
}

/**
 * Create form tables on multisite creation.
 */
add_action('wp_insert_site', 'hashform_on_create_blog');

function hashform_on_create_blog($data) {
    if (is_plugin_active_for_network('hash-form/hash-form.php')) {
        switch_to_blog($data->blog_id);
        $db = new HashFormCreateTable();
        $db->upgrade();
        restore_current_blog();
    }
}

/**
 * Drop form tables on multisite deletion.
 */
add_filter('wpmu_drop_tables', 'hashform_on_delete_blog');

function hashform_on_delete_blog($tables) {
    global $wpdb;
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $tables[] = $wpdb->get_blog_prefix($id) . 'hashform_fields';
    $tables[] = $wpdb->get_blog_prefix($id) . 'hashform_forms';
    $tables[] = $wpdb->get_blog_prefix($id) . 'hashform_entries';
    $tables[] = $wpdb->get_blog_prefix($id) . 'hashform_entry_meta';
    return $tables;
}
