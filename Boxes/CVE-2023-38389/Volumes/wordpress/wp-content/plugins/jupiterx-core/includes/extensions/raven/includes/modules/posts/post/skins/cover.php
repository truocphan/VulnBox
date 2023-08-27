<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace JupiterX_Core\Raven\Modules\Posts\Post\Skins;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Posts\Module;

class Cover extends Base {

	public function get_id() {
		return 'cover';
	}

	public function get_title() {
		return __( 'Inner Content', 'jupiterx-core' );
    }

    protected function _register_controls_actions() {
        parent::_register_controls_actions();

        $this->remove_control( 'mirror_rows' );
    }
}
