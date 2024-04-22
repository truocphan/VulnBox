<transition name="slide">
	<div class="stm-lms-dashboard-inner stm-lms-dashboard-course-user">
		<back></back>
		<div class="loading" v-if="loading"></div>
		<div v-else>
			<div class="course-user-stats">
				<div class="course-user-title">
					<h4 v-html="data.user.login + ' <?php esc_html_e( 'progress for:', 'masterstudy-lms-learning-management-system' ); ?>'"></h4>
					<h2 v-html="data.course_title"></h2>
				</div>
				<div class="progress-wrapper">
					<div class="progress-title">
						<?php esc_html_e( 'Course progress', 'masterstudy-lms-learning-management-system' ); ?>
						<div class="reset_all"
							@click="resetAllProgress('<?php esc_attr_e( 'Do you really want to reset all student progress? Including all assignment and quizzes tries?', 'masterstudy-lms-learning-management-system' ); ?>')">
							<?php esc_html_e( 'Reset', 'masterstudy-lms-learning-management-system' ); ?>
						</div>
					</div>
					<div class="progress">
						<div class="progress-bar progress-bar-success"
							v-bind:class="{'active progress-bar-striped' : data.progress_percent < 100}"
							v-bind:style="{'width': data.progress_percent + '%'}"></div>
					</div>
					<div class="progress-label">{{data.progress_percent}}%</div>
				</div>
			</div>
			<div class="sections">
				<div class="section" v-for="(section, index) in data.sections">
					<h3 v-html="`Section ${index + 1}`"></h3>
					<h2 v-html="decodeURIComponent(section.title)"></h2>
					<div class="section_items">
						<div class="section_item" v-for="item in data.materials.filter(item => item.section_id === section.id)"
							v-bind:class="[item.loading ? 'loading' : '', item.type, 'opened_' + item.opened]">
							<h4 class="section_item__title">
								<img v-if="item.type==='lesson' && item.post_type!=='stm-google-meets'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/text.svg' ); ?>">
								<img v-if="item.type==='quiz'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/quiz.svg' ); ?>">
								<img v-if="item.type==='video'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/video.svg' ); ?>">
								<img v-if="item.type==='text'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/text.svg' ); ?>">
								<img v-if="item.type==='stream'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/stream.svg' ); ?>">
								<img v-if="item.type==='assignment'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/assignments.svg' ); ?>">
								<img v-if="item.type==='zoom_conference'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/zoom_conference.svg' ); ?>">
								<img v-if="item.post_type==='stm-google-meets'" src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/google-meet.svg' ); ?>">
								<strong v-html="item.title"></strong>
								<div class="section_item__toggle"
									v-if="item.type==='assignment' || item.type==='quiz'"
									@click="openAssignments(item)">
									<i class="fa fa-chevron-down"></i>
								</div>
							</h4>
							<div class="section_item__completed">
								<label v-if="item.type!=='assignment'">
									<div class="stm_lms_dashboard_checkbox"
										v-bind:class="'completed_' + item.completed">
										<input type="checkbox" v-model="item.completed"
											v-on:change="completeItem(item)"/>
										<span><?php esc_html_e( 'Complete', 'masterstudy-lms-learning-management-system' ); ?></span>
									</div>
								</label>
								<label v-if="item.type==='assignment' && !item.completed">
									<div class="stm_lms_dashboard_checkbox"
										v-bind:class="'completed_' + item.completed">
										<input type="checkbox" v-model="item.completed"
											v-on:change="completeItem(item)"/>
										<span><?php esc_html_e( 'Complete', 'masterstudy-lms-learning-management-system' ); ?></span>
									</div>
								</label>
								<label v-if="item.type==='assignment' && item.completed">
									<div class="stm_lms_dashboard_checkbox"
										v-bind:class="'completed_' + item.completed">
										<span><?php esc_html_e( 'Complete', 'masterstudy-lms-learning-management-system' ); ?></span>
									</div>
								</label>
							</div>
							<div class="section_item__assignments" v-if="item.opened">
								<div class="section_item__assignments_inner">
									<student_assignments v-if="item.type==='assignment'"
														:course_id="id"
														:student_id="user_id"
														:assignment_id="item.post_id">
									</student_assignments>
									<student_quiz v-if="item.type==='quiz'"
												:course_id="id"
												:student_id="user_id"
												:quiz_id="item.post_id">
									</student_quiz>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</transition>
