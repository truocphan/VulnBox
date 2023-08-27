<?php
namespace JupiterX_Core\Raven\Modules\Tabs;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_jupiterx_load_content_template', [ $this, 'load_content_template' ] );
		add_action( 'wp_ajax_nopriv_jupiterx_load_content_template', [ $this, 'load_content_template' ] );
	}

	public function load_content_template() {
		check_ajax_referer( 'jupiterx-core-raven', 'nonce' );

		$template_id = filter_input( INPUT_POST, 'template_id', FILTER_SANITIZE_NUMBER_INT );
		$data        = do_shortcode( sprintf( '[elementor-template id="%s"]', $template_id ) );

		wp_send_json_success( $data );
	}

	public function get_widgets() {
		return [ 'tabs' ];
	}

}
