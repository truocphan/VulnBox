<?php
namespace JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Walker;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Widgets\Advanced_Nav_Menu;
use Elementor\Plugin as Elementor;

/**
 * Walker class for turning the data of the menu repeater control to HTML.
 */
class Walker {

	/**
	 * @var Advanced_Nav_Menu $widget Instance of the menu widget.
	 */
	private $widget;

	/**
	 * @var string $menu The HTML content of the menu that is returned by class constructor.
	 */
	public $menu;

	private $sub_indicator_icon;

	public function __construct( $widget, $id ) {
		$this->widget = $widget;
		$settings     = $widget->get_settings_for_display();

		$this->sub_indicator_icon = $settings['submenu_indicator'];

		$menu_data = $this->idfy_repeater_data( $settings['menu'] );

		if ( false === $menu_data ) {
			$this->menu = false;
			return;
		}

		$nesting = $this->get_menu_nesting( $menu_data );

		$this->menu = $this->render_menu( $settings, $id, $menu_data, $nesting );
	}

	/**
	 * Rearrange data of the menu repeater control, so that the keys are the "_id" of each repeater row.
	 *
	 * @param array $menu_repeater_data Raw data of the menu repeater control.
	 *
	 * @return array _id keyed data.
	 *
	 * @access private
	 * @since 2.5.0
	 */
	private function idfy_repeater_data( $menu_repeater_data ) {
		$idfied_array = [];

		if ( 'submenu' === $menu_repeater_data[0]['item_type'] ) {
			return false;
		}

		foreach ( $menu_repeater_data as $data ) {
			$idfied_array[ $data['_id'] ] = $data;
		}

		return $idfied_array;
	}

	/**
	 * Organize the IDs of menu repeater control so that submenus are nested inside root menu items.
	 *
	 * @param array $idfied_repeater_data The data of menu repeater control, but keyed with repeater row IDs.
	 *
	 * @return array Nested strcuture of the menu repeater rows IDs.
	 *
	 * @access private
	 * @since 2.5.0
	 */
	private function get_menu_nesting( $idfied_repeater_data ) {
		$menu    = [];
		$last_id = null;

		foreach ( $idfied_repeater_data as $id => $data ) {
			if ( 'menu' === $data['item_type'] ) {
				$menu[ $id ] = [];
				$last_id     = $id;
				continue;
			}

			$menu[ $last_id ][] = $id;
		}

		return $menu;
	}

	/**
	 * Render the menu.
	 *
	 * @param array $settings The settings of the menu widget.
	 * @param string $menu_id The ID of the menu widget.
	 * @param array $menu_data The organized data of the menu repeater control after IDfying.
	 * @param array $nesting The IDs of menu items in repeater, but with nested structure.
	 *
	 * @return string HTML content of the menu.
	 *
	 * @access private
	 * @since 2.5.0
	 */
	private function render_menu( $settings, $menu_id, $menu_data, $nesting ) {
		$layout  = $settings['layout'];
		$menu_id = "menu-{$menu_id}";

		$root_item_count = count( $nesting );
		$counter         = 0;

		ob_start();

		?>
		<ul id="<?php echo $menu_id; ?>" class="raven-adnav-menu">
			<?php
				foreach ( $nesting as $item_id => $sub_item_ids ) {
					$width_type = $menu_data[ $item_id ]['dropdown_width_type'];

					$width_attrs  = "data-width_type=\"{$width_type}\"";
					$width_attrs .= 'custom' === $width_type ? " data-custom_width=\"{$menu_data[ $item_id ]['dropdown_custom_width']['size']}px\"" : '';
					$width_attrs .= ( 'vertical' === $layout || 'custom' === $width_type || 'default' === $width_type ) ? " data-submenu_pos=\"{$menu_data[ $item_id ]['dropdown_position']}\"" : '';

					$is_middle = intval( ceil( $root_item_count / 2 ) ) === $counter;

					if ( $is_middle && 'horizontal' === $layout ) {
						echo $this->widget->render_logo( 'center' );
					}

					$counter++;

					?>
					<li class="menu-item" <?php echo $width_attrs; ?>>
						<?php
						echo $this->render_link( $menu_data[ $item_id ], false, count( $sub_item_ids ) > 0 );
						echo $this->render_submenus( $menu_data, $sub_item_ids );
						?>
					</li>
					<?php
				}
			?>
		</ul>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render all submenus of a menu item based on the data given.
	 *
	 * @param array $menu_data An array holding the data of the menu repeater control, but keyed with their _ids.
	 * @param array $sub_item_ids The _ids of sub menu items in the menu repeater control.
	 *
	 * @return string The html string of the submenus inside a <ul> tag with "submenu" class.
	 *
	 * @access private
	 * @since 2.5.0
	 */
	private function render_submenus( $menu_data, $sub_item_ids ) {
		if ( empty( $sub_item_ids ) ) {
			return '';
		}

		ob_start();

		?>
		<ul class="submenu">
			<?php
			foreach ( $sub_item_ids as $id ) {
				$subitem_data = $menu_data[ $id ];
				$content_type = $subitem_data['content_type'];

				if ( 'text' === $content_type ) {
					?>
					<li class="menu-item"><?php echo $this->render_link( $subitem_data, true ); ?></li>
					<?php
					continue;
				}

				$template_id = 'section' === $content_type ? $subitem_data['section_template'] : $subitem_data['widget_template'];

				if ( empty( $template_id ) ) {
					continue;
				}

				?>
				<li class="submenu submenu-template">
					<?php echo do_shortcode( "[elementor-template id=\"{$template_id}\"]" ); ?>
				</li>
				<?php
			}
			?>
		</ul>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render an <a> tag with the data given.
	 *
	 * @param array $item_data An array consisting the link's text, attributes and icon.
	 * @param bool $is_submenu Whether the <a> is resided in submenus. It only affects the css class.
	 * @param bool $has_submenu Whether the menu item has submenus nested inside it.
	 *
	 * @return string The html string of the <a> tag.
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @access private
	 * @since 2.5.0
	 */
	private function render_link( $item_data, $is_submenu = false, $has_submenu = false ) {
		$this->widget->remove_render_attribute( 'link_attrs' );
		$this->widget->render_link_properties( $this->widget, $item_data['link'], 'link_attrs' );

		// Determine link's type (plain/dynamic) and activity state.
		$is_dynamic_tagged = ! empty( $item_data['__dynamic__'] ) && ! empty( $item_data['__dynamic__']['link'] );
		$is_link_active    = $is_dynamic_tagged && $this->is_link_active( $item_data['__dynamic__']['link'] );

		// Add link's neccessary classes.
		$class  = 'raven-link-item';
		$class .= $is_submenu ? ' raven-submenu-item' : ' raven-menu-item';
		$class .= $is_link_active ? ' active-link' : '';

		// Unhash the URL, and get the hash value from "Hash" control if exists.
		$url_parts = explode( '#', $item_data['link']['url'], 2 );
		$url       = [
			'unhashed_url' => ! empty( $url_parts[0] ) ? untrailingslashit( $url_parts[0] ) : '',
			'hash'         => ! empty( $url_parts[1] ) ? '#' . $url_parts[1] : '',
		];

		if ( ! empty( $item_data['hash'] ) ) {
			$url['hash'] = '#' . trim( $item_data['hash'], '#' );
		}

		// Assign attributes.
		$this->widget->add_render_attribute( 'link_attrs', [
			'href'  => $url['unhashed_url'] . $url['hash'],
			'class' => $class,
		] );

		// Render <a> tag.
		ob_start();

		?>
		<a <?php echo $this->widget->get_render_attribute_string( 'link_attrs' ); ?>>
			<?php
			if ( ! empty( $item_data['icon'] ) && ! empty( $item_data['icon']['value'] ) ) {
				echo $this->widget->render_icon( $item_data['icon'] );
			}
			?>
			<span class="link-text">
				<span class="link-label">
					<?php echo esc_html( $item_data['text'] ); ?>
				</span>
				<?php
				if ( $has_submenu ) {
					$icon_type = ! empty( $this->sub_indicator_icon['library'] ) ? $this->sub_indicator_icon['library'] : '';

					$icon_wrapper_open  = '';
					$icon_wrapper_close = '';

					if ( 'svg' === $icon_type ) {
						$icon_wrapper_open  = '<div class="sub-arrow">';
						$icon_wrapper_close = '</div>';
					}

					echo $icon_wrapper_open;
					echo $this->widget->render_icon( $this->sub_indicator_icon, [ 'class' => 'sub-arrow' ] );
					echo $icon_wrapper_close;
				}
				?>
			</span>
		</a>
		<?php

		return ob_get_clean();
	}

	/**
	 * Determines whether the link is currently active or not.
	 * Uses data from dynamic tags and compares it to current WordPress query.
	 *
	 * @param array $dynamic_data Link's dynamic tag data.
	 * @return bool Activity state of the link.
	 *
	 * @access private
	 */
	private function is_link_active( $dynamic_data ) {
		// Extract data from dynamic tag.
		$dynamic_link_data = Elementor::$instance->dynamic_tags->tag_text_to_tag_data( $dynamic_data );

		// Bail out if failed to extract data OR dynamic tag type is not "Internal URL".
		if ( empty( $dynamic_link_data ) || 'internal-url' !== $dynamic_link_data['name'] ) {
			return false;
		}

		$settings = $dynamic_link_data['settings'];

		if ( empty( $settings['type'] ) ) {
			return false;
		}

		$type = $settings['type'];

		// 1. If the dynamic tag is set to an archive Taxonomy page.
		if ( 'taxonomy' === $type && ! empty( $settings['taxonomy_id'] && get_queried_object() instanceof \WP_Term ) ) {
			return intval( $settings['taxonomy_id'] ) === get_queried_object()->term_id;
		}

		// 2. If the dynamic tag is set to an Author page.
		if ( 'author' === $type && ! empty( $settings['author_id'] ) ) {
			return is_author( intval( $settings['author_id'] ) );
		}

		// 3. TODO: Attachment pages
		// Should be added after "raven query" control and also "Internal URL" dynamic tag were updated to include attachments.

		// Otherwise the dynamic tag is set to a normal\content page\post.
		global $post;
		return ! empty( $settings['post_id'] ) && intval( $settings['post_id'] ) === $post->ID;
	}
}
