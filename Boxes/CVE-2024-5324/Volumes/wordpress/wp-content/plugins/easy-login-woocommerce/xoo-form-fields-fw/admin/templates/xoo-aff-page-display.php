<div class="xoo-tabs">
	<?php

	$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'fields';

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_id => $tab_data ) {
		$active = $current_tab == $tab_id ? 'nav-tab-active' : '';
		echo '<a class="nav-tab ' . esc_attr( $active ) . '" href="?page='. esc_attr( $admin_page_slug ) .'&tab=' . esc_attr( $tab_id ) . '">' . esc_html( $tab_data['title'] ) . '</a>';	
	}
	echo '</h2>';

	$option_name = $aff->admin->settings->get_option_key( $current_tab );

	?>
</div>

<div class="xoo-aff-container">

	<?php do_action( 'xoo_aff_admin_page_display_start', $admin_page_slug ); ?>

	<?php if( $current_tab === 'fields' ): ?>
		<?php $aff->admin->display_layout(); ?>
	<?php else: ?>

	<div class="xoo-main">

			<form method="post" action="options.php" class="xoo-aff-<?php echo esc_attr( $current_tab ); ?>-form-settings">
				<?php

					settings_fields( $option_name ); // Display Settings

					do_settings_sections( 'xoo-el-fields' ); // Display Sections

					submit_button( 'Save Settings' );	// Display Save Button
				?>			
			</form>

		<?php endif; ?>

	</div>


	<?php do_action( 'xoo_aff_admin_page_display_end', $admin_page_slug ); ?>

</div>

