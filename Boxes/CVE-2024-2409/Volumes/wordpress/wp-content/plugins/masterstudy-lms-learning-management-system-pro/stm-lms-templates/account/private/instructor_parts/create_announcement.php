<?php
/**
 * @var $current_user
 */

stm_lms_register_style( 'create_announcement' );
stm_lms_register_script( 'create_announcement', array( 'vue.js', 'vue-resource.js' ) );

?>
<div class="stm_lms_create_announcement" id="stm_lms_create_announcement">

	<div class="row">

		<div class="col-md-12">
			<div class="form-group">
				<label class="heading_font"><?php esc_html_e( 'Choose Course', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
				<select v-model="post_id" class="disable-select form-control">
					<option v-bind:value="''"><?php esc_html_e( '- Choose Course for Announcement -', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
					<option v-for="(item, key) in posts" v-bind:value="key">
						{{ item }}
					</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="heading_font">
					<?php esc_html_e( 'Message for Course Students', 'masterstudy-lms-learning-management-system-pro' ); ?>
				</label>
				<textarea v-model="mail"
						placeholder="<?php esc_attr_e( 'Enter message for students', 'masterstudy-lms-learning-management-system-pro' ); ?>"></textarea>
			</div>
		</div>

	</div>

	<div class="row">

		<div class="col-md-12">
			<button @click="createAnnouncement()"
					v-bind:class="{'loading' : loading}"
					class="btn btn-default create-announcement-btn">
				<span><?php esc_html_e( 'Create Announcement', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			</button>
		</div>

		<div class="col-md-12">
			<transition name="slide-fade">
				<div class="stm-lms-message" v-bind:class="status" v-if="message">
					{{ message }}
				</div>
			</transition>
		</div>

	</div>
</div>
