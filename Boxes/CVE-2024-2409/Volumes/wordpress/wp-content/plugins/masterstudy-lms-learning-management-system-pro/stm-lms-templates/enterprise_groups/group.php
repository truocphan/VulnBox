<div class="stm_lms_enterprise_group" v-if="typeof group.users !== 'undefined' && group.users.length">

	<div class="stm_lms_enterprise_group__single"
			v-bind:class="{'loading' : user.loading, 'active' : user.active}"
			v-for="(user, index) in group.users">

		<div class="stm_lms_enterprise_group__single_avatar" v-html="user.avatar" @click="openUser(user)"></div>

		<div class="stm_lms_enterprise_group__single_title heading_font" v-html="user.login" @click="openUser(user)"></div>

		<div class="actions">
			<a href="#" @click.prevent="changeAdmin(user)" class="adminChange">
				<i class="fa fa-key"></i>
				<span><?php esc_html_e( 'Set as admin', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			</a>
			<a href="#" @click.prevent="removeFromGroup(user, index)" class="deleteFromGroup">
				<i class="fa fa-times"></i>
				<span><?php esc_html_e( 'Remove from group', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			</a>
			<span class="expand" @click="openUser(user)"></span>
		</div>

		<div class="stm_lms_enterprise_group__single_courses" v-if="user.active">
			<?php STM_LMS_Templates::show_lms_template( 'enterprise_groups/user_courses' ); ?>
		</div>
	</div>

</div>

<div v-else>
	<h4><?php esc_html_e( 'No users in group, except you. Please go back to groups, and add some users to manage them.' ); ?></h4>
</div>
