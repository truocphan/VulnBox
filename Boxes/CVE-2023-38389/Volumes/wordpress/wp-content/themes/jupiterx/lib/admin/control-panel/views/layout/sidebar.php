<?php
$sysinfo_warnings = [];

if ( class_exists('JupiterX_Control_Panel_System_Status') ) {
	$sysinfo_warnings = JupiterX_Control_Panel_System_Status::compile_system_status_warnings();
}

if ( class_exists('JupiterX_Control_Panel_Updates_Downgrades') ) {
	$new_update  = new JupiterX_Control_Panel_Updates_Downgrades();
	$new_update_available = $new_update->is_new_update_available();
}

$sections = jupiterx_control_panel()->get_sections();

$default = reset( $sections );
?>

<div class="jupiterx-cp-sidebar">
	<ul class="jupiterx-cp-sidebar-list">
	<?php foreach( $sections as $key => $section ) : ?>
		<?php
			$extra_classes = $default['href'] === $section['href'] ? 'jupiterx-is-active' : '';

			if ( ! $section['condition'] ) {
				continue;
			}
		?>
		<li class="jupiterx-cp-sidebar-list-items <?php echo esc_attr( $extra_classes ); ?>">
			<a class="jupiterx-cp-sidebar-link" href="#<?php echo esc_attr( $section['href'] ); ?>">
				<?php echo esc_html( $section['title'] ); ?>
				<?php if ( 'home' === $key && ! jupiterx_is_registered() && jupiterx_is_premium() ): ?>
					<img class="jupiterx-premium-warning-badge" src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/warning-badge.svg' ); ?>" title="<?php _e( 'Activate Product', 'jupiterx' ); ?>" alt="<?php esc_attr_e( 'Warning icon', 'jupiterx' ); ?>" width="16" height="16"/>
				<?php endif; ?>
				<?php if ( 'system_status' === $key && count( $sysinfo_warnings ) > 0 ): ?>
					<img class="jupiterx-premium-warning-badge" src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/warning-badge.svg' ); ?>" title="<?php _e( 'Resolve issues', 'jupiterx' ); ?>" alt="<?php esc_attr_e( 'Warning icon', 'jupiterx' ); ?>" width="16" height="16"/>
				<?php endif; ?>
				<?php if ( 'updates' === $key && $new_update_available ): ?>
					<img class="jupiterx-premium-warning-badge" src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/warning-badge.svg' ); ?>" title="<?php _e( 'Update Available', 'jupiterx' ); ?>" alt="<?php esc_attr_e( 'Warning icon', 'jupiterx' ); ?>" width="16" height="16"/>
				<?php endif; ?>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
