<?php

$google_classroom = STM_LMS_Google_Classroom::getInstance();
$views_path       = STM_LMS_PRO_ADDONS . '/google_classrooms/admin_view/';

stm_lms_register_style( 'google_classroom/admin_view' );
stm_lms_register_script( 'google_classroom', array( 'vue.js', 'vue-resource.js' ) );


?>

<?php if ( ! empty( $google_classroom->get_auth_token() ) ) : ?>
	<div id="stm_lms_g_c_page_demo">

		<h4><?php esc_html_e( 'Google Classrooms archive page', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>

		<span class="stm_lms_material_button" :class="{'loading' : loading}" v-if="loading">
		<i class="lnr lnr-sync"></i>
	</span>

		<div class="stm_lms_material_buttons" v-else>

			<a :href="pageUrl" class="stm_lms_material_button" target="_blank">
				<i class="lnr lnr-eye"></i>
				<span><?php esc_html_e( 'View Page', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			</a>

		</div>

	</div>

<?php endif; ?>

<div class="stm_metaboxes_grid" id="stm_lms_google_classrooms">
	<div class="stm_metaboxes_grid__inner">
		<div class="container">
			<div class="stm-lms-tab active">

				<?php if ( ! empty( $google_classroom->get_auth_token() ) ) : ?>
					<?php include $views_path . '/courses.php'; ?>
				<?php endif; ?>

				<?php require $views_path . '/token.php'; ?>

			</div>
		</div>
	</div>
</div>

<h2><?php esc_html_e( 'Google Classrooms Settings', 'masterstudy-lms-learning-management-system-pro' ); ?></h2>
