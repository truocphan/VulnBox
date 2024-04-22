<div class="controls">
	<div class="control orientation">
		<label for="orientation">
			<?php esc_html_e( 'Orientation', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</label>
		<select v-model="certificates[currentCertificate].data.orientation" id="orientation">
			<option value="landscape"><?php esc_html_e( 'Landscape', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
			<option value="portrait"><?php esc_html_e( 'Portrait', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
		</select>
	</div>
	<div class="control background">
		<label>
			<?php esc_html_e( 'Background Image', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</label>
		<div class="background-wrap">
			<div class="img-wrap" @click="uploadImage()">
				<img v-if="typeof certificates[currentCertificate].thumbnail !== 'undefined' && certificates[currentCertificate].thumbnail" v-bind:src="certificates[currentCertificate].thumbnail"/>
			</div>
			<div class="img-name">
				<span v-if="certificates[currentCertificate].filename"><span>{{certificates[currentCertificate].filename}}</span><i
							class="fa fa-times" @click="deleteImage()"></i></span>
				<span v-else @click="uploadImage()" class="chose-bg"><?php esc_html_e( 'Choose background', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			</div>
		</div>
	</div>
	<div class="control fields">
		<label><?php esc_html_e( 'Add elements', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
		<div class="fields-wrap">
			<div class="field" v-for="(field, key) in fields">
				<div class="field-wrap" @click="addField(key)">
					<span v-html="field.name"></span>
					<i class="fa fa-plus-circle"></i>
				</div>
			</div>
		</div>
	</div>
</div>
