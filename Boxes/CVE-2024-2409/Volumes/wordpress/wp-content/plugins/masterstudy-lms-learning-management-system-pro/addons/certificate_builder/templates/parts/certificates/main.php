<div class="certificates">
	<div class="certificate" v-for="(certificate, key) in certificates">
		<div class="certificate-id">{{certificate.id}}</div>
		<i class="fa fa-times-circle" @click="deleteCertificate(key)" title="<?php esc_attr_e( 'Delete certificate', 'masterstudy-lms-learning-management-system-pro' ); ?>"></i>
		<label class="certificate-label">
			<div class="certificate-image-wrap">
				<img v-if="typeof certificate.thumbnail !== 'undefined' && certificate.thumbnail" v-bind:src="certificate.thumbnail"/>
			</div>
			<div v-bind:class="currentCertificate === key ? 'certificate-info active' : 'certificate-info'">
				<input type="radio" v-model="currentCertificate" v-bind:value="key"/>
				<input type="text" class="certificate-title" v-model="certificate.title"/>
			</div>
		</label>
	</div>
	<div class="add_certificate" @click="addCertificate()">
		<div class="placeholder"><i class="fa fa-plus"></i></div>
		<div class="certificate-title"><?php esc_html_e( 'Create new', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
	</div>

</div>
