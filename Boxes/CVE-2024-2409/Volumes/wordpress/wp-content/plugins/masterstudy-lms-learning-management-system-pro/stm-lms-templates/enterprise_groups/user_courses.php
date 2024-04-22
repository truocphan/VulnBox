<div class="stm_lms_user_ent_courses" v-if="user.courses">
	<div class="stm_lms_user_ent_course" v-bind:class="{'loading' : course.loading}" v-for="course in user.courses">

		<div class="stm_lms_user_ent_course__title">
			<h4>{{course.data.title}}</h4>

			<div class="stm_lms_user_ent_course__progress" v-if="course.added">
				<div class="progress-bar progress-bar-success progress-bar-striped active" v-bind:style="{width : course.user_data.progress_percent + '%'}">
				</div>
			</div>

		</div>

		<div class="stm_lms_user_ent_course__actions">
			<a href="#" class="btn btn-default add" @click.prevent="addUserCourse(user, course)" v-if="!course.added">
				<?php esc_html_e( 'Add Course', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</a>
			<a href="#" @click.prevent="deleteUserCourse(user, course)" class="btn btn-default remove" v-else>
				<?php esc_html_e( 'Remove Course', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</a>
		</div>

	</div>
</div>
