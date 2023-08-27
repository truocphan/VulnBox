<?php
namespace JupiterX_Core\Raven\Modules\Lottie;

use Elementor\Utils as ElementorUtils;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_filter( 'wp_check_filetype_and_ext', [ $this, 'handle_file_type' ], 10, 3 );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'frontend_register_scripts' ], 0 );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'frontend_enqueue_scripts' ], 1 );
	}

	public function get_widgets() {
		return [ 'lottie' ];
	}

	// Fixing WordPress problem when `finfo_file()` returns wrong file type
	public function handle_file_type( $file_data, $filename ) {
		if ( $file_data['ext'] && $file_data['type'] ) {
			return $file_data;
		}

		$filetype = wp_check_filetype( $filename );

		if ( 'json' === $filetype['ext'] ) {
			$file_data['ext']  = 'json';
			$file_data['type'] = 'application/json';
		}

		return $file_data;
	}

	public function frontend_register_scripts() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_register_script(
			'jupiterx-core-raven-lottie',
			jupiterx_core()->plugin_url() . 'includes/extensions/raven/assets/lib/lottie/lottie' . $suffix . '.js',
			[ 'jquery' ],
			'5.6.8',
			true
		);
	}

	public function frontend_enqueue_scripts() {
		$default_animation_url = jupiterx_core()->plugin_url() . 'includes/extensions/raven/assets/animations/default.json';

		wp_localize_script(
			'jupiterx-core-raven-frontend',
			'lottie_defaultAnimationUrl',
			[ 'url' => $default_animation_url ]
		);
	}
}
