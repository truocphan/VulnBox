<?php
/**
 * Views for the Elementor editor.
 *
 * @since 1.6.12
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<script type="text/template" id="tmpl-masteriyo-single-course-page-preview">
	<img src="<?php echo esc_url( masteriyo_get_plugin_url() . '/addons/elementor-integration/img/single-course-page-preview.jpg' ); ?>" width="100%">
</script>

<script type="text/template" id="tmpl-masteriyo-course-archive-page-preview">
	<img src="<?php echo esc_url( masteriyo_get_plugin_url() . '/addons/elementor-integration/img/course-archive-page-preview.jpg' ); ?>" width="100%">
</script>

<script type="text/template" id="tmpl-masteriyo-template-library-header-actions">
	<a id="masteriyo-template-library-header-import" class="elementor-template-library-template-action elementor-button e-primary">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php echo esc_html__( 'Import', 'masteriyo' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-masteriyo-templates-modal__header__logo">
	<span class="elementor-templates-modal__header__logo__icon-wrapper">
		<div class="masteriyo-templates-button" tab-index="0">
			<?php masteriyo_get_svg( 'logo', true ); ?>
		</div>
		<style>
			.elementor-templates-modal__header__logo__icon-wrapper svg {
				width: 18px;
				height: 18px;
			}
		</style>
	</span>
	<span class="elementor-templates-modal__header__logo__title">{{{ title }}}</span>
</script>
<?php
