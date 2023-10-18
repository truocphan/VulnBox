<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WprAddons\Admin\Includes\WPR_Templates_Loop;
use WprAddons\Classes\Utilities;

// Register Menus
function wpr_addons_add_popups_menu() {
	add_submenu_page( 'wpr-addons', 'Popup Builder', 'Popup Builder', 'manage_options', 'wpr-popups', 'wpr_addons_popups_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_popups_menu' );

function wpr_addons_popups_page() {

?>

<div class="wrap wpr-settings-page-wrap">

<div class="wpr-settings-page-header">
    <h1><?php echo esc_html(Utilities::get_plugin_name(true)); ?></h1>
    <p><?php esc_html_e( 'The most powerful Elementor Addons in the universe.', 'wpr-addons' ); ?></p>

    <!-- Custom Template -->
    <div class="wpr-preview-buttons">
        <div class="wpr-user-template">
            <span><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
            <span class="plus-icon">+</span>
        </div>

        <a href="https://www.youtube.com/watch?v=TbKTNpuXM68" class="wpr-options-button button" target="_blank" style="padding: 10px 22px;">
            <?php echo esc_html__( 'How to use Popups', 'wpr-addons' ); ?>
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
    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'wpr_tab_popups';

    ?>

    <!-- Template ID Holder -->
    <input type="hidden" name="wpr_template" id="wpr_template" value="">

    <!-- Conditions Popup -->
    <?php WPR_Templates_Loop::render_conditions_popup(); ?>

    <!-- Create Templte Popup -->
    <?php WPR_Templates_Loop::render_create_template_popup(); ?>

    <!-- Tabs -->
    <div class="nav-tab-wrapper wpr-nav-tab-wrapper">
        <a href="?page=wpr-theme-builder&tab=wpr_tab_popups" data-title="popup" class="nav-tab <?php echo ($active_tab == 'wpr_tab_popups') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Popups', 'wpr-addons' ); ?>
        </a>
    </div>

    <?php if ( $active_tab == 'wpr_tab_popups' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_popup_conditions" id="wpr_popup_conditions" value="<?php echo esc_attr(get_option('wpr_popup_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'popup' ); ?>

    <?php endif; ?>

</form>
</div>

</div>


<?php

} // End wpr_addons_popups_page()