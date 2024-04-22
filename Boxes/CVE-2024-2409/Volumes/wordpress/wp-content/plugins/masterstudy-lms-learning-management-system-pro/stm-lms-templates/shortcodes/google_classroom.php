<?php
/**
 * @var $title
 */

$title    = ( ! empty( $title ) ) ? $title : esc_html__( 'Classrooms', 'masterstudy-lms-learning-management-system-pro' );
$per_page = ( ! empty( $number ) ) ? $number : 4;

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$chosen_auditory = ! empty( $_GET['auditory_id'] ) ? intval( $_GET['auditory_id'] ) : '';

stm_lms_register_style( 'google_classroom/module' );
stm_lms_register_script( 'google_classroom_module', array( 'vue.js', 'vue-resource.js', 'jquery.cookie' ) );
wp_localize_script(
	'stm-lms-google_classroom_module',
	'google_classroom_data',
	array(
		'auditory'        => STM_LMS_Helpers::get_posts( 'stm-auditory' ),
		'chosen_auditory' => $chosen_auditory,
		'per_page'        => $per_page,
	)
);
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

<div id="stm_lms_google_classroom_grid" :class="loading">

	<div class="row stm_lms_google_classroom_grid__head">
		<div class="col-sm-8">
			<h3><?php echo esc_html( $title ); ?></h3>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<select class="disable-select form-control" v-model="auditory" @change="getCourses">
					<option :value="''"><?php esc_html_e( 'Select auditory', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
					<option v-for="(auditory_label, auditory_value) in auditories" v-html="auditory_label"
							:value="auditory_value"></option>
				</select>
			</div>
		</div>
	</div>

	<div class="stm_lms_g_courses_wrapper">
		<div class="loading loading-spinner" :class="{'is-loading' : loading}"></div>

		<div class="stm_lms_g_courses" :class="{'loading' : loading}">
			<div class="stm_lms_g_course" v-for="(course, course_index) in courses">
				<div class="stm_lms_g_course__inner">
					<div class="stm_lms_g_course__head" :style="{'background-color' : course.color}">
						<div class="stm_lms_g_course__meta stm_lms_g_course__section" v-if="course.meta['section']">
							<i class="fa fa-inbox"></i>
							<span v-html="course.meta['section']"></span>
						</div>
						<h4 class="stm_lms_g_course__title" v-html="course.title"></h4>
						<div class="stm_lms_g_course__content" v-html="course.content"></div>
					</div>

					<div class="stm_lms_g_course__body">
						<div class="stm_lms_g_course__code" v-if="course.meta['code']">
							<label><?php esc_html_e( 'Course code', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
							<div class="stm_lms_g_course__codex" :class="{'copied' : course.copied}" @click="copyCode(course)">
								<span class="g_code" v-html="course.meta['code']"></span>
								<span class="g_copy"><?php esc_html_e( 'Copy', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								<input type="hidden" :id="'code_' + course.meta['code']" :value="course.meta['code']"/>

								<span class="copied_code" :class="{'copied' : course.copied}">
									<?php esc_html_e( 'Copied', 'masterstudy-lms-learning-management-system-pro' ); ?>
								</span>
							</div>
						</div>

						<div v-else class="stm_lms_g_course__message">
							<i class="fa fa-info-circle" :style="{'color' : course.color}"></i>
							<span>
								<?php esc_html_e( 'Only logged in students in a specific classroom can see the code ', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</span>
						</div>

						<div class="stm_lms_g_course__btn">
							<a :href="course.meta['alternateLink']" target="_blank" class="btn btn-default" :style="{'background-color' : course.color}">
								<?php esc_html_e( 'Read more', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="asignments_grid__pagination" v-if="pages !== 1">
		<ul class="page-numbers">
			<li v-for="single_page in pages">
				<a class="page-numbers" href="#" v-if="single_page !== page" @click.prevent="page = single_page; getCourses()">
					{{single_page}}
				</a>
				<span v-else class="page-numbers current">{{single_page}}</span>
			</li>
		</ul>
	</div>

</div>
