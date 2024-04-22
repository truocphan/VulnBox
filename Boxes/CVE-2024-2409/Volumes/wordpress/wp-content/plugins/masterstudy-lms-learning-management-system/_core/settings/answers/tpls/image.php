<div :class="['image-section', {'loading' : mediaLoading.includes(`${key}_<?php echo esc_attr($image_name); ?>`)}]">
	<?php if ( $quiz_media_type == 'url' ) : ?>
		<input type="text"
			:class="{'has-value': hasImage(key, '<?php echo esc_attr($image_name); ?>')}"
			placeholder="<?php esc_attr_e('Add Image URL', 'masterstudy-lms-learning-management-system'); ?>"
			:value="questions[key]['<?php echo esc_attr($image_name); ?>']?.url"
			@input="handleInputChange($event, key, '<?php echo esc_attr($image_name); ?>')"/>
	<?php else : ?>
	<div v-if="hasImage(key, '<?php echo esc_attr($image_name); ?>')">
		<img :src="questions[key]['<?php echo esc_attr($image_name); ?>'].url" class="image"/>
		<div class="actions-overlay" v-if="!mediaLoading.includes(`${key}_<?php echo esc_attr($image_name); ?>`)">
			<a href="#"
				class="delete"
				@click.prevent="$set(questions[key], '<?php echo esc_attr($image_name); ?>', {})">
				<i class="fa fa-trash-alt"></i>
			</a>
			<?php
				$media_library = class_exists( 'STM_LMS_Media_library');
			?>
			<div class="upload">
				<?php if ( $media_library ) : ?>
					<input type="file" @click.prevent="checkImage($event, key, '<?php echo esc_attr($image_name); ?>')" />
				<?php else : ?>
					<input type="file" @change="handleFileChange($event, key, '<?php echo esc_attr($image_name); ?>')"/>
				<?php endif; ?>
				<span><i class="fa fa-pen"></i></span>
			</div>
		</div>
	</div>
	<div v-else class="image empty">
			<?php if ( $media_library ) : ?>
				<input type="file" @click.prevent="checkImage($event, key, '<?php echo esc_attr($image_name); ?>')"/>
			<?php else : ?>
				<input type="file" @change="handleFileChange($event, key, '<?php echo esc_attr($image_name); ?>')"/>
			<?php endif; ?>
			<span>
				<i class="fa fa-plus"></i>
				<i class="fa fa-image"></i>
			</span>
	</div>
	<?php endif; ?>
</div>
<textarea type="text"
			:class="['image-textarea', {'empty' : typeof questions[key]['<?php echo esc_attr($description); ?>'] == 'undefined' || questions[key]['<?php echo esc_attr($description); ?>'].length < 1}]"
			placeholder="<?php esc_attr_e('Add description (not required)', 'masterstudy-lms-learning-management-system'); ?>"
			v-model="questions[key]['<?php echo esc_attr($description); ?>']"></textarea>