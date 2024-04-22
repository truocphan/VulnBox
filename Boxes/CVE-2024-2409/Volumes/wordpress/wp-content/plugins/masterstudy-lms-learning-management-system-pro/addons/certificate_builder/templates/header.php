<div class="certificate_header" v-if="typeof certificates[currentCertificate] !== 'undefined' && typeof certificates[currentCertificate].id !== 'undefined'">
	<h1><?php esc_html_e( 'Certificate Builder', 'masterstudy-lms-learning-management-system-pro' ); ?></h1>
	<div class="certificate_buttons">
		<a v-if="certificates[currentCertificate].id" href="#" class="button button-preview stm_preview_certificate" :data-id="certificates[currentCertificate].id">
			<i class="far fa-eye"></i>
			<?php esc_html_e( 'Preview', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>
		<a href="#" class="button button-primary" @click.prevent="saveCertificate()">
			<?php esc_html_e( 'Save changes', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>
		<div v-if="saved" class="saved">
			<?php esc_html_e( 'Saved!', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</div>
	</div>
</div>
