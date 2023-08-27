<?php
namespace JupiterX_Core\Raven\Modules\Flex_Spacer\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Flex_Spacer extends Base_Widget {

	public function get_name() {
		return 'raven-flex-spacer';
	}

	public function get_title() {
		return __( 'Flex Spacer', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-flex-spacer';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'message',
			[
				'type' => 'raw_html',
				'raw' => $this->get_content_message(),
			]
		);

		$this->end_controls_section();
	}


	protected function render() {
		?>
			<div class="raven-spacer">&nbsp;</div>
		<?php
	}

	private function get_content_message() {
		$message  = '<div class="elementor-panel-alert elementor-panel-alert-info">';
		$message .= __( 'The element is used to add automatic horizontal/vertical spacing between elements for Flex column.', 'jupiterx-core' );
		$message .= '</div>';

		return $message;
	}
}
