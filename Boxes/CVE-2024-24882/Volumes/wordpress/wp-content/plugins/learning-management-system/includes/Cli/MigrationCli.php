<?php
/**
 * CLI Migration.
 *
 * @since 1.3.4
 * @package Masteriyo\Cli
 */

namespace Masteriyo\Cli;

use Masteriyo\Database\Migrator;

class MigrationCli {

	/**
	 * WordPress direct filesystem.
	 *
	 * @since 1.3.4
	 *
	 * @var WP_Filesystem_Direct
	 */
	private $filesystem = null;

	/**
	 * Constructor.
	 *
	 * @since 1.3.4
	 */
	public function __construct() {
		global $wp_filesystem;

		$credentials = request_filesystem_credentials( '', 'direct' );

		if ( false === $credentials ) {
			\WP_CLI::error( esc_html__( 'Invalid filesystem credentials.', 'masteriyo' ) );
		}

		WP_Filesystem( $credentials );

		$this->filesystem = $wp_filesystem;
	}

	/**
	 * Create migration.
	 *
	 * @since 1.3.4
	 *
	 * @param Array $args Arguments in array format.
	 * @param Array $assoc_args Key value arguments stored in associated array format.
	 */
	public function create( $args, $assoc_args ) {
		try {
			$this->validate_create( $args, $assoc_args );
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

		$filename = masteriyo_strtolower( masteriyo_kebab_to_snake( $args[0] ) );
		$class    = masteriyo_snake_to_pascal( $filename );

		$template = dirname( __FILE__ ) . '/templates/migration';
		$contents = $this->filesystem->get_contents( $template );
		$contents = str_replace( '$class', $class, $contents );

		$filename    = gmdate( 'Y_m_d_His' ) . "_{$filename}.php";
		$destination = dirname( dirname( __DIR__ ) ) . '/database/migrations/';

		$this->filesystem->put_contents( $destination . $filename, $contents );

		\WP_CLI::success( sprintf( '%s is created successfully.', $filename ) );
	}

	/**
	 * Validate the migration creation.
	 *
	 * @since 1.3.4
	 *
	 * @param Array $args Arguments in array format.
	 * @param Array $assoc_args Key value arguments stored in associated array format.
	 */
	private function validate_create( $args, $assoc_args ) {
		if ( count( $args ) < 1 ) {
			throw new \Exception( esc_html__( 'Migration name is required.', 'masteriyo' ) );
		}
	}

	/**
	 * Migrate migrations.
	 *
	 * @since 1.3.4
	 *
	 * @param Array $args Arguments in array format.
	 * @param Array $assoc_args Key value arguments stored in associated array format.
	 */
	public function migrate( $args, $assoc_args ) {
		$name = isset( $assoc_args['name'] ) ? masteriyo_kebab_to_snake( $assoc_args['name'] ) : null;

		$migrations = masteriyo( 'migrator' )->migrate( $name );

		foreach ( $migrations as $migration ) {
			\WP_CLI::success(
				sprintf(
					// translators: Migration file name.
					esc_html__( '%s migrated successfully.', 'masteriyo' ),
					$migration
				)
			);
		}

		if ( empty( $migrations ) ) {
			\WP_CLI::success( esc_html__( 'Nothing to migrate.', 'masteriyo' ) );
		} else {
			\WP_CLI::success( esc_html__( 'Migrations ran successfully.', 'masteriyo' ) );
		}
	}

	/**
	 * Rollback migrations.
	 *
	 * @since 1.3.4
	 *
	 * @param Array $args Arguments in array format.
	 * @param Array $assoc_args Key value arguments stored in associated array format.
	 */
	public function rollback( $args, $assoc_args ) {
		$name = isset( $assoc_args['name'] ) ? masteriyo_kebab_to_snake( $assoc_args['name'] ) : null;
		$step = isset( $assoc_args['step'] ) ? absint( $assoc_args['step'] ) : 1;

		$migrations = masteriyo( 'migrator' )->rollback( $step, $name );

		foreach ( $migrations as $migration ) {
			\WP_CLI::success(
				sprintf(
					// translators: Migration file name.
					esc_html__( 'Successful %s rollback.', 'masteriyo' ),
					$migration
				)
			);
		}

		if ( empty( $migrations ) ) {
			\WP_CLI::success( esc_html__( 'Nothing to rollback.', 'masteriyo' ) );
		} else {
			\WP_CLI::success( esc_html__( 'Successful migrations rollback.', 'masteriyo' ) );
		}
	}

	/**
	 * Reset all the migrations.
	 *
	 * @since 1.3.4
	 *
	 * @param Array $args Arguments in array format.
	 * @param Array $assoc_args Key value arguments stored in associated array format.
	 */
	public function reset( $args, $assoc_args ) {
		masteriyo( 'migrator' )->reset();

		\WP_CLI::success( esc_html__( 'Migrations reset successfully.', 'masteriyo' ) );
	}
}
