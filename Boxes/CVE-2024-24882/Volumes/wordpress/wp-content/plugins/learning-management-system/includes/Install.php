<?php
/**
 * Install
 *
 * @since 1.0.0
 */

namespace Masteriyo;

use Masteriyo\Taxonomy\Taxonomy;

class Install {

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		self::create_difficulties();
		self::install();
	}

	/**
	 * Update Masteriyo information.
	 *
	 * @since 1.0.0
	 */
	public static function install() {
		$masteriyo_version = get_option( 'masteriyo_plugin_version' );

		if ( empty( $masteriyo_version ) ) {
			/**
			 * Filters boolean value to enable/disable setup wizard. True for enable.
			 *
			 * @since 1.0.0
			 *
			 * @param boolean $enable True to enable setup wizard.
			 */
			$enable_setup_wizard = apply_filters( 'masteriyo_enable_setup_wizard', true );

			if ( empty( $masteriyo_version ) && $enable_setup_wizard ) {
				set_transient( '_masteriyo_activation_redirect', 1, 30 );
			}
		}
		update_option( 'masteriyo_plugin_version', MASTERIYO_VERSION );

		// Save the install date.
		if ( false === get_option( 'masteriyo_install_date' ) ) {
			update_option( 'masteriyo_install_date', current_time( 'mysql', true ) );
		}

		flush_rewrite_rules();
	}

	/**
	 * Remove previous roles.
	 *
	 * @since 1.3.0
	 * @since 1.5.37 Moved to Roles class.
	 *
	 * @deprecated 1.5.37
	 */
	public static function remove_roles() {
		// Remove the masteriyo manager role for now.
		remove_role( 'masteriyo_manager' );

		foreach ( Roles::get_all() as $role_slug => $role ) {
			remove_role( $role_slug );
		}
	}

	/**
	 * Create roles.
	 *
	 * @since 1.0.0
	 * @since 1.5.37 Move to Activation class.
	 *
	 * @deprecated 1.5.37
	 */
	private static function create_roles() {
		foreach ( Roles::get_all() as $role_slug => $role ) {
			add_role( $role_slug, $role['display_name'], $role['capabilities'] );
		}
	}

	/**
	 * Create default difficulties.
	 *
	 * @since 1.0.0
	 */
	public static function create_difficulties() {

		$difficulty_count = wp_count_terms(
			array(
				'taxonomy'   => Taxonomy::COURSE_DIFFICULTY,
				'hide_empty' => false,
			)
		);

		if ( $difficulty_count > 0 ) {
			return;
		}

		$terms = array(
			'beginner'     => esc_html__( 'Beginner', 'masteriyo' ),
			'intermediate' => esc_html__( 'Intermediate', 'masteriyo' ),
			'expert'       => esc_html__( 'Expert', 'masteriyo' ),
		);

		foreach ( $terms as $slug => $name ) {
			$term = get_term_by( 'slug', $slug, 'course_difficulty' );

			if ( false === $term ) {
				wp_insert_term( $name, 'course_difficulty' );
			}
		}
	}

	/**
	 * Return a list of Masteriyo tables.
	 *
	 * @since 1.5.20
	 *
	 * @return string[]
	 */
	public static function get_tables() {
		global $wpdb;

		$tables = array(
			"{$wpdb->prefix}masteriyo_notifications",
			"{$wpdb->prefix}masteriyo_user_items",
			"{$wpdb->prefix}masteriyo_user_itemmeta",
			"{$wpdb->prefix}masteriyo_user_activities",
			"{$wpdb->prefix}masteriyo_user_activitymeta",
			"{$wpdb->prefix}masteriyo_sessions",
			"{$wpdb->prefix}masteriyo_order_items",
			"{$wpdb->prefix}masteriyo_order_itemmeta",
			"{$wpdb->prefix}masteriyo_quiz_attempts",
			"{$wpdb->prefix}masteriyo_migrations",
		);

		/**
		 * Filter the list of known Masteriyo tables.
		 *
		 * @since 1.5.20
		 *
		 * @param array $tables An array of Masteriyo-specific database table names.
		 */
		$tables = apply_filters( 'masteriyo_get_tables', $tables );

		return $tables;
	}
}
