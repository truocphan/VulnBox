<?php
defined( 'ABSPATH' ) || die();
?>

<script type="text/template" id="tmpl-raven-panel-global-widget">
	<div id="raven-global-widget-locked-header" class="raven-nerd-box raven-panel-nerd-box">
		<img class="raven-nerd-box-icon" src="<?php echo ELEMENTOR_ASSETS_URL . 'images/information.svg'; ?>" />
		<div class="raven-nerd-box-title raven-panel-nerd-box-title"><?php echo esc_html__( 'Your Widget is Now Locked', 'jupiterx-core' ); ?></div>
		<div class="raven-nerd-box-message raven-panel-nerd-box-message"><?php echo esc_html__( 'Edit this global widget to simultaneously update every place you used it, or unlink it so it gets back to being regular widget.', 'jupiterx-core' ); ?></div>
	</div>
	<div id="raven-global-widget-locked-tools">
		<div id="raven-global-widget-locked-edit" class="raven-global-widget-locked-tool">
			<div class="raven-global-widget-locked-tool-description"><?php echo esc_html__( 'Edit global widget', 'jupiterx-core' ); ?></div>
			<button class="raven-button raven-button-success"><?php echo esc_html__( 'Edit', 'jupiterx-core' ); ?></button>
		</div>
		<div id="raven-global-widget-locked-unlink" class="raven-global-widget-locked-tool">
			<div class="raven-global-widget-locked-tool-description"><?php echo esc_html__( 'Unlink from global', 'jupiterx-core' ); ?></div>
			<button class="raven-button raven-button-danger"><?php echo esc_html__( 'Unlink', 'jupiterx-core' ); ?></button>
		</div>
	</div>
	<div id="raven-global-widget-loading" class="elementor-hidden">
		<span class="raven-screen-only"><?php echo esc_html__( 'Loading...', 'jupiterx-core' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-raven-panel-global-widget-no-templates">
	<img src="<?php echo ELEMENTOR_ASSETS_URL . 'images/information.svg'; ?>" alt="Elementor Information Nerd Icon" />
	<div class="raven-nerd-box-title raven-panel-nerd-box-title"><?php echo esc_html__( 'Save Your First Global Widget', 'jupiterx-core' ); ?></div>
	<div class="raven-nerd-box-message raven-panel-nerd-box-message"><?php echo esc_html__( 'Save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'jupiterx-core' ); ?></div>
</script>
