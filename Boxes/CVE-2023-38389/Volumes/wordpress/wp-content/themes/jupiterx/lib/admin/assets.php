<?php
/**
 * Manage admin assets.
 *
 * @package JupiterX\Framework\Admin
 *
 * @since 1.3.0
 */

jupiterx_add_smart_action( 'admin_enqueue_scripts', 'jupiterx_enqueue_admin_scripts' );
/**
 * Enqueue admin scripts.
 *
 * @since 1.3.0
 */
function jupiterx_enqueue_admin_scripts() {
	wp_enqueue_style( 'jupiterx-admin-icons', JUPITERX_ASSETS_URL . 'dist/css/icons-admin.css', [], JUPITERX_VERSION );
	wp_enqueue_style( 'jupiterx-common', JUPITERX_ASSETS_URL . 'dist/css/common' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );

	if ( jupiterx_is_screen( 'widgets' ) ) {
		wp_enqueue_script( 'wp-color-picker-alpha', JUPITERX_ASSETS_URL . 'dist/js/wp-color-picker-alpha' . JUPITERX_MIN_JS . '.js', [ 'wp-color-picker' ], JUPITERX_VERSION, true );
		wp_enqueue_script( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/js/jupiterx-modal' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
		wp_enqueue_script( 'jupiterx-gsap', JUPITERX_ADMIN_URL . 'control-panel/assets/lib/gsap/gsap' . JUPITERX_MIN_JS . '.js', [], '1.19.1', true );
		wp_enqueue_style( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/css/jupiterx-modal' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );

		jupiterx_wpcolorpickeralpha_localize();
	}

	wp_enqueue_script( 'jupiterx-common', JUPITERX_ASSETS_URL . 'dist/js/common' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'wp-util', 'updates' ], JUPITERX_VERSION, true );
	wp_localize_script(
		'jupiterx-common',
		'jupiterxUtils',
		[
			'proBadge'    => jupiterx_get_pro_badge(),
			'proBadgeUrl' => jupiterx_get_pro_badge_url(),
			'helpLinks'   => jupiterx_is_help_links(),
			'nonce'       => wp_create_nonce( 'jupiterx-nonce' ),
		]
	);
	wp_localize_script(
		'jupiterx-common',
		'jupiterx_admin_textdomain',
		[
			'add_custom_sidebar_modal_title' => esc_html__( 'Add New Custom Sidebar', 'jupiterx' ),
			'add_custom_sidebar'             => esc_html__( 'Add Custom Sidebar', 'jupiterx' ),
			'delete_custom_sidebar'          => esc_html__( 'Delete Custom Sidebar', 'jupiterx' ),
			'deleting'                       => esc_html__( 'Deleting', 'jupiterx' ),
			'learn_pro_features'             => __( 'Learn more about Pro features', 'jupiterx' ),
			'pro_upgrade_title'              => __( 'Jupiter X is upgraded', 'jupiterx' ),
			'pro_upgrade_text'               => __( 'Congrats! you have successfully upgraded to Jupiter X Pro. Now you can enjoy working with Jupiter X at its maximum potential.', 'jupiterx' ),
			'activated_title'                => __( 'Jupiter X is activated', 'jupiterx' ),
			'activated_text'                 => __( 'Congrats! Jupiter X is activated successfully. Now you can enjoy working with Jupiter at its maximum potential.', 'jupiterx' ),
			'register_fail_title'            => __( 'Oops! Registration was unsuccessful.', 'jupiterx' ),
			'register_fail_text'             => __( 'Your API key could not be verified. There is no such API key or it is used in another site', 'jupiterx' ),
			'plugin_remove_title'            => __( 'Plugin removed', 'jupiterx' ),
			'plugin_removed_text'            => __( 'You have successfully removed Jupiter X Pro plugin.', 'jupiterx' ),
			'uninstall_pro_title'            => __( 'Uninstalling Jupiter X Pro Plugin', 'jupiterx' ),
			// translators: 1: Pro Plugin notice. 2. Plugins page. 3. Installed Plugin. 4. Delete plugin. 5. Pro plugin name. 6. plugin.
			'important_notice_title'         => __( 'Important Notice!', 'jupiterx' ),
			'important_notice_text'          => sprintf(
				'%1$s<br><br><small>%2$s <strong>%3$s</strong>, %4$s <strong>%5$s</strong> %6$s.</small>',
				__( 'Since Jupiter X v1.6.0, you will no longer need Jupiter X Pro plugin to be able to use premium features as we have moved those features to theme itself for a better user experience. Click the button down below to deactivate and delete the plugin from your site', 'jupiterx' ),
				__( 'If the button does not work, please go to', 'jupiterx' ),
				__( 'Plugins &gt; Installed Plugins', 'jupiterx' ),
				__( 'deactivate and delete the', 'jupiterx' ),
				__( 'Jupiter X Pro', 'jupiterx' ),
				__( 'plugin', 'jupiterx' )
			),
			'done'                           => __( 'Done', 'jupiterx' ),
			'attachment_mp4_title' => esc_html__( 'Select MP4', 'jupiterx' ),
			'attachment_mp4_text' => esc_html__( 'Attach MP4', 'jupiterx' ),
		]
	);

	wp_add_inline_style( 'jupiterx-common', '
#toplevel_page_jupiterx .menu-icon-generic div.wp-menu-image {
	background: url(' . esc_url( JUPITERX_ADMIN_ASSETS_URL ) . 'images/jupiterx-admin-menu-icon.svg) no-repeat 7px 6px !important;
	background-size: 22px auto !important;
	opacity: 0.6;
}
#toplevel_page_jupiterx .menu-icon-generic div.wp-menu-image:before {
	content: " ";
}' );

	if ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_cpanel_logo' ) ) {
		wp_add_inline_style( 'jupiterx-common', '
span.jupiterx-cp-jupiterx-logo {
	height: 55px;
	margin-top: 0;
	background: url( ' . esc_url( jupiterx_get_option( 'white_label_cpanel_logo' ) ) . ') no-repeat center center;
	background-size: 100%;
}' );
	}

	if ( 'jupiterx' === jupiterx_get( 'page' ) ) {
		wp_enqueue_style( 'jupiterx-welcome', JUPITERX_ASSETS_URL . 'dist/css/welcome' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_script( 'jupiterx-welcome', JUPITERX_ASSETS_URL . 'dist/js/welcome' . JUPITERX_MIN_CSS . '.js', [ 'jquery', 'wp-util' ], JUPITERX_VERSION, true );

		wp_localize_script(
			'jupiterx-welcome',
			'jupiterxWelcome',
			[
				'controlPanelUrl' => admin_url( 'admin.php?page=jupiterx' ),
				'isPremium' => jupiterx_is_premium(),
				'i18n' => [
					'defaultText'    => __( 'Install and activate all required plugins', 'jupiterx' ),
					'installText' => __( 'Installing required plugins', 'jupiterx' ),
					'activateText'   => __( 'Activating required plugins', 'jupiterx' ),
					'redirecting'   => __( 'Redirecting', 'jupiterx' ),
					'failedInstallText'   => __( 'An error occurred while downloading the plugin(s). ', 'jupiterx' ),
					'failedActivateText'   => __( 'An error occurred while installing & activating the plugin(s). ', 'jupiterx' ),
					'failedActionLinks'   => sprintf(
						// translators: 1: Site health url. 2. Team support url.
						__( 'Please check your<a href="%1$s" target="_blank"> site health </a> or contact our <a href="%2$s" target="_blank">support team</a>.', 'jupiterx' ),
						esc_url( admin_url( 'site-health.php' ) ),
						esc_url( 'https://themes.artbees.net/docs/the-new-support-platform/' )
					),
				],
			]
		);
	}
	wp_add_inline_script( 'jupiterx-common', 'var jupiterxPremium = true;', 'before' );
	wp_add_inline_script( 'jupiterx-common', 'var jupiterXControlPanelURL = "' . esc_url( admin_url( 'admin.php?page=jupiterx' ) ) . '";', 'before' );

	if ( jupiterx_is_callable( 'JupiterX_Pro' ) && method_exists( 'JupiterX_Pro', 'plugin_name' ) ) {
		wp_add_inline_script( 'jupiterx-common', '
			( function() {
				if ( typeof jupiterx === \'object\' && typeof jupiterx.uninstallPro !== \'undefined\' ) {
					jupiterx.uninstallPro();
				}
			} )( );
		', 'after' );
	}
}

jupiterx_add_smart_action( 'admin_print_footer_scripts', 'jupiterx_print_admin_templates' );
jupiterx_add_smart_action( 'jupiterx_print_templates', 'jupiterx_print_admin_templates' );
/**
 * Print admin JS templates.
 *
 * @since 1.3.0
 */
function jupiterx_print_admin_templates() {
	?>
	<?php
	?>
	<script type="text/html" id="tmpl-jupiterx-upgrade">
		<div class="jupiterx-upgrade">
			<div class="jupiterx-upgrade-step jupiterx-upgrade-buy active">
				<div class="jupiterx-upgrade-count">
					<span class="jupiterx-upgrade-num">1</span>
				</div>
				<div class="jupiterx-upgrade-content">
					<div class="jupiterx-upgrade-title">
						<?php esc_html_e( 'Get a Jupiter X license', 'jupiterx' ); ?>
						<div class="jupiterx-upgrade-help-buy">
							<a target="_blank" href="https://themes.artbees.net/docs/upgrading-to-pro">
								<i class="jupiterx-icon-question-circle"></i>
								<?php esc_html_e( 'Help', 'jupiterx' ); ?>
							</a>
						</div>
					</div>
					<a href="{{ data.url || 'https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775?ref=artbees&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign' }}" target="_blank" class="jupiterx-upgrade-buy-pro btn btn-primary"><?php esc_html_e( 'Buy Jupiter X Pro', 'jupiterx' ); ?></a>
				</div>
			</div>
			<div class="jupiterx-upgrade-step jupiterx-upgrade-activate-key">
				<div class="jupiterx-upgrade-count">
					<span class="jupiterx-upgrade-num">2</span>
				</div>
				<div class="jupiterx-upgrade-content">
					<div class="jupiterx-upgrade-title"><?php esc_html_e( 'Activate PRO version', 'jupiterx' ); ?></div>
					<div class="form-inline jupiterx-upgrade-api-field">
						<input type="text" class="jupiterx-form-control jupiterx-upgrade-api-key" placeholder="<?php esc_html_e( 'Enter your API key', 'jupiterx' ); ?>">
						<a class="jupiterx-upgrade-help-icon" href="https://themes.artbees.net/docs/getting-an-api-key" target="_blank">
							<i class="jupiterx-icon-question-circle"></i>
							<span class="screen-reader-text"><?php esc_html_e( 'Getting an API key', 'jupiterx' ); ?></span>
						</a>
					</div>
					<button type="submit" class="btn btn-primary jupiterx-upgrade-activate"><?php esc_html_e( 'Activate Product', 'jupiterx' ); ?></button>
				</div>
			</div>
			<div class="jupiterx-upgrade-step jupiterx-upgrade-install-plugin">
				<div class="jupiterx-upgrade-count">
					<span class="jupiterx-upgrade-num">3</span>
				</div>
				<div class="jupiterx-upgrade-content">
					<div class="jupiterx-upgrade-title"><?php esc_html_e( 'Install Jupiter X Pro plugin', 'jupiterx' ); ?></div>
					<div class="jupiterx-upgrade-install-progress"></div>
				</div>
			</div>
		</div>
	</script>
	<script type="text/html" id="tmpl-jupiterx-activate">
		<div class="jupiterx-upgrade-step jupiterx-upgrade-activate-key active">
			<div class="jupiterx-upgrade-content">
				<div class="form-inline jupiterx-upgrade-api-field">
					<input type="text" class="jupiterx-form-control jupiterx-upgrade-api-key" placeholder="<?php esc_html_e( 'Enter your API key', 'jupiterx' ); ?>">
					<a class="jupiterx-upgrade-help-icon" href="https://themes.artbees.net/docs/getting-an-api-key" target="_blank">
						<i class="jupiterx-icon-question-circle"></i>
						<span class="screen-reader-text"><?php esc_html_e( 'Getting an API key', 'jupiterx' ); ?></span>
					</a>
				</div>
				<button type="submit" class="btn btn-primary jupiterx-upgrade-activate"><?php esc_html_e( 'Activate Product', 'jupiterx' ); ?></button>
			</div>
		</div>
	</script>
	<?php
	?>
	<script type="text/html" id="tmpl-jupiterx-progress-bar">
		<div class="progress">
			<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 20%"></div>
		</div>
	</script>
	<?php
	?>
	<script type="text/html" id="tmpl-jupiterx-cp-registration">
		<div class="jupiterx-upgrade-step jupiterx-upgrade-activate-key active">
			<div class="jupiterx-upgrade-content">
				<div class="form-inline jupiterx-upgrade-api-field">
					<input type="text" class="jupiterx-form-control jupiterx-purchase-code-mode-element d-block jupiterx-upgrade-email" id="jupiterx-cp-register-email" placeholder="<?php esc_html_e( 'Email Address', 'jupiterx' ); ?>">
					<a class="jupiterx-tooltip jupiterx-purchase-code-mode-element" data-content="<?php esc_attr_e( 'This address will be used to provide you technical support.', 'jupiterx' ); ?>" href="#" data-toggle="popover" data-trigger="focus" data-placement="top"></a>

					<input type="text" class="jupiterx-form-control jupiterx-purchase-code-mode-element d-block jupiterx-upgrade-api-key" id="jupiterx-cp-register-purchase-code-input" placeholder="<?php esc_html_e( 'Envato purchase code', 'jupiterx' ); ?>">
					<a class="jupiterx-tooltip jupiterx-purchase-code-mode-element" data-content="<?php esc_attr_e( "Please check <a href='https://themes.artbees.net/docs/getting-the-purchase-code/' target='_blank'>this</a> to learn how to find your purchase code.", 'jupiterx' ); ?>" href="#" data-toggle="popover" data-trigger="focus" data-placement="top"></a>

					<input type="text" class="jupiterx-form-control jupiterx-api-mode-element d-none jupiterx-upgrade-api-key" id="jupiterx-cp-register-api-input" placeholder="<?php esc_html_e( 'Artbees API key', 'jupiterx' ); ?>">
					<a class="jupiterx-tooltip jupiterx-api-mode-element d-none" data-content="<?php esc_attr_e( "Please check <a href='https://themes.artbees.net/docs/getting-an-api-key/' target='_blank'>this</a> to learn how to find your API key.", 'jupiterx' ); ?>" href="#" data-toggle="popover" data-trigger="focus" data-placement="top"></a>

					<a href="#" class="jupiterx-upgrade-activate" id="jupiterx-api-key-switch"><?php esc_html_e( 'Or insert API key', 'jupiterx' ); ?></a>
					<span id="jupiterx-cp-mailing-list-option-wrapper" class="jupiterx-purchase-code-mode-element d-flex">
						<input type="checkbox" class="jupiterx-form-control" id="jupiterx-cp-register-mailing-list">
						<label for="jupiterx-cp-register-mailing-list">
							<span><?php esc_html_e( 'I’d like to subscribe to Artbees Themes newsletter to get product updates & news, early access, weekly digests and more.', 'jupiterx' ); ?></span>
						</label>
					</span>
					<span id="jupiterx-cp-gdpr-option-wrapper" class="jupiterx-purchase-code-mode-element d-flex">
						<input type="checkbox" class="jupiterx-form-control" id="jupiterx-cp-register-gdpr">
						<label for="jupiterx-cp-register-gdpr">
							<span>
								<?php printf( 'I consent to Artbees Themes collecting my personal information according to its <a href="%s" target="_blank">%s</a> and <a href="%s" target="_blank">%s</a>', 'https://themes.artbees.net/privacy-policy/', esc_html__( 'Privacy policy', 'jupiterx' ), 'https://themes.artbees.net/terms-of-use', esc_html__( 'Terms of use', 'jupiterx' ) ); ?>
							</span>
						</label>

					</span>
					<?php wp_nonce_field( 'license_manager', 'license-manager-nonce' ); ?>
				</div>

			</div>
		</div>
	</script>
	<?php
	?>
	<?php
}

add_action( 'admin_init', 'jupiterx_admin_scripts' );
/**
 * Register admin scripts.
 *
 * @since 1.11.0
 */
function jupiterx_admin_scripts() {
	wp_register_style( 'jupiterx-templates', JUPITERX_ASSETS_URL . 'dist/css/templates' . JUPITERX_MIN_CSS . '.css', [ 'jupiterx-modal' ], JUPITERX_VERSION );
	wp_register_script( 'jupiterx-templates', JUPITERX_ASSETS_URL . 'dist/js/templates' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'underscore', 'jupiterx-modal' ], JUPITERX_VERSION, true );
	wp_localize_script( 'jupiterx-templates', 'jupiterxTemplates', [
		'siteUrl'           => home_url(),
		'adminAjaxUrl'      => admin_url( 'admin-ajax.php' ),
		'nonce'             => wp_create_nonce( 'jupiterx-nonce' ),
		'proBadgeUrl'       => jupiterx_get_pro_badge_url(),
		'isPremium'         => jupiterx_is_premium(),
		'upgradeLink'       => esc_url( jupiterx_upgrade_link( 'templates' ) ),
		'template'          => jupiterx_get_option( 'template_installed_id', null ),
		'api'               => 'https://themes.artbees.net/wp-json/templates/v1',
		'i18n'              => [
			'all'                   => esc_html__( 'All', 'jupiterx' ),
			'empty'                 => esc_html__( 'No template found.', 'jupiterx' ),
			'emptyInfo'             => esc_html__( 'Clear some filters and try again.', 'jupiterx' ),
			'loadMore'              => esc_html__( 'Load More', 'jupiterx' ),
			'import'                => esc_html__( 'Import', 'jupiterx' ),
			'preview'               => esc_html__( 'Preview', 'jupiterx' ),
			'confirm'               => esc_html__( 'Confirm', 'jupiterx' ),
			'cancel'                => esc_html__( 'Cancel', 'jupiterx' ),
			'discard'               => esc_html__( 'Discard', 'jupiterx' ),
			'install'               => esc_html__( 'Install', 'jupiterx' ),
			'yes'                   => esc_html__( 'Yes', 'jupiterx' ),
			'askContinue'           => esc_html__( 'Are you sure to continue?', 'jupiterx' ),
			'installTitle'          => esc_html__( 'Important Notice', 'jupiterx' ),
			'installText'           => __( 'You are about to install <strong>{template}</strong> template. Installing a new template will remove all current data on your website. Are you sure you want to proceed?', 'jupiterx' ),
			'mediaTitle'            => esc_html__( 'Include Images and Videos?', 'jupiterx' ),
			'mediaText'             => sprintf(
				/* translators: Learn more URL */
				__( 'Would you like to import images and videos as preview? <br> Notice that all images are <strong>strictly copyrighted</strong> and you need to acquire the license in case you want to use them on your project. <a href="%s" target="_blank">Learn More</a>', 'jupiterx' ),
				'https://themes.artbees.net/docs/installing-a-template'
			),
			'mediaConfirm'          => esc_html__( 'Do not include', 'jupiterx' ),
			'mediaCancel'           => esc_html__( 'Include', 'jupiterx' ),
			'progressTitle'         => esc_html__( 'Installing in progress...', 'jupiterx' ),
			'progressBackup'        => esc_html__( 'Backup database', 'jupiterx' ),
			'progressPackage'       => esc_html__( 'Downloading package', 'jupiterx' ),
			'progressPlugins'       => esc_html__( 'Installing required plugins...', 'jupiterx' ),
			'progressInstall'       => esc_html__( 'Installing in progress...', 'jupiterx' ),
			'completedTitle'        => esc_html__( 'All Done!', 'jupiterx' ),
			'completedText'         => esc_html__( 'Template is successfully installed.', 'jupiterx' ),
			'errorTitle'            => esc_html__( 'Something went wrong!', 'jupiterx' ),
			'errorText'             => esc_html__( 'There is an error while installing the template, please contact support.', 'jupiterx' ),
			'customTitle'           => esc_html__( 'Choose how you want to import this template:', 'jupiterx' ),
			'customMediaText'       => esc_html__( 'Include media (Copyrighted).', 'jupiterx' ),
			'completeImportTitle'   => esc_html__( 'Full import ', 'jupiterx' ),
			'completeImportText'    => esc_html__( 'Your current content, settings, widgets, etc. will be removed and the database will be reset. New page contents and settings will be replaced.', 'jupiterx' ),
			'completeImportWarning' => esc_html__( 'All your current content, settings, widgets, etc. will be removed and the new content will be replaced.', 'jupiterx' ),
			'partialImportTitle'    => esc_html__( 'Content import', 'jupiterx' ),
			'partialImportText'     => esc_html__( 'Keep your current content, settings, widgets, etc. Only the new page contents will be imported.', 'jupiterx' ),
			'plugins_used'          => __( 'Plugins Used', 'jupiterx' ),
		],
	] );
}
