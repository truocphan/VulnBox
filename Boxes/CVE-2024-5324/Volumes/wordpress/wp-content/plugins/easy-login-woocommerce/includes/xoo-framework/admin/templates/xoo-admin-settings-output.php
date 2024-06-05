<?php
	
$options = apply_filters( 'xoo_aff_export_options', $adminObj->tabs, $adminObj->helper->slug );


?>

<div class="xoo-settings-container">

	<ul class="xoo-sc-tabs">
		<?php foreach( $tabs as $tab_id => $tab_data ): ?>
			<li data-tab="<?php echo esc_attr( $tab_id ); ?>" <?php if( $tab_data['pro'] === 'yes' ) echo 'class="xoo-as-is-pro"'; ?>><?php echo esc_html( $tab_data['title'] ); ?></li>
		<?php endforeach; ?>
	</ul>

	<form class="xoo-as-form">

		<?php foreach( $tabs as $tab_id => $tab_data ): ?>
			<div class="xoo-sc-tab-content <?php if( $tab_data['pro'] === 'yes' ) echo 'xoo-as-is-pro'; ?>" data-tab="<?php echo esc_attr( $tab_id ); ?>">
				<?php do_action( 'xoo_tab_page_start', $tab_id, $tab_data ); ?>
				<?php $adminObj->create_settings_html( $tab_id ); ?>
				<?php do_action( 'xoo_tab_page_end', $tab_id, $tab_data ); ?>
			</div>
		<?php endforeach; ?>

		<div class="xoo-sc-bottom-btns">
			<?php if( $hasPRO ): ?>
				<a class="xoo-as-pro-toggle">Show Pro options</a>
				<a class="xoo-as-pro-toggle xoo-aspt-two">Hide Pro options</a>
			<?php endif; ?>
			<button type="submit" class="xoo-as-form-save">Save</button>
			<a class="xoo-as-form-reset" href="<?php echo esc_url( add_query_arg( 'reset', wp_create_nonce('reset') ) ) ?>">Reset</a>
			<div class="xoo-as-exim">
				<div class="xoo-as-eximbtns" style="display: none;">
					<span class="xoo-as-setexport" >Export Settings</span>
					<span class="xoo-as-setimport">Import Settings</span>
				</div>
				<span class="dashicons dashicons-move"></span>
			</div>
		</div>

	</form>

	<div class="xoo-as-modal">

		<div class="xoo-as-expimmodal">
			
			<div class="xoo-as-emod-cont">

				<span class="xoo-as-exipclose">X</span>

				<div class="xoo-as-excont">

					<div class="xoo-as-exoptions">

						<span>Export settings</span>

						<div class="xoo-as-expcheck">
							<?php

							foreach ( $options as $id => $data ) {
								if( !$data['option_key'] ) continue;
								?>
								<label>
									<?php esc_html_e( $data['title'] ); ?>
									<input type="checkbox" value="<?php echo esc_attr( $data['option_key'] ) ?>" checked>
								</label>
								<?php
							}

							?>
						</div>

						<i>Any unsaved changed will not be exported. Please make sure to save settings.</i>
					

						<button class="xoo-as-run-export">Export</button>

					</div>

					<div class="xoo-as-expdone">

						<b>Copy the value below and paste it into the 'Import settings' feature on the website you want to import it to</b>
						<i>Files/Image upload settings need to be done manually.</i>

						<textarea rows="10"></textarea>

					</div>
				</div>

				<div class="xoo-as-impcont">
					<b>Paste the copied value here.</b>
					<i>Files/Image upload settings need to be done manually.</i>
					<textarea rows="10"></textarea>
					<button class="xoo-as-run-import">Run Import</button>
					<span class="xoo-as-imported">Import Completed. Refreshing....</span>
				</div>
			</div>
		</div>
	</div>
</div>