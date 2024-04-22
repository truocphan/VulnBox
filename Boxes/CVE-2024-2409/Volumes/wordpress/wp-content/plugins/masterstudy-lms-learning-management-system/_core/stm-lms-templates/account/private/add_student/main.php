<?php


stm_lms_register_style( 'add_students/main' );
stm_lms_register_script( 'add_students/main' );

wp_localize_script(
	'stm-lms-add_students/main',
	'stm_lms_add_students',
	array(
		'translations' => array(
			'choose_course'   => esc_html__( 'Please select course', 'masterstudy-lms-learning-management-system' ),
			'choose_students' => esc_html__( 'Please select students', 'masterstudy-lms-learning-management-system' ),
		),
	)
);

if ( ! is_user_logged_in() ) {
	STM_LMS_User::js_redirect( STM_LMS_User::login_page_url() );
	die;
}

if ( ! STM_LMS_Instructor::instructor_can_add_students() || ! STM_LMS_Instructor::is_instructor() ) {
	STM_LMS_User::js_redirect( STM_LMS_User::login_page_url() );
	die;
}

stm_lms_register_style( 'user_info_top' );
stm_lms_register_style( 'bundles/my-bundle' );
stm_lms_register_script( 'bundles/my-bundle', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-bundles/my-bundle',
	'stm_lms_my_bundle_courses',
	array(
		'list' => STM_LMS_Instructor::get_courses( array( 'posts_per_page' => -1 ), true ),
	)
);

stm_lms_register_style( 'enterprise_groups' );
stm_lms_register_script( 'enterprise-groups', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-enterprise-groups',
	'stm_lms_groups',
	array(
		'limit'        => 1000,
		'translations' => array(
			'group_limit' => esc_html__( 'Limit:', 'masterstudy-lms-learning-management-system' ),
		),
	)
);

?>

<div class="stm_lms_add_student__fields">
	<div id="stm_lms_add_students_to_course">
		<div class="stm_lms_add_students_to_course">
			<h2><?php esc_html_e( 'Add Student', 'masterstudy-lms-learning-management-system' ); ?></h2>
		</div>
	</div>
	<div class="multiseparator"></div>
	<div class="stm_lms_add_student__fields_wrapper">
		<div id="stm_lms_my_bundle">
			<?php STM_LMS_Templates::show_lms_template( 'bundles/my-bundle/select-course' ); ?>
		</div>
		<div id="stm_lms_enterprise_groups" v-bind:class="{'loading': loading}">
			<!--EMAILS-->
			<div class="stm_lms_ent_groups_add_edit__emails">
				<h4><?php esc_html_e( 'Type students emails', 'masterstudy-lms-learning-management-system' ); ?></h4>
				<div class="stm_lms_ent_groups_add_edit__emails_new">
					<input type="text"
						v-model="newEmail"
						v-bind:class="{
					'valid' : validEmail(newEmail),
					'invalid' : !validEmail(newEmail) && newEmail.length}"
						class="form-control"
						placeholder="<?php esc_attr_e( 'Enter new user e-mail', 'masterstudy-lms-learning-management-system' ); ?>"
						@keyup.enter="addNewEmail()"/>
					<i class="lnricons-arrow-return" v-if="validEmail(newEmail)" @click="addNewEmail()"></i>
				</div>
				<div class="stm_lms_ent_groups_add_edit__emails_list">
					<div class="stm_lms_ent_groups_add_edit__email"
						v-for="(email, email_index) in groupData['emails']">
						<span>{{email}}</span>
						<i class="lnricons-cross" @click="groupData['emails'].splice(email_index, 1);"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="add_students">
	<a href="#"
	class="btn btn-default"><?php esc_html_e( 'Add students', 'masterstudy-lms-learning-management-system' ); ?></a>
</div>
<div class="add_students_notice">
	<div class="stm-lms-message success" style="display: none;"></div>
</div>
