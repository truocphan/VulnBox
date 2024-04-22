<?php

namespace MasterStudy\Lms;

use MasterStudy\Lms\Plugin\Addon;
use MasterStudy\Lms\Plugin\Addons;
use MasterStudy\Lms\Plugin\Taxonomy;
use MasterStudy\Lms\Rest\SearchHandlers\CourseSearchHandler;
use MasterStudy\Lms\Rest\SearchHandlers\QuestionsSearchHandler;
use MasterStudy\Lms\Routing\Router;
use STM_LMS_Options;
use WP_REST_Server;

final class Plugin {

	public const TRANSLATION_DOMAIN = 'masterstudy-lms-learning-management-system';

	private Router $router;
	private array $enabled_addons;

	public function __construct( Router $router ) {
		$this->enabled_addons = Addons::enabled_addons();
		$this->router         = $router;
	}

	public function init(): void {
		$this->register_taxonomies();
	}

	public function load_file( string $file ): void {
		( function ( $plugin ) use ( $file ) {
			require $file;
		} )( $this );
	}

	public function register_api( WP_REST_Server $server ): void {
		$namespace = trim( $this->router->get_prefix(), '/' );

		foreach ( $this->router->get_routes() as $route ) {
			$server->register_route(
				$namespace,
				$route->wp_uri,
				array(
					array_merge(
						array(
							'methods'                     => $route->methods,
							'callback'                    => array( $this->router, 'handle' ),
							'permission_callback'         => '__return_true',
							$this->router::ROUTE_NAME_KEY => $route->get_name(),
						),
						$route->get_doc()
					),
				)
			);
		}
	}

	public function register_search_handlers( array $handlers ): array {
		$handlers[] = new CourseSearchHandler();
		$handlers[] = new QuestionsSearchHandler();
		return $handlers;
	}

	/**
	 * @param array<Addon> $addons
	 */
	public function register_addons( array $addons ): void {
		foreach ( $addons as $addon ) {
			if ( $addon instanceof Addon && ( $this->enabled_addons[ $addon->get_name() ] ?? false ) ) {
				$addon->register( $this );
			}
		}
	}

	public function get_router(): Router {
		return $this->router;
	}

	private function register_taxonomies(): void {
		$course_category_slug = STM_LMS_Options::get_option(
			'courses_categories_slug',
			Taxonomy::COURSE_CATEGORY_DEFAULT_SLUG
		);

		$taxonomies = apply_filters( 'stm_lms_taxonomies', Taxonomy::defaults( $course_category_slug ) );

		foreach ( $taxonomies as $taxonomy => $taxonomy_args ) {
			register_taxonomy( $taxonomy, $taxonomy_args['post_type'], $taxonomy_args['args'] );
		}
	}
}
