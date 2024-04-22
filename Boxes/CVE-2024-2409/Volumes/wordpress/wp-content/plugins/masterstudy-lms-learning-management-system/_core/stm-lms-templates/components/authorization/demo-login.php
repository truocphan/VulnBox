<div class="masterstudy-authorization__demo">
	<span class="masterstudy-authorization__demo-title">
		<?php echo esc_html__( 'Demo login for', 'masterstudy-lms-learning-management-system' ); ?>:
	</span>
	<div class="masterstudy-authorization__demo-role">
		<a href="<?php echo esc_url( get_site_url() . '?demo_login=' . get_site_url() . '/user-account' ); ?>" class="masterstudy-authorization__demo-role-title">
			<?php echo esc_html__( 'Instructor', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
		<a href="<?php echo esc_url( get_site_url() . '?generate_demo_user=' . get_site_url() . '/user-account' ); ?>" class="masterstudy-authorization__demo-role-title">
			<?php echo esc_html__( 'Student', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
	</div>
</div>
