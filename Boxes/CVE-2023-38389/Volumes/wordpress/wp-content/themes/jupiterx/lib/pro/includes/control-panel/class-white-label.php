<?php
/**
 * White labeling class.
 *
 * @package JupiterX_Core\Framework\Control_Panel\White_Label
 *
 * @since 1.11.0
 */

if ( ! class_exists( 'JupiterX_Control_Panel_White_Label' ) ) {
	/**
	 * Settings.
	 *
	 * @since 1.11.0
	 */
	class JupiterX_Control_Panel_White_Label {

		/**
		 * Constructor.
		 *
		 * @since 1.11.0
		 */
		public function __construct() {
			add_filter( 'jupiterx_control_panel_sections', [ $this, 'enabled_pages' ], 999 );
			add_action( 'jupiterx_control_panel_settings_white_label', [ $this, 'settings_html' ] );
		}

		/**
		 * Set enabled pages in control panel.
		 *
		 * @since 1.11.0
		 *
		 * @param array $sections Control panel sections.
		 */
		public function enabled_pages( $sections ) {
			if ( ! jupiterx_is_white_label() ) {
				return $sections;
			}

			$enabled_pages = jupiterx_get_option( 'white_label_cpanel_pages', [] );

			if ( empty( $enabled_pages ) ) {
				$enabled_pages = [];
			}

			// Always show settings page.
			array_push( $enabled_pages, 'settings' );

			$sections = array_intersect_key( $sections, array_flip( $enabled_pages ) );

			return $sections;
		}

		/**
		 * Render white label settings on control panel.
		 *
		 * @since 1.11.0
		 * @SuppressWarnings(PHPMD.NPathComplexity)
		 */
		public function settings_html() {
			?>
			<div class="col-md-12"><hr></div>
			<div class="form-group col-md-12">
				<label for="jupiterx-cp-settings-white-label"><?php esc_html_e( 'White Label', 'jupiterx' ); ?></label>
				<input type="hidden" name="jupiterx_white_label" value="0">
				<div class="jupiterx-switch">
					<input type="checkbox" id="jupiterx-cp-settings-white-label-mode" name="jupiterx_white_label" value="1" <?php checked( jupiterx_get_option( 'white_label' ), true ); ?>>
					<label for="jupiterx-cp-settings-white-label-mode"></label>
				</div>
			</div>
			<div class="form-group col-md-6 <?php echo ! jupiterx_get_option( 'white_label' ) ? 'hidden' : ''; ?>" data-for="jupiterx_white_label">
				<label class="m-0"><?php esc_html_e( 'Control Panel Pages', 'jupiterx' ); ?></label>
				<small class="form-text text-muted mb-2"><?php esc_html_e( 'Enable or disable control panel pages.', 'jupiterx' ); ?></small>
				<input type="hidden" name="jupiterx_white_label_cpanel_pages" value="">
				<?php
				$pages = [
					'home'          => esc_html__( 'Home', 'jupiterx' ),
					'plugins'       => esc_html__( 'Plugins', 'jupiterx' ),
					'templates'     => esc_html__( 'Templates', 'jupiterx' ),
					'image_size'    => esc_html__( 'Image Sizes', 'jupiterx' ),
					'system_status' => esc_html__( 'System Status', 'jupiterx' ),
					'updates'       => esc_html__( 'Updates', 'jupiterx' ),
				];

				$enabled_pages = jupiterx_get_option( 'white_label_cpanel_pages', array_keys( $pages ) );

				if ( empty( $enabled_pages ) ) {
					$enabled_pages = [];
				}

				foreach ( $pages as $page_id => $page_name ) :
					echo wp_kses( '<div class="custom-control custom-checkbox">', [ 'div' => [ 'class' => [] ] ] );
					echo wp_kses( '<input type="checkbox" class="custom-control-input" name="jupiterx_white_label_cpanel_pages[]" ' . ( ( in_array( $page_id, $enabled_pages, true ) ) ? 'checked="checked"' : '' ) . ' value="' . esc_attr( $page_id ) . '" id="jupiterx_white_label_cpanel_pages_' . esc_attr( $page_id ) . '">', [
						'input' => [
							'type' => [],
							'class' => [],
							'name' => [],
							'checked' => [],
							'value' => [],
							'id' => [],
						],
					]);
					echo wp_kses( '<label class="custom-control-label" for="jupiterx_white_label_cpanel_pages_' . esc_attr( $page_id ) . '">' . $page_name . '</label>', [
						'label' => [
							'class' => [],
							'for' => [],
						],
					] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo wp_kses( '</div>', [ 'div' => [] ] );
				endforeach;
				?>
			</div>
			<div class="form-group col-md-6 <?php echo ! jupiterx_get_option( 'white_label' ) ? 'hidden' : ''; ?>" data-for="jupiterx_white_label">
				<label for="jupiterx-white-label-cpanel-logo"><?php esc_html_e( 'Control Panel Logo', 'jupiterx' ); ?></label>
				<div class="input-group jupiterx-image-uploader <?php echo jupiterx_get_option( 'white_label_cpanel_logo' ) ? 'has-image' : ''; ?>">
					<input class="jupiterx-form-control" value="<?php echo esc_attr( jupiterx_get_option( 'white_label_cpanel_logo' ) ); ?>" name="jupiterx_white_label_cpanel_logo" placeholder="<?php esc_html_e( 'Select an image', 'jupiterx' ); ?>" type="text" />
					<div class="input-group-append">
						<a class="btn btn-danger remove-button" href="#"><?php esc_html_e( 'Remove', 'jupiterx' ); ?></a>
						<a class="btn btn-secondary upload-button" href="#"><?php esc_html_e( 'Upload', 'jupiterx' ); ?></a>
					</div>
				</div>
				<small class="form-text text-muted mb-2"><?php esc_html_e( 'Change control panel logo.', 'jupiterx' ); ?></small>
			</div>
			<div class="form-group col-md-6 <?php echo ! jupiterx_get_option( 'white_label' ) ? 'hidden' : ''; ?>" data-for="jupiterx_white_label">
				<label for="jupiterx-cp-settings-white-label-text-occurence"><?php esc_html_e( 'Text Occurence', 'jupiterx' ); ?></label>
				<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-white-label-text-occurence" value="<?php echo esc_attr( jupiterx_get_option( 'white_label_text_occurence' ) ); ?>" name="jupiterx_white_label_text_occurence">
				<small class="form-text text-muted mb-2"><?php esc_html_e( 'Replace \'Jupiter X\' text to the entire admin pages.', 'jupiterx' ); ?></small>
			</div>
			<div class="form-group col-md-6 <?php echo ! jupiterx_get_option( 'white_label' ) ? 'hidden' : ''; ?>" data-for="jupiterx_white_label">
				<label for="jupiterx-cp-settings-white-label-menu-icon"><?php esc_html_e( 'Menu Icon', 'jupiterx' ); ?></label>
				<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-white-label-menu-icon" value="<?php echo esc_attr( jupiterx_get_option( 'white_label_menu_icon' ) ); ?>" name="jupiterx_white_label_menu_icon" placeholder="dashicons-admin-generic">
				<small class="form-text text-muted mb-2">
					<?php
					printf(
						// translators: 1: Choose icon link.
						esc_html__( '%1$s an icon and paste the class name.', 'jupiterx' ),
						'<a href="https://developer.wordpress.org/resource/dashicons" target="_blank">' . esc_html__( 'Choose', 'jupiterx' ) . '</a>'
					);
					?>
				</small>
			</div>
			<div class="form-group col-md-6 <?php echo ! jupiterx_get_option( 'white_label' ) ? 'hidden' : ''; ?>" data-for="jupiterx_white_label">
				<label for="jupiterx-cp-settings-white-label-help-links"><?php esc_html_e( 'Help Links', 'jupiterx' ); ?></label>
				<input type="hidden" name="jupiterx_white_label_help_links" value="0">
				<div class="jupiterx-switch">
					<input type="checkbox" id="jupiterx-cp-settings-white-label-help-links" name="jupiterx_white_label_help_links" value="1" <?php checked( jupiterx_get_option( 'white_label_help_links', true ), true ); ?>>
					<label for="jupiterx-cp-settings-white-label-help-links"></label>
				</div>
				<small class="form-text text-muted mb-2"><?php esc_html_e( 'Enable help links from control panel, customizer and meta options.', 'jupiterx' ); ?></small>
			</div>
			<div class="form-group col-md-6 <?php echo ! jupiterx_get_option( 'white_label' ) ? 'hidden' : ''; ?>" data-for="jupiterx_white_label">
				<label for="jupiterx-cp-settings-white-label-menu-help"><?php esc_html_e( 'Menu Help', 'jupiterx' ); ?></label>
				<input type="hidden" name="jupiterx_white_label_menu_help" value="0">
				<div class="jupiterx-switch">
					<input type="checkbox" id="jupiterx-cp-settings-white-label-menu-help" name="jupiterx_white_label_menu_help" value="1" <?php checked( jupiterx_get_option( 'white_label_menu_help', true ), true ); ?>>
					<label for="jupiterx-cp-settings-white-label-menu-help"></label>
				</div>
				<small class="form-text text-muted mb-2"><?php esc_html_e( 'Enable help link from menu.', 'jupiterx' ); ?></small>
			</div>
			<div class="form-group col-md-12 <?php echo ! jupiterx_get_option( 'white_label' ) ? 'hidden' : ''; ?>" data-for="jupiterx_white_label">
				<div class="alert alert-info">
				<?php
				printf(
					// translators: The Child theme link.
					esc_html__( 'For further customization on theme name, slug, thumbnail and description that appears in Appearance > Themes, please install the %s.', 'jupiterx' ),
					'<a href="https://themes.artbees.net/docs/installing-the-child-theme-for-jupiter-x/" target="_blank">' . esc_html__( 'Child theme', 'jupiterx' ) . '</a>'
				);
				?>
				</div>
			</div>
			<?php
		}
	}

	new JupiterX_Control_Panel_White_Label();
}
