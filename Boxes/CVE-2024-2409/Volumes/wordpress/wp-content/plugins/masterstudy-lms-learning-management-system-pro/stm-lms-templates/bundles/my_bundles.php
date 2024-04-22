<?php
stm_lms_register_style( 'bundles/my-bundles' );
stm_lms_register_script( 'bundles/my-bundles', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-bundles/my-bundles',
	'stm_lms_my_bundles',
	array(
		'list' => STM_LMS_My_Bundles::get_bundles(),
	)
);

?>

<div id="stm_lms_my_course_bundles">


	<div class="stm_lms_my_bundles">

		<h2><?php esc_html_e( 'Bundles', 'masterstudy-lms-learning-management-system-pro' ); ?></h2>

		<a href="<?php echo esc_url( ms_plugin_user_account_url( 'bundles/add_new' ) ); ?>" class="btn btn-default new_bundle">
			<i class="fa fa-plus-circle"></i>
			<?php esc_html_e( 'Add new Bundle', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>

	</div>

	<div class="multiseparator"></div>

	<div v-bind:class="{'has-pagination' : pages !== 1}">
		<?php STM_LMS_Templates::show_lms_template( 'bundles/card/vue/list' ); ?>
	</div>

	<div class="stm_lms_my_course_bundles__pagination" v-if="pages !== 1">
		<ul class="page-numbers">
			<li v-for="single_page in pages">
				<a class="page-numbers" href="#" v-if="single_page !== page" @click.prevent="page = single_page; getBundles()">
					{{single_page}}
				</a>
				<span v-else class="page-numbers current">{{single_page}}</span>
			</li>
		</ul>
	</div>

</div>
