<?php if ( ! class_exists( 'StmZoom' ) ) : ?>
	<div id="zoom_addon_install">
		<h3><?php esc_html_e( 'You need to install eRoom - Zoom Meetings & Webinar', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>
		<a href="#" class="button button-primary button-large"><?php esc_html_e( 'Install plugin', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
		<h4 class="message" style="display: none"><?php esc_html_e( 'Installing...', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>
	</div>
<?php else : ?>
<script>
	var stmAdminUrl = window.location;
	url = stmAdminUrl.origin + stmAdminUrl.pathname + '?page=stm_zoom_settings';
	window.location.href = url;
</script>
<?php endif; ?>
