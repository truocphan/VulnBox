<?php
wp_enqueue_script( 'stm-lms-media_library', STM_LMS_URL . '/settings/media_library/js/media_library.js', null, array() );
wp_enqueue_script( 'stm-lms-media_library-input', STM_LMS_URL . '/settings/media_library/js/media_library_input.js', null, array() );
wp_enqueue_script( 'stm-lms-media_library-file', STM_LMS_URL . '/settings/media_library/js/media_library_file.js', null, array() );
wp_enqueue_style( 'stm-lms-media_library-style', STM_LMS_URL . '/assets/css/parts/stm-media-library.css', null, array() );

?>

<media-library inline-template v-show="mediaLibrary" @checkImage="checkedImage" @modalClosed="closeModal" :mediaLibraryStatus="mediaLibrary">
	<div class="stm-lms-upload">
		<div class="stm-lms-upload__popup">
			<div class="stm-lms-upload__header">
				<div class="stm-lms-upload__title"><?php esc_html_e( 'Media Gallery', 'masterstudy-lms-learning-management-system' ); ?></div>
				<div class="stm-lms-upload__close" @click="closeModal()">
					<span class="fas fa-times"></span>
				</div>
			</div>
			<div class="stm-lms-upload__drop">
				<?php require STM_LMS_PATH . '/settings/media_library/media_library_input.php'; ?>
			</div>
			<div class="stm-lms-upload__body">
				<div class="stm-lms-upload__navigation">
					<div class="stm-lms-upload__search" :class="{ 'stm-lms-upload__loading': loading }">
						<input type="text" v-model="searchText" placeholder="<?php esc_html_e( 'Search', 'masterstudy-lms-learning-management-system' ); ?>">
						<div class="stm-lms-upload__spinner"></div>
					</div>
					<div class="stm-lms-upload__sort">
						<div class="stm-lms-upload__file-type">
							<div class="stm-lms-upload-select">
								<div class="stm-lms-upload-select__icon">
									<span class="fas fa-caret-down"></span>
								</div>
								<select name="file-type" v-model="filter.fileType">
									<option value="all" selected><?php esc_html_e( 'All files', 'masterstudy-lms-learning-management-system' ); ?></option>
									<option value="image"><?php esc_html_e( 'Images', 'masterstudy-lms-learning-management-system' ); ?></option>
									<option value="application"><?php esc_html_e( 'Documents', 'masterstudy-lms-learning-management-system' ); ?></option>
									<option value="video"><?php esc_html_e( 'Video', 'masterstudy-lms-learning-management-system' ); ?></option>
									<option value="audio"><?php esc_html_e( 'Audio', 'masterstudy-lms-learning-management-system' ); ?></option>
								</select>
							</div>
						</div>
						<div class="stm-lms-upload__filter">
							<div class="stm-lms-upload-select">
								<div class="stm-lms-upload-select__icon">
									<span class="fas fa-caret-down"></span>
								</div>
								<select name="sort" v-model="filter.sortBy">
									<option value="post_title" selected><?php esc_html_e( 'Name: A / z', 'masterstudy-lms-learning-management-system' ); ?></option>
									<option value="post_date" selected><?php esc_html_e( 'Date: New / old', 'masterstudy-lms-learning-management-system' ); ?></option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="stm-lms-upload__files" @scroll="handleScroll(event)">
					<?php require STM_LMS_PATH . '/settings/media_library/media_library_file.php'; ?>
					<div class="stm-lms-upload__empty" v-if="empty">
						<div><?php esc_html_e( 'Files not found', 'masterstudy-lms-learning-management-system' ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</media-library>
