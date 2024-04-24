<?php
/**
 * Handles cli command initialization.
 *
 * @since 1.3.1
 * @package Masteriyo\Cli
 */

namespace Masteriyo\Cli;

class Cli {
	/**
	 * Register CLI commands.
	 *
	 * @since 1.3.1
	 */
	public static function register() {
		/**
		 * Filters CLI commands.
		 *
		 * @since 1.3.1
		 *
		 * @param array $commands Command to command handler class index.
		 */
		$commands = apply_filters(
			'masteriyo_cli_commands',
			array(
				'migration'      => MigrationCli::class,
				'usage-tracking' => UsageTrackingCli::class,
			)
		);

		foreach ( $commands as $command => $class ) {
			\WP_CLI::add_command( "masteriyo {$command}", $class );
		}
	}
}
