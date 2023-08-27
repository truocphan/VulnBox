<?php
/**
 * Customizer settings for post type archive.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.13.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	$post_types = jupiterx_get_post_types( 'objects' );

	if ( empty( $post_types ) ) {
		return;
	}

	foreach ( $post_types as $post_type ) {
		if ( $post_type->has_archive ) :

			// Template.
			JupiterX_Customizer::update_field( "jupiterx_{$post_type->name}_archive_template", [
				'locked' => false,
			] );
		endif;
	}
} );
