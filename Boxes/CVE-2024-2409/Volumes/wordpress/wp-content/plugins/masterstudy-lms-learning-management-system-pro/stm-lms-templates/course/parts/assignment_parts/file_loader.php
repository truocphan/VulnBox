<?php
/**
 * @var $readonly
 */

$readonly = ( ! empty( $readonly ) ) ? $readonly : false;
?>

<div class="stm-lms-course__assignemnt-attachments" id="stm_lms_assignment_file_loader">

	<?php if ( ! $readonly ) : ?>
		<div class="stm-lms-course__assignemnt-attachments__new">
			<i class="fa fa-paperclip"></i>
			<span><?php esc_html_e( 'Attach files', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			<input type="file" ref="lms_group_csv" @change="handleFileUpload()" multiple/>
		</div>
	<?php endif; ?>

	<div class="stm-lms-course__assignemnt-attachments__files">

		<div class="stm-lms-course__assignemnt-attachments__file"
				v-for="(file, key) in files"
				v-if="file.data"
				v-bind:class="{'loading_file' : file.data.loading, 'loaded' : !file.data.loading, 'error' : file.data.error}">
			<div class="progress" v-if="file.data.loading">
				<div class="progress-bar progress-bar-striped progress-bar-animated"></div>
			</div>
			<div class="file_uploaded_data">
				<span class="name">{{file.data.name}}</span>
				<span class="right_part">
					<span class="error_message" v-if="file.data.message" v-html="file.data.message"></span>
					<span class="actions" v-if="file.data.status === 'uploaded'">
						<a v-bind:href="file.data.link" download>
							<i class="fa fa-cloud-download-alt"></i>
						</a>
						<?php if ( ! $readonly ) : ?>
							<i class="fa fa-times" @click="deleteFile(file, key)"></i>
						<?php endif; ?>
					</span>
				</span>
			</div>
		</div>

	</div>
</div>
