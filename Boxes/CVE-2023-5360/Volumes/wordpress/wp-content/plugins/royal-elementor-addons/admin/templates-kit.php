<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WprAddons\Admin\Templates\Library\WPR_Templates_Data;
use WprAddons\Classes\Utilities;
use Elementor\Plugin;

// Register Menus
function wpr_addons_add_templates_kit_menu() {
    add_submenu_page( 'wpr-addons', 'Templates Kit', 'Templates Kit', 'manage_options', 'wpr-templates-kit', 'wpr_addons_templates_kit_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_templates_kit_menu' );

// Import Template Kit
add_action( 'wp_ajax_wpr_activate_required_theme', 'wpr_activate_required_theme' );
add_action( 'wp_ajax_wpr_activate_required_plugins', 'wpr_activate_required_plugins' );
add_action( 'wp_ajax_wpr_fix_royal_compatibility', 'wpr_fix_royal_compatibility' );
add_action( 'wp_ajax_wpr_reset_previous_import', 'wpr_reset_previous_import' );
add_action( 'wp_ajax_wpr_import_templates_kit', 'wpr_import_templates_kit' );
add_action( 'wp_ajax_wpr_final_settings_setup', 'wpr_final_settings_setup' );
add_action( 'wp_ajax_wpr_search_query_results', 'wpr_search_query_results' );
add_action( 'init', 'disable_default_woo_pages_creation', 2 );

// Set Image Timeout
if ( version_compare( get_bloginfo( 'version' ), '5.1.0', '>=' ) ) {
    add_filter( 'http_request_timeout', 'set_image_request_timeout', 10, 2 );
}

/**
** Render Templates Kit Page
*/
function wpr_addons_templates_kit_page() {

?>

<div class="wpr-templates-kit-page">

    <header>
        <div class="wpr-templates-kit-logo">
            <div><img src="<?php echo !empty(get_option('wpr_wl_plugin_logo')) ? esc_url(wp_get_attachment_image_src(get_option('wpr_wl_plugin_logo'), 'full')[0]) : esc_url(WPR_ADDONS_ASSETS_URL .'img/logo-40x40.png'); ?>"></div>
            <div class="back-btn"><?php printf( esc_html__('%s Back to Library', 'wpr-addons'), '<span class="dashicons dashicons-arrow-left-alt2"></span>'); ?></div>
        </div>

        <div class="wpr-templates-kit-search">
            <input type="text" autocomplete="off" placeholder="<?php esc_html_e('Search Templates Kit...', 'wpr-addons'); ?>">
            <span class="dashicons dashicons-search"></span>
        </div>

        <div class="wpr-templates-kit-price-filter">
            <span data-price="mixed"><?php esc_html_e('Price: Mixed', 'wpr-addons'); ?></span>
            <span class="dashicons dashicons-arrow-down-alt2"></span>
            <ul>
                <li><?php esc_html_e('Mixed', 'wpr-addons'); ?></li>
                <li><?php esc_html_e('Free', 'wpr-addons'); ?></li>
                <li><?php esc_html_e('Premium', 'wpr-addons'); ?></li>
            </ul>
        </div>

        <div class="wpr-templates-kit-filters">
            <div>Filter: All</div>
            <ul>
                <li data-filter="all">Blog</li>
                <li data-filter="blog">Blog</li>
                <li data-filter="business">Business</li>
                <li data-filter="ecommerce">eCommerce</li>
                <li data-filter="beauty">Beauty</li>
            </ul>
        </div>
    </header>

    <div class="wpr-templates-kit-page-title">
        <h1><?php esc_html_e('Royal Elementor Templates Kit', 'wpr-addons'); ?></h1>
        <p><?php esc_html_e('Import any Templates Kit with just a Single click', 'wpr-addons'); ?></p>
        <p>
            <a href="https://www.youtube.com/watch?v=kl2xBoWW81o" class="wpr-options-button button" target="_blank">
                <?php esc_html_e('Video Tutorial', 'wpr-addons'); ?>
                <span class="dashicons dashicons-video-alt3"></span>
            </a>
        </p>
    </div>

    <div class="wpr-templates-kit-grid main-grid" data-theme-status="<?php echo esc_attr(get_theme_status()); ?>">
        <?php
            $kits = WPR_Templates_Data::get_available_kits();
            $sorted_kits = [];

            foreach ($kits as $slug => $kit) {
                foreach ($kit as $version => $data ) {
                    $sorted_kits[$slug .'-'. $version] = $data;
                }
            }

            // Sort by Priority
            uasort($sorted_kits, function ($item1, $item2) {
                if ($item1['priority'] == $item2['priority']) return 0;
                return $item1['priority'] < $item2['priority'] ? -1 : 1;
            });           

            // Loop
            foreach ($sorted_kits as $kit_id => $data) {
                $is_expert = isset($data['expert']) && 'expert' === $data['expert'] ? 'true' : 'false';

                echo '<div class="grid-item" data-kit-id="'. esc_attr($kit_id) .'" data-title="'. esc_attr(strtolower($data['name'])) .'" data-tags="'. esc_attr($data['tags']) .'" data-plugins="'. esc_attr($data['plugins']) .'" data-pages="'. esc_attr($data['pages']) .'" data-price="'. esc_attr($data['price']) .'" data-expert="'. esc_attr($is_expert) .'">';
                    echo '' !== $data['label'] ? '<span class="label label-'. esc_attr($data['label']) .'">'. esc_html($data['label']) .'</span>' : '';
                    echo '<div class="image-wrap">';
                        echo '<img src="'. esc_url('https://royal-elementor-addons.com/library/templates-kit/'. $kit_id .'/home.jpg') .'">';
                        echo '<div class="image-overlay"><span class="dashicons dashicons-search"></span></div>';
                    echo '</div>';
                    echo '<footer>';
                        echo '<h3>'. esc_html($data['name']) .'</h3>';
                        if ( $data['woo-builder'] ) {
                            echo '<span class="wpr-woo-builder-label">'. esc_html__( 'Woo Builder', 'wpr-addons' ) .'</span>';
                        } elseif ( $data['theme-builder'] ) {
                            echo '<span class="wpr-theme-builder-label">'. esc_html__( 'Theme Builder', 'wpr-addons' ) .'</span>';
                        }
                    echo '</footer>';
                echo '</div>';
            }

        ?>

    </div>

    <div class="wpr-templates-kit-single">
        <?php if ( !wpr_fs()->is_plan( 'expert' ) ) : ?>
        <div class="wpr-templates-kit-expert-notice">
            <p>
                <span class="dashicons dashicons-warning"></span>
                <strong>Important Notice:</strong> This Demo includes certain <strong>Expert Features</strong>. While you can still import this Template Kit with a <strong>Pro Plan</strong>, you'll need an <strong>Expert Plan</strong> to fully access all Expert options. Please visit our website for more <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-templates-upgrade-expert#purchasepro" target="_blank">details on the Expert Plan</a>.
            </p>
        </div>
        <?php endif; ?>
        
        <div class="wpr-templates-kit-grid single-grid"></div>

        <footer class="action-buttons-wrap">
            <a href="https://royal-elementor-addons.com/" class="preview-demo button" target="_blank"><?php esc_html_e('Preview Demo', 'wpr-addons'); ?> <span class="dashicons dashicons-external"></span></a>
            <a href="https://www.youtube.com/watch?v=G47y0zA-tFg" class="import-tutorial-link" target="_blank"><span class="dashicons dashicons-video-alt3"></span> <?php esc_html_e('How to Import Single Pages?', 'wpr-addons'); ?></a>

            <div class="import-template-buttons">
            <?php
                    echo '<button class="import-kit button">'. esc_html__('Import Templates Kit', 'wpr-addons') .' <span class="dashicons dashicons-download"></span></button>';
                    echo '<a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-templates-upgrade-pro#purchasepro" class="get-access button" target="_blank">'. esc_html__('Upgrade to PRO', 'wpr-addons') .' <span class="dashicons dashicons-external"></span></a>';
                ?>
                <button class="import-template button"><?php printf( esc_html__( 'Import %s Template', 'wpr-addons' ), '<strong></strong>' ); ?></button>
                
            </div>
        </footer>
    </div>

    <div class="wpr-import-kit-popup-wrap">
        <div class="overlay"></div>
        <div class="wpr-import-kit-popup">
            <header>
                <h3><?php esc_html_e('Template Kit is being imported', 'wpr-addons'); ?><span>.</span></h3>
                <span class="dashicons dashicons-no-alt close-btn"></span>
            </header>
            <div class="content">
                <p><?php esc_html_e('The import process can take a few seconds depending on the size of the kit you are importing and speed of the connection.', 'wpr-addons'); ?></p>
                <p><?php esc_html_e('Please do NOT close this browser window until import is completed.', 'wpr-addons'); ?></p>

                <?php // if wp version is lower than 6.1

                if ( version_compare( get_bloginfo('version'), '6.1', '<' ) ) {
                    echo '<p style="display:none;color: #f44;font-weight:bold;" class="wpr-wp-update-notice">'. esc_html__('Demo Content could NOT be imported, WordPress version is too old! Please update WordPress to the Latest version.', 'wpr-addons') .'</p>';
                }

                ?>

                <div class="progress-wrap">
                    <div class="progress-bar"></div>
                    <strong></strong>
                </div>

                <div class="wpr-import-help">
                    <a href="https://royal-elementor-addons.com/contactus/?ref=rea-plugin-backend-templates-import-screen" target="_blank">Having trouble with template import?&nbsp;&nbsp;Get help <span class="dashicons dashicons-sos"></span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="wpr-templates-kit-not-found">
        <img src="<?php echo esc_url(WPR_ADDONS_ASSETS_URL .'img/not-found.png'); ?>">
        <h1><?php esc_html_e('No Search Results Found.', 'wpr-addons'); ?></h1>
        <p><?php esc_html_e('Cant find a Templates Kit you are looking for?', 'wpr-addons'); ?></p>
        <a href="https://royal-elementor-addons.com/library/request-new-kit-red.html" target="_blank"><?php esc_html_e('Request Templates Kit', 'wpr-addons'); ?></a>
    </div>

</div>


<?php

} // End wpr_addons_templates_kit_page()

/**
** Get Theme Status
*/
function get_theme_status() {
    $theme = wp_get_theme();

    // Theme installed and activate.
    if ( 'Royal Elementor Kit' === $theme->name || 'Royal Elementor Kit' === $theme->parent_theme ) {
        return 'req-theme-active';
    }

    // Theme installed but not activate.
    foreach ( (array) wp_get_themes() as $theme_dir => $theme ) {
        if ( 'Royal Elementor Kit' === $theme->name || 'Royal Elementor Kit' === $theme->parent_theme ) {
            return 'req-theme-inactive';
        }
    }

    return 'req-theme-not-installed';
}

/**
** Install/Activate Required Theme
*/
function wpr_activate_required_theme() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }
    
    // Get Current Theme
    $theme = get_option('stylesheet');

    // Activate Royal Elementor Kit Theme
    if ( 'ashe-pro-premium' !== $theme && 'bard-pro-premium' !== $theme
        && 'vayne-pro-premium' !== $theme && 'kayn-pro-premium' !== $theme ) {
        switch_theme( 'royal-elementor-kit' );
        set_transient( 'royal-elementor-kit_activation_notice', true );
    }

    // TODO: maybe return back  - 'ashe' !== $theme && 'bard' !== $theme && 
}

/**
** Activate Required Plugins
*/
function wpr_activate_required_plugins() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js')  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    if ( isset($_POST['plugin']) ) {
        if ( 'contact-form-7' == $_POST['plugin'] ) {
            if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
                activate_plugin( 'contact-form-7/wp-contact-form-7.php' );
            }
        } elseif ( 'woocommerce' == $_POST['plugin'] ) {
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                activate_plugin( 'woocommerce/woocommerce.php' );
            }
        } elseif ( 'media-library-assistant' == $_POST['plugin'] ) {
            if ( !is_plugin_active( 'media-library-assistant/index.php' ) ) {
                activate_plugin( 'media-library-assistant/index.php' );
            }
        }
    }
}

/**
** Deactivate Extra Plugins
*/
function wpr_fix_royal_compatibility() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    // Get currently active plugins
    $active_plugins = (array) get_option( 'active_plugins', array() );
    $active_plugins = array_values($active_plugins);
    $required_plugins = [
        'elementor/elementor.php',
        'royal-elementor-addons/wpr-addons.php',
        'royal-elementor-addons-pro/wpr-addons-pro.php',
        'wpr-addons-pro/wpr-addons-pro.php',
        'contact-form-7/wp-contact-form-7.php',
        'woocommerce/woocommerce.php',
        'really-simple-ssl/rlrsssl-really-simple-ssl.php',
        'wp-mail-smtp/wp_mail_smtp.php',
        'updraftplus/updraftplus.php',
        'temporary-login-without-password/temporary-login-without-password.php',
        'wp-reset/wp-reset.php'
    ];

    // Deactivate Extra Import Plugins
    foreach ( $active_plugins as $key => $value ) {
        if ( ! in_array($value, $required_plugins) ) {
            $active_key = array_search($value, $active_plugins);;
            unset($active_plugins[$active_key]);
        }
    }

    // Set Active Plugins
    update_option( 'active_plugins', array_values($active_plugins) );

    // Get Current Theme
    $theme = get_option('stylesheet');

    // Activate Royal Elementor Kit Theme
    if ( 'ashe-pro-premium' !== $theme && 'bard-pro-premium' !== $theme
        && 'vayne-pro-premium' !== $theme && 'kayn-pro-premium' !== $theme ) {
        switch_theme( 'royal-elementor-kit' );
        set_transient( 'royal-elementor-kit_activation_notice', true );
    }
}

/**
** Import Template Kit
*/
function wpr_import_templates_kit() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }

    // Temp Define Importers
    if ( ! defined('WP_LOAD_IMPORTERS') ) {
        define('WP_LOAD_IMPORTERS', true);
    }

    // Include if Class Does NOT Exist
    if ( ! class_exists( 'WP_Import' ) ) {
        $class_wp_importer = WPR_ADDONS_PATH .'admin/import/class-wordpress-importer.php';
        if ( file_exists( $class_wp_importer ) ) {
            require $class_wp_importer;
        }
    }

    if ( class_exists( 'WP_Import' ) ) {
        $kit = isset($_POST['wpr_templates_kit']) ? sanitize_file_name(wp_unslash($_POST['wpr_templates_kit'])) : '';
        $file = isset($_POST['wpr_templates_kit_single']) ? sanitize_file_name(wp_unslash($_POST['wpr_templates_kit_single'])) : '';

        // Tmp
        update_option( 'wpr-import-kit-id', $kit );

        // Disable Extra Image Sizes
        add_filter( 'intermediate_image_sizes_advanced', [new Utilities, 'disable_extra_image_sizes'], 10, 3 );

        // No Limit for Execution
        set_time_limit(0);

        // Download Import File
        $local_file_path = download_template( $kit, $file );

        // Prepare for Import
        $wp_import = new WP_Import( $local_file_path, ['fetch_attachments' => true] );

        // Import
        ob_start();
            $wp_import->run();
        ob_end_clean();

        // Delete Import File
        unlink( $local_file_path );

        // Send to JS
        echo esc_html(serialize( $wp_import ));
    }

}

/**
** Download Template
*/
function download_template( $kit, $file ) {
    $file = ! $file ? 'main' : $file;

    // Avoid Cache
    $randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);

    // Remote and Local Files
    $remote_file_url = 'https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/main.xml?='. $randomNum;

    // If the function it's not available, require it.
    if ( ! function_exists( 'download_url' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    if ( !vts( $kit ) ) {
        return;
    }

    $tmp_file = download_url( $remote_file_url );

    // WP Error.
    if ( is_wp_error( $tmp_file ) ) {
        // Fallback URL
        $remote_file_url = 'https://mysitetutorial.com/library/templates-kit/'. $kit .'/main.xml?='. $randomNum;
        $tmp_file = download_url( $remote_file_url );

        if ( is_wp_error( $tmp_file ) ) {
            // Track Import Failed Kit
            wpr_track_import_failed_kit( $kit );

            wp_send_json_error([
                'error' => esc_html__('Error: Import File download failed.', 'wpr-addons'),
                'help' => esc_html__('Please contact Customer Support and send this Error.', 'wpr-addons'),
                'problem' => 'download'
            ]);
            
            return false;
        }
    }

    // Array based on $_FILE as seen in PHP file uploads.
    $file_args = [
        'name'     => 'main.xml',
        'tmp_name' => $tmp_file,
        'error'    => 0,
        'size'     => filesize( $tmp_file ),
    ];

    $defaults = array(
        'test_form' => false,
        'test_size' => true,
        'test_upload' => true,
        'mimes'  => [
            'xml'  => 'text/xml',
            'json' => 'text/plain',
        ],
        'wp_handle_sideload' => 'upload',
    );

    // Move the temporary file into the uploads directory.
    $local_file = wp_handle_sideload( $file_args, $defaults );

    if ( isset( $local_file['error'] ) ) {
        wp_send_json_error([
            'error' => esc_html__('Error: Import File upload failed.', 'wpr-addons'),
            'help' => esc_html__('Please contact Customer Support and send this Error.', 'wpr-addons'),
            'problem' => 'upload'
        ]);
        return false;
    }

    // Success.
    return $local_file['file'];
}

/**
** Validate Template
*/
function vts( $kit ) {
    // Avoid Cache
    $randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);

    $remote_file_url = 'https://royal-elementor-addons.com/library/vts.json?='. $randomNum;

    // If the function it's not available, require it.
    if ( ! function_exists( 'download_url' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    $tmp_file = download_url( $remote_file_url );

    // WP Error.
    if ( is_wp_error( $tmp_file ) ) {
        // Track Import Failed Kit
        wpr_track_import_failed_kit( $kit );

        wp_send_json_error([
            'error' => esc_html__('Error: Import File download failed.', 'wpr-addons'),
            'help' => esc_html__('Please contact Customer Support and send this Error.', 'wpr-addons'),
            'problem' => 'download'
        ]);
        
        return false;
    }

    $file_args = [
        'name'     => 'vts.json',
        'tmp_name' => $tmp_file,
        'error'    => 0,
        'size'     => filesize( $tmp_file ),
    ];

    $defaults = array(
        'test_form' => false,
        'test_size' => true,
        'test_upload' => true,
        'mimes'  => [
            'xml'  => 'text/xml',
            'json' => 'text/plain',
        ],
        'wp_handle_sideload' => 'upload',
    );

    $local_file = wp_handle_sideload( $file_args, $defaults );

    if ( isset( $local_file['error'] ) ) {
        return false;
    }

    $tmps = json_decode(file_get_contents($local_file['file']));

    // Delete Import File
    unlink( $local_file['file'] );

    return in_array($kit, $tmps) && !wpr_fs()->can_use_premium_code() ? false : true;
}

/**
** Reset Previous Import
*/
function wpr_reset_previous_import() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }
    
    $args = [
        'post_type' => [
            'page',
            'post',
            'product',
            'wpr_mega_menu',
            'wpr_templates',
            'elementor_library',
            'attachment',

            'acf-post-type',
            'acf-taxonomy',
            'acf-field-group',

            'wpr_portfolio'
        ],
        'post_status' => 'any',
        'posts_per_page' => '-1',
        'meta_key' => '_wpr_demo_import_item'
    ];

    $imported_items = new WP_Query ( $args );

    if ( $imported_items->have_posts() ) {
        while ( $imported_items->have_posts() ) {
            $imported_items->the_post();
        
            // Dont Delete Elementor Kit
            if ( 'Default Kit' == get_the_title() ) {
                continue;
            }

            // Delete Posts
            wp_delete_post( get_the_ID(), true );
        }

        // Reset
        wp_reset_query();

        $imported_terms = get_terms([
            'meta_key' => '_wpr_demo_import_item',
            'posts_per_page' => -1,
            'hide_empty' => false,
        ]);

        if ( !empty($imported_terms) ) {
            foreach( $imported_terms as $imported_term ) {
                // Delete Terms
                wp_delete_term( $imported_term->term_id, $imported_term->taxonomy );
            }
        }

        wp_send_json_success( esc_html__('Previous Import Files have been successfully Reset.', 'wpr-addons') );
    } else {
        wp_send_json_success( esc_html__('There is no Data for Reset.', 'wpr-addons') );
    }
}

/**
** Import Elementor Site Settings
*/
function import_elementor_site_settings( $kit ) {
    // Avoid Cache
    // $randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);

    // Get Remote File
    $site_settings = @file_get_contents('https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/site-settings.json');

    if ( false !== $site_settings ) {
        $site_settings = json_decode($site_settings, true);

        if ( ! empty($site_settings['settings']) ) {
            $default_kit = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( get_option( 'elementor_active_kit' ) );

            $kit_settings = $default_kit->get_settings();
            $new_settings = $site_settings['settings'];
            $settings = array_merge($kit_settings, $new_settings);

            $default_kit->save( [ 'settings' => $settings ] );
        }
    }
}

/**
*** Add Elementor support for Custom Post Types
**/
function add_elementor_cpt_support( $post_type ) {
    if ( ! is_admin() ) {
        return;
    }

    $cpt_support = get_option( 'elementor_cpt_support' );
    
    if ( ! $cpt_support ) {
        update_option( 'elementor_cpt_support', ['post', 'page', $post_type] );
    } elseif ( ! in_array( $post_type, $cpt_support ) ) {
        $cpt_support[] = $post_type;
        update_option( 'elementor_cpt_support', $cpt_support );
    }
}

/**
** Setup WPR Templates
*/
function setup_wpr_templates( $kit ) {
    $kit = isset($kit) ? sanitize_text_field(wp_unslash($kit)) : '';

    // Check if kit has Theme Builder templates
    $kit_name = substr($kit, 0, strripos($kit, '-v'));
    $kit_version = substr($kit, (strripos($kit, '-v') + 1), strlen($kit));
    $get_available_kits = WPR_Templates_Data::get_available_kits();
    $get_custom_types = $get_available_kits[$kit_name][$kit_version]['custom-types'];
    $has_theme_builder = $get_available_kits[$kit_name][$kit_version]['theme-builder'];
    $has_woo_builder = $get_available_kits[$kit_name][$kit_version]['woo-builder'];
    $has_off_canvas = $get_available_kits[$kit_name][$kit_version]['off-canvas'];
    $custom_type_archive_conditions = [];
    $custom_type_single_conditions = [];

    // Set Home & Blog Pages
    $home_page = get_page_by_path('home-'. $kit);
    $blog_page = get_page_by_path('blog-'. $kit);

    if ( $home_page ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $home_page->ID );
        
        if ( $blog_page ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }
    }

    // Set Headers and Footers
    update_option('wpr_header_conditions', '{"user-header-'. $kit .'-header":["global"]}');
    update_post_meta( Utilities::get_template_id('user-header-'. $kit), 'wpr_header_show_on_canvas', 'true' );
    update_option('wpr_footer_conditions', '{"user-footer-'. $kit .'-footer":["global"]}');
    update_post_meta( Utilities::get_template_id('user-footer-'. $kit), 'wpr_footer_show_on_canvas', 'true' );

    // Custom Post Types & Taxonomies
    if ( isset($get_custom_types) && wpr_fs()->is_plan( 'expert' ) ) {
        $index = 0;

        foreach ($get_custom_types as $label => $slug) {
            $label = str_replace('wpr-', '', $label);

            if ( 0 > $index ) {
                $custom_type_archive_conditions[] = '"user-archive-'. $kit .'-'. $label .'":["archive/'. $slug .'/all"]';
            } else {
                $custom_type_archive_conditions[] = '"user-archive-'. $kit .'-'. $label .'":["archive/'. $slug .'"]';
                $custom_type_single_conditions[] = '"user-single-'. $kit .'-'. $label .'":["single/'. $slug.'/all"]';
                add_elementor_cpt_support( $slug );
            }

            $index++;
        }
        
        $custom_type_archive_conditions = implode(',',$custom_type_archive_conditions);
        $custom_type_single_conditions = implode(',',$custom_type_single_conditions);

        if ( get_option('permalink_structure') !== '/%postname%/' ) {
            // Set permalink structure to post name
            update_option('permalink_structure', '/%postname%/');
        }
    
        // Flush rewrite rules
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    // Theme Builder
    if ( $has_theme_builder ) {
        $custom_type_archive_conditions = isset($get_custom_types) ? ','. $custom_type_archive_conditions : '';
        $custom_type_single_conditions = isset($get_custom_types) ? ','. $custom_type_single_conditions : '';

        update_option('wpr_archive_conditions', '{"user-archive-'. $kit .'-blog":["archive/posts"],"user-archive-'. $kit .'-author":["archive/author"],"user-archive-'. $kit .'-date":["archive/date"],"user-archive-'. $kit .'-category-tag":["archive/categories/all","archive/tags/all"],"user-archive-'. $kit .'-search":["archive/search"]'. $custom_type_archive_conditions .'}');
        update_option('wpr_single_conditions', '{"user-single-'. $kit .'-404":["single/page_404"],"user-single-'. $kit .'-post":["single/posts/all"],"user-single-'. $kit .'-page":["single/pages/all"]'. $custom_type_single_conditions .'}');
    } elseif ( isset($get_custom_types) ) {
        update_option('wpr_archive_conditions', '{'. $custom_type_archive_conditions .'}');
        update_option('wpr_single_conditions', '{'. $custom_type_single_conditions .'}');
    }

    // WooCommerce Builder
    if ( $has_woo_builder ) {
        update_option('wpr_product_archive_conditions', '{"user-product_archive-'. $kit .'-shop":["product_archive/products"],"user-product_archive-'. $kit .'-product-category-tag":["product_archive/product_cat/all","product_archive/product_tag/all"]}');
        update_option('wpr_product_single_conditions', '{"user-product_single-'. $kit .'-product":["product_single/product"]}');

        $shop_id = get_page_by_path('shop-'. $kit) ? get_page_by_path('shop-'. $kit)->ID : '';
        $cart_id = get_page_by_path('cart-'. $kit) ? get_page_by_path('cart-'. $kit)->ID : '';
        $checkout_id = get_page_by_path('checkout-'. $kit) ? get_page_by_path('checkout-'. $kit)->ID : '';
        $myaccount_id = get_page_by_path('my-account-'. $kit) ? get_page_by_path('my-account-'. $kit)->ID : '';
        
        update_option('woocommerce_shop_page_id', $shop_id);
        update_option('woocommerce_cart_page_id', $cart_id);
        update_option('woocommerce_checkout_page_id', $checkout_id);

        if ( '' !== $myaccount_id ) {
            update_option('woocommerce_myaccount_page_id', $myaccount_id);
        }

        // Update Options
        update_option( 'woocommerce_queue_flush_rewrite_rules', 'yes' );

        // Set Wishlist and Compare
        $wishlist_page = get_page_by_path('wishlist-'. $kit, OBJECT, 'page');
        $compare_page = get_page_by_path('compare-'. $kit, OBJECT, 'page');

        if ( $wishlist_page ) {
            update_option('wpr_wishlist_page', $wishlist_page->ID);
        }

        if ( $compare_page ) {
            update_option('wpr_compare_page', $compare_page->ID);
        }
        

        // Enable Elementor Builder for WooCommerce CPT
        // $cpt_support = get_option( 'elementor_cpt_support' );
		
		// if ( ! in_array( 'product', $cpt_support ) ) {
		//     $cpt_support[] = 'product';
		//     update_option( 'elementor_cpt_support', $cpt_support );
		// }
    }

    // Set Popups
    if ( $has_off_canvas ) {
        update_option('wpr_popup_conditions', '{"user-popup-'. $kit .'-off-canvas":["global"],"user-popup-'. $kit .'-popup":["global"]}');
    } else {
        update_option('wpr_popup_conditions', '{"user-popup-'. $kit .'-popup":["global"]}');
    }
}

/**
** Fix Elementor Images
*/
function wpr_fix_elementor_images() {
    $args = array(
        'post_type' => ['wpr_templates', 'wpr_mega_menu', 'page'],
        'posts_per_page' => '-1',
        'meta_key' => '_elementor_version'
    );
    $elementor_pages = new WP_Query ( $args );

    // Check that we have query results.
    if ( $elementor_pages->have_posts() ) {
     
        // Start looping over the query results.
        while ( $elementor_pages->have_posts() ) {

            $elementor_pages->the_post();

            // Replace Demo with Current
            $site_url = get_site_url();
            $site_url = str_replace( '/', '\/', $site_url );
            $demo_site_url = 'https://demosites.royal-elementor-addons.com/'. get_option('wpr-import-kit-id');
            $demo_site_url = str_replace( '/', '\/', $demo_site_url );

            // Elementor Data
            $data = get_post_meta( get_the_ID(), '_elementor_data', true );

            if ( ! empty( $data ) ) {
                $data = preg_replace('/\\\{1}\/sites\\\{1}\/\d+/', '', $data);
                $data = str_replace( $demo_site_url, $site_url, $data );
                $data = json_decode( $data, true );
            }

            update_metadata( 'post', get_the_ID(), '_elementor_data', $data );

            // Elementor Page Settings
            $page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
            $page_settings = json_encode($page_settings);

            if ( ! empty( $page_settings ) ) {
                $page_settings = preg_replace('/\\\{1}\/sites\\\{1}\/\d+/', '', $page_settings);
                $page_settings = str_replace( $demo_site_url, $site_url, $page_settings );
                $page_settings = json_decode( $page_settings, true );
            }

            update_metadata( 'post', get_the_ID(), '_elementor_page_settings', $page_settings );

        }
     
    }

    // Clear Elementor Cache
    Plugin::$instance->files_manager->clear_cache();
}

/**
** Final Settings Setup
*/
function wpr_final_settings_setup() {

    $nonce = $_POST['nonce'];

    if ( !wp_verify_nonce( $nonce, 'wpr-templates-kit-js' )  || !current_user_can( 'manage_options' ) ) {
      exit; // Get out of here, the nonce is rotten!
    }
    
    $kit = !empty(get_option('wpr-import-kit-id')) ? esc_html(get_option('wpr-import-kit-id')) : '';

    // Elementor Site Settings
    import_elementor_site_settings($kit);

    // Setup WPR Templates
    setup_wpr_templates($kit);

    // Fix Elementor Images
    wpr_fix_elementor_images();

    // Track Kit
    wpr_track_imported_kit( $kit );

    // Clear DB
    delete_option('wpr-import-kit-id');

    // Delete Hello World Post
    $post = get_page_by_path('hello-world', OBJECT, 'post');
    if ( $post ) {
        wp_delete_post($post->ID,true);
    }

    // Regenerate Extra Image Sizes
    Utilities::regenerate_extra_image_sizes();
}

/**
** Validate Image Extension
*/
function wpr_validate_image_ext( $link = '' ) {
    return preg_match( '/^((https?:\/\/)|(www\.))([a-z0-9-].?)+(:[0-9]+)?\/[\w\-\@]+\.(jpg|png|gif|jpeg|svg)\/?$/i', $link );
}

/**
** Set Timeout for Image Request
*/
function set_image_request_timeout( $timeout_value, $url ) {
    if ( strpos( $url, 'https://royal-elementor-addons.com/' ) === false ) {
        return $timeout_value;
    }

    $valid_ext = preg_match( '/^((https?:\/\/)|(www\.))([a-z0-9-].?)+(:[0-9]+)?\/[\w\-\@]+\.(jpg|png|gif|jpeg|svg)\/?$/i', $url );

    if ( $valid_ext ) {
        $timeout_value = 300;
    }

    return $timeout_value;
}

/**
** Prevent WooCommerce creating default pages
*/
function disable_default_woo_pages_creation() {
    add_filter( 'woocommerce_create_pages', '__return_empty_array' );
}

/**
** Add .xml and .svg files as supported format in the uploader.
*/
function custom_upload_mimes( $mimes ) {
    // Allow SVG files.
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    // Allow XML files.
    $mimes['xml'] = 'text/xml';

    // Allow JSON files.
    $mimes['json'] = 'application/json';

    return $mimes;
}

add_filter( 'upload_mimes', 'custom_upload_mimes', 99 );

function real_mime_types_5_1_0( $defaults, $file, $filename, $mimes, $real_mime ) {
    return real_mimes( $defaults, $filename );
}

function real_mime_types( $defaults, $file, $filename, $mimes ) {
    return real_mimes( $defaults, $filename );
}

function real_mimes( $defaults, $filename ) {
    if ( strpos( $filename, 'main' ) !== false ) {
        $defaults['ext']  = 'xml';
        $defaults['type'] = 'text/xml';
    }

    return $defaults;
}

if ( version_compare( get_bloginfo( 'version' ), '5.1.0', '>=' ) ) {
    add_filter( 'wp_check_filetype_and_ext', 'real_mime_types_5_1_0', 10, 5, 99 );
} else {
    add_filter( 'wp_check_filetype_and_ext', 'real_mime_types', 10, 4 );
}

/**
** Search Query Results
*/
function wpr_search_query_results() {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
		return;
	}
    
    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';

    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/templates-kit-search/data', [
        'body' => [
            'search_query' => $search_query
        ]
    ] );
}

/**
** Imported Kits
*/
function wpr_track_imported_kit( $kit ) {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
		return;
	}
    
    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/templates-kit-import/data', [
        'body' => [
            'imported_kit' => $kit . ' *'. WPR_ADDONS_VERSION .'*'
        ]
    ] );
}

/**
** Import Failed Kits
*/
function wpr_track_import_failed_kit( $kit ) {
    // Freemius OptIn
    // if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
    //     return;
    // }
    
    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/templates-kit-import-failed/data', [
        'body' => [
            'imported_kit' => get_site_url()
        ]
    ] );
}