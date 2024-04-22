<div class="certificate_footer" v-if="typeof certificates[currentCertificate] !== 'undefined' && typeof certificates[currentCertificate].id !== 'undefined'">
	<div class="certificate_buttons">
		<select v-model="certificates[currentCertificate].data.category">
			<option><?php esc_html_e( 'Choose course category', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
			<option value="entire_site"><?php esc_html_e( 'Entire site', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
			<option v-for="category in categories" v-bind:value="category.id" v-html="category.name"></option>
		</select>
		<a v-if="certificates[currentCertificate].id" href="#" class="button button-preview stm_preview_certificate" :data-id="certificates[currentCertificate].id">
			<i class="far fa-eye"></i>
			<?php esc_html_e( 'Preview', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>
		<a href="#" class="button button-primary" @click.prevent="saveCertificate()">
			<?php esc_html_e( 'Save changes', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>
	</div>
</div>
