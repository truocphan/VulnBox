<?php
/**
 * Template for setup introduction.
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="jupiterx-notice">
	<?php esc_html_e( 'This wizard helps you configure your new website quick and easy. We recommend you to use this wizard If you are new to Jupiter X. If you skip you can still come back anytime from control panel.', 'jupiterx' ); ?>
</div>
<div class="jupiterx-form text-center">
	<a href="<?php echo esc_url( admin_url() ); ?>" class="btn btn-outline-secondary"><?php esc_html_e( 'Do it later', 'jupiterx' ); ?></a>
	<button class="btn btn-primary jupiterx-next"><?php esc_html_e( 'Configure Jupiter now', 'jupiterx' ); ?></button>
</div>
