<?php
/**
 * @var boolean $modal
 * @var boolean $is_instructor
 * @var boolean $only_for_instructor
 * @var boolean $elementor_editor
 * @var string $type
 * @var boolean $dark_mode
 *
 * masterstudy-authorization_dark-mode - for dark mode
 */

wp_enqueue_style( 'masterstudy-authorization' );
wp_enqueue_script( 'masterstudy-authorization-main' );
wp_enqueue_script( 'masterstudy-authorization-ajax' );

$titles = array(
	'login'    => array(
		'main'    => __( 'Sign In', 'masterstudy-lms-learning-management-system' ),
		'account' => __( 'No account?', 'masterstudy-lms-learning-management-system' ),
	),
	'register' => array(
		'main'    => __( 'Sign Up', 'masterstudy-lms-learning-management-system' ),
		'account' => __( 'Have account?', 'masterstudy-lms-learning-management-system' ),
	),
);

$social_position                              = STM_LMS_Options::get_option( 'social_login_position', 'top' );
$titles['login']['separator']                 = 'bottom' === $social_position ? __( 'or sign in with socials', 'masterstudy-lms-learning-management-system' ) : __( 'or sign in with email', 'masterstudy-lms-learning-management-system' );
$titles['register']['separator']              = 'bottom' === $social_position ? __( 'or sign up with socials', 'masterstudy-lms-learning-management-system' ) : __( 'or sign up with email', 'masterstudy-lms-learning-management-system' );
$is_instructor                                = $is_instructor ?? false;
$only_for_instructor                          = $only_for_instructor ?? false;
$elementor_editor                             = $elementor_editor ?? false;
$is_logged_in                                 = $elementor_editor ? false : is_user_logged_in();
$titles['register']['main']                   = $only_for_instructor ? __( 'Instructor Sign Up', 'masterstudy-lms-learning-management-system' ) : $titles['register']['main'];
$submission_status                            = get_user_meta( get_current_user_id(), 'submission_status', true );
$settings                                     = get_option( 'stm_lms_settings' );
$settings['user_premoderation']               = $settings['user_premoderation'] ?? false;
$settings['register_as_instructor']           = $settings['register_as_instructor'] ?? true;
$settings['instructor_premoderation']         = $settings['instructor_premoderation'] ?? true;
$settings['separate_instructor_registration'] = $settings['separate_instructor_registration'] ?? false;
$settings['instructor_registration_link']     = $settings['instructor_registration_link'] ?? 'not_show';
$settings['instructor_registration_page']     = $settings['instructor_registration_page'] ?? false;
$settings['instructor_register_page_link']    = $settings['instructor_registration_page'] ? get_permalink( $settings['instructor_registration_page'] ) : '';
$recaptcha_enabled                            = STM_LMS_Helpers::g_recaptcha_enabled();
$recaptcha                                    = $recaptcha_enabled ? STM_LMS_Helpers::g_recaptcha_keys() : '';
$recaptcha_site_key                           = ! empty( $recaptcha['public'] ) ? stm_lms_filtered_output( $recaptcha['public'] ) : false;

if ( $is_logged_in && ! $only_for_instructor ) {
	STM_LMS_Templates::show_lms_template(
		'components/authorization/already-instructor',
		array(
			'message' => __( 'You are already logged in!', 'masterstudy-lms-learning-management-system' ),
		)
	);
	return;
}

if ( 'pending' === $submission_status ) {
	STM_LMS_Templates::show_lms_template(
		'components/authorization/instructor-confirmation',
		array(
			'show' => true,
		)
	);
	return;
}

if ( $only_for_instructor ) {
	if ( $is_instructor ) {
		STM_LMS_Templates::show_lms_template(
			'components/authorization/already-instructor',
			array(
				'message' => __( 'You are already instructor!', 'masterstudy-lms-learning-management-system' ),
			)
		);
		return;
	}
	if ( ! $settings['register_as_instructor'] ) {
		return;
	}
}

if ( class_exists( 'STM_LMS_Form_Builder' ) ) {
	$additional_fields = STM_LMS_Form_Builder::register_form_fields();
	$default_fields    = STM_LMS_Form_Builder::profile_default_fields_for_register();
}
?>
<script>
var authorization_data,
	authorization_settings;

if (typeof authorization_data === 'undefined') {
	authorization_data = {
		'register_nonce': '<?php echo esc_html( wp_create_nonce( 'stm_lms_register' ) ); ?>',
		'instructor_nonce': '<?php echo esc_html( wp_create_nonce( 'stm_lms_become_instructor' ) ); ?>',
		'login_nonce': '<?php echo esc_html( wp_create_nonce( 'stm_lms_login' ) ); ?>',
		'restore_nonce': '<?php echo esc_html( wp_create_nonce( 'stm_lms_lost_password' ) ); ?>',
		'ajax_url': '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
		'email_confirmation': '<?php echo esc_html( $settings['user_premoderation'] ); ?>',
		'recaptcha_site_key': '<?php echo esc_html( $recaptcha_site_key ); ?>',
		'default_fields': <?php echo wp_json_encode( $default_fields ?? array() ); ?>,
		'additional_fields': <?php echo wp_json_encode( $additional_fields['register'] ?? array() ); ?>,
		'instructor_fields': <?php echo wp_json_encode( $additional_fields['become_instructor'] ?? array() ); ?>,
		'only_for_instructor': '<?php echo esc_html( $only_for_instructor ); ?>',
		'user_account_page': '<?php echo esc_url( STM_LMS_User::user_page_url( get_current_user_id() ) ); ?>',
		'instructor_premoderation': '<?php echo esc_html( $settings['instructor_premoderation'] ); ?>',
	};
}
if (typeof authorization_settings === 'undefined') {
	authorization_settings = {
		'register_mode': '<?php echo esc_html( 'register' === $type ? true : false ); ?>',
		'titles': <?php echo wp_json_encode( $titles ); ?>,
	};
}
</script>
<?php
if ( $recaptcha_enabled ) {
	wp_enqueue_script( 'stm_grecaptcha' );
}

if ( ! isset( $dark_mode ) ) {
	global $masterstudy_course_player_template;

	$dark_mode = false;

	if ( $masterstudy_course_player_template ) {
		$mode_settings = get_option( 'stm_lms_settings', array() );
		$dark_mode     = $mode_settings['course_player_theme_mode'] ?? false;
	}
}

$classes = implode(
	' ',
	array_filter(
		array(
			$dark_mode ? 'masterstudy-authorization_dark-mode' : '',
			$modal ? 'masterstudy-authorization_style-modal' : '',
			'register' === $type ? 'masterstudy-authorization_register' : 'masterstudy-authorization_login',
		)
	)
);

if ( $modal ) {
	?>
	<div class="masterstudy-authorization-modal <?php echo esc_attr( $dark_mode ? 'masterstudy-authorization-modal_dark-mode' : '' ); ?>" style="opacity:0">
		<div class="masterstudy-authorization-modal__wrapper">
			<div class="masterstudy-authorization-modal__container">
				<span class="masterstudy-authorization-modal__close"></span>
<?php } ?>
<div class="masterstudy-authorization <?php echo esc_attr( $classes ); ?>">
	<div class="masterstudy-authorization__wrapper">
		<div class="masterstudy-authorization__header">
			<span class="masterstudy-authorization__header-title">
				<?php echo esc_html( 'register' === $type ? $titles['register']['main'] : $titles['login']['main'] ); ?>
			</span>
		</div>
		<?php
		if ( apply_filters( 'masterstudy_authorization_demo_login', false ) ) {
			STM_LMS_Templates::show_lms_template( 'components/authorization/demo-login' );
		}
		STM_LMS_Templates::show_lms_template(
			'components/authorization/social',
			array(
				'titles'          => $titles,
				'type'            => $type,
				'social_position' => $social_position,
			)
		);
		STM_LMS_Templates::show_lms_template( 'components/authorization/login-form' );
		STM_LMS_Templates::show_lms_template(
			'components/authorization/register-form',
			array(
				'default_fields'                   => ! empty( $default_fields ) ? $default_fields : array(),
				'additional_fields'                => ! empty( $additional_fields['register'] ) ? $additional_fields['register'] : array(),
				'instructor_fields'                => ! empty( $additional_fields['become_instructor'] ) ? $additional_fields['become_instructor'] : array(),
				'disable_instructor'               => ! $settings['register_as_instructor'],
				'separate_instructor_registration' => $settings['separate_instructor_registration'],
				'is_instructor'                    => $is_instructor,
				'only_for_instructor'              => $only_for_instructor,
				'is_logged_in'                     => $is_logged_in,
				'dark_mode'                        => $dark_mode,
			)
		);
		if ( ! empty( $settings['gdpr_page'] ) && ! empty( $settings['gdpr_warning'] ) && ! $is_logged_in ) {
			STM_LMS_Templates::show_lms_template(
				'components/authorization/gdpr',
				array(
					'gdpr_page'    => $settings['gdpr_page'],
					'gdpr_warning' => $settings['gdpr_warning'],
				)
			);
		}
		?>
		<div class="masterstudy-authorization__actions">
			<div class="masterstudy-authorization__actions-remember">
				<div class="masterstudy-authorization__checkbox">
					<input type="checkbox" name="masterstudy-authorization-remember" id="masterstudy-authorization-remember"/>
					<span class="masterstudy-authorization__checkbox-wrapper"></span>
				</div>
				<span class="masterstudy-authorization__checkbox-title">
					<?php echo esc_html__( 'Remember me', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
			</div>
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'id'    => 'masterstudy-authorization-login-button',
					'title' => __( 'Sign In', 'masterstudy-lms-learning-management-system' ),
					'link'  => '#',
					'style' => 'primary',
					'size'  => 'sm',
				)
			);
			?>
			<?php
			if ( ! $is_instructor ) {
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'    => $only_for_instructor && $is_logged_in ? 'masterstudy-authorization-instructor-confirm' : 'masterstudy-authorization-register-button',
						'title' => $only_for_instructor && $is_logged_in ? __( 'Send request', 'masterstudy-lms-learning-management-system' ) : __( 'Sign Up', 'masterstudy-lms-learning-management-system' ),
						'link'  => '#',
						'style' => 'primary',
						'size'  => 'sm',
					)
				);
			}
			?>
		</div>
	</div>
	<?php
	STM_LMS_Templates::show_lms_template( 'components/authorization/restore-password' );
	STM_LMS_Templates::show_lms_template(
		'components/authorization/restore-pass-email',
		array(
			'modal' => $modal,
		)
	);
	if ( $settings['user_premoderation'] ) {
		STM_LMS_Templates::show_lms_template(
			'components/authorization/email-confirmation',
			array(
				'modal' => $modal,
			)
		);
	}
	if ( $settings['instructor_premoderation'] ) {
		STM_LMS_Templates::show_lms_template( 'components/authorization/instructor-confirmation' );
	}
	if ( ! $is_logged_in ) {
		?>
		<div class="masterstudy-authorization__switch">
			<div class="masterstudy-authorization__switch-wrapper">
				<div class="masterstudy-authorization__switch-account">
					<span class="masterstudy-authorization__switch-account-title">
						<?php echo esc_html( 'register' === $type ? $titles['register']['account'] : $titles['login']['account'] ); ?>
					</span>
					<a href="#" id="masterstudy-authorization-sign-up" class="masterstudy-authorization__switch-account-link">
						<?php echo esc_html__( 'Sign Up', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
					<a href="#" id="masterstudy-authorization-sign-in" class="masterstudy-authorization__switch-account-link">
						<?php echo esc_html__( 'Sign In', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
				</div>
				<?php if ( 'show' === $settings['instructor_registration_link'] && $settings['separate_instructor_registration'] && $settings['instructor_registration_page'] ) { ?>
					<div class="masterstudy-authorization__instructor-page masterstudy-authorization__instructor-page_hide">
						<a href="<?php echo esc_url( $settings['instructor_register_page_link'] ); ?>" id="masterstudy-authorization-instructor-page" class="masterstudy-authorization__instructor-page-link" target="_blank">
							<?php echo esc_html__( 'Sign up', 'masterstudy-lms-learning-management-system' ); ?>
						</a>
						<span class="masterstudy-authorization__instructor-page-title">
							<?php echo esc_html( 'as instructor' ); ?>
						</span>
					</div>
				<?php } ?>
				<span class="masterstudy-authorization__switch-lost-pass">
						<?php echo esc_html__( 'Lost Password?', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
			</div>
		</div>
	<?php } ?>
</div>
<?php if ( $modal ) { ?>
		</div>
	</div>
</div>
<?php } ?>
