<?php
namespace JupiterX_Core\Control_Panel_2\Custom_Icons\Icon_Sets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use JupiterX_Core\Control_Panel_2\Custom_Icons\Icon_Sets\Icon_Set_Base as Base;

class Fontello extends Base {

	protected $data_file = 'config.json';

	protected $stylesheet_file = '';

	protected $allowed_zipped_files = [ 'config.json', 'demo.html', 'README.txt', 'LICENSE.txt', 'css/', 'font/' ];

	protected $allowed_webfont_extensions = [ 'woff', 'woff2', 'ttf', 'svg', 'otf' ];

	protected function prepare() {
		$this->remove_fontello_styling();
		$this->dir_name = $this->get_unique_name();
	}

	public function get_type() {
		return esc_html__( 'Fontello', 'jupiterx-core' );
	}

	public function is_valid() {
		if ( ! file_exists( $this->directory . $this->data_file ) ) {
			return false; //Missing data file.
		}

		return true;
	}

	private function remove_fontello_styling() {
		$filename      = $this->directory . 'css/' . $this->get_name() . '.css';
		$stylesheet    = $this->_unstable_file_get_contents( $filename );
		$stylesheet    = str_replace( [ 'margin-left: .2em;', 'margin-right: .2em;' ], [ '', '' ], $stylesheet );
		$wp_filesystem = Base::get_wp_filesystem();

		$wp_filesystem->put_contents( $filename, $stylesheet );
	}

	private function get_json() {
		return json_decode( $this->_unstable_file_get_contents( $this->directory . $this->data_file ) );
	}

	protected function extract_icon_list() {
		$config = $this->get_json();

		if ( ! isset( $config->glyphs ) ) {
			return false; //Missing icons list.
		}

		$icons = [];
		foreach ( $config->glyphs as $icon ) {
			$icons[] = $icon->css;
		}

		return $icons;
	}

	protected function get_prefix() {
		$config = $this->get_json();

		if ( ! isset( $config->css_prefix_text ) ) {
			return false; //Missing css_prefix_text.
		}

		return $config->css_prefix_text;
	}

	public function get_name() {
		$config = $this->get_json();

		if ( ! isset( $config->name ) ) {
			return false; //Missing name.
		}

		return $config->name;
	}

	protected function get_stylesheet() {
		$name = $this->get_name();

		if ( ! $name ) {
			return false; //Missing name.
		}

		return $this->get_url() . '/css/' . $name . '.css';
	}
}
