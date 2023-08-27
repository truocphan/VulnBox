<?php
/**
 * The Jupiter X Elementor Finder component.
 *
 * @package JupiterX\Framework\API\Elementor
 *
 * @since   1.18.0
 */

/**
 * Extend Elementor Finder with new items.
 *
 * @since   1.18.0
 *
 * @package JupiterX\Framework\API\Elementor
 */
class JupiterX_Finder_Category extends \Elementor\Core\Common\Modules\Finder\Base_Category {

	/**
	 * Get title.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Jupiter X', 'jupiterx' );
	}

	/**
	 * Get category items.
	 *
	 * @access public
	 *
	 * @param array $options The options.
	 *
	 * @return array
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_category_items( array $options = [] ) {
		$items = [
			'registration' => [
				'title'    => esc_html__( 'Registration', 'jupiterx' ),
				'url'      => esc_url( admin_url( 'admin.php?page=jupiterx' ) ),
				'icon'     => 'dashboard',
				'keywords' => [ 'jupiter', 'jupiterx' ],
			],
			'plugins' => [
				'title'    => esc_html__( 'Plugins', 'jupiterx' ),
				'url'      => esc_url( admin_url( 'admin.php?page=jupiterx#install-plugins' ) ),
				'icon'     => 'plug',
				'keywords' => [ 'jupiter', 'jupiterx', 'install' ],
			],
			'templates' => [
				'title'    => esc_html__( 'Templates', 'jupiterx' ),
				'url'      => esc_url( admin_url( 'admin.php?page=jupiterx#install-templates' ) ),
				'icon'     => 'theme-style',
				'keywords' => [ 'jupiter', 'jupiterx', 'install' ],
			],
			'system-status' => [
				'title'    => esc_html__( 'System Status', 'jupiterx' ),
				'url'      => esc_url( admin_url( 'admin.php?page=jupiterx#system-status' ) ),
				'icon'     => 'info-circle-o',
				'keywords' => [ 'jupiter', 'jupiterx' ],
			],
			'updates' => [
				'title'    => esc_html__( 'Updates', 'jupiterx' ),
				'url'      => esc_url( admin_url( 'admin.php?page=jupiterx#update-theme' ) ),
				'icon'     => 'sync',
				'keywords' => [ 'jupiter', 'jupiterx', 'upgrade', 'release' ],
			],
			'settings' => [
				'title'    => esc_html__( 'Settings', 'jupiterx' ),
				'url'      => esc_url( admin_url( 'admin.php?page=jupiterx#settings' ) ),
				'icon'     => 'cogs',
				'keywords' => [ 'jupiter', 'jupiterx', 'options' ],
			],
			'support' => [
				'title'    => esc_html__( 'Support', 'jupiterx' ),
				'url'      => esc_url( 'https://themes.artbees.net/support/jupiterx/' ),
				'icon'     => 'help-o',
				'keywords' => [ 'jupiter', 'jupiterx', 'support', 'help', 'guide', 'knowledge base', 'docs', 'release notes', 'change log' ],
			],
			'getting-started' => [
				'title'    => esc_html__( 'Getting started with Jupiter X', 'jupiterx' ),
				'url'      => esc_url( 'https://themes.artbees.net/docs/getting-started-with-jupiter-x/' ),
				'icon'     => 'document-file',
				'keywords' => [ 'jupiter', 'jupiterx', 'support', 'help', 'guide', 'knowledge base', 'docs', 'templates', 'demo', 'import', 'register', 'download' ],
			],
			'using-theme' => [
				'title'    => esc_html__( 'Using Jupiter X theme', 'jupiterx' ),
				'url'      => esc_url( 'https://themes.artbees.net/docs/using-jupiter-x-theme/' ),
				'icon'     => 'document-file',
				'keywords' => [ 'jupiter', 'jupiterx', 'support', 'help', 'guide', 'knowledge base', 'docs', 'customizer', 'margin', 'padding', 'plugin', 'demo', 'style', 'header', 'footer', 'transparent', 'overlap', 'child theme', 'logo' ],
			],
		];

		return $items;
	}
}
