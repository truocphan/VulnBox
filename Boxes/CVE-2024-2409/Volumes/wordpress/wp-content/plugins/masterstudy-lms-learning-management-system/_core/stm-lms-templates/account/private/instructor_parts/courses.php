<?php
/**
 * @var $current_user
 */

$course_statuses = array(
	array(
		'label'  => __( 'All', 'masterstudy-lms-learning-management-system' ),
		'status' => 'all',
	),
	array(
		'label'  => __( 'Published', 'masterstudy-lms-learning-management-system' ),
		'status' => 'published',
	),
	array(
		'label'  => __( 'In draft', 'masterstudy-lms-learning-management-system' ),
		'status' => 'draft',
	),
);

$render_upcoming_tab = false;

if ( isset( $current_user['id'] ) ) {
	$has_instructor_role = STM_LMS_Instructor::has_instructor_role( $current_user['id'] );

	$instructor_role = get_option( 'masterstudy_lms_coming_soon_settings', true );
	if ( is_array( $instructor_role ) && $has_instructor_role && isset( $instructor_role['lms_coming_soon_instructor_allow_status'] ) ) {
		$render_upcoming_tab = true;
	}
}

if ( current_user_can( 'administrator' ) ) {
	$render_upcoming_tab = true;
}

if ( is_ms_lms_addon_enabled( 'coming_soon' ) && $render_upcoming_tab ) {
	$course_statuses[] = array(
		'label'  => __( 'Upcoming', 'masterstudy-lms-learning-management-system' ),
		'status' => 'coming_soon_status',
	);
}

wp_enqueue_script( 'vue-resource.js' );
stm_lms_register_script( 'instructor_courses' );
wp_localize_script(
	'stm-lms-instructor_courses',
	'masterstudy_lms_settings_coming_soon',
	array(
		'per_page'        => STM_LMS_Options::get_option( 'courses_per_page', get_option( 'posts_per_page' ) ),
		'logged_user'     => wp_get_current_user()->ID,
		'course_statuses' => $course_statuses,
		'nonce'           => wp_create_nonce( 'wp_rest' ),
	)
);

$links = STM_LMS_Instructor::instructor_links();
stm_lms_register_style( 'instructor_courses' );

?>

	<div id="stm-lms-courses-grid">
		<div class="stm_lms_instructor_courses__top">
			<h3><?php esc_html_e( 'Courses', 'masterstudy-lms-learning-management-system' ); ?></h3>
			<div class="masterstudy-lms-course-filters">
				<a
					href="#"
					class="btn btn-default"
					v-for="(item, index) in statuses"
					:key="index"
					@click.prevent="filterCoursesByStatus(item.status)"
					:class="{ clicked: selectedStatus === item.status }"
				>
					{{ item.label }}
				</a>
			</div>
			<a href="<?php echo esc_url( $links['add_new'] ); ?>" class="btn btn-default" target="_blank">
				<i class="fa fa-plus"></i>
				<?php esc_html_e( 'Add New course', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
		</div>
		<div class="stm-lms-course-spinner-container" v-if="loading">
			<div class="stm-lms-spinner">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
		<div class="stm-lms-course-no-result" id="stm-lms-course-no-result" v-if="!courses.length && !loading">
			<div class="no-found">
				<div class="no-result-background">
					<span class="no-result-icon"></span>
				</div>
				<div class="no-found-icon">
					<i class="stmlms-not_found_courses"></i>
				</div>
			</div>
			<p>
				<?php esc_html_e( "You don't have any courses yet.", 'masterstudy-lms-learning-management-system' ); ?>
			</p>
			<a href="<?php echo esc_url( $links['add_new'] ); ?>">
				<i class="stm-lms-course-reset-filter-icon"></i>
			</a>
			<a href="<?php echo esc_url( $links['add_new'] ); ?>" class="btn btn-default" target="_blank">
				<i class="fa fa-plus"></i>
				<?php esc_html_e( 'Add your first course', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
		</div>

		<div class="stm_lms_instructor_courses vue_is_disabled" id="stm_lms_instructor_courses" v-if="courses.length"
			v-bind:class="{'is_vue_loaded' : vue_loaded}">

			<div class="stm_lms_instructor_quota heading_font" v-if="Object.keys(quota).length">
				<div class="stm_lms_instructor_quota__modal">
					<h5>
						<span class="quota_label">
							<?php esc_html_e( 'Available featured Quotes:', 'masterstudy-lms-learning-management-system' ); ?>
						</span>
						<span class="used_quota">{{quota.used_quota}}</span> from
						<span class="total_quota">{{quota.total_quota}}</span>
					</h5>
					<div class="stm_lms_instructor_quota__buttons">
						<span class="btn btn-default"
							@click="quota = {}"><?php esc_html_e( 'Done', 'masterstudy-lms-learning-management-system' ); ?></span>
						<a href="<?php echo esc_url( STM_LMS_Subscriptions::level_url() ); ?>"
							v-if="quota.available_quota === 0"
							class="btn btn-default upgrade">
							<?php esc_html_e( 'Upgrade', 'masterstudy-lms-learning-management-system' ); ?>
						</a>
					</div>
				</div>
				<div class="stm_lms_instructor_quota__overlay" @click="quota = {}"></div>
			</div>

			<?php STM_LMS_Templates::show_lms_template( 'account/private/instructor_parts/grid' ); ?>

			<div class="text-center">
				<a href="#"
					class="btn btn-default stm-load-more-courses-btn"
					@click.prevent="loadCourses()"
					v-if="!total"
					v-bind:class="{'loading': loading}">
					<span><?php esc_html_e( 'Load more', 'masterstudy-lms-learning-management-system' ); ?></span>
				</a>
			</div>
		</div>
	</div>
<?php
do_action( 'stm_lms_instructor_courses_end' );
