<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_register_style( 'ms-lms-course-builder', MS_LMS_URL . 'assets/course-builder/css/main.css', null, MS_LMS_VERSION );
wp_register_script( 'ms-lms-course-builder', MS_LMS_URL . 'assets/course-builder/js/main.js', null, MS_LMS_VERSION, true );
wp_set_script_translations( 'ms-lms-course-builder', 'masterstudy-lms-learning-management-system', MS_LMS_PATH . '/languages' );

$scripts = wp_scripts();
$styles  = wp_styles();

$load_scripts = array(
	'jquery-core',
	'jquery-migrate',
	'wp-polyfill-inert',
	'regenerator-runtime',
	'wp-polyfill',
	'wp-hooks',
	'wp-i18n',
	'utils',
	'editor',
);

do_action( 'stm_lms_template_main' );
?>
<!doctype html>
<html lang="<?php echo esc_attr( get_locale() ); ?>">
<head>
	<title><?php esc_html_e( 'Course Builder', 'masterstudy-lms-learning-management-system' ); ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url( ms_plugin_favicon_url() ); ?>" />
	<link href="<?php echo esc_url( site_url( $styles->registered['dashicons']->src ) ); // phpcs:ignore ?>" rel="stylesheet">
	<link href="<?php echo esc_url( site_url( $styles->registered['editor-buttons']->src ) ); // phpcs:ignore ?>" rel="stylesheet">
	<link href="<?php echo esc_url( $styles->registered['ms-lms-course-builder']->src ); // phpcs:ignore ?>" rel="stylesheet">
</head>
<body>
<div id="ms_plugin_root"></div>
<?php
foreach ( $load_scripts as $handle ) {
	$handle_src = $scripts->registered[ $handle ]->src;
	$src_url    = filter_var( $handle_src, FILTER_VALIDATE_URL ) ? $handle_src : site_url( $handle_src );
	?>
	<script src="<?php echo esc_url( $src_url ); // phpcs:ignore ?>"></script>
<?php } ?>
<script>
	window.lmsApiSettings = {
		lmsUrl: '<?php echo esc_url_raw( rest_url( 'masterstudy-lms/v2' ) ); ?>',
		wpUrl: '<?php echo esc_url_raw( rest_url( 'wp/v2' ) ); ?>',
		nonce: '<?php echo esc_html( wp_create_nonce( 'wp_rest' ) ); ?>',
	}
</script>
<?php
$scripts->print_translations( 'ms-lms-course-builder' );

if ( ! class_exists( '\_WP_Editors', false ) ) {
	require ABSPATH . WPINC . '/class-wp-editor.php';
}

\_WP_Editors::print_default_editor_scripts();
\_WP_Editors::print_tinymce_scripts();
?>
<script src="<?php echo esc_url( $scripts->registered['ms-lms-course-builder']->src ); // phpcs:ignore ?>"></script>
</body>
</html>
