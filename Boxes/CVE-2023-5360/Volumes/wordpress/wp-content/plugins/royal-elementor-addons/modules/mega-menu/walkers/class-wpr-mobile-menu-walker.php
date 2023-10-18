<?php

class Wpr_Mobile_Menu_Walker extends \Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() ) {
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

		// Default class.
		$classes = array( 'sub-menu', 'wpr-mobile-sub-menu' );

		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names>{$n}";

		if ( wpr_fs()->can_use_premium_code() && $depth === 0 ) {
			$output .= '<li class="wpr-menu-offcanvas-back-wrap">';
				$output .= '<div class="wpr-menu-offcanvas-back">';
				$output .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 320.6" style="enable-background:new 0 0 499.6 320.6;" xml:space="preserve"><g><path class="st0" d="M499.6,159.3c0.3,7-2.4,13.2-7,17.9c-4.3,4.3-10.4,7-16.9,7H81.6l95.6,95.6c9.3,9.3,9.3,24.4,0,33.8c-4.6,4.6-10.8,7-16.9,7c-6.1,0-12.3-2.4-16.9-7L6.9,177.2c-9.3-9.3-9.3-24.4,0-33.8l16.9-16.9l0,0L143.3,6.9c9.3-9.3,24.4-9.3,33.8,0c4.6,4.6,7,10.8,7,16.9s-2.4,12.3-7,16.9l-95.6,95.6h393.7C488.3,136.3,499.1,146.4,499.6,159.3z"/></g></svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped';
					$output .= '<h3></h3>';
				$output .= '</div>';
			$output .= '</li>';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() ) {
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
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->set_menu_item_type( $item->ID, $depth );
		
		if ( 'mega-content' === $this->get_menu_item_type() && 0 < $depth ) {
			return;
		}

        // Get Settings
        $settings = $this->get_mega_menu_settings($item->ID);

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent   = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if ( $this->has_mega_menu( $item->ID ) ) {
			$classes[] = 'menu-item-has-children';
		}

		$id_attr = '';

		if ( $settings ) {
			if ( 'true' === $settings['wpr_mm_enable'] ) {
				$classes[] = 'wpr-mega-menu-'. $settings['wpr_mm_enable'];
				$id_attr = ' data-id="'. $item->ID .'"';

				if ( wpr_fs()->can_use_premium_code() && 'ajax' === $settings['wpr_mm_render'] && 'mega' === $settings['wpr_mm_mobile_content'] ) {
					$classes[] = 'wpr-mega-menu-ajax';
				}
			}
		}
		
		$item_li_class = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$item_li_class = $item_li_class ? ' class="' . esc_attr( $item_li_class ) . '"' : '';
		
		$item_a_class = 'wpr-mobile-menu-item';
		
		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$item_a_class .= ' wpr-active-menu-item';
		}

		$output .= $indent .'<li'. $id . $item_li_class . $id_attr .'>';

			$item_output = $args->before;
				// Parent Item
				if ( in_array( 'menu-item-has-children', $item->classes ) ) {
					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_a_class .= ' wpr-active-menu-item';
					}
				}

				// Sub Menu Classes
				if ( $depth > 0 ) {
					$item_a_class = 'wpr-mobile-sub-menu-item';

					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_a_class .= ' wpr-active-menu-item';
					}
				}

                $item_icon_html = '';
                $item_badge_html = '';

                // If has Settings
                if ( $settings ) {
                    // Icon
                    if ( wpr_fs()->can_use_premium_code() && '' !== $settings['wpr_mm_icon_picker'] ) {
                        $font_size = '' !== $settings['wpr_mm_icon_size'] ? $settings['wpr_mm_icon_size'] .'px' : 'inherit';
                        $color = '' !== $settings['wpr_mm_icon_color'] ? $settings['wpr_mm_icon_color'] : 'inherit';
                        $item_icon_style = 'style="font-size: '. $font_size .'; color: '. $color .'"';
                        $item_icon_html = '<i class="wpr-mega-menu-icon '. $settings['wpr_mm_icon_picker'] .'" '. $item_icon_style .'></i>';
                    }

                    // Badge
                    if ( wpr_fs()->can_use_premium_code() && '' !== $settings['wpr_mm_badge_text'] ) {
                        $item_badge_style = 'color: '. $settings['wpr_mm_badge_color'] .';';
                        $item_badge_style .= 'background-color: '. $settings['wpr_mm_badge_bg_color'] .';';
                        $item_badge_html = '<span class="wpr-mega-menu-badge" style="'. $item_badge_style .'">'. $settings['wpr_mm_badge_text'] .'</span>';
                    }
                }

                // Link Attributes
                $atts = [];
                $atts['title'] = ! empty( $item->attr_title ) ? $item->attr_title : '';
                $atts['target'] = ! empty( $item->target ) ? $item->target : '';
                $atts['rel'] = ! empty( $item->xfn ) ? $item->xfn : '';
                $atts['href'] = ! empty( $item->url ) ? $item->url : '#';
                $atts['class'] = $item_a_class;
        
                $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        
                $attributes = '';
        
                foreach ( $atts as $attr => $value ) {
                    if ( ! empty( $value ) ) {
                        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                }
                
				// Link Output
				$item_output .= '<a'. $attributes .'>';
					$item_output .= $item_icon_html;
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
					$item_output .= $item_badge_html;
					$item_output .= in_array( 'menu-item-has-children', $item->classes ) || $this->has_mega_menu($item->ID) ? '<i class="wpr-mobile-sub-icon"></i>' : '';
				$item_output .= '</a>';
			$item_output .= $args->after;
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() && 0 < $depth ) {
			return;
		}
		
		if ( $depth === 0 ) {
			if ( $this->has_mega_menu($item->ID) ) {
				// Get Settings
				$settings = $this->get_mega_menu_settings($item->ID);

				if ( 'ajax' === $settings['wpr_mm_render'] ) {
					$output .= '<div class="wpr-mobile-sub-mega-menu">';
						if ( wpr_fs()->can_use_premium_code() ) {
							$output .= '<div class="wpr-menu-offcanvas-back">';
								$output .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 320.6" style="enable-background:new 0 0 499.6 320.6;" xml:space="preserve"><g><path class="st0" d="M499.6,159.3c0.3,7-2.4,13.2-7,17.9c-4.3,4.3-10.4,7-16.9,7H81.6l95.6,95.6c9.3,9.3,9.3,24.4,0,33.8c-4.6,4.6-10.8,7-16.9,7c-6.1,0-12.3-2.4-16.9-7L6.9,177.2c-9.3-9.3-9.3-24.4,0-33.8l16.9-16.9l0,0L143.3,6.9c9.3-9.3,24.4-9.3,33.8,0c4.6,4.6,7,10.8,7,16.9s-2.4,12.3-7,16.9l-95.6,95.6h393.7C488.3,136.3,499.1,146.4,499.6,159.3z"/></g></svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped';
								$output .= '<h3></h3>';
							$output .= '</div>';
						}
					$output .= '</div>';
				} else {
					$elementor = \Elementor\Plugin::instance();
					$mega_id = get_post_meta( $item->ID, 'wpr-mega-menu-item', true);
					$content = $elementor->frontend->get_builder_content_for_display($mega_id);

					$output .= '<div class="wpr-mobile-sub-mega-menu">';
						if ( wpr_fs()->can_use_premium_code() ) {
							$output .= '<div class="wpr-menu-offcanvas-back">';
								$output .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 320.6" style="enable-background:new 0 0 499.6 320.6;" xml:space="preserve"><g><path class="st0" d="M499.6,159.3c0.3,7-2.4,13.2-7,17.9c-4.3,4.3-10.4,7-16.9,7H81.6l95.6,95.6c9.3,9.3,9.3,24.4,0,33.8c-4.6,4.6-10.8,7-16.9,7c-6.1,0-12.3-2.4-16.9-7L6.9,177.2c-9.3-9.3-9.3-24.4,0-33.8l16.9-16.9l0,0L143.3,6.9c9.3-9.3,24.4-9.3,33.8,0c4.6,4.6,7,10.8,7,16.9s-2.4,12.3-7,16.9l-95.6,95.6h393.7C488.3,136.3,499.1,146.4,499.6,159.3z"/></g></svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped';
								$output .= '<h3></h3>';
							$output .= '</div>';
						}
						$output .= ''. $content;
					$output .= '</div>';
				}
			}

			$output .= "</li>\n";
		}
	}

	public function set_menu_item_type( $item_id = 0, $depth = 0 ) {
		if ( 0 < $depth ) {
			return;
		}

		$item_type = 'default-content';

		if ( $this->has_mega_menu( $item_id ) ) {
			$item_type = 'mega-content';
		}

		wp_cache_set( 'menu-item-type', $item_type, 'wpr-mega-menu' );
	}

	public function get_menu_item_type() {
		return wp_cache_get( 'menu-item-type', 'wpr-mega-menu' );
	}

	public function has_mega_menu( $item_id ) {
		$settings = get_post_meta( $item_id, 'wpr-mega-menu-settings', true);
		$mega_content = wpr_fs()->can_use_premium_code() && isset($settings['wpr_mm_mobile_content']) ? $settings['wpr_mm_mobile_content'] : 'mega';

		return isset($settings['wpr_mm_enable']) && 'true' === $settings['wpr_mm_enable'] && 'mega' === $mega_content ? true : false;
	}

	public function get_mega_menu_settings( $item_id ) {
		$settings = get_post_meta( $item_id, 'wpr-mega-menu-settings', true);

		return ! empty($settings) ? $settings : false;
	}
}