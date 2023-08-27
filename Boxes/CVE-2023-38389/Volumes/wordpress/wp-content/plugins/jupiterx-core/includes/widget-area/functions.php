<?php
/**
 * JupiterX_Core manage custom widget areas.
 *
 * @package JupiterX_Core\Widget_Area
 *
 * @since 1.6.0
 */

add_action( 'wp_ajax_jupiterx_add_custom_widget_area', 'jupiterx_core_add_custom_widget_area' );
/**
 * Save custom widget area.
 *
 * @since 1.6.0
 *
 * @return void
 */
function jupiterx_core_add_custom_widget_area() {
	check_ajax_referer( 'jupiterx_add_custom_widget_area' );

	$sidebars = jupiterx_get_option( 'custom_sidebars' );

	if ( empty( $sidebars ) ) {
		$sidebars = [];
	}

	if ( empty( $_POST['name'] ) ) {
		wp_send_json_error( __( 'name field is required', 'jupiterx-core' ) );
	}

	$name       = sanitize_text_field( wp_unslash( $_POST['name'] ) );
	$sidebars[] = [ 'name' => $name ];

	jupiterx_update_option( 'custom_sidebars', $sidebars );

	wp_send_json_success();
}

add_action( 'wp_ajax_jupiterx_delete_custom_widget_area', 'jupiterx_core_delete_custom_widget_area' );
/**
 * Delete custom widget area.
 *
 * @since 1.6.0
 *
 * @return void
 */
function jupiterx_core_delete_custom_widget_area() {
	$sidebars         = jupiterx_get_option( 'custom_sidebars' );
	$sidebars_widgets = get_option( 'sidebars_widgets' );

	if ( empty( $_POST['id'] ) && '0' !== $_POST['id'] ) { // phpcs:ignore WordPress.Security
		wp_send_json_error( __( 'id field is required', 'jupiterx-core' ) );
	}

	$id = intval( sanitize_text_field( wp_unslash( $_POST['id'] ) ) ); // phpcs:ignore WordPress.Security

	if ( is_array( $sidebars ) & isset( $sidebars[ $id ] ) ) {
		unset( $sidebars[ $id ] );

		jupiterx_update_option( 'custom_sidebars', $sidebars );

		if ( isset( $sidebars_widgets[ 'jupiterx_custom_sidebar_' . ( $id + 1 ) ] ) ) {
			unset( $sidebars_widgets[ 'jupiterx_custom_sidebar_' . ( $id + 1 ) ] );
			update_option( 'sidebars_widgets', $sidebars_widgets );
		}

		wp_send_json_success();
	}

	wp_send_json_error();
}


add_action( 'widgets_admin_page', function () { ?>

	<div class="jupiterx-widgets-admin-page">
		<button
			id="js__jupiterx-add-custom-widget-area"
			class="button button-primary"
			data-nonce="<?php echo esc_html( wp_create_nonce( 'jupiterx_add_custom_widget_area' ) ); ?>">
			<?php echo esc_html( __( 'Add Custom Sidebar', 'jupiterx-core' ) ); ?>
		</button>
	</div>

	<?php
} );
