<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WprAddons\Admin\Includes\WPR_Templates_Loop;
use WprAddons\Classes\Utilities;

// Register Menus
function wpr_addons_add_theme_builder_menu() {
	add_submenu_page( 'wpr-addons', 'Theme Builder', 'Theme Builder', 'manage_options', 'wpr-theme-builder', 'wpr_addons_theme_builder_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_theme_builder_menu' );

function wpr_addons_theme_builder_page() {

?>

<div class="wrap wpr-settings-page-wrap">

<div class="wpr-settings-page-header">
    <h1><?php echo esc_html(Utilities::get_plugin_name(true)); ?></h1>
    <p><?php esc_html_e( 'The most powerful Elementor Addon in the universe.', 'wpr-addons' ); ?></p>

    <!-- Custom Template -->
    <div class="wpr-preview-buttons">
        <div class="wpr-user-template">
            <span><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
            <span class="plus-icon">+</span>

            <?php
            if ( ! class_exists( 'WooCommerce' ) && isset($_GET['tab']) && ('wpr_tab_product_archive' === $_GET['tab'] || 'wpr_tab_product_single' === $_GET['tab'] )) {
                echo '<div></div>';
            }
            ?>
        </div>

        <a href="https://www.youtube.com/watch?v=cwkhwO_rPuo" class="wpr-how-to-use-theme-builder wpr-options-button button" target="_blank" style="padding: 8px 22px; margin-left: 10px;">
            <?php echo esc_html__( 'How to use Theme Builder', 'wpr-addons' ); ?>
            <span class="dashicons dashicons-video-alt3"></span>
        </a>
        <a href="https://www.youtube.com/watch?v=f_3tNiBC3dw" class="wpr-how-to-use-woo-builder wpr-options-button button" target="_blank" style="padding: 8px 22px; margin-left: 10px;">
            <?php echo esc_html__( 'How to use WooCommerce Builder', 'wpr-addons' ); ?>
            <span class="dashicons dashicons-video-alt3"></span>
        </a>

        <a href="https://royaladdons.frill.co/b/6m4d5qm4/feature-ideas" class="wpr-options-button button" target="_blank" style="padding: 8px 22px;">
            <?php echo esc_html__( 'Request New Feature', 'wpr-addons' ); ?>
            <span class="dashicons dashicons-star-empty"></span>
        </a>
    </div>
</div>

<div class="wpr-settings-page">
<form method="post" action="options.php">
    <?php

    // Active Tab
    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'wpr_tab_header';

    ?>

    <!-- Template ID Holder -->
    <input type="hidden" name="wpr_template" id="wpr_template" value="">

    <!-- Conditions Popup -->
    <?php WPR_Templates_Loop::render_conditions_popup(true); ?>

    <!-- Create Templte Popup -->
    <?php WPR_Templates_Loop::render_create_template_popup(); ?>

    <!-- Tabs -->
    <div class="nav-tab-wrapper wpr-nav-tab-wrapper">
        <a href="?page=wpr-theme-builder&tab=wpr_tab_header" data-title="Header" class="nav-tab <?php echo ($active_tab == 'wpr_tab_header') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Header', 'wpr-addons' ); ?>
        </a>
        <a href="?page=wpr-theme-builder&tab=wpr_tab_footer" data-title="Footer" class="nav-tab <?php echo ($active_tab == 'wpr_tab_footer') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Footer', 'wpr-addons' ); ?>
        </a>
        <a href="?page=wpr-theme-builder&tab=wpr_tab_archive" data-title="Archive" class="nav-tab <?php echo ($active_tab == 'wpr_tab_archive') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Archive', 'wpr-addons' ); ?>
        </a>
        <a href="?page=wpr-theme-builder&tab=wpr_tab_single" data-title="Single" class="nav-tab <?php echo ($active_tab == 'wpr_tab_single') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Single', 'wpr-addons' ); ?>
        </a>

        <a href="?page=wpr-theme-builder&tab=wpr_tab_product_archive" data-title="Product Archive" class="nav-tab <?php echo $active_tab == 'wpr_tab_product_archive' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Product Archive', 'wpr-addons' ); ?>
        </a>
        <a href="?page=wpr-theme-builder&tab=wpr_tab_product_single" data-title="Product Single" class="nav-tab <?php echo $active_tab == 'wpr_tab_product_single' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Product Single', 'wpr-addons' ); ?>
        </a>

        <a href="?page=wpr-theme-builder&tab=wpr_tab_my_templates" data-title="My Templates" class="nav-tab <?php echo ($active_tab == 'wpr_tab_my_templates') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Saved Templates', 'wpr-addons' ); ?>
        </a>
    </div>

    <?php if ( $active_tab == 'wpr_tab_header' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_header_conditions" id="wpr_header_conditions" value="<?php echo esc_attr(get_option('wpr_header_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'header' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_footer' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_footer_conditions" id="wpr_footer_conditions" value="<?php echo esc_attr(get_option('wpr_footer_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'footer' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_archive' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_archive_conditions" id="wpr_archive_conditions" value="<?php echo esc_attr(get_option('wpr_archive_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'archive' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_single' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_single_conditions" id="wpr_single_conditions" value="<?php echo esc_attr(get_option('wpr_single_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'single' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_product_archive' ) : ?>
        
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <!-- Save Conditions -->
            <input type="hidden" name="wpr_product_archive_conditions" id="wpr_product_archive_conditions" value="<?php echo esc_attr(get_option('wpr_product_archive_conditions', '[]')); ?>">

            <?php WPR_Templates_Loop::render_theme_builder_templates( 'product_archive' ); ?>
        <?php else : ?>
            <div class="wpr-activate-woo-notice"><span class="dashicons dashicons-info-outline"></span> Please install/activate WooCommerce in order to create product archive templates!</div>
        <?php endif; ?>

    <?php elseif ( $active_tab == 'wpr_tab_product_single' ) : ?>

        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <!-- Save Conditions -->
            <input type="hidden" name="wpr_product_single_conditions" id="wpr_product_single_conditions" value="<?php echo esc_attr(get_option('wpr_product_single_conditions', '[]')); ?>">

            <?php WPR_Templates_Loop::render_theme_builder_templates( 'product_single' ); ?>
        <?php else : ?>
            <div class="wpr-activate-woo-notice"><span class="dashicons dashicons-info-outline"></span> Please install/activate WooCommerce in order to create product single templates!</div>
        <?php endif ; ?>

    <?php elseif ( $active_tab == 'wpr_tab_my_templates' ) : ?>

        <?php Wpr_Templates_Loop::render_elementor_saved_templates(); ?>

    <?php endif; ?>

</form>
</div>

</div>


<?php

} // End wpr_addons_theme_builder_page()