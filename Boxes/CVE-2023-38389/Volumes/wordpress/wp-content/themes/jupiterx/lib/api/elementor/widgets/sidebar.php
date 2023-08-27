<?php
/**
 * Handles overriding Elementor Sidebar widget.
 *
 * @package JupiterX\Framework\API\Elementor
 *
 * @since   1.0.0
 */

/**
 * The Jupiter Elementor's Custom Sidebar
 *
 * @since   1.0.0
 * @ignore
 *
 * @package JupiterX\Framework\API\Elementor
 */
class JupiterX_Elementor_Widget_Sidebar extends \Elementor\Widget_Sidebar {

	/**
	 * Render sidebar widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$sidebar = $this->get_settings_for_display( 'sidebar' );

		if ( empty( $sidebar ) ) {
			return;
		}

		echo jupiterx_widget_area( $sidebar ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'elementor/element/sidebar/section_sidebar/before_section_end', 'jupiterx_elementor_sidebar_add_settings' );
/**
 * Add settings to Elementor Sidebar widget.
 *
 * @todo Find a proper way to add this to JupiterX_Elementor_Widget_Sidebar class.
 *
 * @since 1.4.0
 *
 * @param object $element The element object.
 */
function jupiterx_elementor_sidebar_add_settings( $element ) {

	$element->add_control(
		'jupiterx_location',
		[
			'label' => __( 'Location', 'jupiterx' ),
			'description' => __( 'Content inherits styles from <strong>Customizer > Sidebar</strong> and Footer from <strong>Customizer > Footer</strong>.', 'jupiterx' ),
			'type' => 'select',
			'options' => [
				'' => __( 'Default', 'jupiterx' ),
				'sidebar' => __( 'Content', 'jupiterx' ),
				'footer-widgets' => __( 'Footer', 'jupiterx' ),
			],
			'prefix_class' => 'jupiterx-',
		]
	);

};
