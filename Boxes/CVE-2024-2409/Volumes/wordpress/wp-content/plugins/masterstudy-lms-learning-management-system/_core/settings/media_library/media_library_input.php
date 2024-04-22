<media-library-input inline-template @fileAdded="addFile">
	<div class="stm-lms-upload-input">
		<div class="stm-lms-upload-input-demo-mode">
			<span class="demo-text">
				Sorry, this site is only for demo purposes.
			</span>
		</div>
		<div class="stm-lms-upload-input__body" v-if="!this.loading">
			<div class="stm-lms-upload-input__description">
				<div class="stm-lms-upload-input__title"><?php esc_html_e( 'Upload file', 'masterstudy-lms-learning-management-system' ); ?></div>
				<div class="stm-lms-upload-input__subtitle"><?php esc_html_e( 'Drag and Drop file here or click here to select from computer', 'masterstudy-lms-learning-management-system' ); ?></div>
				<a href="#" class="btn btn-default media_library_upload_btn" @click="openDialog"><?php esc_html_e( 'Browse file', 'masterstudy-lms-learning-management-system' ); ?></a>
				<div class="error" v-show="error.status">{{ error.message }}</div>
			</div>
			<input type="file" @change="uploadImage" ref="uploadInput" style="opacity: 0">
		</div>
		<div v-else class="stm-lms-upload-input__progress" :class="{'error': error.status}" >
			<div v-show="!error.status">
				<div class="stm-lms-upload-progress-bar" >
					<div :style="{ width: this.progressBar + '%' }"></div>
				</div>
				<span v-show="!error.status">{{ this.fileName }} - <?php esc_html_e( 'Uploading', 'masterstudy-lms-learning-management-system' ); ?> {{ this.progressBar }}%</span>
				<div class="progress-close" @click="clearUpload">
					<i class="fas fa-times"></i>
				</div>
			</div>
			<div class="error-message-block" v-show="error.status">
				<div class="error-message-icon">
					<i class="fas fa-exclamation-circle"></i>
				</div>
				<div>
					<div class="error-message-filename">{{ this.fileName }}</div>
					<div class="error-message">{{ error.message }}</div>
				</div>
				<div class="error-message-close" @click="closeInput">
					<i class="fas fa-times"></i>
				</div>
			</div>
		</div>
	</div>
</media-library-input>
