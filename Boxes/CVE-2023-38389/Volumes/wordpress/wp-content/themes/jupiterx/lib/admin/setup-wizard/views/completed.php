<?php
/**
 * Template for setup complete.
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="jupiterx-notice">
	<?php esc_html_e( 'Your website is all set and ready to go. You can now customise your website and add your content to it. Here are some useful info you may need along the way.', 'jupiterx' ); ?>
</div>
<div class="jupiterx-container container">
	<div class="row">
		<div class="col-6">
			<h6><?php esc_html_e( 'More Help:', 'jupiterx' ); ?></h6>
			<ul class="jupiterx-help-links list-unstyled">
				<li><a href="https://www.youtube.com/watch?v=fnlzOHECEDo" target="_blank" class="text-secondary"><i class="fa fa-flag-checkered"></i> <?php esc_html_e( 'How to get started', 'jupiterx' ); ?></a></li>
				<li><a href="https://themes.artbees.net/support/jupiterx/videos/" target="_blank" class="text-secondary"><i class="fa fa-video"></i> <?php esc_html_e( 'Video tutorials', 'jupiterx' ); ?></a></li>
				<li><a href="https://themes.artbees.net/docs/getting-started" target="_blank" class="text-secondary"><i class="fa fa-book"></i> <?php esc_html_e( 'Documentation', 'jupiterx' ); ?></a></li>
				<li class="jupiterx-help-link-image-icon"><a href="https://www.youtube.com/watch?v=-TPpwuB6dnI" target="_blank" class="text-secondary"><img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'setup-wizard/assets/images/elementor-icon.png' ); ?>" alt="<?php esc_attr_e( 'Elementor icon', 'jupiterx' ); ?>" width="16" height="16"> <?php esc_html_e( 'Get started with Elementor', 'jupiterx' ); ?></a></li>
				<?php if ( jupiterx_is_premium() ) : ?>
					<li><a href="https://themes.artbees.net/support/" target="_blank" class="text-secondary"><i class="fa fa-life-ring"></i> <?php esc_html_e( 'Support', 'jupiterx' ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="col-6 jupiterx-setup-wizard-mailchimp-box">
			<h6><?php esc_html_e( 'Don\'t miss an update', 'jupiterx' ); ?></h6>
			<ul class="jupiterx-social-links">
				<li><a href="https://www.facebook.com/artbees" target="_blank"><i class="fab fa-facebook"></i> <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'jupiterx' ); ?></span></a></li>
				<li><a href="https://twitter.com/jupiterxwp" target="_blank"><i class="fab fa-twitter"></i> <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'jupiterx' ); ?></a></span></li>
				<li><a href="https://instagram.com/artbees" target="_blank"><i class="fab fa-instagram"></i> <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'jupiterx' ); ?></span></a></li>
			</ul>
		</div>
	</div>
</div>
<div class="jupiterx-form text-center">
	<a class="btn btn-primary" href="<?php echo esc_url( add_query_arg( 'return', rawurlencode( admin_url() ), admin_url( 'customize.php' ) ) ); ?>"><?php esc_html_e( 'Customize your website', 'jupiterx' ); ?></a>
</div>
