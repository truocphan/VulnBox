<?php
namespace JupiterX_Core\Raven\Modules\My_Account\Submodules;

defined( 'ABSPATH' ) || die();

class Navigation {
	private $widget;

	public $tabs = [];

	public function __construct( $widget ) {
		$this->widget = $widget;
		$this->tabs   = $this->get_menu_items();
	}

	/**
	 * Render our custom navigation template.
	 *
	 * This navigation menu's items have icon and also is wrapped in
	 * an additional div which helps us to control its layout (Horizontal/Vertical);
	 *
	 * @access public
	 * @since 2.5.0
	 */
	public function render_custom_navigation() {
		$settings   = $this->widget->get_settings_for_display();
		$icon_align = $settings['icon_alignment'];

		?>
		<div class="custom-my-account-nav-<?php echo $settings['tabs_layout']; ?>">
			<nav class="woocommerce-MyAccount-navigation">
				<ul>
				<?php foreach ( $this->tabs as $endpoint => $data ) {
					if ( empty( $endpoint ) ) {
						continue;
					}

					$full_url = wc_get_account_endpoint_url( $endpoint );

					if ( isset( $data['template'] ) ) {
						$base_url = trailingslashit( wc_get_account_endpoint_url( JX_MY_ACCOUNT_CUSTOM_ENDPOINT ) );
						$full_url = $base_url . $data['template'];
					}

					$this->widget->add_render_attribute( 'nav-item-' . $endpoint, 'href', $full_url );

					?>
					<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
						<a <?php echo $this->widget->get_render_attribute_string( 'nav-item-' . $endpoint ); ?>>
						<?php
						if ( in_array( $settings['icon_alignment'], [ 'right', 'below' ], true ) ) {
							echo esc_html( $data['label'] );
						}

						// Rendering Icon.
						if ( isset( $data['icon'] ) ) {
							$icon_render_string = $this->get_icon_render_strings( $data['icon'] );

							if ( false !== $icon_render_string ) {
								echo $icon_render_string;
							}
						}

						if ( in_array( $settings['icon_alignment'], [ 'left', 'above' ], true ) ) {
							echo esc_html( $data['label'] );
						}
						?>
						</a>
					</li>
				<?php } ?>
				</ul>
			</nav>
		</div>
		<?php
	}

	/**
	 * Retrieve navigation menu items based on repeater control.
	 *
	 * @return array
	 * @access public
	 * @since 2.5.0
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function get_menu_items() {
		$settings = $this->widget->get_settings_for_display();
		$woo_tabs = wc_get_account_menu_items();
		$result   = [];

		foreach ( $settings['tabs'] as $tab ) {
			// Scenario 1: Tabs which user has decided to "hide".
			if ( 'yes' === $tab['hide_tab'] ) {
				if ( isset( $tab['field_key'] ) ) {
					unset( $woo_tabs[ $tab['field_key'] ] );
				}

				continue;
			}

			// Scenario 2: Tabs belonging to 3rd party plugins that were uninstalled after creation of the widget.
			if ( isset( $tab['field_key'] ) && ! array_key_exists( $tab['field_key'], $woo_tabs ) ) {
				continue;
			}

			// Scenario 3: Default tabs with "Custom Template" disabled.
			if ( 'yes' === $tab['is_default'] && 'yes' !== $tab['custom_template_enabled'] ) {
				$result[ $tab['field_key'] ]['label'] = esc_html( $tab['tab_name'] );
				$result[ $tab['field_key'] ]['icon']  = $tab['tab_icon'];

				continue;
			}

			// Scenario 4: Default my account tabs with "Custom Template" enabled AND ALSO user added tabs.
			if ( isset( $tab['field_key'] ) ) {
				unset( $woo_tabs[ $tab['field_key'] ] );
			}

			$template_id = $tab['custom_template'];
			$template    = self::get_template_by_id( $template_id );

			if ( false === $template ) {
				continue;
			}

			$result[ $template_id ]['label']    = empty( esc_html( $tab['tab_name'] ) ) ? $template['title'] : esc_html( $tab['tab_name'] );
			$result[ $template_id ]['icon']     = $tab['tab_icon'];
			$result[ $template_id ]['template'] = $template['slug'];
		}

		// Check for 3rd party plugins that were installed after creation of the widget,
		// and add their link at the end of list.
		foreach ( $woo_tabs as $endpoint => $tab_label ) {
			if ( ! array_key_exists( $endpoint, $result ) ) {
				$result[ $endpoint ]['label'] = $tab_label;
			}
		}

		return $result;
	}

	public static function get_template_by_id( $template_id ) {
		$all_templates = \Elementor\Plugin::$instance->templates_manager->get_templates( [ 'local' ] );
		$key           = array_search( intval( $template_id ), array_column( $all_templates, 'template_id' ), true );

		if ( false === $key ) {
			return false;
		}

		$template = $all_templates[ $key ];
		$url      = $template['url'];
		parse_str( wp_parse_url( $url, PHP_URL_QUERY ), $url_parts );
		$template['slug'] = $url_parts['elementor_library'];

		return $template;
	}

	/**
	 * Retrieve render string of the tabs icons.
	 *
	 * @param Array $icon_data includes [library] and [value].
	 * @return string|false
	 * @access private
	 * @since 2.5.0
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	private function get_icon_render_strings( $icon_data ) {
		$font_icon    = '';
		$icon_fa      = '';
		$icon_svg_url = '';

		if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) && $icon_data['value'] ) {
			if ( 'svg' === $icon_data['library'] ) {
				$font_icon = \Elementor\Icons_Manager::render_uploaded_svg_icon( $icon_data['value'] );
			} else {
				$font_icon = \Elementor\Icons_Manager::render_font_icon( $icon_data );
			}
		}

		if ( 'svg' !== $icon_data['library'] && $icon_data['value'] ) {
			$icon_fa = $icon_data['value'];
		}

		if ( 'svg' === $icon_data['library'] && $icon_data['value'] ) {
			$icon_svg_url = $icon_data['value']['url'];
		}

		// ► Process scenarios ◄

		// 1: if font icon is available, it's preferred.
		if ( ! empty( $font_icon ) ) {
			return $font_icon;
		}

		// 2: Otherwise, when the user has used font awesome option.
		if ( ! empty( $icon_fa ) ) {
			return '<i class="' . esc_attr( $icon_fa ) . '"></i>';
		}

		// 3: Otherwise, when the user has used upload svg option.
		if ( ! empty( $icon_svg_url ) ) {
			return '<object type="image/svg+xml" data="' . esc_attr( $icon_svg_url ) . '"></object>';
		}

		// 4: if no icon
		return false;
	}
}
