<?php
/**
 * Customizer functions.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.11.0
 */

add_action( 'after_switch_theme', 'jupiterx_update_initial_mods' );
/**
 * Update initial theme mods to remove mods with integer key.
 *
 * @since 1.4.0
 *
 * @return void
 */
function jupiterx_update_initial_mods() {
	$mods = get_theme_mods();

	foreach ( $mods as $key => $value ) {
		if ( is_numeric( $key ) ) {
			unset( $mods[ $key ] );
		}
	}

	$theme_stylesheet_slug = get_option( 'stylesheet' );

	update_option( 'theme_mods_' . $theme_stylesheet_slug, $mods );
}
add_action( 'after_switch_theme', 'jupiterx_migrate_lite_mods' );
/**
 * Update theme mods from Lite version.
 *
 * @since 1.11.0
 */
function jupiterx_migrate_lite_mods() {
	if ( jupiterx_get_option( 'mods_migrated_from_lite', false ) || ! jupiterx_is_premium() ) {
		return;
	}

	$theme_mods = get_option( 'theme_mods_jupiterx-lite-child', [] );

	if ( empty( $theme_mods ) ) {
		$theme_mods = get_option( 'theme_mods_jupiterx-lite', [] );
	}

	update_option( 'theme_mods_jupiterx', $theme_mods );

	jupiterx_update_option( 'mods_migrated_from_lite', true );
}
