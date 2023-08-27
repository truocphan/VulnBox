<?php
namespace JupiterX_Core\Raven\Modules\Global_Widget\Data;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Global_Widget\Module;
use Elementor\Data\Base\Controller as Controller_Base;

class Controller extends Controller_Base {

	public function get_name() {
		return 'global-widget/templates';
	}

	public function register_endpoints() {}

	public function get_items( $request ) {
		$result = [];
		$ids    = $request->get_param( 'ids' );

		if ( empty( $ids ) ) {
			return $result;
		}

		$ids = explode( ',', $ids );

		foreach ( $ids as $template_id ) {
			$template_data = Module::elementor()->templates_manager->get_template_data( [
				'source' => 'local',
				'template_id' => $template_id,
			] );

			if ( ! empty( $template_data ) ) {
				$result[ $template_id ] = $template_data['content'][0];
			}
		}

		return $result;
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_permission_callback( $request ) {
		return current_user_can( 'edit_posts' );
	}
}
