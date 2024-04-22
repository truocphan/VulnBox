<div class="stm_lms_students_gradebook" v-if="course.opened && course.data">

	<a href="#"
		@click.prevent="loadStudents(course)"
		v-if="!course.students"
		class="stm_lms_students_gradebook__load"
		v-bind:class="{'loading' : course.students_loading}">
		<span><?php esc_html_e( 'Load Students Statistics', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	</a>

	<div class="stm_lms_students_gradebook__inner" v-else>

		<h4><?php esc_html_e( 'Students Statistics', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>

		<div class="stm_lms_students_gradebook__list">
			<div class="stm_lms_students_gradebook__single" v-for="student in course.students">

				<div class="stm_lms_students_gradebook__single__image" v-html="student.user_data['avatar']"></div>

				<div class="stm_lms_students_gradebook__single__name">
					<h4 v-html="student.user_data['login']"></h4>
				</div>

				<div class="stm_lms_students_gradebook__single__email">
					<a v-bind:href="'mailto:' + student.user_data['email']" v-html="student.user_data['email']"></a>
				</div>

				<div class="stm_lms_students_gradebook__single__date">
					<h4>
						<?php esc_html_e( 'Started: ', 'masterstudy-lms-learning-management-system-pro' ); ?> {{student['start_date']}}
					</h4>
				</div>

				<div class="stm_lms_students_gradebook__single__stats">

					<div class="stm_lms_students_gradebook__single__lessons">
						<div class="inner">
							<?php esc_html_e( 'Lessons Passed', 'masterstudy-lms-learning-management-system-pro' ); ?>:
							<strong>{{student.lessons_progress['count']}}</strong>/{{course_curriculum['lessons']}}
						</div>

						<div class="progress-bar progress-bar-success"
							v-bind:class="{'progress-bar-striped active' : student.lessons_progress['percent'] !== 100}"
							v-bind:style="{'width' : student.lessons_progress['percent'] + '%'}"></div>
					</div>

					<div class="stm_lms_students_gradebook__single__lessons">
						<div class="inner">
							<?php esc_html_e( 'Quizzes Passed', 'masterstudy-lms-learning-management-system-pro' ); ?>:
							<strong>{{student.quizzes_progress['count']}}</strong>/{{course_curriculum['quizzes']}}
						</div>

						<div class="progress-bar progress-bar-success"
							v-bind:class="{'progress-bar-striped active' : student.quizzes_progress['percent'] !== 100}"
							v-bind:style="{'width' : student.quizzes_progress['percent'] + '%'}"></div>
					</div>

					<div class="stm_lms_students_gradebook__single__lessons" v-if="!student.assignments_progress">
						<div class="inner">
							<?php esc_html_e( 'Quizzes Failed', 'masterstudy-lms-learning-management-system-pro' ); ?>:
							<strong>{{student.quizzes_failed.length}}</strong>/{{student.quizzes_failed.length +
							student.quizzes.length}}
						</div>

						<div class="progress-bar progress-bar-success"
							v-bind:class="{'progress-bar-striped active' : student.quizzes_progress['fails'] !== 100}"
							v-bind:style="{'width' : student.quizzes_progress['fails'] + '%'}"></div>
					</div>

					<div class="stm_lms_students_gradebook__single__lessons" v-else>
						<div class="inner">
							<?php esc_html_e( 'Assignments Passed', 'masterstudy-lms-learning-management-system-pro' ); ?>:
							<strong>{{student.assignments_progress['count']}}</strong>/{{course_curriculum['assignments']}}
						</div>

						<div class="progress-bar progress-bar-success"
							v-bind:class="{'progress-bar-striped active' : student.assignments_progress['percent'] !== 100}"
							v-bind:style="{'width' : student.assignments_progress['percent'] + '%'}"></div>
					</div>

					<div class="stm_lms_students_gradebook__single__lessons">
						<div class="inner">
							<?php esc_html_e( 'Progress', 'masterstudy-lms-learning-management-system-pro' ); ?>:
							{{student.progress_percent}}%
						</div>

						<div class="progress-bar progress-bar-success"
							v-bind:class="{'progress-bar-striped active' : student.progress_percent !== '100'}"
							v-bind:style="{'width' : student.progress_percent + '%'}"></div>
					</div>

				</div>

			</div>
		</div>

	</div>

</div>
