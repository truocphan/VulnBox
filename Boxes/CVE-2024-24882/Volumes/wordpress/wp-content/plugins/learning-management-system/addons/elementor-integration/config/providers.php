<?php
/**
 * Masteriyo elementor integration service providers.
 *
 * @since 1.6.12
 */

use Masteriyo\Addons\ElementorIntegration\Providers\ElementorIntegrationServiceProvider;

return array_unique(
	array(
		ElementorIntegrationServiceProvider::class,
	)
);
