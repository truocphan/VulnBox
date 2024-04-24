<?php
/**
 * Service providers for the addon.
 *
 * @since 1.6.15
 */

use Masteriyo\Addons\GamiPressIntegration\Providers\GamiPressIntegrationServiceProvider;

return array_unique(
	array(
		GamiPressIntegrationServiceProvider::class,
	)
);
