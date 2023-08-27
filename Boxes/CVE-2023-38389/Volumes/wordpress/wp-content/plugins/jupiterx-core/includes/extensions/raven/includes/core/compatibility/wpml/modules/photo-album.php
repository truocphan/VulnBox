<?php

namespace JupiterX_Core\Raven\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class Photo_Album extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'list';
	}

	public function get_fields() {
		return [ 'title', 'description' ];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return esc_html__( 'Raven Form: Photo Album item title', 'jupiterx-core' );

			case 'description':
				return esc_html__( 'Raven Form: Photo Album item description', 'jupiterx-core' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';

			case 'description':
				return 'AREA';

			default:
				return '';
		}
	}

}
