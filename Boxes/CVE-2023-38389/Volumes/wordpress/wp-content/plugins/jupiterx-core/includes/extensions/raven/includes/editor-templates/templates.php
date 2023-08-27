<?php
/**
 * Editor JS templates.
 *
 * @since 1.2.0
 *
 * @package JupiterX_Core\Raven
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-elementor-template-library-get-raven-pro-button">
		<?php if ( function_exists( 'jupiterx_is_premium' ) && jupiterx_is_premium() ) : ?>
			<a class="elementor-template-library-template-action elementor-button raven-go-pro-button jupiterx-upgrade-modal-trigger" href="#" target="_blank">
				<i class="jupiterx-icon-rocket-solid"></i>
				<span class="elementor-button-title">
					<?php esc_html_e( 'Activate to Unlock', 'jupiterx-core' ); ?>
				</span>
			</a>
		<?php else : ?>
			<a class="elementor-template-library-template-action elementor-button raven-go-pro-button" href="https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775?ref=artbees&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign" target="_blank">
				<i class="jupiterx-icon-rocket-solid"></i>
				<span class="elementor-button-title">
					<?php esc_html_e( 'Upgrade to unlock', 'jupiterx-core' ); ?>
				</span>
			</a>
		<?php endif; ?>
</script>
