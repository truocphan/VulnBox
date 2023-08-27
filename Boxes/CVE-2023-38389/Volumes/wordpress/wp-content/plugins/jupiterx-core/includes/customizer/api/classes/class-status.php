<?php
/**
 * This class handles status debug utils for customizer.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Run status debug.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Customizer_Status {

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'render_settings_table' ] );
	}

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function render_settings_table() {
		$exclude_type = [ 'jupiterx-divider', 'jupiterx-label', 'jupiterx-child-popup' ];

		?>
		<table class="table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Customizer Settings Name', 'jupiterx-core' ); ?></th>
					<th><?php esc_html_e( 'Control Type', 'jupiterx-core' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( JupiterX_Customizer::$settings as $id => $setting ) : ?>
					<?php if ( ! in_array( $setting['type'], $exclude_type, true ) ) : ?>
						<tr>
							<td><?php echo esc_html( $id ); // @codingStandardsIgnoreLine ?></td>
							<td><?php echo esc_html( $setting['type'] ); // @codingStandardsIgnoreLine ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}
}
