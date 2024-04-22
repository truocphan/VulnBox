<div class="stm-lms-pro-starter-theme-setup">
	<div class="stm-lms-pro-starter-theme-back-to-dashboard">
		<a href="<?php echo esc_url( admin_url( '/' ) ); ?>">
			<i class="stm-lms-left-arrow-icon"></i>
			<?php esc_html_e( 'Back to Dashboard', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
	</div>
	<div class="stm-lms-pro-starter-theme-setup-wrapper">

		<div class="stm-lms-pro-starter-theme-content step-1">
			<div class="stm-lms-theme-preview">
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/starter_cover.png' ); ?>">
			</div>
			<div class="stm-lms-theme-pre-install-info">
				<ul>
						<?php esc_html_e( 'A free ready-to-use starter theme for MasterStudy LMS. View how it works in life or install it for free. Get started now!', 'masterstudy-lms-learning-management-system' ); ?>
				</ul>
				<div class="stm-lms-attention">
					<div class="stm-lms-attention-info">
						<span>
							<?php esc_html_e( 'If you continue with the installation, all previous data may be lost or displayed incorrectly.', 'masterstudy-lms-learning-management-system' ); ?>
						</span>
					</div>
				</div>
			</div>
			<div class="stm-lms-pro-actions">
				<a target="_blank" class="stm-lms-pro-button default-btn" href="https://masterstudy.stylemixthemes.com/lms-plugin/">
					<?php esc_html_e( 'Live Demo', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
				<button class="stm-lms-pro-button main-btn buttonload button starter_install_theme_btn" name="starter_install_theme_btn">
					<span class="ui-button-text"> <?php echo esc_html( __( 'Install Now', 'masterstudy-lms-learning-management-system' ) ); ?></span>
					<i class="fa fa-refresh fa-spin installing"></i>
					<i class="fa fa-check downloaded" aria-hidden="true"></i>
				</button>
			</div>
		</div>
	</div>
</div>
