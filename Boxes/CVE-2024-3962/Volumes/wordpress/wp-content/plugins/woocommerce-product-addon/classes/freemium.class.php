<?php
/**
 * Class PPOM_Freemium
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PPOM_Freemium
 */
class PPOM_Freemium {
	const TAB_KEY_FREEMIUM_CFR = 'locked_conditional_field_repeater';

	public static $instance = null;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	private function __construct()
	{
		if( ppom_pro_is_installed() ) {
			return;
		}

		add_filter( 'ppom_fields_tabs_show', array( $this, 'add_locked_cfr_tab' ), 10, 1 );
		add_filter( 'ppom_all_inputs', array( $this, 'locked_cfr_register_form_elements' ), PHP_INT_MAX );
	}

	/**
	 * Get instance
	 *
	 * @return void
	 */
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add a tab to all PPOM field types as Conditional Repeater (PRO)
	 *
	 * @param  array $tabs Current tabs.
	 * @return array
	 */
	public function add_locked_cfr_tab( $tabs ) {
		$tabs[self::TAB_KEY_FREEMIUM_CFR] = array(
			'label' => __( 'Conditional Repeater (PRO)', 'woocommerce-product-addon' ),
			'class' => array( 'ppom-tabs-label' ),
			'field_depend' => array( 'all' )
		);
		return $tabs;
	}

	/**
	 * HTML content of the freemium Conditional Field Repeater
	 *
	 * @return string
	 */
	public function get_freemium_cfr_content() {
		ob_start();
		$upgrade_url = tsdk_utmify( 'https://themeisle.com/plugins/ppom-pro/upgrade/', 'lockedconditionalfield', 'ppompage' );
		?>
		<div class="freemium-cfr-content">
			<p><?php esc_html_e( 'Conditional Field Repeater allows repeating this field across a value of another PPOM field. Conditional Field Repeater feature is the part of the PPOM Pro.', 'woocommerce-product-addon' ); ?></p>

			<p><?php printf( '<strong>%s</strong> %s', esc_html__( 'Use case example:', 'woocommerce-product-addon' ),  esc_html__( 'Get the number of players from another PPOM field, and repeat this field(let\'s say that\'s a text field representing the player name) across the number of players. That\'s pretty useful for dynamic repeating the PPOM field.', 'woocommerce-product-addon' ) ); ?></p>

			<a target="_blank" class="btn btn-sm btn-primary" href="<?php echo esc_url( $upgrade_url ); ?>"><?php echo __( 'Upgrade to Pro', 'woocommerce-product-addon' ); ?></a>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
     * Adds admin setting fields to all input types.
     *
     * @param  array $inputs current input classes
     * @return array
     */
    public function locked_cfr_register_form_elements( $inputs ) {
        return array_map( function($input_class) {
            if( ! is_object( $input_class ) || ! property_exists( $input_class, 'settings' ) || !is_array($input_class->settings) ) {
                return $input_class;
            }

            $input_class->settings['locked_cfr'] = array(
                'type' => 'checkbox',
                'title' => __( 'Enable Conditional Repeat', 'woocommerce-product-addon' ),
				'disabled' => true,
                'desc' => __( 'This control turns on the Conditional Field Repeater mode for this field, in this way, this field is repeated by the selected field(selected in the Origin setting) below', 'woocommerce-product-addon' ),
				'tabs_class' => array( 'ppom_handle_' . self::TAB_KEY_FREEMIUM_CFR )
            );

            return $input_class;
        }, $inputs );
    }

	public function get_pro_fields() {
		return [
			[
				'title' => __( 'Collapse', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-money" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Emojis', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-user-plus" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Phone Input', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-check" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Chained Input', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-check" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Conditional Images', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-picture-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Domain', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-server" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Fonts Picker', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-font" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Texter', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-keyboard-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Text Counter', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-comments-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Fixed Price', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-money" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Select Option Quantity', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-list-ol" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Image DropDown', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-file-image-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Super List', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-check" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Quantity Option', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-money" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Quantities Pack', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-list-alt" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Radio Switcher', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Variation Matrix', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-list-alt" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'DateRange Input', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-table" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Color picker', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-modx" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'File Input', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-file" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Image Cropper', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-crop" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Timezone Input', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-clock-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Variation Quantity', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-list-ol" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Images', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-picture-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Price Matrix', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-usd" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'HTML', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-code" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Color Palettes', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-user-plus" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Audio / Video', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-file-video-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Measure Input', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-building-o" aria-hidden="true"></i>',
			],
			[
				'title' => __( 'Divider', 'woocommerce-product-addon' ),
				'icon'  => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
			],
		];
	}
}
