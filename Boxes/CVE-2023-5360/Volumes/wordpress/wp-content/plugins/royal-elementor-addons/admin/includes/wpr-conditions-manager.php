<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Conditions_Manager setup
 *
 * @since 1.0
 */
class WPR_Conditions_Manager {

    /**
    ** Header & Footer Conditions
    */
    public static function header_footer_display_conditions( $conditions ) {
        $template = NULL;

        // Custom
        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
	        if ( !empty($conditions) ) {
                $conditions['caller-header-footer'] = ['true'];

				// Archive Pages (includes search)
				if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $conditions ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $conditions );
				}

	        	// Single Pages
				if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $conditions ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $conditions );
				}

	        }
        } else {
        	$template = Utilities::get_template_slug( $conditions, 'global' );
        }

        if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
        	$template_type = Utilities::get_wpr_template_type(get_the_ID());
        	
        	if ( 'header' === $template_type || 'footer' === $template_type || is_singular('wpr_mega_menu') ) {
        		$template = NULL;
        	}
        }

        if ( !current_user_can('administrator') && ('maintenance' == get_option('elementor_maintenance_mode_mode') || 'coming_soon' == get_option('elementor_maintenance_mode_mode')) ) {
            $template = NULL;
        }

	    return $template;
    }

    /**
    ** Canvas Content Conditions
    */
    public static function canvas_page_content_display_conditions() {
        $template = NULL;

		// Get Conditions
		if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
			$archives = json_decode( get_option( 'wpr_product_archive_conditions' ), true );
			$singles  = json_decode( get_option( 'wpr_product_single_conditions' ), true );
		} else {
			$archives = json_decode( get_option( 'wpr_archive_conditions' ), true );
			$singles  = json_decode( get_option( 'wpr_single_conditions' ), true );
		}

        if ( empty($archives) && empty($singles) ) {
            return NULL;
        }

        // Custom
        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {

			// Archive Pages (includes search)
			if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $archives ) ) ) {
				$template = \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $archives );
			}

	    	// Single Pages
			if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $singles ) ) ) {
				$template = \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $singles );
			}
        } else {
            // Archive Pages (includes search)
            if ( !is_null( WPR_Conditions_Manager::archive_templates_conditions_free($archives) ) ) {
                $template = WPR_Conditions_Manager::archive_templates_conditions_free($archives);
            }

            // Single Pages
            if ( !is_null( WPR_Conditions_Manager::single_templates_conditions_free($singles) ) ) {
                $template = WPR_Conditions_Manager::single_templates_conditions_free($singles);
            }
        }

	    return $template;
    }


    /**
    ** Archive Pages Templates Conditions Free
    */
    public static function archive_templates_conditions_free( $conditions ) {
        $term_id = '';
        $term_name = '';
        $queried_object = get_queried_object();

        // Get Terms
        if ( ! is_null( $queried_object ) ) {
            if ( isset( $queried_object->term_id ) && isset( $queried_object->taxonomy ) ) {
                $term_id   = $queried_object->term_id;
                $term_name = $queried_object->taxonomy;
            }
        }

        // Reset
        $template = NULL;

        // Archive Pages (includes search)
        if ( is_archive() || is_search() ) {
            if ( ! is_search() ) {
                // Author
                if ( is_author() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/author' );
                // Date
                } elseif ( is_date() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/date' );
                // Category
                } elseif ( is_category() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/categories', $term_id );
                // Tag
                } elseif ( is_tag() ) {
                    $template = Utilities::get_template_slug( $conditions, 'archive/tags', $term_id );
				// Products
                } elseif ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
                    $template = Utilities::get_template_slug( $conditions, 'product_archive/products' );              
                }

            // Search Page
            } else {
                $template = Utilities::get_template_slug( $conditions, 'archive/search' );
            }

        // Posts Page
        } elseif ( Utilities::is_blog_archive() ) {
            $template = Utilities::get_template_slug( $conditions, 'archive/posts' );
        }

        // Global - For All Archives
        if ( is_null($template) ) {
            $all_archives = Utilities::get_template_slug( $conditions, 'archive/all_archives' );

            if ( ! is_null($all_archives) ) {
                if ( class_exists( 'WooCommerce' ) && is_shop() ) {
                    $template = null;
                } else {
                    if ( is_archive() || is_search() || Utilities::is_blog_archive() ) {
                        $template = $all_archives;
                    }
                }
            }
        }

        return $template;
    }

    /**
    ** Single Pages Templates Conditions - Free
    */
    public static function single_templates_conditions_free( $conditions ) {
        global $post;

        // Get Posts
        $post_id   = is_null($post) ? '' : $post->ID;
        $post_type = is_null($post) ? '' : $post->post_type;

        // Reset
        $template = NULL;

        // Single Pages
        if ( is_single() || is_front_page() || is_page() || is_404() ) {

            if ( is_single() ) {
                // Blog Posts
                if ( 'post' == $post_type ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/posts', $post_id );
                } elseif ( 'product' == $post_type ) {
                    $template = Utilities::get_template_slug( $conditions, 'product_single/product', $post_id );
                }
            } else {
                // Front page
                if ( is_front_page() && ! Utilities::is_blog_archive() ) {//TODO: is it a good check? - is_blog_archive()
                    $template = Utilities::get_template_slug( $conditions, 'single/front_page' );
                // Error 404 Page
                } elseif ( is_404() ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/page_404' );
                // Single Page
                } elseif ( is_page() ) {
                    $template = Utilities::get_template_slug( $conditions, 'single/pages', $post_id );
                }
            }

        }

        return $template;
    }
}