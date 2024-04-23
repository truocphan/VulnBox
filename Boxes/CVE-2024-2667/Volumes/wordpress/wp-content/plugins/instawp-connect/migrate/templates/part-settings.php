<?php
/**
 * Migrate template - Settings
 */

?>

<div class="nav-item-content settings bg-white rounded-md p-6">
    <form class="instawp-form w-full">
        <div class="instawp-form-fields">
			<?php foreach ( array_values( InstaWP_Setting::get_migrate_settings() ) as $index => $section ) : ?>
				<?php InstaWP_Setting::generate_section( $section, $index ); ?>
			<?php endforeach; ?>
        </div>
        <div class="instawp-form-footer rounded-md bg-grayCust-400 p-3 mt-6 flex justify-between items-center">
            <div class="instawp-form-buttons flex gap-4">
				<?php if ( ! empty( InstaWP_Setting::get_api_key() ) ) { ?>
                    <button type="button" class="text-grayCust-500 py-3 px-5 border border-grayCust-350 text-sm font-medium rounded-md instawp-disconnect-plugin"><?php esc_html_e( 'Disconnect', 'instawp-connect' ); ?></button>
				<?php } ?>
                <p class="instawp-form-response loading flex items-center text-sm font-medium"></p>
            </div>
            <div class="instawp-form-buttons flex gap-4 items-center">
				<?php wp_nonce_field( 'instawp_settings_nonce_action', 'instawp_settings_nonce' ) ?>
                <span aria-label="<?= esc_html__( 'Plugin Version', 'instawp-connect' ); ?>" class="hint--top cursor-pointer text-sm text-primary-900 font-medium"><?= INSTAWP_PLUGIN_VERSION; ?></span>
                <button type="button" class="text-grayCust-500 py-3 px-5 border border-grayCust-350 text-sm font-medium rounded-md instawp-reset-plugin"><?php esc_html_e( 'Reset Plugin', 'instawp-connect' ); ?></button>
                <button type="submit" class="bg-primary-900 text-white py-3 px-5 text-sm font-medium rounded-md"><?php esc_html_e( 'Save Changes', 'instawp-connect' ); ?></button>
            </div>
        </div>
    </form>
</div>