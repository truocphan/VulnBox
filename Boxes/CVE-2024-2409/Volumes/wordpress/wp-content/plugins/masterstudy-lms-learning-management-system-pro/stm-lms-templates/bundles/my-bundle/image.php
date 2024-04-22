<div class="stm_lms_my_bundle__image">

	<h4 class="stm_lms_my_bundle__label">
		<?php esc_html_e( 'Bundle image', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</h4>

	<div class="stm_lms_bundle_upload_image" v-if="!bundle_image_id">

		<div class="stm_lms_bundle_upload_image__to_upload" v-if="typeof bundle_upload_image.name !== 'undefined'">
			{{bundle_upload_image.name}}
			<i class="lnricons-cross" @click="bundle_upload_image = ''"></i>
		</div>

		<div class="stm_lms_bundle_upload_image__upload_new" v-else>

			<span class="heading_font">
				<i class="lnricons-cloud-upload"></i>
				<?php esc_html_e( 'Upload image', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>

			<input type="file" ref="bundleImage" class="form-control" @change="previewFiles"/>

		</div>

	</div>

	<div class="stm_lms_bundle_upload_image__to_upload" v-else>
		{{bundle_image_id}}
		<i class="lnricons-cross" @click="bundle_image_id = ''"></i>
	</div>

</div>
