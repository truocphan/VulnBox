<?php
/**
 * This handles customizer custom link section.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Link class.
 *
 * This is a special section for rendering section as a static link.
 *
 * @since 1.3.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Section_Link extends WP_Customize_Section {

	/**
	 * Type of this section.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $type = 'kirki-jupiterx-link';

	/**
	 * Url of this section.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $jupiterx_url = '#';

	/**
	 * Icon of this section.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $jupiterx_icon = '';

	/**
	 * Upgrade link.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $upgrade_link = '';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 1.3.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$json                  = parent::json();
		$json['jupiterx_url']  = esc_url( $this->jupiterx_url );
		$json['jupiterx_icon'] = $this->jupiterx_icon;
		$json['upgradeLink']   = $this->upgrade_link;

		return $json;
	}

	/**
	 * An Underscore (JS) template for rendering this section.
	 *
	 * @since 1.3.0
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function render_template() {
		?>
		<li id="jupiterx-popup-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
			<?php if ( jupiterx_is_premium() ) : ?>
				<a href="{{ data.jupiterx_url }}" class="accordion-section-title jupiterx-upgrade-modal-trigger" tabindex="0" target="_blank" <# if ( data.upgradeLink ) { #> data-upgrade-link="{{ data.upgradeLink }}" <# } #>>
			<?php else : ?>
				<a href="{{ data.upgradeLink }}" class="accordion-section-title" tabindex="0" target="_blank">
			<?php endif; ?>
					<# if ( data.jupiterx_icon ) { #>
					<span class="{{ data.jupiterx_icon }}"></span>
					<# } #>
					{{ data.title }}
					<span class="screen-reader-text"><?php esc_html_e( 'Press return or enter to open this section', 'jupiterx-core' ); ?></span>
				</a>
		</li>
		<?php
	}
}
