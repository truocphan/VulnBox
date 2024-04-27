<?php
/*
 * working behind the seen
 */

/*
**========== Direct access not allowed ============
*/
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Not Allowed' );
}

class NM_PersonalizedProduct_Admin extends NM_PersonalizedProduct {

	var $menu_pages, $plugin_scripts_admin, $plugin_settings;

	/**
	 * Plugin meta data.
	 *
	 * @var string $plugin_meta
	 */
	public $plugin_meta = array();

	function __construct() {

		// setting plugin meta saved in config.php
		$this->plugin_meta = ppom_get_plugin_meta();

		// getting saved settings
		$this->plugin_settings = get_option( $this->plugin_meta['shortname'] . '_settings' );

		// populating $inputs with NM_Inputs object
		// $this -> inputs = self::get_all_inputs ();

		/*
		 * [1] TODO: change this for plugin admin pages
		*/
		$this->menu_pages = array(
			array(
				'page_title'  => __( 'PPOM', 'woocommerce-product-addon' ),
				'menu_title'  => __( 'PPOM', 'woocommerce-product-addon' ),
				'cap'         => 'manage_options',
				'slug'        => 'ppom',
				'callback'    => 'product_meta',
				'parent_slug' => 'woocommerce',
			),
		);


		add_action(
			'admin_menu',
			array(
				$this,
				'add_menu_pages',
			)
		);

		add_action( 'init', array( 'NM_PersonalizedProduct', 'set_ppom_menu_permission' ) );

		// Getting products list
		add_action( 'wp_ajax_ppom_get_products', array( $this, 'get_products' ) );
		add_action( 'wp_ajax_ppom_attach_ppoms', array( $this, 'ppom_attach_ppoms' ) );

		// Adding setting tab in WooCommerce
		if ( ! ppom_settings_migrated() ) {
			add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
			// Display settings
			add_action( 'woocommerce_settings_tabs_ppom_settings', array( $this, 'settings_tab' ) );
			// Save settings
			add_action( 'woocommerce_update_options_ppom_settings', array( $this, 'save_settings' ) );
		}

		// adding wpml support for PPOM Settings
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'ppom_setting_wpml' ), 10, 3 );
		add_action( 'ppom_pdf_setting_action', 'ppom_admin_update_pro_notice', 10 );

		add_action( 'admin_head', array( $this, 'ppom_tabs_custom_style' ) );

		add_action(
			'woocommerce_admin_field_ppom_multi_select',
			array(
				$this,
				'ppom_multi_select_role_setting',
			),
			2,
			10
		);

	}


	/*
	 * creating menu page for this plugin
	*/
	function add_menu_pages() {

		if ( ! $this->menu_pages ) {
			return '';
		}

		foreach ( $this->menu_pages as $page ) {

			$cap = apply_filters( 'ppom_menu_capability', $page ['cap'] );

			if ( $page ['parent_slug'] == '' ) {

				$menu = add_options_page(
					__( 'PPOM Fields', 'woocommerce-product-addon' ),
					__( 'PPOM Fields', 'woocommerce-product-addon' ),
					$cap,
					$page ['slug'],
					array(
						$this,
						$page ['callback'],
					),
					$this->plugin_meta ['logo'],
					$this->plugin_meta ['menu_position']
				);
			} else {

				$menu = add_submenu_page(
					$page ['parent_slug'],
					__( $page ['page_title'], 'woocommerce-product-addon' ),
					__( 'PPOM Fields', 'woocommerce-product-addon' ),
					$cap,
					$page ['slug'],
					array(
						$this,
						$page ['callback'],
					)
				);
			}

			if ( ! current_user_can( 'administrator' ) ) {
				$cap = 'ppom_options_page';
				// Menu page for roles set by PPOM Permission Settings
				add_menu_page(
					__( $page ['page_title'], 'woocommerce-product-addon' ),
					__( 'PPOM Fields', 'woocommerce-product-addon' ),
					$cap,
					$page ['slug'],
					array(
						$this,
						$page ['callback'],
					)
				);
			}
		}
	}


	/*
	 * CALLBACKS
	*/
	function product_meta() {

		echo '<div id="ppom-pre-loading"></div>';

		echo '<div class="ppom-admin-wrap woocommerce ppom-wrapper" style="display:none">';

		$action  = ( isset( $_REQUEST ['action'] ) ? sanitize_text_field( $_REQUEST ['action'] ) : '' );
		$do_meta = ( isset( $_REQUEST ['do_meta'] ) ? sanitize_text_field( $_REQUEST ['do_meta'] ) : '' );
		$view    = ( isset( $_REQUEST ['view'] ) ? sanitize_text_field( $_REQUEST ['view'] ) : '' );
		$ppom_settings_url = admin_url( "admin.php?page=wc-settings&tab=ppom_settings" );
		$addons           = add_query_arg( array( 'view' => 'addons' ) );
		$changelog_url           = add_query_arg( array( 'view' => 'changelog' ) );

		if ( $action != 'new' && $do_meta != 'edit' && $do_meta != 'clone' && $view != 'addons' && $view != 'changelog' ) {
			?>
			<div class="ppom-manage-fields-topbar d-flex">
				<h1 class="ppom-heading-style"><?php esc_html_e('PPOM Field Groups', 'woocommerce-product-addon'); ?></h1>
				<div class="ppom-top-nav">
					<a id="ppom-all-addons" class="mr-3" href="<?php echo esc_url($addons); ?>">+ <?php esc_html_e( 'All Addons', 'woocommerce-product-addon' ); ?></a>
					<a id="ppom-all-addons" class="mr-3" href="<?php echo esc_url($changelog_url); ?>"><?php esc_html_e( 'Changelog', 'woocommerce-product-addon' ); ?></a>
					<a  href="<?php echo esc_url($ppom_settings_url); ?>"><?php esc_html_e('General Settings', 'woocommerce-product-addon'); ?></a>
				</div>
			</div>
			<?php
			echo '<p>' . __( 'You can create different meta groups for different products.', 'woocommerce-product-addon' ) . '</p>';
		}

		if ( ( isset( $_REQUEST ['productmeta_id'] ) && $_REQUEST ['do_meta'] == 'edit' ) || $action == 'new' ) {
			ppom_load_template( 'admin/ppom-fields.php' );
		} elseif ( isset( $_REQUEST ['do_meta'] ) && $_REQUEST ['do_meta'] == 'clone' ) {

			$this->clone_product_meta( intval( $_REQUEST ['productmeta_id'] ) );
		} elseif ( isset( $_REQUEST ['page'] ) && $_REQUEST ['page'] == 'ppom' && $view === 'addons' ) {

			ppom_load_template( 'admin/addons-list.php' );
		} elseif ( isset( $_REQUEST ['page'] ) && $_REQUEST ['page'] == 'ppom' && $view === 'changelog' ) {

			require_once PPOM_PATH . '/backend/changelog_handler.php';
			ppom_load_template( 'admin/changelog.php' );
		} else {
			do_action( 'ppom_pdf_setting_action' );
			do_action( 'ppom_enquiryform_setting_action' );


		}

		// existing meta group tables show only ppom main page
		if ( $action != 'new' && $do_meta != 'edit' && $view != 'addons' && $view != 'changelog' ) {
			ppom_load_template( 'admin/existing-meta.php' );
		}

		echo '</div>';
	}

	/*
	 * Get Products
	*/
	function get_products() {

		if ( ! ppom_security_role() ) {
			_e( 'Sorry, you are not allowed to perform this action', 'woocommerce-product-addon' );
			die( 0 );
		}

		global $wpdb;

		$all_product_data = $wpdb->get_results( 'SELECT ID,post_title FROM `' . $wpdb->prefix . "posts` where post_type='product' and post_status = 'publish'" );

		$ppom_id = intval( $_GET['ppom_id'] );

		ob_start();
		$vars = array(
			'product_list' => $all_product_data,
			'ppom_id'      => $ppom_id,
		);
		ppom_load_template( 'admin/products-list.php', $vars );

		$list_html = ob_get_clean();

		echo ppom_esc_html( $list_html );

		die( 0 );
	}

	/**
	 * Ajax handler for "Attach to Product" feature.
	 * Attaches products to the PPOM Field Group.
	 * Removes prodducts from the PPOM Field Group.
	 *
	 * @return void
	 */
	function ppom_attach_ppoms() {

		if ( ! isset( $_POST['ppom_attached_nonce'] )
			 || ! wp_verify_nonce( $_POST['ppom_attached_nonce'], 'ppom_attached_nonce_action' )
			 || ! ppom_security_role()
		) {
			$response = array(
				'status'  => 'error',
				'message' => __( 'Sorry, you are not allowed to perform this action please try again', 'woocommerce-product-addon' ),
			);

			wp_send_json( $response );
		}

		// wp_send_json($_POST);
		$response = array();

		$ppom_id = intval( $_POST['ppom_id'] );

		$ppom_udpated = 0;
		// Reset
		if ( isset( $_POST['ppom_removed'] ) ) {

			foreach ( $_POST['ppom_removed'] as $product_id ) {

				delete_post_meta( intval( $product_id ), PPOM_PRODUCT_META_KEY );
				$ppom_udpated ++;
			}
		}

		if ( isset( $_POST['ppom_attached'] ) ) {

			foreach ( $_POST['ppom_attached'] as $product_id ) {

				update_post_meta( intval( $product_id ), PPOM_PRODUCT_META_KEY, $ppom_id );
				$ppom_udpated ++;
			}
		}

		$response = array(
			'message' => "PPOM updated for {$ppom_udpated} Products",
			'status'  => 'success',
		);

		wp_send_json( $response );
	}


	/*
	 * Plugin Validation
	*/
	function validate_plugin() {

		echo '<div class="wrap">';
		echo '<h2>' . __( 'Provide API key below:', 'woocommerce-product-addon' ) . '</h2>';
		echo '<p>' . __( 'If you don\'t know your API key, please login into your: <a target="_blank" href="http://wordpresspoets.com/member-area">Member area</a>', 'woocommerce-product-addon' ) . '</p>';

		echo '<form onsubmit="return validate_api_wooproduct(this)">';
		echo '<p><label id="plugin_api_key">' . __( 'Entery API key', 'woocommerce-product-addon' ) . ':</label><br /><input type="text" name="plugin_api_key" id="plugin_api_key" /></p>';
		wp_nonce_field();
		echo '<p><input type="submit" class="button-primary button" name="plugin_api_key" /></p>';
		echo '<p id="nm-sending-api"></p>';
		echo '</form>';

		echo '</div>';
	}

	public static function add_settings_tab( $settings_tabs ) {

		if ( current_user_can( 'manage_options' ) ) {
			$settings_tabs['ppom_settings'] = __('PPOM Settings', 'woocommerce-product-addon');
		}

		return $settings_tabs;
	}

	function settings_tab() {

		if ( current_user_can( 'manage_options' ) ) {
			woocommerce_admin_fields(ppom_array_settings());
		}

	}

	function save_settings() {

		if ( current_user_can( 'manage_options' ) ) {
			woocommerce_update_options(ppom_array_settings());
		}
	}

	function ppom_setting_wpml( $value, $option, $raw_value ) {

		if ( isset( $option['type'] ) && isset( $option['type'] ) == 'text' ) {
			$value = ppom_wpml_translate( $value, 'PPOM' );
		}

		return $value;
	}

	function ppom_tabs_custom_style() {
		?>
		<style>
			#woocommerce-product-data .ppom_extra_options_panel label {
				margin: 0 !important;
			}

			/* PPOM Meta in column */
			th.column-ppom_meta {
				width: 10% !important;
			}
		</style>
		<?php
	}

	function ppom_multi_select_role_setting( $value ) {
		$selections = get_option( $value['id'] ) ? get_option( $value['id'] ) : 'administrator';


		if ( ! empty( $value['options'] ) ) {
			$selected_roles = $value['options'];
		} else {
			$selected_roles = array( 'administrator' => 'Administrator' );
		}

		asort( $selected_roles );


		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <span
							class="woocommerce-help-tip" data-tip="<?php echo $value['desc']; ?>"></span></label>
			</th>
			<td class="forminp">
				<select multiple="multiple" name="<?php echo esc_attr( $value['id'] ); ?>[]" style="width:350px"
						data-placeholder="<?php esc_attr_e( 'Choose Roles', 'woocommerce-product-addon' ); ?>"
						aria-label="<?php esc_attr_e( 'Roles', 'woocommerce-product-addon' ); ?>" class="wc-enhanced-select">
					<?php
					if ( ! empty( $selected_roles ) ) {
						foreach ( $selected_roles as $key => $val ) {
							echo '<option value="' . esc_attr( $key ) . '"' . wc_selected( $key, $selections ) . '>' . esc_html( $val ) . '</option>'; // WPCS: XSS ok.
						}
					}
					?>
				</select> <br/><a class="select_all button" href="#"><?php esc_html_e( 'Select all', 'woocommerce-product-addon' ); ?></a> <a
						class="select_none button" href="#"><?php esc_html_e( 'Select none', 'woocommerce-product-addon' ); ?></a>
			</td>
		</tr>
		<?php

	}


}
