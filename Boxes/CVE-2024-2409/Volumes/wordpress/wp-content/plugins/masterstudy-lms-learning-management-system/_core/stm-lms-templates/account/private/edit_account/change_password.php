<div class="stm_lms_edit_socials">
	<div class="row">
		<div class="col-md-12">
			<h3><?php esc_html_e( 'Change Password', 'masterstudy-lms-learning-management-system' ); ?></h3>
		</div>
	</div>
	<div class="stm_lms_edit_socials_list">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="heading_font"><?php esc_html_e( 'New Password', 'masterstudy-lms-learning-management-system' ); ?></label>
					<div class="form-group-social">
						<input v-model="data.meta.new_pass"
								:type="inputType('new_visible')"
								class="form-control"
								placeholder="<?php esc_html_e( 'Enter your new password', 'masterstudy-lms-learning-management-system' ); ?>"/>
						<i class="fa visible_pass"
							v-bind:class="{'fa-eye' : new_visible, 'fa-eye-slash' : !new_visible}"
							v-on:mousedown="new_visible=true"
							v-on:mouseup="new_visible=false"></i>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="heading_font"><?php esc_html_e( 'Re-type New Password', 'masterstudy-lms-learning-management-system' ); ?></label>
					<div class="form-group-social">
						<input v-model="data.meta.new_pass_re"
								:type="inputType('re_type_visible')"
								class="form-control"
								placeholder="<?php esc_html_e( 'Enter your new password again', 'masterstudy-lms-learning-management-system' ); ?>"/>
						<i class="fa visible_pass"
							v-bind:class="{'fa-eye' : re_type_visible, 'fa-eye-slash' : !re_type_visible}"
							v-on:mousedown="re_type_visible=true"
							v-on:mouseup="re_type_visible=false"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
