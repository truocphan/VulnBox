<?php

namespace JupiterX_Core\Raven\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class Tabs extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'tabs';
	}

	public function get_fields() {
		return [ 'tab_title', 'tab_content' ];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'tab_title':
				return esc_html__( 'Raven Tabs: Tab title', 'jupiterx-core' );

			case 'tab_content':
				return esc_html__( 'Raven Tabs: Tab content', 'jupiterx-core' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'tab_title':
				return 'LINE';

			case 'tab_content':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
