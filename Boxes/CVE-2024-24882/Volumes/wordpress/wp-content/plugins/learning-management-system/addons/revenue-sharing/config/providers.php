<?php
/**
 * Revenue sharing config.
 *
 * @since 1.6.14
 */

use Masteriyo\Addons\RevenueSharing\Providers\EarningServiceProvider;
use Masteriyo\Addons\RevenueSharing\Providers\RevenueSharingServiceProvider;
use Masteriyo\Addons\RevenueSharing\Providers\WithdrawServiceProvider;

/**
 * Masteriyo revenue sharing service providers.
 *
 * @since 1.6.14
 */
return array_unique(
	array(
		RevenueSharingServiceProvider::class,
		EarningServiceProvider::class,
		WithdrawServiceProvider::class,
	)
);
