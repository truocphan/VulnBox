<?php
/**
 * The Jupiter Widgets API extends the WordPress Widgets API. Where WordPress uses 'sidebar', Jupiter uses
 * 'widget_area' as it is more appropriate when using it to define an area which is not besides the main
 * content (e.g. a mega footer widget area).
 *
 * @package JupiterX_Core\Widgets
 *
 * @since 1.0.0
 */

add_action( 'widgets_init', 'jupiterx_register_widgets' );

if ( ! function_exists( 'jupiterx_register_widgets' ) ) {
	/**
	 * Register custom widgets of Jupiter
	 *
	 * @since 1.0.0
	 * @ignore
	 * @access private
	 *
	 * @return void
	 */
	function jupiterx_register_widgets() {

		$widgets = [
			'widget-social.php'   => 'JupiterX_Widget_Social',
			'widget-posts.php'    => 'JupiterX_Widget_Posts',
			'widget-nav-menu.php' => '',
		];

		foreach ( $widgets as $file => $widget ) {
			require_once $file;

			if ( ! empty( $widget ) ) {
				register_widget( $widget );
			}
		}
	}
}
