<?php
/**
 * @var array $attachments
 * @var string $lesson_type
 * @var string $course_title
 * @var string $course_url
 * @var string $user_page_url
 * @var boolean $has_access
 * @var boolean $has_preview
 * @var boolean $lesson_lock_before_start
 * @var array $settings
 * @var int $quiz_duration
 * @var boolean $is_scorm_course
 * @var boolean $dark_mode
 * @var boolean $theme_fonts
 * @var boolean $discussions_sidebar
 * @var array $current_user
 */

wp_enqueue_style( 'masterstudy-course-player-header' );
wp_enqueue_script( 'masterstudy-course-player-header' );
wp_localize_script(
	'masterstudy-course-player-header',
	'settings',
	array(
		'theme_fonts' => $theme_fonts,
	)
);

global $post;
global $masterstudy_course_player_template;

$masterstudy_course_player_template = true;
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php echo esc_html( $post->post_title ?? get_bloginfo( 'charset' ) ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
$classes = implode(
	' ',
	array_filter(
		array(
			$dark_mode ? 'masterstudy-course-player-header_dark-mode' : '',
			$is_scorm_course ? 'masterstudy-course-player-header_scorm' : '',
		)
	)
);
?>
<div class="masterstudy-course-player-header <?php echo esc_attr( $classes ); ?>">
	<div class="masterstudy-course-player-header__back">
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/back-link',
			array(
				'id'  => 'masterstudy-course-player-back',
				'url' => $user_page_url,
			)
		);
		?>
	</div>
	<?php
	if ( ! empty( $settings['course_player_brand_icon_navigation'] ) ) {
		$logo_url = ! empty( $settings['course_player_brand_icon_navigation_image'] )
			? wp_get_attachment_image_url( $settings['course_player_brand_icon_navigation_image'] )
			: STM_LMS_URL . '/assets/img/image_not_found.png';
		?>
		<div class="masterstudy-course-player-header__logo">
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="">
		</div>
		<?php
	} if ( ! $is_scorm_course && ( $has_preview || $has_access ) ) {
		?>
		<div class="masterstudy-course-player-header__curriculum">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/switch-button',
				array(
					'title'     => __( 'Curriculum', 'masterstudy-lms-learning-management-system' ),
					'id'        => 'masterstudy-curriculum-switcher',
					'dark_mode' => $dark_mode,
				)
			);
			?>
		</div>
	<?php } ?>
	<div class="masterstudy-course-player-header__course">
		<span class="masterstudy-course-player-header__course-label">
			<?php echo esc_html__( 'Course', 'masterstudy-lms-learning-management-system' ); ?>:
		</span>
		<a href="<?php echo esc_url( $course_url ); ?>" class="masterstudy-course-player-header__course-title">
			<?php echo esc_html( mb_strlen( $course_title ) > 43 ? mb_substr( $course_title, 0, 40 ) . '...' : $course_title ); ?>
		</a>
	</div>
	<div class="masterstudy-course-player-header__navigation">
		<?php
		if ( ! empty( $attachments ) && $has_access && ! $lesson_lock_before_start ) {
			STM_LMS_Templates::show_lms_template(
				'components/tabs',
				array(
					'items'            => array(
						array(
							'id'    => 'lesson',
							'title' => __( 'Lesson', 'masterstudy-lms-learning-management-system' ),
						),
						array(
							'id'    => 'materials',
							'title' => __( 'Materials', 'masterstudy-lms-learning-management-system' ),
						),
					),
					'style'            => 'nav-sm',
					'active_tab_index' => 0,
					'dark_mode'        => $dark_mode,
				)
			);
		}
		?>
	</div>
	<?php if ( ! empty( $quiz_duration ) && $quiz_duration > 0 ) { ?>
		<div class="masterstudy-course-player-header__quiz-timer">
			<?php STM_LMS_Templates::show_lms_template( 'course-player/content/quiz/timer' ); ?>
		</div>
		<?php
	}
	if ( empty( $current_user['id'] ) ) {
		?>
		<div class="masterstudy-course-player-header__login">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'title' => __( 'Login', 'masterstudy-lms-learning-management-system' ),
					'link'  => '#',
					'style' => 'primary',
					'size'  => 'sm',
					'login' => 'login',
				)
			);
			?>
		</div>
		<?php
	}
	if ( ! $is_scorm_course && ( $has_preview || $has_access ) ) {
		?>
		<div class="masterstudy-course-player-header__dark-mode">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/dark-mode-button',
				array(
					'dark_mode' => $dark_mode,
				)
			);
			?>
		</div>
		<?php
	} if ( $has_access && ! $is_scorm_course && $discussions_sidebar && ! empty( $current_user['id'] ) ) {
		?>
		<div class="masterstudy-course-player-header__discussions">
			<span class="masterstudy-course-player-header__discussions-toggler">
				<span class="masterstudy-course-player-header__discussions-toggler__title">
					<?php echo esc_html__( 'Discussions', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
			</span>
			<span class="masterstudy-course-player-header__discussions-close-btn"></span>
		</div>
	<?php } ?>
</div>
