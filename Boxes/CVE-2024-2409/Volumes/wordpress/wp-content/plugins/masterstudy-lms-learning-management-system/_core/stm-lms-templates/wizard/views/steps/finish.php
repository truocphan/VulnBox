<div class="wizard-finish" v-if="active_step === 'finish'">
	<div class="wizard-finish__welcome">
		<div class="wizard-finish__welcome_column">
			<h2>
				<span><?php esc_html_e( 'Welcome to MasterStudy LMS', 'masterstudy-lms-learning-management-system' ); ?></span>
				<span><?php esc_html_e( 'WordPress plugin', 'masterstudy-lms-learning-management-system' ); ?></span>
			</h2>
			<p>
				<?php esc_html_e( 'Now you can easily create courses from scratch or import our specially built demo courses.', 'masterstudy-lms-learning-management-system' ); ?>
			</p>
			<div class="wizard-finish__welcome_button_wrapper">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=stm-lms-settings#section_2' ) ); ?>" class="wizard-finish__welcome_button">
					<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/run_demo.svg' ); ?>"/>
					<?php esc_html_e( 'Import Demo Courses', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=stm-courses' ) ); ?>" class="wizard-finish__welcome_button">
					<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/add_course.svg' ); ?>"/>
					<?php esc_html_e( 'Add new course', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
			</div>
		</div>
		<div class="wizard-finish__welcome_column">
			<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/welcome_bg.png' ); ?>"/>
		</div>
	</div>
	<div class="wizard-finish__column_wrapper">
		<div class="wizard-finish__column">
			<div class="wizard-finish__column_block unlimited">
				<h3><?php esc_html_e( 'Unlimited lessons and quizzes', 'masterstudy-lms-learning-management-system' ); ?></h3>
				<p><?php esc_html_e( 'You have the option to create lessons and quizzes of various types. Choose the type of lesson and quiz, or combine them in the course.', 'masterstudy-lms-learning-management-system' ); ?></p>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/unlim_learn_bg.png' ); ?>"/>
			</div>
			<div class="wizard-finish__column_block small_companies">
				<h3><?php esc_html_e( 'For small companies and enterprises', 'masterstudy-lms-learning-management-system' ); ?></h3>
				<p><?php esc_html_e( 'With the front-end course builder, you will be able to design courses equally professionally for both small schools and full-fledged marketplaces. No code skills are required at all.', 'masterstudy-lms-learning-management-system' ); ?></p>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/small_companies_bg.png' ); ?>"/>
			</div>
		</div>
		<div class="wizard-finish__column">
			<div class="wizard-finish__column_block users_manage">
				<h3><?php esc_html_e( 'User мanagement', 'masterstudy-lms-learning-management-system' ); ?></h3>
				<p><?php esc_html_e( 'There are two types of profiles in MasterStudy LMS: for students and for instructors. Drive courses and the progress of your students on one page.', 'masterstudy-lms-learning-management-system' ); ?></p>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/users_manage_bg.png' ); ?>"/>
			</div>
			<div class="wizard-finish__column_block video">
				<h3><?php esc_html_e( 'Video guides and documentation', 'masterstudy-lms-learning-management-system' ); ?></h3>
				<p><?php esc_html_e( 'We have an extensive knowledge base and a playlist on our Youtube channel to explain each feature of the plugin.', 'masterstudy-lms-learning-management-system' ); ?></p>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/video_bg.png' ); ?>"/>
				<div class="wizard-finish__column_block_button_wrapper">
					<a href="https://www.youtube.com/watch?v=wGtDvLkVvaQ&list=PL3Pyh_1kFGGDikfKuVbGb_dqKmXZY86Ve" class="wizard-finish__column_block_button" target="_blank">
						<?php esc_html_e( 'WATCH PLAYLIST', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
					<a href="https://docs.stylemixthemes.com/masterstudy-lms/" class="wizard-finish__column_block_button" target="_blank">
						<?php esc_html_e( 'READ GUIDES', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
				</div>
			</div>
			<div class="wizard-finish__column_block sell_online">
				<h3><?php esc_html_e( 'Sell courses online', 'masterstudy-lms-learning-management-system' ); ?></h3>
				<p><?php esc_html_e( 'Set up PayPal, Stripe or WooCommerce (for other payment gateways) to monetize your courses.', 'masterstudy-lms-learning-management-system' ); ?></p>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/sell_online_bg.png' ); ?>"/>
			</div>
		</div>
	</div>
	<div class="wizard-starter">
		<div class="wizard-starter__screen">
			<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/wizard/starter-theme.png' ); ?>" width="432" height="262" alt="<?php esc_html_e( 'Free WordPress LMS Theme', 'masterstudy-lms-learning-management-system' ); ?>">
		</div>
		<div class="wizard-starter__content">
			<h3>
			<?php
				// translators: This is a translatable string with placeholders.
				$description = esc_html__( 'Free WordPress %1$sLMS Theme%2$s', 'masterstudy-lms-learning-management-system' );
				echo wp_kses_post( sprintf( $description, '<span style="font-weight: normal">', '</span>' ) );
			?>
			</h3>
			<p>
			<?php
			// translators: This is a translatable string with placeholders.
			$description = esc_html__( 'Download our Masterstudy Starter theme %1$sfor free%2$s and get an intuitive and easy-to-navigate look for your website.', 'masterstudy-lms-learning-management-system' );
			echo wp_kses_post( sprintf( $description, '<strong>', '</strong>' ) );
			?>
			</p>
			<div class="wizard-starter__buttons">
				<a href="https://masterstudy.stylemixthemes.com/lms-plugin/" class="wizard-finish__column_block_button" target="_blank">
					<?php esc_html_e( 'View Demo', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
				<a href="https://stylemixthemes-public.s3.us-west-2.amazonaws.com/ms-lms-starter-theme.zip" class="wizard-finish__column_block_button" target="_blank">
					<?php esc_html_e( 'Download', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
			</div>
		</div>
	</div>
	<div class="wizard-finish__links">
		<a href="https://docs.stylemixthemes.com/masterstudy-lms/" class="wizard-finish__links_block" target="_blank">
			<div class="wizard-finish__links_block_wrapper">
				<div class="icon_wrapper">
					<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/help_desk_icon.svg' ); ?>"/>
				</div>
				<span><?php esc_html_e( 'Help Desk', 'masterstudy-lms-learning-management-system' ); ?></span>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/arrow.svg' ); ?>" class="wizard-arrow"/>
			</div>
		</a>
		<a href="https://stylemix.net/ticket-form/?utm_source=wpadmin-ms&utm_medium=lms-wizard&utm_campaign=customization" class="wizard-finish__links_block" target="_blank">
			<div class="wizard-finish__links_block_wrapper">
				<div class="icon_wrapper">
					<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/custom_icon.svg' ); ?>"/>
				</div>
				<span><?php esc_html_e( 'Customization', 'masterstudy-lms-learning-management-system' ); ?></span>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/arrow.svg' ); ?>" class="wizard-arrow"/>
			</div>
		</a>
		<a href="https://www.facebook.com/groups/masterstudylms"class="wizard-finish__links_block" target="_blank">
			<div class="wizard-finish__links_block_wrapper">
				<div class="icon_wrapper">
					<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/fb_icon.svg' ); ?>"/>
				</div>
				<span><?php esc_html_e( 'Facebook Community', 'masterstudy-lms-learning-management-system' ); ?></span>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/wizard/arrow.svg' ); ?>" class="wizard-arrow"/>
			</div>
		</a>
	</div>
	<div class="wizard-finish__bottom_banner">
		<h3><?php esc_html_e( 'Upgrade the ultimate online learning experience', 'masterstudy-lms-learning-management-system' ); ?></h3>
		<p><?php esc_html_e( 'Get MasterStudy Pro Plus to access extra features your users will love: Certificate Builder, Email Editor and Branding Manager, Assignments, Drip Content and Prerequisites, Zoom Conference, Co-Instructors, Course Bundles, Live Streaming, and much more!', 'masterstudy-lms-learning-management-system' ); ?></p>
		<a href="https://stylemixthemes.com/wordpress-lms-plugin/pricing/?utm_source=wpadmin-ms&utm_medium=lms-wizard&utm_campaign=get-masterstudy-pro" target="_blank"><?php esc_html_e( 'Get MasterStudy Pro Plus', 'masterstudy-lms-learning-management-system' ); ?></a>
	</div>
</div>
