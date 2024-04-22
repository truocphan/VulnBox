<div class="stm_lms_manage_add_instructor">

	<div class="stm_lms_manage_add_instructor__inst" v-if="coInstructor">
		<div class="meta-unit teacher clearfix">
			<div class="pull-left" v-html="coInstructor['data']['lms_data']['avatar']"></div>
			<div class="meta_values">
				<div class="label h6"><?php esc_html_e( 'Teacher', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
				<div class="value heading_font h6" v-html="coInstructor['data']['user_login']"></div>
			</div>
			<i class="lnricons-cross" @click="coInstructor = ''"></i>
		</div>
	</div>

	<div class="stm_lms_manage_add_instructor_list" v-else>
		<div class="stm_lms_manage_add_instructor__plus">
			<i class="lnricons-plus-circle"></i>
			<span><?php esc_html_e( 'Add co-instructor', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
		</div>
		<div class="stm_lms_manage_add_instructor__list">
			<div class="stm_lms_manage_add_instructor__user" @click="coInstructor = user" v-for="user in users">
				{{user.data.user_nicename}}
			</div>
		</div>
	</div>

</div>
