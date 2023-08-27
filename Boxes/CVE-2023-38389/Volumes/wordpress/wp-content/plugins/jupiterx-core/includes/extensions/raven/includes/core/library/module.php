<?php
/**
 * Add Library Module.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Core\Library;

use Elementor\Core\Base\Module as BaseModule;
use JupiterX_Core\Raven\Core\Library\Documents;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

/**
 * Raven library module.
 *
 * Raven library module handler class is responsible for registering and
 * managing Elementor library modules.
 *
 * @since 1.0.0
 */
class Module extends BaseModule {

	/**
	 * Get module name.
	 *
	 * Retrieve the library module name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'library';
	}

	/**
	 * Add column head.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $defaults The default columns.
	 *
	 * @return array Default columns.
	 */
	public function add_column_head( $defaults ) {
		$defaults['shortcode'] = 'Shortcode';

		return $defaults;
	}

	/**
	 * Add column content.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string  $column_name The column name.
	 * @param integer $post_ID     The post ID.
	 *
	 * @return array Default columns.
	 */
	public function add_column_content( $column_name, $post_ID ) {
		if ( 'shortcode' !== $column_name ) {
			return;
		}

		echo '<input class="elementor-shortcode-input" style="width: 100%;" type="text" readonly="" onfocus="this.select()" value="[elementor-template id=&quot;' . $post_ID . '&quot;]">';
	}

	/**
	 * Register shortcode.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $atts The shortcode attributes.
	 *
	 * @return mixed Content.
	 */
	public function register_shortcode( $atts ) {
		if ( empty( $atts['id'] ) ) {
			return;
		}

		$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $atts['id'] );

		return $content;
	}

	/**
	 * Library module constructor.
	 *
	 * Initializing Raven library module.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		Plugin::$instance->documents
			->register_document_type( 'header', Documents\Header::get_class_full_name() )
			->register_document_type( 'footer', Documents\Footer::get_class_full_name() )
			->register_document_type( 'single', Documents\Single::get_class_full_name() )
			->register_document_type( 'archive', Documents\Archive::get_class_full_name() );

		add_filter( 'manage_elementor_library_posts_columns', [ $this, 'add_column_head' ], 15 );
		add_action( 'manage_elementor_library_posts_custom_column', [ $this, 'add_column_content' ], 10, 2 );

		if ( ! shortcode_exists( 'elementor-template' ) ) {
			add_shortcode( 'elementor-template', [ $this, 'register_shortcode' ] );
		}
	}
}
