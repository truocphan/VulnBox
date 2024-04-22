<?php

$course_id = get_the_ID();
$author_id = get_post_meta( $course_id, 'co_instructor', true );

if ( ! empty( $author_id ) ) :
	stm_lms_register_style( 'co_instructor' );

	$author = STM_LMS_User::get_current_user( $author_id );
	?>
	<a href="<?php echo esc_url( STM_LMS_User::user_public_page_url( $author['id'] ) ); ?>">
		<div class="meta-unit teacher clearfix">
			<div class="pull-left">
				<?php echo wp_kses_post( apply_filters( 'stm_lms_co_instructor_avatar', $author['avatar'] ) ); ?>
			</div>
			<div class="meta_values">
				<div class="label h6"><?php esc_html_e( 'Co-instructor', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
				<div class="value heading_font h6"><?php echo esc_html( apply_filters( 'stm_lms_co_instructor_login', esc_html( $author['login'] ) ) ); ?></div>
			</div>
		</div>
	</a>
	<?php
endif;
