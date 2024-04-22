<div class="stm_lms_splash_wizard__footer"
	v-if="active_step !== 'business' && active_step !== 'finish'"
	v-bind:class="{'absolute' : active_step === 'profiles'}"
>
	<div class="stm_lms_splash_wizard__footer_wrapper">
		<a
			href="#"
			class="btn btn-prev"
			@click="prevStep()"
		>
			<i class="lnricons-chevron-left"></i>
			<span>
				<?php esc_html_e( 'Previous', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
		<a href="<?php echo esc_url( get_admin_url( null, 'admin.php?page=stm-lms-settings' ) ); ?>" class="skip-btn">
			<?php esc_html_e( 'skip', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
		<a
			href="#"
			class="btn btn-next"
			@click="nextStep()"
		>
			<span v-if="active_step !== 'profiles'">
				<?php esc_html_e( 'Next step', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
			<span v-if="active_step === 'profiles'">
				<?php esc_html_e( 'Finish setup', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
			<i class="lnricons-chevron-right"></i>
		</a>
	</div>
</div>
