<?php
/**
 * @var string $gdpr_page
 * @var string $gdpr_warning
 */
?>

<div class="masterstudy-authorization__gdpr">
	<div class="masterstudy-authorization__checkbox">
		<input type="checkbox" name="privacy_policy" id="masterstudy-authorization-gdbr"/>
		<span class="masterstudy-authorization__checkbox-wrapper"></span>
	</div>
	<span class="masterstudy-authorization__gdpr-text">
		<?php echo esc_html( $gdpr_warning ); ?>
		<a href="<?php echo esc_url( get_the_permalink( $gdpr_page ) ); ?>" target="_blank" class="masterstudy-authorization__gdpr-link">
			<?php echo esc_html__( 'Privacy Policy', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
	</span>
</div>
