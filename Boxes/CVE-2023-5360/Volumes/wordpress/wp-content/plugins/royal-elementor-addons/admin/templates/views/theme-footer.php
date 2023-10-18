<?php
use WprAddons\Admin\Includes\WPR_Conditions_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$conditions = json_decode( get_option('wpr_footer_conditions', '[]'), true );
$template_slug = WPR_Conditions_Manager::header_footer_display_conditions($conditions);

// Render WPR Header
Utilities::render_elementor_template($template_slug);

wp_footer();

?>

</body>
</html> 