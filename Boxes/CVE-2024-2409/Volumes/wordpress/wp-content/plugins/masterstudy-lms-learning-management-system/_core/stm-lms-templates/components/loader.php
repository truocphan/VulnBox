<?php
/**
 * Loader component.
 *
 * @var int     $delay     - delay in seconds of the loader on the page.
 * @var boolean $dark_mode - if true theme mode will be dark, otherwise light.
 * @var boolean $is_local  - if true then show on specific place, otherwise on whole page.
 * @var boolean $bordered  - for bordered style
 * @var boolean $global    - for main loader on the page
 *
 * masterstudy-loader_dark-mode - for dark mode
 *
 * @package masterstudy
 */

$global    = $global ?? false;
$is_local  = $is_local ?? false;
$dark_mode = $dark_mode ?? false;
$bordered  = $bordered ?? false;

if ( $global ) {
	wp_enqueue_script( 'masterstudy-loader' );
	wp_localize_script(
		'masterstudy-loader',
		'data',
		array(
			'delay' => intval( $delay ),
		)
	);
}

$loader_class  = $dark_mode ? ' masterstudy-loader_dark-mode' : '';
$loader_class .= $global ? ' masterstudy-loader_global' : '';
$loader_class .= $is_local ? ' masterstudy-loader_local' : '';
$loader_class .= $bordered ? ' masterstudy-loader_bordered' : '';
?>
<span class="masterstudy-loader <?php echo esc_attr( $loader_class ); ?>">
	<div class="masterstudy-loader__body"></div>
</span>
