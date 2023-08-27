<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace JupiterX_Core\Raven\Modules\Posts\Carousel\Skins;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Posts\Module;

class Cover extends Base {

	public function get_id() {
		return 'cover';
	}

	public function get_title() {
		return esc_html__( 'Content Overlay', 'jupiterx-core' );
	}
}
