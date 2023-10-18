<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Count_Wishlist_Compare_Items setup
 *
 * @since 1.0
 */
class WPR_Count_Wishlist_Compare_Items { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_count_wishlist_items',[$this, 'count_wishlist_items'] );
        add_action( 'wp_ajax_nopriv_count_wishlist_items',[$this, 'count_wishlist_items'] );
        add_action( 'wp_ajax_count_compare_items',[$this, 'count_compare_items'] );
        add_action( 'wp_ajax_nopriv_count_compare_items',[$this, 'count_compare_items'] );
    }

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
        if (isset($_COOKIE['wpr_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
        } else if ( isset($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']), true);
        }
        return array();
	}
    
    function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
    }
    
    function count_wishlist_items() {
        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }

        $product_data = [];

       $product_data['wishlist_count'] = sizeof($wishlist);
       $wishlist_product_array = [];

        $settings = [
            'element_addcart_simple_txt' => isset($_POST['element_addcart_simple_txt']) ? $_POST['element_addcart_simple_txt'] : '', 
            'element_addcart_grouped_txt' => isset($_POST['element_addcart_grouped_txt']) ? $_POST['element_addcart_grouped_txt'] : '', 
            'element_addcart_variable_txt' => isset($_POST['element_addcart_variable_txt']) ? $_POST['element_addcart_grouped_txt'] : ''
        ];
            
        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);

            if ($product) {
                $wishlist_product_array[] = [
                    'product_id' => $product_id,
                    'product_image' => $product->get_image(),
                    'product_title' => $product->get_title(),
                    'product_url' => $product->get_permalink(),
                    'product_price' => $product->get_price_html(),
                    'product_stock' => $product->get_stock_status() == 'instock' ? esc_html__('In Stock', 'wpr-addons') : esc_html__('Out of Stock', 'wpr-addons'),
                    'product_atc' => $this->render_product_add_to_cart($settings, $product)
                ];
            }
        }

        $product_data['wishlist_items'] = $wishlist_product_array;

       wp_send_json($product_data);

       wp_die();
    }
    
    function count_compare_items() {
        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $product_data = [];

       $product_data['compare_count'] = sizeof($compare);
       $product_data['compare_table'] = $this->compare_table();

       wp_send_json($product_data);

       wp_die();
    }

    public function compare_table() {
        $user_id = get_current_user_id();
		$notification_hidden = '';
		$table_hidden = '';

        $settings = [
            'compare_empty_text' => isset($_POST['compare_empty_text']) ? $_POST['compare_empty_text'] : '',
            'remove_from_compare_text' => isset($_POST['remove_text']) ? sanitize_text_field($_POST['remove_text']) : '',
            'element_addcart_simple_txt' => isset($_POST['element_addcart_simple_txt']) ? $_POST['element_addcart_simple_txt'] : '', 
            'element_addcart_grouped_txt' => isset($_POST['element_addcart_grouped_txt']) ? $_POST['element_addcart_grouped_txt'] : '', 
            'element_addcart_variable_txt' => isset($_POST['element_addcart_variable_txt']) ? $_POST['element_addcart_grouped_txt'] : ''
        ];

		if ($user_id > 0) {
			$compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
		} else {
			$compare = $this->get_compare_from_cookie();
		}
		
        if ( ! $compare ) {
			$table_hidden = 'wpr-hidden-element';
        } else {
			$notification_hidden = 'wpr-hidden-element';
		}

        ob_start();

		echo '<p class="wpr-compare-empty '. $notification_hidden .'">'. $settings['compare_empty_text'] .'</p>';
        
        // Start the table
		echo '<div class="wpr-compare-products '. $table_hidden .'">';
        echo '<table class="wpr-compare-table">';
        
            // Create the first row of table headers
            echo '<tr>';
            echo '<th></th>'; // Blank space for top-left corner
            foreach ( $compare as $product_id ) {
                $product = wc_get_product( $product_id );
                if ( ! $product ) {
                    continue;
                }
                echo '<th data-product-id="' . $product->get_id() . '"><button class="wpr-compare-remove" data-product-id="' . $product->get_id() . '">'. esc_html__($settings['remove_from_compare_text'], 'wpr-addons') .'</button></th>';
            }
            echo '</tr>';
            
            // Create the remaining rows of the table
            $table_data = array(
                ['label' => esc_html__('Image', 'wpr-addons'), 'type' => 'image'],
                ['label' => esc_html__('Name', 'wpr-addons'), 'type' => 'text'],
                ['label' => esc_html__('Rating', 'wpr-addons'), 'type' => 'text'],
                ['label' => esc_html__('Description', 'wpr-addons'), 'type' => 'text'],
                ['label' => esc_html__('Price', 'wpr-addons'), 'type' => 'text'],
                ['label' => esc_html__('SKU', 'wpr-addons'), 'type' => 'text'],
                ['label' => esc_html__('Stock Status', 'wpr-addons'), 'type' => 'text'],
                ['label' => esc_html__('Dimensions', 'wpr-addons'), 'type' => 'text'],
                ['label' => esc_html__('Weight', 'wpr-addons'), 'type' => 'text']
            );

            
            $all_attributes = array();
            
            foreach ( $compare as $product_id ) {
                $product = wc_get_product( $product_id );
                if ( ! $product ) {
                    continue;
                }
                $attributes = $product->get_attributes();
                foreach ( $attributes as $attribute ) {
                    $attribute_name = wc_attribute_label($attribute->get_name());
                    if ( !in_array($attribute_name, $all_attributes) ) {
                        $all_attributes[] = $attribute_name;
                    }
                }
            }
            foreach ( $all_attributes as $attribute_name ) {
                $table_data[] = array('label' => $attribute_name, 'type' => 'text');
            }

            foreach ( $table_data as $row ) {
                echo '<tr>';
                echo '<th>' . $row['label'] . '</th>';
                foreach ( $compare as $key => $product_id ) {
                    $product = wc_get_product( $product_id );
                    if ( ! $product ) {
                        continue;
                    }
                    echo '<td data-product-id="' . $product->get_id() . '">';
                    switch ( $row['type'] ) {
                        case 'image':
                            echo '<a class="wpr-compare-img-wrap" href="' . $product->get_permalink() . '">' . $product->get_image() . '</a>';
							echo '<div class="wpr-compare-product-atc">' . $this->render_product_add_to_cart( $settings, $product ) . '</div>';
                            break;
                        case 'text':
                            if( in_array(strtolower($row['label']), ['description', 'sku']) ) {
                                echo $product->get_data()[strtolower($row['label'])];
                            } else if ( strtolower($row['label']) == 'name' ) {
                                echo '<a class="wpr-compare-product-name" href="' . $product->get_permalink() . '">'. $product->get_data()[strtolower($row['label'])] .'</a>';
							} else if ( strtolower($row['label']) == 'price' ) {
                                echo $product->get_price_html();
                            } else if ( strtolower($row['label']) == 'rating' ) {
                                $this->render_product_rating($product);
                            } else if ( strtolower($row['label']) == 'stock status' ) {
					            $stock_status = $product->get_stock_status();
                                echo $stock_status == 'instock' ? esc_html__('In Stock', 'wpr-addons') : esc_html__('Out of Stock', 'wpr-addons');
                            } else if ( strtolower($row['label']) == 'dimensions' ) {

								if ( $product->has_dimensions() ) {
									$dimensions = sprintf(
										'<span class="wpr-dimensions">%s</span>',
										wc_format_dimensions( $product->get_dimensions( false ) )
									);
	
									echo $dimensions;
								}
								
							} else if ( strtolower($row['label']) == 'weight' ) {

								if ( $product->get_weight() ) {
									$weight = sprintf(
										'<span class="wpr-weight">%s %s</span>',
										$product->get_weight(),
										get_option( 'woocommerce_weight_unit' )
									);
	
									echo $weight;
								}

							} else {      
                                $attributes = $product->get_attributes();
                                $attribute_name = wc_attribute_label(strtolower($row['label']));

								foreach ($product->get_attributes($product_id) as $attr) {

									if ( strtolower($attr['name']) === strtolower($row['label']) ) {
										echo $attr['value'];
									}
								}

								// Product Attributes
								if (isset($attributes['pa_'.$attribute_name])) {
									// Get the value(s) of the 'dimensions' attribute for the product
									$attributes_value = $attributes['pa_'.$attribute_name]->get_options();
									$attributes_value_array = [];

									// Loop through the values and output them
									foreach ($attributes_value as $value) {
										$term = get_term($value);
										$attributes_value_array[] = $term->name;
									}

									echo implode(' | ', $attributes_value_array);
								}
                            }
                            break;
                    }
                    echo '</td>';
                }
                echo '</tr>';
            }
        
        // Close the table
        echo '</table>';
		echo '</div>';

        return ob_get_clean();
    }

    public function render_product_rating($product) {

        // $rating_count = $product->get_rating_count();
		// $rating_amount = floatval( $product->get_average_rating() );
		// $round_rating = (int)$rating_amount;
        // $rating_icon = '&#9734;';

		// echo '<div class="wpr-woo-rating">';

		// 	for ( $i = 1; $i <= 5; $i++ ) {
		// 		if ( $i <= $rating_amount ) {
		// 			echo '<i class="wpr-rating-icon-full">'. $rating_icon .'</i>';
		// 		} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
		// 			echo '<i class="wpr-rating-icon-'. ( $rating_amount - $round_rating ) * 10 .'">'. $rating_icon .'</i>';
		// 		} else {
		// 			echo '<i class="wpr-rating-icon-empty">'. $rating_icon .'</i>';
		// 		}
	    //  	}

		// echo '</div>';

		// Another option
		$rating  = $product->get_average_rating();
		$count   = $product->get_rating_count();
		return wc_get_rating_html( $rating, $count );
	}
    	
	// Render Add To Cart
	public function render_product_add_to_cart( $settings, $product ) {

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		ob_start();

		// Get Button Class
		$button_class = implode( ' ', array_filter( [
			'product_type_'. $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		] ) );

		$attributes = [
			'rel="nofollow"',
			'class="'. esc_attr($button_class) .' wpr-button-effect '. (!$product->is_in_stock() && 'simple' === $product->get_type() ? 'wpr-atc-not-clickable' : '').'"',
			'aria-label="'. esc_attr($product->add_to_cart_description()) .'"',
			'data-product_id="'. esc_attr($product->get_id()) .'"',
			'data-product_sku="'. esc_attr($product->get_sku()) .'"',
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();

		// Button Text
		if ( 'simple' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_simple_txt'];

			if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
				array_push( $attributes, 'href="'. esc_url( get_permalink( $page_id ) .'/?add-to-cart='. get_the_ID() ) .'"' );
			} else {
				array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
			}
		} elseif ( 'grouped' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_grouped_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} elseif ( 'variable' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_variable_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} else {
			array_push( $attributes, 'href="'. esc_url( $product->get_product_url() ) .'"' );
			$button_HTML .= get_post_meta( get_the_ID(), '_button_text', true ) ? get_post_meta( get_the_ID(), '_button_text', true ) : 'Buy Product';
		}

			// Button HTML
		echo '<a '. implode( ' ', $attributes ) .'><span>'. $button_HTML .'</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		return \ob_get_clean();
	} 
}

new WPR_Count_Wishlist_Compare_Items();