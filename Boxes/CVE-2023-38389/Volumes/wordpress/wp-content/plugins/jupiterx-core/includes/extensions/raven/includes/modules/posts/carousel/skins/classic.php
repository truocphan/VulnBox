<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace JupiterX_Core\Raven\Modules\Posts\Carousel\Skins;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Posts\Module;

class Classic extends Base {

	public function get_id() {
		return 'classic';
	}

	public function get_title() {
		return esc_html__( 'Content Under Image', 'jupiterx-core' );
	}
}
