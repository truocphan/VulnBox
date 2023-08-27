<?php
namespace JupiterX_Core\Raven\Modules\Global_Widget\Documents;

use JupiterX_Core\Raven\Modules\Global_Widget\Module;
use Elementor\Core\Base\Document;
use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\User;

defined( 'ABSPATH' ) || die();

class Widget extends Library_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = 'library';
		$properties['show_in_library'] = false;
		$properties['is_editable']     = false;
		$properties['show_in_finder']  = false;

		return $properties;
	}

	public function get_name() {
		return 'widget';
	}

	public static function get_title() {
		return esc_html__( 'Global Widget', 'jupiterx-core' );
	}

	public static function get_plural_title() {
		return esc_html__( 'Global Widgets', 'jupiterx-core' );
	}

	public function is_editable_by_current_user() {
		return User::is_current_user_can_edit( $this->get_main_id() );
	}

	public function import( array $data ) {
		parent::import( $data );
		$this->update_main_meta( Module::WIDGET_TYPE_META_KEY, $data['content'][0]['widgetType'] );
	}

	public function save( $data ) {
		$data['settings'] = [ 'post_status' => Document::STATUS_PUBLISH ];

		return parent::save( $data );
	}
}
