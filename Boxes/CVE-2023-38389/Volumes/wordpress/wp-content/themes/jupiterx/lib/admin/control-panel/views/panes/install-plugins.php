<?php
if ( ! JUPITERX_CONTROL_PANEL_PLUGINS ) {
	return;
}
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-plugins">
	<div class="jupiterx-cp-message">
	<h4><?php
		printf(
			__( 'Please <a href="%s">install/update</a> "Jupiter X Core" plugin to enable this feature.', 'jupiterx' ),
			esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) )
		);
	?></h4>
	</div>
</div>
