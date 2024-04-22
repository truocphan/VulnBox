<?php
$url   = admin_url( 'admin-ajax.php?action=stm_lms_enable_addon&addon=certificate_builder' );
$class = 'disabled';
if ( is_ms_lms_addon_enabled( 'certificate_builder' ) ) {
	$url   = admin_url( 'admin.php?page=certificate_builder' );
	$class = 'enabled';
}
?>
<div class="certificate_banner">
	<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $class ); ?>"
		data-url="<?php echo esc_url( admin_url( 'admin.php?page=certificate_builder' ) ); ?>">
		<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/cert_builder.jpg' ); ?>"
			style="display: inline-block; vertical-align: top; margin-bottom: 10px;"/>
	</a>
</div>
