<?php
/**
 * Walker class.
 *
 * @package JupiterX_Core\Raven
 *
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Nav_Menu\Classes;

use Elementor\Plugin as Elementor;
defined( 'ABSPATH' ) || die();

/**
 * Custom nav menu walker.
 *
 * @since 1.0.0
 *
 * Supress this class since this is almost similar to WP Walker_Nav_Menu class with minimal code modification.
 *
 * @SuppressWarnings(PHPMD)
 */
class Walker_Nav_Menu extends \Walker_Nav_Menu {

	/**
	 * Total top level count.
	 *
	 * @since 1.0.0
	 *
	 * @var integer
	 */
	protected $top_level_count = 0;

	/**
	 * Current loop top level index.
	 *
	 * @since 1.0.0
	 *
	 * @var integer
	 */
	protected $top_level_index = 0;

	public $menu_settings = [];

	/**
	 * Check for mega menus.
	 *
	 * @since 1.5.0
	 *
	 * @var integer
	 */
	public $mega_menu_id;
	public $mega_menu_enabled;

	public function __construct( $menu_settings ) {
		$this->mega_menu_id      = false;
		$this->mega_menu_enabled = false;
		$this->menu_settings     = $menu_settings;
	}

	/**
	 * Display array of elements hierarchically.
	 *
	 * Does not assume any existing order of elements.
	 *
	 * $max_depth = -1 means flatly display every element.
	 * $max_depth = 0 means display all levels.
	 * $max_depth > 0 specifies the number of display levels.
	 *
	 * @since 1.0.0
	 *
	 * @param array $elements  An array of elements.
	 * @param int   $max_depth The maximum hierarchical depth.
	 *
	 * @return string The hierarchical item output.
	 */
	public function walk( $elements, $max_depth, ...$args ) {
		$args   = array_slice( func_get_args(), 2 );
		$output = '';

		if ( $max_depth < -1 || empty( $elements ) ) {
			return $output;
		}

		$parent_field = $this->db_fields['parent'];

		if ( -1 === $max_depth ) {
			$empty_array = [];
			foreach ( $elements as $e ) {
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			}
			return $output;
		}

		$top_level_elements = [];
		$children_elements  = [];
		foreach ( $elements as $e ) {
			if ( empty( $e->$parent_field ) ) {
				$top_level_elements[] = $e;
			} else {
				$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		if ( empty( $top_level_elements ) ) {

			$first = array_slice( $elements, 0, 1 );
			$root  = $first[0];

			$top_level_elements = [];
			$children_elements  = [];
			foreach ( $elements as $e ) {
				if ( $root->$parent_field === $e->$parent_field ) {
					$top_level_elements[] = $e;
				} else {
					$children_elements[ $e->$parent_field ][] = $e;
				}
			}
		}

		// Hack to get count and index of loop via class scope variable.
		$this->top_level_count = count( $top_level_elements );
		foreach ( $top_level_elements as $index => $e ) {
			$this->top_level_index = $index;

			if ( $this->mega_menu_check( $e->ID ) ) {
				$this->unset_children( $e, $children_elements );
			}
			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
		}
		// Immediately reset.
		$this->top_level_count = 0;
		$this->top_level_index = 0;

		if ( ( 0 === $max_depth ) && count( $children_elements ) > 0 ) {
			$empty_array = [];
			foreach ( $children_elements as $orphans ) {
				foreach ( $orphans as $op ) {
					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
				}
			}
		}

		return $output;
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 1.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = [] ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";

		if ( 0 === $depth && $this->top_level_count ) {
			$output .= apply_filters_deprecated(
				'raven_walker_nav_menu_logo',
				[ '', $this->top_level_count, $this->top_level_index ],
				'1.18.0',
				'jupiterx_core_raven_walker_nav_menu_logo'
			);

			$output .= apply_filters(
				'jupiterx_core_raven_walker_nav_menu_logo',
				'',
				$this->top_level_count,
				$this->top_level_index
			);
		}
	}

	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $this->mega_menu_id > 0 ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		$classes = array( 'sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $depth . ' ' . $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->mega_menu_id      = $this->mega_menu_check( $item->ID );
		$this->mega_menu_enabled = $this->mega_menu_id ? true : false;

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		if ( $this->mega_menu_enabled ) {
			$classes[] = 'mega-menu-enabled';
			$classes[] = 'has-mega-menu';
			$classes[] = 'mega-menu-' . $item->ID;
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$title = apply_filters_deprecated(
			'raven_nav_menu_item_title',
			[ $title, $item, $this->menu_settings ],
			'1.18.0',
			'jupiterx_core_raven_nav_menu_item_title'
		);

		$title = apply_filters(
			'jupiterx_core_raven_nav_menu_item_title',
			$title,
			$item,
			$this->menu_settings
		);

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if ( $this->mega_menu_id ) {
			$mega_menu_content = $this->getItemContent( $this->mega_menu_id );
			$item_output      .= sprintf( "<ul class='submenu'><div class='raven-megamenu-wrapper'>%s</div></ul>", $mega_menu_content );
		}
		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Get Elementor template by Id.
	 *
	 * @since 1.5.0
	 * @param int $item_id Elementor template ID.
	 *
	 * @return string Elementor Template String.
	 */
	private function getItemContent( $item_id ) {
		static $elementor = null;
		if ( $item_id ) {
			if ( null === $elementor ) {
				$elementor = Elementor::instance();
			}

			return $elementor->frontend->get_builder_content_for_display( $item_id );
		}
	}
	/**
	 * Check if the nav menu item is a mega menu or not and return the mega menu template id.
	 *
	 * @since 1.5.0
	 * @param int Nav menu Item.
	 *
	 * @return mixed    $mega_menu_template.
	 */
	public function mega_menu_check( $item_id ) {

		if ( '' !== $item_id && class_exists( 'acf' ) ) {
			$mega_menu_template = get_field( 'jupiterx_mega_template', $item_id );

			if ( 'global' !== $mega_menu_template && (int) $mega_menu_template > 0 ) {
				return $mega_menu_template;
			}
		}
		return false;
	}
}
