<?php
/**
 * Template for API activation.
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( jupiterx_get_option( 'api_key' ) ) ) :
?>
<div class="jupiterx-notice">
	<?php esc_html_e( 'By activating Jupiter, you will be able to download hundreds of free templates, contact one on one support, install key plugins, get constant updates and much more.', 'jupiterx' ); ?>
</div>
<div class="jupiterx-form">
	<div class="form-inline">
		<input type="text" class="jupiterx-form-control" placeholder="<?php esc_html_e( 'Enter your API key', 'jupiterx' ); ?>" />
		<button type="submit" class="btn btn-primary jupiterx-activate"><?php esc_html_e( 'Activate', 'jupiterx' ); ?></button>
	</div>
</div>
<div class="jupiterx-help-button">
	<a href="https://themes.artbees.net/docs/getting-an-api-key" class="btn jupiterx-btn-info" target="_blank">
		<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'setup-wizard/assets/images/question-mark.svg' ); ?>" alt="Help icon" width="16" height="16">
		<span><?php esc_html_e( 'How to get API key', 'jupiterx' ); ?></span>
	</a>
</div>
<div class="jupiterx-skip-wizard">
	<a href="#" class="jupiterx-skip-link jupiterx-next"><?php esc_html_e( 'Skip this step', 'jupiterx' ); ?></a>
</div>
<?php else : ?>
<div class="jupiterx-notice">
	<?php esc_html_e( 'You have already entered an API key. Please proceed to the next step.', 'jupiterx' ); ?>
</div>
<div class="jupiterx-form">
	<div class="text-center">
		<button type="submit" class="btn btn-primary jupiterx-next"><?php esc_html_e( 'Continue', 'jupiterx' ); ?></button>
	</div>
</div>
<div class="jupiterx-skip-wizard">
	<a href="#" class="jupiterx-skip-link jupiterx-next"><?php esc_html_e( 'Skip this step', 'jupiterx' ); ?></a>
</div>
<?php endif; ?>
