<div class="xoo-aff-settings-modal">

	<div class="xoo-aff-settings-topbar">
		<div class="xoo-aff-notice-holder"></div>
		<button class="xoo-aff-add-field"><span class="fas fa-plus-circle"></span>Add Field</button>
		<button id="xoo-aff-save"><span class="fas fa-save"></span>Save</button>
		<form class="xoo-aff-form-save" method="POST" style="display: none;"></form>
		<button class="xoo-aff-reset-field"><span class="fas fa-sync"></span>Reset</button>
	</div>
	
	<div class="xoo-aff-settings-container">
		<div class="xoo-aff-sidebar">
			<?php echo wp_kses_post( $sidebar_template ); ?>
			<div class="xoo-aff-field-settings-container"></div>
		</div>

		<ul class="xoo-aff-main"></ul>
	</div>

</div>