<?php
stm_lms_register_style( 'teacher' );
$author = STM_LMS_User::get_current_user( get_the_author_meta( 'ID' ) );
?>

<div class="pull-left stm_lms_teachers">

	<a href="<?php echo esc_url( STM_LMS_User::user_public_page_url( $author['id'] ) ); ?>">
		<div class="meta-unit teacher clearfix">
			<div class="pull-left">
				<?php echo wp_kses_post( $author['avatar'] ); ?>
			</div>
			<div class="meta_values">
				<div class="label h6"><?php esc_html_e( 'Teacher', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
				<div class="value heading_font h6"><?php echo esc_html( $author['login'] ); ?></div>
			</div>
		</div>
	</a>

</div>
