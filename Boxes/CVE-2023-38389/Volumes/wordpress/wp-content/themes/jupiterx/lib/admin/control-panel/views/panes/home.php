<?php
if ( ! JUPITERX_CONTROL_PANEL_HOME ) {
	return;
}

$api_key = jupiterx_get_option( 'api_key' );
$is_apikey = empty( $api_key ) ? false : true;
$has_api_key = empty( $api_key ) ? 'd-none' : '';
$no_api_key = empty( $has_api_key ) ? 'd-none' : '';
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-home">
	<?php
	if ( ! jupiterx_setup_wizard()->is_notice_hidden() ) :
	?>
	<div class="alert alert-secondary jupiterx-setup-wizard-message" role="alert">
		<div class="row align-items-center">
			<div class="col-md-8">
				<p><?php esc_html_e( 'This wizard helps you configure your new website quick and easy.', 'jupiterx' ); ?></p>
			</div>
			<div class="col-md-4">
				<div class="text-right">
					<a class="btn btn-success" href="<?php echo jupiterx_setup_wizard()->get_url(); ?>"><?php esc_html_e( 'Run Setup Wizard', 'jupiterx' ); ?></a>
					<button class="btn btn-outline-secondary jupiterx-setup-wizard-hide-notice"><?php esc_html_e( 'Discard', 'jupiterx' ); ?></button>
				</div>
			</div>
		</div>
	</div>
	<?php
	endif;

	if ( ! jupiterx_is_premium() ) : ?>
		<div class="jupiterx-pro-banner">
			<i class="jupiterx-icon-pro"></i>
			<h1><?php esc_html_e( 'Upgrade to Jupiter X Pro', 'jupiterx' ); ?></h1>
			<a href="<?php echo esc_attr( jupiterx_upgrade_link( 'banner' ) ); ?>" target="_blank" class="btn btn-primary"><?php esc_html_e( 'Upgrade Now', 'jupiterx' ); ?></a>
			<div class="features">
				<ul>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Shop customizer', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Custom Header and Footer', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Blog and Portfolio customizer', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Premium plugins', 'jupiterx' ); ?></span>
					</li>
				</ul>
				<ul>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'More elementor elements', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Block and page templates', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Premium support', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( '280+ pre-made website templates', 'jupiterx' ); ?></span>
					</li>
				</ul>
				<ul>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Premium Slideshows', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Adobe fonts', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<?php esc_html_e( 'Advanced tracking options', 'jupiterx' ); ?>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<?php esc_html_e( 'And much more...', 'jupiterx' ); ?>
					</li>
				</ul>
			</div>
		</div>
	<?php
		else :
	?>
		<div class="get-api-key-form <?php echo esc_attr( $no_api_key ); ?>">
			<h3 class="heading-with-icon icon-lock">
				<?php esc_html_e( 'Activate Product', 'jupiterx' ); ?>
			</h3>
			<div class="jupiterx-callout bd-callout-danger mb-4 ml-0">
				<h4>
					<span class="dashicons dashicons-warning"></span>
					<?php esc_html_e( 'Almost Done! Please register Jupiter X to activate its features.', 'jupiterx' ); ?>
				</h4>
				<p>
					<?php esc_html_e( 'By registering Jupiter X you will be able to download hundreds of free templates, contact one on one support, install key plugins, get constant updates and unlock more feature.', 'jupiterx' ); ?>
					<a href="https://themes.artbees.net/docs/registering-the-theme" target="_blank"><?php esc_html_e( 'Learn how>>', 'jupiterx' ); ?></a>
				</p>
			</div>
			<div class="form-group mb-5">
				<button class="btn btn-primary js__activate-product d-inline-block" id="js__regiser-api-key-btn"><?php esc_html_e( 'Activate Product', 'jupiterx' ); ?></button>
				<a href="https://themes.artbees.net/docs/registering-the-theme/" class="btn jupiterx-btn-info d-inline-block" target="_blank">
					<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'setup-wizard/assets/images/question-mark.svg' ); ?>" width="16" height="16">
					<span><?php esc_html_e( 'How to get API key', 'jupiterx' ); ?></span>
				</a>
			</div>
		</div>
	<?php
		endif;
	?>
	<div class="remove-api-key-form mb-5 <?php echo esc_attr( $has_api_key ); ?>">
		<h3 class="heading-with-icon icon-checkmark mb-4">
			<?php echo esc_html( JUPITERX_NAME ); ?>
			<?php esc_html_e( 'is Activated', 'jupiterx' ); ?>
		</h3>
		<?php wp_nonce_field( 'jupiterx-cp-ajax-register-api', 'security' ); ?>
		<?php
			$revoking_mode = 'api';
			$jupiterx_license_manager = jupiterx_license_manager();
			if ( $jupiterx_license_manager->get_purchase_code() ) {
				$revoking_mode = 'purchase_code';
			}
		?>
		<button class="btn btn-primary js__deactivate-product" id="js__revoke-api-key-btn" href="#" data-revoking-mode="<?php echo esc_attr( $revoking_mode ); ?>"><?php esc_html_e( 'Deactivate Product', 'jupiterx' ); ?></button>
		<?php wp_nonce_field( 'license_manager', 'license-manager-nonce' ); ?>
	</div>
	<?php
	?>

	<div class="row jupiterx-cp-help-section">
		<div class="col">
			<h3 class="heading-with-icon icon-learn"><?php esc_html_e( 'Learn', 'jupiterx' ); ?></h3>
			<?php do_action( 'jupiterx_control_panel_get_started' ); ?>
			<a class="btn btn-primary js__deactivate-product mb-4" href="https://themes.artbees.net/docs/getting-started" target="_blank"><?php esc_html_e( 'Get Started Guide', 'jupiterx' ); ?></a>
			<h6><?php esc_html_e( 'Learn deeper:', 'jupiterx' ); ?></h6>
			<ul class="list-unstyled d-inline-block">
				<li><a class="list-with-icon icon-video" target="_blank" href="https://themes.artbees.net/support/jupiterx/videos/"><?php esc_html_e( 'Video Tutorials', 'jupiterx' ); ?></a></li>
				<li><a class="list-with-icon icon-docs" target="_blank" href="https://themes.artbees.net/docs/getting-help-from-the-artbees-support/"><?php esc_html_e( 'Articles', 'jupiterx' ); ?></a></li>
			</ul>
			<ul class="list-unstyled d-inline-block">
				<li><a class="list-with-icon icon-community" target="_blank" href="https://themes.artbees.net/dashboard/new-topic/"><?php esc_html_e( 'Ask a question', 'jupiterx' ); ?></a></li>
				<li><a class="list-with-icon icon-history" target="_blank" href="<?php echo jupiterx_is_premium() ? 'https://themes.artbees.net/support/jupiterx/release-notes/' : 'https://themes.artbees.net/support/jupiterx-lite-release-notes/'; ?>"><?php esc_html_e( 'Release History', 'jupiterx' ); ?></a></li>
			</ul>
		</div>
		<div class="col">
			<div class="mb-5">
				<h3 class="heading-with-icon icon-comments-solid-lightgrey"><?php esc_html_e( 'Support', 'jupiterx' ); ?></h3>
				<p><?php esc_html_e( 'Got any questions? Ask away and we will get back to you shortly.', 'jupiterx' ); ?></p>
				<a class="btn btn-secondary" href="https://themes.artbees.net/dashboard/new-topic" target="_blank"><?php esc_html_e( 'Contact Support', 'jupiterx' ); ?></a>
			</div>
			<div>
				<h3 class="heading-with-icon icon-download"><?php esc_html_e( 'Start with a Template', 'jupiterx' ); ?></h3>
				<p><?php esc_html_e( 'Save time by choosing among beautiful templates designed for different sectors and purposes.', 'jupiterx' ); ?></p>
				<a class="btn btn-secondary jupiterx-cpanel-link" href="#install-templates"><?php esc_html_e( 'Import a Template', 'jupiterx' ); ?></a>
			</div>
		</div>
	</div>
</div>
