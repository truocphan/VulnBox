<?php
/**
 * Deactivation feedback service provider.
 *
 * @package Masteriyo\Providers
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Service provider for job-related services.
 *
 * @since 1.6.0
 */
class DeactivationFeedbackServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.6.0
	 *
	 * @var array
	 */
	protected $provides = array();

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.6.0
	 */
	public function register() {
		// Register any services or dependencies here.
	}

	/**
	 * Bootstraps the application by scheduling a recurring action and registering the job.
	 *
	 * This method is called after all service providers are registered.
	 *
	 * @since 1.6.0
	 */
	public function boot() {
		add_action( 'admin_footer', array( $this, 'feedback_html' ) );
	}

	/**
	 * Deactivation Feedback HTML.
	 *
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function feedback_html() {
		if ( ! $this->is_plugins_screen() ) {
			return;
		}

		$deactivate_reasons = array(
			'feature_unavailable'     => array(
				'title'    => esc_html__( 'I didn’t find the feature I was looking for', 'masteriyo' ),
				'link'     => sprintf(
					/* translators: %s: feedback and feature requests link */
					esc_html__( 'Your input is valuable and will be considered for future releases. To request new features for the Masteriyo plugin, please visit this %s.', 'masteriyo' ),
					'<a href="' . esc_url_raw( 'https://masteriyo.feedbear.com/boards/feature-requests' ) . '" target="_blank">' . esc_html__( 'link', 'masteriyo' ) . '</a>'
				),
				'is_input' => false,
			),
			'complex_to_use'          => array(
				'title'    => esc_html__( 'I found the plugin complex to use', 'masteriyo' ),
				'link'     => sprintf(
					/* translators: %s: documentation link */
					esc_html__( 'For plugin complexity, refer to the %s or use live chat at the bottom right corner of our documentation site for immediate assistance.', 'masteriyo' ),
					'<a href="' . esc_url_raw( 'https://docs.masteriyo.com/' ) . '" target="_blank">' . esc_html__( 'documentation', 'masteriyo' ) . '</a>'
				),
				'is_input' => false,
			),
			'found_a_better_plugin'   => array(
				'title'             => esc_html__( 'I found better alternative', 'masteriyo' ),
				'input_placeholder' => esc_html__( 'If possible, please mention the alternatives', 'masteriyo' ),
				'is_input'          => true,
			),
			'temporary_deactivation'  => array(
				'title'             => esc_html__( 'Temporary deactivation', 'masteriyo' ),
				'is_input'          => true,
				'input_placeholder' => '',
			),
			'no_longer_needed'        => array(
				'title'             => esc_html__( 'I no longer need the plugin', 'masteriyo' ),
				'is_input'          => true,
				'input_placeholder' => '',
			),
			'found_bug_in_the_plugin' => array(
				'title'             => esc_html__( 'Found a bug in the plugin?', 'masteriyo' ),
				'input_placeholder' => esc_html__( 'If possible, please elaborate on this', 'masteriyo' ),
				'is_input'          => true,
			),
		);

		masteriyo_get_template( 'deactivation/deactivation-feedback.php', array( 'deactivate_reasons' => $deactivate_reasons ) );
	}

	/**
	 * Check if the current screen is the plugins screen and returns a boolean.
	 *
	 * @since 1.6.0
	 *
	 * @return boolean
	 */
	private function is_plugins_screen() {
		if ( ! is_callable( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();

		return $screen && in_array( $screen->id, array( 'plugins', 'plugins-network' ), true );
	}
}
