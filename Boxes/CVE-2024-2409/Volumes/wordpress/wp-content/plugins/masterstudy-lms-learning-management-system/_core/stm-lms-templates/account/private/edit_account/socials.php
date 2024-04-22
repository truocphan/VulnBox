<div class="stm_lms_edit_socials">
	<div class="row">
		<div class="col-md-12">
			<h3><?php esc_html_e( 'Social network', 'masterstudy-lms-learning-management-system' ); ?></h3>
			<p><?php esc_html_e( 'Add your social profiles links, they will be shown on your public profile.', 'masterstudy-lms-learning-management-system' ); ?></p>
		</div>
	</div>
	<div class="stm_lms_edit_socials_list">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="heading_font"><?php esc_html_e( 'Facebook', 'masterstudy-lms-learning-management-system' ); ?></label>
					<div class="form-group-social form-group-social_icon form-group-social_facebook">
						<input v-model="data.meta.facebook"
							class="form-control"
							placeholder="<?php esc_html_e( 'Facebook profile url', 'masterstudy-lms-learning-management-system' ); ?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="heading_font"><?php esc_html_e( 'Google Plus', 'masterstudy-lms-learning-management-system' ); ?></label>
					<div class="form-group-social form-group-social_icon form-group-social_google">
						<input v-model="data.meta['google-plus']"
							class="form-control"
							placeholder="<?php esc_html_e( 'Google Plus profile url', 'masterstudy-lms-learning-management-system' ); ?>"/>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="heading_font"><?php esc_html_e( 'X (Twitter)', 'masterstudy-lms-learning-management-system' ); ?></label>
					<div class="form-group-social form-group-social_icon form-group-social_twitter">
						<input v-model="data.meta.twitter"
							class="form-control"
							placeholder="<?php esc_html_e( 'X profile url', 'masterstudy-lms-learning-management-system' ); ?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="heading_font"><?php esc_html_e( 'Instagram', 'masterstudy-lms-learning-management-system' ); ?></label>
					<div class="form-group-social form-group-social_icon form-group-social_instagram">
						<input v-model="data.meta.instagram"
							class="form-control"
							placeholder="<?php esc_html_e( 'Instagram profile url', 'masterstudy-lms-learning-management-system' ); ?>"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
