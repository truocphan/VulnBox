<div class="stm_lms_instructor_courses__grid">

	<div class="stm_lms_instructor_courses__single" v-for="course in courses"  v-bind:class="'course-' + course.status">
		<div class="stm_lms_instructor_courses__single__inner">
			<div class="stm_lms_instructor_courses__single--image">

				<div class="stm_lms_post_status heading_font"
					v-if="course.post_status"
					v-bind:class="course.post_status.status">
					{{ course.post_status.label }}
				</div>

				<a v-bind:href="course.link" target="_blank">

					<div class="pending-message" v-if="course.status==='pending'">
						<i class="fa fa-hourglass-half"></i>
						<h4><?php esc_html_e( 'Pending for approval', 'masterstudy-lms-learning-management-system' ); ?></h4>
					</div>

					<div class="stm_lms_instructor_courses__single--image-wrapper"
						v-bind:class="{'no-image' : course.image===''}"
						v-html="course.image"></div>
				</a>
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

				<div class="stm_lms_instructor_courses__single--meta">
					<div class="average-rating-stars__top">
						<div class="star-rating">
								<span v-bind:style="{'width' : course.percent + '%'}">
									<strong class="rating">{{ course.average }}</strong>
								</span>
						</div>
						<div class="average-rating-stars__av heading_font">
							{{ course.average }}<span v-if="course.total != ''"> ({{course.total}})</span>
						</div>
					</div>
					<div class="views">
						<i class="lnr lnr-eye"></i>
						{{ course.views }}
					</div>
				</div>

				<div class="stm_lms_instructor_courses__single--bottom">


					<div class="stm_lms_instructor_courses__single--status" v-bind:class="course.status">

						<div class="stm_lms_instructor_courses__single--status-inner"
							v-bind:class="{
								'coming-soon': course.availability === '1',
								'loading': course.changingStatus
								}">

							<div class="stm_lms_instructor_courses__single--choice publish"
								@click="changeStatus(course, 'publish')"
								v-bind:class="{'chosen' : (course.status === 'publish' || course.status === 'pending')}">
								<i class="fa fa-check-circle"></i>
								<span><?php esc_html_e( 'Published', 'masterstudy-lms-learning-management-system' ); ?></span>
							</div>

							<div class="stm_lms_instructor_courses__single--choice draft"
								@click="changeStatus(course, 'draft')"
								v-bind:class="{'chosen' : course.status === 'draft'}">
								<i class="fa fa-pause"></i>
								<span><?php esc_html_e( 'Drafted', 'masterstudy-lms-learning-management-system' ); ?></span>
							</div>

							<a v-bind:href="course.edit_link" target="_blank"
								class="stm_lms_instructor_courses__single--choice edit">
								<i class="fa fa-edit"></i>
								<span><?php esc_html_e( 'Edit', 'masterstudy-lms-learning-management-system' ); ?></span>
							</a>

						</div>


					</div>


					<div class="stm_lms_instructor_courses__single--price heading_font"
						v-if="course.sale_price && course.price">
						<span>{{ course.price }}</span>
						<strong>{{ course.sale_price }}</strong>
					</div>
					<div class="stm_lms_instructor_courses__single--price heading_font" v-else>
						<strong>{{ course.price }}</strong>
					</div>
				</div>

				<div class="stm_lms_instructor_courses__single--featured heading_font"
					v-bind:class="{'loading' : course.changingFeatured}">

					<div class="feature_it add_to_featured"
						@click="changeFeatured(course)"
						v-if="course.status == 'publish' && course.is_featured != 'on'">
						<?php esc_html_e( 'Make Featured', 'masterstudy-lms-learning-management-system' ); ?>
					</div>

					<div class="feature_it remove_from_featured"
						v-if="course.is_featured == 'on'"
						@click="changeFeatured(course)">
						<?php esc_html_e( 'Remove from Featured', 'masterstudy-lms-learning-management-system' ); ?>
					</div>

					<a class="feature_it edit_course" v-if="course.status === 'draft'" :href="course.edit_link" target="_blank">
						<?php esc_html_e( 'Edit course', 'masterstudy-lms-learning-management-system' ); ?>
					</a>

					<div class="feature_it cancel_request" v-if="course.status === 'pending'" @click="changeStatus(course, 'draft')">
						<?php esc_html_e( 'Cancel request', 'masterstudy-lms-learning-management-system' ); ?>
					</div>

				</div>

				<div class="stm_lms_instructor_courses__single--updated"
					v-if="course.updated"
					v-html="course.updated">
				</div>
			</div>
		</div>
	</div>

</div>
