<?php
/**
 * @var $current_user
 */

stm_lms_register_style( 'edit_account' );
stm_lms_register_script( 'edit_account', array( 'vue.js', 'vue-resource.js' ) );
stm_lms_register_style( 'user_info_top' );
$data = wp_json_encode( $current_user );
wp_add_inline_script(
	'stm-lms-edit_account',
	"var stm_lms_edit_account_info = {$data}"
);

?>


<div class="stm_lms_edit_account" id="stm_lms_edit_account">

	<div class="stm_lms_edit_socials stm_lms_edit_name">
		<div class="stm_lms_edit_socials_list">
			<?php STM_LMS_Templates::show_lms_template( 'account/private/edit_account/name' ); ?>

			<?php
			if ( STM_LMS_Instructor::is_instructor() ) {
				STM_LMS_Templates::show_lms_template( 'account/private/edit_account/position' );
				STM_LMS_Templates::show_lms_template( 'account/private/edit_account/bio' );
			}
			?>

			<?php STM_LMS_Templates::show_lms_template( 'account/private/edit_account/custom_fields' ); ?>
		</div>
	</div>

	<?php
	if ( STM_LMS_Instructor::is_instructor() ) {
		STM_LMS_Templates::show_lms_template( 'account/private/edit_account/socials' );}
	?>

	<?php STM_LMS_Templates::show_lms_template( 'account/private/edit_account/change_password' ); ?>

	<div class="row">

		<div class="col-md-12">

			<div class="row">

				<div class="col-md-6 col-sm-6">

					<button @click="saveUserInfo()"
							v-bind:class="{'loading' : loading}"
							class="btn btn-default btn-save-account">
						<span><?php esc_html_e( 'Save changes', 'masterstudy-lms-learning-management-system' ); ?></span>
					</button>

				</div>

				<div class="col-md-6 col-sm-6">

					<div class="stm_lms_sidebar_logout_wrapper text-right xs-text-left">
						<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/logout' ); ?>
					</div>

				</div>

			</div>

		</div>

		<div class="col-md-12">
			<transition name="slide-fade">
				<div class="stm-lms-message" v-bind:class="status" v-if="message">
					{{ message }}
				</div>
			</transition>
		</div>

	</div>

</div>
