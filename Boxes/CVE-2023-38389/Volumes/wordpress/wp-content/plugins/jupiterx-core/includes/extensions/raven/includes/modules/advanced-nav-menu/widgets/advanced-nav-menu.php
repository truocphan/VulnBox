<?php
namespace JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Module;
use JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Controls;
use JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Walker;
use Elementor\Plugin as Elementor;
use Elementor\Utils as Elementor_Utils;
use Elementor\Icons_Manager as Icon_Manager;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Advanced_Nav_Menu extends Base_Widget {

	public function get_name() {
		return 'raven-advanced-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Advanced Menu', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-advanced-nav-menu';
	}

	public function get_script_depends() {
		if ( wp_script_is( 'smartmenus', 'registered' ) ) {
			wp_dequeue_script( 'smartmenus' );
			wp_deregister_script( 'smartmenus' );
		}

		return [ 'jupiterx-core-raven-smartmenus', 'jupiterx-core-raven-url-polyfill' ];
	}

	/**
	 * Get active breakpoint of the Elementor.
	 *
	 * @access public
	 * @since 2.5.0
	 */
	public function get_active_breakpoints() {
		$all_breakpoints = Elementor::$instance->breakpoints->get_breakpoints_config();

		$result = [];

		foreach ( $all_breakpoints as $device => $config ) {
			if ( $config['is_enabled'] ) {
				$result[ $device ] = [
					'label' => $config['label'],
					'size'  => $config['value'],
				];
			}
		}

		return $result;
	}

	protected function register_controls() {
		new Controls\Tab_Content( $this );
		new Controls\Tab_Style( $this );
	}

	protected function render() {
		$settings    = $this->get_settings_for_display();
		$id          = $this->get_id();
		$main_layout = $settings['layout'];

		$menu_template = ( new Walker\Walker( $this, $id ) )->menu;

		if ( false === $menu_template ) {
			$this->render_error();
			return;
		}

		$this->add_menu_attributes( $settings );

		$this->render_toggle_button( $settings );

		?>
		<!-- Main Menu -->
		<nav <?php echo $this->get_render_attribute_string( 'menu' ); ?>>
			<?php
			if ( 'offcanvas' === $main_layout ) {
				$this->render_close_button();
				echo $this->render_logo( 'side' );
			}

			echo $menu_template;
			?>
		</nav>
		<?php

		if ( ! $this->mobile_menu_exists( $settings ) ) {
			return;
		}

		$mobile_layout = $settings['mobile_layout'];

		?>
		<!-- Mobile Menu -->
		<nav <?php echo $this->get_render_attribute_string( 'mobile_menu' ); ?>>
			<?php
			if ( 'full-screen' === $mobile_layout || 'side' === $mobile_layout ) {
				$this->render_close_button();
			}

			if ( 'side' === $settings['mobile_layout'] ) {
				echo $this->render_logo( 'side' );
			}
			?>
			<div class="raven-container">
				<?php echo str_replace( "menu-{$id}", "menu-mobile-{$id}", $menu_template ); ?>
			</div>
		</nav>
		<?php
	}

	private function mobile_menu_exists( $settings ) {
		$main_menu_needs_mobile   = in_array( $settings['layout'], [ 'horizontal', 'vertical' ], true );
		$is_mobile_breakpoint_set = ! empty( $settings['mobile_breakpoint'] );

		return $main_menu_needs_mobile && $is_mobile_breakpoint_set;
	}

	private function toggle_button_exists( $settings ) {
		$main_menu_needs_toggle   = in_array( $settings['layout'], [ 'dropdown', 'offcanvas' ], true );
		$is_mobile_breakpoint_set = ! empty( $settings['mobile_breakpoint'] );

		return $main_menu_needs_toggle || $is_mobile_breakpoint_set;
	}

	private function render_toggle_button( $settings ) {
		if ( ! $this->toggle_button_exists( $settings ) ) {
			return;
		}

		$custom_toggle = 'yes' === $settings['custom_toggle_button'];
		$custom_icon   = $custom_toggle ? $settings['custom_toggle_button_icon'] : null;
		$animation     = $settings['toggle_button_animation'];
		$is_hamburger  = ! $custom_toggle && 'none' !== $animation;

		?>
		<!-- Menu Toggle Button -->
		<div class="raven-adnav-menu-toggle">
			<div class="raven-adnav-menu-toggle-button">
			<?php
			if ( $is_hamburger ) {
						?>
						<div class="hamburger hamburger--<?php echo $animation; ?>">
							<div class="hamburger-box">
								<div class="hamburger-inner"></div>
							</div>
						</div>
					</div>
				</div>
				<?php
				return;
			}

			?>
				<div class="toggle-button-custom">
				<?php

				if ( 'none' === $animation && ! $custom_toggle ) {
								?>
								<i class="fa fa-bars"></i>
							</div>
						</div>
					</div>
					<?php
					return;
				}

				?>
					<?php echo $this->render_icon( $custom_icon ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function render_close_button() {
		?>
		<div class="raven-adnav-menu-close-button">
			<span class="raven-adnav-menu-close-icon">&times;</span>
		</div>
		<?php
	}

	/**
	 * Render logo in the menu.
	 *
	 * @param string $type One of the values "side" or "center".
	 *
	 * @SuppressWarnings(CyclomaticComplexity)
	 * @SuppressWarnings(NPathComplexity)
	 */
	public function render_logo( $type ) {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings[ "{$type}_logo" ] ) {
			return '';
		}

		$devices = [ '' ];

		foreach ( $this->get_active_breakpoints() as $device => $data ) {
			$devices[] = "_{$device}";
		}

		$logo_skins = [];

		foreach ( $devices as $device ) {
			$device_setting = 'primary';

			if ( ! empty( $settings[ "{$type}_logo_skin{$device}" ] ) ) {
				$device_setting = $settings[ "{$type}_logo_skin{$device}" ];
			}

			$logo_skin = 'primary' !== $device_setting ? "jupiterx_logo_{$device_setting}" : 'jupiterx_logo';

			if ( in_array( $logo_skin, $logo_skins, true ) ) {
				$this->add_render_attribute( $logo_skins[ $logo_skin ], 'class', "raven-adnav-menu-{$type}-logo{$device}" );
				continue;

			}

			$logo_skins[ $logo_skin ] = $logo_skin;

			$image_src = get_theme_mod( $logo_skin, '' );

			if ( empty( $image_src ) ) {
				$image_src = Elementor_Utils::get_placeholder_image_src();
			}

			$this->add_render_attribute( $logo_skin, [
				'src'   => esc_url( $image_src ),
				'alt'   => get_bloginfo( 'title' ),
				'class' => "raven-adnav-menu-{$type}-logo{$device}",
			], '', true );

			// Add retina version of the logo if exists.
			$retina_logo_skin = 'primary' !== $device_setting ? "jupiterx_logo_retina_{$device_setting}" : 'jupiterx_logo_retina';
			$retina_image_src = get_theme_mod( $retina_logo_skin, '' );

			if ( ! empty( $retina_image_src ) ) {
				$this->add_render_attribute( $logo_skin, 'srcset', "{$image_src} 1x, {$retina_image_src} 2x" );
			}
		}

		// Add logo link.
		$link = $settings[ "{$type}_logo_link" ];

		if ( ! isset( $link['url'] ) || empty( $link['url'] ) ) {
			$link['url'] = get_bloginfo( 'url' );
		}

		if ( ! empty( $link['url'] ) ) {
			$this->add_render_attribute( "{$type}_logo_link", 'class', 'raven-adnav-logo-link', true );
			$this->add_render_attribute( "{$type}_logo_link", 'href', esc_url( $link['url'] ), true );
			$this->render_link_properties( $this, $link, "{$type}_logo_link", true );
		}

		$wrapper_class = "raven-adnav-{$type}-logo";

		ob_start();

		?>
		<div class="<?php echo $wrapper_class; ?>">
			<?php if ( ! empty( $link['url'] ) ) : ?>
				<a <?php echo $this->get_render_attribute_string( "{$type}_logo_link" ); ?>>
			<?php endif; ?>
			<?php foreach ( $logo_skins as $device_logo ) : ?>
				<img <?php echo $this->get_render_attribute_string( $device_logo ); ?> />
			<?php endforeach; ?>
			<?php if ( ! empty( $link['url'] ) ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render icon based on settings.
	 *
	 * @param Array $icon_data including "library" and "value".
	 *
	 * @access public
	 * @since 2.5.0
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function render_icon( $icon_data, $attrs = [] ) {
		if ( 'svg' === $icon_data['library'] ) {
			echo Icon_Manager::render_uploaded_svg_icon( $icon_data['value'] );
			return;
		}

		echo Icon_Manager::render_font_icon( $icon_data, $attrs );
	}

	private function render_error() {
		?>
		<div class="raven-adnav-menu-error" role="alert">
			<span>
				<?php echo esc_html__( 'Error: First item of the menu cannot be of "Sub Menu" type.', 'jupiterx-core' ); ?>
			</span>
		</div>
		<?php
	}

	private function add_menu_attributes( $settings ) {
		// Menu.
		$this->add_render_attribute( 'menu', [
			'data-layout' => $settings['layout'],
			'class'       => [
				'raven-adnav-menu-main',
				"raven-adnav-menu-{$settings['layout']}",
				'yes' === $settings['center_logo'] ? 'raven-adnav-menu-has-logo' : '',
				'offcanvas' === $settings['layout'] ? "raven-side-menu-{$settings['offcanvas_position']}" : '',
			],
		] );

		// Mobile Menu.
		$this->add_render_attribute( 'mobile_menu', [
			'data-layout' => $settings['mobile_layout'],
			'class'       => [
				'raven-adnav-menu-mobile',
				"raven-adnav-menu-{$settings['mobile_layout']}",
				'side' === $settings['mobile_layout'] ? "raven-side-menu-{$settings['side_menu_alignment']}" : '',
			],
		] );
	}
}
