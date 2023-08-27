<?php
namespace JupiterX_Core\Raven\Modules\My_Account\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\My_Account\Submodules\Tab_Content;
use JupiterX_Core\Raven\Modules\My_Account\Submodules\Tab_Style;
use JupiterX_Core\Raven\Modules\My_Account\Submodules\Editor;
use JupiterX_Core\Raven\Modules\My_Account\Submodules\Frontend;

class My_Account extends Base_Widget {

	public function get_name() {
		return 'raven-my-account';
	}

	public function get_title() {
		return esc_html__( 'My Account', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-my-account';
	}

	protected function register_controls() {
		Tab_Content::add_section_content( $this );
		Tab_Content::add_section_tabs( $this );

		Tab_Style::add_section_tabs( $this );
		Tab_Style::add_section_tabs_icon( $this );
		Tab_Style::add_section_sections( $this );
		Tab_Style::add_section_typography( $this );
		Tab_Style::add_section_forms( $this );
		Tab_Style::add_section_tables( $this );
	}

	protected function render() {
		$is_edit = \Elementor\Plugin::$instance->editor->is_edit_mode();

		if ( $is_edit ) {
			( new Editor( $this ) )->render_editor();
			return;
		}

		( new Frontend( $this ) )->render_frontend();
	}
}
