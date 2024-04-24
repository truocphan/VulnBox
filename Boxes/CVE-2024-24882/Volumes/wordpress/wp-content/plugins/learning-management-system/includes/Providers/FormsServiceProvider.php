<?php
/**
 * Forms service provider.
 *
 * @since 1.5.41
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\FormHandler\AddToCartFormHandler;
use Masteriyo\FormHandler\ChangePasswordFormHandler;
use Masteriyo\FormHandler\CheckoutFormHandler;
use Masteriyo\FormHandler\InstructorRegistrationFormHandler;
use Masteriyo\FormHandler\PasswordResetFormHandler;
use Masteriyo\FormHandler\RegistrationFormHandler;
use Masteriyo\FormHandler\RequestPasswordResetFormHandler;

class FormsServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.5.41
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
	 * @since 1.5.41
	 */
	public function register() {
	}


	/**
	 * In much the same way, this method has access to the container
	 * itself and can interact with it however you wish, the difference
	 * is that the boot method is invoked as soon as you register
	 * the service provider with the container meaning that everything
	 * in this method is eagerly loaded.
	 *
	 * If you wish to apply inflectors or register further service providers
	 * from this one, it must be from a bootable service provider like
	 * this one, otherwise they will be ignored.
	 *
	 * @since 1.5.41
	 */
	public function boot() {
		foreach ( $this->get_form_handlers() as $form_handler ) {
			new $form_handler();
		}
	}

	/**
	 * Get form handlers list.
	 *
	 * @since 1.5.41
	 *
	 * @return array
	 */
	protected function get_form_handlers() {
		/**
		 * Filters form handler classes.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $form_handlers Form handler classes.
		 */
		return apply_filters(
			'masteriyo_form_handlers',
			array(
				RegistrationFormHandler::class,
				RequestPasswordResetFormHandler::class,
				PasswordResetFormHandler::class,
				ChangePasswordFormHandler::class,
				AddToCartFormHandler::class,
				CheckoutFormHandler::class,
				InstructorRegistrationFormHandler::class,
			)
		);
	}
}
