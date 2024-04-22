<?php

$co_courses = STM_LMS_Multi_Instructors::getCoCourses();

if ( count( $co_courses['posts'] ) ) :
	stm_lms_register_style( 'co_courses/list' );
	stm_lms_register_script( 'co_courses' );
	wp_localize_script( 'stm-lms-co_courses', 'stm_lms_co_courses', $co_courses );
	?>

	<div class="stm_lms_instructor_co_courses">
		<div class="stm_lms_instructor_courses__top">
			<h3><?php esc_html_e( 'Co-courses', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>
		</div>

		<div id="stm_lms_instructor_co_courses">

			<div class="stm_lms_instructor_courses__grid" v-bind:class="{'loading' : loading}">

				<div class="stm_lms_instructor_courses__single" v-for="course in courses">
					<div class="stm_lms_instructor_courses__single__inner">
						<div class="stm_lms_instructor_courses__single--image">
							<div class="stm_lms_post_status heading_font" v-if="course.post_status" v-bind:class="course.post_status.status">
								{{ course.post_status.label }}
							</div>

							<div class="stm_lms_instructor_courses__single--actions heading_font">
								<a v-bind:href="course.edit_link" target="_blank"><?php esc_html_e( 'Edit', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
								<a v-bind:href="course.link" target="_blank"><?php esc_html_e( 'View', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
							</div>
							<div class="stm_lms_instructor_courses__single--image-wrapper" v-bind:class="{'no-image' : course.image===''}" v-html="course.image"></div>
						</div>
						<div class="stm_lms_instructor_courses__single--inner">
							<div class="stm_lms_instructor_courses__single--terms" v-if="course.terms">
								<div class="stm_lms_instructor_courses__single--terms" v-if="course.terms">
									<div class="stm_lms_instructor_courses__single--term"
										v-for="(term, key) in course.terms"
										v-html="term + ' >'" v-if="key === 0">
									</div>
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
										{{ course.average }} ({{course.total}})
									</div>
								</div>
								<div class="views">
									<i class="lnr lnr-eye"></i>
									{{ course.views }}
								</div>
							</div>

							<div class="stm_lms_instructor_courses__single--bottom">
								<div class="stm_lms_instructor_courses__single--status" v-bind:class="course.status">
									<i class="lnr lnr-checkmark-circle" v-if="course.status == 'publish'"></i>
									{{ course.status_label }}
								</div>
								<div class="stm_lms_instructor_courses__single--price heading_font" v-if="course.sale_price && course.price">
									<span>{{ course.price }}</span>
									<strong>{{ course.sale_price }}</strong>
								</div>
								<div class="stm_lms_instructor_courses__single--price heading_font" v-else>
									<strong>{{ course.price }}</strong>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="asignments_grid__pagination" v-if="pages !== 1">
				<ul class="page-numbers">
					<li v-for="single_page in pages">
						<a class="page-numbers"
								href="#"
								v-if="single_page !== page"
								@click.prevent="page = single_page; getCourses(); ">
							{{single_page}}
						</a>
						<span v-else class="page-numbers current">{{single_page}}</span>
					</li>
				</ul>
			</div>

		</div>

	</div>

	<?php
endif;
