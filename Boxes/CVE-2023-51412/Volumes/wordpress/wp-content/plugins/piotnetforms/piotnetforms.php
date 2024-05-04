<?php
/**
 * Plugin Name: Piotnetforms
 * Description: Piotnet Forms - Highly Customizable WordPress Form Builder
 * Plugin URI:  https://piotnetforms.com/
 * Version:     1.0.25
 * Author:      Piotnet
 * Author URI:  https://piotnet.com/
 * Text Domain: piotnetforms
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once __DIR__ . '/inc/variables.php';

define( 'PIOTNETFORMS_VERSION', '1.0.25' );

class Piotnetforms extends Piotnetforms_Variables {

	public function __construct() {

		parent::__construct();

		add_action( 'plugins_loaded', [ $this, 'init' ] );
		register_activation_hook( __FILE__, [ $this, 'plugin_activate' ] );
	}

	public function init() {
        if ( !defined('PIOTNETFORMS_PRO_VERSION') ) {

            add_action( 'init', [ $this, 'register_post_type' ] );

            add_filter( 'single_template', [ $this, 'single_template' ] );

            require_once __DIR__ . '/inc/managers/controls.php';

            require_once __DIR__ . '/inc/class/piotnetforms-editor.php';

            require_once __DIR__ . '/inc/ajax/preview.php';

            require_once __DIR__ . '/inc/ajax/get-json-file.php';

            require_once __DIR__ . '/inc/ajax/save.php';

            require_once __DIR__ . '/inc/ajax/save-draft.php';

            require_once __DIR__ . '/inc/ajax/export.php';

            require_once __DIR__ . '/inc/ajax/duplicate.php';

            require_once __DIR__ . '/inc/shortcode/shortcode-widget.php';

            require_once( __DIR__ . '/inc/forms/meta-box-piotnetforms-shortcode-in-post.php' );

            add_action( 'wp_enqueue_scripts', [ $this, 'load_jquery' ] );

            add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );

            add_action( 'admin_enqueue_scripts', [ $this, 'add_admin_scripts' ], 10, 1 );

            add_action( 'admin_footer', [ $this, 'admin_footer' ], 10, 1 );

            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );

            add_action( 'admin_menu', [ $this, 'admin_menu' ], 600 );

            add_filter( 'the_content', [ $this, 'add_wrapper' ] );

            add_filter( 'manage_' . $this->slug . '_posts_columns', [ $this, 'set_custom_edit_columns' ] );
            add_action( 'manage_' . $this->slug . '_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );

        	add_filter( 'script_loader_tag', [ $this, 'custom_script_tag' ], 10, 3 );

            add_action( 'wp_footer', [ $this, 'enqueue_footer' ], 600 );

            add_action( 'wp_head', [ $this, 'enqueue_head' ], 600 );

            $upload     = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $upload_dir = $upload_dir . '/piotnetforms';
            if ( ! is_dir( $upload_dir ) ) {
                mkdir( $upload_dir, 0775 );
            } else {
                @chmod( $upload_dir, 0775 );
            }
            if ( ! is_dir( $upload_dir . '/css' ) ) {
                mkdir( $upload_dir . '/css', 0775 );
            } else {
                @chmod( $upload_dir . '/css', 0775 );
            }
            if ( ! is_dir( $upload_dir . '/files' ) ) {
                mkdir( $upload_dir . '/files', 0775 );
            } else {
                @chmod( $upload_dir . '/files', 0775 );
            }

            // Disable Directory Browsing
            if (!file_exists($upload_dir . '/files/index.html')) {
                touch($upload_dir . '/files/index.html');
            }
            if (!file_exists($upload_dir . '/css/index.html')) {
                touch($upload_dir . '/css/index.html');
            }

            add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ], 10, 1 );

            add_action( 'admin_init', [ $this, 'plugin_redirect' ] );

            add_filter( 'post_row_actions', [ $this, 'modify_list_row_actions' ], 10, 2 );

            add_filter( 'page_row_actions', [ $this, 'modify_list_row_actions' ], 10, 2 );

            add_filter( 'body_class', [ $this, 'add_body_class' ] );

            // Forms

            require_once( __DIR__ . '/inc/forms/ajax-form-builder.php' );

            if (function_exists('get_field')) {
                add_filter('acf/settings/remove_wp_meta_box', '__return_false');
            }

//            add_action( 'init', [ $this, 'check_starter_forms' ] );
        }
	}

    function check_starter_forms() {
        $option_key = 'piotnetforms_starter_forms_imported';
        $starter_forms_imported = get_option($option_key, null);
        if (!isset($starter_forms_imported)) {
            $this->import_starter_forms();
        }
        $data = [
            "importedDate" => time()
        ];
        update_option($option_key, $data);
    }

    function import_starter_forms() {
        $dir = __DIR__ . "/assets/forms/starter/";
        $files = array_diff(scandir($dir), array('.', '..'));
        if (count($files) === 0) {
            return;
        }

        foreach ($files as $file) {
            $content = file_get_contents( $dir . $file );
            $data         = json_decode( $content, true );

            $post = [
                'post_title'  => $data['title'],
                'post_status' => 'publish',
                'post_type'   => 'piotnetforms',
            ];

            $post_id = wp_insert_post( $post );
            piotnetforms_do_import( $post_id, $data );
        }
    }

	public function get_string_between($string, $start, $end){
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0) return '';
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}

	public function custom_script_tag( $tag, $handle, $src ) {
	    if ( stripos( $handle, 'piotnetforms-custom-script-tag-id-' ) !== false ) {
	    	$id = $this->get_string_between($handle, 'piotnetforms-custom-script-tag-id-', '-type');
	    	$type = $this->get_string_between($handle, '-id-', '-attr-');
	    	$attr = $this->get_string_between($handle, '-attr-', '-end');

	        $tag = str_replace( 'src=', 'id="' . $id . ' type="' . $type . '" ' . $attr . ' src=', $tag );
	    }
	    return $tag;
	}

	public function add_wrapper( $content ) {
		$post_id          = get_the_ID();

        $raw_data = get_post_meta( $post_id, '_piotnetforms_data', true );
        $data = json_decode( $raw_data, true );
        $widget_content = !empty($data['content']) ? $data['content'] : '';

		if ( ! empty( $widget_content ) ) {
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'piotnetforms' ) {
				$editor = new piotnetforms_Editor();
				if ( is_user_logged_in() ) {
					if ( current_user_can( 'edit_others_posts' ) ) {
						$content = $editor->editor_preview( $widget_content );
						$content = '<div class="piotnetforms-widget-preview" id="piotnetforms" data-piotnetforms-widget-preview data-piotnet-sortable>' . $content . '</div>';
					}
				}
			}

			if ( !isset( $_GET['action'] ) ) {
				$content          = $this->piotnetforms_render_loop( $widget_content, $post_id );
				$content          = '<div id="piotnetforms">' . $content . '</div>';

				$upload     = wp_upload_dir();
				$upload_dir = $upload['baseurl'];
				$upload_dir = $upload_dir . '/piotnetforms/css/';

				$css_file = $upload_dir . $post_id . '.css';

				wp_enqueue_style( 'piotnetforms-style-' . $post_id, $css_file, [], get_post_meta( $post_id, '_piotnet-revision-version', true ) );
				wp_enqueue_script( 'piotnetforms-script' );
				wp_enqueue_style( 'piotnetforms-style' );
			}
		} else {
			if ( is_user_logged_in() ) {
				if ( current_user_can( 'edit_others_posts' ) ) {
					if ( isset( $_GET['action'] ) && $_GET['action'] == 'piotnetforms' ) {
						$content = '<div class="piotnetforms-widget-preview" id="piotnetforms" data-piotnetforms-widget-preview data-piotnet-sortable></div>';
					}
				}
			}
		}

		return $content;
	}

	public function load_jquery() {
	    if ( ! wp_script_is( 'jquery', 'enqueued' )) {

	        //Enqueue
	        wp_enqueue_script( 'jquery' );

	    }
	}

	public function add_body_class( $classes ) {
		$classes[] = 'piotnetforms-edit';
		return $classes;
	}

	public function modify_list_row_actions( $actions, $post ) {
		// Check for your post type.
		if ( $post->post_type == "piotnetforms" ) {
			$url = admin_url() . 'admin.php?page=piotnetforms&post=' . $post->ID;

			$url_html = '<a href="' . esc_url( $url ) . '">' . __( 'Edit With Piotnet Forms', 'piotnetforms' ) . '</a>';

			$url_export_html = '<a href="' . esc_url( get_admin_url( null, 'admin-ajax.php?action=piotnetforms_export&id=' ) . $post->ID ) . '">' . __( 'Export', 'piotnetforms' ) . '</a>';

			$duplicate_html = '<a href="' . esc_url( get_admin_url( null, 'admin-ajax.php?action=piotnetforms_duplicate&id=' ) . $post->ID ) . '">' . __( 'Duplicate', 'piotnetforms' ) . '</a>';

			$actions['edit_with_piotnetforms'] = $url_html;
			$actions['export_piotnetforms'] = $url_export_html;
			$actions['duplicate_piotnetforms'] = $duplicate_html;
		}

		return $actions;
	}

	public function plugin_action_links( $links ) {
		$activated_license = get_option( 'piotnetforms-activated' );
		$links[]           = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=piotnetforms' ) ) . '">' . esc_html__( 'Settings', 'piotnetforms' ) . '</a>';
		if ( $activated_license != 1 ) {
			$links[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=piotnetforms' ) ) . '" class="piotnetforms-plugins-gopro">' . esc_html__( 'Activate License', 'piotnetforms' ) . '</a>';
		}
		$links[] = '<a href="http://piotnetforms.com/?wpam_id=1" target="_blank">' . esc_html__( 'Go Pro', 'pafe' ) . '</a>';
		return $links;

	}

	public function enqueue_frontend() {
		wp_enqueue_script( $this->slug . '-script' );
		wp_enqueue_style( $this->slug . '-style' );
	}

	public function enqueue() {
		wp_register_script( $this->slug . '-script', plugin_dir_url( __FILE__ ) . 'assets/js/minify/frontend.min.js', [ 'jquery' ], PIOTNETFORMS_VERSION );
		wp_register_style( $this->slug . '-style', plugin_dir_url( __FILE__ ) . 'assets/css/minify/frontend.min.css', [], PIOTNETFORMS_VERSION );

		if ( is_user_logged_in() ) {
			if ( current_user_can( 'edit_others_posts' ) ) {
				if ( isset( $_GET['action'] ) && $_GET['action'] == 'piotnetforms' ) {
					$this->enqueue_frontend();
					wp_enqueue_style( $this->slug . '-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/minify/admin.min.css', [], PIOTNETFORMS_VERSION );
				}
			}
		}

		global $post;
		if ( is_object($post) ) {
            if ( has_shortcode( $post->post_content, 'piotnetforms') || !empty($_GET['ct_builder']) ) {
                $this->enqueue_frontend();
            }
        }

		$shortcode = get_post_meta( get_the_ID(), '_piotnetforms_shortcode_in_post', true );
		if (!empty($shortcode)) {
			$this->enqueue_frontend();
			$shortcode = explode('|', $shortcode);
			foreach ($shortcode as $shortcode_item) {
				$shortcode_atts = shortcode_parse_atts($shortcode_item);
				if (!empty($shortcode_atts['id'])) {
					$post_id = intval($shortcode_atts['id']);
					$upload     = wp_upload_dir();
					$upload_dir = $upload['baseurl'];
					$upload_dir = $upload_dir . '/piotnetforms/css/';
					$css_file = $upload_dir . $post_id . '.css';

					wp_enqueue_style( $this->slug . '-style-' . $post_id, $css_file, [], get_post_meta( $post_id, '_piotnet-revision-version', true ) );
				}
			}
		}
	}

	public function add_admin_scripts( $hook ) {
		global $post;

		if ( isset($_GET['page']) && isset($_GET['post']) ) {
			if ( $_GET['page'] == 'piotnetforms' ) {
				wp_enqueue_script( $this->slug . '-editor-script', plugin_dir_url( __FILE__ ) . 'assets/js/minify/editor.min.js', [ 'jquery' ], PIOTNETFORMS_VERSION );
				wp_enqueue_script( $this->slug . '-editor-forms-script', plugin_dir_url( __FILE__ ) . 'assets/js/minify/preview.min.js', [ 'jquery' ], PIOTNETFORMS_VERSION );
				wp_enqueue_style( $this->slug . '-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/minify/admin.min.css', [], PIOTNETFORMS_VERSION );
				wp_enqueue_script( $this->slug . '-script', plugin_dir_url( __FILE__ ) . 'assets/js/minify/frontend.min.js', [ 'jquery' ], PIOTNETFORMS_VERSION );
				wp_enqueue_style( $this->slug . '-style', plugin_dir_url( __FILE__ ) . 'assets/css/minify/frontend.min.css', [], PIOTNETFORMS_VERSION );
				wp_enqueue_media();
			}
		}

		wp_enqueue_script( $this->slug . '-admin-script', plugin_dir_url( __FILE__ ) . 'assets/js/minify/admin.min.js', [ 'jquery' ], PIOTNETFORMS_VERSION );
	}

	public function enqueue_head() {

		if ( is_user_logged_in() ) {
			if ( current_user_can( 'edit_others_posts' ) ) {
				if ( isset( $_GET['action'] ) && $_GET['action'] == 'piotnetforms' ) {
					$post_id = get_the_ID();

					if ( $post_id != false ) {
						$widget_settings = get_post_meta( $post_id, '_piotnet-widget-settings', true );

						if ( ! empty( $widget_settings ) ) {
							$widget_settings = json_decode( $widget_settings, true );
							if ( ! empty( $widget_settings['fonts'] ) ) {
								$fonts = $widget_settings['fonts'];
								foreach ( $fonts as $font ) {
									wp_enqueue_style( $this->slug . '-font-' . $font, $font, [] );
								}
							}
						}
					}
				}
			}
		}
	}

	public function enqueue_footer() {
		echo '<div data-piotnetforms-ajax-url="' . admin_url( 'admin-ajax.php' ) . '"></div>';
		echo '<div data-piotnetforms-plugin-url="' . plugins_url() . '"></div>';
		echo '<div class="piotnetforms-break-point" data-piotnetforms-break-point-md="1025" data-piotnetforms-break-point-lg="767"></div>';
	}

	public function admin_footer() {
		echo '<div data-piotnetforms-admin-url="' . admin_url() . '"></div>';
		echo '<div data-piotnetforms-plugin-url="' . plugins_url() . '"></div>';
		global $pagenow;
		global $typenow;
		if ("piotnetforms" == $typenow) {
			wp_enqueue_script( $this->slug . '-admin-forms-script', plugin_dir_url( __FILE__ ) . 'assets/js/admin-forms.js', [ 'jquery' ], PIOTNETFORMS_VERSION );
		}

		if (( $pagenow == 'edit.php' ) && !empty($_GET['post_type'])) {
			if (sanitize_text_field($_GET['post_type']) == 'piotnetforms') {
				if ( get_option( 'piotnetforms_do_flush', false ) ) {
					delete_option( 'piotnetforms_do_flush' );
					flush_rewrite_rules();
				}
			}
		}
	}

	public function register_post_type() {
		register_post_type(
			$this->slug,
			[
				'labels'       => [
					'name'          => __( $this->post_type_name, 'piotnetforms' ),
					'singular_name' => __( $this->post_type_name, 'piotnetforms' ),
				],
				'public'       => true,
				'has_archive'  => true,
				'show_in_menu' => false,
				'supports'     => [
					'title',
					'custom-fields',
				],
			]
		);

		remove_post_type_support( $this->slug, 'editor' );
	}

	public function single_template($single) {
	    global $post;
	    if ( $post->post_type == 'piotnetforms' ) {
	        return plugin_dir_path( __FILE__ ) . 'inc/templates/single-template.php';
	    }
	    return $single;
	}

	public function admin_menu() {

		add_menu_page(
			$this->plugin_name,
			$this->plugin_name,
			'edit_others_posts',
			$this->slug,
			[ $this, 'settings_page' ],
			'dashicons-piotnetforms-icon'
		);

		add_submenu_page( $this->slug, 'Settings', 'Settings', 'edit_others_posts', $this->slug, [ $this, 'settings_page' ] );

		add_submenu_page( $this->slug, 'Forms', 'Forms', 'edit_others_posts', 'edit.php?post_type=' . $this->slug );

		add_submenu_page( $this->slug, 'Import', 'Import', 'edit_others_posts', 'import-piotnetforms', [ $this, 'import_page' ] );

		add_submenu_page($this->slug, 'Database (Pro)', 'Database (Pro)', 'manage_options', 'edit.php?post_type=piotnetforms-data');

		add_submenu_page($this->slug, 'Abandonment (Pro)', 'Abandonment (Pro)', 'manage_options', 'edit.php?post_type=piotnetforms-aban');
		
		add_submenu_page($this->slug, 'Booking (Pro)', 'Booking (Pro)', 'manage_options', 'edit.php?post_type=piotnetforms-book');

		add_action( 'admin_init', [ $this, 'piotnet_base_settings' ] );
	}

	public function piotnet_base_settings() {
		register_setting( 'piotnetforms-google-sheets-group', 'piotnetforms-google-sheets-client-id' );
		register_setting( 'piotnetforms-google-sheets-group', 'piotnetforms-google-sheets-client-secret' );

		register_setting( 'piotnetforms-google-maps-group', 'piotnetforms-google-maps-api-key' );

		register_setting( 'piotnetforms-stripe-group', 'piotnetforms-stripe-publishable-key' );
		register_setting( 'piotnetforms-stripe-group', 'piotnetforms-stripe-secret-key' );

		register_setting( 'piotnetforms-mailchimp-group', 'piotnetforms-mailchimp-api-key' );

		register_setting( 'piotnetforms-mailerlite-group', 'piotnetforms-mailerlite-api-key' );

		register_setting( 'piotnetforms-activecampaign-group', 'piotnetforms-activecampaign-api-key' );
		register_setting( 'piotnetforms-activecampaign-group', 'piotnetforms-activecampaign-api-url' );

		register_setting( 'piotnetforms-recaptcha-group', 'piotnetforms-recaptcha-site-key' );
		register_setting( 'piotnetforms-recaptcha-group', 'piotnetforms-recaptcha-secret-key' );

		register_setting( 'piotnetforms-zoho-group', 'piotnetforms-zoho-domain' );
		register_setting( 'piotnetforms-zoho-group', 'piotnetforms-zoho-client-id' );
		register_setting( 'piotnetforms-zoho-group', 'piotnetforms-zoho-client-secret' );
		register_setting( 'piotnetforms-zoho-group', 'piotnetforms-zoho-refresh-token' );
		register_setting( 'piotnetforms-zoho-group', 'piotnetforms-zoho-token' );

		register_setting( 'piotnetforms-paypal-group', 'piotnetforms-paypal-client-id' );

		register_setting( 'piotnetforms-settings-group', 'piotnetforms-username' );
		register_setting( 'piotnetforms-settings-group', 'piotnetforms-password' );
	}

	public function settings_page() {

		require_once __DIR__ . '/inc/settings/settings-page.php';

	}

	public function import_page() {

		require_once __DIR__ . '/inc/settings/import-page.php';

	}

	public function admin_enqueue() {
		wp_enqueue_style( $this->slug . '-admin-css', plugin_dir_url( __FILE__ ) . 'assets/css/minify/admin.min.css', false, PIOTNETFORMS_VERSION );
	}

	public function set_custom_edit_columns( $columns ) {
		$columns['piotnet-widget-shortcode'] = __( 'Shortcode', 'piotnetforms' );
		return $columns;
	}

	public function custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'piotnet-widget-shortcode':
				echo '<input class="piotnet-widget-shortcode-input" type="text" readonly="" onfocus="this.select()" value="[' . $this->slug . ' id=' . $post_id  . ']">';
				break;
		}
	}

	public function plugin_redirect() {

		if ( get_option( 'piotnetforms_do_activation_redirect', false ) ) {
			delete_option( 'piotnetforms_do_activation_redirect' );
			flush_rewrite_rules();
			wp_redirect( 'edit.php?post_type=piotnetforms' );
		}

	}

	public function plugin_row_meta( $links, $file ) {

		if ( strpos( $file, 'piotnetforms' ) !== false ) {
			$links[] = '<a href="https://piotnetforms.com/documents/" target="_blank">' . esc_html__( 'Documents', 'piotnetforms' ) . '</a>';
			$links[] = '<a href="https://piotnetforms.com/change-log/" target="_blank">' . esc_html__( 'Change Log', 'piotnetforms' ) . '</a>';
		}
		return $links;

	}

	public function plugin_activate() {

		add_option( 'piotnetforms_do_activation_redirect', true );
		add_option( 'piotnetforms_do_flush', true );

	}

    /**
     * @return piotnetforms_Base_Control[]
     */
	private function new_widget( string $class_name ) {
		return new $class_name();
	}

	public function piotnetforms_render_loop( $loop, $post_id ) {

		ob_start();

		foreach ( $loop as $widget_item ) {
			$widget            = $this->new_widget( $widget_item['class_name'] );
			$widget->settings  = $widget_item['settings'];
			$widget_id         = $widget_item['id'];
			$widget->widget_id = $widget_id;
			$widget->post_id   = $post_id;

			if ( ! empty( $widget_item['fonts'] ) ) {
				$fonts = $widget_item['fonts'];
				if ( ! empty( $fonts ) ) {
					foreach ( $fonts as $font ) {
						wp_enqueue_style( 'piotnetforms-add-font-' . $font, $font );
					}
				}
			}

			$widget_type = $widget->get_type();
			if ( $widget_type === 'section' || $widget_type === 'column' ) {
				echo @$widget->output_wrapper_start( $widget_id );
				if ( isset( $widget_item['elements'] ) ) {
					echo @$this->piotnetforms_render_loop( $widget_item['elements'], $post_id );
				}
			} else {
				$output = @$widget->output( $widget_id );
				$output = @$this->piotnetforms_dynamic_tags( $output );
				echo @$output;
			}

			if ( $widget_type === 'section' || $widget_type === 'column' ) {
				echo @$widget->output_wrapper_end( $widget_id );
			}
		}

		return ob_get_clean();
	}

	public function piotnetforms_dynamic_tags( $output ) {

		if ( stripos( $output, '{{' ) !== false && stripos( $output, '}}' ) !== false ) {
			$pattern = '~\{\{\s*(.*?)\s*\}\}~';
			preg_match_all( $pattern, $output, $matches );
			$dynamic_tags = [];

			if ( ! empty( $matches[1] ) ) {
				$matches = array_unique( $matches[1] );

				foreach ( $matches as $key => $match ) {
					if ( stripos( $match, '|' ) !== false ) {
						$match_attr = explode( '|', $match );
						$attr_array = [];
						foreach ( $match_attr as $key_attr => $value_attr ) {
							if ( $key_attr != 0 ) {
								$attr                           = explode( ':', $value_attr, 2 );
								$attr_array[ trim( $attr[0] ) ] = trim( $attr[1] );
							}
						}

						$dynamic_tags[] = [
							'dynamic_tag' => '{{' . $match . '}}',
							'name'        => trim( $match_attr[0] ),
							'attr'        => $attr_array,
						];
					} else {
						$dynamic_tags[] = [
							'dynamic_tag' => '{{' . $match . '}}',
							'name'        => trim( $match ),
						];
					}
				}
			}

			if ( ! empty( $dynamic_tags ) ) {
				foreach ( $dynamic_tags as $tag ) {
					if ( $tag['name'] == 'current_date_time' ) {
						if ( empty( $tag['attr']['date_format'] ) ) {
							$tag_value = date( 'Y-m-d H:i:s' );
						} else {
							$tag_value = date( $tag['attr']['date_format'] );
						}

						$output = str_replace( $tag['dynamic_tag'], $tag_value, $output );
					}

					if ( $tag['name'] == 'post_id' ) {
						$tag_value = get_the_ID();
						$output    = str_replace( $tag['dynamic_tag'], $tag_value, $output );
					}
				}
			}
		}

		return $output;
	}

}

$piotnetforms = new Piotnetforms();
