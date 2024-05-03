<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'blocks_editor' ) ) :

	class blocks_editor extends Field_Base {

		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type    function
		*  @date    5/03/2014
		*  @since   5.0.0
		*
		*  @param   n/a
		*  @return  n/a
		*/

		function initialize() {
			// vars
			$this->name     = 'blocks_editor';
			$this->label    = __( 'Block Editor', 'acf-frontend-form-element' );
			$this->category = 'content';
			$this->defaults = array(
				'tabs'          => 'all',
				'toolbar'       => 'full',
				'media_upload'  => 1,
				'default_value' => '',
				'delay'         => 0,
			);

			//add_filter( 'frontend_admin/forms/sanitize_input', [ $this, 'sanitize_field_input' ], 10, 3 );
			add_action( 'frontend_admin/form_assets/type=blocks_editor', array( $this, 'form_assets' ) );
			add_action( 'frontend_admin/form_assets/type=post_content', array( $this, 'form_assets' ) );
			add_action( 'frontend_admin/form_assets/type=product_description', array( $this, 'form_assets' ) );

			
		}


			    
	/**
	 * Sanitize the data of the field's input
	 * 
 	 * @param bool $sanitized Whether or not we sanitized the data 
 	 * @param mixed $input Form input to sanitize 
	 * @param array $field An array holding all the field's data
	 *
	 * @return string
	 */
	public function sanitize_field_input( $sanitized, $input, $field ) {
		if( $this->name != $field['type'] ) return $sanitized;

		return $input;
	}

		    
		 	/**
	 * Load any third-party blocks
	 *
	 * @return void
	 */
	private function load_extra_blocks() {
		// phpcs:ignore
		$GLOBALS['hook_suffix'] = '';

		/**
		 * @psalm-suppress MissingFile
		 */
		require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
		/**
		 * @psalm-suppress MissingFile
		 */
		require_once ABSPATH . 'wp-admin/includes/screen.php';
		/**
		 * @psalm-suppress MissingFile
		 */
		require_once ABSPATH . 'wp-admin/includes/post.php';

		// Fake a WP_Screen object so we can pretend we're in the block editor, and therefore other block libraries load
		set_current_screen();

		$current_screen = get_current_screen();
		if ( $current_screen ) {
			$current_screen->is_block_editor( true );
		}
	}

		/**
	 * Restrict TinyMCE to the basics
	 *
	 * @param array $settings TinyMCE settings.
	 * @return array
	 */
	public function tiny_mce_before_init( $settings ) {
		$settings['toolbar1'] = 'bold,italic,bullist,numlist,blockquote,pastetext,removeformat,undo,redo';
		$settings['toolbar2'] = '';

		return $settings;
	}

		/**
	 * Ensure media works in Gutenberg
	 *
	 * @return void
	 */
	public function setup_media() {
		// If we've already loaded the media stuff then don't do it again
		if ( did_action( 'wp_enqueue_media' ) > 0 ) {
			return;
		}

		/**
		 * @psalm-suppress MissingFile
		 */
		require_once ABSPATH . 'wp-admin/includes/media.php';

		wp_enqueue_media();
	}

	/*
		*  input_admin_enqueue_scripts
		*
		*  description
		*
		*  @type    function
		*  @date    16/12/2015
		*  @since    5.3.2
		*
		*  @param    $post_id (int)
		*  @return    $post_id (int)
		*/

		function form_assets( $field ) {
			if( isset( $field['field_type'] ) && 'blocks_editor' != $field['field_type'] ){
				return;
			}
			global $block_assets, $post, $editor_assets;

			//try to get editor assets
			if( empty( $editor_assets ) ) $editor_assets = include_once FEA_DIR . '/main/gutenberg/build/frontend-block-editor/index.asset.php';
			
			//return if there are no editor assets
			if( empty( $editor_assets ) ) return;

			if( ! $block_assets ){
				$block_assets = true;			
				//$can_upload = true;

				//$this->load_extra_blocks();

				// Restrict tinymce buttons
				add_filter( 'tiny_mce_before_init', [ $this, 'tiny_mce_before_init' ] );

						// Keep Jetpack out of things
				add_filter(
					'jetpack_blocks_variation',
					function() {
						return 'no-post-editor';
					}
				);

						// Only call the editor assets if we are not dynamically loading.
				if ( ! defined( '__EXPERIMENTAL_DYNAMIC_LOAD' ) ) {
					wp_tinymce_inline_scripts();

					wp_enqueue_editor();

					do_action( 'enqueue_block_editor_assets' );

					add_action( 'wp_print_footer_scripts', array( '_WP_Editors', 'print_default_editor_scripts' ), 45 );
				}


				// Gutenberg scripts
				wp_enqueue_script( 'wp-block-library' );
				wp_enqueue_script( 'wp-format-library' );
				wp_enqueue_script( 'wp-editor' );
				wp_enqueue_script( 'wp-plugins' );

				// Gutenberg styles
				wp_enqueue_style( 'wp-edit-post' );
				wp_enqueue_style( 'wp-format-library' );

				// Keep Jetpack out of things
				add_filter(
					'jetpack_blocks_variation',
					function() {
						return 'no-post-editor';
					}
				);

				wp_tinymce_inline_scripts();
				wp_enqueue_editor();

				//do_action( 'enqueue_block_editor_assets' );

				add_action( 'wp_print_footer_scripts', array( '_WP_Editors', 'print_default_editor_scripts' ), 45 );

				$this->setup_rest_api();

				set_current_screen( 'front' );
				wp_styles()->done = array( 'wp-reset-editor-styles' );

				$categories = wp_json_encode( get_block_categories( $post ) );

				if ( $categories !== false ) {
					wp_add_inline_script(
						'wp-blocks',
						sprintf( 'wp.blocks.setCategories( %s );', $categories ),
						'after'
					);
				}

				/**
				 * @psalm-suppress PossiblyFalseOperand
				 */
				wp_add_inline_script(
					'wp-blocks',
					'wp.blocks.unstable__bootstrapServerSideBlockDefinitions(' . wp_json_encode( get_block_editor_server_block_settings() ) . ');'
				);

				//if( $can_upload ) $this->setup_media();
					
			} 
			
			wp_enqueue_script( 'fea-block-editor', FEA_URL . 'main/gutenberg/build/frontend-block-editor/index.js', $editor_assets['dependencies'], $editor_assets['version'] );
			wp_enqueue_style( 'fea-isolated-editor', FEA_URL . 'main/gutenberg/build/style-frontend-block-editor.css', [], $editor_assets['version'] );
			wp_enqueue_style( 'fea-block-editor', FEA_URL . 'assets/css/block-editor-min.css', [], $editor_assets['version'] ); 
		}

		/**
		 * Create the HTML interface for your field
		 *
		 * @param array $field An array holding all the field's data
		 *
		 * @type  action
		 * @since 3.6
		 * @date  23/01/13
		 */
		function render_field( $field ) {
			$this->form_assets( $field );

			echo '<div class="fea-block-wrap iso-editor__loading"></div>';
			acf_textarea_input(
				array(
					'class' => 'saved-blocks',
					'name'  => $field['name'],
					'style' => 'display:none!important',
					'value' => $field['value'],
				)
			);
		}

		/**
	 * Set up the Gutenberg REST API and preloaded data
	 *
	 * @return void
	 */
	public function setup_rest_api() {
		global $post;

		$post_type = 'post';

		// Preload common data.
		$preload_paths = array(
			'/',
			'/wp/v2/types?context=edit',
			'/wp/v2/taxonomies?per_page=-1&context=edit',
			'/wp/v2/themes?status=active',
			sprintf( '/wp/v2/types/%s?context=edit', $post_type ),
			sprintf( '/wp/v2/users/me?post_type=%s&context=edit', $post_type ),
			array( '/wp/v2/media', 'OPTIONS' ),
			array( '/wp/v2/blocks', 'OPTIONS' ),
		);

		/**
		 * @psalm-suppress TooManyArguments
		 */
		$preload_paths = apply_filters( 'block_editor_preload_paths', $preload_paths, $post );
		$preload_data = array_reduce( $preload_paths, 'rest_preload_api_request', array() );

		$encoded = wp_json_encode( $preload_data );
		if ( $encoded !== false ) {
			wp_add_inline_script(
				'wp-editor',
				sprintf( 'wp.apiFetch.use( wp.apiFetch.createPreloadingMiddleware( %s ) );', $encoded ),
				'after'
			);
		}
		
	}

	

		/**
		 * This filter is applied to the $value after it is loaded from the db, and before it is returned to the template
		 *
		 * @type  filter
		 * @since 3.6
		 * @date  23/01/13
		 *
		 * @param mixed $value   The value which was loaded from the database
		 * @param mixed $post_id The $post_id from which the value was loaded
		 * @param array $field   The field array holding all the field options
		 *
		 * @return mixed $value The modified value
		 */
		function format_value( $value, $post_id, $field ) {
			 // Bail early if no value or not a string.
			if ( empty( $value ) || ! is_string( $value ) ) {
				return $value;
			}

			$value = apply_filters( 'acf_the_content', $value );

			// Follow the_content function in /wp-includes/post-template.php
			return str_replace( ']]>', ']]&gt;', $value );
		}

	}




endif; // class_exists check

?>
