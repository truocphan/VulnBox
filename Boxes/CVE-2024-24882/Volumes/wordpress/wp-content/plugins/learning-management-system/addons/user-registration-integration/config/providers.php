<?php
/**
 * Service providers for the addon.
 *
 * @since 1.7.1
 */

use Masteriyo\Addons\UserRegistrationIntegration\Providers\UserRegistrationIntegrationServiceProvider;

return array_unique(
	array(
		UserRegistrationIntegrationServiceProvider::class,
	)
);
