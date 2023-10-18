<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WprAddons\Admin\Includes\WPR_Templates_Loop;
use WprAddonsPro\Admin\Wpr_White_Label;
use WprAddonsPro\Classes\Pro_Modules;
use WprAddons\Classes\Utilities;
use WprAddons\Admin\Templates\Library\WPR_Templates_Data;

// Register Menus
function wpr_addons_add_admin_menu() {
    $menu_icon = !empty(get_option('wpr_wl_plugin_logo')) ? 'dashicons-admin-generic' : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTciIGhlaWdodD0iNzUiIHZpZXdCb3g9IjAgMCA5NyA3NSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTAuMDM2NDA4NiAyMy4yODlDLTAuNTc1NDkgMTguNTIxIDYuNjg4NzMgMTYuMzY2NiA5LjU0OSAyMC40Njc4TDQyLjgzNjUgNjguMTk3MkM0NC45MTgxIDcxLjE4MiA0Mi40NDk0IDc1IDM4LjQzNzggNzVIMTEuMjc1NkM4LjY1NDc1IDc1IDYuNDUyNjQgNzMuMjg1NSA2LjE2MTcgNzEuMDE4NEwwLjAzNjQwODYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTk2Ljk2MzYgMjMuMjg5Qzk3LjU3NTUgMTguNTIxIDkwLjMxMTMgMTYuMzY2NiA4Ny40NTEgMjAuNDY3OEw1NC4xNjM1IDY4LjE5NzJDNTIuMDgxOCA3MS4xODIgNTQuNTUwNiA3NSA1OC41NjIyIDc1SDg1LjcyNDRDODguMzQ1MiA3NSA5MC41NDc0IDczLjI4NTUgOTAuODM4MyA3MS4wMTg0TDk2Ljk2MzYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTUzLjI0MTIgNC40ODUyN0M1My4yNDEyIC0wLjI3MDc2MSA0NS44NDg1IC0xLjc0ODAzIDQzLjQ2NTEgMi41MzE3NEw2LjY4OTkxIDY4LjU2NzdDNS4wMzM0OSA3MS41NDIxIDcuNTIyNzIgNzUgMTEuMzIwMyA3NUg0OC4wOTU1QzUwLjkzNzQgNzUgNTMuMjQxMiA3Mi45OTQ4IDUzLjI0MTIgNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTQzLjc1ODggNC40ODUyN0M0My43NTg4IC0wLjI3MDc2MSA1MS4xNTE1IC0xLjc0ODAzIDUzLjUzNDkgMi41MzE3NEw5MC4zMTAxIDY4LjU2NzdDOTEuOTY2NSA3MS41NDIxIDg5LjQ3NzMgNzUgODUuNjc5NyA3NUg0OC45MDQ1QzQ2LjA2MjYgNzUgNDMuNzU4OCA3Mi45OTQ4IDQzLjc1ODggNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==';
    add_menu_page( Utilities::get_plugin_name(), Utilities::get_plugin_name(), 'manage_options', 'wpr-addons', 'wpr_addons_settings_page', $menu_icon, '58.6' );
    
    add_action( 'admin_init', 'wpr_register_addons_settings' );
    add_filter( 'plugin_action_links_'. WPR_ADDONS_PLUGIN_BASE, 'wpr_settings_link' );
}
add_action( 'admin_menu', 'wpr_addons_add_admin_menu' );

// Ajax Hooks
add_action( 'wp_ajax_wpr_backend_widget_search_query_results', 'wpr_backend_widget_search_query_results' );
add_action( 'wp_ajax_wpr_backend_freepro_search_query_results', 'wpr_backend_freepro_search_query_results' );

// Add Settings page link to plugins screen
function wpr_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wpr-addons">Settings</a>';
    array_push( $links, $settings_link );

    if ( !is_plugin_installed('wpr-addons-pro/wpr-addons-pro.php') ) { // GOGA - Check if ok
        $links[] = '<a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-wpplugindashboard-upgrade-pro#purchasepro" style="color:#93003c;font-weight:700" target="_blank">' . esc_html__('Go Pro', 'wpr-addons') . '</a>';
    } elseif ( !wpr_fs()->is_plan( 'expert' ) ) {
        $links[] = '<a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-wpplugindashboard-upgrade-expert#purchasepro" style="color:#93003c;font-weight:700" target="_blank">' . esc_html__('Go Expert', 'wpr-addons') . '</a>';
    }

    return $links;
}

function is_plugin_installed($file) {
    $installed_plugins = [];

    foreach( get_plugins() as $slug => $plugin_info ) {
        array_push($installed_plugins, $slug);
    }

    if ( in_array($file, $installed_plugins) ) {
        return true;
    } else {
        return false;
    }
}

// Register Settings
function wpr_register_addons_settings() {
    // WooCommerce
    register_setting( 'wpr-settings', 'wpr_override_woo_templates' );
    register_setting( 'wpr-settings', 'wpr_override_woo_cart' );
    register_setting( 'wpr-settings', 'wpr_override_woo_mini_cart' );
    register_setting( 'wpr-settings', 'wpr_override_woo_notices' );
    register_setting( 'wpr-settings', 'wpr_remove_wc_default_lightbox' );
    register_setting( 'wpr-settings', 'wpr_enable_product_image_zoom' );
    register_setting( 'wpr-settings', 'wpr_enable_woo_flexslider_navigation' );
    register_setting( 'wpr-settings', 'wpr_add_wishlist_to_my_account' );
    register_setting( 'wpr-settings', 'wpr_woo_shop_ppp' );
    register_setting( 'wpr-settings', 'wpr_woo_shop_cat_ppp' );
    register_setting( 'wpr-settings', 'wpr_woo_shop_tag_ppp' );
    register_setting( 'wpr-settings', 'wpr_compare_page' );
    register_setting( 'wpr-settings', 'wpr_wishlist_page' );

    // Integrations
    register_setting( 'wpr-settings', 'wpr_google_map_api_key' );
    register_setting( 'wpr-settings', 'wpr_mailchimp_api_key' );
    register_setting( 'wpr-settings', 'wpr_recaptcha_v3_site_key' );
    register_setting( 'wpr-settings', 'wpr_recaptcha_v3_secret_key' );
    register_setting( 'wpr-settings', 'wpr_recaptcha_v3_score' );

    // Lightbox
    register_setting( 'wpr-settings', 'wpr_lb_bg_color' );
    register_setting( 'wpr-settings', 'wpr_lb_toolbar_color' );
    register_setting( 'wpr-settings', 'wpr_lb_caption_color' );
    register_setting( 'wpr-settings', 'wpr_lb_gallery_color' );
    register_setting( 'wpr-settings', 'wpr_lb_pb_color' );
    register_setting( 'wpr-settings', 'wpr_lb_ui_color' );
    register_setting( 'wpr-settings', 'wpr_lb_ui_hr_color' );
    register_setting( 'wpr-settings', 'wpr_lb_text_color' );
    register_setting( 'wpr-settings', 'wpr_lb_icon_size' );
    register_setting( 'wpr-settings', 'wpr_lb_arrow_size' );
    register_setting( 'wpr-settings', 'wpr_lb_text_size' );

    // White Label
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_logo' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_name' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_desc' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_author' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_website' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_links' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_elements_tab' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_extensions_tab' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_settings_tab' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_free_pro_tab' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_white_label_tab' );

    // Optimizers
    register_setting ('wpr-settings', 'wpr_ignore_wp_rocket_js');
    register_setting ('wpr-settings', 'wpr_ignore_wp_optimize_js');
    register_setting ('wpr-settings', 'wpr_ignore_wp_optimize_css');

    // Extensions
    register_setting('wpr-extension-settings', 'wpr-particles');
    register_setting('wpr-extension-settings', 'wpr-parallax-background');
    register_setting('wpr-extension-settings', 'wpr-parallax-multi-layer');
    register_setting('wpr-extension-settings', 'wpr-custom-css');
    register_setting('wpr-extension-settings', 'wpr-sticky-section');

    // Element Toggle
    register_setting( 'wpr-elements-settings', 'wpr-element-toggle-all', [ 'default' => 'on' ]  );

    // Widgets
    foreach ( Utilities::get_registered_modules() as $title => $data ) {
        $slug = $data[0];
        register_setting( 'wpr-elements-settings', 'wpr-element-'. $slug, [ 'default' => 'on' ] );
    }

    // Theme Builder
    foreach ( Utilities::get_theme_builder_modules() as $title => $data ) {
        $slug = $data[0];
        register_setting( 'wpr-elements-settings', 'wpr-element-'. $slug, [ 'default' => 'on' ] );
    }

    $woo_modules = Utilities::get_woocommerce_builder_modules();
    $woo_modules_pro = (wpr_fs()->can_use_premium_code() && defined( 'WPR_ADDONS_PRO_VERSION' )) ? Pro_Modules::get_woocommerce_builder_modules() : [];

    // WooCommerce Builder
    foreach ( array_merge($woo_modules, $woo_modules_pro) as $title => $data ) {
        $slug = is_array($data) ? $data[0] : $data;
        // var_dump('wpr-element-'. $slug);
        register_setting( 'wpr-elements-settings', 'wpr-element-'. $slug, [ 'default' => 'on' ] );
    }
    
    // Image Metaboxes
    $post_types = Utilities::get_custom_types_of( 'post', false );
    foreach ( $post_types as $key => $value ) {
        if ( 'page' !== $key && 'e-landing-page' !== $key ) {
            register_setting( 'wpr-settings', 'wpr_meta_secondary_image_'. $key );
        }

        if ( 'post' !== $key && 'product' !== $key && 'page' !== $key && 'e-landing-page' !== $key ) {
            register_setting( 'wpr-settings', 'wpr_cpt_ppp_'. $key );
        }
    }

}

function wpr_addons_settings_page() {
    
?>

<div class="wrap wpr-settings-page-wrap">

<div class="wpr-settings-page-header">
    <h1><?php echo esc_html(Utilities::get_plugin_name(true)); ?></h1>
    <p><?php esc_html_e( 'The most powerful Elementor Addon in the universe.', 'wpr-addons' ); ?></p>

    <?php if ( empty(get_option('wpr_wl_plugin_links')) ) : ?>
    <div class="wpr-preview-buttons">
        <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-plugin-prev-btn" target="_blank" class="button wpr-options-button">
            <span><?php echo esc_html__( 'View Plugin Demo', 'wpr-addons' ); ?></span>
            <span class="dashicons dashicons-external"></span>
        </a>

        <a href="https://www.youtube.com/watch?v=rkYQfn3tUc0" class="wpr-options-button button" target="_blank">
            <?php echo esc_html__( 'How to use Widgets', 'wpr-addons' ); ?>
            <span class="dashicons dashicons-video-alt3"></span>
        </a>

        <!-- <a href="https://royaladdons.frill.co/b/6m4d5qm4/feature-ideas" class="wpr-options-button button" target="_blank">
            <?php echo esc_html__( 'Request New Feature', 'wpr-addons' ); ?>
            <span class="dashicons dashicons-star-empty"></span>
        </a> -->
    </div>
    <?php endif; ?>
</div>

<div class="wpr-settings-page">
<form method="post" action="options.php">
    <?php

    // Active Tab
    if ( empty(get_option('wpr_wl_hide_elements_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'wpr_tab_elements';
    } elseif ( empty(get_option('wpr_wl_hide_extensions_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'wpr_tab_extensions';
    } elseif ( empty(get_option('wpr_wl_hide_settings_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'wpr_tab_settings';
    } elseif ( empty(get_option('wpr_wl_hide_free_pro_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'wpr_tab_free_pro';
    } elseif ( empty(get_option('wpr_wl_hide_white_label_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'wpr_tab_white_label';
    } else {
        $active_tab = $_GET['tab'];
    }
    

    // Render Create Templte Popup
    WPR_Templates_Loop::render_create_template_popup();
    
    ?>

    <!-- Tabs -->
    <div class="nav-tab-wrapper wpr-nav-tab-wrapper">
        <?php if ( empty(get_option('wpr_wl_hide_elements_tab')) ) : ?>
        <a href="?page=wpr-addons&tab=wpr_tab_elements" data-title="Elements" class="nav-tab <?php echo ($active_tab == 'wpr_tab_elements') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Widgets', 'wpr-addons' ); ?>
        </a>
        <?php endif; ?>

        <?php if ( empty(get_option('wpr_wl_hide_extensions_tab')) ) : ?>
        <a href="?page=wpr-addons&tab=wpr_tab_extensions" data-title="Extensions" class="nav-tab <?php echo ($active_tab == 'wpr_tab_extensions') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Extensions', 'wpr-addons' ); ?>
        </a>
        <?php endif; ?>
        
        <?php if ( empty(get_option('wpr_wl_hide_settings_tab')) ) : ?>
        <a href="?page=wpr-addons&tab=wpr_tab_settings" data-title="Settings" class="nav-tab <?php echo ($active_tab == 'wpr_tab_settings') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Settings', 'wpr-addons' ); ?>
        </a>
        <?php endif; ?>
        
        <?php if ( empty(get_option('wpr_wl_hide_free_pro_tab')) && !wpr_fs()->is_plan( 'expert' ) ) : ?>
        <a href="?page=wpr-addons&tab=wpr_tab_free_pro" data-title="Settings" class="nav-tab <?php echo ($active_tab == 'wpr_tab_free_pro') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Free vs Pro', 'wpr-addons' ); ?>
        </a>
        <?php endif; ?>

        <?php // White Label
            if ( wpr_fs()->is_plan( 'expert' ) ) {
                echo !empty(get_option('wpr_wl_hide_white_label_tab')) ? '<div style="display: none;">' : '<div>';
                        do_action('wpr_white_label_tab');
                echo '</div>';
            }
        ?>
    </div>

    <?php if ( $active_tab == 'wpr_tab_elements' ) : ?>

    <?php

    // Settings
    settings_fields( 'wpr-elements-settings' );
    do_settings_sections( 'wpr-elements-settings' );

    ?>

    <div class="wpr-elements-header">
        <div class="wpr-widgets-search">
            <input type="text" autocomplete="off" placeholder="<?php esc_html_e('Search Widgets...', 'wpr-addons'); ?>">
            <span class="dashicons dashicons-search"></span>
        </div>

        <div class="wpr-elements-toggle">
            <div>
                <h3><?php esc_html_e( 'Toggle all Widgets', 'wpr-addons' ); ?></h3>
                <input type="checkbox" name="wpr-element-toggle-all" id="wpr-element-toggle-all" <?php checked( get_option('wpr-element-toggle-all', 'on'), 'on', true ); ?>>
                <label for="wpr-element-toggle-all"></label>
            </div>
            <p><?php esc_html_e( 'You can disable some widgets for faster page speed.', 'wpr-addons' ); ?></p>
        </div>

        <div class="wpr-elements-filters">
            <ul>
                <li data-filter="all" class="wpr-active-filter"><?php esc_html_e( 'All Widgets', 'wpr-addons' ); ?></li>
                <li data-filter="theme"><?php esc_html_e( 'Theme Builder', 'wpr-addons' ); ?></li>
                <li data-filter="woo"><?php esc_html_e( 'WooCommerce', 'wpr-addons' ); ?></li>
            </ul>
        </div>
    </div>

    <div class="wpr-elements wpr-elements-general">
    <?php
        $modules = Utilities::get_registered_modules();
        $premium_modules = [
			'Breadcrumbs' => ['breadcrumbs-pro', 'https://royal-elementor-addons.com/?ref=rea-plugin-backend-elements-breadcrumbs-widgets-upgrade-pro#purchasepro', '', 'pro'],
        ];

        foreach ( array_merge($modules, $premium_modules) as $title => $data ) {
            $slug = $data[0];
            $url  = $data[1];
            $reff = '?ref=rea-plugin-backend-elements-widget-prev'. $data[2];
            $class = 'new' === $data[3] ? ' wpr-new-element' : '';
            $default_value = 'on';
            $link_text = esc_html__('View Widget Demo', 'wpr-addons');

            if ( 'pro' === $data[3] && !wpr_fs()->can_use_premium_code() ) {
                $class = 'wpr-pro-element';
            } elseif ( 'expert' === $data[3] && !wpr_fs()->is_plan( 'expert' ) ) {
                $class = 'wpr-expert-element';
            }

            if ( 'wpr-pro-element' === $class || 'wpr-expert-element' === $class ) {
                $default_value = 'off';
                $link_text = '';
                $reff = '';
            }

            if ( 'breadcrumbs-pro' == $data[0] && wpr_fs()->can_use_premium_code() ) {
                $url = '';
            }

            echo '<div class="wpr-element '. esc_attr($class) .'">';
                echo '<div class="wpr-element-info">';
                    echo '<h3>'. esc_html($title) .'</h3>';
                    echo '<input type="checkbox" name="wpr-element-'. esc_attr($slug) .'" id="wpr-element-'. esc_attr($slug) .'" '. checked( get_option('wpr-element-'. $slug, $default_value), 'on', false ) .'>';
                    echo '<label for="wpr-element-'. esc_attr($slug) .'"></label>';
                    echo ( '' !== $url && empty(get_option('wpr_wl_plugin_links')) ) ? '<a href="'. esc_url($url . $reff) .'" target="_blank">'. $link_text .'</a>' : '';
                echo '</div>';
            echo '</div>';
        }
    ?>
    </div>

    <div class="wpr-elements-heading">
        <h3><?php esc_html_e( 'Theme Builder Widgets', 'wpr-addons' ); ?></h3>
        <p><?php esc_html_e( 'Post (CPT) Archive Pages, Post (CPT) Single Pages', 'wpr-addons' ); ?></p>
        <a href="https://youtu.be/cwkhwO_rPuo?t=743" target="_blank"><?php esc_html_e( 'How to use Theme Builder Widgets', 'wpr-addons' ); ?></a>
    </div>
    <div class="wpr-elements wpr-elements-theme">
    <?php
        foreach ( Utilities::get_theme_builder_modules() as $title => $data ) {
            $slug = $data[0];
            $url  = $data[1];
            $reff = '?ref=rea-plugin-backend-elements-widget-prev'. $data[2];
            $class = 'new' === $data[3] ? ' wpr-new-element' : '';

            echo '<div class="wpr-element'. esc_attr($class) .'">';
                echo '<div class="wpr-element-info">';
                    echo '<h3>'. esc_html($title) .'</h3>';
                    echo '<input type="checkbox" name="wpr-element-'. esc_attr($slug) .'" id="wpr-element-'. esc_attr($slug) .'" '. checked( get_option('wpr-element-'. $slug, 'on'), 'on', false ) .'>';
                    echo '<label for="wpr-element-'. esc_attr($slug) .'"></label>';
                    echo ( '' !== $url && empty(get_option('wpr_wl_plugin_links')) ) ? '<a href="'. esc_url($url . $reff) .'" target="_blank">'. esc_html__('View Widget Demo', 'wpr-addons') .'</a>' : '';
                echo '</div>';
            echo '</div>';
        }
    ?>
    </div>

    <div class="wpr-elements-heading">
        <h3><?php esc_html_e( 'WooCommerce Builder Widgets', 'wpr-addons' ); ?></h3>
        <p><?php esc_html_e( 'Product Archive Pages, Product Single Pages. Cart, Checkout and My Account Pages', 'wpr-addons' ); ?></p>
        <?php if (!class_exists('WooCommerce')) : ?>
            <p class='wpr-install-activate-woocommerce'><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e( 'Install/Activate WooCommerce to use these widgets', 'wpr-addons' ); ?></p>
        <?php endif; ?>
        <a href="https://youtu.be/f_3tNiBC3dw?t=238" target="_blank"><?php esc_html_e( 'How to use WooCommerce Builder Widgets', 'wpr-addons' ); ?></a>
        <br><br>
        <a href="https://www.youtube.com/watch?v=wis1rQTn1tg" target="_blank"><?php esc_html_e( 'How to use Wishlist & Compare', 'wpr-addons' ); ?></a>
    </div>
    <div class="wpr-elements wpr-elements-woo">
    <?php
        $woocommerce_builder_modules = Utilities::get_woocommerce_builder_modules();
        $premium_woo_modules = [
			'Product Filters' => ['product-filters-pro', 'https://royal-elementor-addons.com/?ref=rea-plugin-backend-elements-woo-prodfilter-widgets-upgrade-pro#purchasepro', '', 'pro'],
			'Product Breadcrumbs' => ['product-breadcrumbs-pro', 'https://royal-elementor-addons.com/?ref=rea-plugin-backend-elements-woo-breadcru-widgets-upgrade-pro#purchasepro', '', 'pro'],
			'Page My Account' => ['page-my-account-pro', 'https://royal-elementor-addons.com/?ref=rea-plugin-backend-elements-woo-myacc-widgets-upgrade-pro#purchasepro', '', 'pro'],
			'Woo Category Grid' => ['woo-category-grid-pro', 'https://royal-elementor-addons.com/?ref=rea-plugin-backend-elements-woo-catgrid-widgets-upgrade-pro#purchasepro', '', 'pro'],
			'Wishlist Button' => ['wishlist-button-pro', 'https://royal-elementor-addons.com/#purchasepro?ref=rea-plugin-backend-elements-woo-wishlist-btn-widgets-upgrade-expert#purchasepro', '', 'expert'],
			'Mini Wishlist' => ['mini-wishlist-pro', 'https://royal-elementor-addons.com/#purchasepro?ref=rea-plugin-backend-elements-woo-wishlist-mini-widgets-upgrade-expert#purchasepro', '', 'expert'],
			'Wishlist Table' => ['wishlist-pro', 'https://royal-elementor-addons.com/#purchasepro?ref=rea-plugin-backend-elements-woo-wishlist-widgets-upgrade-expert#purchasepro', '', 'expert'],
			'Compare Button' => ['compare-button-pro', 'https://royal-elementor-addons.com/#purchasepro?ref=rea-plugin-backend-elements-woo-compare-btn-widgets-upgrade-expert#purchasepro', '', 'expert'],
			'Mini Compare' => ['mini-compare-pro', 'https://royal-elementor-addons.com/#purchasepro?ref=rea-plugin-backend-elements-woo-compare-mini-widgets-upgrade-expert#purchasepro', '', 'expert'],
			'Compare Table' => ['compare-pro', 'https://royal-elementor-addons.com/#purchasepro?ref=rea-plugin-backend-elements-woo-compare-widgets-upgrade-expert#purchasepro', '', 'expert'],
        ];

        foreach ( array_merge($woocommerce_builder_modules, $premium_woo_modules) as $title => $data ) {
            $slug = $data[0];
            $url  = $data[1];
            $reff = '?ref=rea-plugin-backend-elements-widget-prev'. $data[1];
            $class = 'new' === $data[3] ? 'wpr-new-element' : '';
            $class = ('pro' === $data[3] && !wpr_fs()->can_use_premium_code()) ? 'wpr-pro-element' : '';
            $default_value = class_exists( 'WooCommerce' ) ? 'on' : 'off';

            if ( 'pro' === $data[3] && !wpr_fs()->can_use_premium_code() ) {
                $class = 'wpr-pro-element';
            } elseif ( 'expert' === $data[3] && !wpr_fs()->is_plan( 'expert' ) ) {
                $class = 'wpr-expert-element';
            }

            if ( 'wpr-pro-element' === $class || 'wpr-expert-element' === $class ) {
                $default_value = 'off';
                $reff = '';
            }

            echo '<div class="wpr-element '. esc_attr($class) .'">';
                echo '<a href="'. esc_url($url . $reff) .'" target="_blank"></a>';
                echo '<div class="wpr-element-info">';
                    echo '<h3>'. esc_html($title) .'</h3>';
                    echo '<input type="checkbox" name="wpr-element-'. esc_attr($slug) .'" id="wpr-element-'. esc_attr($slug) .'" '. checked( get_option('wpr-element-'. $slug, $default_value), 'on', false ) .'>';
                    echo '<label for="wpr-element-'. esc_attr($slug) .'"></label>';
                    // echo ( '' !== $url && empty(get_option('wpr_wl_plugin_links')) ) ? '<a href="'. esc_url($url . $reff) .'" target="_blank">'. esc_html__('View Widget Demo', 'wpr-addons') .'</a>' : '';
                echo '</div>';
            echo '</div>';
        }
    ?>
    </div>

    <div class="wpr-widgets-not-found">
        <img src="<?php echo esc_url(WPR_ADDONS_ASSETS_URL .'img/not-found.png'); ?>">
        <h1><?php esc_html_e('No Search Results Found.', 'wpr-addons'); ?></h1>
        <p><?php esc_html_e('Cant find a Widget you are looking for?', 'wpr-addons'); ?></p>
        <a href="https://royaladdons.frill.co/b/6m4d5qm4/feature-ideas" target="_blank"><?php esc_html_e('Request a New Widget', 'wpr-addons'); ?></a>
    </div>

    <?php //submit_button( '', 'wpr-options-button' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_settings' ) : ?>

        <?php

        // Settings
        settings_fields( 'wpr-settings' );
        do_settings_sections( 'wpr-settings' );

        ?>

        <div class="wpr-settings">

        <?php submit_button( '', 'wpr-options-button' ); ?>

        <div class="wpr-settings-group wpr-settings-navigation">
            <a href="#optimizers-tab">Optimizers</a> / 
            <a href="#woocommerce-tab">WooCommerce</a> / 
            <?php if ( wpr_fs()->is_plan( 'expert' ) ) : ?>
                <a href="#cpt-tab">Custom Post Types</a> / 
            <?php endif; ?>
            <a href="#metabox-tab">Metabox</a> /  
            <a href="#integrations-tab">Integrations</a> /  
            <a href="#lightbox-tab">Lightbox</a>
        </div>

        <div class="wpr-settings-group wpr-settings-group-optimizers">
            <h3 id="optimizers-tab" class="wpr-settings-group-title"><?php esc_html_e( 'Optimizers', 'wpr-addons' ); ?></h3>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4><?php echo 'WP Rocket JS'; ?></h4>
                </div>
                <input type="checkbox" name="wpr_ignore_wp_rocket_js" id="wpr_ignore_wp_rocket_js" <?php echo checked( get_option('wpr_ignore_wp_rocket_js', 'on'), 'on', false ); ?>>
                <label for="wpr_ignore_wp_rocket_js"></label>
            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4><?php echo 'WP Optimize JS'; ?></h4>
                </div>
                <input type="checkbox" name="wpr_ignore_wp_optimize_js" id="wpr_ignore_wp_optimize_js" <?php echo checked( get_option('wpr_ignore_wp_optimize_js', 'on'), 'on', false ); ?>>
                <label for="wpr_ignore_wp_optimize_js"></label>
            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4><?php echo 'WP Optimize CSS'; ?></h4>
                </div>
                <input type="checkbox" name="wpr_ignore_wp_optimize_css" id="wpr_ignore_wp_optimize_css" <?php echo checked( get_option('wpr_ignore_wp_optimize_css', 'on'), 'on', false ); ?>>
                <label for="wpr_ignore_wp_optimize_css"></label>
            </div>

            <p class="wpr-settings-group-description"><?php esc_html_e( 'Ignores our Scripts in Respective Optimizers.', 'wpr-addons' ); ?></p>
        </div>

        <div class="wpr-settings-group wpr-settings-group-woo">
            <h3 id="woocommerce-tab" class="wpr-settings-group-title"><?php esc_html_e( 'WooCommerce', 'wpr-addons' ); ?></h3>
            
            <div class="wpr-settings-group-inner">

            <?php if ( !wpr_fs()->can_use_premium_code() ) : ?>
                <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-settings-woo-pro#purchasepro" class="wpr-settings-pro-overlay" target="_blank">
                    <span class="dashicons dashicons-lock"></span>
                    <span class="dashicons dashicons-unlock"></span>
                    <span><?php esc_html_e( 'Upgrade to Pro', 'wpr-addons' ); ?></span>
                </a>
                <div class="wpr-setting">
                    <h4>
                        <span><?php esc_html_e( 'Shop Page: Products Per Page', 'wpr-addons' ); ?></span>
                        <br>
                    </h4>
                    <input type="text" value="9">
                </div>
                <div class="wpr-setting">
                    <h4>
                        <span><?php esc_html_e( 'Product Category: Products Per Page', 'wpr-addons' ); ?></span>
                        <br>
                    </h4>
                    <input type="text" value="9">
                </div>
                <div class="wpr-setting">
                    <h4>
                        <span><?php esc_html_e( 'Product Tag: Products Per Page', 'wpr-addons' ); ?></span>
                        <br>
                    </h4>
                    <input type="text" value="9">
                </div>
            <?php else: ?>
                <?php do_action('wpr_woocommerce_settings'); ?>
            <?php endif; ?>

            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Royal Woocommerce Config</h4>
                    <span>Below options work only if this option is enabled</span>
                </div>
                <input type="checkbox" name="wpr_override_woo_templates" id="wpr_override_woo_templates" <?php echo checked( get_option('wpr_override_woo_templates', 'on'), 'on', false ); ?>>
                <label for="wpr_override_woo_templates"></label>
            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Cart</h4>
                    <span>Overrides Default Cart Template</span>
                </div>
                <input type="checkbox" name="wpr_override_woo_cart" id="wpr_override_woo_cart" <?php echo checked( get_option('wpr_override_woo_cart', 'on'), 'on', false ); ?>>
                <label for="wpr_override_woo_cart"></label>
            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Mini Cart</h4>
                    <span>Overrides Default Mini Cart Template</span>
                </div>
                <input type="checkbox" name="wpr_override_woo_mini_cart" id="wpr_override_woo_mini_cart" <?php echo checked( get_option('wpr_override_woo_mini_cart', 'on'), 'on', false ); ?>>
                <label for="wpr_override_woo_mini_cart"></label>
            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Notices</h4>
                    <span>Overrides Default Notice Templates</span>
                </div>
                <input type="checkbox" name="wpr_override_woo_notices" id="wpr_override_woo_notices" <?php echo checked( get_option('wpr_override_woo_notices', 'on'), 'on', false ); ?>>
                <label for="wpr_override_woo_notices"></label>
            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Lightbox</h4>
                    <span>Disables Default Lightbox to avoid compatibility issues while using royal product media</span>
                </div>
                <input type="checkbox" name="wpr_remove_wc_default_lightbox" id="wpr_remove_wc_default_lightbox" <?php echo checked( get_option('wpr_remove_wc_default_lightbox', 'on'), 'on', false ); ?>>
                <label for="wpr_remove_wc_default_lightbox"></label>
            </div>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Product Image Zoom</h4>
                    <span>Enable/Disable Image Zoom Effect on Woocommerce products</span>
                </div>
                <input type="checkbox" name="wpr_enable_product_image_zoom" id="wpr_enable_product_image_zoom" <?php echo checked( get_option('wpr_enable_product_image_zoom', 'on'), 'on', false ); ?>>
                <label for="wpr_enable_product_image_zoom"></label>
            </div>

            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Product Slider Nav</h4>
                    <span>Enable/Disable Navigation Arrows on Woocommerce products slider</span>
                </div>
                <input type="checkbox" name="wpr_enable_woo_flexslider_navigation" id="wpr_enable_woo_flexslider_navigation" <?php echo checked( get_option('wpr_enable_woo_flexslider_navigation', 'on'), 'on', false ); ?>>
                <label for="wpr_enable_woo_flexslider_navigation"></label>
            </div>

            <?php if ( wpr_fs()->is_plan( 'expert' ) ) : ?>
            
            <div class="wpr-woo-template-info">
                <div class="wpr-woo-template-title">
                    <h4>Add Wishlist To My Account</h4>
                    <span>Adds wishlist menu item to my account widget</span>
                </div>
                <input type="checkbox" name="wpr_add_wishlist_to_my_account" id="wpr_add_wishlist_to_my_account" <?php echo checked( get_option('wpr_add_wishlist_to_my_account', 'on'), 'on', false ); ?>>
                <label for="wpr_add_wishlist_to_my_account"></label>
            </div>

            <div class="wpr-woo-template-info wpr-compare-wishlist">
                <?php
                    $pages = get_pages(); // Get all pages on the site
                    $current_page = get_option( 'wpr_compare_page' ); // Get the current selected page
                    echo '<label for="wpr_compare_page">Select Compare Page</label>';
                    echo '<select name="wpr_compare_page" id="wpr_compare_page" >';
                    
                    foreach ( $pages as $page ) {
                        $selected = ( $current_page == $page->ID ) ? 'selected="selected"' : '';
                        echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
                    }
                    
                    echo '</select>';
                ?>
            </div>

            <div class="wpr-woo-template-info wpr-compare-wishlist">
                <?php
                    $pages = get_pages(); // Get all pages on the site
                    $current_page = get_option( 'wpr_wishlist_page' ); // Get the current selected page
                    echo '<label for="wpr_wishlist_page">Select Wishlist Page</label>';
                    echo '<select name="wpr_wishlist_page" id="wpr_wishlist_page" >';
                    
                    foreach ( $pages as $page ) {
                        $selected = ( $current_page == $page->ID ) ? 'selected="selected"' : '';
                        echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
                    }
                    
                    echo '</select>';
                ?>
            </div>
            
            <h4>
                <a href="https://youtu.be/wis1rQTn1tg?t=97" target="_blank"><?php esc_html_e( 'How to use Wishlist & Compare pages', 'wpr-addons' ); ?></a>
            </h4>
            <?php endif; ?>
            
        </div>
        

        <?php if ( wpr_fs()->is_plan( 'expert' ) ) : ?>
        <div class="wpr-settings-group wpr-settings-group-cpt">
            <h3 id="cpt-tab" class="wpr-settings-group-title"><?php esc_html_e( 'Custom Post Types', 'wpr-addons' ); ?></h3>

            <?php
                $post_types = Utilities::get_custom_types_of( 'post', false );

                $custom_post_types_exist = false;
                
                foreach ( $post_types as $key => $post_type ) {
                    if ( ! in_array( $key, ['post', 'page', 'attachment', 'product', 'e-landing-page', 'revision', 'nav_menu_item'] ) ) {
                        // This is a custom post type.
                        $custom_post_types_exist = true;
                        break;
                    }
                }
                   
                if ( $custom_post_types_exist ) {
                    foreach ( $post_types as $key => $value ) {
                        if ( 'post' == $key || 'product' == $key || 'page' == $key || 'e-landing-page' === $key ) {
                            continue;
                        }
    
                        ?>  
                            <div class="wpr-setting">
                                <h4>
                                    <span><?php esc_html_e( $value . ' : Posts Per Page', 'wpr-addons' ); ?></span>
                                    <br>
                                </h4>
    
                                <input type="text" name="wpr_cpt_ppp_<?php echo $key ?>" id="wpr_cpt_ppp_<?php echo $key ?>" value="<?php echo esc_attr(get_option('wpr_cpt_ppp_'. $key, 10)); ?>">
                            </div>
                        <?php
                    }
                } else {
                    echo '<p>No custom post types found.</p>';
                }

                
                // do_action('wpr_cpt_settings');
            ?>
            
            <!-- <div class="wpr-settings-group-inner"> -->

            <!-- </div> -->
        </div>
        <?php endif; ?>
            

        <?php if ( wpr_fs()->can_use_premium_code() ) : ?>
        <div class="wpr-settings-group wpr-settings-group-meta">
            <h3 id="metabox-tab" class="wpr-settings-group-title"><?php esc_html_e( 'Metabox', 'wpr-addons' ); ?></h3>

            <?php
                $post_types = Utilities::get_custom_types_of( 'post', false );
                foreach ( $post_types as $key => $value ) {
                    if ( 'page' == $key || 'e-landing-page' === $key ) {
                        continue;
                    }

                    ?>
                        <div class="wpr-woo-template-info">
                            <div class="wpr-woo-template-title">
                                <h4><?php echo $value; ?></h4>
                            </div>
                            <input type="checkbox" name="wpr_meta_secondary_image_<?php echo $key ?>" id="wpr_meta_secondary_image_<?php echo $key ?>" <?php echo checked( get_option('wpr_meta_secondary_image_'. $key, 'on'), 'on', false ); ?>>
                            <label for="wpr_meta_secondary_image_<?php echo $key ?>"></label>
                        </div>
                    <?php
                }
            ?>
            <p class="wpr-settings-group-description"><?php esc_html_e( 'Add secondary Featured image metabox to post types.', 'wpr-addons' ); ?></p>
            
            <!-- <div class="wpr-settings-group-inner"> -->

            <!-- </div> -->
        </div>
        <?php endif; ?>

        <div class="wpr-settings-group">
            <h3 id="integrations-tab" class="wpr-settings-group-title"><?php esc_html_e( 'Integrations', 'wpr-addons' ); ?></h3>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'Google Map API Key', 'wpr-addons' ); ?></span>
                    <br>
                    <a href="https://www.youtube.com/watch?v=O5cUoVpVUjU" target="_blank"><?php esc_html_e( 'How to get Google Map API Key?', 'wpr-addons' ); ?></a>
                </h4>

                <input type="text" name="wpr_google_map_api_key" id="wpr_google_map_api_key" value="<?php echo esc_attr(get_option('wpr_google_map_api_key')); ?>">
            </div>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'MailChimp API Key', 'wpr-addons' ); ?></span>
                    <br>
                    <a href="https://mailchimp.com/help/about-api-keys/" target="_blank"><?php esc_html_e( 'How to get MailChimp API Key?', 'wpr-addons' ); ?></a>
                </h4>

                <input type="text" name="wpr_mailchimp_api_key" id="wpr_mailchimp_api_key" value="<?php echo esc_attr(get_option('wpr_mailchimp_api_key')); ?>">
            </div>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'reCAPTCHA Site Key', 'wpr-addons' ); ?></span>
                    <br>
                    <a href="https://www.google.com/recaptcha/intro/v3.html" target="_blank"><?php esc_html_e( 'How to get reCAPTCHA Site Key?', 'wpr-addons' ); ?></a>
                </h4>

                <input type="text" name="wpr_recaptcha_v3_site_key" id="wpr_recaptcha_v3_site_key" value="<?php echo esc_attr(get_option('wpr_recaptcha_v3_site_key')); ?>">
            </div>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'reCAPTCHA Secret Key', 'wpr-addons' ); ?></span>
                    <br>
                </h4>

                <input type="text" name="wpr_recaptcha_v3_secret_key" id="wpr_recaptcha_v3_secret_key" value="<?php echo esc_attr(get_option('wpr_recaptcha_v3_secret_key')); ?>">
            </div>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'Score Threshold', 'wpr-addons' ); ?></span>
                    <br>
                </h4>

                <input type="number" name="wpr_recaptcha_v3_score" id="wpr_recaptcha_v3_score" placeholder="0.5" step="0.1" min="0" max="1" value="<?php echo esc_attr(get_option('wpr_recaptcha_v3_score')); ?>">
            </div>
            
        </div>

        <div class="wpr-settings-group">
            <h3 id="lightbox-tab" class="wpr-settings-group-title"><?php esc_html_e( 'Lightbox', 'wpr-addons' ); ?></h3>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Background Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_bg_color" id="wpr_lb_bg_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_bg_color','rgba(0,0,0,0.6)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Toolbar BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_toolbar_color" id="wpr_lb_toolbar_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_toolbar_color','rgba(0,0,0,0.8)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Caption BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_caption_color" id="wpr_lb_caption_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_caption_color','rgba(0,0,0,0.8)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Gallery BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_gallery_color" id="wpr_lb_gallery_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_gallery_color','#444444')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Progress Bar Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_pb_color" id="wpr_lb_pb_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_pb_color','#a90707')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_ui_color" id="wpr_lb_ui_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_ui_color','#efefef')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Hover Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_ui_hr_color" id="wpr_lb_ui_hr_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_ui_hr_color','#ffffff')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Text Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_text_color" id="wpr_lb_text_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_text_color','#efefef')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Icon Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_icon_size" id="wpr_lb_icon_size" value="<?php echo esc_attr(get_option('wpr_lb_icon_size','20')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Navigation Arrow Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_arrow_size" id="wpr_lb_arrow_size" value="<?php echo esc_attr(get_option('wpr_lb_arrow_size','35')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Text Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_text_size" id="wpr_lb_text_size" value="<?php echo esc_attr(get_option('wpr_lb_text_size','14')); ?>">
            </div>
        </div>

        <?php submit_button( '', 'wpr-options-button' ); ?>

        </div>

    
    <?php elseif ( $active_tab == 'wpr_tab_free_pro' && !wpr_fs()->is_plan( 'expert' ) ) : ?>

        <div class="wpr-free-vs-pro-wrap">

        <div class="wpr-free-vs-pro-search-wrap">
            <div class="wpr-free-pro-search">
                <input type="text" autocomplete="off" placeholder="<?php esc_html_e('Search for Features...', 'wpr-addons'); ?>">
                <span class="dashicons dashicons-search"></span>
            </div>

            <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-freevsprotab-pro#purchasepro" target="_blank" class="button wpr-free-pro-upgrade">
                <span><?php echo esc_html__( 'Get Premium', 'wpr-addons' ); ?></span>
                <span class="dashicons dashicons-smiley"></span>
            </a>
        </div>

        <div class="wpr-free-vs-pro">
            <div class="wpr-free-widgets">
                <header>
                    <span class="dashicons dashicons-layout"></span>
                    <h3><?php echo esc_html__( 'Free Version', 'wpr-addons' ); ?></h3>
                    <span><?php echo esc_html__( 'Basic Functionality', 'wpr-addons' ); ?></span>
                </header>
                <ul>
                    <li><span>Basic Support</span></li>
                    <li><span>Mega Menu</span></li>
                    <li><span>Post Grid/Slider/Carousel Widget</span></li>
                    <li><span>Woocommerce Grid/Slider/Carousel Widget</span></li>
                    <li><span>Image Grid/Slider/Carousel Widget</span></li>
                    <li><span>Magazine Grid/Slider Widget</span></li>
                    <li><span>Basic Timeline Widget</span></li>
                    <li><span>Basic Slider Widget</span></li>
                    <li><span>Basic Form Builder Widget</span></li>
                    <li><span>Offcanvas Content</span></li>
                    <li><span>Instagram Feed</span></li>
                    <li><span>Twitter Feed</span></li>
                    <li><span>Basic Data Table</span></li>
                    <li><span>Testimonial Slider Widget</span></li>
                    <li><span>Nav Menu Widget</span></li>
                    <li><span>Advanced Accordion</span></li>
                    <li><span>Image Accordion</span></li>
                    <li><span>Charts</span></li>
                    <li><span>One Page Navigation / Scroll Widget</span></li>
                    <li><span>Tabs Widget</span></li>
                    <li><span>Promo Box Widget</span></li>
                    <li><span>Flip Box Widget</span></li>
                    <li><span>Flip Carousel</span></li>
                    <li><span>Before After Slider Widget</span></li>
                    <li><span>Content Ticker Widget</span></li>
                    <li><span>MailChimp Widget</span></li>
                    <li><span>Image Hotspot Widget</span></li>
                    <li><span>Team Member Widget</span></li>
                    <li><span>Button Widget</span></li>
                    <li><span>Dual Button Widget</span></li>
                    <li><span>Price List Widget</span></li>
                    <li><span>Business Hours Widget</span></li>
                    <li><span>Sharing Buttons Widget</span></li>
                    <li><span>Progress Bar Widget</span></li>
                    <li><span>Countdown Timer Widget</span></li>
                    <li><span>Content Toggle Widget</span></li>
                    <li><span>Pricing Table Widget</span></li>
                    <li><span>Advanced Text Widget</span></li>
                    <li><span>Search Widget (Ajax)</span></li>
                    <li><span>Free Premade Widget Templates</span></li>
                    <li><span>Popup Builder</span></li>
                    <li><span>9 Premade Popup Templates</span></li>
                    <li><span>Particle Effects</span></li>
                    <li><span>Parallax Effect</span></li>
                    <li><span>Sticky Section</span></li>
                    <li><span>Free Templates Kit Library</span></li>
                    <li><span>Theme Builder</span></li>
                    <li><span>WooCommerce Shop Builder  </span></li>
                    <li><span>Elementor Pro Not Required</span></li>
                </ul>
            </div>

            <div class="wpr-pro-widgets">
                <header>
                    <span class="dashicons dashicons-star-filled"></span>
                    <h3><?php echo esc_html__( 'Pro Version', 'wpr-addons' ); ?></h3>
                    <span><?php echo esc_html__( 'Advanced Functionality', 'wpr-addons' ); ?></span>
                </header>
                <ul>
                    <li><span>Priority Support</span></li>
                    <li><span>Advanced Mega Menu</span>
                        <ul>
                            <li>Load SubMenu Items with Ajax</li>
                            <li>Add Icons and Badges to Menu Items</li>
                            <li>Set Submenu width to Automatically Fit to Section width</li>
                            <li>Display Mobile Menu sub items as Mega Item or WordPress Default Sub Item</li>
                            <li>Vertical Mega Menu Layout</li>
                            <li>Mobile Menu Off-Canvas Layout</li>
                            <li>Mobile Menu Display Conditions</li>
                            <li>SubMenu Width option</li>
                            <li>Advanced Link Hover Effects: Slide, Grow, Drop</li>
                            <li>SubMenu Entrance Advanced Effects</li>
                            <li>Mobile Menu Button Custom Text option</li>
                        </ul>
                    </li>
                    <li><span>Advanced Post Grid/Slider/Carousel Widget</span>
                        <ul>
                            <li>Grid Columns 1,2,3,4,5,6</li>
                            <li>Custom Post Types Support (Expert)</li>
                            <li>Secondary Featured Image on Hover</li>
                            <li>Masonry Layout</li>
                            <li>List Layout Zig-zag</li>
                            <li>Posts Slider Columns (Carousel) 1,2,3,4,5,6</li>
                            <li>Related Posts Query, Current Page Query, Random Posts Query</li>
                            <li>Infinite Scrolling Pagination</li>
                            <li>Post Slider Autoplay options</li>
                            <li>Post Slider Advanced Navigation Positioning</li>
                            <li>Post Slider Advanced Pagination Positioning</li>
                            <li>Advanced Post Likes</li>
                            <li>Advanced Post Sharing</li>
                            <li>Custom Fields Support</li>
                            <li>Advanced Grid Loading Animations (Fade in & Slide Up)</li>
                            <li>Unlimited Grid Elements Positioning</li>
                            <li>Unlimited Image Overlay Animations</li>
                            <li>Image overlay GIF upload option</li>
                            <li>Image Overlay Blend Mode</li>
                            <li>Image Effects: Zoom, Grayscale, Blur</li>
                            <li>Lightbox Thumbnail Gallery, Lightbox Image Sharing Button</li>
                            <li>Ability to Select Default Active Grid Filter</li>
                            <li>Grid Category Filter Deeplinking</li>
                            <li>Grid Category Filter Icons select</li>
                            <li>Grid Category Filter Count</li>
                            <li>Grid Item Even/Odd Background Color</li>
                            <li>Title, Category, Read More Advanced Link Hover Animations</li>
                            <li>Display Scheduled Posts</li>
                            <li>Lazy Loading</li>
                            <li>Open Links in New Tab</li>
                            <li>Posts Order</li>
                            <li>Trim Title & Excerpt By Letter Count</li>
                        </ul>
                    </li>
                    <li><span>Advanced Woo Grid/Slider/Carousel Widget</span>
                        <ul>
                            <li>Grid Columns 1,2,3,4,5,6</li>
                            <li>Masonry Layout</li>
                            <li>Products Slider Columns (Carousel) 1,2,3,4,5,6</li>
                            <li>Current Page Query, Random Products Query</li>
                            <li>Infinite Scrolling Pagination</li>
                            <li>Products Slider Autoplay options</li>
                            <li>Products Slider Advanced Navigation Positioning</li>
                            <li>Products Slider Advanced Pagination Positioning</li>
                            <li>Advanced Products Likes</li>
                            <li>Advanced Products Sharing</li>
                            <li>Advanced Grid Loading Animations (Fade in & Slide Up)</li>
                            <li>Unlimited Grid Elements Positioning</li>
                            <li>Unlimited Image Overlay Animations</li>
                            <li>Image overlay GIF upload option</li>
                            <li>Image Overlay Blend Mode</li>
                            <li>Image Effects: Zoom, Grayscale, Blur</li>
                            <li>Lightbox Thumbnail Gallery, Lightbox Image Sharing Button</li>
                            <li>Ability to Select Default Active Grid Filter</li>
                            <li>Grid Category Filter Deeplinking</li>
                            <li>Grid Category Filter Icons select</li>
                            <li>Grid Category Filter Count</li>
                            <li>Grid Item Even/Odd Background Color</li>
                            <li>Title, Category, Read More Advanced Link Hover Animation</li>
                            <li>Open Links in New Tab</li>
                        </ul>
                    </li>
                    <li><span>Advanced Image Grid/Slider/Carousel Widget</span>
                        <ul>
                            <li>Grid Columns 1,2,3,4,5,6,7,8</li>
                            <li>Masonry Layout</li>
                            <li>Random Images Query</li>
                            <li>Image Slider Columns (Carousel) 1,2,3,4,5,6,7,8</li>
                            <li>Infinite Scrolling Pagination</li>
                            <li>Image Slider Autoplay options</li>
                            <li>Image Slider Advanced Navigation Positioning</li>
                            <li>Image Slider Advanced Pagination Positioning</li>
                            <li>Advanced Image Likes</li>
                            <li>Advanced Image Sharing</li>
                            <li>Advanced Grid Loading Animations (Fade in & Slide Up)</li>
                            <li>Unlimited Grid Elements Positioning</li>
                            <li>Unlimited Image Overlay Animations</li>
                            <li>Image overlay GIF upload option</li>
                            <li>Image Overlay Blend Mode</li>
                            <li>Image Effects: Zoom, Grayscale, Blur</li>
                            <li>Lightbox Thumbnail Gallery, Lightbox Image Sharing Button</li>
                            <li>Ability to Select Default Active Grid Filter</li>
                            <li>Grid Category Filter Deeplinking</li>
                            <li>Grid Category Filter Icons select</li>
                            <li>Grid Category Filter Count</li>
                            <li>Grid Item Even/Odd Background Color</li>
                            <li>Title & Category Advanced Link Hover Animations</li>
                        </ul>
                    </li>
                    <li><span>Advanced Magazine Grid/Slider Widget</span>
                        <ul>
                            <li>Random Posts Query</li>
                            <li>Custom Post Types Support (Expert)</li>
                            <li>+6 Magazine Grid Layouts</li>
                            <li>Magazine Grid Slider</li>
                            <li>Magazine Grid Slider Autoplay options</li>
                            <li>Magazine Grid Slider Advanced Navigation Positioning</li>
                            <li>Magazine Grid Slider Advanced Pagination Positioning</li>
                            <li>Advanced Post Likes</li>
                            <li>Advanced Post Sharing</li>
                            <li>Custom Fields Support</li>
                            <li>Unlimited Grid Elements Positioning</li>
                            <li>Unlimited Image Overlay Animations</li>
                            <li>Image overlay GIF upload option</li>
                            <li>Title, Category, Read More Advanced Link Hover Animations</li>
                            <li>Open Links in New Tab</li>
                            <li>Posts Order</li>
                            <li>Trim Title & Excerpt By Letter Count</li>   
                        </ul>
                    </li>
                    <li><span>Advanced Timeline Widget</span>
                        <ul>
                            <li>Add Unlimited Custom Timeline Items</li>
                            <li>Custom Post Types Support (Expert)</li>
                            <li>Advanced Pagination - Load More Button or Infinite Scroll options</li>
                            <li>Unlimited Slides to Show option</li>
                            <li>Carousel Autoplay and Autoplay Speed</li>
                            <li>Unlimited Posts Per Page option</li>
                            <li>Advanced Entrance Animation Options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Slider Widget</span>
                        <ul>
                            <li>Add Unlimited Slides</li>
                            <li>Vertical Slider</li>
                            <li>Elementor Templates Slider option</li>
                            <li>Scroll to Section Button</li>
                            <li>Ken Burn Effect</li>
                            <li>Columns (Carousel) 1,2,3,4,5,6</li>
                            <li>Unlimited Slides to Scroll option</li>
                            <li>Autoplay options</li>
                            <li>Advanced Navigation Positioning</li>
                            <li>Advanced Pagination Positioning</li>
                        </ul>
                    </li>
                    <li><span>Advanced Form Builder Widget</span> 
                        <ul>
                            <li>Unlimited number of fields</li>
                            <li>Submission action</li>
                            <li>Mailchimp action</li>
                            <li>Webhook action</li>
                        </ul>
                    </li>
                    <li><span>Advanced Offcanvas Menu</span>
                        <ul>
                            <li>Advanced Positioning</li>
                            <li>Advanced Entrance Animations</li>
                            <li>Custom Width & Height</li>
                            <li>Open Offcanvas by Default</li>
                            <li>Trigger Button Icon Select</li>
                            <li>Close Icon Positioning</li>
                        </ul>
                    </li>
                    <li><span>Advanced Instagram Feed</span>
                        <ul>
                            <li>Unlimited Number of Posts</li>
                            <li>Unlimited Number of Slides</li>
                            <li>Trim Title & Caption By Letter Count</li>
                            <li>Advanced Grid Elements Positioning</li>
                            <li>Advanced Post Sharing</li>
                            <li>Unlimited Image Overlay Animations</li>
                            <li>Lightbox Thumbnail Gallery, Lightbox Image Sharing Button</li>
                        </ul>
                    </li>
                    <li><span>Advanced Twitter Feed</span>
                        <ul>
                            <li>Unlimited Number of Profiles</li>
                            <li>Unlimited Number of Posts</li>
                        </ul>
                    </li>
                    <li><span>Advanced Data Table Widget</span>
                        <ul>
                            <li>Import Table data from CSV file upload or URL</li>
                            <li>Show/Hide Export Table data buttons</li>
                            <li>Enable Live Search for Tables</li>
                            <li>Enable Table Pagination. Divide Table items by pages</li>
                            <li>Enable Table Sorting option</li>
                            <li>Enable Tooltips on each cell</li>
                        </ul>
                    </li>
                    <li><span>Advanced Testimonial Slider Widget</span>
                        <ul>
                            <li>Add Unlimited Testimonials</li>
                            <li>Columns (Carousel) 1,2,3,4,5,6</li>
                            <li>Advanced Social Media Icon options</li>
                            <li>Advanced Rating Styling options</li>
                            <li>Unlimited Slides to Scroll option</li>
                            <li>Autoplay options</li>
                            <li>Advanced Navigation Positioning</li>
                            <li>Advanced Pagination Positioning</li>
                        </ul>
                    </li>
                    <li><span>Advanced Nav Menu Widget</span>
                        <ul>
                            <li>Vertical Layout</li>
                            <li>Advanced Link Hover Effects: Slide, Grow, Drop</li>
                            <li>SubMenu Entrance Slide Effect</li>
                            <li>SubMenu Width option</li>
                            <li>Advanced Display Conditions</li>
                            <li>Mobile Menu Display Custom Conditions</li>
                            <li>Mobile Menu Button Custom Text option</li>
                        </ul>
                    </li>
                    <li><span>Advanced Accordion</span>
                        <ul>
                            <li>Load Elementor Template in Accordion Panels</li>
                            <li>Enable Accordion content Live Search</li>
                        </ul>
                    </li>
                    <li><span>Advanced Image Accordion</span>
                        <ul>
                            <li>Add Unlimited Images</li>
                            <li>Vertical Accordion Layout</li>
                            <li>Trigger Images on Click</li>
                            <li>Skew Images by default</li>
                            <li>Enable Image Lightbox</li>
                            <li>Advanced Elements Positioning</li>
                            <li>Image Effects: Zoom, Grayscale, Blur</li>
                            <li>Image Overlay Blend Mode</li>
                        </ul>
                    </li>
                    <li><span>Advanced Charts</span>
                        <ul>
                            <li>Add Unlimited Data Labels</li>
                            <li>Add Unlimited Chart Items</li>
                            <li>Import published Google Sheets</li>
                            <li>Import CSV File from URL</li>
                            <li>Upload CSV File</li>
                        </ul>
                    </li>
                    <li><span>Advanced One Page Navigation / Scroll Widget</span>
                        <ul>
                            <li>Highlight Active Nav Icon</li>
                            <li>Nav Icon Custom Color</li>
                            <li>Nav Icon Advanced Tooltip</li>
                            <li>Scrolling Animation Speed</li>
                            <li>Navigation Full-height (Sidebar) option</li>
                        </ul>
                    </li>
                    <li><span>Advanced Tabs Widget</span>
                        <ul>
                            <li>Add Unlimited Tabs</li>
                            <li>Tab Content Type - Elementor Template</li>
                            <li>Custom Tab Colors</li>
                            <li>Tab Label Align</li>
                            <li>Swich Tabs on Hover option</li>
                            <li>Set Active Tab by Default</li>
                            <li>Advanced Tab Content Animations</li>
                            <li>Tabs Autoplay option</li>
                        </ul>
                    </li>
                    <li><span>Advanced Promo Box Widget</span>
                        <ul>
                            <li>Classic Layout - Image & Content Side to Side with Image Width & Position options</li>
                            <li>Advanced Image Hover Animations</li>
                            <li>Advanced Content Hover Animations - Icon, Title, Description, Button separately</li>
                            <li>Advanced Badge (Ribon) options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Flip Box Widget</span>
                        <ul>
                            <li>Flip on Button Click</li>
                            <li>Advanced Flipping Animations</li>
                        </ul>
                    </li>
                    <li><span>Advanced Flip Carousel Widget</span>
                        <ul>
                            <li>Add Unlimited Slides</li>
                            <li>Slider Autoplay options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Before After Slider Widget</span>
                        <ul>
                            <li>Vertical Image Comparison</li>
                            <li>Move Images on Mouse Move (Hover)</li>
                            <li>Set Default Divider Position (What part of After Image to show)</li>
                            <li>Show Labels on Image Hover</li>
                        </ul>
                    </li>
                    <li><span>Advanced Content Ticker Widget</span>
                        <ul>
                            <li>Add Custom Ticker Items (Instead of loading Dynamically)</li>
                            <li>Custom Post Types Support (Expert)</li>
                            <li>Query Shop Products, On Sale products and Featured products (Instead of loading Dynamically)</li>
                            <li>Marquee Animation - a Smooth Animation with Direction option</li>
                            <li>Slider Animation options - Typing, Fade & Vertical Slide</li>
                            <li>Heading Icon Type - Animated Circle</li>
                        </ul>
                    </li>
                    <li><span>Advanced MailChimp Widget</span>
                        <ul>
                            <li>Add Extra Fields - Name & Last Name</li>
                        </ul>
                    </li>
                    <li><span>Advanced Image Hotspot Widget</span>
                        <ul>
                            <li>Add Unlimited Hotspots</li>
                            <li>Show Tooltips on Click or Hover</li>
                            <li>Advanced Tooltip Positioning</li>
                        </ul>
                    </li>
                    <li><span>Advanced Team Member Widget</span>
                        <ul>
                            <li>Advanced Layout options - Move Elements over Image (Title, Job, Social Icons, etc...)</li>
                            <li>Advanced Image Overlay Hover Animations</li>
                        </ul>
                    </li>
                    <li><span>Advanced Button Widget</span>
                        <ul>
                            <li>Advanced Button Styles</li>
                            <li>Advanced Hover Animations - Change Text on Hover</li>
                            <li>Advanced Tooltip options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Dual Button Widget</span>
                        <ul>
                            <li>Middle Badge Text & Icon options</li>
                            <li>Advanced Tooltip options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Price List Widget</span>
                        <ul>
                            <li>Add Images to Menu Items</li>
                            <li>Add Custom Links to Menu Items</li>
                            <li>Advanced Layout Options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Business Hours Widget</span>
                        <ul>
                            <li>List Item Custom Icon options</li>
                            <li>List Item Custom Text & Background Color options</li>
                            <li>List Item Even/Odd Background Color option</li>
                        </ul>
                    </li>
                    <li><span>Advanced Sharing Buttons Widget</span>
                        <ul>
                            <li>Custom Social Media Label</li>
                            <li>Custom Social Media Colors</li>
                            <li>Layout Columns 1,2,3,4,5,6</li>
                            <li>Show Hide Icon</li>
                            <li>Show Hide Label</li>
                            <li>Advanced Styling options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Progress Bar Widget</span>
                        <ul>
                            <li>Vertical Progress Bar</li>
                            <li>Stripe Animation option</li>
                            <li>Advanced Animation Timing options</li>
                        </ul>
                    </li>
                    <li><span>Advanced Countdown Timer Widget</span>
                        <ul>
                            <li>Evergreen Timer - User Specific Timer</li>
                            <li>An evergreen countdown timer is used to display the amount of time a particular user has to avail the offer. This is a great way to create a feeling of scarcity, urgency and exclusivity</li>
                        </ul>
                    </li>
                    <li><span>Advanced Content Toggle Widget</span>
                        <ul>
                            <li>Multi Label Switcher (ex: Monthly, Annually, Lifetime)</li>
                            <li>Switcher Label Inside Position</li>
                        </ul>
                    </li>
                    <li><span>Advanced Pricing Table Widget</span>
                        <ul>
                            <li>List Item Advanced Tooltip</li>
                            <li>List Item Even/Odd Background Color</li>
                        </ul>
                    </li>
                    <li><span>Advanced Text Widget</span>
                        <ul>
                            <li>Clipped Text Effect</li>
                            <li>Examples - Clipped effects</li>
                        </ul>
                    </li>
                    <li><span>Advanced Search Widget (Ajax)</span>
                        <ul>
                            <li>More than 2 Results in Ajax Search</li>
                            <li>Custom Search Query - Only Posts, Pages or Custom Post Types (Expert)</li>
                            <li>Ajax Search Results Pagination (Load More)</li>
                        </ul>
                    </li>
                    <li><span>60+ PRO Premade Widget Templates</span>
                        <ul>
                            <li>Post Grid Premade Templates</li>
                            <li>Post Carousel Premade Templates</li>
                            <li>Post Slider Premade templates</li>
                            <li>Product Grid Premade Templates</li>
                            <li>Product Carousel Premade Templates</li>
                            <li>Product Slider Premade templates</li>
                            <li>Image Grid Premade Templates</li>
                            <li>Image Carousel Premade Templates</li>
                            <li>Image Slider Premade templates</li>
                            <li>Magazine Grid Premade Templates</li>
                            <li>Advanced Slider Premade Templates</li>
                            <li>Testimonial Slider Premade Templates</li>
                            <li>Advanced Nav Menu Premade Templates</li>
                            <li>OnePage Navigation Premade Templates</li>
                            <li>Tabs Premade Templates</li>
                            <li>Promo Box Premade Templates</li>
                            <li>Flip Box Premade Templates</li>
                            <li>Before After Slider Premade Templates</li>
                            <li>Content Ticker Premade Templates</li>
                            <li>Mail Chimp Premade Templates</li>
                            <li>Image Hotspot Premade Templates</li>
                            <li>Team Member Premade Templates</li>
                            <li>Button Premade Templates</li>
                            <li>Dual Button Premade Templates</li>
                            <li>Price List Premade Templates</li>
                            <li>Business Hours Premade Templates</li>
                            <li>Sharing Buttons Premade Templates</li>
                            <li>Progress Bar Premade Templates</li>
                            <li>Countdown Timer Premade Templates</li>
                            <li>Content Toggle Premade Templates</li>
                            <li>Pricing Table Premade Templates</li>
                            <li>Advanced Text Premade Templates</li>
                            <li>Search Premade Templates</li>
                        </ul>
                    </li>
                    <li><span>Advanced Popup Builder</span>
                        <ul>
                            <li>Build any type of popups: Email subscriptions, Promotion Sales, Countdown, Announcements, Yes/No popups, Welcome Mat, Cookie contest, GDPR notice, Age Restriction gates and others</li>
                            <li>Trigger Popup: On Page Load, on Page scroll, after user Exit intent, by clicking on specific element or button, after user inactivity, after Specific date, on Scroll to specific element</li>
                            <li>Trigger popup on specific URL Query parameters</li>
                            <li>Layout styles: Full Screen, Slide in, Modal, Top or Bottom Bar</li>
                            <li>Customize "Show Again Delay" option after popup being closed</li>
                            <li>Show Popup for Specific User Roles & Devices</li>
                            <li>Show Popups anywhere you want: Entire site, only for the Front page, only for the Single post or Single page, on Search page, 404 page or any other pages</li>
                            <li>Use prebuilt Popups from Library</li>
                            <li>Use any Elementor widget you like</li>
                        </ul>
                    </li>
                    <li><span>17 Premade Popup Templates</span>
                        <ul>
                            <li>Discount Popup Templates</li>
                            <li>Subscription Popup Templates</li>
                            <li>Cookie & GDPR Popup Templates</li>
                            <li>Yes/No Popup Templates</li>
                        </ul>
                    </li>
                    <li><span>Advanced Particles</span>
                        <ul>
                            <li>Pro Effects: Particles, Nasa, Snow</li>
                            <li>Control Particle Shapes: Circle, Edge, Triangle, Polygon, Star</li>
                            <li>Control Particles Quantity</li>
                            <li>Control Particles Size</li>
                            <li>Control Animation Speed</li>
                            <li>Control Animation Color</li>
                        </ul>
                    </li>
                    <li><span>Advanced Parallax Effext</span>
                        <ul>
                            <li>Advanced Scrolling Effects: Opacity, Scale opacity, Scroll opacity</li>
                            <li>Multilayer Parallax</li>
                        </ul>
                    </li>
                    <li><span class="wpr-advanced-sticky-options">Advanced Sticky Section - View Demo</span>
                        <ul>
                            <li>Replace Header Section with a new Section on Scroll</li>
                            <li>Change Section Height, Background and Text/Link Colors and Scale logo with transitions</li>
                            <li>Hide Section when Scrolling Down and only show when Scrolling Up</li>
                            <li>Add Borders, Shadows and Animations on Scroll</li>
                        </ul>
                    </li>
                    <li><span class="wpr-premium-template-kit-lib">Premium Template Kit Library - View Demo</span>
                        <ul>
                            <li>Access to All Premium Template Kit Library. Ready to use Sites which can be imported in one click in a few seconds.</li>
                        </ul>
                    </li>
                    <li><span>Advanced Theme Builder</span>
                        <ul>
                            <li>Advanced Conditions: create multiple Theme Builder templates and set custom conditions. You choose where and how your Header, Footer or other templates appear.</li>
                            <li>Premium premade Template Kits.</li>
                            <li>Advanced Theme Builder widgets: Archive Title, Single Post Navigation, Post Comments, Author Box, etc...</li>
                            <li>Custom Post Type Support (Expert)</li>
                        </ul>
                    </li>
                    <li><span>Advanced WooCommerce Shop Builder</span>
                        <ul>
                            <li>My account Widget - Customize any part of my account page.</li>
                            <li>Product Filter Widget - Filter Products by Price, Rating, Category, Tags. Also Woocommerce native search is supported.</li>
                            <li>Sort Woocommerce Grid by: Default, Latest, Popularity, Average Rating, Price Low to High, Price Hight to Low, Title A to Z, Title Z to A.</li>
                            <li>Category Widget - Display Woocommerce categories with advanced Grid.</li>
                            <li>Display Upsell, Cross Sell, Featured and Onsale Products.</li>
                            <li>Controll how many products to display on the Shop page, Category Page, and Tag page.</li>
                            <li>Ability to customize Category & Tag page in theme builder.</li>
                            <li>Advanced Woo Builder Conditions: Create multiple Woo Builder templates and set custom conditions.</li>
                            <li>Premium premade Woocommerce Template Kits.</li>
                            <li>Advanced Mini Cart widget options like display Mini cart as Dropdown or as Sidebar.</li>
                            <li>Add to cart popup effect. Displays a small popup banner on left/right corner of the screen with the text "XXX item was added to Cart - View Cart".</li>
                            <li>Vertical single product tabs layout.</li>
                            <li>Product Breadcrumbs - Display Product, Post, Page, Categories addresses to make navigation much easier.</li>
                            <li>Custom Post Type Support (Expert)</li>
                        </ul>
                    </li>
                    <li><span>Elementor Pro Not Required</span>
                        <ul>
                            <li>Templates Kit, Widgets and any other setting and Extension Doesn't require Elementor PRO</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="wpr-expert-widgets">
                <header>
                    <span class="dashicons dashicons-star-filled"></span>
                    <h3><?php echo esc_html__( 'Expert Version', 'wpr-addons' ); ?></h3>
                    <span><?php echo esc_html__( 'Top-tier Functionality', 'wpr-addons' ); ?></span>
                </header>
                <ul>
                    <li><span>Dedicated Support</span></li>
                    <li><span>Free and Pro Functionality Included</span></li>
                    <li><span>Extended Custom Field Options </span></li>
                    <li><span>Ability to build Dynamic Websites</span></li>
                    <li><span>Dynamic Tags for All Widgets</span></li>
                    <li><span>Extended Custom Field Options</span></li>
                    <li><span>Custom Post Type Generator</span></li>
                    <li><span>Custom Taxonomy Generator</span></li>
                    <li><span>WooCommerce Wishlist Widget</span></li>
                    <li><span>WooCommerce Compare Widget</span></li>
                    <li><span>Category Grid Widget</span></li>
                    <li><span>White Label Branding</span></li>
                    <li><span>Elementor Pro Not Required</span></li>
                    <li><span>Custom Field Widget</span></li>
                    <li>And More is Comming Soon...</li>
                </ul>
            </div>
        </div>

        <div class="wpr-feature-not-found">
            <img src="<?php echo esc_url(WPR_ADDONS_ASSETS_URL .'img/not-found.png'); ?>">
            <h1><?php esc_html_e('No Search Results Found.', 'wpr-addons'); ?></h1>
            <p><?php esc_html_e('Cant find a Feature you are looking for?', 'wpr-addons'); ?></p>
            <a href="https://royaladdons.frill.co/b/6m4d5qm4/feature-ideas" target="_blank"><?php esc_html_e('Request a New Feature', 'wpr-addons'); ?></a>
        </div>

        <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-freevsprotab-pro#purchasepro" target="_blank" class="button last wpr-free-pro-upgrade">
            <span><?php echo esc_html__( 'Get Premium', 'wpr-addons' ); ?></span>
            <span class="dashicons dashicons-smiley"></span>
        </a>

        </div>
    
    <?php elseif ( $active_tab == 'wpr_tab_extensions' ) :

        // Extensions
        settings_fields( 'wpr-extension-settings' );
        do_settings_sections( 'wpr-extension-settings' );

        global $new_allowed_options;

        // array of option names
        $option_names = $new_allowed_options[ 'wpr-extension-settings' ];

        echo '<div class="wpr-elements">';

        foreach ($option_names as $option_name) {  
            $option_title = ucwords( preg_replace( '/-/i', ' ', preg_replace('/wpr-||-toggle/i', '', $option_name ) ));

            echo '<div class="wpr-element">';
                echo '<div class="wpr-element-info">';
                    echo '<h3>'. esc_html($option_title) .'</h3>';
                    echo '<input type="checkbox" name="'. esc_attr($option_name) .'" id="'. esc_attr($option_name) .'" '. checked( get_option(''. $option_name .'', 'on'), 'on', false ) .'>';
                    echo '<label for="'. esc_attr($option_name) .'"></label>';

                    if ( 'wpr-parallax-background' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Style tab</span>';
                        echo '<a href="https://www.youtube.com/watch?v=DcDeQ__lJbw" target="_blank">Watch Video Tutorial</a>';
                    } elseif ( 'wpr-parallax-multi-layer' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Style tab</span>';
                        echo '<a href="https://youtu.be/DcDeQ__lJbw?t=121" target="_blank">Watch Video Tutorial</a>';
                    } elseif ( 'wpr-particles' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Style tab</span>';
                        echo '<a href="https://www.youtube.com/watch?v=8OdnaoFSj94" target="_blank">Watch Video Tutorial</a>';
                    } elseif ( 'wpr-sticky-section' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Advanced tab</span>';
                        echo '<a href="https://www.youtube.com/watch?v=at0CPKtklF0&t=375s" target="_blank">Watch Video Tutorial</a>';
                        if ( !wpr_fs()->can_use_premium_code() && !wpr_fs()->is_plan( 'expert') ) {
                            echo '<h4 class="wpr-sticky-advanced-demos-title">Advanced Sticky Section (Pro)</h4>';
                            echo '<p class="wpr-sticky-advanced-demos">';
                                echo '<span>View Demos: </span>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/fashion-v2/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 1, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/digital-marketing-agency-v2/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 2, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/personal-blog-v1/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 3, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/digital-marketing-agency-v1/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 4, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/construction-v3/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 5</a>';
                            echo '</p>';
                            echo '<a class="wpr-sticky-video-tutorial wpr-inline-link" href="https://www.youtube.com/watch?v=ORay3VWrWuc" target="_blank">Watch Video Tutorial</a>';
                            echo '<a class="wpr-inline-link" href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-elements-advanced-stiky-pro#purchasepro" target="_blank">Upgrade to Pro</a>';
                        } else {
                            echo '<h4 class="wpr-sticky-advanced-demos-title">Advanced Sticky Section</h4>';
                            echo '<p class="wpr-sticky-advanced-demos">';
                                echo '<span>View Demos: </span>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/fashion-v2/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 1, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/digital-marketing-agency-v2/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 2, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/personal-blog-v1/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 3, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/digital-marketing-agency-v1/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 4, </a>';
                                echo '<a href="https://demosites.royal-elementor-addons.com/construction-v3/?ref=rea-plugin-backend-elements-advanced-stiky-preview" target="_blank">Demo 5</a>';
                            echo '</p>';
                            echo '<a class="wpr-sticky-video-tutorial" href="https://www.youtube.com/watch?v=ORay3VWrWuc" target="_blank">Watch Video Tutorial</a>';
                        }
                    } elseif ( 'wpr-custom-css' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Advanced tab</span>';
                    }

                    // echo '<a href="https://royal-elementor-addons.com/elementor-particle-effects/?ref=rea-plugin-backend-extentions-prev">'. esc_html('View Extension Demo', 'wpr-addons') .'</a>';
                echo '</div>';
            echo '</div>';
        }

        echo '</div>';
    ?>



    <?php

    elseif ( $active_tab == 'wpr_tab_white_label' ) :

        if ( wpr_fs()->is_plan( 'expert' ) ) {
            do_action('wpr_white_label_tab_content');
        }

    endif; ?>

    <div class="wpr-settings-saved">
        <span><?php esc_html_e('Options Updated', 'wpr-addons'); ?></span>
        <span class="dashicons dashicons-yes"></span>
    </div>
</form>
</div>

</div>


<?php

} // End wpr_addons_settings_page()

// // Add Pro Plugin Link Sub Menu item that will redirect to wp.org
// function wpr_addons_add_pro_plugin_direct_link_page() {
//     add_submenu_page( 'wpr-addons', 'Pro Plugin Direct Link', 'Pro Plugin Direct Link', 'manage_options', 'wpr-pro-plugin-direct-link', 'wpr_addons_pro_plugin_direct_link', 99 );
// }
// add_action( 'admin_menu', 'wpr_addons_add_pro_plugin_direct_link_page', 99 );

// function wpr_addons_pro_plugin_direct_link() {  
//     define( 'FS__API_SCOPE', 'developer' );
//     define( 'FS__API_DEV_ID', 8416 );
//     define( 'FS__API_PUBLIC_KEY', 'pk_a0b21b234a7c9581a555b9ee9f28a' );
//     define( 'FS__API_SECRET_KEY', 'sk_HN%H:Qlyn]0(3R[IhfCYss3A9.]mO' );
    
//     // Init SDK.
//     $api = new Freemius_Api_WordPress(FS__API_SCOPE, FS__API_DEV_ID, FS__API_PUBLIC_KEY, FS__API_SECRET_KEY);
    
//     // Get all products.
//     $result = $api->Api('/plugins/8416/licenses/721741/tags/latest.json?format=json');

//     var_dump($result);
// }



// Add Support Sub Menu item that will redirect to wp.org
function wpr_addons_add_support_menu() {
    add_submenu_page( 'wpr-addons', 'Support', 'Support', 'manage_options', 'wpr-support', 'wpr_addons_support_page', 99 );
}
add_action( 'admin_menu', 'wpr_addons_add_support_menu', 99 );

function wpr_addons_support_page() {}

function wpr_redirect_support_page() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            $( 'ul#adminmenu a[href*="page=wpr-support"]' ).attr('href', 'https://wordpress.org/support/plugin/royal-elementor-addons/').attr( 'target', '_blank' );
        });
    </script>
    <?php
}
add_action( 'admin_head', 'wpr_redirect_support_page' );


// Add Upgrade Sub Menu item that will redirect to royal-elementor-addons.com
function wpr_addons_add_upgrade_menu() {
    if ( defined('WPR_ADDONS_PRO_VERSION') && !wpr_fs()->can_use_premium_code() ) return;

    if ( wpr_fs()->is_plan( 'expert' ) ) return;

    if ( !wpr_fs()->can_use_premium_code() ) {
        $label = 'Upgrade';
    } else if ( wpr_fs()->is_plan( 'pro' ) ) {
        $label = 'Upgrade to Expert';
    }

    add_submenu_page( 'wpr-addons', $label, $label, 'manage_options', 'wpr-upgrade', 'wpr_addons_upgrade_page', 999 );
}
add_action( 'admin_menu', 'wpr_addons_add_upgrade_menu', 999999999999 );

function wpr_addons_upgrade_page() {}

function wpr_redirect_upgrade_page() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            let ref = 'Upgrade to Expert' == $( 'ul#adminmenu a[href*="page=wpr-upgrade"]' ).text() ? 'expert' : 'pro';
            $( 'ul#adminmenu a[href*="page=wpr-upgrade"]' ).attr('href', 'https://royal-elementor-addons.com/?ref=rea-plugin-backend-menu-upgrade-'+ ref +'#purchasepro').attr( 'target', '_blank' );
            $( 'ul#adminmenu a[href*="#purchasepro"]' ).css('color', 'greenyellow');
        });
    </script>
    <?php
}
add_action( 'admin_head', 'wpr_redirect_upgrade_page' );


/**
** Search Query Results
*/
function wpr_backend_widget_search_query_results() {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
		return;
	}

    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';

    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/backend-widget-search/data', [
        'body' => [
            'search_query' => $search_query
        ]
    ] );
}

function wpr_backend_freepro_search_query_results() {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    if ( strpos($_SERVER['SERVER_NAME'],'instawp') || strpos($_SERVER['SERVER_NAME'],'tastewp') ) {
		return;
	}
    
    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';

    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/backend-freepro-search/data', [
        'body' => [
            'search_query' => $search_query
        ]
    ] );
}