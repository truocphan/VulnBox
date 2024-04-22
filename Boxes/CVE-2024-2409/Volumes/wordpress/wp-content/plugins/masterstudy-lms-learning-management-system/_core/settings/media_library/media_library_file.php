<upload-file-component
		inline-template
		v-for="file in files"
		:file="file" :loading="loading"
		@deleteFile="removeById"
		@checkFile="checkFile">
	<div class="stm-lms-upload-file" :class="{ 'stm-lms-upload-file__image': file.type === 'image', deleted: file.deleted }">
		<div v-if="!loading">
			<div @click="$emit('checkFile', file)" class="stm-lms-upload-file__body" :style="[file.type === 'image' ? { 'background-image': 'url(' + file.url + ')' } : {'background': '#F7FAFB'}]">
				<div class="stm-lms-upload-file__icon" v-if="file.type !== 'image'">
					<img :src="'<?php echo esc_url( STM_LMS_URL . '/assets/icons/files/' ); ?>' + file.type + '-file.svg'">
				</div>
				<div class="stm-lms-upload-file__title">{{ fileName }}</div>
			</div>
			<div class="stm-lms-upload-file__footer">
				<div class="stm-lms-upload-file__date">{{ file.date }}</div>
				<div class="stm-lms-upload-file__size">{{ file.size }}</div>
				<div class="stm-lms-upload-file__actions">
					<span class="fas fa-trash" @click="deleteFile(file)"></span>
				</div>
			</div>
		</div>
		<div class="stm-lms-upload-skeleton" v-else>
			<div class="stm-lms-upload-skeleton-item"></div>
			<div class="stm-lms-upload-skeleton-item"></div>
			<div class="stm-lms-upload-skeleton-item"></div>
		</div>
	</div>
</upload-file-component>