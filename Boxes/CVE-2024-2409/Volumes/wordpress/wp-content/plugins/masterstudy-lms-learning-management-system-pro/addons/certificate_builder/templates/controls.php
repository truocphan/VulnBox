<div class="stm-accordion open" v-if="typeof certificates[currentCertificate] !== 'undefined'">
	<div class="accordion-header">
		<?php esc_html_e( 'General', 'masterstudy-lms-learning-management-system-pro' ); ?>
		<i class="fa fa-chevron-down"></i>
	</div>
	<div class="accordion-body">
		<?php require_once STM_LMS_PRO_ADDONS . '/certificate_builder/templates/parts/controls/main.php'; ?>
	</div>
</div>
