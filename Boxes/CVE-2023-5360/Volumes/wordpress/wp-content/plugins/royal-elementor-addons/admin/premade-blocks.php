<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WprAddons\Admin\Templates\Library\WPR_Templates_Data;
use WprAddons\Admin\Templates\Library\WPR_Templates_Library_Blocks;
use WprAddons\Classes\Utilities;
use Elementor\Plugin;

// Register Menus
function wpr_addons_add_premade_blocks_menu() {
	add_submenu_page( 'wpr-addons', 'Premade Blocks', 'Premade Blocks', 'manage_options', 'wpr-premade-blocks', 'wpr_addons_premade_blocks_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_premade_blocks_menu' );

/**
** Render Premade Blocks Page
*/
function wpr_addons_premade_blocks_page() {

?>

<div class="wpr-premade-blocks-page">

    <div class="wpr-settings-page-header">
        <h1><?php echo esc_html(Utilities::get_plugin_name(true)); ?></h1>
        <p><?php esc_html_e( 'The most powerful Elementor Addon in the universe.', 'wpr-addons' ); ?></p>
    </div>

    <?php WPR_Templates_Library_Blocks::render_library_templates_blocks(); ?>

</div>


<?php

} // End wpr_addons_premade_blocks_page()
