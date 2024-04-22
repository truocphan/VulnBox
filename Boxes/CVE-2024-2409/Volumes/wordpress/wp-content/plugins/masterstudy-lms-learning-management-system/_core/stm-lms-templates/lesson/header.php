<?php
/**
 * @var $post_id
 * @var $item_id
 * @var $is_previewed
 * @var $lesson_type
 */

$html_classes = apply_filters( 'stm_lms_lesson_html_classes', array( $lesson_type ) );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>
	class="no-js stm-lms-lesson-opened stm_lms_type_<?php echo esc_attr( implode( ' ', $html_classes ) ); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="stm_lms_lesson_header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-2 col-md-2 col-sm-6 col-xs-4">
				<div class="stm_lms_lesson_header__left">
					<?php STM_LMS_Templates::show_lms_template( 'global/curriculum-trigger', array( 'item_id' => $item_id ) ); ?>
				</div>
			</div>
			<div class="col-lg-8 col-md-6 col-sm-6 col-xs-8">
				<div class="row">
					<div class="container">
						<div class="row">
							<div class="col-md-8 col-md-push-2">
								<div class="stm_lms_lesson_header__center">
									<h5>
										<a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>">
											<?php echo esc_html( get_the_title( $post_id ) ); ?>
										</a>
									</h5>
									<a href="<?php echo esc_url( STM_LMS_User::user_page_url() ); ?>">
										<i class="lnr lnr-arrow-left"></i>
										<?php esc_html_e( 'Back to Dashboard', 'masterstudy-lms-learning-management-system' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-4 col-xs-12">
				<div class="stm_lms_lesson_header__right">
					<?php STM_LMS_Templates::show_lms_template( 'global/account-dropdown' ); ?>
					<?php STM_LMS_Templates::show_lms_template( 'global/wishlist-button' ); ?>
					<?php STM_LMS_Templates::show_lms_template( 'global/settings-button' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
