<?php
/**
 * Welcome Page template.
 *
 * @since 1.21.0
 *
 * @package JupiterX\Framework\Admin\Weclcome
 */

?>
<div class="jupiterx-welcome-wrapper">
	<div class="jupiterx-welcome">
		<div class="jupiterx-welcome-header">
			<div class="jupiterx-welcome-title">
				<?php
					/* translators: %s: Jupiter X version */
					echo esc_html( sprintf( __( 'Welcome to Jupiter X v%s', 'jupiterx' ), JUPITERX_VERSION ) );
				?>
			</div>
			<div class="jupiterx-welcome-desc">
				<?php esc_html_e( 'Thanks for installing Jupiter X WordPress theme! Jupiter X is a fast, light and powerful WordPress theme for building all kinds of websites. Everything in your website, from header and footer to your custom post types and woo-commerce store, is now customizable thanks to Jupiter X.', 'jupiterx' ); ?>
			</div>
		</div>
		<div class="jupiterx-welcome-rplugins">
			<div class="jupiterx-welcome-rplugins-title">
				<?php esc_html_e( 'To complete the installation and unlock all Jupiter X features, install and activate these plugins:', 'jupiterx' ); ?>
			</div>
			<div class="jupiterx-welcome-rplugins-progress">
				<span role="status" aria-hidden="true" class="jupiterx-welcome-spinner-border"></span>
			</div>
			<div class="jupiterx-welcome-rplugins-error">
				<div role="alert"><?php esc_html_e( 'Could not connect to Artbees server, please try again and if the issue persists contact support', 'jupiterx' ); ?></div>
			</div>
			<div class="jupiterx-welcome-rplugins-list" data-nonce="<?php echo esc_attr( wp_create_nonce( 'jupiterx_get_welcome_inactive_required_plugins' ) ); ?>">
				<!-- PLUGINS WILL BE INSERTED HERE -->
			</div>
			<div class="jupiterx-welcome-rplugins-failed">
				<div class="welcome-plugin-error jupiterx-welcome-rplugins-error">
					<div role="alert"><!-- PLUGINS ERRORS WILL BE INSERTED HERE --></div>
				</div>
			</div>
			<div class="jupiterx-welcome-rplugins-action">
				<button class="jupiterx-welcome-rplugins-action-btn">
					<span role="status" aria-hidden="true" class="jupiterx-welcome-spinner-border jupiterx-welcome-spinner-border-sm-white"></span>
					<span><?php esc_html_e( 'Install and activate all required plugins', 'jupiterx' ); ?></span>
				</button>
			</div>
		</div>
	</div>
</div>
