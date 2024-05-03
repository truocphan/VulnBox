<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'fea_admin_tools' ) ) :

	class fea_admin_tools {



		/**
		 *
		 *
		 * @var array Contains an array of admin tool instances
		 */
		var $tools = array();


		/**
		 *
		 *
		 * @var string The active tool
		 */
		var $active = '';


		/**
		 *  register_tool
		 *
		 *  This function will store a tool tool class
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  string $class
		 * @return n/a
		 */

		function register_tool( $class ) {
			$instance                       = new $class();
			$this->tools[ $instance->name ] = $instance;

		}


		/**
		 *  get_tool
		 *
		 *  This function will return a tool tool class
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  string $name
		 * @return n/a
		 */

		function get_tool( $name ) {
			return isset( $this->tools[ $name ] ) ? $this->tools[ $name ] : null;

		}


		/**
		 *  get_tools
		 *
		 *  This function will return an array of all tools
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return array
		 */

		function get_tools() {
			return $this->tools;

		}




		/**
		 *  load
		 *
		 *  description
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function load() {
			// disable filters (default to raw data)
			acf_disable_filters();

			// include tools
			$this->include_tools();

			// check submit
			$this->check_submit();

			// load acf scripts
			acf_enqueue_scripts();

		}


		/**
		 *  include_tools
		 *
		 *  description
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function include_tools() {
			// include
			acf_include( 'includes/admin/tools/class-acf-admin-tool.php' );
			acf_include( 'includes/admin/tools/class-acf-admin-tool-export.php' );
			acf_include( 'includes/admin/tools/class-acf-admin-tool-import.php' );

			// action
			do_action( 'acf/include_admin_tools' );

		}


		/**
		 *  check_submit
		 *
		 *  description
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function check_submit() {
			// loop
			foreach ( $this->get_tools() as $tool ) {

				// load
				$tool->load();

				// submit
				if ( acf_verify_nonce( $tool->name ) ) {
					$tool->submit();
				}
			}

		}


		/**
		 *  html
		 *
		 *  description
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function html() {
			// vars
			$screen = get_current_screen();
			$active = acf_maybe_get_GET( 'tool' );
			// view
			$view = array(
				'screen_id' => $screen->id,
				'active'    => $active,
			);

			// register metaboxes
			foreach ( $this->get_tools() as $tool ) {

				// check active
				if ( $active && $active !== $tool->name ) {
					continue;
				}

				// add metabox
				add_meta_box( 'acf-admin-tool-' . $tool->name, acf_esc_html( $tool->title ), array( $this, 'metabox_html' ), $screen->id, 'normal', 'default', array( 'tool' => $tool->name ) );

			}

			// view
			acf_get_view( 'tools/tools', $view );

		}


		/**
		 *  meta_box_html
		 *
		 *  description
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function metabox_html( $post, $metabox ) {
			// vars
			$tool = $this->get_tool( $metabox['args']['tool'] );

			?>
		<form method="post">
			<?php $tool->html(); ?>
			<?php echo '<input type="hidden" name="_acf_nonce" value="' . wp_create_nonce( $tool->name ) . '" />'; ?>
		</form>
			<?php

		}

		function load_tools() {
			if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'fea-settings' ) {
				if ( ! empty( $_GET['tab'] ) && $_GET['tab'] == 'tools' ) {
					$this->load();
				}
			}
		}

		function __construct() {
			add_action( 'admin_init', array( $this, 'load_tools' ) );
		}

	}

	// initialize
	fea_instance()->admin_tools = new fea_admin_tools();

endif; // class_exists check

?>
