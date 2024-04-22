<?php
trait MsLmsAddOverlay {

	public function add_courses_widget_overlay() {
		if ( '2.9.38' === STM_LMS_VERSION ) {
			?>
			<div class="stm_lms_courses_widget_overlay_wrapper">
				<div class="stm_lms_courses_widget_overlay">
					<div class="stm_lms_courses_widget_overlay_container">
						<h2 class="stm_lms_courses_widget_overlay_title">
							<?php echo esc_html_e( 'Try Courses Widget', 'masterstudy-lms-learning-management-system' ); ?>
						</h2>
						<p class="stm_lms_courses_widget_overlay_desc">
							<?php echo esc_html__( 'Do you like our current widgets? We have a brand new Elementor', 'masterstudy-lms-learning-management-system' ); ?> - 
							<span class="stm_lms_courses_widget_overlay_desc_highlight">
								<?php echo esc_html__( 'Courses 4 in 1 widget', 'masterstudy-lms-learning-management-system' ); ?>
							</span>
							<?php echo esc_html__( 'just for you, give it a try!', 'masterstudy-lms-learning-management-system' ); ?>
						</p>
						<div class="stm_lms_courses_widget_overlay_info">
							<div class="stm_lms_courses_widget_overlay_info_title">
								<?php echo esc_html__( 'Courses', 'masterstudy-lms-learning-management-system' ); ?>
								<span class="stm_lms_courses_widget_overlay_info_title_highlight">
									<?php echo esc_html__( '4 in 1', 'masterstudy-lms-learning-management-system' ); ?>
								</span>
								<?php echo esc_html__( 'widget', 'masterstudy-lms-learning-management-system' ); ?>
								<span class="stm_lms_courses_widget_overlay_info_title_block recent">
									<?php echo esc_html__( 'Recent Courses', 'masterstudy-lms-learning-management-system' ); ?>
								</span>
								<span class="stm_lms_courses_widget_overlay_info_title_block featured">
									<?php echo esc_html__( 'Featured Teacher', 'masterstudy-lms-learning-management-system' ); ?>
								</span>
								<span class="stm_lms_courses_widget_overlay_info_title_block grid">
									<?php echo esc_html__( 'Courses Grid', 'masterstudy-lms-learning-management-system' ); ?>
								</span>
								<span class="stm_lms_courses_widget_overlay_info_title_block carousel">
									<?php echo esc_html__( 'Courses Carousel', 'masterstudy-lms-learning-management-system' ); ?>
								</span>
								<span class="stm_lms_courses_widget_overlay_info_title_circle one"></span>
								<span class="stm_lms_courses_widget_overlay_info_title_circle two"></span>
								<span class="stm_lms_courses_widget_overlay_info_title_circle three"></span>
							</div>
						</div>
						<div class="stm_lms_courses_widget_overlay_button_wrapper">
							<a href="https://docs.stylemixthemes.com/masterstudy-lms/getting-started-1/lms-widgets#courses-4-in-1-elementor" target="_blank" class="stm_lms_courses_widget_overlay_button_article">
								<?php echo esc_html__( 'Learn How It Works', 'masterstudy-lms-learning-management-system' ); ?>
							</a>
							<a href="https://youtu.be/_yJdPSVVLo8" target="_blank" class="stm_lms_courses_widget_overlay_button_video"><?php echo esc_html__( 'Watch video', 'masterstudy-lms-learning-management-system' ); ?></a>
						</div>
						<button class="stm_lms_courses_widget_overlay_button_close"><?php echo esc_html__( 'Not now', 'masterstudy-lms-learning-management-system' ); ?></button>
					</div>
					<div class="stm_lms_courses_widget_overlay_bg"></div>
				</div>
			</div>
			<?php
		}
	}
}
