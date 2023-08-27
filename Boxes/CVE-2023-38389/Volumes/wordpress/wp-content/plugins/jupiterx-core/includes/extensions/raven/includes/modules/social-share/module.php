<?php
namespace JupiterX_Core\Raven\Modules\Social_Share;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use JupiterX_Core\Raven\Modules\Social_Share\Widgets\Social_Share;

class Module extends Module_Base {
	public function __construct() {
		parent::__construct();

		$networks = self::supported_social_media();

		add_filter( 'elementor/editor/localize_settings', function( $settings ) use ( $networks ) {
			$new_settings = [];

			foreach ( $networks as $key => $label ) {
				$new_settings['share_buttons'][ $key ] = Social_Share::get_network_icon_data( $key )['value'];
			}

			$settings = array_replace_recursive( $settings,
				$new_settings
			);

			return $settings;
		}, 10, 1 );
	}

	public function get_widgets() {
		return [ 'social-share' ];
	}

	public static function supported_social_media() {
		return [
			'facebook'      => esc_html__( 'Facebook', 'jupiterx-core' ),
			'twitter'       => esc_html__( 'Twitter', 'jupiterx-core' ),
			'linkedin'      => esc_html__( 'Linkedin', 'jupiterx-core' ),
			'pinterest'     => esc_html__( 'Pinterest', 'jupiterx-core' ),
			'reddit'        => esc_html__( 'Reddit', 'jupiterx-core' ),
			'vk'            => esc_html__( 'VK', 'jupiterx-core' ),
			'odnoklassniki' => esc_html__( 'OK', 'jupiterx-core' ),
			'tumblr'        => esc_html__( 'Tumblr', 'jupiterx-core' ),
			'skype'         => esc_html__( 'Skype', 'jupiterx-core' ),
			'stumbleupon'   => esc_html__( 'StumbleUpon', 'jupiterx-core' ),
			'telegram'      => esc_html__( 'Telegram', 'jupiterx-core' ),
			'pocket'        => esc_html__( 'Pocket', 'jupiterx-core' ),
			'xing'          => esc_html__( 'Xing', 'jupiterx-core' ),
			'whatsapp'      => esc_html__( 'WhatsApp', 'jupiterx-core' ),
			'email'         => esc_html__( 'Email', 'jupiterx-core' ),
			'print'         => esc_html__( 'Print', 'jupiterx-core' ),
		];
	}
}
