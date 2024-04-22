<?php
/**
 * @var string $new_version
 */
?>
</p><?php // close the paragraph tag opened by WP (@see wp-admin/includes/update.php) ?>

<hr>
<h4>
	<i class="masterstudy-update-notice__header-icon fa fa-exclamation-circle"></i> <?php esc_html_e( 'Important!', 'masterstudy-lms-learning-management-system-pro' ); ?>
</h4>
<p class="masterstudy-update-notice__text">
	<?php esc_html_e( 'The latest update covers some important changes across the different areas of the plugin.', 'masterstudy-lms-learning-management-system-pro' ); ?> <?php echo wp_kses( __( 'We highly recommend you <a href="https://docs.stylemixthemes.com/masterstudy-lms/troubleshooting/update-issues"> back up your site and test the update on a staging site</a> before any updates.', 'masterstudy-lms-learning-management-system-pro' ), array( 'a' => array( 'href' => true ) ) ); ?>
</p>
<hr>
<h4>
	<i class="masterstudy-update-notice__header-icon fa fa-exclamation-circle"></i> <?php esc_html_e( 'Check Compatibility:', 'masterstudy-lms-learning-management-system-pro' ); ?>
</h4>
<p class="masterstudy-update-notice__text">
	<?php /* translators: %s: new plugin version */ ?>
	<?php echo esc_html( sprintf( __( 'The version of the free plugin you\'re using has not been tested with the latest update of MasterStudy LMS Pro %s.', 'masterstudy-lms-learning-management-system-pro' ), $new_version ) ); ?>
	<?php esc_html_e( 'To avoid issues, please update the free plugin before updating the MasterStudy LMS Pro plugin.', 'masterstudy-lms-learning-management-system-pro' ); ?>

	<?php // do not close the paragraph tag here, it will be closed by WP (@see wp-admin/includes/update.php) ?>
