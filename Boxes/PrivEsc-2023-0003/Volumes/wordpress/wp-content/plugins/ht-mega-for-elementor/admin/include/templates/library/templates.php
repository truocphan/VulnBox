 <?php
	/**
	 * Template library templates
	 */
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<script type="text/template" id="tmpl-htmega-template-library-header-actions">
	<div id="htmega-template-library-header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'htmega-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php echo __( 'Sync Library', 'htmega-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-htmega-template-library-logo">
    <span class="tmpl-htmega-template-library-logo-area">
        <img src="<?php echo esc_url(HTMEGA_ADDONS_PL_URL .'admin/assets/images/menu-icon.png'); ?>" />
    </span>
    <span class="tmpl-htmega-template-library-logo-title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-htmega-template-library-header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
	<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-htmega-template-library-header-menu-responsive">
	<div class="elementor-component-tab htmega-template-library-responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'htmega-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'htmega-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab htmega-template-library-responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'htmega-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'htmega-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab htmega-template-library-responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'htmega-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'htmega-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-htmega-template-library-loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php echo __( 'Loading', 'htmega-addons' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-htmega-template-library-preview">
    <iframe></iframe>
</script>

<script type="text/template" id="tmpl-htmega-template-library-insert-button">
	<a class="elementor-template-library-template-action htmega-template-library-template-insert elementor-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php echo __( 'Insert', 'htmega-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-htmega-template-library-get-pro-button">
	<a class="elementor-template-library-template-action elementor-button elementor-go-pro" href="https://wphtmega.com/pricing/" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php echo __( 'Go Pro', 'htmega-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-htmega-template-library-header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ htmega.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-htmega-template-library-header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'htmega-addons' ); ?></span>
</script>

<script type="text/template" id="tmpl-htmega-template-library-templates">
	<div id="elementor-template-library-toolbar">

		<div id="htmega-template-library-filter-area-wrapper">
			
		</div>

		<div id="elementor-template-library-filter-text-wrapper">
			<label for="htmega-template-library-filter-text" class="elementor-screen-only"><?php esc_html_e( 'Search Templates:', 'htmega-addons' ); ?></label>
			<input id="htmega-template-library-filter-text" placeholder="<?php esc_attr_e( 'Search', 'htmega-addons' ); ?>">
			<i class="eicon-search"></i>
		</div>

	</div>

	<div class="htmega-template-library-window">
		<div id="htmega-template-library-list"></div>
	</div>

</script>

<script type="text/template" id="htmega-template-library-template">

	<div class="elementor-template-library-template-body">
		<# if ( 'page' === type ) { #>
			<div class="elementor-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
		<# } else { #>
			<img class="htmega-template-library-thumbnail" src="{{ thumbnail }}">
		<# } #>
		<div class="htmega-template-library-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
		<# if ( obj.isPro ) { #>
		<span class="htmega-template-library-pro-badge"><?php esc_html_e( 'Pro', 'htmega-addons' ); ?></span>
		<# } #>
	</div>

	<div class="htmega-template-library-footer">
		<div class="htmega-template-library-template-info">
			<h5 class="htmega-template-library-template-title">{{{ title }}}</h5>
			<h6 class="htmega-template-library-template-type">{{{ type }}}</h6>
		</div>

		<div class="htmega-template-library-template-action-btn">
			{{{ htmega.library.getModal().getTemplateActionButton( obj ) }}}
			<a href="#" class="elementor-button htmega-template-library-preview-button">
				<i class="eicon-device-desktop" aria-hidden="true"></i>
				<?php esc_html_e( 'Preview', 'htmega-addons' ); ?>
			</a>
		</div>

	</div>

</script>

<script type="text/template" id="tmpl-elementor-htmega-library-templates-empty">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo esc_url(ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg'); ?>" class="elementor-template-library-no-results" />
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php echo __( 'Want to learn more about the HT Mega library?', 'htmega-addons' ); ?>
		<a class="elementor-template-library-blank-footer-link" href=<?php echo esc_url("https://wphtmega.com"); ?> target="_blank"><?php echo __( 'Click here', 'htmega-addons' ); ?></a>
	</div>
</script>