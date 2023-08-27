<div id="wrap" class="wrap">
	<h1></h1>
	<div class="jupiterx-cp-wrap jupiterx-wrap jupiterx">
		<?php include_once( JUPITERX_CONTROL_PANEL_PATH . '/views/layout/header.php' ); ?>
		<div class="jupiterx-cp-container">
			<?php include_once( JUPITERX_CONTROL_PANEL_PATH . '/views/layout/sidebar.php' ); ?>
			<div class="jupiterx-cp-panes">
				<?php jupiterx_control_panel()->print_pane(); ?>
			</div>
		</div>
	</div>
</div>
