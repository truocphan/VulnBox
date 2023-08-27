<?php
namespace JupiterX_Core\Control_Panel_2\Custom_Icons\Icon_Sets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use JupiterX_Core\Control_Panel_2\Custom_Icons\Icon_Sets\Icon_Set_Base as Base;

class Fontastic extends Base {

	protected $data = '';

	protected $data_file = 'icons-reference.html';

	protected $stylesheet_file = 'styles.css';

	protected $allowed_zipped_files = [ 'icons-reference.html', 'styles.css', 'fonts/' ];

	protected $allowed_webfont_extensions = [ 'woff', 'ttf', 'svg', 'eot' ];

	protected function prepare() {
		$this->data     = $this->_unstable_file_get_contents( $this->directory . $this->stylesheet_file );
		$this->dir_name = $this->get_unique_name();
	}

	public function get_type() {
		return esc_html__( 'Fontastic', 'jupiterx-core' );
	}

	public function is_valid() {
		if ( ! file_exists( $this->directory . $this->data_file ) ) {
			return false; //Missing data file.
		}

		return true;
	}

	protected function extract_icon_list() {
		$pattern = '/\.' . $this->get_prefix() . '(.*)\:before\s\{/';
		preg_match_all( $pattern, $this->data, $icons_matches );

		if ( empty( $icons_matches[1] ) ) {
			return false; //Missing icons list.
		}

		$icons = [];
		foreach ( $icons_matches[1] as $icon ) {
			$icons[] = $icon;
		}

		return $icons;
	}

	protected function get_prefix() {
		static $set_prefix = null;

		if ( null === $set_prefix ) {
			$pattern = '/class\^="(.*)?"/';
			preg_match_all( $pattern, $this->data, $prefix );

			if ( ! isset( $prefix[1][0] ) ) {
				return false; //Missing css_prefix_text.
			}

			$set_prefix = $prefix[1][0];
		}

		return $set_prefix;
	}

	public function get_name() {
		static $set_name = null;

		if ( null === $set_name ) {
			$pattern = '/font-family: "(.*)"/';
			preg_match_all( $pattern, $this->data, $name );

			if ( ! isset( $name[1][0] ) ) {
				return false; //Missing name.
			}

			$set_name = $name[1][0];
		}

		return $set_name;
	}

	protected function get_stylesheet( $unique_name = '' ) {
		return $this->get_url() . '/' . $this->stylesheet_file;
	}
}
