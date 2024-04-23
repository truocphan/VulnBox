<?php
/**
 * Connect to InstaWP Screen
 */

$error_title   = esc_html__( 'We have removed support for local sites for now.', 'instawp-connect' );
$error_message = esc_html__( 'You may use a third party backup and restore plugin such as WP Vivid or Everest Backup.', 'instawp-connect' );

if ( ! instawp()->can_bundle ) {
	$error_title   = esc_html__( 'We did not find either ZipArchive or Phardata for faster staging upload.', 'instawp-connect' );
	$error_message = esc_html__( 'Please ask your hosting provider to enable this support.', 'instawp-connect' );
} elseif ( instawp()->has_unsupported_plugins ) {
	$unsupported_plugins     = array_map( function ( $plugin ) {
		return isset( $plugin['name'] ) ? $plugin['name'] : '';
	}, InstaWP_Tools::get_unsupported_active_plugins() );
	$unsupported_plugins_str = implode( ', ', $unsupported_plugins );

	$error_title   = esc_html__( 'Unsupported plugins detected.', 'instawp-connect' );
	$error_message = sprintf( __( 'We have detected <strong class="underline">%s</strong> plugin(s) which is incompatible with our service currently. Please deactivate it to start the process (later you can activate it back).', 'instawp-connect' ), $unsupported_plugins_str );
}

?>

<div class="bg-white text-center rounded-md py-20 flex items-center justify-center">
    <div class="w-2/3">
        <div class="mb-4">
            <img src="<?php echo esc_url( instaWP::get_asset_url( 'migrate/assets/images/createsite.svg' ) ); ?>" class="mx-auto" alt="">
        </div>
        <div class="text-sm text-redCust-100 font-medium text-grayCust-200 mb-1"><?= $error_title ?></div>
        <div class="text-center inline-block text-sm font-normal text-grayCust-50 mb-4"><?= $error_message ?></div>
    </div>
</div>

