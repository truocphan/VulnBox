<?php
/**
 * This handles customizer custom Container section.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Container class.
 *
 * @since 2.0.0
 * @ignore
 * @access public
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Section_Container extends WP_Customize_Section {

	/**
	 * Type of this section.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $type = 'kirki-container';

	/**
	 * Preview URL of container.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $preview;

	/**
	 * Order priority to load the control in Customizer.
	 *
	 * @since 2.0.0
	 *
	 * @var int
	 */
	public $priority = 160;

	/**
	 * Tabs for this section.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $tabs = [];

	/**
	 * Toggles (group of settings) for this section.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $boxes = [];

	/**
	 * Help link data for this section.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $help = [];

	/**
	 * Set visibility to hidden.
	 *
	 * If set to true, it will remove the accordion title inside the panel.
	 *
	 * @since 2.0.0
	 *
	 * @var boolean
	 */
	public $hidden = false;

	public $customizing_action = '';

	public $group = 'general';

	public $icon = '';

	public $front_icon = false;

	public $pro = false;

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since Next
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array                    = wp_array_slice_assoc( (array) $this, [ 'id', 'priority', 'panel', 'type' ] );
		$array['title']           = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content']         = $this->get_content();
		$array['active']          = $this->active();
		$array['instanceNumber']  = $this->instance_number;
		$array['tabs']            = $this->tabs;
		$array['boxes']           = $this->boxes;
		$array['hidden']          = $this->hidden;
		$array['preview']         = $this->preview;
		$array['help']            = $this->help;
		$array['group']           = $this->group;
		$array['icon']            = $this->icon;
		$array['front_icon']      = $this->front_icon;
		$array['pro']             = $this->pro;
		$array['customizeAction'] = $this->customizing_action ? $this->customizing_action : __( 'Customizing', 'jupiterx-core' );

		foreach ( $array['tabs'] as $key => $tab ) {
			// Transform label.
			if ( is_string( $tab ) ) {
				$array['tabs'][ $array['id'] . '-' . $key ] = [ 'label' => $tab ];
				unset( $array['tabs'][ $key ] );
			}

			if ( is_array( $tab ) ) {
				$array['tabs'][ $array['id'] . '-' . $key ] = [
					'label' => $tab['label'],
					'pro_tabs' => $tab['pro_tabs'],
				];
				unset( $array['tabs'][ $key ] );
			}
		}

		return $array;
	}

	/**
	 * An Underscore (JS) template for rendering this section.
	 *
	 * @since 2.0.0
	 */
	protected function render_template() {
		?>
		<li
		id="jupiterx-setting-container-{{ data.id }}"
		class="accordion-section control-section control-section-{{ data.type }}"
		data-group="{{ data.group }}">
			<h3 class="accordion-section-title" tabindex="0">
			<# if ( ! _.isEmpty( data.icon ) ) { #>
				<span class="accordion-section-title-icon">
					<svg><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-section.svg#{{ data.icon }}"></use></svg>
				</span>
			<# } #>
				{{ data.title }}
				<span class="screen-reader-text"><?php esc_html_e( 'Press return or enter to open this section', 'jupiterx-core' ); ?></span>

			<# if ( data.front_icon ) { #>
				<span class="child-box-probox-wrapper">
					<# if ( data.pro ) { #><svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-icons.svg#lock-badge"></use></svg><# } #>
				</span>
			<# } #>
			</h3>

			<ul class="accordion-section-content">
				<li class="jupiterx-customizer-section-title-wraper">
					<div class="customize-section-title">
						<button class="customize-section-back" tabindex="-1">
							<span class="screen-reader-text"><?php esc_html_e( 'Back', 'jupiterx-core' ); ?></span>
						</button>
						<h3>
							<span class="customize-action">
								{{{ data.customizeAction }}}
							</span>
							{{ data.title }}
						</h3>
					</div>
				</li>
				<?php $this->section_template(); ?>
				<li class="jupiterx-customize-help-link-wrapper">
					<?php if ( jupiterx_is_help_links() ) : ?>
					<# if ( ! _.isEmpty( data.help.url ) ) { #>
						<a href="{{ data.help.url }}" class="jupiterx-customizer-help-link jupiterx-icon-question" title="{{ data.help.title }}" target="_blank">
						<?php esc_html_e( 'Need Help', 'jupiterx-core' ); ?>
						</a>
					<# } #>
					<?php endif; ?>
				</li>
			</ul>
		</li>
		<?php
	}

	/**
	 * Template for content container.
	 *
	 * @since 2.0.0
	 */
	protected function section_template() {
		$this->tabs_template();
		$this->search_controls();
		?>
		<div class="customize-jupiterx-container-section jupiterx-container-section">
			<?php
			$this->boxes_template();
			?>
		</div>
		<?php
	}

	/**
	 * Content template for tabs.
	 *
	 * @since 2.0.0
	 */
	protected function tabs_template() {
		?>
		<# if ( ! _.isEmpty( data.tabs ) ) { #>
			<div class="jupiterx-container-tabs">
				<div class="jupiterx-container-tab-items">
					<# _.each( data.tabs, function( tab, tabId ) { #>
						<button
							class="jupiterx-container-tabs-button"
							tabindex="-1"
						>
						<span class="jupiterx-container-tabs-label">{{{ tab.label }}}</span>
						<# if ( tab.pro_tabs ) { #>
							<span class="child-box-probox-wrapper">
								<svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>"><use></svg>
							</span>
						<# } #>
						</button>
					<# }) #>
				</div>
			</div>
		<# } #>
		<?php
	}

	/**
	 * Serach controls.
	 *
	 * @since 2.0.0
	 */
	protected function search_controls() {
		?>
		<div class="jupiterx-customizer-search-wrapper">
			<svg><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-icons.svg#search"></use></svg>
			<input type="search" id="jupiterx-customizer-control-search" autocomplete="off" placeholder="<?php echo __( 'Search options & settings...', 'jupiterx-core' ); ?>">
		</div>
		<?php
	}

	/**
	 * Content template for toggle-able boxes.
	 *
	 * @since 2.0.0
	 */
	protected function boxes_template() {
		?>
		<# if ( ! _.isEmpty( data.boxes ) ) { #>
				<# _.each( data.boxes, function( box, boxId ) { #>
					<div class="jupiterx-child-box jupiterx-child-box-{{ box.tab || 'any' }} {{ 'empty_notice' === boxId ? 'open' : '' }}" id="box-{{ boxId }}-{{ data.id }}" data-tab="{{ data.id }}-{{ box.tab || 'any' }}" >
						<div class="jupiterx-child-box-header">
							<button class="jupiterx-child-box-toggle">
								<span class="toggle-icon dashicons {{ 'empty_notice' === boxId ? 'dashicons-arrow-down' : 'dashicons-arrow-right' }}"></span>
								<span class="screen-reader-text"><?php esc_html_e( 'Toggle', 'jupiterx-core' ); ?></span>
								<h3 class="jupiterx-child-box-title">{{ box.label }}</h3>
							</button>
						</div>
						<div class="jupiterx-child-box-content"></div>
					</div>
			<# } ) #>
		<# } #>
		<?php
	}
}
