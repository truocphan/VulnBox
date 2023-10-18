<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Plugin;
use Elementor\TemplateLibrary\Source_Base;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * WPR_Templates_Actions setup
 *
 * @since 1.0
 */
class WPR_Templates_Actions {

	/**
	** Constructor
	*/
	public function __construct() {

		// Save Conditions
		add_action( 'wp_ajax_wpr_save_template_conditions', [ $this, 'wpr_save_template_conditions' ] );

		// Create Template
		add_action( 'wp_ajax_wpr_create_template', [ $this, 'wpr_create_template' ] );

		// Import Library Template
		add_action( 'wp_ajax_wpr_import_library_template', [ $this, 'wpr_import_library_template' ] );

		// Reset Template
		add_action( 'wp_ajax_wpr_delete_template', [ $this, 'wpr_delete_template' ] );

		// Register Elementor AJAX Actions
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_elementor_ajax_actions' ] );

		// Enqueue Scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'templates_library_scripts' ] );

	}

	/**
	** Save Template Conditions
	*/
	public function wpr_save_template_conditions() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template = isset($_POST['template']) ? sanitize_text_field(wp_unslash($_POST['template'])): false;

		// Header
		if ( isset($_POST['wpr_header_conditions']) ) {
			update_option( 'wpr_header_conditions', $this->sanitize_conditions($_POST['wpr_header_conditions']) );  // phpcs:ignore

			$wpr_header_show_on_canvas = isset($_POST['wpr_header_show_on_canvas']) ? sanitize_text_field(wp_unslash($_POST['wpr_header_show_on_canvas'])): false;
			if ( $wpr_header_show_on_canvas && $template ) {
				update_post_meta( Utilities::get_template_id($template), 'wpr_header_show_on_canvas', $wpr_header_show_on_canvas );
			}
		}

		// Footer
		if ( isset($_POST['wpr_footer_conditions']) ) {
			update_option( 'wpr_footer_conditions', $this->sanitize_conditions($_POST['wpr_footer_conditions']) );  // phpcs:ignore

			$wpr_footer_show_on_canvas = isset($_POST['wpr_footer_show_on_canvas']) ? sanitize_text_field(wp_unslash($_POST['wpr_footer_show_on_canvas'])): false;
			if ( $wpr_footer_show_on_canvas && $template ) {
				update_post_meta( Utilities::get_template_id($template), 'wpr_footer_show_on_canvas', $wpr_footer_show_on_canvas );
			}
		}

		// Archive
		if ( isset($_POST['wpr_archive_conditions']) ) {
			update_option( 'wpr_archive_conditions', $this->sanitize_conditions($_POST['wpr_archive_conditions']) );  // phpcs:ignore
		}

		// Single
		if ( isset($_POST['wpr_single_conditions']) ) {
			update_option( 'wpr_single_conditions', $this->sanitize_conditions($_POST['wpr_single_conditions']) );  // phpcs:ignore
		}

		// Product Archive
		if ( isset($_POST['wpr_product_archive_conditions']) ) {
			update_option( 'wpr_product_archive_conditions', $this->sanitize_conditions($_POST['wpr_product_archive_conditions']) );  // phpcs:ignore
		}

		// Product Single
		if ( isset($_POST['wpr_product_single_conditions']) ) {
			update_option( 'wpr_product_single_conditions', $this->sanitize_conditions($_POST['wpr_product_single_conditions']) );  // phpcs:ignore
		}

		// Popup
		if ( isset($_POST['wpr_popup_conditions']) ) {
			update_option( 'wpr_popup_conditions', $this->sanitize_conditions($_POST['wpr_popup_conditions']) );  // phpcs:ignore
		}
	}

	public function sanitize_conditions( $data ) {
		return wp_unslash( json_encode( array_filter( json_decode(stripcslashes($data), true) ) ) );
	}

	/**
	** Create Template
	*/
	public function wpr_create_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$user_template_type = isset($_POST['user_template_type']) ? sanitize_text_field(wp_unslash($_POST['user_template_type'])): false;
		$user_template_library = isset($_POST['user_template_library']) ? sanitize_text_field(wp_unslash($_POST['user_template_library'])): false;
		$user_template_title = isset($_POST['user_template_title']) ? sanitize_text_field(wp_unslash($_POST['user_template_title'])): false;
		$user_template_slug = isset($_POST['user_template_slug']) ? sanitize_text_field(wp_unslash($_POST['user_template_slug'])): false;
		
		$check_post_type =( $user_template_library == 'wpr_templates' || $user_template_library == 'elementor_library' );

		if ( $user_template_title && $check_post_type ) {
			// Create
			$template_id = wp_insert_post(array (
				'post_type' 	=> $user_template_library,
				'post_title' 	=> $user_template_title,
				'post_name' 	=> $user_template_slug,
				'post_content' 	=> '',
				'post_status' 	=> 'publish'
			));

			// Set Types
			if ( 'wpr_templates' === $_POST['user_template_library'] ) {

				wp_set_object_terms( $template_id, [$user_template_type, 'user'], 'wpr_template_type' );

				if ( 'popup' === $_POST['user_template_type'] ) {
					update_post_meta( $template_id, '_elementor_template_type', 'wpr-popups' );
				} else {
					if ( 'header' === $_POST['user_template_type'] ) {
						update_post_meta( $template_id, '_elementor_template_type', 'wpr-theme-builder-header' );
					} elseif ( 'footer' === $_POST['user_template_type'] ) {
						update_post_meta( $template_id, '_elementor_template_type', 'wpr-theme-builder-footer' );
					} else {
						update_post_meta( $template_id, '_elementor_template_type', 'wpr-theme-builder' );
					}

					update_post_meta( $template_id, '_wpr_template_type', $user_template_type );
				}
			} else {
				update_post_meta( $template_id, '_elementor_template_type', 'page' );
			}

			// Set Canvas Template
			update_post_meta( $template_id, '_wp_page_template', 'elementor_canvas' ); //tmp - maybe set for wpr_templates only

			// Send ID to JS
			echo esc_html($template_id);
		}
	}

	/**
	** Import Library Template
	*/
	public function wpr_import_library_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'wpr-addons-library-frontend-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        $source = new WPR_Library_Source();
		$slug = isset($_POST['slug']) ? sanitize_text_field(wp_unslash($_POST['slug'])) : '';
		$kit = isset($_POST['kit']) ? sanitize_text_field(wp_unslash($_POST['kit'])) : '';
		$section = isset($_POST['section']) ? sanitize_text_field(wp_unslash($_POST['section'])) : '';

        $data = $source->get_data([
        	'template_id' => $slug,
			'kit_id' => $kit,
			'section_id' => $section
        ]);
        
		$template = '' !== $kit ? $kit : $slug;
		if ( $this->vts($slug) ) {
			echo json_encode($data);
		}
	}

	/**
	** Validate Template
	*/
	public function vts( $template ) {
		// Avoid Cache
		$randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);

		$remote_file_url = 'https://royal-elementor-addons.com/library/vts.json?='. $randomNum;

		$tmp_file = download_url( $remote_file_url );

		$file_args = [
			'name'     => 'vts.json',
			'tmp_name' => $tmp_file,
			'error'    => 0,
			'size'     => filesize( $tmp_file ),
		];

		$defaults = array(
			'test_form' => false,
			'test_size' => true,
			'test_upload' => true,
			'mimes'  => [
				'xml'  => 'text/xml',
				'json' => 'text/plain',
			],
			'wp_handle_sideload' => 'upload',
		);

		$local_file = wp_handle_sideload( $file_args, $defaults );

		if ( isset( $local_file['error'] ) ) {
			return false;
		}

		$tmps = json_decode(file_get_contents($local_file['file']));

		// Delete Import File
		unlink( $local_file['file'] );

		return in_array($template, $tmps) && !wpr_fs()->can_use_premium_code() ? false : true;
	}

	/**
	** Reset Template
	*/
	public function wpr_delete_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'delete_post-' . $_POST['template_slug'] )  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template_slug = isset($_POST['template_slug']) ? sanitize_text_field(wp_unslash($_POST['template_slug'])): '';
		$template_library = isset($_POST['template_library']) ? sanitize_text_field(wp_unslash($_POST['template_library'])): '';

		$post = get_page_by_path( $template_slug, OBJECT, $template_library );

		if ( get_post_type($post->ID) == 'wpr_templates' || get_post_type($post->ID) == 'elementor_library' ) {
			wp_delete_post( $post->ID, true );
		}
	}

	/**
	** Enqueue Scripts and Styles
	*/
	public function templates_library_scripts( $hook ) {

		// Get Plugin Version
		$version = Plugin::instance()->get_version();

		// Deny if NOT Plugin Page
		if ( 'toplevel_page_wpr-addons' == $hook || strpos($hook, 'wpr-theme-builder') || strpos($hook, 'wpr-popups') ) {

			// Color Picker
			wp_enqueue_style( 'wp-color-picker' );
		    wp_enqueue_script( 'wp-color-picker-alpha', WPR_ADDONS_URL .'assets/js/admin/lib/wp-color-picker-alpha.min.js', ['jquery', 'wp-color-picker'], $version, true );

		    // Media Upload
			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			// enqueue CSS
			wp_enqueue_style( 'wpr-plugin-options-css', WPR_ADDONS_URL .'assets/css/admin/plugin-options.css', [], $version );

		    // enqueue JS
		    wp_enqueue_script( 'wpr-plugin-options-js', WPR_ADDONS_URL .'assets/js/admin/plugin-options.js', ['jquery'], $version );

			wp_localize_script(
				'wpr-plugin-options-js',
				'WprPluginOptions', // This is used in the js file to group all of your scripts together
				[
					'nonce' => wp_create_nonce( 'wpr-plugin-options-js' ),
				]
			);

		}

		if ( strpos($hook, 'wpr-templates-kit') ) {
			wp_enqueue_style( 'wpr-templates-kit-css', WPR_ADDONS_URL .'assets/css/admin/templates-kit.css', [], $version );
		    wp_enqueue_script( 'wpr-templates-kit-js', WPR_ADDONS_URL .'assets/js/admin/templates-kit.js', ['jquery', 'updates'], $version );

			wp_localize_script(
				'wpr-templates-kit-js',
				'WprTemplatesKitLoc', // This is used in the js file to group all of your scripts together
				[
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'resturl' => get_rest_url() . 'wpraddons/v1',
					'nonce' => wp_create_nonce( 'wpr-templates-kit-js' ),
				]
			);
		}

		if ( strpos($hook, 'wpr-premade-blocks') ) {
			wp_enqueue_style( 'wpr-premade-blocks-css', WPR_ADDONS_URL .'assets/css/admin/premade-blocks.css', [], $version );

		    wp_enqueue_script( 'wpr-macy-js', WPR_ADDONS_URL .'assets/js/lib/macy/macy.js', ['jquery'], $version );
		    wp_enqueue_script( 'wpr-premade-blocks-js', WPR_ADDONS_URL .'assets/js/admin/premade-blocks.js', ['jquery'], $version );
		}
	}

	/**
	** Register Elementor AJAX Actions
	*/
	public function register_elementor_ajax_actions( Ajax $ajax ) {

		// Elementor Search Data
		$ajax->register_ajax_action( 'wpr_elementor_search_data', function( $data ) {
			// Freemius OptIn
			if ( ! (wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed() || wpr_fs()->is_pending_activation() )) {
				return;
			}

			if ( strlen($data['search_query']) > 25 ) {
				return;
			}

			// Send Search Query
		    wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/elementor-search/data', [
		        'body' => [
		            'search_query' => $data['search_query']
		        ]
		    ] );
		} );

		// Templates Library Pages Search Data
		$ajax->register_ajax_action( 'wpr_templates_library_search_data', function( $data ) {
			// Freemius OptIn
			if ( ! (wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed() || wpr_fs()->is_pending_activation() )) {
				return;
			}

			if ( strlen($data['search_query']) > 25 ) {
				return;
			}

			// Send Search Query
		    wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/templates-library-search/data', [
		        'body' => [
		            'search_query' => $data['search_query']
		        ]
		    ] );
		} );

		// Templates Library Blocks Search Data
		$ajax->register_ajax_action( 'wpr_templates_library_blocks_search_data', function( $data ) {
			// Freemius OptIn
			if ( ! (wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed() || wpr_fs()->is_pending_activation() )) {
				return;
			}

			if ( strlen($data['search_query']) > 25 ) {
				return;
			}

			// Send Search Query
		    wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/templates-library-blocks-search/data', [
		        'body' => [
		            'search_query' => $data['search_query']
		        ]
		    ] );
		} );

		// Templates Library Sections Search Data
		$ajax->register_ajax_action( 'wpr_templates_library_sections_search_data', function( $data ) {
			// Freemius OptIn
			if ( ! (wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed() || wpr_fs()->is_pending_activation() )) {
				return;
			}

			if ( strlen($data['search_query']) > 25 ) {
				return;
			}

			// Send Search Query
		    wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/templates-library-sections-search/data', [
		        'body' => [
		            'search_query' => $data['search_query']
		        ]
		    ] );
		} );

		// Library Template Import Finished
		$ajax->register_ajax_action( 'wpr_library_template_import_finished', function( $data ) {
			Utilities::regenerate_extra_image_sizes();

			// Freemius OptIn
			if ( ! (wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed() || wpr_fs()->is_pending_activation() )) {
				return;
			}

			// Templates Library Import Block
			if ( isset($data['block'])) {
				wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/templates-library-blocks-import/data', [
					'body' => [
						'imported_block' => $data['block']
					]
				] );
			}

			// Templates Library Import Section
			if ( isset($data['section'])) {
				wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/templates-library-sections-import/data', [
					'body' => [
						'imported_section' => $data['section']
					]
				] );
			}

			if ( ! isset($data['kit']) ) {
				return;
			}

			// Templates Library Import Template
		    wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/templates-library-import/data', [
		        'body' => [
		            'imported_template' => $data['kit'] .'-'. $data['template'] . ' *'. WPR_ADDONS_VERSION .'*'
		        ]
		    ] );
		} );
	}
}

/**
 * WPR_Templates_Actions setup
 *
 * @since 1.0
 */
class WPR_Library_Source extends \Elementor\TemplateLibrary\Source_Base {

	public function get_id() {
		return 'wpr-layout-manager';
	}

	public function get_title() {
		return 'WPR Layout Manager';
	}

	public function register_data() {}

	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a WPR layout manager' );
	}

	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a WPR layout manager' );
	}

	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a WPR layout manager' );
	}

	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a WPR layout manager' );
	}

	public function get_items( $args = [] ) {
		return [];
	}

	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	public function request_template_data( $template_id, $kit_id, $section_id ) {
		if ( empty( $template_id ) ) {
			return;
		}

		if ( '' !== $kit_id ) {
			$url = 'https://royal-elementor-addons.com/library/templates-kit/'. $kit_id .'/';
		} elseif ( '' !== $section_id ) {
			$url = 'https://royal-elementor-addons.com/library/premade-sections/';
			// $url = 'https://royal-elementor-addons.com/library/premade-sections/'. $kit_id .'/';
		} else {
			$url = 'https://royal-elementor-addons.com/library/premade-styles/';
		}

		// Avoid Cache
		$randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);
		
		$response = wp_remote_get($url . $template_id .'.json?='. $randomNum, [
			'timeout'   => 60,
			'sslverify' => false
		] );
		
		return wp_remote_retrieve_body( $response );
	}

	public function get_data( array $args ) {
		$data = $this->request_template_data( $args['template_id'], $args['kit_id'], $args['section_id'] );

		$data = json_decode( $data, true );

		if ( empty( $data ) || empty( $data['content'] ) ) {
			throw new \Exception( 'Template does not have any content' );
		}

		add_filter( 'intermediate_image_sizes_advanced', [new Utilities, 'disable_extra_image_sizes'], 10, 3 );

		// Remove Parallax Images from Import File
		foreach( $data['content'] as $key => $content ) {
			if ( isset($data['content'][$key]['settings']['bg_image']) ) {
				unset($data['content'][$key]['settings']['bg_image']);
			}
			if ( isset($data['content'][$key]['settings']['hover_parallax']) ) {
				unset($data['content'][$key]['settings']['hover_parallax']);
			}
		}

		$parallax_bg = get_option('wpr-parallax-background', 'on');
		$parallax_multi = get_option('wpr-parallax-multi-layer', 'on');

		// Disable Extensions during Import
		if ( 'on' === $parallax_bg ) {
			update_option('wpr-parallax-background', '');
		}
		if ( 'on' === $parallax_multi ) {
			update_option('wpr-parallax-multi-layer', '');
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );		
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		// Enable Back
		if ( 'on' === $parallax_bg ) {
			update_option('wpr-parallax-background', 'on');
		}
		if ( 'on' === $parallax_multi ) {
			update_option('wpr-parallax-multi-layer', 'on');
		}

		return $data;
	}

}