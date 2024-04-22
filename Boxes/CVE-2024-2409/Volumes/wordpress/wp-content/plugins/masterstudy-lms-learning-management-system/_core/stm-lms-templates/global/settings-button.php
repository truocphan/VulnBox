<?php if ( is_user_logged_in() ) { ?>
	<div class="stm_lms_settings_button">
		<a href="<?php echo esc_url( STM_LMS_User::user_page_url() . 'settings' ); ?>">
			<i class="lnr lnr-cog"></i>
		</a>
	</div>
	<?php
}
