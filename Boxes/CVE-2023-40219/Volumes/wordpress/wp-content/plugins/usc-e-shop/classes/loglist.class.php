<?php
/**
 * Log List table class.
 *
 * @since 2.7.4
 * @package  Welcart
 * @author   Collne Inc.
 * @since    2.7.4
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Log_List_Table
 */
class Log_List_Table extends WP_List_Table {
	/**
	 * The "usces_admin_log" database table
	 *
	 * @since 2.7.4
	 * @access private
	 * @var string $admin_log_table
	 */
	private $admin_log_table = 'usces_admin_log';

	/**
	 * The mode: all, order, member. Default: all
	 *
	 * @since 2.7.4
	 * @access private
	 * @var string $mode
	 */
	private $mode = 'all';

	/**
	 * Amount items are display per each page. Default: 50
	 *
	 * @since 2.7.4
	 * @access private
	 * @var integer $items_per_page
	 */
	private $items_per_page = 50;

	/**
	 * The view mode: compact view or expanded view. Default: compact view
	 *
	 * @since 2.7.4
	 * @access private
	 * @var string $view_mode
	 */
	private $view_mode = 'compact_view';

	/**
	 * The loglist screen id.
	 *
	 * @since 2.7.4
	 * @access private
	 * @var string $screen_id
	 */
	public $screen_id = '';

	/**
	 * The base info
	 *
	 * @since 2.7.4
	 * @access private
	 * @var array
	 */
	private $base_info = array();

	/**
	 * Override: WP_List_Table::__construct()
	 *
	 * @since 2.7.4
	 * @param string $mode The mode.
	 */
	public function __construct( $mode = 'all' ) {
		global $wpdb;

		$this->mode            = $mode;
		$this->base_info       = $this->get_base_info();
		$this->admin_log_table = $wpdb->prefix . $this->admin_log_table;
		$this->populate_screen_options();

		parent::__construct();
	}

	/**
	 * Create the instance of the "Log_List_Table" class.
	 *
	 * @since 2.7.4
	 * @access public
	 */
	public static function get_instance() {
		return new self();
	}

	/**
	 * Create the admin submenu under the "usces_orderlist" menu
	 *
	 * @since 2.7.4
	 * @access public
	 */
	public static function add_submenu_page() {
		if ( self::is_enabled() ) {
			$log_list            = self::get_instance();
			$admin_log_screen_id = add_submenu_page(
				'usces_orderlist',
				__( 'Operation log', 'usces' ),
				__( 'Operation log', 'usces' ),
				'level_5',
				'usces_loglist',
				array( $log_list, 'display' )
			);

			$log_list->screen_id = $admin_log_screen_id;
			add_action( "load-{$admin_log_screen_id}", array( $log_list, 'add_screen_options' ) );
			add_filter( 'screen_settings', array( $log_list, 'display_screen_options' ), 10, 2 );
		}
	}

	/**
	 * Implementation of the hook "set_screen_option_{option}"
	 * Handle saving the log screen options.
	 *
	 * @see includes/default_filter.php: add_filter('set_screen_option_usces_admin_log_screen_options')
	 * @since 2.7.4
	 * @access public
	 * @param string $status The status.
	 * @param string $option The option name.
	 * @param string $value The option value.
	 * @return array The screen option.
	 */
	public static function set_screen_options( $status, $option, $value ) {
		$usces_admin_log_options = filter_input_array(
			INPUT_POST,
			array(
				'usces_admin_log_screen_options' => array(
					'view_mode'      => array(
						'filter' => FILTER_DEFAULT,
					),
					'items_per_page' => array(
						'filter' => FILTER_VALIDATE_INT,
					),
					'flags'          => FILTER_REQUIRE_ARRAY,
				),
			)
		);

		return $usces_admin_log_options['usces_admin_log_screen_options'];
	}

	/**
	 * Add the meta box on the editing member screen.
	 *
	 * @since 2.7.4
	 * @access public
	 * @param string $member_action The member action.
	 */
	public static function add_member_meta_box( $member_action ) {
		if ( self::is_member_metabox_enabled() ) {
			$allowed_member_actions = array( 'editpost', 'newpost', 'edit' );
			if ( in_array( $member_action, $allowed_member_actions, true ) ) {
				$log_list = new self( 'member' );
				add_meta_box( 'member-logs', __( 'Operation log', 'usces' ), array( $log_list, 'display' ), 'member', 'edit' );
			}
		}
	}

	/**
	 * Add the meta box on the editing order screen.
	 *
	 * @since 2.7.4
	 * @access public
	 * @param string $order_action The order action.
	 * @return void
	 */
	public static function add_order_meta_box( $order_action ) {
		if ( self::is_order_metabox_enabled() ) {
			$allowed_order_actions = array( 'editpost', 'newpost', 'edit' );
			if ( in_array( $order_action, $allowed_order_actions, true ) ) {
				$log_list = new self( 'order' );
				add_meta_box( 'order-logs', __( 'Operation log', 'usces' ), array( $log_list, 'display' ), 'order', 'edit' );
			}
		}
	}

	/**
	 * Clear up the admin log by the period set.
	 *
	 * @since 2.7.4
	 * @see functions\utility.php: usces_cron_do()
	 * @access public
	 * @return void
	 */
	public static function clearup() {
		if ( self::is_enabled() ) {
			global $usces, $wpdb;
			$period              = '-' . self::get_retention_period();
			$period_ago_datetime = get_date_from_gmt( gmdate( 'Y-m-d 00:00:00', strtotime( $period ) ) );
			$table               = $wpdb->prefix . 'usces_admin_log';
			$res                 = $wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$table} WHERE `datetime` < %s",
					$period_ago_datetime
				)
			);
		}
	}

	/**
	 * Override: WP_List_Table::display()
	 *
	 * @since 2.7.4
	 */
	public function display() {
		$this->enqueue_script();
		$this->enqueue_style();

		$this->prepare_items();

		if ( $this->is_all_mode() ) {
			$total_items = $this->count_logs();
			$per_page    = $this->items_per_page;
			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'per_page'    => $per_page,
				)
			);
		}

		$this->set_anchor();
		$wrapper_ele_id = "{$this->mode}-log-table";
		?>
		<div class="wrap" id="<?php echo esc_attr( $wrapper_ele_id ); ?>">
			<div class="usces_admin">
				<?php if ( $this->is_all_mode() ) : ?>
					<h1>Welcart Management <?php _e( 'Operation log', 'usces' ); ?></h1>
					<p class="version_info">Version <?php echo esc_html( USCES_VERSION ); ?></p>
				<?php endif; ?>
				<?php $this->start_form(); ?>
				<?php parent::display(); ?>
				<?php $this->end_form(); ?>
			</div>
		</div>
			<?php
	}

	/**
	 * Implementation of the hook "load-{screen_id}"
	 * Add the screen options for the log list page.
	 *
	 * @since 2.7.4
	 * @access public
	 */
	public function add_screen_options() {
		add_screen_option(
			'items_per_page',
			array(
				'label'   => __( 'Number of items per page:' ),
				'default' => $this->items_per_page,
				'option'  => 'items_per_page',
			)
		);

		add_screen_option(
			'view_mode',
			array(
				'label'   => __( 'View mode' ),
				'default' => $this->view_mode,
				'option'  => 'view_mode',
			)
		);
	}

	/**
	 * Implementation of the hook "screen_settings"
	 * Render the html markup of the screen options in the log list page.
	 *
	 * @since 2.7.4
	 * @access public
	 * @param string $screen_settings The screen settings.
	 * @param Object $current_screen The Wp_Screen.
	 * @return string The html markup.
	 */
	public function display_screen_options( $screen_settings, $current_screen ) {
		if ( $this->screen_id === $current_screen->id ) {
			$options = $current_screen->get_options();
			ob_start();
			?>
		<div class="metabox-prefs usces-admin-log-screen-options">
				<input type="hidden" name="wp_screen_options[option]" value="usces_admin_log_screen_options" />
				<input type="hidden" name="wp_screen_options[value]" value="yes" />
				<fieldset class="screen-options">
					<legend><?php esc_html_e( 'Pagination' ); ?></legend>
					<label for="items_per_page"><?php echo esc_html( $options['items_per_page']['label'] ); ?></label>
					<input type="number" step="1" min="1" max="999" class="items-per-page" name="usces_admin_log_screen_options[items_per_page]" id="items_per_page" maxlength="3" value="<?php echo esc_attr( $options['items_per_page']['default'] ); ?>">
				</fieldset>
				<fieldset class="screen-options">
					<legend><?php echo esc_html( $options['view_mode']['label'] ); ?></legend>
					<input type="radio" class="view-mode" value="compact_view" id="compact_view" name="usces_admin_log_screen_options[view_mode]" <?php checked( $options['view_mode']['default'], 'compact_view' ); ?>/><label for="compact_view"><?php esc_html_e( 'Compact view' ); ?></label>
					<input type="radio" class="view-mode" value="expanded_view" id="expanded_view" name="usces_admin_log_screen_options[view_mode]" <?php checked( $options['view_mode']['default'], 'expanded_view' ); ?>/><label for="expanded_view"> <?php esc_html_e( 'Extended view' ); ?></label>
				</fieldset>
		</div><!-- metabox-prefs -->
		<br class="clear">
			<?php
			$button = get_submit_button( __( 'Apply' ), 'button button-primary', 'screen-options-apply', false );
			echo $button; // already escaped.
			return ob_get_clean();
		} else {
			return $screen_settings;
		}
	}

	/**
	 * Override: WP_List_Table::get_columns()
	 *
	 * @since 2.7.4
	 * @return array List of columns.
	 */
	public function get_columns() {
		$columns = array(
			'ID'        => __( 'ID', 'usces' ),
			'author'    => __( 'Author', 'usces' ),
			'datetime'  => __( 'Datetime', 'usces' ),
			'action'    => __( 'Action', 'usces' ),
			'screen'    => __( 'Screen', 'usces' ),
			'entity_id' => __( 'Entity', 'usces' ),
			'message'   => __( 'Message', 'usces' ),
		);

		return $columns;
	}

	/**
	 * Override: WP_List_Table::prepare_items()
	 *
	 * @since 2.7.4
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$logs                  = $this->get_logs();
		$this->items           = array();
		foreach ( $logs as $log ) {
			$this->items[] = array(
				'ID'        => $log->ID,
				'author'    => $log->author,
				'message'   => $log->message,
				'screen'    => $log->screen,
				'entity_id' => $log->entity_id,
				'action'    => $log->action,
				'data'      => $log->data,
				'datetime'  => $log->datetime,
			);
		}
	}

	/**
	 * Override: WP_List_Table::extra_tablenav()
	 *
	 * @since 2.7.4
	 * @param string $which The top or bottom.
	 */
	protected function extra_tablenav( $which ) {
		if ( $this->is_all_mode() ) {
			$this->display_search_form();
		}
	}

	/**
	 * Override: WP_List_Table::column_default()
	 *
	 * @since 2.7.4
	 * @param array  $item The item.
	 * @param string $column_name The column name.
	 * @return string $output The output.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'message':
				$output  = $item[ $column_name ];
				$output .= $this->display_data_popup( $item );
				break;
			case 'author':
				$author_login = $item[ $column_name ];
				$author       = get_user_by( 'login', $author_login );
				$author_url   = get_edit_user_link( $author->ID );
				$output       = "<a href='{$author_url}' title='{$author_login}'  target='_blank'>{$author_login}</a>";
				break;
			case 'entity_id':
				$entity_id = $item['entity_id'];
				$deleted   = 'delete' === $item['action'];
				if ( $this->is_order_mode() || $this->is_member_mode() || $deleted ) {
					$output = $entity_id;
				} else {
					$edit_entity_url = '#';
					if ( $this->is_order_screen( $item['screen'] ) ) {
						$edit_entity_url = $this->get_edit_order_url( $entity_id );
					}
					if ( $this->is_member_screen( $item['screen'] ) ) {
						$edit_entity_url = $this->get_edit_member_url( $entity_id );
					}
					$output = "<a href='{$edit_entity_url}' title='{$entity_id}' target='_blank'>{$entity_id}</a>";
				}
				break;
			case 'screen':
				$output = $this->get_display_label( $item[ $column_name ] );
				break;
			case 'action':
				$output = $this->get_display_label( $item[ $column_name ] );
				break;
			default:
				$output = $item[ $column_name ];
		}

		return $output;
	}

	/**
	 * Override: WP_List_Table::get_sortable_columns().
	 *
	 * @since 2.7.4
	 * @return array $sortable_columns The sortable columns.
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'ID'        => array( 'ID', false ),
			'author'    => array( 'author', false ),
			'screen'    => array( 'screen', false ),
			'entity_id' => array( 'entity_id', false ),
			'action'    => array( 'action', false ),
			'datetime'  => array( 'datetime', false ),
		);

		return $sortable_columns;
	}

	/**
	 * Populate screen options to this instance.
	 */
	private function populate_screen_options() {
		$screen_options = get_user_meta(
			get_current_user_id(),
			'usces_admin_log_screen_options',
			array(
				'view_mode'      => 'compact',
				'items_per_page' => 50,
			)
		);

		$this->view_mode      = isset( $screen_options['view_mode'] ) ? $screen_options['view_mode'] : 'compact_view';
		$this->items_per_page = isset( $screen_options['items_per_page'] ) ? $screen_options['items_per_page'] : 50;
	}

	/**
	 * Get the display label.
	 *
	 * @access private
	 * @param string $label_key The label key.
	 * @return string The label.
	 */
	private function get_display_label( $label_key ) {
		if ( isset( $this->base_info['screens']['order'][ $label_key ] ['label'] ) ) {
			return $this->base_info['screens']['order'][ $label_key ]['label'];
		} elseif ( isset( $this->base_info['screens']['member'][ $label_key ]['label'] ) ) {
			return $this->base_info['screens']['member'][ $label_key ]['label'];
		} elseif ( isset( $this->base_info['actions'][ $label_key ]['label'] ) ) {
			return $this->base_info['actions'][ $label_key ]['label'];
		} else {
			return '';
		}
	}

	/**
	 * Check if the screent is order screen or not.
	 *
	 * @access private
	 * @param string $screen_id The screen id.
	 * @return boolean true/false
	 */
	private function is_order_screen( $screen_id ) {
		return isset( $this->base_info['screens']['order'][ $screen_id ] );
	}

	/**
	 * Check if the screent is member screen or not.
	 *
	 * @access private
	 * @param string $screen_id The screen id.
	 * @return boolean true/false
	 */
	private function is_member_screen( $screen_id ) {
		return isset( $this->base_info['screens']['member'][ $screen_id ] );
	}

	/**
	 * Get list of logs.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array $logs The logs.
	 */
	private function get_logs() {
		global $wpdb;
		$sql  = "SELECT * FROM {$this->admin_log_table}";
		$sql .= $this->get_sql_where_clause();
		$sql .= $this->get_sql_order_clause();

		if ( $this->is_all_mode() ) {
			$current_page = max( filter_input( INPUT_GET, 'paged' ), 1 );
			$from         = ( $current_page - 1 ) * $this->items_per_page;
		} else {
			$from                 = 0;
			$this->items_per_page = 1000;
		}

		$sql .= " LIMIT {$from}, {$this->items_per_page}";
		$logs = $wpdb->get_results( $sql );
		if ( ! empty( $logs ) ) {
			return $logs;
		} else {
			return array();
		}
	}

	/**
	 * Count the total of logs.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return integer $total The total.
	 */
	private function count_logs() {
		global $wpdb;

		$sql  = "SELECT COUNT(id) FROM {$this->admin_log_table}";
		$sql .= $this->get_sql_where_clause();

		$total = $wpdb->get_var( $sql );
		return $total;
	}

	/**
	 * Render open tag <form>
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function start_form() {
		if ( $this->is_all_mode() ) {
			echo '<form>';
		}
	}

	/**
	 * Render end tag <form>
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function end_form() {
		if ( $this->is_all_mode() ) {
			echo '</form>';
		}
	}

	/**
	 * Generate the SQL order clause.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return string $sql The SQL order clause.
	 */
	private function get_sql_order_clause() {
		$sql     = '';
		$orderby = filter_input( INPUT_GET, 'orderby' );
		$order   = filter_input( INPUT_GET, 'order' );

		if ( empty( $orderby ) ) {
			$orderby = 'ID';
		}

		if ( empty( $order ) ) {
			$order = 'DESC';
		}
		$sql = ' ORDER BY ' . $orderby . ' ' . $order;

		return $sql;
	}

	/**
	 * Generate the SQL where clause.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return string $sql The SQL where clause.
	 */
	private function get_sql_where_clause() {
		$sql = '';
		if ( $this->is_all_mode() ) {
			$screen    = filter_input( INPUT_GET, 'usces_screen' );
			$authors   = filter_input( INPUT_GET, 'usces_author' );
			$action    = filter_input( INPUT_GET, 'usces_action' );
			$datetime  = filter_input( INPUT_GET, 'usces_datetime' );
			$entity_id = filter_input( INPUT_GET, 'usces_entity_id' );
			$where     = array();
			if ( ! empty( $authors ) ) {
				$authors = explode( ', ', trim( $authors, ', ' ) );
				$authors = '"' . implode( '","', $authors ) . '"';
				$where[] = 'author IN (' . $authors . ')';
			}

			if ( ! empty( $action ) ) {
				$where[] = 'action="' . $action . '"';
			}

			if ( ! empty( $screen ) ) {
				$where[] = 'screen="' . $screen . '"';
			}

			if ( ! empty( $datetime ) ) {
				$datetime = trim( $datetime );
				$where[]  = 'datetime LIKE "' . $datetime . '%"';
			}

			if ( ! empty( $entity_id ) ) {
				$entity_id = trim( $entity_id );
				$where[]   = 'entity_id = "' . $entity_id . '"';
			}
		} elseif ( $this->is_order_mode() ) {
			$order_id      = $this->get_context_entity_id( 'order_id' );
			$order_screens = '"' . implode( '","', $this->get_order_screen_ids() ) . '"';
			$where[]       = 'screen IN (' . $order_screens . ')';
			$where[]       = 'entity_id = "' . $order_id . '"';
		} elseif ( $this->is_member_mode() ) {
			$member_id      = $this->get_context_entity_id( 'member_id' );
			$member_screens = '"' . implode( '","', $this->get_member_screen_ids() ) . '"';
			$where[]        = 'screen IN (' . $member_screens . ')';
			$where[]        = 'entity_id = "' . $member_id . '"';
		}

		if ( ! empty( $where ) ) {
			$sql .= ' WHERE ' . implode( ' AND ', $where );
		}

		return $sql;
	}

	/**
	 * Get entity id on the editing entity screen.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param string $name The name of variable.
	 * @return integer $order_id The SQL where clause.
	 */
	private function get_context_entity_id( $name ) {
		$entity_id = filter_input( INPUT_GET, $name );
		if ( empty( $order_id ) ) {
			$order_id = ( ! empty( $_REQUEST[ $name ] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $name ] ) ) : '';
		}

		return $order_id;
	}

	/**
	 * Set anchor.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function set_anchor() {
		$anchor                 = "#{$this->mode}-log-table";
		$_SERVER['REQUEST_URI'] = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) . $anchor;
	}

	/**
	 * Check if the mode is member mode or not
	 *
	 * @since 2.7.4
	 * @access private
	 * @return boolean true/false
	 */
	private function is_member_mode() {
		return 'member' === $this->mode;
	}

	/**
	 * Check if the mode is order mode or not
	 *
	 * @since 2.7.4
	 * @access private
	 * @return boolean true/false
	 */
	private function is_order_mode() {
		return 'order' === $this->mode;
	}

	/**
	 * Check if the mode is all mode or not
	 *
	 * @since 2.7.4
	 * @access private
	 * @return boolean true/false
	 */
	private function is_all_mode() {
		return 'all' === $this->mode;
	}

	/**
	 * Generate the edit order link.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param integer $order_id The order id.
	 * @return string $url The edit order link.
	 */
	private function get_edit_order_url( $order_id ) {
		$url = USCES_ADMIN_URL . "?page=usces_orderlist&order_action=edit&order_id={$order_id}&wc_nonce=" . wp_create_nonce( 'order_list' );
		return $url;
	}

	/**
	 * Generate the edit order link.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param integer $member_id The order id.
	 * @return string $url The edit order link.
	 */
	private function get_edit_member_url( $member_id ) {
		$url = USCES_ADMIN_URL . "?page=usces_memberlist&member_action=edit&member_id={$member_id}";
		return $url;
	}

	/**
	 * Display data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $item The log.
	 * @return string $output The output.
	 */
	private function display_data_popup( $item ) {
		$output        = '';
		$data          = maybe_unserialize( $item['data'] );
		$compared_mode = ! empty( $data['differed'] );
		$item['mode']  = ( $compared_mode ) ? 'diff' : 'all';
		$diff_ele_id   = 'log-dialog-' . $item['ID'];
		$output       .= '<p><a href="javascript:void(0);" class="log-dialog-button" for="' . $diff_ele_id . '">' . __( 'More Details' ) . '</a></p>';
		$output       .= '<div style="display:none;" class="log-dialog" id="' . $diff_ele_id . '" title="' . __( 'Detailed information', 'usces' ) . '">';
		$output       .= $this->display_control_buttons( $item );

		$display_table = '<table width="90%" align="center" class="compared-table">';
		if ( $compared_mode ) {
			$display_table .= '<tr><th>' . __( 'Field', 'usces' ) . '</th><th>' . __( 'Before', 'usces' ) . '</th><th>' . __( 'After', 'usces' ) . '</th></tr>';
		} else {
			$deleled        = 'delete' === $item['action'];
			$label          = ( $deleled ) ? __( 'Before', 'usces' ) : __( 'After', 'usces' );
			$display_table .= '<tr><th>' . __( 'Field', 'usces' ) . '</th><th colspan="2">' . $label . '</th></tr>';
		}

		$display_data = $data['display_data'];
		foreach ( $display_data as $key => $data_field ) {
			$data_field['compared_mode'] = $compared_mode;
			$display_table              .= $this->display_field_data( $data_field );
		}

		$display_table .= '</table></div>';
		$output        .= $display_table;
		$inline_showed  = $this->is_expanded_view_mode() && $compared_mode;
		if ( $inline_showed ) {
			$output = '<div class="inline-diff"><h4>' . __( 'Detailed information', 'usces' ) . '</h4>' . $display_table . '</div>' . $output;
		}

		return $output;
	}

	/**
	 * Check if the view mode is expanded or not.
	 *
	 * @return boolean true/false.
	 */
	private function is_expanded_view_mode() {
		return 'expanded_view' === $this->view_mode;
	}

	/**
	 * Display the control button.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $item The log.
	 * @return string $output The output.
	 */
	private function display_control_buttons( $item ) {
		$display_log_mode_id = 'display_log_mode_' . $item['ID'];
		$screen              = $item['screen'];
		$output              = '<fieldset class="display-control"><legend>' . __( 'Display control', 'usces' ) . '</legend>';
		$output             .= '<table width="90%" align="center" class="control-table">';
		$output             .= '<tr><th>' . __( 'Mode', 'usces' ) . '</th>';
		$output             .= '<td><label><input type="radio" name="' . $display_log_mode_id . '" value="all" ' . checked( 'all', $item['mode'], false ) . '/>' . __( 'All', 'usces' ) . '</label></td>';
		$output             .= '<td><label><input type="radio" name="' . $display_log_mode_id . '" value="diff" ' . checked( 'diff', $item['mode'], false ) . ' />' . __( 'Only changes', 'usces' ) . '</label></td></tr>';
		$output             .= '<tr><th>' . __( 'Fields', 'usces' ) . '</th>';
		if ( $this->is_order_screen( $screen ) ) {
			$output .= '<td>';
			$output .= '<label><input type="checkbox" name="group" value="other" checked />' . __( 'Other', 'usces' ) . '</label><br>';
			$output .= '<label><input type="checkbox" value="order_cart" name="group" checked />' . __( 'Cart', 'usces' ) . '</label>';
			$output .= '</td><td>';
			$output .= '<label><input type="checkbox" value="custom_order" name="group" checked />' . __( 'Custom order field', 'usces' ) . '</label><br>';
			$output .= '<label><input type="checkbox" value="custom_customer" name="group" checked />' . __( 'Custom customer field', 'usces' ) . '</label><br>';
			$output .= '<label><input type="checkbox" value="custom_delivery" name="group" checked />' . __( 'Custom delivery field', 'usces' ) . '</label><br>';
			$output .= '<label><input type="checkbox" value="payment_transaction_logs" name="group" checked />' . __( 'Payment information', 'usces' ) . '</label><br>';
			$output .= '</td>';
		} elseif ( $this->is_member_screen( $screen ) ) {
			$output .= '<td>';
			$output .= '<label><input type="checkbox" name="group" value="other" checked />' . __( 'Other', 'usces' ) . '</label><br>';
			$output .= '<label><input type="checkbox" value="custom_member" name="group" checked />' . __( 'Custom member field', 'usces' ) . '</label>';
			$output .= '</td><td>';
			$output .= '<label><input type="checkbox" value="custom_admin_member" name="group" checked />' . __( 'Admin custom field', 'usces' ) . '</label><br>';
			$output .= '</td>';
		}
		$output .= '</tr>';
		$output .= '</table>';
		$output .= '</fieldset><p></p>';
		return $output;
	}

	/**
	 * Render the field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field_data The field data.
	 * @return string $output The output.
	 */
	private function display_field_data( $field_data ) {
		$output            = '';
		$is_repeater_field = ! empty( $field_data['is_repeater'] );
		$is_group_field    = ! empty( $field_data['is_group'] );

		if ( $is_repeater_field ) {
			$output .= $this->display_repeater_field( $field_data );
		} elseif ( $is_group_field ) {
			$output .= $this->display_group_field( $field_data );
		} else {
			$output .= $this->display_plain_field( $field_data );
		}

		return $output;
	}

	/**
	 * Get classes for the field row.
	 *
	 * @param boolean $is_diff The field is different or not.
	 * @param string  $group The field group.
	 * @return array $classes The list of classes.
	 */
	private function get_row_classes( $is_diff = false, $group = '' ) {
		$classes = array();

		if ( ! empty( $group ) ) {
			$classes[] = $group;
		}

		if ( $is_diff ) {
			$classes[] = 'different';
		} else {
			$classes[] = 'same-row';
			$classes[] = 'log-hidden';
		}

		return $classes;
	}

	/**
	 * Render the repeater field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field_data The field data.
	 * @return string $output The output.
	 */
	private function display_repeater_field( $field_data ) {
		$is_diff          = ! empty( $field_data['is_diff'] );
		$group            = ! empty( $field_data['field']['group'] ) ? $field_data['field']['group'] : '';
		$label            = $field_data['field']['label'];
		$compared_mode    = ! empty( $field_data['compared_mode'] );
		$is_key_and_value = ! empty( $field_data['is_key_and_value'] );

		$output = $this->display_header_row( $label, $is_diff, $group );

		$rows  = ! empty( $field_data['rows'] ) ? $field_data['rows'] : array();
		$index = 1;
		foreach ( $rows as $row ) {
			$has_row_label = isset( $field_data['field']['row_label'] );
			if ( $has_row_label ) {
				$is_row_diff = false;
				if ( $is_key_and_value ) {
					$is_row_diff = ! empty( $row['is_diff'] );
				} else {
					foreach ( $row as $col ) {
						$is_row_diff = $is_row_diff || ! empty( $col['is_diff'] );
					}
				}
				$row_label = $field_data['field']['row_label'] . ' ' . $index;

				$output .= $this->display_header_row( $row_label, $is_row_diff, $group );
			}

			if ( $is_key_and_value ) {
				$row['compared_mode'] = $compared_mode;
				$output              .= $this->display_field_data( $row );
			} else {
				foreach ( $row as $key => $col ) {
					if ( ! empty( $col ) ) {
						$col['compared_mode'] = $compared_mode;
					}

					$output .= $this->display_field_data( $col );
				}
			}

			$index++;
		}

		return $output;
	}

	/**
	 * Display header row for the field.
	 *
	 * @param string  $label The label.
	 * @param boolean $is_diff The field is different or not.
	 * @param string  $group The field group.
	 * @return string The html of the header.
	 */
	private function display_header_row( $label, $is_diff, $group = '' ) {
		$output = '';
		if ( ! empty( $label ) ) {
			$classes   = $this->get_row_classes( $is_diff, $group );
			$row_class = implode( ' ', $classes );
			$style     = ( $is_diff ) ? '' : ' style="display:none;"';
			$output    = '<tr class="' . esc_attr( $row_class ) . '"><td colspan="3"><h3>' . esc_html( $label ) . '</h3></tr>';
		}

		return $output;
	}

	/**
	 * Render the plain field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field_data The field data.
	 * @return string $output The output.
	 */
	private function display_plain_field( $field_data ) {
		if ( empty( $field_data ) ) {
			return '';
		} else {
			$compared_mode = ! empty( $field_data['compared_mode'] );
			$group         = ! empty( $field_data['field']['group'] ) ? $field_data['field']['group'] : '';
			$is_diff       = ! empty( $field_data['is_diff'] );
			$row_classes   = $this->get_row_classes( $is_diff, $group );
			$classes       = implode( ' ', $row_classes );
			$label         = ( ! empty( $field_data['field']['label'] ) ) ? $field_data['field']['label'] : '';

			$output  = '<tr class="' . esc_attr( $classes ) . '">';
			$output .= '<th width="20%">' . esc_html( $label ) . '</th>';
			if ( $compared_mode ) {
				$output .= '<td>' . esc_html( $field_data['before'] ) . '</td>';
				$output .= '<td>' . esc_html( $field_data['after'] ) . '</td>';
			} else {
				$field_value = ( ! empty( $field_data['after'] ) ) ? $field_data['after'] : $field_data['before'];
				$output     .= '<td colspan="2">' . esc_html( $field_value ) . '</td>';
			}

			$output .= '</tr>';

			return $output;
		}
	}

	/**
	 * Render the group field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field_data The field data.
	 * @return string $output The output.
	 */
	private function display_group_field( $field_data ) {
		$output = '';

		if ( ! empty( $field_data['field']['label'] ) ) {
			$is_diff = ! empty( $field_data['is_diff'] );
			$group   = ! empty( $field_data['field']['group'] ) ? $field_data['field']['group'] : '';
			$label   = $field_data['field']['label'];

			$output .= $this->display_header_row( $label, $is_diff, $group );
		}

		$sub_fields = ! empty( $field_data['fields'] ) ? $field_data['fields'] : array();
		foreach ( $sub_fields as $sub_field_data ) {
			$sub_field_data['compared_mode'] = $field_data['compared_mode'];
			$output                         .= $this->display_field_data( $sub_field_data );
		}

		return $output;
	}

	/**
	 * The base information.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array The base info.
	 */
	private function get_base_info() {
		return array(
			'screens' => array(
				'order'  => array(
					'orderlist' => array(
						'label' => __( 'Order list screen', 'usces' ),
					),
					'ordernew'  => array(
						'label' => __( 'Order new screen', 'usces' ),
					),
					'orderedit' => array(
						'label' => __( 'Order edit screen', 'usces' ),
					),
				),
				'member' => array(
					'memberlist' => array(
						'label' => __( 'Member list screen', 'usces' ),
					),
					'membernew'  => array(
						'label' => __( 'Member new screen', 'usces' ),
					),
					'memberedit' => array(
						'label' => __( 'Member edit screen', 'usces' ),
					),
				),
			),
			'actions' => array(
				'create' => array(
					'label' => __( 'Register', 'usces' ),
				),
				'update' => array(
					'label' => __( 'Update', 'usces' ),
				),
				'delete' => array(
					'label' => __( 'Delete', 'usces' ),
				),
			),
		);
	}

	/**
	 * The list of the order screen ids.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array The order screen ids
	 */
	private function get_order_screen_ids() {
		return array_keys( $this->base_info['screens']['order'] );
	}

	/**
	 * The list of the member screen ids.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array The member screen ids
	 */
	private function get_member_screen_ids() {
		return array_keys( $this->base_info['screens']['member'] );
	}

	/**
	 * Render the search form.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function display_search_form() {
		$author    = filter_input( INPUT_GET, 'usces_author' );
		$action    = filter_input( INPUT_GET, 'usces_action' );
		$screen    = filter_input( INPUT_GET, 'usces_screen' );
		$datetime  = filter_input( INPUT_GET, 'usces_datetime' );
		$entity_id = filter_input( INPUT_GET, 'usces_entity_id' );

		$management_users = $this->get_wc_management_users();
		$order_screens    = $this->base_info['screens']['order'];
		$member_screens   = $this->base_info['screens']['member'];
		$actions          = $this->base_info['actions'];
		$cancel_url       = admin_url( 'admin.php?page=usces_loglist' );
		?>
		<div style="float:left;">
			<input type="hidden" name="page" value="usces_loglist"/>
			<select name="usces_author">
				<option value=""><?php esc_attr_e( '--Select an author--', 'usces' ); ?></option>
			<?php foreach ( $management_users as $management_user ) : ?>
					<option value="<?php echo esc_attr( $management_user ); ?>"<?php selected( $management_user, $author ); ?>>
						<?php echo esc_html( $management_user ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<select name="usces_action">
				<option value=""><?php esc_attr_e( '--Select action--', 'usces' ); ?></option>
			<?php foreach ( $actions as $key => $action_option ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $action ); ?>>
						<?php echo esc_html( $action_option['label'] ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<select name="usces_screen">
				<option value=""><?php esc_attr_e( '--Select Screen--', 'usces' ); ?></option>
			<?php foreach ( $order_screens as $key => $order_screen ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $screen ); ?>>
						<?php echo esc_html( $order_screen['label'] ); ?>
					</option>
				<?php endforeach; ?>
			<?php foreach ( $member_screens as $key => $member_screen ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $screen ); ?>>
						<?php echo esc_html( $member_screen['label'] ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<input type="text" name="usces_datetime" placeholder="<?php esc_attr_e( 'Enter a datetime', 'usces' ); ?>" value="<?php echo esc_attr( $datetime ); ?>"/>
			<input type="text" name="usces_entity_id" placeholder="<?php esc_attr_e( 'Enter an entity id', 'usces' ); ?>" value="<?php echo esc_attr( $entity_id ); ?>"/>
			<input type="button" value="<?php esc_attr_e( 'Search', 'usces' ); ?>" class="button logSearchBtn"/>
			<a href="<?php echo esc_url( $cancel_url ); ?>" class="button logSearchBtn"><?php esc_attr_e( 'Cancellation', 'usces' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Get the list of the Welcart management ussers.
	 */
	private function get_wc_management_users() {
		global $wpdb;
		$management_users      = array();
		$capabilities_meta_key = $wpdb->prefix . 'capabilities';
		$args                  = array(
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => $capabilities_meta_key,
					'value'   => 'level_5',
					'compare' => 'LIKE',
				),
				array(
					'key'     => $capabilities_meta_key,
					'value'   => 'administrator',
					'compare' => 'LIKE',
				),
			),
		);

		$user_query = new WP_User_Query( $args );
		$users      = $user_query->get_results();

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$management_users[] = $user->user_login;
			}
		}

		return $management_users;
	}

	/**
	 * Check if the order operator log function is enabled or not.
	 */
	public static function is_order_metabox_enabled() {
		return OPERATION_LOG::is_order_metabox_enabled();
	}

	/**
	 * Check if the member operator log function is enabled or not.
	 */
	public static function is_member_metabox_enabled() {
		return OPERATION_LOG::is_member_metabox_enabled();
	}

	/**
	 * Check if the operator log function is enabled or not.
	 */
	public static function is_enabled() {
		return OPERATION_LOG::is_enabled();
	}

	/**
	 * Get the retention period.
	 *
	 * @return string
	 */
	public static function get_retention_period() {
		return OPERATION_LOG::get_retention_period();
	}

	/**
	 * Render style for the log list table.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function enqueue_style() {
		?>
		<style>
			table.compared-table th, table.compared-table td {
				border: 1px solid #c3c4c7;
				word-wrap: break-word;
				min-width: 50px;
				max-width: 150px;
			}
			table.compared-table td h3 {
				text-align: center;
				font-size: medium;
				font-weight: 500;
			}
			fieldset.display-control {
				border: 1px solid #c3c4c7;
				margin: auto;
				width: 89%;
			}
			table.control-table {
				margin: auto;
				padding: 10px;
			}
			.log-hidden {
				display:none;
			}
			table.compared-table tr.different{
				color: red;
			}
			th.column-message {
				width : 35%;
			}
			.inline-diff table.compared-table tr.different td:nth-child(3){
				color: red;
			}
		</style>
		<?php
	}

	/**
	 * Render javascript for the log list table.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function enqueue_script() {
		wp_enqueue_script( 'jquery-ui-dialog' );
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function ( $ ) {
				const logListTable = {
					start: function () {
						this.initPopup();
						this.listen();
					},
					initPopup: function () {
						$( '.log-dialog' ).dialog( {
							autoOpen: false,
							show: { effect: 'blind', duration: 1000 },
							hide: { effect: 'explode', duration: 1000 },
							width: '40%',
							maxHeight: 600,
						} );
					},
					listen: function () {
						this.onClickDiffButton();
						this.onChangeSelect( 'usces_author' );
						this.onChangeSelect( 'usces_screen' );
						this.onChangeSelect( 'usces_action' );
						this.onChangeTextInput( 'usces_datetime' );
						this.onChangeTextInput( 'usces_entity_id' );
						this.onChangeTextInput( 'usces_author' );
						this.onClickSearchBtn();
						this.toggleOpenOrClose();
						this.onChangeControlButtons();
					},
					onChangeControlButtons: function () {
						const modeEles = $( 'fieldset.display-control input[name*=display_log_mode]' );
						modeEles.on( 'click', function () {
							const parentEle = $( this ).parents( '.log-dialog' );
							const mode = $( this ).val();
							parentEle
								.find( 'fieldset.display-control input[name=group]:checked' )
								.each( function () {
									let group = $( this ).val();
									let selector = 'tr.same-row.' + group;
									if ( 'all' === mode ) {
										selector += ',tr.different.' + group;
										parentEle.find( selector ).removeClass('log-hidden');
									}

									if ( 'diff' === mode ) {
										parentEle.find( selector ).addClass('log-hidden');
									}
								} );
						} );

						$( 'fieldset.display-control input[name*=display_log_mode]:checked' ).trigger(
							'click'
						);

						$( 'fieldset.display-control input[name=group]' ).on( 'change', function () {
							let eleId = 'tr.' + $( this ).val();
							const parentEle = $( this ).parents( '.log-dialog' );

							if ( $( this ).is( ':checked' ) ) {
								const isDiffMode = parentEle
									.find(
										'fieldset.display-control input[name*=display_log_mode][value=diff]'
									)
									.is( ':checked' );
								if ( isDiffMode ) {
									eleId += '.different';
								}
								parentEle.find( eleId ).removeClass('log-hidden');
							} else {
								parentEle.find( eleId ).addClass('log-hidden');
							}
						} );
					},
					toggleOpenOrClose: function () {
						$( 'span.toggle-indicator' ).on( 'click', function () {
							$( this ).parents( '.postbox' ).toggleClass( 'closed' );
							$( this )
								.parents( '.handlediv' )
								.attr( 'aria-expanded', function ( _, attr ) {
									return ! attr;
								} );
						} );
					},
					onClickSearchBtn: function () {
						$( '.logSearchBtn' ).on( 'click', function ( e ) {
							$( 'input[name=_wp_http_referer]' ).remove();
							$( 'input[name=_wpnonce]' ).remove();
							$( '#current-page-selector' ).val( 1 );
							$( this ).parents( 'form' ).submit();
						} );
					},
					onClickDiffButton: function () {
						$( '.log-dialog-button' ).on( 'click', function () {
							let a = '#' + $( this ).attr( 'for' );
							$( a ).dialog( 'open' );
						} );
					},
					onChangeSelect: function ( name ) {
						$( 'select[name="' + name + '"]' ).on( 'change', function () {
							const thisEle = $( this );
							const isTop = thisEle.parents( '.top' ).length > 0;
							const selected = thisEle.val();
							let thatEle = null;
							if ( isTop ) {
								thatEle = $(
									'.tablenav.bottom select[name="' + name + '"]'
								);
							} else {
								thatEle = $( '.tablenav.top select[name="' + name + '"]' );
							}

							thatEle.find( 'option' ).removeProp( 'selected' );
							thatEle
								.find( 'option[value="' + selected + '"]' )
								.prop( 'selected', true );
						} );
					},
					onChangeTextInput: function ( name ) {
						$( 'input[name="' + name + '"]' ).on( 'change', function () {
							const thisEle = $( this );
							const isTop = thisEle.parents( '.top' ).length > 0;
							const thisValue = thisEle.val();
							let thatEle = null;
							if ( isTop ) {
								thatEle = $(
									'.tablenav.bottom input[name="' + name + '"]'
								);
							} else {
								thatEle = $( '.tablenav.top input[name="' + name + '"]' );
							}
							thatEle.val( thisValue );
						} );
					},
				};

				logListTable.start();
			} );
		</script>
		<?php
	}
}
