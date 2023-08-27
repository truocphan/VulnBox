<?php
/**
 * Manage onboarding jupiter theme.
 *
 * @package JupiterX\Framework\API\Onboarding
 *
 * @since   1.2.0
 */

add_action( 'after_switch_theme', 'jupiterx_activation_popup' );
/**
 * Manage popup while theme switch.
 *
 * It includes scripts, styles, localizations and needed checks.
 *
 * @since 1.2.0
 *
 * @return void
 */
function jupiterx_activation_popup() {

	$jupiterx_current_version = jupiterx_get_option( 'theme_current_version' ); // prevent showing popup if user had Jupiter X.
	$jupiterx_onboarding      = jupiterx_get_option( 'unboarding_hide_popup' );

	if ( ! jupiterx_had_jupiter() || ! empty( $jupiterx_current_version ) || ! empty( $jupiterx_onboarding ) ) {
		return;
	}

	jupiterx_update_option( 'unboarding_hide_popup', true );

	wp_enqueue_style( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/css/jupiterx-modal' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
	wp_enqueue_style( 'jupiterx-activation-popup', JUPITERX_ASSETS_URL . 'dist/css/activation-popup' . JUPITERX_MIN_JS . '.css', [], JUPITERX_VERSION );

	wp_enqueue_script( 'jupiterx-popper', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/popper/popper' . JUPITERX_MIN_JS . '.js', [], '1.14.3', true );
	wp_enqueue_script( 'jupiterx-bootstrap', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/bootstrap/bootstrap' . JUPITERX_MIN_JS . '.js', [], '4.1.2', true );
	wp_enqueue_script( 'jupiterx-dynamicmaxheight', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/dynamicmaxheight/dynamicmaxheight' . JUPITERX_MIN_JS . '.js', [], '0.0.3', true );
	wp_enqueue_script( 'jupiterx-gsap', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/gsap/gsap' . JUPITERX_MIN_JS . '.js', [], '1.19.1', true );
	wp_enqueue_script( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/js/jupiterx-modal' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
	wp_enqueue_script( 'jupiterx-activation-popup', JUPITERX_ASSETS_URL . 'dist/js/activation-popup' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );

	$jupiter              = wp_get_theme( 'jupiter' );
	$jupiter_is_installed = false;

	if ( $jupiter->exists() ) {
		$jupiter_is_installed = true;
	}

	wp_localize_script( 'jupiterx-activation-popup', 'jupiterx_activation_popup', [
		'important_note'                     => __( 'Important Note', 'jupiterx' ),
		'jupiter_usage_title'                => __( 'It looks like you used Jupiter before.', 'jupiterx' ),
		'jupiter_usage_message'              => __( 'We will support and update Jupiter as before, so you can keep using it safely. But in case you want to upgrade from Jupiter to Jupiter X please keep in mind that some re-adjustments or content recreation might be required.', 'jupiterx' ),
		'learn_more'                         => __( 'Learn More', 'jupiterx' ),
		'learn_more_link_title'              => __( 'Upgrading Jupiter Theme to Version X', 'jupiterx' ),
		'activate_jupiter'                   => __( 'Activate Jupiter', 'jupiterx' ),
		'activate_jupiter_message'           => __( 'This will deactivate Jupiter X theme and activate the Jupiter theme which is already installed. Are you sure you want to do it?', 'jupiterx' ),
		'install_activate_jupiter'           => __( 'Install and activate Jupiter ', 'jupiterx' ),
		'install_activate_jupiter_message_1' => __( 'Unzip the ThemeForest Downloaded package and locate Jupiter folder. <strong>jupiter-main-package > jupiter</strong>', 'jupiterx' ),
		'install_activate_jupiter_message_2' => __( 'Upload Jupiter zip file and activate: <strong>Dashboard > Appearance  > Themes</strong>', 'jupiterx' ),
		// Buttons.
		'take_me_back'                       => __( 'Take me back to Jupiter', 'jupiterx' ),
		'fresh_start'                        => __( 'Fresh start with Jupiter X', 'jupiterx' ),
		'done'                               => __( 'Done', 'jupiterx' ),
		'discard'                            => __( 'Discard', 'jupiterx' ),
		'activate_jupiter_btn'               => __( 'Activate Jupiter 6', 'jupiterx' ),
		'images_url'                         => JUPITERX_API_URL . 'onboarding/assets/img/',
		'jupiter_installed'                  => $jupiter_is_installed,
	] );
}
