<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>

<?php
get_header();
stm_lms_register_style( 'gradebook' );
stm_lms_register_script( 'gradebook', array( 'vue.js', 'vue-resource.js' ) );
do_action( 'stm_lms_template_main' );
$style = STM_LMS_Options::get_option( 'profile_style', 'default' );
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

<div class="stm-lms-wrapper stm-lms-wrapper--gradebook user-account-page">

	<div class="container">

		<div id="stm_lms_gradebook">

			<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

			<div class="gradebook-header">
				<h1><?php esc_html_e( 'The Gradebook', 'masterstudy-lms-learning-management-system-pro' ); ?></h1>

				<div class="stm_lms_gradebook__filter">

					<div class="form-group">
						<label class="heading_font"><?php esc_html_e( 'Search Courses', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
						<input type="text"
								class="form-control"
								v-model="search"
								placeholder="<?php esc_html_e( 'Enter keyword here', 'masterstudy-lms-learning-management-system-pro' ); ?>"/>
					</div>

				</div>

			</div>

			<div class="multiseparator"></div>

			<div class="stm_lms_gradebook__courses" v-bind:class="{'loading' : loading}">
				<div class="stm_lms_gradebook__course"
						v-for="course in filteredList">

					<div class="stm_lms_gradebook__course__inner" @click.prevent="openCourse(course)">

						<a v-bind:href="course.link"
							class="stm_lms_gradebook__course__image"
							v-if="course.image_small"
							target="_blank"
							v-html="course.image_small"></a>

						<h4 class="stm_lms_gradebook__course__title" v-html="course.title"></h4>

						<div class="stm_lms_gradebook__course__toggle">
							<span v-if="!course.opened"><?php esc_html_e( 'More info', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
							<span v-else><?php esc_html_e( 'Show less', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
							<i class="fa fa-chevron-down" v-if="!course.opened"></i>
							<i class="fa fa-chevron-up" v-else></i>
						</div>

					</div>

					<?php STM_LMS_Templates::show_lms_template( 'gradebook/course-details' ); ?>

					<?php STM_LMS_Templates::show_lms_template( 'gradebook/students-details' ); ?>

				</div>
			</div>

			<a href="#"
				class="btn btn-default gradebook-load-btn"
				v-bind:class="{'loading' : loading}"
				@click.prevent="loadMore()" v-if="!total">
				<?php esc_html_e( 'Load more', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</a>

		</div>

	</div>
</div>

<?php get_footer(); ?>
