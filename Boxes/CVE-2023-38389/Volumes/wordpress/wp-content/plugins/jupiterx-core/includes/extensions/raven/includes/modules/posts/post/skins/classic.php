<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace JupiterX_Core\Raven\Modules\Posts\Post\Skins;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Posts\Module;

class Classic extends Base {

	public function get_id() {
		return 'classic';
	}

	public function get_title() {
		return __( 'Outer Content', 'jupiterx-core' );
	}
}
