<?php
/**
 * Template for plugins activation.
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="jupiterx-notice">
	<?php esc_html_e( 'This will add essential plugins needed for Jupiter X to work properly.', 'jupiterx' ); ?><br />
	<?php esc_html_e( 'You can add/remove plugins later from control panel.', 'jupiterx' ); ?>
</div>
<div class="jupiterx-form">
	<?php $theme_plugins = jupiterx_setup_wizard()->get_plugins_list(); ?>
	<?php if ( ! empty( $theme_plugins ) ) : ?>
	<div class="jupiterx-plugins-list">
		<?php foreach ( $theme_plugins as $theme_plugin ) { ?>
			<?php $required = 'true' === $theme_plugin->required ? true : false; ?>
			<?php $pro = 'true' === $theme_plugin->pro ? true : false; ?>
			<?php $required = 'jupiterx-pro' === $theme_plugin->slug && ( jupiterx_is_premium() || jupiterx_is_pro() ) ? true : $required; ?>
			<?php $extra_class = $pro && ! jupiterx_is_pro() && jupiterx_is_premium() ? 'jupiterx-upgrade-modal-trigger' : ''; ?>
			<?php $disabled = $pro && ! jupiterx_is_premium() ? 'disabled="disabled"' : ''; ?>
			<div class="custom-control custom-checkbox <?php echo esc_attr( $extra_class ); ?>">
				<input type="checkbox" class="custom-control-input" name="jupiterx-plugins" value="<?php echo esc_attr( $theme_plugin->slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" id="jupiterx-plugin-<?php echo esc_attr( $theme_plugin->slug ); ?>" <?php echo esc_attr( $required ? 'disabled="disabled" checked="checked"' : '' ); ?>>
				<label class="custom-control-label" for="jupiterx-plugin-<?php echo esc_attr( $theme_plugin->slug ); ?>">
					<?php echo wp_kses_post( $theme_plugin->name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</label>
				<?php
					if ( $pro && 'jupiterx-pro' !== $theme_plugin->slug ) {
						jupiterx_pro_badge();
					}
				?>
			</div>
		<?php } ?>
	</div>
	<?php endif; ?>
	<div class="text-center">
		<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Install Plugins', 'jupiterx' ); ?></button>
	</div>
</div>
<div class="jupiterx-skip-wizard">
	<a href="#" class="jupiterx-skip-link jupiterx-next"><?php esc_html_e( 'Skip this step', 'jupiterx' ); ?></a>
</div>
