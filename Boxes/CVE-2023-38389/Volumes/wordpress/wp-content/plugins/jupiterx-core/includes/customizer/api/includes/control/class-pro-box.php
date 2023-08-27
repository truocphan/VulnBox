<?php
/**
 * PRO Box UI control.
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
 * PRO Box control class.
 *
 * @since 1.3.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_PRO_Box extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-pro-box';

	/**
	 * Control's title.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * Control's description.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.3.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['title']       = $this->title;
		$this->json['description'] = $this->description;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @since 1.3.0
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function content_template() {
		if ( jupiterx_is_premium() ) {
			$title       = __( 'Activate Jupiter X', 'jupiterx-core' );
			$description = __( 'To unlock this feature you must activate Jupiter X', 'jupiterx-core' );
		} else {
			$title       = __( 'Upgrade to unlock this feature', 'jupiterx-core' );
			$description = __( 'You can unlock more customization options.', 'jupiterx-core' );
		}
		?>
		<div class="jupiterx-control jupiterx-pro-box-control">
			<# title = data.title || '<?php echo esc_html( $title ); ?>' #>
			<# description = data.description || '<?php echo esc_html( $description ); ?>' #>
			<?php if ( jupiterx_is_premium() ) : ?>
				<img class="jupiterx-control-pro-badge" src="<?php echo esc_url( JUPITERX_ADMIN_ASSETS_URL . 'images/lock-badge-dark.svg' ); ?>">
			<?php else : ?>
				<span class="jupiterx-icon-pro"></span>
			<?php endif; ?>
			<div class="jupiterx-pro-box-control-title">{{ title }}</div>
			<div class="jupiterx-pro-box-control-description">{{ description }}</div>
			<?php if ( jupiterx_is_premium() ) : ?>
				<a class="jupiterx-pro-box-control-button jupiterx-upgrade-modal-trigger" href="#" data-upgrade-link="<?php echo esc_attr( jupiterx_upgrade_link( 'customizer' ) ); ?>">
					<?php esc_html_e( 'Activate Now', 'jupiterx-core' ); ?>
				</a>
			<?php else : ?>
				<a class="jupiterx-pro-box-control-button" href="<?php echo esc_attr( jupiterx_upgrade_link( 'customizer' ) ); ?>" target="_blank">
					<?php esc_html_e( 'Upgrade to Jupiter X Pro', 'jupiterx-core' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
