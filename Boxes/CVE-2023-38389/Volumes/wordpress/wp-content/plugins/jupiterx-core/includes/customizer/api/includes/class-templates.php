<?php
/**
 * This class handles printing custom templates in Customizer preview.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print custom templates.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Core_Customizer_Templates {

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'render_templates' ], 0 );
	}

	/**
	 * Print templates in Customizer page.
	 *
	 * @since 1.0.0
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	public function render_templates() {
		?>
		<script type="text/html" id="tmpl-customize-jupiterx-popup-content">
			<div id="customize-jupiterx-popup-content" class="jupiterx-popup">
				<div id="customize-jupiterx-popup-controls" class="jupiterx-popup-container"></div>
			</div>
		</script>

		<script type="text/html" id="tmpl-customize-jupiterx-exceptions-control-group">
			<div class="jupiterx-exceptions-control-group">
				<h3>{{{ data.text }}}</h3>
				<button class="jupiterx-exceptions-control-remove jupiterx-button jupiterx-button-outline jupiterx-button-danger jupiterx-button-small" data-id="{{ data.id }}"><?php esc_html_e( 'Remove', 'jupiterx-core' ); ?></button>
				<ul class="jupiterx-row jupiterx-group-controls"></ul>
			</div>
		</script>

		<script type="text/html" id="tmpl-customize-jupiterx-pro-preview-lightbox">
			<div class="jupiterx-pro-preview">
				<div class="jupiterx-pro-preview-header">
					<a class="jupiterx-pro-preview-back" href="#"><span class="jupiterx-icon-arrow-left-solid"></span> <?php esc_html_e( 'Back', 'jupiterx-core' ); ?></a>
					<?php if ( jupiterx_is_premium() ) : ?>
						<span>
							<span class="jupiterx-pro-preview-modal-description"><?php esc_html_e( 'Activate Jupiter X to unlock this feature', 'jupiterx-core' ); ?></span>
							<a href="<?php echo esc_attr( jupiterx_upgrade_link( 'customizer' ) ); ?>" class="jupiterx-pro-preview-upgrade jupiterx-upgrade-modal-trigger" target="_blank"><?php esc_html_e( 'Activate Now', 'jupiterx-core' ); ?></a>
						</span>
					<?php else : ?>
						<a class="jupiterx-pro-preview-upgrade" href="<?php echo esc_attr( jupiterx_upgrade_link( 'customizer' ) ); ?>" target="_blank"><?php esc_html_e( 'Upgrade to Jupiter X Pro', 'jupiterx-core' ); ?></a>
					<?php endif; ?>
				</div>
				<div class="jupiterx-pro-preview-content">
					<div class="jupiterx-pro-preview-container">
						<# if ( data.preview ) { #>
							<img class="jupiterx-pro-preview-image" src="{{ data.preview }}" />
						<# } #>
					</div>
				</div>
			</div>
		</script>
		<?php
	}
}

// Initialize.
new JupiterX_Core_Customizer_Templates();
