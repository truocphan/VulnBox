<?php

/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/

use Elementor\Plugin as Elementor;

class HTMega_Menu_Nav_Walker extends Walker_Nav_Menu {

  public $htmenu_menupos = '';
  public $htmenu_menuwidth = '';

  function start_lvl( &$output, $depth = 0, $args = array() ) {
    
        // Depth-dependent classes.
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1 ); // because it counts the first submenu as 0
        if ($display_depth == 1) {
         $style = 'style="width:'.$this->htmenu_menuwidth.'px; left:'.$this->htmenu_menupos.'px;"';
        }else{
          $style = '';
        }

        $classes = array(
            'sub-menu',
            ( $display_depth % 2 ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >=2 ? 'sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );

        // Build HTML for output.
        $output .= "\n$indent<ul class='" . $class_names . "' $style >\n";
  }


  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

    if( isset( $item->menuposition ) && $item->ID  ){
        $this->htmenu_menupos =  $item->menuposition;
    }

    if( isset( $item->menuwidth ) &&  $item->ID ){
         $this->htmenu_menuwidth = $item->menuwidth;
    }

    global $wp_query;
    $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

    // Depth-dependent classes.
    $depth_classes = array(
      ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
      ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
      ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
      'menu-item-depth-' . $depth
    );
    $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

    // Passed classes.
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    // If Enable MegaMenu
    if( isset( $item->template ) && !empty( $item->template ) ){
        $classes[] = 'htmega_mega_menu';
    }

    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

    // Build HTML.
    $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

    // Link attributes.
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';


    $dropdown_icon = '';
    if( !empty( $item->classes ) && 
        is_array( $item->classes ) && 
        in_array( 'menu-item-has-children', $item->classes ) ){
        if($depth > 0 ){
            $dropdown_icon = '<span class="htmenu-icon"><i class="fas fa-angle-right"></i></span>';
        }else{
            if ( isset( $args->extra_menu_settings['dropdown_icon'] ) ) {
                $dropdown_icon = '<span class="htmenu-icon">'.$args->extra_menu_settings['dropdown_icon'].'</span>';
            } else {
                $dropdown_icon = '<span class="htmenu-icon"><i class="fas fa-angle-down"></i></span>';
            }
        }

    }

    $icons = substr( $item->ficon,0,3);
    $icons = str_replace($icons, $icons." ", $item->ficon);

    // Custom Data
    $icon = $buildercontent = $badge = $styles = '';
    $item_settings = get_post_meta( $item->ID, 'htmega_menu_settings', true );

    if( isset( $item->ficon ) && !empty( $item->ficon ) ){
        $icon_style = '';
        if( !empty( $item->ficoncolor ) ){
            $icon_style .= 'color:#'.$item->ficoncolor.';';
        }
        $icon = '<i class="'.$icons.'" style="'.$icon_style.'"></i>';
    }

    if( isset( $item->template ) && !empty( $item->template ) ){
        $buildercontent = $this->getItemBuilderContent( $item->template );
        if ( isset( $args->extra_menu_settings['dropdown_icon'] ) ) {
            $dropdown_icon = '<span class="htmenu-icon">'.$args->extra_menu_settings['dropdown_icon'].'</span>';
        } else {
            $dropdown_icon = '<span class="htmenu-icon"><i class="fas fa-angle-down"></i></span>';
        }
    }

    if( isset( $item->menutag ) && !empty( $item->menutag ) ){
        $badge_style = '';
        if( !empty( $item->menutagcolor ) ){
            $badge_style .= 'color:#'.$item->menutagcolor.';';
        }
        if( $item->badge_bg_type === 'gradient' ){
            $badge_style .= 'background-image: -webkit-linear-gradient(45deg, #'.$item->menutagbgcolor.' 0%, #'.$item->badge_bg_color_two.' 100%);background-image: -o-linear-gradient(45deg, #'.$item->menutagbgcolor.' 0%, #'.$item->badge_bg_color_two.' 100%);background-image: linear-gradient(45deg, #'.$item->menutagbgcolor.' 0%, #'.$item->badge_bg_color_two.' 100%);';
        }else{
            if( !empty( $item->menutagbgcolor ) ){
                $badge_style .= 'background-color:#'.$item->menutagbgcolor.';';
            }
        }
        $badge = '<span class="htmenu-menu-tag" style="'.$badge_style.'">'.$item->menutag.'</span>';
    }


    if( isset( $item->menuposition ) && !empty( $item->menuposition ) ){
        $styles .= 'left:'.$item->menuposition.'px;';
    }

    if( isset( $item->menuwidth ) && !empty( $item->menuwidth ) ){
        $styles .= 'width:'.$item->menuwidth.'px;';
    }

    // Build HTML output and pass through the proper filter.
    $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s%6$s%7$s%8$s</a>%9$s',
        $args->before,
        $attributes,
        $args->link_before,
        $icon,
        apply_filters( 'the_title', $item->title, $item->ID ),
        $dropdown_icon,
        $badge,
        $args->link_after,
        $args->after
    );

    if( !empty( $buildercontent ) ){
        $item_output .= sprintf('<div class="htmegamenu-content-wrapper sub-menu" style="%1s">%2s</div>', $styles, $buildercontent );
    }

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

  public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
    $id_field = $this->db_fields['id'];
    if ( is_object( $args[0] ) ){
       $args[0]->has_children =  !empty ( $children_elements[ $element->$id_field ] ) ;
    }
    parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }

  // Item Builder Content
  private function getItemBuilderContent( $template_id ){
    static $elementor = null;
    $elementor = Elementor::instance();
    return $elementor->frontend->get_builder_content_for_display( $template_id );
  }
}