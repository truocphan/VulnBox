<div id="certificate_builder" v-bind:class="loaded ? 'loaded' : ''" style="opacity: 0;">
	<?php
	require_once STM_LMS_PRO_ADDONS . '/certificate_builder/templates/header.php';
	?>
	<div class="controls-wrap">
		<div v-bind:class="loading ? 'preloader loading' : 'preloader'"></div>
		<?php
		require_once STM_LMS_PRO_ADDONS . '/certificate_builder/templates/certificates.php';
		require_once STM_LMS_PRO_ADDONS . '/certificate_builder/templates/controls.php';
		require_once STM_LMS_PRO_ADDONS . '/certificate_builder/templates/canvas.php';
		?>
	</div>

	<?php
	require_once STM_LMS_PRO_ADDONS . '/certificate_builder/templates/footer.php';
	?>
</div>
