<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Render_Templates setup
 *
 * @since 1.0
 */
class WPR_Render_Templates {

	/**
	** Instance of Elemenntor Frontend class.
	*
	** @var \Elementor\Frontend()
	*/
	private static $elementor_instance;

	/**
	** Get Current Theme.
	*/
	public $current_theme;

	/**
	** Royal Themes Array.
	*/
	public $royal_themes;


	/**
	** Constructor
	*/
	public function __construct( $only_hf = false ) {

		// Elementor Frontend
		self::$elementor_instance = \Elementor\Plugin::instance();

		// Ative Theme
		$this->current_theme = get_template();

		// Royal Themes
		$this->royal_themes = ['ashe', 'ashe-pro', 'ashe-pro-premium', 'bard', 'bard-pro', 'bard-pro-premium'];

		// Popular Themes
		if ( 'astra' === $this->current_theme ) {
			require_once(__DIR__ . '/../templates/views/astra/class-astra-compat.php');

		} elseif ( 'generatepress' === $this->current_theme ) {
			require_once(__DIR__ . '/../templates/views/generatepress/class-generatepress-compat.php');

		} elseif ( 'oceanwp' === $this->current_theme ) {
			require_once(__DIR__ . '/../templates/views/oceanwp/class-oceanwp-compat.php');

		} elseif ( 'storefront' === $this->current_theme ) {
			require_once(__DIR__ . '/../templates/views/storefront/class-storefront-compat.php');
		
		// Other Themes
		} else {
			add_action( 'wp', [ $this, 'global_compatibility' ] );
		}

		// Scripts and Styles
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Theme Builder
		if ( !$only_hf ) { // Prevent Loading in Header or Footer Templates
			add_filter( 'template_include', [ $this, 'convert_to_canvas' ], 12 ); // 12 after WP Pages and WooCommerce.
			add_action( 'elementor/page_templates/canvas/wpr_print_content', [ $this, 'canvas_page_content_display' ] );
		}
	}

	public function global_compatibility() {
		add_action( 'get_header', [ $this, 'replace_header' ] );
		add_action( 'elementor/page_templates/canvas/before_content', [ $this, 'add_canvas_header' ] );

		add_action( 'get_footer', [ $this, 'replace_footer' ] );
		add_action( 'elementor/page_templates/canvas/after_content', [ $this, 'add_canvas_footer' ], 9 );
	}

    /**
    ** Check if a Template has Conditions
    */
	public function is_template_available( $type ) {
    	if ( 'content' === $type ) {
			return !is_null(WPR_Conditions_Manager::canvas_page_content_display_conditions()) ? true : false;
    	} else {
    		$conditions = json_decode( get_option('wpr_'. $type .'_conditions', '[]'), true );
    		$template = WPR_Conditions_Manager::header_footer_display_conditions( $conditions );
    		return (!empty( $conditions ) && !is_null($template)) ? true : false;
    	}
	}

    /**
    ** Header
    */
    public function replace_header() {
    	if ( $this->is_template_available('header') ) {
    		if ( ! in_array($this->current_theme, $this->royal_themes) ) {
				require __DIR__ . '/../templates/views/theme-header.php';
			} else {
				require __DIR__ . '/../templates/views/royal/theme-header-royal.php';
			}

			$templates   = [];
			$templates[] = 'header.php';
			
			remove_all_actions( 'wp_head' ); // Avoid running wp_head hooks again.

			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
        }
    }

    public function add_canvas_header() {
    	if ( $this->is_template_available('header') ) {
	    	$conditions = json_decode( get_option('wpr_header_conditions', '[]'), true );
			$template_slug = WPR_Conditions_Manager::header_footer_display_conditions($conditions);
			$template_id = Utilities::get_template_id($template_slug);
			$show_on_canvas = get_post_meta($template_id, 'wpr_header_show_on_canvas', true);

			if ( !empty($show_on_canvas) && 'true' === $show_on_canvas && 0 === strpos($template_slug, 'user-header-') ) {
				Utilities::render_elementor_template($template_slug);
			}
		}
    }

	/**
	** Footer
	*/
	public function replace_footer() {
    	if ( $this->is_template_available('footer') ) {
    		if ( ! in_array($this->current_theme, $this->royal_themes) ) {
				require __DIR__ . '/../templates/views/theme-footer.php';
			} else {
				require __DIR__ . '/../templates/views/royal/theme-footer-royal.php';
			}

			$templates   = [];
			$templates[] = 'footer.php';
			
			remove_all_actions( 'wp_footer' ); // Avoid running wp_footer hooks again.

			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
        }
	}

    public function add_canvas_footer() {
    	if ( $this->is_template_available('footer') ) {
	    	$conditions = json_decode( get_option('wpr_footer_conditions', '[]'), true );
			$template_slug = WPR_Conditions_Manager::header_footer_display_conditions($conditions);
			$template_id = Utilities::get_template_id($template_slug);
			$show_on_canvas = get_post_meta($template_id, 'wpr_footer_show_on_canvas', true);

			if ( !empty($show_on_canvas) && 'true' === $show_on_canvas && 0 === strpos($template_slug, 'user-footer-') ) {
				Utilities::render_elementor_template($template_slug);
			}
		}
    }

    public function convert_to_canvas( $template ) {
    	$is_theme_builder_edit = \Elementor\Plugin::$instance->preview->is_preview_mode() && Utilities::is_theme_builder_template() ? true : false;
    	$_wp_page_template = get_post_meta(get_the_ID(), '_wp_page_template', true);

    	if ( $this->is_template_available('content') || $is_theme_builder_edit ) {
    		if ( (is_page() || is_single()) && 'elementor_canvas' === $_wp_page_template && !$is_theme_builder_edit ) {
    			return $template;
    		} else {
    			return WPR_ADDONS_PATH . 'admin/templates/wpr-canvas.php';
    		}
    	} else {
    		return $template;
    	}
    }

	/**
	** Theme Builder Content Display
	*/
	public function canvas_page_content_display() {
		// Get Template
		$template = WPR_Conditions_Manager::canvas_page_content_display_conditions();

		// Display Template
		Utilities::render_elementor_template( $template );
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
		}

		if ( class_exists( '\ElementorPro\Plugin' ) ) {
			$elementor_pro = \ElementorPro\Plugin::instance();
			$elementor_pro->enqueue_styles();
		}

		// Load Header Template CSS File
		$heder_conditions = json_decode( get_option('wpr_header_conditions', '[]'), true );
		$heder_template = WPR_Conditions_Manager::header_footer_display_conditions($heder_conditions);
		$header_template_id = !is_null($heder_template) ? Utilities::get_template_id($heder_template) : false;

		if ( false !== $header_template_id ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$header_css_file = new \Elementor\Core\Files\CSS\Post( $header_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$header_css_file = new \Elementor\Post_CSS_File( $header_template_id );
			}

			$header_css_file->enqueue();
		}

		// Load Footer Template CSS File
		$footer_conditions = json_decode( get_option('wpr_footer_conditions', '[]'), true );
		$footer_template = WPR_Conditions_Manager::header_footer_display_conditions($footer_conditions);
		$footer_template_id = !is_null($footer_template) ? Utilities::get_template_id($footer_template) : false;

		if ( false !== $footer_template_id ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$footer_css_file = new \Elementor\Core\Files\CSS\Post( $footer_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$footer_css_file = new \Elementor\Post_CSS_File( $footer_template_id );
			}

			$footer_css_file->enqueue();
		}

		// Load Canvas Content Template CSS File
		$canvas_conditions = WPR_Conditions_Manager::canvas_page_content_display_conditions();
		$canvas_template_id = !empty($canvas_conditions) ? Utilities::get_template_id($canvas_conditions) : false;

		if ( false !== $canvas_template_id ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$footer_css_file = new \Elementor\Core\Files\CSS\Post( $canvas_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$footer_css_file = new \Elementor\Post_CSS_File( $canvas_template_id );
			}

			$footer_css_file->enqueue();
		}
	}

}