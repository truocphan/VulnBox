<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use \MasterStudy\Lms\Pro\AddonsPlus\GoogleMeet\Services\GoogleOpenAuth;

get_header();

stm_lms_register_script( 'google-meet', array( 'jquery-ui-resizable' ) );
stm_lms_register_style( 'lesson_meet' );
wp_localize_script(
	'stm-lms-google-meet',
	'stm_google_meet_ajax_variable',
	array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'stm-lms-gm-nonce' ),
	)
);
wp_enqueue_script( 'stm-google-meet-form-js', STM_LMS_PRO_URL . '/assets/js/google-meet/stm-google-meet-form.js', false, STM_LMS_PRO_VERSION, true );
wp_localize_script(
	'stm-google-meet-form-js',
	'stm_gm_front_ajax_variable',
	array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'gm_front_meet_ajax' ),
	)
);
$paged = ( isset( $_GET['paged'] ) ) ? absint( $_GET['paged'] ) : 1; // Get current page number from URL query parameter

$args = array(
	'post_type'      => 'stm-google-meets',
	'author'         => get_current_user_id(),
	'posts_per_page' => get_option( 'posts_per_page' ) * $paged,
);

$user_meetings = new WP_Query( $args );

$user_id                       = get_current_user_id();
$google_api_credentials        = get_user_meta( $user_id, GoogleOpenAuth::TOKEN_NAME, true );
$google_api_credentials_config = get_user_meta( $user_id, GoogleOpenAuth::CONFIG_NAME, true );
$google_api_credentials_token  = get_user_meta( $user_id, GoogleOpenAuth::TOKEN_NAME, true );

$frontend_gm_settings = get_user_meta( $user_id, 'frontend_instructor_google_meet_settings', true );
do_action( 'stm_lms_template_main' );

$has_meetings = $user_meetings->have_posts();
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

<div class="stm-lms-wrapper stm-lms-wrapper--meet-lesson user-account-page-meet">

	<div class="container">

		<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>
		<?php
		if ( empty( $google_api_credentials_config ) || empty( $google_api_credentials_token ) ) {
			?>

			<div class="stm-lms-google-meet-wrapper">

				<form id="regForm" class="google-meet-steps">
					<div class="gm-header">
						<div class="gm-logo">
							<img src="<?php echo esc_attr( STM_LMS_PRO_URL . '/assets/img/meet-form-logo.svg' ); ?>">
						</div>
					</div>

					<div class="tab" style="display: block">
						<div class="gm-tab1-head">
							<h3><?php echo esc_html__( 'Setup your Google Meet Integration', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>
							<p><?php echo esc_html__( 'Google Meet integration enables seamless video conferencing will enhance collaboration and communication between users. Follow the steps below to get started.', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</p>
						</div>
						<div class="gm-tab1-head">
							<div class="intro-head">
								<span>1</span>
								<p class="title"><?php echo esc_html__( 'Open Google Developer Console', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
							</div>
							<p>
								<?php echo esc_html__( 'Access the Google Developer Console to create and configure your project for the Google Meet addon following Documentation.', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</p>
							<div class="head-buttons">
								<a href="https://console.cloud.google.com/apis/dashboard" class="gm-btn-outlined" target="_blank">
									<?php echo esc_html__( 'Open Dev Console', 'masterstudy-lms-learning-management-system-pro' ); ?>
								</a>
								<a href="https://docs.stylemixthemes.com/masterstudy-lms/lms-pro-addons/google-meet" class="gm-secondary-btn" target="_blank">
									<?php echo esc_html__( 'Documentation', 'masterstudy-lms-learning-management-system-pro' ); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="tab">
						<div class="gm-tab1-head">
							<div class="intro-head">
								<span>2</span>
								<p class="title"><?php echo esc_html__( 'Set Web Application URL in Google Developer Console', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
							</div>
							<p><?php echo esc_html__( 'The Web Application URL is an essential configuration that establishes the connection between the add-on and the Google Meet integration. By using the URL below, you enable seamless integration and allow your users to access Google Meet features directly from your site.', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
							<div class="gm-copy-url">
								<input type="text" class="gm-copy-url" id="gm-copy-url" value="<?php echo esc_url( ms_plugin_user_account_url( 'google-meets' ) ); ?>" disabled />
								<a class="gm-btn lms-gm-btn-copy">
									<?php echo esc_html__( 'Copy', 'masterstudy-lms-learning-management-system-pro' ); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="tab" id="jsonCredentials">
						<div class="gm-tab1-head">
							<div class="intro-head">
								<span>3</span>
								<p class="title">
									<?php echo esc_html__( 'Upload Credentials .JSON File', 'masterstudy-lms-learning-management-system-pro' ); ?>
								</p>
							</div>
							<p>
								<?php echo esc_html__( 'In this step, you need to upload the credentials .JSON file. The credentials .JSON file contains the necessary authentication information that allows securely interacting with the Google Meet API.', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</p>
							<div class="gm-json-config-wrapper">
								<div class="gm-json-config-upload">
									<label for="lms-gm-upload-file" id="lms-gm-upload-file-label"><?php echo esc_html__( 'Select File', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
									<input type="file" id="lms-gm-upload-file" class="lms-gm-upload-file" name="file"/>
								</div>
								<img src="<?php echo esc_url( STM_LMS_PRO_URL . '/assets/img/close_meet.png' ); ?>" class="cancel-uploaded-file" alt="close window">
							</div>
						</div>
					</div>
					<div class="tab">
						<div class="gm-tab1-head">
							<div class="intro-head">
								<span>4</span>
								<p class="title">
									<?php echo esc_html__( 'Grant App Permissions', 'masterstudy-lms-learning-management-system-pro' ); ?>
								</p>
							</div>
							<p>
								<?php echo esc_html__( 'Click Grant Permissions to give access to your Google account. Please allow all required permissions so that this app works correctly.', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</p>
						</div>
					</div>
					<div id="meetSteps">
						<?php echo esc_html__( 'Step:', 'masterstudy-lms-learning-management-system-pro' ); ?>
						<span class="step">1</span>
						<span class="step">2</span>
						<span class="step">3</span>
						<span class="step">4</span>
						<?php echo esc_html__( 'from 4', 'masterstudy-lms-learning-management-system-pro' ); ?>
					</div>

					<div class="next-btn-buttons">
						<button type="button" id="prevBtn" class="gm-prev-btn" style="opacity: 0;"><?php echo esc_html__( 'Back', 'masterstudy-lms-learning-management-system-pro' ); ?></button>
						<button type="button" id="nextBtn" class="gm-next-btn"><?php echo esc_html__( 'Next', 'masterstudy-lms-learning-management-system-pro' ); ?></button>
					</div>
				</form>
			</div>
		</div>
			<?php
		} else {
			?>
			<div class="stm-lms-google-meet-wrapper">

				<div class="gmi-header">
					<p class="gmi-title">
						<?php echo esc_html__( 'Google Meet Integration', 'masterstudy-lms-learning-management-system-pro' ); ?>
					</p>
					<a class="button btn create-meeting-header-btn">
						<?php echo esc_html__( 'Create Meeting', 'masterstudy-lms-learning-management-system-pro' ); ?>
					</a>
				</div>
				<div class="gmi-tabs-container">
					<ul class="tabs-nav">
						<li class="active">
							<a href="#meetings" id="meetingsList">
								<?php echo esc_html__( 'Meetings', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</a>
						</li>
						<li>
							<a href="#settings">
								<?php echo esc_html__( 'Settings', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</a>
						</li>
					</ul>
						<div class="tabs-content">
							<div id="meetings" class="tab-pane active">
								<table class="<?php echo esc_attr( $has_meetings ? '' : 'hidden' ); ?>">
									<thead>
									<tr class="gmi-tr">
										<th><?php echo esc_html__( 'Title', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
										<th><?php echo esc_html__( 'Date & Time', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
										<th><?php echo esc_html__( 'Meeting URL', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
										<th><?php echo esc_html__( 'Actions', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
									</tr>
									</thead>
									<tbody>
									<?php
									if ( $has_meetings ) {
										foreach ( $user_meetings->posts as $meet ) {
											STM_LMS_Templates::show_lms_template( 'google-meet/meeting-table-data', array( 'meeting_id' => $meet->ID ) );
										}
									}
									?>
									</tbody>
								</table>
										<?php do_action( 'stm_lms_after_groups_end' ); ?>
										<?php
										if ( $has_meetings ) {
											echo '<div class="meet-pagination">';
											$total_pages     = $user_meetings->max_num_pages;
											$current_page    = $paged;
											$remaining_pages = $total_pages - $current_page;
											if ( $remaining_pages >= 0 && $total_pages > 1 ) {
												echo '<div class="load-more-container">';
												//phpcs:ignore
												echo '<a href="?paged=' . ( $current_page + 1 ) . '" class="load-more-button">' . esc_html__( 'Load More', 'masterstudy-lms-learning-management-system-pro' ) . '</a>';
												echo '</div>';
											}
											echo '</div>';

											wp_reset_postdata();
										}
										?>
								<div class="not-found-meetings<?php echo esc_attr( $has_meetings ? ' hidden' : '' ); ?>">
									<img src="<?php echo esc_attr( STM_LMS_PRO_URL . '/assets/img/front-logo-meet.svg' ); ?>">
									<h3 class="not-found-title"><?php echo esc_html__( 'No Meetings Yet', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>
									<p><?php echo esc_html__( 'Once you create Meetings, they will appear here:', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
									<button class="btn create-gm-front-settings create-meeting-header-btn"><?php echo esc_html__( 'Create', 'masterstudy-lms-learning-management-system-pro' ); ?></button>
								</div>
							</div>
							<div id="settings" class="tab-pane gm-front-settings">
								<div class="gm-front-settings-container">
									<div class="gm-front-fields">
										<div class="gm-front-field-inner">
											<p>
												<?php echo esc_html__( 'Meet account status', 'masterstudy-lms-learning-management-system-pro' ); ?>
											</p>
											<p class="description">
												<?php echo esc_html__( 'You are currently connected to Meet Reset Credential', 'masterstudy-lms-learning-management-system-pro' ); ?>
											</p>
										</div>
										<div class="gm-front-field-inner">
											<a href="" class="btn reset-credentials btn-outlined"
												id="front-settings-reset-credentials">
												<?php echo esc_html__( 'Reset Credential', 'masterstudy-lms-learning-management-system-pro' ); ?>
											</a>
											<a href="" class="btn change-account btn-outlined"
												id="front-settings-change-account">
												<?php echo esc_html__( 'Change account', 'masterstudy-lms-learning-management-system-pro' ); ?>
											</a>
										</div>
									</div>
									<div class="gm-front-fields">
										<div class="gm-front-field-inner">
											<p>
												<?php echo esc_html__( 'Default timezone', 'masterstudy-lms-learning-management-system-pro' ); ?>
											</p>
											<p class="description">
												<?php echo esc_html__( 'Set the default timezone for Google Meet', 'masterstudy-lms-learning-management-system-pro' ); ?>
											</p>
										</div>
										<div class="gm-front-field-inner">
											<?php
											$timezones = stm_lms_get_timezone_options();
											?>
											<select name="timezone"
													class="select-meeting-form disable-select front-timezone-settings"
													id="front-meeting-timezone-settings">
												<?php foreach ( $timezones as $timezone => $label ) { ?>
													<option value="<?php echo esc_attr( $timezone ); ?>"
														<?php
														if ( ( isset( $frontend_gm_settings['timezone'] ) ? $frontend_gm_settings['timezone'] : 'UTC' ) === $timezone ) {
															echo esc_html__( 'selected', 'masterstudy-lms-learning-management-system-pro' );
														}
														?>
													>
														<?php echo esc_html( $label ); ?>
													</option>
												<?php } ?>
											</select>

										</div>
									</div>
									<div class="gm-front-fields">
										<div class="gm-front-field-inner">
											<p><?php echo esc_html__( 'Default reminder time (minutes)', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
											<p class="description">
												<?php echo esc_html__( 'Set a default reminder time to get an email notification', 'masterstudy-lms-learning-management-system-pro' ); ?>
											</p>
										</div>
										<div class="gm-front-field-inner">
											<input type="number" class="frontend-reminder-settings"
													value="<?php echo esc_attr( $frontend_gm_settings['reminder'] ?? 30 ); ?>">
										</div>
									</div>
									<div class="gm-front-fields">
										<div class="gm-front-field-inner">
											<p><?php echo esc_html__( 'Send updates', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
											<p class="description"><?php echo esc_html__( 'Select how to send notifications about the creation of the new event. Note that some emails might still be sent.', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
										</div>
										<div class="gm-front-field-inner">
											<select id="front-send-updates-settings"
													class="disable-select front-timezone-settings update-settings">
												<option
														value="all" <?php echo ( 'all' === ( $frontend_gm_settings['send_updates'] ?? 'all' ) ) ? 'selected' : ''; ?>>
													<?php echo esc_html__( 'All', 'masterstudy-lms-learning-management-system-pro' ); ?>
												</option>
												<option
														value="externalOnly" <?php echo ( 'externalOnly' === ( $frontend_gm_settings['send_updates'] ?? 'all' ) ) ? 'selected' : ''; ?>>
													<?php echo esc_html__( 'External Only', 'masterstudy-lms-learning-management-system-pro' ); ?>
												</option>
												<option
														value="none" <?php echo ( 'none' === ( $frontend_gm_settings['send_updates'] ?? 'all' ) ) ? 'selected' : ''; ?>>
													<?php echo esc_html__( 'None', 'masterstudy-lms-learning-management-system-pro' ); ?>
												</option>
											</select>

										</div>
									</div>
								</div>
										<?php do_action( 'stm_lms_after_groups_end' ); ?>
								<a href="" class="btn btn-outlined save-gm-front-settings"><?php echo esc_html__( 'Save', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
								<div id="saveNotificationGM">
									<div>Settings saved successfully</div>
									<i class="fa fa-refresh fa-spin installing"></i>
									<i class="fa fa-check downloaded" aria-hidden="true"></i>
								</div>
							</div>

						</div>
				</div>
			<?php STM_LMS_Templates::show_lms_template( 'google-meet/meeting-form', array( 'timezones' => stm_lms_get_timezone_options() ) ); ?>

			<?php } ?> 
			</div>
	</div>
</div>
		<?php
		get_footer();
