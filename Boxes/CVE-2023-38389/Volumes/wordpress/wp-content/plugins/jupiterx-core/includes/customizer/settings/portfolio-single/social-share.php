<?php
/**
 * Add Jupiter settings for Portfolio Single > Styles > Featured Image tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_portfolio_single_social_share_pro_box',
	'section'  => 'jupiterx_portfolio_pages',
	'box'      => 'social_share',
] );
