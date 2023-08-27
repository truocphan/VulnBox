<?php
/**
 * Template for selecting templates.
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( jupiterx_get_option( 'template_installed_id' ) ) :
?>

	<div class="jupiterx-notice">
		<?php esc_html_e( 'You have already installed a template. Please proceed to the next step.', 'jupiterx' ); ?>
	</div>
	<div class="jupiterx-form">
		<div class="text-center">
			<button type="submit" class="btn btn-primary jupiterx-next"><?php esc_html_e( 'Continue', 'jupiterx' ); ?></button>
		</div>
	</div>
	<div class="jupiterx-skip-wizard">
		<a href="#" class="jupiterx-skip-link jupiterx-next"><?php esc_html_e( 'Skip this step', 'jupiterx' ); ?></a>
	</div>

<?php elseif ( ! function_exists( 'jupiterx_core' ) ) : ?>

	<div class="jupiterx-notice">
		<?php
			printf(
				/* translators: TGMPA url */
				esc_html__( 'Please <a href="%s">install/update</a> "Jupiter X Core" plugin to enable this feature.', 'jupiterx' ),
				esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) )
			);
		?>
	</div>
	<div class="jupiterx-skip-wizard">
		<a href="#" class="jupiterx-skip-link jupiterx-next"><?php esc_html_e( 'Skip this step', 'jupiterx' ); ?></a>
	</div>

<?php else : ?>

	<div class="jupiterx-notice">
		<?php esc_html_e( 'Instead of starting from scratch you can import a pre-built website template to save time. To find the perfect match, search for any keyword or choose a category.', 'jupiterx' ); ?>
	</div>
	<div class="jupiterx-skip-wizard">
		<a href="#" class="jupiterx-skip-link jupiterx-next"><?php esc_html_e( 'Skip this step', 'jupiterx' ); ?></a>
	</div>

	<?php jupiterx_templates()->html(); ?>

<?php endif; ?>
