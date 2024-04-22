<?php
wp_enqueue_script( 'vue.js' );
wp_enqueue_script( 'vue-resource.js' );
stm_lms_register_script( 'account/v1/enrolled-courses' );
stm_lms_register_style( 'user-courses' );
stm_lms_register_style( 'instructor_courses' );
stm_lms_register_style( 'expiration/main' );
?>

<div class="stm_lms_user_info_top">

	<h3><?php esc_html_e( 'Enrolled Courses', 'masterstudy-lms-learning-management-system' ); ?></h3>

	<div class="stm_lms_user_info_top__sort">

		<select class="no-search">
			<option value="date_low"><?php esc_html_e( 'Enrolled date (last one)', 'masterstudy-lms-learning-management-system' ); ?></option>
			<option value="date_high"><?php esc_html_e( 'Enrolled date (first one)', 'masterstudy-lms-learning-management-system' ); ?></option>
			<option value="progress_low"><?php esc_html_e( 'Progress (increasing)', 'masterstudy-lms-learning-management-system' ); ?></option>
			<option value="progress_high"><?php esc_html_e( 'Progress (decreasing)', 'masterstudy-lms-learning-management-system' ); ?></option>
		</select>

	</div>

</div>

<div id="enrolled-courses">
	<div class="stm-lms-user-courses">

		<div class="multiseparator"></div>

		<div class="stm_lms_instructor_courses__grid">
			<div class="stm_lms_instructor_courses__single" v-for="course in courses"
				v-bind:class="{'expired' : course.expiration.length && course.is_expired || course.membership_expired || course.membership_inactive}">
				<div class="stm_lms_instructor_courses__single__inner">
					<div class="stm_lms_instructor_courses__single--image">

						<div class="stm_lms_post_status heading_font"
							v-if="course.post_status"
							v-bind:class="course.post_status.status">
							{{ course.post_status.label }}
						</div>

						<div v-html="course.image" class="image_wrapper"></div>

						<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/expiration' ); ?>

					</div>

					<div class="stm_lms_instructor_courses__single--inner">

						<div class="stm_lms_instructor_courses__single--terms" v-if="course.terms">
							<div class="stm_lms_instructor_courses__single--term" v-for="(term, key) in course.terms">
								<a :href="'<?php echo esc_url( STM_LMS_Course::courses_page_url() ); ?>' + '?terms[]=' + term.term_id + '&category[]=' + term.term_id" v-if="key === 0">
									{{ term.name }}
								</a>
							</div>
						</div>

						<div class="stm_lms_instructor_courses__single--title">
							<a v-bind:href="course.link">
								<h5 v-html="course.title"></h5>
							</a>
						</div>

						<div class="stm_lms_instructor_courses__single--progress">
							<div class="stm_lms_instructor_courses__single--progress_top">
								<div class="stm_lms_instructor_courses__single--duration" v-if="course.duration">
									<i class="far fa-clock"></i>
									{{ course.duration }}
								</div>
								<div class="stm_lms_instructor_courses__single--completed">
									{{ course.progress_label }}
								</div>
							</div>

							<div class="stm_lms_instructor_courses__single--progress_bar">
								<div class="stm_lms_instructor_courses__single--progress_filled"
									v-bind:style="{'width' : course.progress + '%'}"></div>
							</div>

						</div>

						<div class="stm_lms_instructor_courses__single--enroll">
							<a v-if="course.expiration.length && course.is_expired || course.membership_expired || course.membership_inactive" class="btn btn-default"
								:href="course.url" target="_blank">
								<span><?php esc_html_e( 'Preview Course', 'masterstudy-lms-learning-management-system' ); ?></span>
							</a>
							<?php
							if ( is_ms_lms_addon_enabled( 'coming_soon' ) ) {
								?>
								<a v-bind:href="course.current_lesson_id" class="btn btn-default"
									v-bind:class="{
									'continue': course.progress !== '0',
									'disabled': course.availability === '1'
								}"
									v-else>
									<span v-if="course.progress === '0' && course.availability === ''"><?php esc_html_e( 'Start Course', 'masterstudy-lms-learning-management-system' ); ?></span>
									<?php
									if ( is_ms_lms_addon_enabled( 'coming_soon' ) ) {
										?>
										<span
											v-else-if="course.availability === '1'"><?php esc_html_e( 'Coming soon', 'masterstudy-lms-learning-management-system' ); ?></span>
										<?php
									}
									?>
									<span v-else-if="course.progress === '100'"><?php esc_html_e( 'Completed', 'masterstudy-lms-learning-management-system' ); ?></span>
									<span v-else><?php esc_html_e( 'Continue', 'masterstudy-lms-learning-management-system' ); ?></span>
								</a>
								<?php
							} else {
								?>
								<a v-bind:href="course.current_lesson_id" class="btn btn-default"
									v-bind:class="{
									'continue': course.progress !== '0',
								}"
									v-else>
									<span v-if="course.progress === '0' && course.availability === ''"><?php esc_html_e( 'Start Course', 'masterstudy-lms-learning-management-system' ); ?></span>
									<?php
									if ( is_ms_lms_addon_enabled( 'coming_soon' ) ) {
										?>
										<span
											v-else-if="course.availability === '1'"><?php esc_html_e( 'Coming soon', 'masterstudy-lms-learning-management-system' ); ?></span>
										<?php
									}
									?>
									<span v-else-if="course.progress === '100'"><?php esc_html_e( 'Completed', 'masterstudy-lms-learning-management-system' ); ?></span>
									<span v-else><?php esc_html_e( 'Continue', 'masterstudy-lms-learning-management-system' ); ?></span>
								</a>
								<?php
							}
							?>
						</div>

						<div class="stm_lms_instructor_courses__single--started">
							{{ course.start_time }}
						</div>

					</div>
				</div>

			</div>

		</div>

		<h4 v-if="!courses.length && !loading"><?php esc_html_e( 'No courses.', 'masterstudy-lms-learning-management-system' ); ?></h4>
		<h4 v-if="loading"><?php esc_html_e( 'Loading courses.', 'masterstudy-lms-learning-management-system' ); ?></h4>

	</div>

	<div class="text-center load-my-courses">
		<a @click="getCourses()" v-if="!total" class="btn btn-default" v-bind:class="{'loading' : loading}">
			<span><?php esc_html_e( 'Show more', 'masterstudy-lms-learning-management-system' ); ?></span>
		</a>
	</div>

</div>
