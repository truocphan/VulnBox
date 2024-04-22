<questions_image inline-template :item="item" @showMediaLibrary="openMedia">

	<div class="question_image__popup" v-bind:class="{'loading' : loading}">
		<div class="question_image__popup_content">
			<h4 v-if="!hasImage()"><?php esc_html_e( 'Insert image for question', 'masterstudy-lms-learning-management-system' ); ?></h4>
			<img :src="item.image.url" v-else />
		</div>
		<div class="question_image__popup_actions">
			<a href="#" class="button">
				<input type="file" @change="handleFileChange" />
				<span v-if="!hasImage()"><?php esc_html_e( 'Upload', 'masterstudy-lms-learning-management-system' ); ?></span>
				<span v-else><?php esc_html_e( 'Change', 'masterstudy-lms-learning-management-system' ); ?></span>
			</a>
			<a href="#" class="cancel" @click.prevent="$set(item, 'image_opened', false)"><?php esc_html_e( 'Close', 'masterstudy-lms-learning-management-system' ); ?></a>
			<a href="#" class="delete" v-if="hasImage()" @click.prevent="$set(item, 'image', {})"><i class="fa fa-trash-alt"></i></a>
		</div>
	</div>

</questions_image>
