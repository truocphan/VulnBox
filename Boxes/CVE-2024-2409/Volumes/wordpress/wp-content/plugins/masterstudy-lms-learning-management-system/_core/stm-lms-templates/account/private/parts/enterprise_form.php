<?php
stm_lms_register_style( 'enterprise' );
?>
<div class="stm_lms_become_instructor masterstudy-enterprise-modal-parent">
	<i class="stmlms-case secondary_color"></i>
	<h3><?php esc_html_e( 'Have a question?', 'masterstudy-lms-learning-management-system' ); ?></h3>
	<p><?php esc_html_e( 'Here you can send a direct request to the site owner.', 'masterstudy-lms-learning-management-system' ); ?></p>
	<a href="#" class="btn-default btn lms_become_instructor_btn"  data-masterstudy-modal="masterstudy-enterprise-modal">
		<?php esc_html_e( 'Send Request', 'masterstudy-lms-learning-management-system' ); ?>
	</a>
	<?php
	STM_LMS_Templates::show_lms_template(
		'components/modals/enterprise',
		array(
			'dark_mode' => false,
		)
	);
	?>
</div>
