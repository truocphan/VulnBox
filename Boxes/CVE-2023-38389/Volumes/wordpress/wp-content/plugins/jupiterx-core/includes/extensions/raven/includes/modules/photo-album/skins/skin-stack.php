<?php
namespace JupiterX_Core\Raven\Modules\Photo_Album\Skins;

use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Skin_Stack extends Skin_Base {
	public function get_id() {
		return 'stack';
	}

	public function get_title() {
		return __( 'Stack', 'jupiterx-core' );
	}

	protected function add_skin_hover_effect() {
		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'Hamal',
				/**
				 * Key are the original function names.
				 * Labels can be any custom name.
				 */
				'options' => [
					'Hamal' => __( 'Bunch', 'jupiterx-core' ),
					'Polaris' => __( 'Stack', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);
	}

	protected function render_skin_image( $settings ) {
		$stack_color = $this->item['stack_color'];

		?>
		<div class="raven-stack">
			<div class="raven-stack-deco" style="background-color: <?php echo $stack_color; ?>"></div>
			<div class="raven-stack-deco" style="background-color: <?php echo $stack_color; ?>"></div>
			<div class="raven-stack-deco" style="background-color: <?php echo $stack_color; ?>"></div>
			<div class="raven-stack-deco" style="background-color: <?php echo $stack_color; ?>"></div>
			<div class="raven-stack-figure">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
			</div>
		</div>
		<?php
	}
}
