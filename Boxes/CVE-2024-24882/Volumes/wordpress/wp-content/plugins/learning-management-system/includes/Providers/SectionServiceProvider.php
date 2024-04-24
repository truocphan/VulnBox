<?php
/**
 * Section model service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Models\Section;
use Masteriyo\Repository\SectionRepository;
use Masteriyo\RestApi\Controllers\Version1\SectionsController;

class SectionServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $provides = array(
		'section',
		'section.store',
		'section.rest',
		'mto-section',
		'mto-section.store',
		'mto-section.rest',
		'\Masteriyo\RestApi\Controllers\Version1\SectionsController',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->getContainer()->add( 'section.store', SectionRepository::class );

		$this->getContainer()->add( 'section.rest', SectionsController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\SectionsController' )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'section', Section::class )
			->addArgument( 'section.store' );

		// Register based on post type.
		$this->getContainer()->add( 'mto-section', Section::class )
			->addArgument( 'section.store' );

		$this->getContainer()->add( 'mto-section.store', SectionRepository::class );

		$this->getContainer()->add( 'mto-section.rest', SectionsController::class )
				->addArgument( 'permission' );

	}
}
