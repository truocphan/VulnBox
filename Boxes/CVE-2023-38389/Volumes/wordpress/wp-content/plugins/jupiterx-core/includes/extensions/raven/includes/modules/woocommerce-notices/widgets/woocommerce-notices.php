<?php
namespace JupiterX_Core\Raven\Modules\WooCommerce_Notices\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Woocommerce_Notices extends Base_Widget {

	public function get_name() {
		return 'raven-woocommerce-notices';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce Notices', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-woocommerce-notice';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section',
			[
				'label' => esc_html__( 'WooCommerce Notices', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'where_to_appear_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Drop this widget anywhere on the page or template where you want notices to appear.', 'jupiterx-core' ),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_control(
			'site_settings_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					/* translators: 1: Link opening tag, 2: Link closing tag. */
					esc_html__( 'To change the design of your notices, go to your %1$sWooCommerce Settings%2$s', 'jupiterx-core' ),
					'<a href="#" onclick="elementor.channels.editor.trigger(`jupiterXGoToWooCommerceSettings`);">',
					'</a>'
				),
				'content_classes' => 'elementor-descriptor elementor-descriptor-subtle',
			]
		);

		$this->add_control(
			'one_per_page_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					/* translators: 1: Bold text opening tag, 2: Bold text closing tag. */
					esc_html__( '%1$sNote:%2$s You can only add the Notices widget once per page.', 'jupiterx-core' ),
					'<strong>',
					'</strong>'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode() ) {
			?>
			<div class="woocommerce-info jupiterx-demo-woocommerce-notices">
				<?php echo esc_html__( 'This is an example of a WooCommerce notice. (You won\'t see this while previewing your site.)', 'jupiterx-core' ); ?>
			</div>
			<?php
		} else {
			?>
			<div class="jupiterx-woocommerce-notices-wrapper jupiterx-woocommerce-notices-wrapper-loading">
				<?php woocommerce_output_all_notices(); ?>
			</div>
			<?php
		}
	}
}
